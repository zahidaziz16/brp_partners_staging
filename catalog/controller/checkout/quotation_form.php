<?php
class ControllerCheckoutQuotationForm extends Controller {
	public function index() {
		$this->load->language('checkout/quotation');

		$data['text_address_existing'] = $this->language->get('text_address_existing');
		$data['text_address_new'] = $this->language->get('text_address_new');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');

		$data['button_generate'] = $this->language->get('button_generate');
		$data['quote'] = 'xshippingpro';
		
		if (isset($this->session->data['payment_address']['address_id'])) {
			$data['address_id'] = $this->session->data['payment_address']['address_id'];
		} else {
			$data['address_id'] = $this->customer->getAddressId();
		}
   
		$this->load->model('account/address');
        $data['addresses'] = $this->model_account_address->getAddresses();
        $address_info = $this->model_account_address->getAddress($data['address_id']);
        
        $data['firstname']         = $address_info['firstname'];
        $data['lastname']          = $address_info['lastname'];
		$data['company']           = $address_info['company'];
        $data['address_1']         = $address_info['address_1'];
		$data['address_2']         = $address_info['address_2'];
        $data['postcode']          = $address_info['postcode'];
        $data['city']              = $address_info['city'];
        $data['country_id']        = $address_info['country_id'];
        $data['zone_id']           = $address_info['zone_id']; 
        
        foreach ($data['addresses'] as $address) {
            if (empty($address['address_1'])) {
                $address_empty = true;
            } else {
                $address_empty = false;
            }
        }
        if(empty($data['addresses']) && isset($address_empty)){
            $this->load->model('account/customer');

            if ($this->request->server['REQUEST_METHOD'] != 'POST') {
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            }

            if (isset($this->request->post['firstname'])) {
                $data['firstname'] = $this->request->post['firstname'];
            } elseif (!empty($customer_info)) {
                $data['firstname'] = $customer_info['firstname'];
            } else {
                $data['firstname'] = '';
            }

            if (isset($this->request->post['lastname'])) {
                $data['lastname'] = $this->request->post['lastname'];
            } elseif (!empty($customer_info)) {
                $data['lastname'] = $customer_info['lastname'];
            } else {
                $data['lastname'] = '';
            }

            if (isset($this->request->post['telephone'])) {
                $data['telephone'] = $this->request->post['telephone'];
            } elseif (!empty($customer_info)) {
                $data['telephone'] = $customer_info['telephone'];
            } else {
                $data['telephone'] = '';
            }
        }
  
		if (isset($this->session->data['payment_address']['country_id'])) {
			$data['country_id'] = $this->session->data['payment_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['payment_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$this->load->model('localisation/country');
        $data['countries'] = $this->model_localisation_country->getCountries();
        
        $this->load->model('account/customer');
                
        $data['isPetronasUser'] = $this->model_account_customer->isPetronasUser();

        // List Zones 
        $this->load->model('localisation/zone');
        if (isset($data['country_id'])) {
            $data['zones'] = $this->model_localisation_zone->getZonesByCountryId($data['country_id']);
        } else {
            // 129 is Malaysia country ID
            $data['zones'] = $this->model_localisation_zone->getZonesByCountryId(129);
        }

		// Custom Fields
		$this->load->model('account/custom_field');
		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		if (isset($this->session->data['payment_address']['custom_field'])) {
			$data['payment_address_custom_field'] = $this->session->data['payment_address']['custom_field'];
		} else {
			$data['payment_address_custom_field'] = array();
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/quotation_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/quotation_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('checkout/quotation_form.tpl', $data));
		}
	}

	public function save() {
		$this->load->language('checkout/quotation');
		$json = array();

		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
		}
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();
		$flag = false; $flagPaper = false; $flagRestriction = false; $locArr = array();
		
		if (!$json) {
			$this->load->model('account/address');
			$this->load->model('account/customer');
			
            //Existing Address
			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
				if (!$json) {
					// Default Payment Address
                    if(isset($this->request->post['custom_field'])){
                        $this->model_account_address->editAddress($this->request->post['address_id'], $this->request->post);
                        $this->model_account_customer->editCustomer($this->request->post);
                    } else {
                        $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);
                    }
					
					$address_id = $this->request->post['address_id'];
					//$this->model_account_address->editAddress($address_id, $this->request->post);
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
					$this->session->data['shipping_address'] = $this->session->data['payment_address']; //copy payment address
					// Shipment Method
					$method_data = array();

					$this->session->data['payment_method']['title'] = "Quotation";
					$this->session->data['payment_method']['code'] = "quotation_checkout";
					
					if (isset($this->request->post['shipping_method'])) {
						
						/*
						$code = $this->request->post['shipping_method'];
						$this->session->data['shipping_method']['code'] = $this->request->post['shipping_method'];
						$this->load->model('extension/shipping/'.$code);
						$quote = $this->{'model_extension_shipping_' . $code}->getQuote($this->session->data['shipping_address']);
						if ($quote) {
							foreach($quote['quote'] AS $quote){
								$method_data[] = array(
									'title'     	=> $quote['title'],
									'code'      	=> $quote['code'],
									'tax_class_id' 	=> $quote['tax_class_id'],
									'cost' 			=> $quote['cost'],
									'sort_order'	=> $quote['sort_order']
								);
							}
							$this->session->data['shipping_method'] = $method_data[0];
						}
						*/
					$this->load->model('extension/extension');

					$results = $this->model_extension_extension->getExtensions('shipping');

						foreach ($results as $result) {
							if ($this->config->get($result['code'] . '_status')) {
								$this->load->model('extension/shipping/' . $result['code']);

								$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

								if ($quote) {
									$method_data[$result['code']] = array(
										'title'      => $quote['title'],
										'quote'      => $quote['quote'],
										'sort_order' => $quote['sort_order'],
										'error'      => $quote['error'],
										'cost'      => (reset($quote['quote'])['cost'])
									);
								}
							}
						}
					$sort_order = array();

					foreach ($method_data as $key => $value) {
						$sort_order[$key] = $value['cost'];
					}

					array_multisort($sort_order, SORT_ASC, $method_data);
					$method_data = reset($method_data);
					$tempSortOrder = $method_data['sort_order'];						
					$method_data = reset($method_data['quote']);
					if (empty($tempSortOrder)){
						$tempSortOrder = 0;
					}
					$method_data['sort_order'] = $tempSortOrder;
					
					//print_r($method_data); exit;
					$this->session->data['shipping_method'] = $method_data;
					}else{
						$json['error']['warning'] = $this->language->get('error_shipping');
					}
				
                } 
				else {
                    //Select address is empty
                    if (empty($this->request->post['address_id'])) {
					   $json['error']['warning'] = $this->language->get('error_address');
				    } elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					   $json['error']['warning'] = $this->language->get('error_address');
				    }
                }
			} 
			else { //new
                //Validate address
				if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
				if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}
				if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}              
				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
				}
                
                if(isset($this->request->post['telephone'])){
					if($this->request->post['telephone'] != ''){
						if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
							$json['error']['telephone'] = $this->language->get('error_telephone');
						} else if(preg_match('/[^\d]/is', $this->request->post['telephone'])){
							$json['error']['telephone'] = 'The field must be a number';
						} else{
							$this->session->data['payment_telephone'] = $this->request->post['telephone'];
						}
					}
                }
                if(isset($this->request->post['postcode']) && $this->config->get('imdev_config_postcoderegister')) {
					$this->load->model('tool/pinpro');
					$data['pincode'] = $this->request->post['postcode'];
					$pinpro = $this->model_tool_pinpro->checkpin($data);

					 if($pinpro['failed'] == '1') {
						$json['error']['postcode'] = $pinpro['fail'];
					 } else if(isset($pinpro['city_name']) && $pinpro['city_name'] != $this->request->post['city']){
						 $json['error']['postcode'] = 'City and Post Code does not match';
					 }
                }

				$this->load->model('localisation/country');
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}
				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}
				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}

				// Default Payment Address
				$address_id = $this->model_account_address->addAddress($this->request->post);
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
				$this->session->data['shipping_address'] = $this->session->data['payment_address']; //copy payment address
				
				// Shipment Method
				$method_data = array();
				if (isset($this->request->post['shipping_method'])) {
				$this->load->model('extension/extension');

				$results = $this->model_extension_extension->getExtensions('shipping');

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('extension/shipping/' . $result['code']);

							$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

							if ($quote) {
								$method_data[$result['code']] = array(
									'title'      => $quote['title'],
									'quote'      => $quote['quote'],
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error'],
									'cost'      => (reset($quote['quote'])['cost'])
								);
							}
						}
					}
				$sort_order = array();

				foreach ($method_data as $key => $value) {
					$sort_order[$key] = $value['cost'];
				}

				array_multisort($sort_order, SORT_ASC, $method_data);
				$method_data = reset($method_data);
				$tempSortOrder = $method_data['sort_order'];						
				$method_data = reset($method_data['quote']);
				if (empty($tempSortOrder)){
					$tempSortOrder = 0;
				}
				$method_data['sort_order'] = $tempSortOrder;
				
				//print_r($method_data); exit;
				$this->session->data['shipping_method'] = $method_data;
				}else{
					$json['error']['warning'] = $this->language->get('error_shipping');
				}
				
				$this->session->data['payment_method']['title'] = "Quotation";
				$this->session->data['payment_method']['code'] = "quotation_checkout";
				//$this->session->data['comment'] = $this->request->post['comment'];
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function populateAddress() {
		$json = array();
		if (isset($this->request->post['address_id'])){
			$this->load->model('account/address');
			$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);
			$json['firstname']         = $address_info['firstname'];
			$json['lastname']          = $address_info['lastname'];
			$json['address_1']         = $address_info['address_1'];
			$json['address_2']         = $address_info['address_2'];
			$json['company']           = $address_info['company'];
			$json['postcode']          = $address_info['postcode'];
			$json['city']              = $address_info['city'];
			$json['country_id']        = $address_info['country_id'];
			$json['zone_id']           = $address_info['zone_id'];
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}