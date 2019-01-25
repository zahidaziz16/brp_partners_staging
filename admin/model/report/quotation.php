<?php
class ModelReportQuotation extends Model {
	public function getTotalSales($data = array()) {
		$sql = "SELECT SUM(total) AS total FROM `" . DB_PREFIX . "quotation` WHERE quotation_status_id > '0'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalQuotationsByCountry() {
		$query = $this->db->query("SELECT COUNT(*) AS total, SUM(o.total) AS amount, c.iso_code_2 FROM `" . DB_PREFIX . "quotation` o LEFT JOIN `" . DB_PREFIX . "country` c ON (o.payment_country_id = c.country_id) WHERE o.quotation_status_id > '0' GROUP BY o.payment_country_id");

		return $query->rows;
	}

	public function getTotalQuotationsByDay() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $quotation_status_id) {
			$implode[] = "'" . (int)$quotation_status_id . "'";
		}

		$quotation_data = array();

		for ($i = 0; $i < 24; $i++) {
			$quotation_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "quotation` WHERE quotation_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$quotation_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $quotation_data;
	}

	public function getTotalQuotationsByWeek() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $quotation_status_id) {
			$implode[] = "'" . (int)$quotation_status_id . "'";
		}

		$quotation_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$quotation_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "quotation` WHERE quotation_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$quotation_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $quotation_data;
	}

	public function getTotalQuotationsByMonth() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $quotation_status_id) {
			$implode[] = "'" . (int)$quotation_status_id . "'";
		}

		$quotation_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$quotation_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "quotation` WHERE quotation_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

		foreach ($query->rows as $result) {
			$quotation_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $quotation_data;
	}

	public function getTotalQuotationsByYear() {
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $quotation_status_id) {
			$implode[] = "'" . (int)$quotation_status_id . "'";
		}

		$quotation_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$quotation_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "quotation` WHERE quotation_status_id IN(" . implode(",", $implode) . ") AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");

		foreach ($query->rows as $result) {
			$quotation_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);
		}

		return $quotation_data;
	}
        
        public function getExcelReport($condition) {
            $sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `quotations`, SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "quotation_product` op WHERE op.quotation_id = o.quotation_id GROUP BY op.quotation_id)) AS products, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "quotation_total` ot WHERE ot.quotation_id = o.quotation_id AND ot.code = 'tax' GROUP BY ot.quotation_id)) AS tax, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "quotation_total` ot WHERE ot.quotation_id = o.quotation_id AND (ot.code = 'sub_total' OR ot.code = 'shipping') GROUP BY ot.quotation_id)) AS sub_total, SUM(o.total) AS `total`, approver_name, o.quotation_id, CONCAT(o.firstname, ' ', o.lastname) as user_name, f.delivery_date AS delivery_date, e.approved_date AS approved_date, CASE
                WHEN o.quotation_status_id = 1 THEN 'Pending for Approval'
                WHEN o.quotation_status_id = 3 THEN 'Quotation Rejected'
                WHEN o.quotation_status_id = 11 THEN 'Quotation Approved, Pending Confirmation'
                WHEN o.quotation_status_id = 6 THEN c.name
                WHEN o.quotation_status_id = 7 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
                WHEN o.quotation_status_id = 8 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
                WHEN o.quotation_status_id = 9 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
                WHEN o.quotation_status_id = 10 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
            END AS bord  FROM `" . DB_PREFIX . "quotation` o LEFT JOIN oc_order b ON o.order_id = b.order_id  LEFT JOIN oc_quotation_status c ON o.quotation_status_id = c.quotation_status_id LEFT JOIN oc_order_status d ON b.order_status_id = d.order_status_id LEFT JOIN (SELECT MIN(date_added) AS approved_date, quotation_id FROM `oc_quotation_history` WHERE `quotation_status_id` IN (9,10,8,7,11) GROUP BY quotation_id) AS e ON o.quotation_id = e.quotation_id LEFT JOIN (SELECT MIN(date_added) AS delivery_date, order_id FROM `oc_order_history` WHERE `order_status_id` IN (5) GROUP BY order_id) AS f ON b.order_id = f.order_id ".$condition." ";
            $sql .= " GROUP BY o.quotation_id";
             $sql .= " ORDER BY o.date_added DESC";
              
	    $query = $this->db->query($sql);
	    return $query->rows;
	  }

	public function getQuotations($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `quotations`, SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "quotation_product` op WHERE op.quotation_id = o.quotation_id GROUP BY op.quotation_id)) AS products, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "quotation_total` ot WHERE ot.quotation_id = o.quotation_id AND ot.code = 'tax' GROUP BY ot.quotation_id)) AS tax, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "quotation_total` ot WHERE ot.quotation_id = o.quotation_id AND (ot.code = 'sub_total' OR ot.code = 'shipping') GROUP BY ot.quotation_id)) AS sub_total, SUM(o.total) AS `total`, approver_name, o.quotation_id, CONCAT(o.firstname, ' ', o.lastname) as user_name, f.delivery_date AS delivery_date, e.approved_date AS approved_date, CASE
                WHEN o.quotation_status_id = 1 THEN 'Pending for Approval'
                WHEN o.quotation_status_id = 3 THEN 'Quotation Rejected'
                WHEN o.quotation_status_id = 11 THEN 'Quotation Approved, Pending Confirmation'
                WHEN o.quotation_status_id = 6 THEN c.name
                WHEN o.quotation_status_id = 7 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
                WHEN o.quotation_status_id = 8 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
                WHEN o.quotation_status_id = 9 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
                WHEN o.quotation_status_id = 10 THEN CONCAT(c.name, ' Order ID: ', b.order_id, ' Order Date: ', b.date_added, ' Order Status: ', d.name)
            END AS bord  FROM `" . DB_PREFIX . "quotation` o LEFT JOIN oc_order b ON o.order_id = b.order_id  LEFT JOIN oc_quotation_status c ON o.quotation_status_id = c.quotation_status_id LEFT JOIN oc_order_status d ON b.order_status_id = d.order_status_id LEFT JOIN (SELECT MIN(date_added) AS approved_date, quotation_id FROM `oc_quotation_history` WHERE `quotation_status_id` IN (9,10,8,7,11) GROUP BY quotation_id) AS e ON o.quotation_id = e.quotation_id LEFT JOIN (SELECT MIN(date_added) AS delivery_date, order_id FROM `oc_order_history` WHERE `order_status_id` IN (5) GROUP BY order_id) AS f ON b.order_id = f.order_id ";

		if (!empty($data['filter_quotation_status_id'])) {
			$sql .= " WHERE o.quotation_status_id = '" . (int)$data['filter_quotation_status_id'] . "'";
		} else {
			$sql .= " WHERE o.quotation_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                
                if (!empty($data['filter_approver'])) {
			$sql .= " AND o.approver_name LIKE '%" . $this->db->escape($data['filter_approver']) . "%'";
		}
                
                if (!empty($data['filter_approved'])) {
                    
                    if($data['filter_approved'] != "") {
                        if($data['filter_approved'] == "yes") {
                            $sql .= "AND o.approver_id != '0' ";
                        }else {
                            $sql .= "AND o.approver_id = '0' ";
                        }
                        
                    }
		}

//		if (!empty($data['filter_group'])) {
//			$group = $data['filter_group'];
//		} else {
//			$group = 'year';
//		}
//
//		switch($group) {
//			case 'day';
//				//$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
//				break;
//			default:
//			case 'week':
//				//$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added)";
//				break;
//			case 'month':
//				//$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
//				break;
//			case 'year':
//				//$sql .= " GROUP BY YEAR(o.quotation_id)";
//				break;
//		}
                $sql .= " GROUP BY o.quotation_id";
		$sql .= " ORDER BY o.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
//echo $sql; exit;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalQuotations($data = array()) {
//		if (!empty($data['filter_group'])) {
//			$group = $data['filter_group'];
//		} else {
//			$group = 'week';
//		}
//
//		switch($group) {
//			case 'day';
//				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added), DAY(date_added)) AS total FROM `" . DB_PREFIX . "quotation`";
//				break;
//			default:
//			case 'week':
//				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), WEEK(date_added)) AS total FROM `" . DB_PREFIX . "quotation`";
//				break;
//			case 'month':
//				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added)) AS total FROM `" . DB_PREFIX . "quotation`";
//				break;
//			case 'year':
//				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `" . DB_PREFIX . "quotation`";
//				break;
//		}
            
                $sql = "SELECT COUNT(DISTINCT quotation_id) AS total FROM `" . DB_PREFIX . "quotation`";

		if (!empty($data['filter_quotation_status_id'])) {
			$sql .= " WHERE quotation_status_id = '" . (int)$data['filter_quotation_status_id'] . "'";
		} else {
			$sql .= " WHERE quotation_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
                
                if (!empty($data['filter_approver'])) {
			$sql .= " AND approver_name LIKE '%" . $this->db->escape($data['filter_approver']) . "%'";
		}
                
                if (!empty($data['filter_approved'])) {
                    
                    if($data['filter_approved'] != "") {
                        if($data['filter_approved'] == "yes") {
                            $sql .= "AND approver_id != '0' ";
                        }else {
                            $sql .= "AND approver_id = '0' ";
                        }
                        
                    }
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.quotation_id) AS `quotations` FROM `" . DB_PREFIX . "quotation` o LEFT JOIN `" . DB_PREFIX . "quotation_total` ot ON (ot.quotation_id = o.quotation_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_quotation_status_id'])) {
			$sql .= " AND o.quotation_status_id = '" . (int)$data['filter_quotation_status_id'] . "'";
		} else {
			$sql .= " AND o.quotation_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

	public function getTotalTaxes($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "quotation_total` ot ON (o.quotation_id = ot.quotation_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_quotation_status_id'])) {
			$sql .= " AND o.quotation_status_id = '" . (int)$data['filter_quotation_status_id'] . "'";
		} else {
			$sql .= " AND o.quotation_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.quotation_id) AS `quotations` FROM `" . DB_PREFIX . "quotation` o LEFT JOIN `" . DB_PREFIX . "quotation_total` ot ON (o.quotation_id = ot.quotation_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_quotation_status_id'])) {
			$sql .= " AND o.quotation_status_id = '" . (int)$data['filter_quotation_status_id'] . "'";
		} else {
			$sql .= " AND o.quotation_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
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

	public function getTotalShipping($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "quotation` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "quotation_total` ot ON (o.quotation_id = ot.quotation_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_quotation_status_id'])) {
			$sql .= " AND quotation_status_id = '" . (int)$data['filter_quotation_status_id'] . "'";
		} else {
			$sql .= " AND quotation_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}