<?php
class ModelquotationOrder extends Model {
	public function createquotation() {
		$order_data = array();

		$order_data['totals'] = array();
		$total = 0;
		$taxes = $this->quotation->getTaxes();

		$this->load->model('extension/extension');

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

				$this->{'model_qtotal_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
			}
		}

		$sort_order = array();

		foreach ($order_data['totals'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $order_data['totals']);

		$this->load->language('quotation/checkout');

		$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$order_data['store_id'] = $this->config->get('config_store_id');
		$order_data['store_name'] = $this->config->get('config_name');

		if ($order_data['store_id']) {
			$order_data['store_url'] = $this->config->get('config_url');
		} else {
			$order_data['store_url'] = HTTP_SERVER;
		}

		if ($this->customer->isLogged()) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

			$order_data['customer_id'] = $this->customer->getId();
			$order_data['customer_group_id'] = $customer_info['customer_group_id'];
			$order_data['firstname'] = $customer_info['firstname'];
			$order_data['lastname'] = $customer_info['lastname'];
			$order_data['email'] = $customer_info['email'];
			$order_data['telephone'] = $customer_info['telephone'];
			$order_data['fax'] = $customer_info['fax'];
			$order_data['custom_field'] = unserialize($customer_info['custom_field']);
		} elseif (isset($this->session->data['guest'])) {
			$order_data['customer_id'] = 0;
			$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['guest']['firstname'];
			$order_data['lastname'] = $this->session->data['guest']['lastname'];
			$order_data['email'] = $this->session->data['guest']['email'];
			$order_data['telephone'] = $this->session->data['guest']['telephone'];
			$order_data['fax'] = $this->session->data['guest']['fax'];
			$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
		}

		$order_data['payment_firstname'] =  "";
		$order_data['payment_lastname'] =   "";
		$order_data['payment_company'] = "";
		$order_data['payment_address_1'] = "";
		$order_data['payment_address_2'] = "";
		$order_data['payment_city'] = "";
		$order_data['payment_postcode'] = "";
		$order_data['payment_zone'] = "";
		$order_data['payment_zone_id'] = "";
		$order_data['payment_country'] = "";
		$order_data['payment_country_id'] = "";
		$order_data['payment_address_format'] = "";
		$order_data['payment_custom_field'] = "";
		$order_data['payment_method'] = '';
		$order_data['payment_code'] = '';

		if ($this->quotation->hasShipping()) {
			$order_data['shipping_required'] = 1;
		} else {
			$order_data['shipping_required'] = 0;
		}

		$order_data['shipping_method'] = '';
		$order_data['shipping_code'] = '';
		$order_data['shipping_firstname'] = '';
		$order_data['shipping_lastname'] = '';
		$order_data['shipping_company'] = '';
		$order_data['shipping_address_1'] = '';
		$order_data['shipping_address_2'] = '';
		$order_data['shipping_city'] = '';
		$order_data['shipping_postcode'] = '';
		$order_data['shipping_zone'] = '';
		$order_data['shipping_zone_id'] = '';
		$order_data['shipping_country'] = '';
		$order_data['shipping_country_id'] = '';
		$order_data['shipping_address_format'] = '';
		$order_data['shipping_custom_field'] = array();
		$order_data['shipping_method'] = '';
		$order_data['shipping_code'] = '';
		$order_data['products'] = array();

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

			$order_data['products'][] = array(
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
		$order_data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$order_data['vouchers'][] = array(
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

		if(isset($this->request->post['comment'])) {
			$order_data['comment'] = $this->request->post['comment'];
			$this->session->data['comment'] = $this->request->post['comment'];
		} else {
			$order_data['comment'] = "";
		}

		$data['comment'] = $order_data['comment'];

		$order_data['total'] = $total;

		if (isset($this->request->cookie['tracking'])) {
			$order_data['tracking'] = $this->request->cookie['tracking'];

			$subtotal = $this->quotation->getSubTotal();

			// Affiliate
			$this->load->model('affiliate/affiliate');

			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

			if ($affiliate_info) {
				$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
				$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
			}

			// Marketing
			$this->load->model('quotation/marketing');

			$marketing_info = $this->model_quotation_marketing->getMarketingByCode($this->request->cookie['tracking']);

			if ($marketing_info) {
				$order_data['marketing_id'] = $marketing_info['marketing_id'];
			} else {
				$order_data['marketing_id'] = 0;
			}
		} else {
			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';
		}

		$order_data['language_id'] = $this->config->get('config_language_id');
		$order_data['currency_id'] = $this->currency->getId();
		$order_data['currency_code'] = $this->currency->getCode();
		$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$order_data['forwarded_ip'] = '';
		}

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
		} else {
			$order_data['user_agent'] = '';
		}

		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
		} else {
			$order_data['accept_language'] = '';
		}

		$this->load->model('quotation/order');

		$this->session->data['quotation_id'] = $this->model_quotation_order->addquotation($order_data);

		$this->model_quotation_order->addQuotationHistory($this->session->data['quotation_id'], $this->config->get('quotation_quotation_status_id'),0);
		
	}
	
	public function addquotation($data) {
		//$this->event->trigger('pre.quotation.add', $data);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "quotation` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? serialize($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "',shipping_required = '".(int)$data['shipping_required']."', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? serialize($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', quotation_status_id = 0, quotation_under_review = 1, affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', date_expired = DATE_ADD(NOW(),INTERVAL 30 DAY), date_added = NOW(), date_modified = NOW()");
		$quotation_id = $this->db->getLastId();
		

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_product SET quotation_id = '" . (int)$quotation_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "',price_prefix = 'q', percent = '".(float)$product['price']."', qprice = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");
				$quotation_product_id = $this->db->getLastId();
				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_option SET quotation_id = '" . (int)$quotation_id . "', quotation_product_id = '" . (int)$quotation_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
		
		// Totals
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}
		//$this->event->trigger('post.quotation.add', $quotation_id);
		return $quotation_id;
	}

	public function editquotation($quotation_id, $data) {
		//$this->event->trigger('pre.quotation.edit', $data);

		$this->db->query("UPDATE `" . DB_PREFIX . "quotation` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "'");

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_product SET quotation_id = '" . (int)$quotation_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "',price_prefix = 'q', percent = percent = '".(float)$product['price']."', qprice = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

				$quotation_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_option SET quotation_id = '" . (int)$quotation_id . "', quotation_product_id = '" . (int)$quotation_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
		
		// Totals
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$quotation_id . "'");

		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}

		//$this->event->trigger('post.quotation.edit', $quotation_id);
	}

	public function deletequotation($quotation_id) {
		//$this->event->trigger('pre.quotation.delete', $quotation_id);

		// Void the quotation first
		$this->addQuotationHistory($quotation_id, 0);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "quotation` WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quotation_product` WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quotation_option` WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quotation_total` WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quotation_history` WHERE quotation_id = '" . (int)$quotation_id . "'");


		//$this->event->trigger('post.quotation.delete', $quotation_id);
	}

	public function getquotation($quotation_id) {
		$quotation_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "quotation_status` os WHERE os.quotation_status_id = o.quotation_status_id AND os.language_id = o.language_id) AS quotation_status FROM `" . DB_PREFIX . "quotation` o WHERE o.quotation_id = '" . (int)$quotation_id . "'");

		if ($quotation_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$quotation_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$quotation_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$quotation_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$quotation_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($quotation_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}

			return array(
				'quotation_id'            => $quotation_query->row['quotation_id'],
				'invoice_no'              => $quotation_query->row['invoice_no'],
				'invoice_prefix'          => $quotation_query->row['invoice_prefix'],
				'store_id'                => $quotation_query->row['store_id'],
				'store_name'              => $quotation_query->row['store_name'],
				'store_url'               => $quotation_query->row['store_url'],
				'customer_id'             => $quotation_query->row['customer_id'],
				'firstname'               => $quotation_query->row['firstname'],
				'lastname'                => $quotation_query->row['lastname'],
				'email'                   => $quotation_query->row['email'],
				'telephone'               => $quotation_query->row['telephone'],
				'fax'                     => $quotation_query->row['fax'],
				'custom_field'            => unserialize($quotation_query->row['custom_field']),
				'payment_firstname'       => $quotation_query->row['payment_firstname'],
				'payment_lastname'        => $quotation_query->row['payment_lastname'],
				'payment_company'         => $quotation_query->row['payment_company'],
				'payment_address_1'       => $quotation_query->row['payment_address_1'],
				'payment_address_2'       => $quotation_query->row['payment_address_2'],
				'payment_postcode'        => $quotation_query->row['payment_postcode'],
				'payment_city'            => $quotation_query->row['payment_city'],
				'payment_zone_id'         => $quotation_query->row['payment_zone_id'],
				'payment_zone'            => $quotation_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $quotation_query->row['payment_country_id'],
				'payment_country'         => $quotation_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $quotation_query->row['payment_address_format'],
				'payment_custom_field'    => unserialize($quotation_query->row['payment_custom_field']),
				'payment_method'          => $quotation_query->row['payment_method'],
				'payment_code'            => $quotation_query->row['payment_code'],
				'shipping_firstname'      => $quotation_query->row['shipping_firstname'],
				'shipping_lastname'       => $quotation_query->row['shipping_lastname'],
				'shipping_company'        => $quotation_query->row['shipping_company'],
				'shipping_address_1'      => $quotation_query->row['shipping_address_1'],
				'shipping_address_2'      => $quotation_query->row['shipping_address_2'],
				'shipping_postcode'       => $quotation_query->row['shipping_postcode'],
				'shipping_city'           => $quotation_query->row['shipping_city'],
				'shipping_zone_id'        => $quotation_query->row['shipping_zone_id'],
				'shipping_zone'           => $quotation_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $quotation_query->row['shipping_country_id'],
				'shipping_country'        => $quotation_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $quotation_query->row['shipping_address_format'],
				'shipping_custom_field'   => unserialize($quotation_query->row['shipping_custom_field']),
				'shipping_method'         => $quotation_query->row['shipping_method'],
				'shipping_code'           => $quotation_query->row['shipping_code'],
				'comment'                 => $quotation_query->row['comment'],
				'total'                   => $quotation_query->row['total'],
				'quotation_under_review'  => $quotation_query->row['quotation_under_review'],
				'quotation_status_id'     => $quotation_query->row['quotation_status_id'],
				'quotation_status'        => $quotation_query->row['quotation_status'],
				'affiliate_id'            => $quotation_query->row['affiliate_id'],
				'commission'              => $quotation_query->row['commission'],
				'language_id'             => $quotation_query->row['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,
				'currency_id'             => $quotation_query->row['currency_id'],
				'currency_code'           => $quotation_query->row['currency_code'],
				'currency_value'          => $quotation_query->row['currency_value'],
				'ip'                      => $quotation_query->row['ip'],
				'user_agent'              => $quotation_query->row['user_agent'],
				'date_expired'         	  => $quotation_query->row['date_expired'],
				'date_modified'           => $quotation_query->row['date_modified'],
				'date_added'              => $quotation_query->row['date_added']
			);
		} else {
			return false;
		}
	}

	public function rejectquotation($quotation_id, $comment) {
		//$this->addQuotationHistory($quotation_id,,0, $comment, 1);
		$quotation_info = $this->getquotation($quotation_id);
		$this->db->query("UPDATE `" . DB_PREFIX . "quotation` SET quotation_status_id = '" . (int) $this->config->get('quotation_quotation_reject_status_id') . "',quotation_under_review = '0', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_history SET quotation_id = '" . (int)$quotation_id . "', quotation_status_id = '" . (int)$this->config->get('quotation_quotation_reject_status_id') . "', notify = '0', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
		
		$language = new Language($quotation_info['language_directory']);
		$language->load($quotation_info['language_directory']);
		$language->load('mail/quotation');

		$subject = sprintf($language->get('text_update_subject'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'), $quotation_id);

		$message  = $language->get('text_update_quotation') . ' ' . $quotation_id . "\n";
		$message .= $language->get('text_update_date_added') . ' ' . date($language->get('l dS F Y'), strtotime($quotation_info['date_added'])) . "\n\n";

		$quotation_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int) $this->config->get('quotation_quotation_reject_status_id') . "' AND language_id = '" . (int)$quotation_info['language_id'] . "'");

		if ($quotation_status_query->num_rows) {
			$message .= $language->get('text_update_quotation_status') . "\n\n";
			$message .= $quotation_status_query->row['name'] . "\n\n";
		}

		if ($comment) {
			$message .= $language->get('text_update_comment') . "\n\n";
			$message .= strip_tags($comment) . "\n\n";
		}

		$message .= $language->get('text_update_footer');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText($message);
		$mail->send();
	}

	public function addQuotationHistory($quotation_id, $quotation_status_id, $comment = '', $notify=true) {
		//$this->event->trigger('pre.quotation.history.add', $quotation_id);
		$quotation_info = $this->getquotation($quotation_id);
		if ($quotation_info) { 
			// Fraud Detection
			$this->load->model('account/customer'); 
			$this->load->model('account/quotation'); 

			$customer_info = $this->model_account_customer->getCustomer($quotation_info['customer_id']);
			
			if ($customer_info && $customer_info['safe']) {
				$safe = true;
			} else {
				$safe = false;
			}
			/*
			if (!$safe) {
				// Ban IP
				$status = false;
				if ($quotation_info['customer_id']) { 
					$results = $this->model_account_customer->getIps($quotation_info['customer_id']); 
					foreach ($results as $result) {
						if ($this->model_account_customer->isBanIp($result['ip'])) {
							$status = true;

							break;
						}
					}
				} else {
					$status = $this->model_account_customer->isBanIp($quotation_info['ip']);
				}
				if ($status) {
					$quotation_status_id = $this->config->get('quotation_quotation_status_id');
				}

				// Anti-Fraud
				/*$this->load->model('extension/extension');
				$extensions = $this->model_extension_extension->getExtensions('fraud');
				foreach ($extensions as $extension) {
					if ($this->config->get($extension['code'] . '_status')) {
						$this->load->model('fraud/' . $extension['code']);

						$fraud_status_id = $this->{'model_fraud_' . $extension['code']}->check($quotation_info);

						if ($fraud_status_id) {
							$quotation_status_id = $fraud_status_id;
						}
					}
				}
				
			}*/
			$this->db->query("UPDATE `" . DB_PREFIX . "quotation` SET quotation_status_id = '" . (int)$quotation_status_id . "', date_modified = NOW() WHERE quotation_id = '" . (int)$quotation_id . "'");
                        
                        
			$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_history SET quotation_id = '" . (int)$quotation_id . "', quotation_status_id = '" . (int)$quotation_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
			
			$completedStatus = $this->config->get('quotation_completed');
			$ipay88Status = $this->config->get('quotation_ipay88_payment');
			$creditStatus = $this->config->get('quotation_credit_payment');
			$codStatus = $this->config->get('quotation_cod_payment');
			$bankTransferStatus = $this->config->get('quotation_banktransfer_payment');
			$paypalStatus = $this->config->get('quotation_pp_standard_payment');
			$eghlStatus = $this->config->get('quotation_ghl_payment');
			$flagOrder = 0;
			if(($quotation_status_id == $completedStatus || $quotation_status_id == $ipay88Status || $quotation_status_id == $creditStatus || $quotation_status_id == $codStatus || $quotation_status_id == $bankTransferStatus || $quotation_status_id == $paypalStatus || $quotation_status_id == $eghlStatus) && $quotation_status_id != 0){ //insert to oc_order & oc_order_product
                            if($this->config->get('config_enable_approval')) {
                                $this->db->query("UPDATE `" . DB_PREFIX . "quotation` SET approver_id = '".(int)$this->customer->getGroupId()."', approver_name = '".$this->customer->getFirstName()." ".$this->customer->getFirstName()."' WHERE quotation_id = '" . (int)$quotation_id . "'");
                            }	
                                $fExist = $this->checkOrderExists($quotation_id);
				if(!$fExist){ //if record do not exists
					$flagOrder = 1;
					$totalQty = $this->model_account_quotation->getTotalquotationProductsByquotationId($quotation_id);
					if($quotation_status_id == $completedStatus){
						$orderStatus = 5;
						$paymentMethod = "'Bank In / Others'";
						$paymentCode = "'others'";
					}
					else if($quotation_status_id == $creditStatus){
						$orderStatus = 2;
						$paymentMethod = "'Free Checkout'";
						$paymentCode = "'free_checkout'";
					}
					else if($quotation_status_id == $ipay88Status){ //ipay88
						$orderStatus = 2;
						$paymentMethod = "'iPay88 Payment Gateway'";
						$paymentCode = "'ipay88'";
					}
					else if ($quotation_status_id == $codStatus){
					$orderStatus = 1;
					$paymentMethod = "'Cash on Delivery'";
					$paymentCode = "'cod'";	
					}
					else if ($quotation_status_id == $bankTransferStatus){
					$orderStatus = 2;
					$paymentMethod = "'Bank Transfer'";
					$paymentCode = "'bank_transfer'";	
					}
					else if($quotation_status_id == $paypalStatus){
						$orderStatus = 2;
						$paymentMethod = "'Paypal Standard Payment Gateway'";
						$paymentCode = "'pp_standard'";
					}
					else if($quotation_status_id == $eghlStatus){
						$orderStatus = 2;
						$paymentMethod = "'eGHL Payment Gateway'";
						$paymentCode = "'ghl'";
					}
					$extra = "\r\nQuotation ID: #".$quotation_id;
					$formatcomment = $this->db->escape($comment);
					$sql = "INSERT INTO " . DB_PREFIX . "order (invoice_no, invoice_prefix, store_id, store_name, store_url, customer_id, customer_group_id, firstname, lastname, email, telephone, fax, custom_field, payment_firstname, payment_lastname , payment_company, payment_address_1, payment_address_2, payment_city, payment_postcode, payment_country, payment_country_id, payment_zone, payment_zone_id, payment_address_format, payment_custom_field, payment_method, payment_code, shipping_firstname, shipping_lastname, shipping_company, shipping_address_1, shipping_address_2, shipping_city, shipping_postcode, shipping_country, shipping_country_id, shipping_zone, shipping_zone_id, shipping_address_format, shipping_custom_field, shipping_method, shipping_code, comment, total, order_status_id, language_id, currency_id, currency_code, currency_value, ip, user_agent, date_added, date_modified)
							SELECT invoice_no, invoice_prefix, store_id, store_name, store_url, customer_id, customer_group_id, firstname, lastname, email, telephone, fax, custom_field, payment_firstname, payment_lastname , payment_company, payment_address_1, payment_address_2, payment_city, payment_postcode, payment_country, payment_country_id, payment_zone, payment_zone_id, payment_address_format, payment_custom_field, $paymentMethod, $paymentCode, shipping_firstname, shipping_lastname, shipping_company, shipping_address_1, shipping_address_2, shipping_city, shipping_postcode, shipping_country, shipping_country_id, shipping_zone, shipping_zone_id, shipping_address_format, shipping_custom_field, shipping_method, shipping_code, CONCAT('$formatcomment', '$extra'), total, $orderStatus, language_id, currency_id, currency_code, currency_value, ip, user_agent, date_added, date_modified
							FROM " . DB_PREFIX . "quotation WHERE quotation_id = " . $quotation_id;
					$this->db->query($sql);
					$orderId = $this->db->getLastId();
                                        
                                        $this->load->model('checkout/order');
                                        $this->load->model('catalog/product');
                                        
                                        $this->session->data['order_id'] = $orderId;
                                        
                                        $this->db->query("UPDATE " . DB_PREFIX . "quotation SET order_id = '" . (int)$orderId . "' WHERE quotation_id = '" . $quotation_id . "'");
				
                                        $dataUpdate = array();
                                        //$dataUpdate['unique_order_id'] = constant("PARTNER_UNIQUE_ID")."@".$intOrderID;
                                        $dataUpdate['unique_order_id'] = $this->config->get('config_unique_brp_partner_id')."@".$orderId;
                                        $this->model_checkout_order->editOrderUniqueID($orderId, $dataUpdate);
					
					$quotation_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");
					foreach ($quotation_product_query->rows as $product) {
                                                $product_data = $this->model_catalog_product->getProduct($product['product_id']);
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . $orderId . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', data_source = '" . $product_data['data_source'] . "', model = '" . $this->db->escape($product['model']) . "', matching_code = '" . $this->db->escape($product_data['matching_code']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");
					}
					
					$quotation_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quotation_total` WHERE quotation_id = '" . (int)$quotation_id . "' ORDER BY sort_order ASC");
					foreach ($quotation_total_query->rows as $total) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $orderId . "', code = '".$total['code']."', title = '".$total['title']."', value = '".$total['value']."', sort_order = '".$total['sort_order']."'");
					}
					if ($quotation_status_id == $bankTransferStatus){
						$this->load->language('extension/payment/bank_transfer');
						$ordercomment  = $this->language->get('text_instruction') . "\n\n";
						$ordercomment .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "\n\n";
						$ordercomment .= $this->language->get('text_payment');
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . $orderId . "', order_status_id = '" . $orderStatus . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($ordercomment) . "', date_added = NOW()");
					}
					if($quotation_status_id == $creditStatus){
						$amount = -$quotation_info['total'];
						$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '".$quotation_info['customer_id']."', order_id = '". $orderId ."', description = 'Order ID: #". $orderId ."', amount = '".$amount."', date_added = NOW()");
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . $orderId . "', code = 'credit', title = 'Store Credit', value = '" . $amount . "', sort_order = 7");
						$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = 0 WHERE order_id = '" . $orderId . "' AND code = 'total'");
					}
				}
			}
                        
                        $this->load->model('account/customer');
                
                        $parentsEmail = $this->model_account_customer->custParents();
                        
                        foreach($parentsEmail AS $peKey => $peVal) {
                            if($this->model_account_quotation->getQuotationSuperAdmin($peKey)) {
                                unset($parentsEmail[$peKey]);
                            }
                        }
                        
                        if($this->model_account_quotation->getQuotationSuperAdmin()) {
                            $arrGroupMates = $this->model_account_quotation->getGroupMates((int)$this->customer->getGroupId());
                            foreach($arrGroupMates AS $gmKey => $gmVal) {
                                $parentsEmail[$gmVal['customer_id']] = $gmVal['email'];
                            }
                        }
			
			//send email on new quotation order
			if (!$quotation_info['quotation_status_id'] && $quotation_status_id) {
				// Check for any downloadable products
				$download_status = false;

				$quotation_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");
				foreach ($quotation_product_query->rows as $quotation_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$quotation_product['product_id'] . "'");

					if ($product_download_query->row['total']) {
						$download_status = true;
					}
				}

				// Load the language for any mails that might be required to be sent out
				$language = new Language($quotation_info['language_directory']);
				$language->load($quotation_info['language_directory']);
				$language->load('mail/quotation');

				$quotation_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int)$quotation_status_id . "' AND language_id = '" . (int)$quotation_info['language_id'] . "'");

				if ($quotation_status_query->num_rows) {
					$quotation_status = $quotation_status_query->row['name'];
				} else {
					$quotation_status = '';
				}

				$subject = sprintf($language->get('text_new_subject'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'), $quotation_id);

				// HTML Mail
				$data = array();
				$data['title'] = sprintf($language->get('text_new_subject'), $quotation_info['store_name'], $quotation_id);
				$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $quotation_info['store_name']);
				$data['text_link'] = $language->get('text_new_link');
				$data['text_download'] = $language->get('text_new_download');
				$data['text_quotation_detail'] = $language->get('text_new_quotation_detail');
				$data['text_instruction'] = $language->get('text_new_instruction');
				$data['text_quotation_id'] = $language->get('text_new_quotation_id');
				$data['text_date_added'] = $language->get('text_new_date_added');
				$data['text_payment_method'] = $language->get('text_new_payment_method');
				$data['text_shipping_method'] = $language->get('text_new_shipping_method');
				$data['text_email'] = $language->get('text_new_email');
				$data['text_telephone'] = $language->get('text_new_telephone');
				$data['text_ip'] = $language->get('text_new_ip');
				$data['text_quotation_status'] = $language->get('text_new_quotation_status');
				$data['text_payment_address'] = $language->get('text_new_payment_address');
				$data['text_shipping_address'] = $language->get('text_new_shipping_address');
				$data['text_product'] = $language->get('text_new_product');
				$data['text_image'] = $language->get('text_new_image');
				$data['text_model'] = $language->get('text_new_model');
				$data['text_quantity'] = $language->get('text_new_quantity');
				$data['text_price'] = $language->get('text_new_price');
				$data['text_total'] = $language->get('text_new_total');
				$data['text_gst'] = $language->get('text_new_gst');
				$data['text_footer'] = $language->get('text_new_footer');
				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $quotation_info['store_name'];
				$data['store_url'] = $quotation_info['store_url'];
				$data['customer_id'] = $quotation_info['customer_id'];
				$data['link'] = $quotation_info['store_url'] . 'index.php?route=account/quotation/info&quotation_id=' . $quotation_id;

				if ($download_status) {
					$data['download'] = $quotation_info['store_url'] . 'index.php?route=account/download';
				} else {
					$data['download'] = '';
				}

				$data['quotation_id'] = $quotation_id;
				$data['date_added'] = date($language->get('l dS F Y'), strtotime($quotation_info['date_added']));
				$data['payment_method'] = $quotation_info['payment_method'];
				$data['shipping_method'] = $quotation_info['shipping_method'];
				$data['email'] = $quotation_info['email'];
				$data['telephone'] = $quotation_info['telephone'];
				$data['name'] = $quotation_info['firstname'].' '.$quotation_info['lastname'];
				$data['company'] = $quotation_info['payment_company'];
				$data['address'] = $quotation_info['payment_address_1'].', '.$quotation_info['payment_city'].', '.$quotation_info['payment_postcode'].', '.$quotation_info['payment_zone'];
				$data['quotation_status'] = $quotation_status;
				$data['quotation_under_review'] = 0;

				if ($comment && $notify) {
					$data['comment'] = nl2br($comment);
				} else {
					$data['comment'] = '';
				}

				if ($quotation_info['payment_address_format']) {
					$format = $quotation_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
				$replace = array(
					'firstname' => $quotation_info['payment_firstname'],
					'lastname'  => $quotation_info['payment_lastname'],
					'company'   => $quotation_info['payment_company'],
					'address_1' => $quotation_info['payment_address_1'],
					'address_2' => $quotation_info['payment_address_2'],
					'city'      => $quotation_info['payment_city'],
					'postcode'  => $quotation_info['payment_postcode'],
					'zone'      => $quotation_info['payment_zone'],
					'zone_code' => $quotation_info['payment_zone_code'],
					'country'   => $quotation_info['payment_country']
				);

				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($quotation_info['shipping_address_format']) {
					$format = $quotation_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				$replace = array(
					'firstname' => $quotation_info['shipping_firstname'],
					'lastname'  => $quotation_info['shipping_lastname'],
					'company'   => $quotation_info['shipping_company'],
					'address_1' => $quotation_info['shipping_address_1'],
					'address_2' => $quotation_info['shipping_address_2'],
					'city'      => $quotation_info['shipping_city'],
					'postcode'  => $quotation_info['shipping_postcode'],
					'zone'      => $quotation_info['shipping_zone'],
					'zone_code' => $quotation_info['shipping_zone_code'],
					'country'   => $quotation_info['shipping_country']
				);

				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				$this->load->model('tool/upload');

				// Products
				$data['products'] = array();

				foreach ($quotation_product_query->rows as $product) {
					$this->load->model('tool/image');
					$image = $this->model_account_quotation->getProductImage($product['product_id']);

					$option_data = array();
					$quotation_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_product_id = '" . (int)$product['quotation_product_id'] . "'");
					foreach ($quotation_option_query->rows as $option) {
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
						'name'     => $product['name'],
						'model'    => $product['model'],
						'thumb'    => $image,
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
						'gst'      => ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0)
					);
				}

				// quotation Totals
				$quotation_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quotation_total` WHERE quotation_id = '" . (int)$quotation_id . "' ORDER BY sort_order ASC");
				foreach ($quotation_total_query->rows as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
					);
				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/quotation.tpl')) {
					$html = $this->load->view($this->config->get('config_template') . '/template/mail/quotation.tpl', $data);
				} else {
					$html = $this->load->view('mail/quotation.tpl', $data);
				}

				// Text Mail
				$text  = sprintf($language->get('text_new_greeting'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				$text .= $language->get('text_new_quotation_id') . ' ' . $quotation_id . "\n";
				$text .= $language->get('text_new_date_added') . ' ' . date($language->get('l dS F Y'), strtotime($quotation_info['date_added'])) . "\n";
				$text .= $language->get('text_new_quotation_status') . ' ' . $quotation_status . "\n\n";

				if ($comment && $notify) {
					$text .= $language->get('text_new_instruction') . "\n\n";
					$text .= $comment . "\n\n";
				}

				// Products
				$text .= $language->get('text_new_products') . "\n";

				foreach ($quotation_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					$quotation_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_product_id = '" . $product['quotation_product_id'] . "'");

					foreach ($quotation_option_query->rows as $option) {
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
						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}

				$text .= "\n";
				$text .= $language->get('text_new_quotation_total') . "\n";

				foreach ($quotation_total_query->rows as $total) {
					$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				}

				$text .= "\n";
				if ($quotation_info['customer_id']) {
					$text .= $language->get('text_new_link') . "\n";
					$text .= $quotation_info['store_url'] . 'index.php?route=account/quotation/info&quotation_id=' . $quotation_id . "\n\n";
				}

				if ($download_status) {
					$text .= $language->get('text_new_download') . "\n";
					$text .= $quotation_info['store_url'] . 'index.php?route=account/download' . "\n\n";
				}

				// Comment
				if ($quotation_info['comment']) {
					$text .= $language->get('text_new_comment') . "\n\n";
					$text .= $quotation_info['comment'] . "\n\n";
				}

				$text .= $language->get('text_new_footer') . "\n\n";

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                                
                                

				$mail->setTo($quotation_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($html);
				$mail->setText($text);
				$mail->send();
                                
                                if($this->config->get('config_enable_approval')) {
                                    foreach ($parentsEmail as $email) {
                                            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                    $mail->setTo($email);
                                                    $mail->send();
                                            }
                                    }
                                }
				
				// Admin Alert Mail
				
				if (in_array('quotation', (array)$this->config->get('config_mail_alert'))) {
					$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $quotation_id);

					// HTML Mail
					$data['text_greeting'] = $language->get('text_new_received');

					if ($comment) {
						if ($quotation_info['comment']) {
							$data['comment'] = nl2br($comment) . '<br/><br/>' . $quotation_info['comment'];
						} else {
							$data['comment'] = nl2br($comment);
						}
					} else {
						if ($quotation_info['comment']) {
							$data['comment'] = $quotation_info['comment'];
						} else {
							$data['comment'] = '';
						}
					}

					$data['text_download'] = '';
					$data['text_footer'] = '';
					$data['text_link'] = '';
					$data['link'] = $quotation_info['store_url'] . 'admin/index.php?route=sale/quotation/info&quotation_id=' . $quotation_id;
					$data['download'] = '';

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/quotation.tpl')) {
						$html = $this->load->view($this->config->get('config_template') . '/template/mail/quotation.tpl', $data);
					} else {
						$html = $this->load->view('mail/quotation.tpl', $data);
					}

					// Text
					$text  = $language->get('text_new_received') . "\n\n";
					$text .= $language->get('text_new_quotation_id') . ' ' . $quotation_id . "\n";
					$text .= $language->get('text_new_date_added') . ' ' . date($language->get('l dS F Y'), strtotime($quotation_info['date_added'])) . "\n";
					$text .= $language->get('text_new_quotation_status') . ' ' . $quotation_status . "\n\n";
					$text .= $language->get('text_new_products') . "\n";

					foreach ($quotation_product_query->rows as $product) {
						$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
						$quotation_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_product_id = '" . $product['quotation_product_id'] . "'");

						foreach ($quotation_option_query->rows as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
							}
							$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
						}
					}

					$text .= "\n";
					$text .= $language->get('text_new_quotation_total') . "\n";

					foreach ($quotation_total_query->rows as $total) {
						$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					}

					$text .= "\n";
					if ($quotation_info['comment']) {
						$text .= $language->get('text_new_comment') . "\n\n";
						$text .= $quotation_info['comment'] . "\n\n";
					}

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);
					$mail->setText($text);
					$mail->send();
					/*
					// Send to additional alert emails
					$emails = explode(',', $this->config->get('config_mail_alert'));
					foreach ($emails as $email) {
						if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
							$mail->setTo($email);
							$mail->send();
						}
					}
					*/
					// Send to additional alert emails
					$emails = explode(',', $this->config->get('config_alert_email'));
	
                                            foreach ($emails as $email) {
                                                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                            $mail->setTo($email);
                                                            $mail->send();
                                                    }
                                            }
                                        }
				}
			// If quotation status is not 0 then send update text email
			if ($flagOrder == 1 && $notify) {
				$language = new Language($quotation_info['language_directory']);
				$language->load($quotation_info['language_directory']);
				$language->load('mail/order');
				
				if(isset($orderId)){ //send order received email
					if($customer_info['customer_group_id'] != 10)
						$subject = sprintf($language->get('text_new_subject'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'), $orderId);
					else
						$subject = sprintf($language->get('text_new_subject_credit'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'), $orderId);

					// HTML Mail
					$data = array();
					$data['title'] = sprintf($language->get('text_new_subject'), $quotation_info['store_name'], $orderId);
					$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $quotation_info['store_name']);
					$data['text_link'] = $language->get('text_new_link');
					$data['text_download'] = $language->get('text_new_download');
					$data['text_order_detail'] = $language->get('text_new_order_detail');
					$data['text_instruction'] = $language->get('text_new_instruction');
					$data['text_order_id'] = $language->get('text_new_order_id');
					$data['text_date_added'] = $language->get('text_new_date_added');
					$data['text_payment_method'] = $language->get('text_new_payment_method');
					$data['text_shipping_method'] = $language->get('text_new_shipping_method');
					$data['text_email'] = $language->get('text_new_email');
					$data['text_telephone'] = $language->get('text_new_telephone');
					$data['text_ip'] = $language->get('text_new_ip');
					$data['text_order_status'] = $language->get('text_new_order_status');
					$data['text_payment_address'] = $language->get('text_new_payment_address');
					$data['text_shipping_address'] = $language->get('text_new_shipping_address');
					$data['text_product'] = $language->get('text_new_product');
					$data['text_model'] = $language->get('text_new_model');
					$data['text_quantity'] = $language->get('text_new_quantity');
					$data['text_price'] = $language->get('text_new_price');
					$data['text_total'] = $language->get('text_new_total');
					$data['text_gst'] = $language->get('text_new_gst');
					$data['text_footer'] = $language->get('text_new_footer');
					$data['logo'] = $this->config->get('config_url').'image/'.$this->config->get('config_logo');
					$data['store_name'] = $quotation_info['store_name'];
					$data['store_url'] = $quotation_info['store_url'];
					$data['customer_id'] = $quotation_info['customer_id'];
					$data['link'] = $quotation_info['store_url'] . 'index.php?route=account/order/info&order_id='.$orderId;
					$data['download'] = '';
					
					$data['order_id'] = $orderId;
					$data['date_added'] = date($language->get('l dS F Y'), strtotime($quotation_info['date_added']));
					$data['payment_method'] = $paymentMethod;
					$data['shipping_method'] = $quotation_info['shipping_method'];
					$data['email'] = $quotation_info['email'];
					$data['telephone'] = $quotation_info['telephone'];
					$data['ip'] = $quotation_info['ip'];
					
					if ($orderStatus == 2){
						$orderStatusText = 'Processing';
					} else if ($orderStatus == 5){
						$orderStatusText = 'Completed';
					} else if ($orderStatus == 1){
						$orderStatusText = 'Pending';
					} else {
						$orderStatusText = $orderStatus;
					}
					$data['order_status'] = $orderStatusText;
					$data['comment'] = nl2br($comment)."<br>Quotation ID: #".$quotation_id;
					
					if($quotation_info['payment_address_format']) {
						$format = $quotation_info['payment_address_format'];
					} else {
						$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
					}
					$find = array(
						'{firstname}','{lastname}','{company}','{address_1}','{address_2}','{city}','{postcode}','{zone}','{zone_code}','{country}'
					);
					$replace = array(
						'firstname' => $quotation_info['payment_firstname'],
						'lastname'  => $quotation_info['payment_lastname'],
						'company'   => $quotation_info['payment_company'],
						'address_1' => $quotation_info['payment_address_1'],
						'address_2' => $quotation_info['payment_address_2'],
						'city'      => $quotation_info['payment_city'],
						'postcode'  => $quotation_info['payment_postcode'],
						'zone'      => $quotation_info['payment_zone'],
						'zone_code' => $quotation_info['payment_zone_code'],
						'country'   => $quotation_info['payment_country']
					);
					$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

					if ($quotation_info['shipping_address_format']) {
						$format = $quotation_info['shipping_address_format'];
					} else {
						$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
					}
					$replace = array(
						'firstname' => $quotation_info['shipping_firstname'],
						'lastname'  => $quotation_info['shipping_lastname'],
						'company'   => $quotation_info['shipping_company'],
						'address_1' => $quotation_info['shipping_address_1'],
						'address_2' => $quotation_info['shipping_address_2'],
						'city'      => $quotation_info['shipping_city'],
						'postcode'  => $quotation_info['shipping_postcode'],
						'zone'      => $quotation_info['shipping_zone'],
						'zone_code' => $quotation_info['shipping_zone_code'],
						'country'   => $quotation_info['shipping_country']
					);
					$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
					
					// Products
					$data['products'] = array();
					foreach ($quotation_product_query->rows as $product) {
						$option_data = array();
						$quotation_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_product_id = '" . (int)$product['quotation_product_id'] . "'");
						foreach ($quotation_option_query->rows as $option) {
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
							'name'     => $product['name'],
							'model'    => $product['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'price'    => $this->currency->format($product['price'], $quotation_info['currency_code'], $quotation_info['currency_value']),
							'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
							'gst'      => ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0)
						);
					}
					
					// Totals
					$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . $orderId . "' ORDER BY sort_order ASC");
					foreach ($order_total_query->rows as $total) {
						$data['totals'][] = array(
							'title' => $total['title'],
							'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
						);
					}
					$data['vouchers'] = array();
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
						$html = $this->load->view($this->config->get('config_template') . '/template/mail/order.tpl', $data);
					} else {
						$html = $this->load->view('mail/order.tpl', $data);
					}
					// Text Mail
					$text = '';
					$text .= $language->get('text_new_order_id') . ' ' . $orderId . "\n";
					$text .= $language->get('text_new_date_added') . ' ' . date($language->get('l dS F Y'), strtotime($quotation_info['date_added'])) . "\n";
					$text .= $language->get('text_new_order_status') . ' ' . @$order_status . "\n\n";

					if ($comment) {
						$text .= $language->get('text_new_instruction') . "\n\n";
						$text .= $comment . "\n\n";
					}
					$text .= $language->get('text_new_products') . "\n";
					foreach ($quotation_product_query->rows as $product) {
						$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
						$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$orderId . "' AND order_product_id = '" . @$product['order_product_id'] . "'");
						foreach ($quotation_option_query->rows as $option) {
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
							$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
						}
					}
					$text .= "\n";
					$text .= $language->get('text_new_order_total') . "\n";
					foreach ($order_total_query->rows as $total) {
						$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					}
					$text .= "\n";
					
					if ($quotation_info['customer_id']) {
						$text .= $language->get('text_new_link') . "\n";
						$text .= $quotation_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $orderId . "\n\n";
					}
					if ($quotation_info['comment']) {
						$text .= $language->get('text_new_comment') . "\n\n";
						$text .= $quotation_info['comment'] . "\n\n";
					}
					$text .= $language->get('text_new_footer') . "\n\n";
					
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);
					$mail->setText($text);
					
					//send mail to customer
					$textHeading1 = sprintf($language->get('text_new_greeting'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
					$mail->setText($textHeading1.$text);
					$mail->setTo($quotation_info['email']);
					$mail->send();
                                        
                                        if($this->config->get('config_enable_approval')) {
                                            foreach ($parentsEmail as $email) {
                                                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                            $mail->setTo($email);
                                                            $mail->send();
                                                    }
                                            }
                                        }
					
					//send email copy to admin
					$textHeading2 = $language->get('text_new_received') . "\n\n";
					$mail->setText($textHeading2.$text);
					$mail->setTo($this->config->get('config_email'));
					$mail->send();
				}
			}
			else if($quotation_info['quotation_status_id'] && $quotation_status_id && $notify){ //send update email
				$language = new Language($quotation_info['language_directory']);
				$language->load($quotation_info['language_directory']);
				$language->load('mail/quotation');
				
				$subject = sprintf($language->get('text_update_subject'), html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'), $quotation_id);
				if(isset($orderId)){
					if(!empty($orderId)){
						$subject .= ", Order ID: #".$orderId;
					}
				}
				$message  = $language->get('text_update_quotation') . ' ' . $quotation_id . "\n";
				$message .= $language->get('text_update_date_added') . ' ' . date('l dS F Y', strtotime($quotation_info['date_added'])) . "\n\n";
				
				$quotation_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int)$quotation_status_id . "' AND language_id = '" . (int)$quotation_info['language_id'] . "'");
				
				if ($quotation_status_query->num_rows) {
					$message .= $language->get('text_update_quotation_status') . "\n";
					$message .= $quotation_status_query->row['name'] . "\n\n";
				}
				if ($this->config->get('config_pdf_attachment')) {
					$message .= $language->get('text_update_pdf') . "\n\n";
				}
				if ($quotation_info['customer_id']) {
					$message .= $language->get('text_update_link') . "\n";
					$message .= $quotation_info['store_url'] . 'index.php?route=account/quotation/info&quotation_id=' . $quotation_id . "\n\n";
				}
				if ($comment) {
					$message .= $language->get('text_update_comment') . "\n\n";
					$message .= strip_tags($comment) . "\n\n";
				}
				$message .= $language->get('text_update_footer');
				//$this->log->write($message);
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($quotation_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				
				//send mail to customer
				$mail->setTo($quotation_info['email']);
				$mail->send();
                                
                                if($this->config->get('config_enable_approval')) {
                                    foreach ($parentsEmail as $email) {
                                            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                    $mail->setTo($email);
                                                    $mail->send();
                                            }
                                    }
                                }
				
				//send email copy to admin
				$mail->setTo($this->config->get('config_email'));
				$mail->send();
			}
		}

		//$this->event->trigger('post.quotation.history.add', $quotation_id);
	}

	public function createPdf($quotation_id) {
		$this->load->language('account/quotation');
		$quotation_id = $quotation_id;
		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
		  $data['base'] = HTTPS_SERVER;
		} else {
		  $data['base'] = HTTP_SERVER;
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
		  $data['logo'] = HTTP_SERVER . 'image/' . $this->config->get('config_logo');
		} else {
		  $data['logo'] = '';
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_quotation_detail'] = $this->language->get('text_quotation_detail');
		$data['text_quotation_id'] = $this->language->get('text_quotation_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');

		$data['customer_name'] = $this->language->get('customer_name');
		$data['text_store_info'] = $this->language->get('storeinfo');
		$data['customer_info'] = $this->language->get('customerinfo');
		$data['discount_done'] = $this->language->get('discount_done');

		$data['column_product'] = $this->language->get('column_product_name');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_qprice'] = $this->language->get('column_qprice');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_comment'] = $this->language->get('column_comment');
		
		require_once('tcpdf/tcpdf.php');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->config->get('config_owner'));
		$pdf->SetTitle('quotation PDF');
		$pdf->SetSubject('PDF Invoice');
		$pdf->SetKeywords('TCPDF, PDF Invoice');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('sans-serif', '', 18);

		$this->load->model('account/quotation');
		$this->load->model('setting/setting');
		$data['quotation_actualprice'] = $this->config->get('quotation_actualprice');

		$quotation_info = $this->model_account_quotation->getquotation($quotation_id);

		if ($quotation_info) {
		    $store_info = $this->model_setting_setting->getSetting('config', $quotation_info['store_id']);

		    if ($store_info) {
		      $store_address = $store_info['config_address'];
		      $store_email = $store_info['config_email'];
		      $store_telephone = $store_info['config_telephone'];
		      $store_fax = $store_info['config_fax'];
		    } else {
		      $store_address = $this->config->get('config_address');
		      $store_email = $this->config->get('config_email');
		      $store_telephone = $this->config->get('config_telephone');
		      $store_fax = $this->config->get('config_fax');
		    }

		    $this->load->model('tool/upload');
		    $this->load->model('tool/image');

		    $product_data = array();

		    $products = $this->model_account_quotation->getquotationProducts($quotation_id);
		    $discounttotal = 0;
		    foreach ($products as $product) {
		      $option_data = array();
		      $options = $this->model_account_quotation->getquotationOptions($this->request->get['quotation_id'], $product['quotation_product_id']);

		      foreach ($options as $option) {
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
		          'value' => $value
		        );
		      }

		     $image = $this->model_account_quotation->getProductImage($product['product_id']);
		      if (is_file(DIR_IMAGE . $image)) {
		        $image = $this->model_tool_image->resize($image, 60, 60);
		        $image = str_replace(" ","%20",$image);
		      } else {
		        $image = $this->model_tool_image->resize('no_image.png', 60, 60);
		      }
			  
		      $product_data[] = array(
		        'name'     => $product['name'],
		        'thumb'     => $image,
		        'model'    => $product['model'],
		        'option'   => $option_data,
		        'quantity' => $product['quantity'],
		        'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
		        'qprice'    => $this->currency->format($product['qprice'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value'])
		      );
		      $discounttotal = $discounttotal + (($product['price'] - $product['qprice']) * $product['quantity']);
		    }
		    $discounttotal = $this->currency->format($discounttotal,$quotation_info['currency_code'], $quotation_info['currency_value']);
		    $pdf->AddPage(); $tbl = "";
		    $voucher_data = array();

		    $total_data = array();
		    $totals = $this->model_account_quotation->getquotationTotals($quotation_id);
		    foreach ($totals as $total) {
		      $total_data[] = array(
		        'title' => $total['title'],
		        'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
		      );
		    } 

		    $quotationcomment = $this->model_account_quotation->getLatestComment($quotation_id);
		    
		    if($data['logo']) { 
				$tbl .=  '<table cellpadding="1" cellspacing="1" border="0" ><tr style="text-align:left;"><td><img src="' . $data['logo'] . '" border="0" width="150px" /></td></tr></table>';
			}

			$tbl .= '<table border="0.2" cellpadding="4">';

			$tbl .= '<tbody><tr><td><b>'.$quotation_info['store_name'].'</b><br/><b>Address: </b> '.nl2br($store_address)."</td>";

			$tbl .= '<td><b>'.$data['text_date_added'].'</b> '.date($this->language->get('date_format_short'), strtotime($quotation_info['date_added'])).'<br />';
		    $tbl .= '<b>' . $data['text_quotation_id'].'</b> '.$quotation_id.'<br />';

		    $tbl .= '</td></tr></tbody></table>';

		    $tbl .= '<table border="0.2" cellpadding="4">';

		    $tbl .=  '<tr><td><b>'.$data['text_store_info'].'</b></td><td><b>'.$data['customer_info'].'</b></td></tr>';

			$tbl .= '<tr><td style="width: 50%;"><b>'.$data['text_telephone'].'</b> '.$store_telephone . '<br />';
		        
	        if ($store_fax) { 
	          $tbl .= '<b>'.$data['text_fax'].'</b>'.$store_fax.'<br /> ';
	        }

	        $tbl .= '<b>' . $data['text_email'] . '</b>  '.rtrim($store_email, '/').'<br/><b>'.$data['text_website'].'</b><a href=' . rtrim($quotation_info['store_url'], '/') . '>'.rtrim($quotation_info['store_url'], '/').'</a></td>';

	        $tbl .= '<td style="width: 50%;">';

	         $tbl .= '<b>'.$data['customer_name'].'</b> '.$quotation_info['firstname'] .' '.$quotation_info['lastname'].'<br />';
	        
	        if ($quotation_info['telephone']) { 
	          $tbl .= '<b>'.$data['text_telephone'].'</b> '.$quotation_info['telephone'] . '<br />';
	        }

	        $tbl .= '<b>' . $data['text_email'] . '</b>  '.$quotation_info['email']."</td></tr>";

			$tbl .= '</table>';
		   	if(!$data['quotation_actualprice']) {
	          $tbl .= '<table border="0.2" cellpadding="4" ><thead><tr><td style="width: 12%;" align="left"><b>' . $data['column_image'] . '</b></td><td  style="width: 38%;" align="left"><b>' . $data['column_product'] . '</b></td><td style="width: 14%;" align="left"><b>' . $data['column_model'] . '</b></td><td style="width: 8%;" align="right"><b>' . $data['column_quantity'] . '</b></td><td style="width: 16%;" align="right"><b>' . $data['column_qprice'] . '</b></td><td align="right" style="width: 12%;"><b>' . $data['column_total'] . '</b></td></tr></thead>';
	        } else {
	          $tbl .= '<table border="0.2" cellpadding="4" ><thead><tr><td style="width: 12%;" align="left"><b>' . $data['column_image'] . '</b></td><td  style="width: 38%;" align="left"><b>' . $data['column_product'] . '</b></td><td style="width: 8%;" align="right"><b>' . $data['column_quantity'] . '</b></td><td style="width: 15%;" align="center"><b>' . $data['column_price'] . '</b></td><td style="width: 15%;" align="center"><b>' . $data['column_qprice'] . '</b></td><td align="right" style="width: 12%;"><b>' . $data['column_total'] . '</b></td></tr></thead>';
	        }
	        $tbl .= '<tbody>';
			foreach ($product_data as $product) { 
				if(!$data['quotation_actualprice']) {
					$tbl .= '<tr style="font-size:18px;"><td style="width: 10%;" align="left">';
				    if($product['thumb']) {
				    	$tbl .='<img src="'.$product['thumb'].'" alt="'.$product['name'].'" title="'.$product['name'].'" class="img-thumbnail" />';
				    }
				    $tbl .= '</td>';
					$tbl .= '<td style="width: 36%;" align="left">'.$product['name'];
					foreach ($product['option'] as $option) {
						$tbl .= '<br /><small> - ' . $option['name'] . ': ' . $option['value'] . '</small>';
					}
					$tbl .= ' </td><td style="width: 14%; font-size:16px;" align="left">' . $product['model'] . '</td>
							<td style="width: 8%;" align="right">' . $product['quantity'] . 
							'</td><td style="width: 16%;" align="right">' . $product['qprice'] . 
							'</td><td style="width: 16%;" align="right">' . $product['total'] . '</td></tr>';
				} else {
					$tbl .= '<tr><td style="width: 10%;" align="left">';
				    if($product['thumb']) {
				    	$tbl .='<img src="'.$product['thumb'].'" alt="'.$product['name'].'" title="'.$product['name'].'" class="img-thumbnail" />';
				    }
				    $tbl .= '</td>';
					$tbl .= '<td style="width: 36%;" align="left">'.$product['name'];
					foreach ($product['option'] as $option) {
						$tbl .= '<br /><small> - ' . $option['name'] . ': ' . $option['value'] . '</small>';
					}
					$tbl .= '<br /><small> - ' . $product['model'] . '</small>';
					$tbl .= ' </td><td style="width: 8%;" align="right">' . $product['quantity'] . 
							'</td><td style="width: 15%;" align="right">' . $product['price'] . 
							'</td><td style="width: 15%;" align="right">' . $product['qprice'] . 
							'</td><td style="width: 16%;" align="right">' . $product['total'] . '</td></tr>';
				}	
			}

		    foreach ($voucher_data as $voucher) { 
		      $tbl .= '<tr><td>' . $voucher['description'] . '</td><td></td><td align="right">1</td><td align="right">' . $voucher['amount'] . '</td><td align="right">' . $voucher['amount'] . '</td></tr>';
		    }
		    if($this->config->get('quotation_discountdone')) {
		     $tbl .= '<tr><td align="right" colspan="5"><b>'.$data['discount_done'].'</b></td><td align="right">' . $discounttotal . '</td></tr>';
		 	}
		    foreach ($total_data as $total) { 
		      $tbl .= '<tr><td align="right" colspan="5"><b>' . $total['title'] . '</b></td><td align="right">' . $total['text'] . '</td></tr>';
		    }
		    $tbl .= '</tbody></table>';
		    if (isset($quotationcomment['comment']) && $quotationcomment['comment'] != "" && $quotationcomment['comment'] != 0) { 
		      $tbl .= '<table border="1" cellpadding="4"><thead><tr><td><b>' . $data['column_comment'] . '</b></td></tr></thead><tbody><tr><td>' .  nl2br($quotationcomment['comment']) . '</td></tr></tbody></table>'; 
		    }
		    $pdf->writeHTML($tbl, true, false, true, false, '');
		  }
		  $filename = DIR_DOWNLOAD."pdf/quotation_".$quotation_id.".pdf";
		  //pdf email data
		  //$this->log->write(print_r($tbl,true));
		  
		  $pdf->Output($filename, 'f');
	}

	public function updatepricedetails($quotation_id,$data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "'");

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_product SET quotation_id = '" . (int)$quotation_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "',price_prefix = '".$product['price_prefix']."', percent = '".(float)$product['percent']."', qprice = '" . (float)$product['qprice'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

				$quotation_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_option SET quotation_id = '" . (int)$quotation_id . "', quotation_product_id = '" . (int)$quotation_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}
	}
	
	public function updatetotaldetails($quotation_id,$totals) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$quotation_id . "'");

		if (isset($totals)) {
			foreach ($totals as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_total SET quotation_id = '" . (int)$quotation_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		
			if($total['code'] == "total") {

				$this->db->query("UPDATE " . DB_PREFIX . "quotation SET `total` = '" . (float)$total['value'] . "' WHERE quotation_id = '".(int)$quotation_id."'");
			} 
		}
	}

	public function myFloats($str) {
	  $str = str_replace(",", "", $str);
	  if(preg_match("#([0-9\.]+)#", $str, $match)) {
	    return floatval($match[0]);
	  } else {
	    return floatval($str);
	  }
	}

	public function getquotationReview($quotation_id) {
		$query = $this->db->query("SELECT quotation_under_review FROM " . DB_PREFIX . "quotation WHERE quotation_id = '" . (int)$quotation_id . "'");
		return $query->row['quotation_under_review'];
	}

	public function getCustomerGroupId($customer_id) {
		$query = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['customer_group_id'];
	}

	public function addComment($quotation_id,$comment) {
		$this->db->query("UPDATE  " . DB_PREFIX . "quotation SET  comment = '" . $this->db->escape($comment) . "' WHERE quotation_id = '".(int)$quotation_id."'");
	}

	public function updateShipping($quotation_id,$shipping) {
		$this->db->query("UPDATE  " . DB_PREFIX . "quotation SET  shipping_required = '" . (int)$shipping . "' WHERE quotation_id = '".(int)$quotation_id."'");
	}

	public function addCustomer($data) {
		//$this->event->trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();
		
		$this->load->language('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

		$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($subject);
		$mail->setText($message);
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		//$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}

	public function checkOrderExists($quotation_id) {
		$sql = "SELECT order_id FROM " . DB_PREFIX . "order WHERE comment LIKE '%Quotation ID: #" . $quotation_id . "'";
		$rs = $this->db->query($sql);
		if ($rs->num_rows) {
			return true;
		} else {
			return false;
		}
	}
}