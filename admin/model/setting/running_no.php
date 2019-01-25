<?php
class ModelSettingRunningNo extends Model {
	
	public function getTotalRecords($data = array()) {
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "running_number WHERE 1=1";
		if (!empty($data['filter_module_uid'])) {
			$sql .= " AND module_uid LIKE '%" . trim($this->db->escape($data['filter_module_uid'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getRecord($id) {
		//echo "SELECT DISTINCT * FROM " . DB_PREFIX . "running_number WHERE id = '" . (int)$id . "' ";exit;
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "running_number WHERE id = '" . (int)$id . "' ");
		return $query->row;
	}

	public function getRecords($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "running_number WHERE 1=1";
		if (!empty($data['filter_module_uid'])) {
			$sql .= " AND module_uid LIKE '%" . trim($this->db->escape($data['filter_module_uid'])) . "%'";
		}
		$sql .= " GROUP BY id";

		$sort_data = array(
			'module_uid',
			'current',
			'padding',
			'prefix',
			'suffix',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY module_uid";
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
	
	public function editRecordHeader($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "running_number SET 
			module_uid = '" . $this->db->escape($data['module_uid']) . "', 
			current = '" . $this->db->escape($data['current']) . "', 
			padding = '" . $this->db->escape($data['padding']) . "', 
			prefix = '" . $this->db->escape($data['prefix']) . "',
			suffix = '" . $this->db->escape($data['suffix']) . "',
			date_modified = NOW() WHERE id = '" . (int)$id . "'");
	}
	
	public function addRecordHeader($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "running_number SET 
			module_uid = '" . $this->db->escape($data['module_uid']) . "', 
			current = '" . $this->db->escape($data['current']) . "', 
			padding = '" . $this->db->escape($data['padding']) . "', 
			prefix = '" . $this->db->escape($data['prefix']) . "',
			suffix = '" . $this->db->escape($data['suffix']) . "',
			date_added = NOW()");
		$id = $this->db->getLastId();
		return $id;
	}
	
	public function deleteRecord($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "running_number WHERE id = '" . (int)$id . "'");
	}
	
	public function addEditRecordDetails($id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "stockmovement_details WHERE transaction_id = '" . (int)$id . "'");
		if (isset($data['warehouse_tran'])) {
			foreach ($data['warehouse_tran'] as $warehouse_tran) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "stockmovement_details SET transaction_id = '" . (int)$id . "', 
					row_no = '" . (int)$warehouse_tran['row_no'] . "', 
					product_name = '" . $warehouse_tran['product_name'] . "', 
					product_id = '" . $warehouse_tran['product_id'] . "', 
					product_model = '" . $warehouse_tran['product_model'] . "', 
					product_type = '" . $warehouse_tran['product_type'] . "', 
					matching_code = '" . $warehouse_tran['matching_code'] . "', 
					quantity = '" . $warehouse_tran['quantity'] . "', 
					remarks = '" . $this->db->escape($warehouse_tran['remarks']) . "', 
					date_added = NOW()");
			}
			$total_item_lines = count($data['warehouse_tran']);
			$this->db->query("UPDATE " . DB_PREFIX . "running_number SET total_item_lines = '" . $total_item_lines . "' WHERE id = '" . (int)$id . "'");
		}
	}
	
	public function generateRunningNo($id){
		$no = "";
		if($id != ""){
			$sql = "SELECT * FROM `oc_running_number` WHERE `module_uid`='".$id."'";
			$query = $this->db->query($sql);
			foreach($query->rows as $result){
				$no .= $result['prefix'];
				$iteration = $result['current'] + 1;
				$digit = $result['padding'];
				for($i = strlen($iteration); $i<$digit; $i++){
					$no .= "0";
				}
				$no .= $iteration.$result['suffix']; 
			}
		}
		return $no;
	}
	
	public function updateRunningNo($id){
		$sql = "SELECT * FROM `oc_running_number` WHERE `module_uid`='".$id."'";
		$query = $this->db->query($sql);
		foreach($query->rows as $result){
			$current = $result['current'] + 1;
		}
		if(isset($current)){
			$sql = "UPDATE `oc_running_number` SET `current`='".$current."' WHERE `module_uid`='".$id."'";
			$this->db->query($sql);
		}
	}
}
