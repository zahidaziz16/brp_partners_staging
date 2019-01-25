<?php

/**
 * Upay OpenCart Plugin
 * @package Payment Gateway
 * @author Upay Team
 * @version 1.0
 */
class ControllerExtensionPaymentUpay extends Controller {

    private $url_pay = 'https://www.upay2us.com/iServeGateway/transaction_window';
    private $pay_url_notication = 'https://www.upay2us.com/iServeGateway/payment_notification';
    private $upay_currency = array('MYR');

    public function index() {
        
        $this->load->language('extension/payment/upay');
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        if ($this->verify_currency($order_info['currency_code'])) {            
            return $form = $this->generate_form($order_info);
        } else {
            return '<span style="color:red">Currency does not match.Upay payment gateway supports MYR</span> ';
        }
    }

    public function generate_form($order_info) {
         $this->load->language('extension/payment/upay');
        $data['products'] = array();
        $description = "";
        foreach ($this->cart->getProducts() as $product) {

            $description .= htmlspecialchars($product['name']) . '(' . htmlspecialchars($product['model']) . ')' . ' * ' . $product['quantity'] . '--';
        }

        //Encryption method
        $signature = $this->config->get('upay_vkey') . $this->config->get('upay_mid') . $order_info['total'] . $order_info['customer_id'] . $order_info['order_id'];
		$signature = hash('SHA512', $signature);
        $signature = str_pad($signature, 128, '0', STR_PAD_LEFT);
        
        //$this->shipping->getCost($this->session->get('shipping_method'));
        $upay_args = array(
            'MERCHANT_IDENTIFIER' => $this->config->get('upay_mid'),
            'AMOUNT' => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) ,
            'TXN_DESC' => $description,
            'CUSTOMER_ID' => $order_info['customer_id'], //0 mean Guests other mean registed customer
            'ORDER_ID' => $order_info['order_id'],
            'INSTALLMENT' => 0, //installment not ready yet
            'CUSTOMER_NAME' => $order_info['payment_firstname'] . " " . $order_info['payment_lastname'],
            'CUSTOMER_EMAIL' => $order_info['email'],
            'CUSTOMER_COUNTRY' => $order_info['payment_country'],
            'CUSTOMER_BILLING_ADDRESS' => $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'] . ' ' . $order_info['payment_city'] . ' ' . $order_info['payment_zone'] . ' ' . $order_info['payment_postcode'] . ' ' . $order_info['payment_country'] . ' ' . $order_info['telephone'],
            'CUSTOMER_SHIPPING_ADDRESS' => $order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2'] . ' ' . $order_info['shipping_city'] . ' ' . $order_info['shipping_zone'] . ' ' . $order_info['shipping_postcode'] . ' ' . $order_info['shipping_country'] . ' ' . $order_info['telephone'],
            'CUSTOMER_SHIPPING_COST' => '',
            'CUSTOMER_IP' => $order_info['ip'],
            'CALLBACK_URL' => $this->config->get('config_url') . 'index.php?route=extension/payment/upay/return_ipn',
            'TXN_SIGNATURE' => $signature,
            'IS_TEST' => $this->config->get('upay_env'),
            'SOURCE_FROM' => 'WEB',
            'CURRENCY' => 'MYR'
        );

        $upay_args_array = array();
        foreach ($upay_args as $key => $value) {
            $upay_args_array[] = "<input type='hidden' name='" . $key . "' value='" . $value . "' />";
        }
        $data = array();
        $data['upay_args_array'] = $upay_args_array;
        $data['action'] = $this->url_pay;
        $data['button'] = $this->language->get("button_confirm");
        //$this->load->view('extension/payment/upay', $data);
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/upay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/extension/payment/upay.tpl', $data);
        } else {
            return $this->load->view('extension/payment/upay.tpl', $data);
        }
    }

    public function return_ipn() {
 $this->load->language('extension/payment/upay');
        $this->load->model('checkout/order');
        $status_code = (isset($_POST['STATUS_CODE']) && !empty($_POST['STATUS_CODE'])) ? $_POST['STATUS_CODE'] : '';
        $tranID = (isset($_POST['TXN_ID']) && !empty($_POST['TXN_ID'])) ? $_POST['TXN_ID'] : '';
        $orderid = (isset($_POST['ORDER_ID']) && !empty($_POST['ORDER_ID'])) ? $_POST['ORDER_ID'] : '';
        $status = (isset($_POST['TXN_STATUS']) && !empty($_POST['TXN_STATUS'])) ? $_POST['TXN_STATUS'] : '';
        $amount = (isset($_POST['AMOUNT']) && !empty($_POST['AMOUNT'])) ? $_POST['AMOUNT'] : '';
        $paydate = (isset($_POST['TXN_TIMESTAMP']) && !empty($_POST['TXN_TIMESTAMP'])) ? $_POST['TXN_TIMESTAMP'] : '';
        $order_info = $this->model_checkout_order->getOrder($orderid);
        $signature_post = $this->config->get('upay_vkey') . $this->config->get('upay_mid') . number_format((float)$amount, 2, '.', '') . $order_info['customer_id'] . $orderid;
        $signature_post = str_pad($signature_post, 128, '0', STR_PAD_LEFT);
        $signature_post = hash('SHA512', $signature_post);

        $signature_send = $this->config->get('upay_vkey') . $this->config->get('upay_mid') .number_format((float)$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), 2, '.', '') . $order_info['customer_id'] . $orderid;
        $signature_send = str_pad($signature_send, 128, '0', STR_PAD_LEFT);
        $signature_send = hash('SHA512', $signature_send);
//       echo $amount;echo '<br>';echo number_format((float)$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), 2, '.', ''); echo '<br>';
//        echo $signature_send;echo '<br>';echo $signature_post;exit;
        
        
        if ($signature_send != $signature_post) {
            $status_code = "-1";
        }

        if ($status_code == "00") {
            $order_status_id = $this->config->get('upay_completed_status_id');
            $redirect = $this->url->link('checkout/success', '200');
        } elseif ($status_code == "-1") {
            $order_status_id = $this->config->get('upay_pending_status_id');
            $redirect = $this->url->link('checkout/success', '200');
        } else {
            $order_status_id = $this->config->get('upay_failed_status_id');
            $redirect = $this->url->link('checkout/failure', '200');
        }
        
        $this->model_checkout_order->addOrderHistory($orderid, $order_status_id, 'TX_ID:' . $tranID, true);
        $this->send_notification_to_upay($_POST);
        $this->response->redirect($redirect);
    }

    public function send_notification_to_upay($data_post) {
//        print_r($_POST);exit;
         $this->load->language('extension/payment/upay');
        $this->load->model('checkout/order');
        $data_curl = array();
        $data_curl['STATUS_CODE'] = $data_post['STATUS_CODE'];
        $data_curl['ORDER_ID'] = $data_post['ORDER_ID'];
        $data_curl['TXN_ID'] = $data_post['TXN_ID'];
        $data_curl['TXN_TIMESTAMP'] = $data_post['TXN_TIMESTAMP'];
        $data_curl['MERCHANT_HASHKEY'] = $this->config->get('upay_vkey');
        $data_curl['MERCHANT_IDENTIFIER'] = $this->config->get('upay_mid');
        $data_curl['AMOUNT'] = $data_post['AMOUNT'];
        $data_curl['IS_TEST'] = 'Y';
        $data_curl['RESPONSE_CODE'] = '200';
        while (list($k, $v) = each($data_curl)) {
            $curlData[] = $k . "=" . $v;
        }
        $postdata = implode("&", $curlData);
        $url = $this->pay_url_notication;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSLVERSION     , 3);

        $result = curl_exec($ch);
        curl_close($ch);
//        if ($result['notification_code'] == "0") {
//            $order_status_id = $this->config->get('upay_completed_status_id');
//            $this->model_checkout_order->addOrderHistory($data_curl['ORDER_ID'], $order_status_id, 'Upay Payment Acknowledgement: SUCCESSFUL', false);
//        } else {
//            $order_status_id = $this->config->get('upay_pending_status_id');
//            $this->model_checkout_order->addOrderHistory($data_curl['ORDER_ID'], $order_status_id, 'Upay Payment Acknowledgement: ' . $result['notification_code'] . $result['notification_desc'], false);
//        }
//        return;
    }

    public function verify_currency($currency) {

        if (!in_array($currency, $this->upay_currency)) {
            return false;
        }
        return true;
    }

}
