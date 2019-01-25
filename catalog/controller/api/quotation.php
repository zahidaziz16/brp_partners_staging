<?php
class ControllerApiQuotation extends Controller {
	public function add() {
		$this->load->language('api/quotation');

		$json = array();

		if (false) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Customer
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			// Cart
			if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json['error'] = $this->language->get('error_stock');
			}

			// Validate minimum quantity requirements.
			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

					break;
				}
			}

			if (!$json) {
				$quotation_data = array();

				// Store Details
				$quotation_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
				$quotation_data['store_id'] = $this->config->get('config_store_id');
				$quotation_data['store_name'] = $this->config->get('config_name');
				$quotation_data['store_url'] = $this->config->get('config_url');

				// Customer Details
				$quotation_data['customer_id'] = $this->session->data['customer']['customer_id'];
				$quotation_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
				$quotation_data['firstname'] = $this->session->data['customer']['firstname'];
				$quotation_data['lastname'] = $this->session->data['customer']['lastname'];
				$quotation_data['email'] = $this->session->data['customer']['email'];
				$quotation_data['telephone'] = $this->session->data['customer']['telephone'];
				$quotation_data['fax'] = $this->session->data['customer']['fax'];
				$quotation_data['custom_field'] = $this->session->data['customer']['custom_field'];

				// Payment Details
				$quotation_data['payment_firstname'] = "";
				$quotation_data['payment_lastname'] = "";
				$quotation_data['payment_company'] = "";
				$quotation_data['payment_address_1'] = "";
				$quotation_data['payment_address_2'] = "";
				$quotation_data['payment_city'] = "";
				$quotation_data['payment_postcode'] = "";
				$quotation_data['payment_zone'] = "";
				$quotation_data['payment_zone_id'] ="";
				$quotation_data['payment_country'] = "";
				$quotation_data['payment_country_id'] = "";
				$quotation_data['payment_address_format'] = "";
				$quotation_data['payment_custom_field'] = array();
				$quotation_data['payment_method'] = '';
				$quotation_data['payment_code'] = '';
			

				if ($this->cart->hasShipping()) {
					$quotation_data['shipping_required'] = 1;
				} else {
					$quotation_data['shipping_required'] = 0;
				}

				$quotation_data['shipping_method'] = '';
				$quotation_data['shipping_code'] = '';
				$quotation_data['shipping_firstname'] = '';
				$quotation_data['shipping_lastname'] = '';
				$quotation_data['shipping_company'] = '';
				$quotation_data['shipping_address_1'] = '';
				$quotation_data['shipping_address_2'] = '';
				$quotation_data['shipping_city'] = '';
				$quotation_data['shipping_postcode'] = '';
				$quotation_data['shipping_zone'] = '';
				$quotation_data['shipping_zone_id'] = '';
				$quotation_data['shipping_country'] = '';
				$quotation_data['shipping_country_id'] = '';
				$quotation_data['shipping_address_format'] = '';
				$quotation_data['shipping_custom_field'] = array();
				$quotation_data['shipping_method'] = '';
				$quotation_data['shipping_code'] = '';

				// Products
				$quotation_data['products'] = array();

				foreach ($this->cart->getProducts() as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $option['value'],
							'type'                    => $option['type']
						);
					}

					$quotation_data['products'][] = array(
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'model'      => $product['model'],
						'option'     => $option_data,
						'download'   => $product['download'],
						'quantity'   => $product['quantity'],
						'subtract'   => $product['subtract'],
						'price'      => $product['price'],
						'total'      => $product['total'],
						'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'     => $product['reward']
					);
				}

				// Gift Voucher
				$quotation_data['vouchers'] = array();

				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$quotation_data['vouchers'][] = array(
							'description'      => $voucher['description'],
							'code'             => substr(md5(mt_rand()), 0, 10),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],
							'amount'           => $voucher['amount']
						);
					}
				}

				// quotation Totals
				$this->load->model('extension/extension');

				$quotation_data['totals'] = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();

				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);

						$this->{'model_total_' . $result['code']}->getTotal($quotation_data['totals'], $total, $taxes);
					}
				}

				$sort_order = array();

				foreach ($quotation_data['totals'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $quotation_data['totals']);

				if (isset($this->request->post['comment'])) {
					$quotation_data['comment'] = $this->request->post['comment'];
				} else {
					$quotation_data['comment'] = '';
				}

				$quotation_data['total'] = $total;

				if (isset($this->request->post['affiliate_id'])) {
					$subtotal = $this->cart->getSubTotal();

					// Affiliate
					$this->load->model('affiliate/affiliate');

					$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

					if ($affiliate_info) {
						$quotation_data['affiliate_id'] = $affiliate_info['affiliate_id'];
						$quotation_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					} else {
						$quotation_data['affiliate_id'] = 0;
						$quotation_data['commission'] = 0;
					}

					// Marketing
					$quotation_data['marketing_id'] = 0;
					$quotation_data['tracking'] = '';
				} else {
					$quotation_data['affiliate_id'] = 0;
					$quotation_data['commission'] = 0;
					$quotation_data['marketing_id'] = 0;
					$quotation_data['tracking'] = '';
				}

				$quotation_data['language_id'] = $this->config->get('config_language_id');
				$quotation_data['currency_id'] = $this->currency->getId();
				$quotation_data['currency_code'] = $this->currency->getCode();
				$quotation_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
				$quotation_data['ip'] = $this->request->server['REMOTE_ADDR'];

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$quotation_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
				} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$quotation_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
				} else {
					$quotation_data['forwarded_ip'] = '';
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$quotation_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
				} else {
					$quotation_data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$quotation_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$quotation_data['accept_language'] = '';
				}

				$this->load->model('quotation/order');

				$json['quotation_id'] = $this->model_quotation_order->addquotation($quotation_data);

				// Set the quotation history
				if (isset($this->request->post['quotation_status_id'])) {
					$quotation_status_id = $this->request->post['quotation_status_id'];
				} else {
					$quotation_status_id = $this->config->get('quotation_quotation_status_id');
				}

				$this->model_quotation_order->addquotationHistory($json['quotation_id'], $quotation_status_id);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('api/quotation');

		$json = array();

		if (false) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('quotation/order');

			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$quotation_info = $this->model_quotation_order->getquotation($quotation_id);

			if ($quotation_info) {
				// Customer
				if (!isset($this->session->data['customer'])) {
					$json['error'] = $this->language->get('error_customer');
				}

				// Cart
				if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$json['error'] = $this->language->get('error_stock');
				}

				// Validate minimum quantity requirements.
				$products = $this->cart->getProducts();
				foreach ($products as $product) {
					$product_total = 0;
					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}
					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

						break;
					}
				}

				if (!$json) {
					$quotation_data = array();

					// Store Details
					$quotation_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
					$quotation_data['store_id'] = $this->config->get('config_store_id');
					$quotation_data['store_name'] = $this->config->get('config_name');
					$quotation_data['store_url'] = $this->config->get('config_url');

					// Customer Details
					$quotation_data['customer_id'] = $this->session->data['customer']['customer_id'];
					$quotation_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
					$quotation_data['firstname'] = $this->session->data['customer']['firstname'];
					$quotation_data['lastname'] = $this->session->data['customer']['lastname'];
					$quotation_data['email'] = $this->session->data['customer']['email'];
					$quotation_data['telephone'] = $this->session->data['customer']['telephone'];
					$quotation_data['fax'] = $this->session->data['customer']['fax'];
				
					if ($this->cart->hasShipping()) {
						$quotation_data['shipping_required'] = 1;
					} else {
						$quotation_data['shipping_required'] = 0;
					}

					// Products
					$quotation_data['products'] = array();
					foreach ($this->cart->getProducts() as $product) {
						$option_data = array();
						foreach ($product['option'] as $option) {
							$option_data[] = array(
								'product_option_id'       => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id'               => $option['option_id'],
								'option_value_id'         => $option['option_value_id'],
								'name'                    => $option['name'],
								'value'                   => $option['value'],
								'type'                    => $option['type']
							);
						}
						$quotation_data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $product['name'],
							'model'      => $product['model'],
							'option'     => $option_data,
							'download'   => $product['download'],
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $product['price'],
							'total'      => $product['total'],
							'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
							'reward'     => $product['reward']
						);
					}

					// Gift Voucher
					$quotation_data['vouchers'] = array();
					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$quotation_data['vouchers'][] = array(
								'description'      => $voucher['description'],
								'code'             => substr(md5(mt_rand()), 0, 10),
								'to_name'          => $voucher['to_name'],
								'to_email'         => $voucher['to_email'],
								'from_name'        => $voucher['from_name'],
								'from_email'       => $voucher['from_email'],
								'voucher_theme_id' => $voucher['voucher_theme_id'],
								'message'          => $voucher['message'],
								'amount'           => $voucher['amount']
							);
						}
					}

					// quotation Totals
					$this->load->model('extension/extension');

					$quotation_data['totals'] = array();
					$total = 0;
					$taxes = $this->cart->getTaxes();

					$sort_order = array();

					$results = $this->model_extension_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if($result['code'] == 'credit'){ //credit payment disabled for quotation
							continue;
						}
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);

							$this->{'model_total_' . $result['code']}->getTotal($quotation_data['totals'], $total, $taxes);
						}
					}

					$sort_order = array();

					foreach ($quotation_data['totals'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $quotation_data['totals']);

					if (isset($this->request->post['comment'])) {
						$quotation_data['comment'] = $this->request->post['comment'];
					} else {
						$quotation_data['comment'] = '';
					}

					$quotation_data['total'] = $total;

					if (isset($this->request->post['affiliate_id'])) {
						$subtotal = $this->cart->getSubTotal();

						// Affiliate
						$this->load->model('affiliate/affiliate');

						$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

						if ($affiliate_info) {
							$quotation_data['affiliate_id'] = $affiliate_info['affiliate_id'];
							$quotation_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
						} else {
							$quotation_data['affiliate_id'] = 0;
							$quotation_data['commission'] = 0;
						}
					} else {
						$quotation_data['affiliate_id'] = 0;
						$quotation_data['commission'] = 0;
					}

					$this->model_quotation_order->editquotation($quotation_id, $quotation_data,1);

					// Set the quotation history
					if (isset($this->request->post['quotation_status_id'])) {
						$quotation_status_id = $this->request->post['quotation_status_id'];
					} else {
						$quotation_status_id = $this->config->get('quotation_quotation_status_id');
					}
					
					$this->model_quotation_order->updateShipping($quotation_id, $quotation_data['shipping_required']);
					$this->model_quotation_order->addquotationHistory($quotation_id, $quotation_status_id , $quotation_data['comment'],0);

					$json['success'] = $this->language->get('text_success');
				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setall() {
		$this->load->model('account/customer');
		$this->load->language('api/customer');
		$this->load->language('api/quotation');

		$json = array();

		if (false) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}
			$this->session->data['adminpriceedit'] = $quotation_id;
			
			
			$this->load->model('quotation/order');
			$quotation_info = $this->model_quotation_order->getquotation($quotation_id);
			if($quotation_info) {
				$this->load->model('localisation/currency');
				$currency_info = $this->model_localisation_currency->getCurrencyByCode($quotation_info['currency_code']);
			
				if ($currency_info) {
					$this->currency->set($quotation_info['currency_code']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				} else {
					$json['error'] = $this->language->get('error_currency');
				}

				// Delete past customer in case there is an error
				unset($this->session->data['customer']);
				
				// Customer
				if ($quotation_info['customer_id']) {
					$customer_info = $this->model_account_customer->getCustomer($quotation_info['customer_id']);

					if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
						$json['error'] = $this->language->get('error_customer');
					}
				}
			
				if (isset($this->request->post['product'])) {
					$this->quotation->clear();
					
					foreach ($this->request->post['product'] as $product) {
						
						if (isset($product['option'])) {
							$option = $product['option'];
						} else {
							$option = array();
						}

						$this->quotation->add($product['product_id'], $product['quantity'], $option,$recurring_id = 0,$product['price_prefix'],$product['percent'],$product['qprice'],$product['price']);
					}
				}

				if ((!$this->quotation->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->quotation->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$json['error'] = $this->language->get('error_notinstock');
				}
				
				// Validate minimum quantity requirements.
				$products = $this->quotation->getProducts();

				foreach ($products as $product) {
					$product_total = 0;

					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}
					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

						break;
					}
				}

				if(!$json) {
				// Totals
					$this->load->model('extension/extension');

					$total_data = array();
					$total = 0;
					$taxes = $this->quotation->getTaxes();
					
					$sort_order = array();

					$results[] = array("code"=>"sub_total");
					$results[] = array("code"=>"total");
					$results[] = array("code"=>"tax");

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					
					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('qtotal/' . $result['code']);

							$this->{'model_qtotal_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
					}

					$sort_order = array();

					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $total_data);

					$json['totals'] = array();

					foreach ($total_data as $total) {
						$json['totals'][] = array(
							'title' => $total['title'],
							'text'  => $this->currency->format($total['value'])
						);
					}
					$this->model_quotation_order->updatetotaldetails($quotation_id, $total_data);
					// Products
					$quotation_data['products'] = array();

					foreach ($this->quotation->getProducts() as $product) {
						
						$option_data = array();

						foreach ($product['option'] as $option) {
							$option_data[] = array(
								'product_option_id'       => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id'               => $option['option_id'],
								'option_value_id'         => $option['option_value_id'],
								'name'                    => $option['name'],
								'value'                   => $option['value'],
								'type'                    => $option['type']
							);
						}

						$quotation_data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $product['name'],
							'model'      => $product['model'],
							'option'     => $option_data,
							'download'   => $product['download'],
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $product['ogprice'],
							'qprice'      => $product['qprice'],
							'price_prefix'      => $product['price_prefix'],
							'percent'      => $product['percent'],
							'total'      => $product['total'],
							'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
							'reward'     => $product['reward']
						);
					}

					//$quotation_data['products'] = array_reverse($quotation_data['products']);
					
					$this->model_quotation_order->updatepricedetails($quotation_id, $quotation_data);

					$json['success'] = $this->language->get('text_success');
				}
				unset($this->session->data['adminpriceedit']);

			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function delete() {
		$this->load->language('api/quotation');
		$json = array();
		//!isset($this->session->data['api_id'])
		if (false) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('quotation/order');

			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$quotation_info = $this->model_quotation_order->getquotation($quotation_id);

			if ($quotation_info) {
				$this->model_quotation_order->deletequotation($quotation_id);
				
				$json['success'] = $this->language->get('text_success');
				$this->log->write($json['success']);
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history() {
		$this->load->language('api/quotation');
		$json = array();

		//if (!isset($this->session->data['api_id'])) {
		if (false){
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'quotation_status_id',
				'notify',
				'append',
				'comment'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			$this->load->model('quotation/order');

			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$quotation_info = $this->model_quotation_order->getquotation($quotation_id);
				
			if ($quotation_info) {
				$review = $this->model_quotation_order->getquotationReview($quotation_id);
				if(!$review) {
				  $json['error'] = $this->language->get('error_submit');
				} else {
					$this->model_quotation_order->addQuotationHistory($quotation_id,$this->request->post['quotation_status_id'],$this->request->post['comment'],$this->request->post['notify']);
					$json['success'] = $this->language->get('text_apihistorysuccess');
				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}