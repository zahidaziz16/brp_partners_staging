<?php
class ControllerExtensionPaymentGHL extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		$this->load->model('quotation/order');
		$quotationFlag = false;
		
		if(isset($this->session->data['quotation_id'])){
			$order_info = $this->model_quotation_order->getquotation($this->session->data['quotation_id']);
			$order_info['order_id'] = "Q_" . $order_info['quotation_id'];
			$orderId = $this->session->data['quotation_id'];
			$quotationFlag = true;
		}else{
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$orderId = $this->session->data['order_id'];
		}

		if (!$this->config->get('ghl_test')){
			$data['action'] = 'https://securepay.e-ghl.com/IPG/Payment.aspx'; // <-- live site
		}else{
			$data['action'] = 'https://test2pay.ghl.com/IPGSG/Payment.aspx';
		}

		$data['merchant_id'] = $this->config->get('ghl_merchant');
		$data['order_id'] = $order_info['order_id'];
		$data['amount'] = sprintf("%.02f", $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false));
		$data['currency'] = $order_info['currency_code'];
		$data['description'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
		$data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

		$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$data['page_timeout'] = 600;
		$data['card_no'] = "";

		$data['return_url'] = $this->url->link('extension/payment/ghl/callback');
		$data['server_callback'] = $this->url->link('extension/payment/ghl/server_callback');

		$data['digest'] = hash( 'sha256', $this->config->get('ghl_password') . $data['merchant_id'] . $data['order_id'] . $data['return_url'] . $data['server_callback'] . $data['amount'] . $data['currency'] . $data['ip_address'] . $data['page_timeout'] );

		$data['country'] = $order_info['payment_iso_code_2'];
		$data['telephone'] = $order_info['telephone'];
		$data['email'] = $order_info['email'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/ghl.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/ghl.tpl', $data);
		} else {
			return $this->load->view('extension/payment/ghl.tpl', $data);
		}
	}

	public function callback() {
		$this->language->load('extension/payment/ghl');

		$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$data['base'] = $this->config->get('config_url');
		} else {
			$data['base'] = $this->config->get('config_ssl');
		}
		$data['language'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		$data['text_response'] = $this->language->get('text_response');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
		$data['text_failure'] = $this->language->get('text_failure');
		$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/checkout', '', 'SSL'));
		
		$quotationFlag = false;
		
		if(isset($this->session->data['quotation_id'])){
		$quotationFlag = true;
		}

		if (!empty($this->request->post)){
			$PaymentID = $this->request->post['PaymentID'];
			$ServiceID = $this->request->post['ServiceID'];
			$OrderNumber = $this->request->post['OrderNumber'];
			$Amount = $this->request->post['Amount'];
			$CurrencyCode = $this->request->post['CurrencyCode'];
			$TxnID = $this->request->post['TxnID'];
			$PymtMethod = $this->request->post['PymtMethod'];
			$TxnStatus = $this->request->post['TxnStatus'];
			$AuthCode = (!empty($this->request->post['AuthCode'])) ? $this->request->post['AuthCode'] : "";
			$TxnMessage = $this->request->post['TxnMessage'];
			$IssuingBank = (!empty($this->request->post['IssuingBank'])) ? $this->request->post['IssuingBank'] : "";
			$HashValue = $this->request->post['HashValue'];
		} else {
			$PaymentID = $this->request->get['PaymentID'];
			$ServiceID = $this->request->get['ServiceID'];
			$OrderNumber = $this->request->get['OrderNumber'];
			$Amount = $this->request->get['Amount'];
			$CurrencyCode = $this->request->get['CurrencyCode'];
			$TxnID = $this->request->get['TxnID'];
			$PymtMethod = $this->request->get['PymtMethod'];
			$TxnStatus = $this->request->get['TxnStatus'];
			$AuthCode = (!empty($this->request->get['AuthCode'])) ? $this->request->get['AuthCode'] : "";
			$TxnMessage = $this->request->get['TxnMessage'];
			$IssuingBank = (!empty($this->request->get['IssuingBank'])) ? $this->request->get['IssuingBank'] : "";
			$HashValue = $this->request->get['HashValue'];
		}

		$verify = hash( 'sha256', $this->config->get('ghl_password') . $TxnID . $ServiceID . $PaymentID . $TxnStatus . $Amount . $CurrencyCode . $AuthCode );

		if( $HashValue != $verify ) $TxnStatus= -3;

		if ( $TxnStatus == 0 ) {
			$this->load->model('checkout/order');
			$this->load->model('quotation/order');
			
			$message = '';
			if (isset($PaymentID)) {
				$message .= 'PaymentID: ' . $PaymentID . "\n";
			}

			if (isset($ServiceID)) {
				$message .= 'ServiceID: ' . $ServiceID . "\n";
			}

			if (isset($OrderNumber)) {
				$message .= 'OrderNumber: ' . $OrderNumber . "\n";
			}

			if (isset($Amount)) {
				$message .= 'Amount: ' . $Amount . "\n";
			}

			if (isset($CurrencyCode)) {
				$message .= 'CurrencyCode: ' . $CurrencyCode . "\n";
			}

			if (isset($TxnID)) {
				$message .= 'TxnID: ' . $TxnID . "\n";
			}

			if (isset($PymtMethod)) {
				$message .= 'PymtMethod: ' . $PymtMethod . "\n";
			}

			if (isset($TxnStatus)) {
				$message .= 'TxnStatus: ' . $TxnStatus . "\n";
			}

			if (isset($AuthCode)) {
				$message .= 'AuthCode: ' . $AuthCode . "\n";
			}

			if (isset($TxnMessage)) {
				$message .= 'TxnMessage: ' . $TxnMessage . "\n";
			}

			if (isset($IssuingBank)) {
				$message .= 'IssuingBank: ' . $IssuingBank . "\n";
			}

			if (isset($HashValue)) {
				$message .= 'HashValue: ' . $HashValue . "\n";
			}
			
			if(isset($this->request->get['comment'])){
				$comment = $this->request->get['comment'];
			}else{
				$comment = "";
			}
			
			$message .= "&nbsp; " . $comment;
			
			$order_id = $this->request->post['PaymentID'];
			
			if ($quotationFlag){
			if(strpos($order_id, "Q_")!== false){
			$order_id = str_replace("Q_","",$order_id);
			}
			
			$this->model_quotation_order->addQuotationHistory($order_id, $this->config->get('quotation_ghl_payment'), $message);
			$data['continue'] = $this->url->link('account/quotation/info&quotation_id='.$this->session->data['quotation_id']);
			
			}else{
			$this->model_checkout_order->addOrderHistory($this->request->post['PaymentID'], $this->config->get('ghl_order_status_id'), $message, false);

			$data['continue'] = $this->url->link('checkout/success');
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/ghl_success.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/ghl_success.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/payment/ghl_success.tpl', $data));
			}
		} else {
			if($quotationFlag){
			$data['continue'] = $this->url->link('account/quotation');	
			}else{
			$data['continue'] = $this->url->link('checkout/cart');
			}
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/ghl_failure.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/payment/ghl_failure.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/payment/ghl_failure.tpl', $data));
			}
		}
	}

	public function server_callback() {
		$this->language->load('extension/payment/ghl');

		$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$quotationFlag = false;
		
		if(isset($this->session->data['quotation_id'])){
		$quotationFlag = true;
		}

		if (!empty($this->request->post)){
			$PaymentID = $this->request->post['PaymentID'];
			$ServiceID = $this->request->post['ServiceID'];
			$OrderNumber = $this->request->post['OrderNumber'];
			$Amount = $this->request->post['Amount'];
			$CurrencyCode = $this->request->post['CurrencyCode'];
			$TxnID = $this->request->post['TxnID'];
			$PymtMethod = $this->request->post['PymtMethod'];
			$TxnStatus = $this->request->post['TxnStatus'];
			$AuthCode = (!empty($this->request->post['AuthCode'])) ? $this->request->post['AuthCode'] : "";
			$TxnMessage = $this->request->post['TxnMessage'];
			$IssuingBank = (!empty($this->request->post['IssuingBank'])) ? $this->request->post['IssuingBank'] : "";
			$HashValue = $this->request->post['HashValue'];
		} else {
			$PaymentID = $this->request->get['PaymentID'];
			$ServiceID = $this->request->get['ServiceID'];
			$OrderNumber = $this->request->get['OrderNumber'];
			$Amount = $this->request->get['Amount'];
			$CurrencyCode = $this->request->get['CurrencyCode'];
			$TxnID = $this->request->get['TxnID'];
			$PymtMethod = $this->request->get['PymtMethod'];
			$TxnStatus = $this->request->get['TxnStatus'];
			$AuthCode = (!empty($this->request->get['AuthCode'])) ? $this->request->get['AuthCode'] : "";
			$TxnMessage = $this->request->get['TxnMessage'];
			$IssuingBank = (!empty($this->request->get['IssuingBank'])) ? $this->request->get['IssuingBank'] : "";
			$HashValue = $this->request->get['HashValue'];
		}

		$verify = hash( 'sha256', $this->config->get('ghl_password') . $TxnID . $ServiceID . $PaymentID . $TxnStatus . $Amount . $CurrencyCode . $AuthCode );

		if( $HashValue != $verify ) $TxnStatus= -3;

		if ( $TxnStatus == 0 ) {
			$this->load->model('checkout/order');
			$this->load->model('quotation/order');

			$message = '';
			if (isset($PaymentID)) {
				$message .= 'PaymentID: ' . $PaymentID . "\n";
			}

			if (isset($ServiceID)) {
				$message .= 'ServiceID: ' . $ServiceID . "\n";
			}

			if (isset($OrderNumber)) {
				$message .= 'OrderNumber: ' . $OrderNumber . "\n";
			}

			if (isset($Amount)) {
				$message .= 'Amount: ' . $Amount . "\n";
			}

			if (isset($CurrencyCode)) {
				$message .= 'CurrencyCode: ' . $CurrencyCode . "\n";
			}

			if (isset($TxnID)) {
				$message .= 'TxnID: ' . $TxnID . "\n";
			}

			if (isset($PymtMethod)) {
				$message .= 'PymtMethod: ' . $PymtMethod . "\n";
			}

			if (isset($TxnStatus)) {
				$message .= 'TxnStatus: ' . $TxnStatus . "\n";
			}

			if (isset($AuthCode)) {
				$message .= 'AuthCode: ' . $AuthCode . "\n";
			}

			if (isset($TxnMessage)) {
				$message .= 'TxnMessage: ' . $TxnMessage . "\n";
			}

			if (isset($IssuingBank)) {
				$message .= 'IssuingBank: ' . $IssuingBank . "\n";
			}

			if (isset($HashValue)) {
				$message .= 'HashValue: ' . $HashValue . "\n";
			}
			
			if(isset($this->request->get['comment'])){
				$comment = $this->request->get['comment'];
			}else{
				$comment = "";
			}
			
			$message .= "&nbsp; " . $comment;
			$order_id = $this->request->post['PaymentID'];
			
			if ($quotationFlag){
				if(strpos($order_id, "Q_")!== false){
				$order_id = str_replace("Q_","",$order_id);
				}
			}
			if ($quotationFlag){
			$this->model_quotation_order->addQuotationHistory($order_id, $this->config->get('quotation_ghl_payment'), $message);
			
			}else{
			$this->model_checkout_order->addOrderHistory($this->request->post['PaymentID'], $this->config->get('ghl_order_status_id'), $message, false);
			}
			echo "OK";

		} else {
			/* failure */
			echo "ERROR";
		}
	}

}
?>