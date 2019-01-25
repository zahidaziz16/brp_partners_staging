<?php
//==============================================================================
// Bulk Product Editing v230.1
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ControllerExtensionModuleBulkProductEditing extends Controller {
	private $error = array();
	private $type = 'module';
	private $name = 'bulk_product_editing';
	
	public function index() {
		$data['type'] = $this->type;
		$data['name'] = $this->name;
		
		$token = $data['token'] = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		
		$data = array_merge($data, $this->load->language('catalog/product'));
		$data = array_merge($data, $this->load->language($this->type . '/' . $this->name));
                if(isset($this->request->get['exit'])) {
                    $data['exit'] = $this->makeURL("common/dashboard", 'token=' . $token, 'SSL');
                }else {
                    $data['exit'] = "";
                }
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			// non-standard
			$post_data = $this->request->post;
			
			$product_ids = array();
			if ($post_data['edit'] == 'c') {
				$product_id_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = " . implode(" OR category_id = ", array_map('intval', $post_data['edit-c'])));
				foreach ($product_id_query->rows as $p) {
					$product_ids[] = $p['product_id'];
				}
			} elseif ($post_data['edit'] == 'm') {
				$product_id_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE manufacturer_id = " . implode(" OR manufacturer_id = ", array_map('intval', $post_data['edit-m'])));
				foreach ($product_id_query->rows as $p) {
					$product_ids[] = $p['product_id'];
				}
			} else {
				$product_ids = array_map('intval', $post_data['edit-p']);
			}
			$product_ids = array_unique($product_ids);
			
			if (!empty($product_ids)) {
				// Edit General Data
				$sql = "";
				
				if ($post_data['status'] != '')				$sql .= " status = " . (int)$post_data['status'] . ",";
				if ($post_data['model'] != '')				$sql .= " model = '" . $this->db->escape($post_data['model']) . "',";
				if ($post_data['sku'] != '')				$sql .= " sku = '" . $this->db->escape($post_data['sku']) . "',";
				if ($post_data['upc'] != '')				$sql .= " upc = '" . $this->db->escape($post_data['upc']) . "',";
				if ($post_data['ean'] != '')				$sql .= " ean = '" . $this->db->escape($post_data['ean']) . "',";
				if ($post_data['jan'] != '')				$sql .= " jan = '" . $this->db->escape($post_data['jan']) . "',";
				if ($post_data['isbn'] != '')				$sql .= " isbn = '" . $this->db->escape($post_data['isbn']) . "',";
				if ($post_data['mpn'] != '')				$sql .= " mpn = '" . $this->db->escape($post_data['mpn']) . "',";
				if ($post_data['location'] != '')			$sql .= " location = '" . $this->db->escape($post_data['location']) . "',";
				if ($post_data['price'] != '')				$sql .= $this->calculationSQL('price');
				if ($post_data['tax_class_id'] != '')		$sql .= " tax_class_id = " . (int)$post_data['tax_class_id'] . ",";
				if ($post_data['quantity'] != '')			$sql .= $this->calculationSQL('quantity');
				if ($post_data['minimum'] != '')			$sql .= " minimum = " . (int)$post_data['minimum'] . ",";
				if ($post_data['subtract'] != '')			$sql .= " subtract = " . (int)$post_data['subtract'] . ",";
				if ($post_data['stock_status_id'] != '')	$sql .= " stock_status_id = " . (int)$post_data['stock_status_id'] . ",";
				if ($post_data['shipping'] != '')			$sql .= " shipping = " . (int)$post_data['shipping'] . ",";
				if (!empty($post_data['image']))			$sql .= " image = '" . $this->db->escape($post_data['image']) . "',";
				if ($post_data['date_available'] != '')		$sql .= " date_available = '" . $this->db->escape($post_data['date_available']) . "',";
				if ($post_data['length'] != '')				$sql .= $this->calculationSQL('length');
				if ($post_data['width'] != '')				$sql .= $this->calculationSQL('width');
				if ($post_data['height'] != '')				$sql .= $this->calculationSQL('height');
				if ($post_data['length_class_id'] != '')	$sql .= " length_class_id = " . (int)$post_data['length_class_id'] . ",";
				if ($post_data['weight'] != '')				$sql .= $this->calculationSQL('weight');
				if ($post_data['weight_class_id'] != '')	$sql .= " weight_class_id = " . (int)$post_data['weight_class_id'] . ",";
				if ($post_data['sort_order'] != '')			$sql .= " sort_order = " . (int)$post_data['sort_order'] . ",";
				if ($post_data['points'] != '')				$sql .= $this->calculationSQL('points');
				if ($post_data['viewed'] != '')				$sql .= " viewed = " . (int)$post_data['viewed'] . ",";
				if ($post_data['manufacturer_id'] != '')	$sql .= " manufacturer_id = " . (int)$post_data['manufacturer_id'] . ",";
				
				if ($sql) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET" . $sql . " date_modified = NOW() WHERE product_id = " . implode(" OR product_id = ", $product_ids));
				}
				
				foreach ($post_data['product_reward'] as $customer_group_id => $product_reward) {
					if ($product_reward['points'] != '') {
						foreach ($product_ids as $p) {
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = " . (int)$p . " AND customer_group_id = " . (int)$customer_group_id);
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = " . (int)$p . ", customer_group_id = " . (int)$customer_group_id . ", points = " . (int)$product_reward['points']);
						}
					}
				}
				
				// Edit Categories
				if (!empty($post_data['add-c'])) {
					$sql = "INSERT IGNORE INTO " . DB_PREFIX . "product_to_category (product_id, category_id) VALUES";
					foreach (array_map('intval', $post_data['add-c']) as $c) {
						foreach ($product_ids as $p) {
							$sql .= " (" . $p . "," . $c . "),";
						}
					}
					$this->db->query(substr($sql, 0, -1));
				}
				if (!empty($post_data['remove-c'])) {
					$sql = "DELETE FROM " . DB_PREFIX . "product_to_category WHERE";
					foreach (array_map('intval', $post_data['remove-c']) as $c) {
						foreach ($product_ids as $p) {
							$sql .= " (product_id =" . $p . " AND category_id = " . $c . ") OR";
						}
					}
					$this->db->query(substr($sql, 0, -3));
				}
				
				// Edit Stores
				if (!empty($post_data['add-s'])) {
					$sql = "INSERT IGNORE INTO " . DB_PREFIX . "product_to_store (product_id, store_id) VALUES";
					foreach (array_map('intval', $post_data['add-s']) as $s) {
						foreach ($product_ids as $p) {
							$sql .= " (" . $p . "," . $s . "),";
						}
					}
					$this->db->query(substr($sql, 0, -1));
				}
				if (!empty($post_data['remove-s'])) {
					$sql = "DELETE FROM " . DB_PREFIX . "product_to_store WHERE";
					foreach (array_map('intval', $post_data['remove-s']) as $s) {
						foreach ($product_ids as $p) {
							$sql .= " (product_id = " . $p . " AND store_id = " . $s . ") OR";
						}
					}
					$this->db->query(substr($sql, 0, -3));
				}
				
				// Edit Related Products
				if (!empty($post_data['remove-all-r'])) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = " . implode(" OR product_id = ", $product_ids));
					if (empty($post_data['oneway'])) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = " . implode(" OR related_id = ", $product_ids));
					}
				}
				if (!empty($post_data['related'])) {
					$oneway_values = array();
					$twoway_values = array();
					foreach (array_map('intval', $post_data['related']) as $r) {
						foreach ($product_ids as $p) {
							$oneway_values[] = "(" . $p . "," . $r . ")";
							$twoway_values[] = "(" . $r . "," . $p . ")";
						}
					}
					$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_related (product_id, related_id) VALUES " . implode(", ", $oneway_values));
					if (empty($post_data['oneway'])) {
						$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_related (product_id, related_id) VALUES " . implode(", ", $twoway_values));
					}
				}
				
				// Edit Discounts
				if (!empty($post_data['remove-all-d'])) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = " . implode(" OR product_id = ", $product_ids));
				}
				if (!empty($post_data['product_discount'])) {
					$x = $post_data['product_discount'];
					$sql = "INSERT INTO " . DB_PREFIX . "product_discount (product_id, customer_group_id, quantity, priority, price, date_start, date_end) VALUES";
					foreach ($product_ids as $p) {
						for ($i = 0; $i < count($x['customer_group_id']); $i++) {
							if (strpos($x['price'][$i], '-') !== false) {
								$price_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = " . $p);
								$price = $price_query->row['price'] + (float)$x['price'][$i];
							} elseif (strpos($x['price'][$i], '%') !== false) {
								$price_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = " . $p);
								$price = $price_query->row['price'] * (1 - (float)$x['price'][$i] / 100);
								if (!empty($post_data['round-d'])) $price = round($price);
							} else {
								$price = $x['price'][$i];
							}
							$sql .= " (" . $p . ", " . (int)$x['customer_group_id'][$i] . ", " . (int)$x['quantity'][$i] . ", " . (int)$x['priority'][$i] . ", " . (float)$price . ", '" . $this->db->escape($x['date_start'][$i]) . "', '" . $this->db->escape($x['date_end'][$i]) . "'),";
						}
					}
					$this->db->query(substr($sql, 0, -1));
				}
				
				// Edit Specials
				if (!empty($post_data['remove-all-s'])) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = " . implode(" OR product_id = ", $product_ids));
				}
				if (!empty($post_data['product_special'])) {
					$x = $post_data['product_special'];
					$sql = "INSERT INTO " . DB_PREFIX . "product_special (product_id, customer_group_id, priority, price, date_start, date_end) VALUES";
					foreach ($product_ids as $p) {
						for ($i = 0; $i < count($x['customer_group_id']); $i++) {
							if (strpos($x['price'][$i], '-') !== false) {
								$price_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = " . $p);
								$price = $price_query->row['price'] + (float)$x['price'][$i];
							} elseif (strpos($x['price'][$i], '%') !== false) {
								$price_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = " . $p);
								$price = $price_query->row['price'] * (1 - (float)$x['price'][$i] / 100);
								if (!empty($post_data['round-s'])) $price = round($price);
							} else {
								$price = $x['price'][$i];
							}
							$sql .= " (" . $p . ", " . (int)$x['customer_group_id'][$i] . ", " . (int)$x['priority'][$i] . ", " . (float)$price . ", '" . $this->db->escape($x['date_start'][$i]) . "', '" . $this->db->escape($x['date_end'][$i]) . "'),";
						}
					}
					$this->db->query(substr($sql, 0, -1));
				}
				
				$this->cache->delete('product');
			}
			// end
			
			$this->session->data['success'] = $data['standard_success'];
			$redirect = (isset($this->request->get['exit'])) ? $data['exit'] : $this->makeURL("extension/module" . '/' . $this->name, 'token=' . $token, 'SSL');
			$this->response->redirect(str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $redirect));
		}
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href'		=> $this->makeURL('common/home', 'token=' . $token, 'SSL'),
			'text'		=> $data['text_home'],
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'href'		=> $this->makeURL('extension/' . $this->type, 'token=' . $token, 'SSL'),
			'text'		=> $data['standard_' . $this->type],
			'separator' => ' :: '
		);
		$data['breadcrumbs'][] = array(
			'href'		=> $this->makeURL($this->type . '/' . $this->name, 'token=' . $token, 'SSL'),
			'text'		=> $data['heading_title'],
			'separator' => ' :: '
		);
		
		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';
		unset($this->session->data['success']);
		
		// non-standard
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(version_compare(VERSION, '2.0', '<') ? 0 : array('sort' => 'name'));
		
		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		
		$data['selectall_links'] = '<div class="selectall-links"><a onclick="$(this).parent().prev().find(\':checkbox\').attr(\'checked\', true)">' . $data['text_select_all'] . '</a> / <a onclick="$(this).parent().prev().find(\':checkbox\').attr(\'checked\', false)">' . $data['text_unselect_all'] . '</a></div>';
		
		$this->load->model('localisation/tax_class');
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('localisation/weight_class');
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		
		$this->load->model('localisation/length_class');
		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		$data['customer_groups'] = $this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups();
		
		$stores = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		$data['stores'] = $stores->rows;
		array_unshift($data['stores'], array('store_id' => 0, 'name' => $this->config->get('config_name')));
		
		$this->load->model('tool/image');
		$data['no_image'] = $this->model_tool_image->resize('no_image.' . (version_compare(VERSION, '2.0', '<') ? 'jpg' : 'png'), 100, 100);
		// end
		
		if (version_compare(VERSION, '2.0', '<')) {
			$this->data = $data;
			$this->template = $this->type . '/' . $this->name . '.tpl';
			$this->children = array(
				'common/header',	
				'common/footer',
			);
			if (version_compare(VERSION, '1.5', '<')) {
				$this->document->title = $data['heading_title'];
				$this->document->breadcrumbs = $data['breadcrumbs'];
				$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
			} else {
				$this->document->setTitle($data['heading_title']);
				$this->response->setOutput($this->render());
			}
		} else {
			$this->document->setTitle($data['heading_title']);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view($this->type . '/' . $this->name . (version_compare(VERSION, '2.2', '<') ? '.tpl' : ''), $data));
		}
	}
	
	private function calculationSQL($field) {
		if (strpos($this->request->post[$field], '+') !== false) {
			$calculation_sql = " " . $field . " = (" . $field . " + " . (float)str_replace('+', '', $this->request->post[$field]) . "),";
		} elseif (strpos($this->request->post[$field], '-') !== false) {
			$calculation_sql = " " . $field . " = (" . $field . " - " . (float)str_replace('-', '', $this->request->post[$field]) . "),";
		} elseif (strpos($this->request->post[$field], '%') !== false) {
			$round = (!empty($this->request->post['round-g'])) ? "ROUND" : "";
			$calculation_sql = " " . $field . " = " . $round . "(" . $field . " * " . (float)$this->request->post[$field] . " / 100),";
		} else {
			$calculation_sql = " " . $field . " = " . (float)$this->request->post[$field] . ",";
		}
		return $calculation_sql;
	}
	
	private function makeURL($route, $args = '', $connection = 'NONSSL') {
		if (!defined('VERSION') || VERSION < 1.5) {
			$url = ($connection == 'NONSSL') ? HTTP_SERVER : HTTPS_SERVER;
			$url .= 'index.php?route=' . $route;
			$url .= ($args) ? '&' . ltrim($args, '&') : '';
			return $url;
		} else {
			return $this->url->link($route, $args, $connection);
		}
	}
	
	private function validate() {
		$data = $this->load->language($this->type . '/' . $this->name);
		
		if (!$this->user->hasPermission('modify', "extension/module" . '/' . $this->name)) {
			$this->error['warning'] = $data['standard_error'];
		}
		
		if (($this->request->post['edit'] == 'c' && empty($this->request->post['edit-c'])) ||
			($this->request->post['edit'] == 'm' && empty($this->request->post['edit-m'])) ||
			($this->request->post['edit'] == 'p' && empty($this->request->post['edit-p'])) ||
			empty($this->request->post['edit'])
		) {
			$this->error['warning'] = $data['text_error'];
		}
		
		return ($this->error) ? false : true;
	}
	
	public function getProducts() {
		$sql = "SELECT p.product_id, p.status, pd.name, p.model FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . ")";
		if (!empty($this->request->get['id'])) {
			if (substr($this->request->get['id'], 0, 1) == 'm') {
				$sql .= " WHERE p.manufacturer_id = " . (int)substr($this->request->get['id'], 1);
			} elseif (substr($this->request->get['id'], 0, 1) == 'c') {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2c.category_id = " . (int)substr($this->request->get['id'], 1);
			}
		}
		$sql .= " ORDER BY p.status DESC, LOWER(pd.name) ASC";
		$query = $this->db->query($sql);
		$this->response->setOutput(json_encode($query->rows));
	}
}
?>