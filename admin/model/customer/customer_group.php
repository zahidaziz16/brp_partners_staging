<?php
class ModelCustomerCustomerGroup extends Model {
	public function addCustomerGroup($data) {
                if($this->config->get('config_enable_approval')) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', parent_customer_group_id = '" . (int)$data['parent_customer_group_id'] . "', customer_level_id = '" . (int)$data['customer_level_id'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
                }else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
                }
                
		$customer_group_id = $this->db->getLastId();

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		return $customer_group_id;
	}

	public function editCustomerGroup($customer_group_id, $data) {
                if($this->config->get('config_enable_approval')) {
                    $this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', parent_customer_group_id = '" . (int)$data['parent_customer_group_id'] . "', customer_level_id = '" . (int)$data['customer_level_id'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_group_id = '" . (int)$customer_group_id . "'");
                }else {
                    $this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_group_id = '" . (int)$customer_group_id . "'");
                }

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	}
        
        public function notChild($curr, $parent) {
            $children = $this->recursiveGetChild((int)$curr);
            if(!empty($children)) {
                if(in_array($parent, $children)) {
                    return false;
                }else {
                    return true;
                }
            }else {
                return true;
            }
        }
        
        public function recursiveGetChild($parentId) {
            $arrVal = array();
            $query = $this->db->query("SELECT customer_group_id FROM oc_customer_group WHERE parent_customer_group_id = '".$parentId."'");
            
            if($query->num_rows) {
                foreach($query->rows AS $key => $value) {
                    array_push($arrVal, $value['customer_group_id']);
                    foreach($this->recursiveGetChild($value['customer_group_id']) AS $innerKey => $innerVal) {
                        array_push($arrVal, $innerVal);
                    }
                    
                }
            }
            
            return $arrVal;
        }

	public function deleteCustomerGroup($customer_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
	}

	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomerGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cgd.name";
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
        
        public function getCustomerLevels($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer_level cg ";

		$sort_data = array(
			'cg.name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cg.name";
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

	public function getCustomerGroupDescriptions($customer_group_id) {
		$customer_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		foreach ($query->rows as $result) {
			$customer_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}

		return $customer_group_data;
	}

	public function getTotalCustomerGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");

		return $query->row['total'];
	}
}
