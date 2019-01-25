<?php
class ModelSaleQuotation extends Model {
	public function getquotation($quotation_id) {
		$quotation_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "quotation` o WHERE o.quotation_id = '" . (int)$quotation_id . "'");

		if ($quotation_query->num_rows) {
			$reward = 0;
			$quotation_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");
			foreach ($quotation_product_query->rows as $product) {
				$reward += $product['reward'];
			}

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

			if ($quotation_query->row['affiliate_id']) {
				$affiliate_id = $quotation_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
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
				'customer'                => $quotation_query->row['customer'],
				'customer_group_id'       => $quotation_query->row['customer_group_id'],
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
				'reward'                  => $reward,
				'quotation_status_id'     => $quotation_query->row['quotation_status_id'],
				'quotation_under_review'  => $quotation_query->row['quotation_under_review'],
				'affiliate_id'            => $quotation_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $quotation_query->row['commission'],
				'language_id'             => $quotation_query->row['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,
				'currency_id'             => $quotation_query->row['currency_id'],
				'currency_code'           => $quotation_query->row['currency_code'],
				'currency_value'          => $quotation_query->row['currency_value'],
				'ip'                      => $quotation_query->row['ip'],
				'invoice_attachment'      => $quotation_query->row['invoice_attachment'],
				'user_agent'              => $quotation_query->row['user_agent'],
				'date_expired'         	  => $quotation_query->row['date_expired'],
				'date_added'              => $quotation_query->row['date_added'],
				'date_modified'           => $quotation_query->row['date_modified']
			);
		} else {
			return;
		}
	}

	public function getquotations($data = array()) {
		$sql = "SELECT o.quotation_id,o.quotation_status_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "quotation_status os WHERE os.quotation_status_id = o.quotation_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_expired, o.date_added, o.date_modified FROM `" . DB_PREFIX . "quotation` o";

		if (isset($data['filter_quotation_status'])) {
			$implode = array();
			$quotation_statuses = explode(',', $data['filter_quotation_status']);

			foreach ($quotation_statuses as $quotation_status_id) {
				$implode[] = "o.quotation_status_id = '" . (int)$quotation_status_id . "'";
			}
			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.quotation_status_id > '0'";
		}
		if (!empty($data['filter_quotation_id'])) {
			$sql .= " AND o.quotation_id = '" . (int)$data['filter_quotation_id'] . "'";
		}
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
		
		//check for expired quotations
		$expiredStatus = $this->config->get('quotation_expired');
		$completedStatus = $this->config->get('quotation_completed');
		$ipay88Status = $this->config->get('quotation_ipay88_payment');
		$creditStatus = $this->config->get('quotation_credit_payment');
		$rs = $this->db->query($sql);
		if ($rs->num_rows) {
			$results = $rs->rows;
			foreach($results AS $r){
				if(strtotime($r['date_expired']) < time() && $r['quotation_status_id'] != $expiredStatus && $r['quotation_status_id'] != $completedStatus && $r['quotation_status_id'] != $ipay88Status && $r['quotation_status_id'] != $creditStatus){
					$esql = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = '$expiredStatus', date_modified = NOW() WHERE quotation_id = ".$r['quotation_id'];
					$this->db->query($esql);
				}
			}
		}

		$sort_data = array(
			'o.quotation_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.quotation_id";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getquotationProducts($quotation_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");

		return $query->rows;
	}

	public function getquotationOption($quotation_id, $quotation_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_option_id = '" . (int)$quotation_option_id . "'");

		return $query->row;
	}

	public function getquotationOptions($quotation_id, $quotation_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_option WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_product_id = '" . (int)$quotation_product_id . "'");

		return $query->rows;
	}

	public function getquotationTotals($quotation_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_total WHERE quotation_id = '" . (int)$quotation_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getquotationReview($quotation_id) {
		$query = $this->db->query("SELECT quotation_under_review FROM " . DB_PREFIX . "quotation WHERE quotation_id = '" . (int)$quotation_id . "'");

		return $query->row['quotation_under_review'];
	}

	public function getTotalquotations($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation`";

		if (!empty($data['filter_quotation_status'])) {
			$implode = array();

			$quotation_statuses = explode(',', $data['filter_quotation_status']);

			foreach ($quotation_statuses as $quotation_status_id) {
				$implode[] = "quotation_status_id = '" . (int)$quotation_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE quotation_status_id > '0'";
		}

		if (!empty($data['filter_quotation_id'])) {
			$sql .= " AND quotation_id = '" . (int)$data['filter_quotation_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalquotationsByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}

	public function getTotalquotationsByquotationStatusId($quotation_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` WHERE quotation_status_id = '" . (int)$quotation_status_id . "' AND quotation_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalquotationsByProcessingStatus() {
		$implode = array();

		$quotation_statuses = $this->config->get('config_processing_status');

		foreach ($quotation_statuses as $quotation_status_id) {
			$implode[] = "quotation_status_id = '" . (int)$quotation_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` WHERE " . implode(" OR ", $implode));

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalquotationsByCompleteStatus() {
		$implode = array();

		$quotation_statuses = $this->config->get('config_complete_status');

		foreach ($quotation_statuses as $quotation_status_id) {
			$implode[] = "quotation_status_id = '" . (int)$quotation_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalquotationsByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` WHERE language_id = '" . (int)$language_id . "' AND quotation_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalquotationsByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` WHERE currency_id = '" . (int)$currency_id . "' AND quotation_status_id > '0'");

		return $query->row['total'];
	}

	public function createInvoiceNo($quotation_id) {
		$quotation_info = $this->getquotation($quotation_id);

		if ($quotation_info && !$quotation_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "quotation` WHERE invoice_prefix = '" . $this->db->escape($quotation_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "quotation` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($quotation_info['invoice_prefix']) . "' WHERE quotation_id = '" . (int)$quotation_id . "'");

			return $quotation_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function getquotationHistories($quotation_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "quotation_history oh LEFT JOIN " . DB_PREFIX . "quotation_status os ON oh.quotation_status_id = os.quotation_status_id WHERE oh.quotation_id = '" . (int)$quotation_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalquotationHistories($quotation_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation_history WHERE quotation_id = '" . (int)$quotation_id . "'");

		return $query->row['total'];
	}

	public function getProductImage($product_id) {
		$query = $this->db->query("SELECT image FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row['image'];
	}

	public function getTotalquotationHistoriesByquotationStatusId($quotation_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation_history WHERE quotation_status_id = '" . (int)$quotation_status_id . "'");

		return $query->row['total'];
	}

	public function getEmailsByProductsquotationed($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "quotation` o LEFT JOIN " . DB_PREFIX . "quotation_product op ON (o.quotation_id = op.quotation_id) WHERE (" . implode(" OR ", $implode) . ") AND o.quotation_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}

	public function getTotalEmailsByProductsquotationed($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "quotation` o LEFT JOIN " . DB_PREFIX . "quotation_product op ON (o.quotation_id = op.quotation_id) WHERE (" . implode(" OR ", $implode) . ") AND o.quotation_status_id <> '0'");

		return $query->row['total'];
	}

	public function underreviewquotation($quotation_id,$quotation_under_review) {
		if($quotation_under_review == 1){
			$this->db->query("UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = 2, quotation_under_review = '" . (int)$quotation_under_review . "' WHERE quotation_id ='".(int)$quotation_id."'");
		}else{
			$this->db->query("UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = 1, quotation_under_review = '" . (int)$quotation_under_review . "' WHERE quotation_id ='".(int)$quotation_id."'");
		}
	}

	public function getLatestComment($quotation_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_history  WHERE quotation_id = '".(int)$quotation_id."' ORDER BY quotation_history_id DESC LIMIT 1");
		return $query->row;
	}
}