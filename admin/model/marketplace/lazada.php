<?php
class ModelMarketplaceLazada extends Model {
	
	public function getCustomerByName($filter){
		$name = $filter['name'];
		$page = $filter['page'];
		$sql = "SELECT customer_id as id, CONCAT(c.firstname, ' ', c.lastname) AS full_name FROM " . DB_PREFIX . "customer c WHERE CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($name) . "%'";
		
		$sql .= "LIMIT 30 ";
		
		if($page != 1){
		$sql .= "OFFSET ". ($page-1)*30;
		}	
		
		//echo $sql; exit;
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalCustomerByName($name){
		$sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "customer c WHERE CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($name) . "%'";
		
		$query = $this->db->query($sql);

		return $query->row['count'];
	}
	
	public function getCustomerNameById($id){
		$sql = "SELECT customer_id as id, CONCAT(c.firstname, ' ', c.lastname) AS full_name FROM " . DB_PREFIX . "customer c WHERE customer_id = $id";
		
		//echo $sql; exit;
		$query = $this->db->query($sql);

		return $query->row['full_name'];
	}	

	public function getCategory($category_id) {
		//echo "SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'<br /><br /><br /><br />";
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order, COALESCE(x.lazada_id, 0) AS lazada_id FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) LEFT JOIN oc_category_to_lazada x ON (cp.category_id = x.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                    if(isset($data['filter_name'])) {
                        $sql .= " ORDER BY cd2." . $data['sort'] ." <> '".$data['filter_name']."', cd2.".$data['sort'];
                    }else {
                        $sql .= " ORDER BY " . $data['sort'];
                    }
		} else {
			$sql .= " ORDER BY sort_order";
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

	public function getLazadaCategoryTree(){
		$sql = "SELECT * FROM `oc_category_tree_lazada`";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function resetCategoryTree(){
		$sql = "TRUNCATE TABLE `oc_category_to_lazada`";
		
		$query = $this->db->query($sql);
		
		return $query;
		//$this->log->write("Result: ".print_r($query));
	}
	
	public function getLazadaCategories($filter){
		$name = $filter['name'];
		$page = $filter['page'];
		
		$sql = "SELECT * FROM `oc_category_tree_lazada` WHERE `full_path_name` LIKE '%$name%' AND `is_leaf` = 1 ORDER BY `lazada_id` ASC ";
		
		$sql .= "LIMIT 30 ";
		
		if($page != 1){
		$sql .= "OFFSET ". ($page-1)*30;
		}	
		
		//echo $sql; exit;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalLazadaCategories($name){
		$sql = "SELECT COUNT(*) as count FROM `oc_category_tree_lazada` WHERE `full_path_name` LIKE '%$name%' ";
		
		$query = $this->db->query($sql);

		return $query->row['count'];
	}
	
	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}
	
	public function getLazadaCategoryByBrpId($category_id) {
		$query = $this->db->query("SELECT * FROM `oc_category_to_lazada` WHERE `category_id` = ".$category_id." LIMIT 1");
		if ($query->num_rows > 0)
		{
			return $query->row['lazada_id'];
		}else{
			return 0;
		}

	}
	
	public function getLazadaFullPathNameById($lazada_id) {
		$query = $this->db->query("SELECT * FROM `oc_category_tree_lazada` WHERE `lazada_id` = ".$lazada_id." LIMIT 1");
		if ($query->num_rows > 0)
		{
			return $query->row['full_path_name'];
		}else{
			return 0;
		}

	}
	
		
	public function linkCategoryToLazada($data) {
		$id = "";
		$insert = "";
		foreach($data as $value){
			$id .= $value['category_id'] . ", ";
			if($value['lazada_id'] != 0){ // if not set/disabled, skip
			$insert .= "(". $value['category_id'] . ", " . $value['lazada_id'] . "), ";	
			}
		}
		$id = rtrim($id, ", ");

		$this->db->query("DELETE FROM `oc_category_to_lazada` WHERE `category_id` IN ($id)");
		
		if (!empty($insert)){
			$insert = rtrim($insert, ", ");

			$query = $this->db->query("INSERT INTO `oc_category_to_lazada` (`category_id`, `lazada_id`) VALUES ".$insert);
		}

	}
	
	
	

}
