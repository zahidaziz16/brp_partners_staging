<?php
class ControllerExtensionPaymentIpay88 extends Controller {
	public function index() {
		$this->language->load('payment/ipay88');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');

    	$data['action'] = 'https://payment.ipay88.com.my/epayment/entry.asp';
		
		$vendor = $this->config->get('ipay88_vendor');
		$password = $this->config->get('ipay88_password');		
		$support_currency = $this->config->get('entry_currency');
		
		$this->load->model('checkout/order');
		$this->load->model('quotation/order');
		
		if(isset($this->session->data['quotation_id'])){
			$order_info = $this->model_quotation_order->getquotation($this->session->data['quotation_id']);
			$order_info['order_id'] = "Q_" . $order_info['quotation_id'];
			$orderId = "Q_" . $this->session->data['quotation_id'];
		}else{
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$orderId = $this->session->data['order_id'];
		}
		// Lets start define iPay88 parameters here
		
			$data['products'] = array();
			
				foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
						
						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$data['products'][] = array(
					'name'     => htmlspecialchars($product['name']),
					'model'    => htmlspecialchars($product['model']),
					'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
					'quantity' => $product['quantity'],
					'option'   => $option_data,
					'weight'   => $product['weight']
				);
			}

			$data['discount_amount_cart'] = 0;

			$total = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

			if ($total > 0) {
				$data['products'][] = array(
					'name'     => $this->language->get('text_total'),
					'model'    => '',
					'price'    => $total,
					'quantity' => 1,
					'option'   => array(),
					'weight'   => 0
				);
			} else {
				$data['discount_amount_cart'] -= $total;
			}

	
		// Let's Generate Digital Signature  
		
		$ipaySignature ='';
		
		
        $merId = $this->config->get('ipay88_vendor');
        $ikey = $this->config->get('ipay88_password');		
		$tmpAmount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE);
		$ordAmount = number_format($tmpAmount, 2, ".", "");
        
		$ipaySignature ='';
	    $HashAmount = str_replace(".","",str_replace(",","",$ordAmount));	
		$str = sha1($ikey  . $merId . $orderId . $HashAmount . $order_info['currency_code']);
		
		for ($i=0;$i<strlen($str);$i=$i+2)
		{
        $ipaySignature .= chr(hexdec(substr($str,$i,2)));
		}
     
		$ipaySignature = base64_encode($ipaySignature);
		
		// Signature generating done !
		
		// Assign values for form post

		$data['MerchantCode'] = $this->config->get('ipay88_vendor');
		$data['PaymentId'] = '';
		$data['RefNo'] = $orderId;
		$data['Amount'] = $ordAmount;
		$data['Currency'] = $order_info['currency_code'];
		//$data['ProdDesc'] = $product['name'];
		$data['ProdDesc'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
		$data['UserName'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$data['UserEmail'] = $order_info['email'];
		$data['UserContact'] = $order_info['telephone'];
		$data['Remark'] = sprintf($this->language->get('text_description'), date($this->language->get('date_format_short')), $orderId);
		$data['Lang'] = "UTF-8";
		$data['Signature'] = $ipaySignature;
		$data['ResponseURL'] = $this->url->link('extension/payment/ipay88/callback', '', 'SSL');
		$data['BackendURL'] = $this->url->link('extension/payment/ipay88/backendcallback', '', 'SSL');
		
		
		$data['back'] = $this->url->link('checkout/payment', '', 'SSL');
		
		$this->id       = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipay88.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/ipay88.tpl', $data);
		} else {
			return $this->load->view('payment/ipay88.tpl', $data);
		}	
		
	}
	
	public function callback() 
	{
		$this->language->load('payment/ipay88');
	
		$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$data['base'] = HTTP_SERVER;
		} else {
			$data['base'] = HTTPS_SERVER;
		}
	
		$data['charset'] = $this->language->get('charset');
		$data['language'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
	
		$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$data['text_response'] = $this->language->get('text_response');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success', '', 'SSL'));
		$data['text_failure'] = $this->language->get('text_failure');
		$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));

	
		$expected_sign = $_POST['Signature'];
	    $merId = $this->config->get('ipay88_vendor');
        $ikey = $this->config->get('ipay88_password');	

		$check_sign = "";
		$ipaySignature = "";
		$str = "";
		$HashAmount = "";
		
		$HashAmount = str_replace(array(',','.'), "", $_POST['Amount']);
		$str = $ikey . $merId . $_POST['PaymentId'].trim(stripslashes($_POST['RefNo'])). $HashAmount . $_POST['Currency'] . $_POST['Status'];
	
	
		$str = sha1($str);
	   
	    for ($i=0;$i<strlen($str);$i=$i+2)
		{
        $ipaySignature .= chr(hexdec(substr($str,$i,2)));
		}
       
		$check_sign = base64_encode($ipaySignature);


	if ($_POST['Status']=="1" && $check_sign==$expected_sign) 
		{
	
		$this->load->model('checkout/order');
		
		//$this->model_checkout_order->addOrderHistory($_POST['RefNo'], $this->config->get('ipay88_order_status_id'), "Transaction ID : " . $_POST['TransId'], TRUE);	  
		if(isset($this->session->data['quotation_id'])){
				$quotationId = $this->session->data['quotation_id'];
				$this->load->model('quotation/order');
				$this->model_quotation_order->addQuotationHistory($quotationId, $this->config->get('quotation_ipay88_payment'), "Transaction ID : " . $_POST['TransId'], TRUE);	  
				$data['continue'] = $this->url->link('account/quotation/info&quotation_id='.$this->session->data['quotation_id']);
			}
			else{
				$data['continue'] = $this->url->link('checkout/success', '', 'SSL');
			}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipay88_success.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/ipay88_success.tpl';
				} else {
					$this->template = 'payment/ipay88_success.tpl';
				}	
		
	  			$this->response->setOutput($this->load->view($this->template, $data), $this->config->get('config_compression'));

		}	
		else
		{				
				$data['continue'] = $this->url->link('checkout/cart');
		
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipay88_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/ipay88_failure.tpl';
				} else {
					$this->template = 'payment/ipay88_failure.tpl';
				}
				
	  			$this->response->setOutput($this->load->view($this->template, $data), $this->config->get('config_compression'));	

		}
		
	}
	
	
		public function backendcallback() 
	{
		$this->language->load('payment/ipay88');
	
		$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$data['base'] = HTTP_SERVER;
		} else {
			$data['base'] = HTTPS_SERVER;
		}
	
		// $data['charset'] = $this->language->get('charset');
		// $data['language'] = $this->language->get('code');
		// $data['direction'] = $this->language->get('direction');
	
		// $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		// $data['text_response'] = $this->language->get('text_response');
		// $data['text_success'] = $this->language->get('text_success');
		// $data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success', '', 'SSL'));
		// $data['text_failure'] = $this->language->get('text_failure');
		// $data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));


		$expected_sign = $_POST['Signature'];
	    $merId = $this->config->get('ipay88_vendor');
        $ikey = $this->config->get('ipay88_password');	

		$check_sign = "";
		$ipaySignature = "";
		$str = "";
		$HashAmount = "";
		
		$HashAmount = str_replace(array(',','.'), "", $_POST['Amount']);
		$str = $ikey . $merId . $_POST['PaymentId'].trim(stripslashes($_POST['RefNo'])). $HashAmount . $_POST['Currency'] . $_POST['Status'];
	
	
		$str = sha1($str);
	   
	    for ($i=0;$i<strlen($str);$i=$i+2)
		{
        $ipaySignature .= chr(hexdec(substr($str,$i,2)));
		}
       
		$check_sign = base64_encode($ipaySignature);


	if ($_POST['Status']=="1" && $check_sign==$expected_sign) 
		{
			if(strpos($_POST['RefNo'], "Q_") !== FALSE){
				$quotationId = $this->session->data['quotation_id'];
				$this->load->model('quotation/order');
				$this->model_quotation_order->addQuotationHistory($quotationId, $this->config->get('quotation_ipay88_payment'), "Transaction ID : " . $_POST['TransId'], TRUE);	  
			}
			else{
			$this->load->model('checkout/order');
			
			$this->model_checkout_order->addOrderHistory($_POST['RefNo'], $this->config->get('ipay88_order_status_id'), "Transaction ID : " . $_POST['TransId'], TRUE);	  
			}
				$data['continue'] = $this->url->link('checkout/success', '', 'SSL');
					
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipay88_success_backend.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/ipay88_success_backend.tpl';
				} else {
					$this->template = 'payment/ipay88_success_backend.tpl';
				}	
		
	  			$this->response->setOutput($this->load->view($this->template, $data), $this->config->get('config_compression'));

		}	
		else
		{				
				if(isset($this->session->data['quotation_id']))
				$data['continue'] = $this->url->link('account/quotation/info&quotation_id='.$this->session->data['quotation_id']);
				else
				$data['continue'] = $this->url->link('checkout/cart');
	
		
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipay88_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/ipay88_failure.tpl';
				} else {
					$this->template = 'payment/ipay88_failure.tpl';
				}
				
	  			$this->response->setOutput($this->load->view($this->template, $data), $this->config->get('config_compression'));	

		}
		
	}
	
	
}
?>