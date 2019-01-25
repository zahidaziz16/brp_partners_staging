<?php
class ControllerExtensionPaymentPPStandard extends Controller {
	public function index() {
		$this->load->language('extension/payment/pp_standard');

		$data['text_testmode'] = $this->language->get('text_testmode');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['testmode'] = $this->config->get('pp_standard_test');

		if (!$this->config->get('pp_standard_test')) {
			$data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
		} else {
			$data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$this->load->model('checkout/order');
		$this->load->model('quotation/order');
		$quotationFlag = false;
		
		if(isset($this->session->data['quotation_id'])){
			$order_info = $this->model_quotation_order->getquotation($this->session->data['quotation_id']);
			$order_info['order_id'] = $order_info['quotation_id'];
			$orderId = $this->session->data['quotation_id'];
			$data['quotation_id'] = $this->session->data['quotation_id'];
			$quotationFlag = true;
		}else{
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$orderId = $this->session->data['order_id'];
		}

		if ($order_info) {
			$data['business'] = $this->config->get('pp_standard_email');
			$data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$data['products'] = array();
			
			if ($quotationFlag == false){
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
						'data_source'    => htmlspecialchars($product['data_source']),
						'model'    => htmlspecialchars($product['model']),
						'matching_code'    => htmlspecialchars($product['matching_code']),
						'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
						'quantity' => $product['quantity'],
						'option'   => $option_data,
						'weight'   => $product['weight']
					);
				}
			}
			$data['discount_amount_cart'] = 0;
			if ($quotationFlag){
			$total = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
			} else {
			$total = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);
			}
			$text_total_temp = $this->language->get('text_total');
			if ($quotationFlag){
				$text_total_temp = "Quotation #". $orderId;
			}
			
			if ($total > 0) {
				$data['products'][] = array(
					'name'     => $text_total_temp,
					'model'    => '',
					'matching_code'    => '',
					'price'    => $total,
					'quantity' => 1,
					'option'   => array(),
					'weight'   => 0
				);
			} else {
				$data['discount_amount_cart'] -= $total;
			}

			$data['currency_code'] = $order_info['currency_code'];
			$data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
			$data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$data['country'] = $order_info['payment_iso_code_2'];
			$data['email'] = $order_info['email'];
			$data['invoice'] = $orderId . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['lc'] = $this->session->data['language'];
			if ($quotationFlag){
			$data['return'] = $this->url->link('account/quotation/info&quotation_id='.$this->session->data['quotation_id']);
			$data['cancel_return'] = $this->url->link('account/quotation', '', true);			
			} else {
			$data['return'] = $this->url->link('checkout/success');
			$data['cancel_return'] = $this->url->link('checkout/checkout', '', true);
			}
			$data['notify_url'] = $this->url->link('extension/payment/pp_standard/callback', '', true);

			if (!$this->config->get('pp_standard_transaction')) {
				$data['paymentaction'] = 'authorization';
			} else {
				$data['paymentaction'] = 'sale';
			}
			if ($quotationFlag){
			$data['custom'] = "q_". $orderId;
			}else{
			$data['custom'] = $orderId;
			}
			//$this->log->write(print_r($data)); exit;
			return $this->load->view('extension/payment/pp_standard', $data);
		}
	}

	public function callback() {
		if (isset($this->request->post['custom'])) {
			$order_id = $this->request->post['custom'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');
		$this->load->model('quotation/order');
		$quotationFlag = false;
		
		if(strpos($order_id, "q_")!== false){
			$order_id = str_replace("q_","",$order_id);
			$quotationFlag = true;
		}
		
		if(isset($this->request->get['comment'])){
			$comment = $this->request->get['comment'];
		}else{
			$comment = "";
		}
		
		if (isset($this->request->post['invoice'])) {
			$invoice = $this->request->post['invoice'];
		} else {
			$invoice = "";
		}
		
		if($quotationFlag){
			$quotationId = $order_id;
			$order_info = $this->model_quotation_order->getquotation($order_id);
			$order_info['order_id'] = $order_info['quotation_id'];
		}else{
			$order_info = $this->model_checkout_order->getOrder($order_id);
		}
		
		if ($order_info) {
			$request = 'cmd=_notify-validate';

			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}

			if (!$this->config->get('pp_standard_test')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}

			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($curl);

			if (!$response) {
				$this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}

			if ($this->config->get('pp_standard_debug')) {
				$this->log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
				$this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
			}

			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				$order_status_id = $this->config->get('config_order_status_id');

				switch($this->request->post['payment_status']) {
					case 'Canceled_Reversal':
						$order_status_id = $this->config->get('pp_standard_canceled_reversal_status_id');
						break;
					case 'Completed':
				
						$receiver_match = (strtolower($this->request->post['receiver_email']) == strtolower($this->config->get('pp_standard_email')));

						$total_paid_match = ((float)$this->request->post['mc_gross'] == $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false));

						if ($receiver_match && $total_paid_match) {
							$order_status_id = $this->config->get('pp_standard_completed_status_id');
						}
						
						if (!$receiver_match) {
							$this->log->write('PP_STANDARD :: RECEIVER EMAIL MISMATCH! ' . strtolower($this->request->post['receiver_email']));
						}
						
						if (!$total_paid_match) {
							$this->log->write('PP_STANDARD :: TOTAL PAID MISMATCH! ' . $this->request->post['mc_gross'] . " Currency: " . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false));
						}
						break;
					case 'Denied':
						$order_status_id = $this->config->get('pp_standard_denied_status_id');
						break;
					case 'Expired':
						$order_status_id = $this->config->get('pp_standard_expired_status_id');
						break;
					case 'Failed':
						$order_status_id = $this->config->get('pp_standard_failed_status_id');
						break;
					case 'Pending':
						$order_status_id = $this->config->get('pp_standard_pending_status_id');
						break;
					case 'Processed':
						$order_status_id = $this->config->get('pp_standard_processed_status_id');
						break;
					case 'Refunded':
						$order_status_id = $this->config->get('pp_standard_refunded_status_id');
						break;
					case 'Reversed':
						$order_status_id = $this->config->get('pp_standard_reversed_status_id');
						break;
					case 'Voided':
						$order_status_id = $this->config->get('pp_standard_voided_status_id');
						break;
				}
				if ($quotationFlag){
					if($order_status_id == $this->config->get('pp_standard_completed_status_id')){
						$this->model_quotation_order->addQuotationHistory($quotationId, $this->config->get('quotation_pp_standard_payment'), $comment);
						//$this->model_quotation_order->addQuotationHistory($quotationId, $this->config->get('quotation_pp_standard_payment'), "Invoice No : " . $_POST['Invoice']);
					} else {
						$this->log->write("Paypal status not completed. Order status id : " . $order_status_id);
					}
				}else{
				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
				}
			} else {
				if ($quotationFlag){
					//do nothing
				}else{
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));
				}
			}

			curl_close($curl);
		}
	}
}