<?php
class ModelLocalisationQuotationStatus extends Model {
	public function addquotationStatus($data) {
		foreach ($data['quotation_status'] as $language_id => $value) {
			if (isset($quotation_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_status SET quotation_status_id = '" . (int)$quotation_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				$quotation_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('quotation_status');
	}

	public function editquotationStatus($quotation_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int)$quotation_status_id . "'");

		foreach ($data['quotation_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "quotation_status SET quotation_status_id = '" . (int)$quotation_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('quotation_status');
	}

	public function deletequotationStatus($quotation_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int)$quotation_status_id . "'");

		$this->cache->delete('quotation_status');
	}

	public function getquotationStatus($quotation_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int)$quotation_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getquotationStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "quotation_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql .= " ORDER BY name";

			if (isset($data['quotation']) && ($data['quotation'] == 'DESC')) {
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
		else {
			$quotation_status_data = $this->cache->get('quotation_status.' . (int)$this->config->get('config_language_id'));
			if (!$quotation_status_data) {
				$sql = "SELECT quotation_status_id, name FROM " . DB_PREFIX . "quotation_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name";
				$query = $this->db->query($sql);

				$quotation_status_data = $query->rows;
				$this->cache->set('quotation_status.' . (int)$this->config->get('config_language_id'), $quotation_status_data);
			}
			return $quotation_status_data;
		}
	}

	public function getquotationStatusDescriptions($quotation_status_id) {
		$quotation_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_status WHERE quotation_status_id = '" . (int)$quotation_status_id . "'");

		foreach ($query->rows as $result) {
			$quotation_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $quotation_status_data;
	}

	public function getTotalquotationStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}