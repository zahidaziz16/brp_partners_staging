<?php
class ModelAccountQuotation extends Model {
	public function getquotation($quotation_id) {
            
                $customers = array();
                
                $children = $this->recursiveGetChild((int)$this->customer->getGroupId());
                if(!empty($children)) {
                    $query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` o WHERE customer_group_id IN (" . implode(",", $children) . ")");

                    if($query->num_rows) {
                        foreach($query->rows AS $key => $value) {
                            array_push($customers, $value['customer_id']);
                        }
                    }
                }
                
                array_push($customers, (int)$this->customer->getId());
                
                if($this->getQuotationSuperAdmin()) {
                    $arrGroupMates = $this->getGroupMates((int)$this->customer->getGroupId());
                    foreach($arrGroupMates AS $gmKey => $gmVal) {
                        array_push($customers, $gmVal['customer_id']);
                    }
                }
                
                $quotation_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quotation` WHERE quotation_id = '" . (int)$quotation_id . "' AND customer_id IN (". implode(",", $customers) .") AND quotation_status_id > '0'");
		
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

			return array(
				'quotation_id'            => $quotation_query->row['quotation_id'],
				'invoice_no'              => $quotation_query->row['invoice_no'],
				'invoice_prefix'          => $quotation_query->row['invoice_prefix'],
				'store_id'                => $quotation_query->row['store_id'],
				'store_name'              => $quotation_query->row['store_name'],
				'store_url'               => $quotation_query->row['store_url'],
				'customer_id'             => $quotation_query->row['customer_id'],
				'customer_group_id'       => $quotation_query->row['customer_group_id'],
				'firstname'               => $quotation_query->row['firstname'],
				'lastname'                => $quotation_query->row['lastname'],
				'telephone'               => $quotation_query->row['telephone'],
				'fax'                     => $quotation_query->row['fax'],
				'email'                   => $quotation_query->row['email'],
				'custom_field'            => $quotation_query->row['custom_field'],
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
				'payment_method'          => $quotation_query->row['payment_method'],
				'payment_code'          => $quotation_query->row['payment_code'],
				'shipping_required'      => $quotation_query->row['shipping_required'],
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
				'shipping_method'         => $quotation_query->row['shipping_method'],
				'shipping_code'           => $quotation_query->row['shipping_code'],
				'comment'                 => $quotation_query->row['comment'],
				'total'                   => $quotation_query->row['total'],
				'quotation_status_id'     => $quotation_query->row['quotation_status_id'],
				'quotation_under_review'  => $quotation_query->row['quotation_under_review'],
				'language_id'             => $quotation_query->row['language_id'],
				'currency_id'             => $quotation_query->row['currency_id'],
				'currency_code'           => $quotation_query->row['currency_code'],
				'currency_value'          => $quotation_query->row['currency_value'],
				'ip'                      => $quotation_query->row['ip'],
				'date_expired'            => $quotation_query->row['date_expired'],
				'date_added'              => $quotation_query->row['date_added'],
				'date_modified'           => $quotation_query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getquotations($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 1;
		}
		$sql = "SELECT o.quotation_id,o.quotation_status_id, o.firstname, o.lastname, o.quotation_under_review, o.date_expired, o.date_added, o.total, o.currency_code, o.currency_value, o.invoice_attachment, os.name as status, o.customer_id FROM `" . DB_PREFIX . "quotation` o LEFT JOIN " . DB_PREFIX . "quotation_status os ON (o.quotation_status_id = os.quotation_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.quotation_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.quotation_id DESC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);
		
		//check for expired quotations
		$expiredStatus = $this->config->get('quotation_expired');
		$completedStatus = $this->config->get('quotation_completed');
		$ipay88Status = $this->config->get('quotation_ipay88_payment');
		$creditStatus = $this->config->get('quotation_credit_payment');
                $codStatus = $this->config->get('quotation_cod_payment');
		$paypalStatus = $this->config->get('quotation_pp_standard_payment');
		$ghlStatus = $this->config->get('quotation_ghl_payment');
		$banktransferStatus = $this->config->get('quotation_banktransfer_payment');
                $approveStatus = $this->config->get('quotation_quotation_approve_status_id');
                
		if ($query->num_rows) {
			$results = $query->rows;
			foreach($results AS $r){
				if(strtotime($r['date_expired']) < time() && $r['quotation_status_id'] != $expiredStatus && $r['quotation_status_id'] != $completedStatus && $r['quotation_status_id'] != $ipay88Status && $r['quotation_status_id'] != $creditStatus && $r['quotation_status_id'] != $codStatus && $r['quotation_status_id'] != $paypalStatus && $r['quotation_status_id'] != $ghlStatus && $r['quotation_status_id'] != $banktransferStatus && $r['quotation_status_id'] != $approveStatus){
					$esql = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = '$expiredStatus', date_modified = NOW() WHERE quotation_id = ".$r['quotation_id'];
					$this->db->query($esql);
				}
			}
		}
		$query2 = $this->db->query($sql);
		return $query2->rows;
	}
        
        public function getquotationsApproval($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 1;
		}
                
                $customers = array();
                
                $children = $this->recursiveGetChild((int)$this->customer->getGroupId());
                if(!empty($children)) {
                    $query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` o WHERE customer_group_id IN (" . implode(",", $children) . ")");

                    if($query->num_rows) {
                        foreach($query->rows AS $key => $value) {
                            array_push($customers, $value['customer_id']);
                        }
                    }
                }
                
                array_push($customers, (int)$this->customer->getId());
                
                if($this->getQuotationSuperAdmin()) {
                    $arrGroupMates = $this->getGroupMates((int)$this->customer->getGroupId());
                    foreach($arrGroupMates AS $gmKey => $gmVal) {
                        array_push($customers, $gmVal['customer_id']);
                    }
                }
                
		$sql = "SELECT o.quotation_id,o.quotation_status_id, o.firstname, o.lastname, o.quotation_under_review, o.date_expired, o.date_added, o.total, o.currency_code, o.currency_value, o.invoice_attachment, os.name as status, o.customer_id, o.reject_reason FROM `" . DB_PREFIX . "quotation` o LEFT JOIN " . DB_PREFIX . "quotation_status os ON (o.quotation_status_id = os.quotation_status_id) WHERE o.customer_id IN (" . implode(",", $customers) . ") AND o.quotation_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.quotation_id DESC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);
		
		//check for expired quotations
		$expiredStatus = $this->config->get('quotation_expired');
		$completedStatus = $this->config->get('quotation_completed');
		$ipay88Status = $this->config->get('quotation_ipay88_payment');
		$creditStatus = $this->config->get('quotation_credit_payment');
                $codStatus = $this->config->get('quotation_cod_payment');
		$paypalStatus = $this->config->get('quotation_pp_standard_payment');
		$ghlStatus = $this->config->get('quotation_ghl_payment');
		$banktransferStatus = $this->config->get('quotation_banktransfer_payment');
                $approveStatus = $this->config->get('quotation_quotation_approve_status_id');
                
		if ($query->num_rows) {
			$results = $query->rows;
			foreach($results AS $r){
				if(strtotime($r['date_expired']) < time() && $r['quotation_status_id'] != $expiredStatus && $r['quotation_status_id'] != $completedStatus && $r['quotation_status_id'] != $ipay88Status && $r['quotation_status_id'] != $creditStatus && $r['quotation_status_id'] != $codStatus && $r['quotation_status_id'] != $paypalStatus && $r['quotation_status_id'] != $ghlStatus && $r['quotation_status_id'] != $banktransferStatus && $r['quotation_status_id'] != $approveStatus){
					$esql = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = '$expiredStatus', date_modified = NOW() WHERE quotation_id = ".$r['quotation_id'];
					$this->db->query($esql);
				}
			}
		}
		$query2 = $this->db->query($sql);
		return $query2->rows;
	}

	 public function getProductImage($product_id) {
	    $query = $this->db->query("SELECT image FROM `" . DB_PREFIX . "product` WHERE product_id = '".$product_id."'");
	    return $query->row['image'];
	  }
          
          public function getExcelReport($columns, $table, $condition) {
            $sql = "SELECT ".$columns." FROM ".$table." ".$condition." ";
              
	    $query = $this->db->query($sql);
	    return $query->rows;
	  }
          
        public function getQuotationApproval() {
	    $query = $this->db->query("SELECT customer_level_id FROM `" . DB_PREFIX . "customer_group` o WHERE customer_group_id ='" . (int)$this->customer->getGroupId() . "'");

            $query = $this->db->query("SELECT approve_quotation FROM `" . DB_PREFIX . "customer_level` o WHERE customer_level_id ='" . $query->row['customer_level_id'] . "'");
            
            if(isset($query->row['approve_quotation'])) {
                return $query->row['approve_quotation'];
            }else {
                return 0;
            }
	    
	}
        
        public function getQuotationSuperAdmin($customerId = "") {
            if ($this->customer->isLogged()){
            if($customerId == "") {
                $customerGroupId = (int)$this->customer->getGroupId();
            }else {
                $query = $this->db->query("SELECT customer_group_id FROM `" . DB_PREFIX . "customer` o WHERE customer_id ='" . $customerId . "'");
                $customerGroupId = $query->row['customer_group_id'];
            }
            
	    $query = $this->db->query("SELECT customer_level_id FROM `" . DB_PREFIX . "customer_group` o WHERE customer_group_id ='" . $customerGroupId . "'");

            $query = $this->db->query("SELECT super_admin FROM `" . DB_PREFIX . "customer_level` o WHERE customer_level_id ='" . $query->row['customer_level_id'] . "'");
            
            if(isset($query->row['super_admin'])) {
                return $query->row['super_admin'];
            }else {
                return 0;
            }
			}else{
				return 0;
			}
	}
        
        public function getGroupMates($groupId = "") {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$groupId . "' AND customer_id != '". (int)$this->customer->getId() ."' ");

            return $query->rows;
	}
        
        public function getQuotationPermission() {
	    $query = $this->db->query("SELECT customer_level_id FROM `" . DB_PREFIX . "customer_group` o WHERE customer_group_id ='" . (int)$this->customer->getGroupId() . "'");

            $query = $this->db->query("SELECT get_quotation FROM `" . DB_PREFIX . "customer_level` o WHERE customer_level_id ='" . $query->row['customer_level_id'] . "'");
            
	    return $query->row['get_quotation'];
	}

	public function getquotationProduct($quotation_id, $quotation_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "' AND quotation_product_id = '" . (int)$quotation_product_id . "'");

		return $query->row;
	}

	public function getquotationProducts($quotation_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");

		return $query->rows;
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

	public function getquotationHistories($quotation_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "quotation_history oh LEFT JOIN " . DB_PREFIX . "quotation_status os ON oh.quotation_status_id = os.quotation_status_id WHERE oh.quotation_id = '" . (int)$quotation_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getCurrency($currency_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

		return $query->row;
	}

	public function getTotalquotations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.quotation_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
                return $query->row['total'];
	}
        
        public function getTotalquotationsApproval() {
                $customers = array();
		
                $children = $this->recursiveGetChild((int)$this->customer->getGroupId());
                if(!empty($children)) {
                    $query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` o WHERE customer_group_id IN (" . implode(",", $children) . ")");

                    if($query->num_rows) {
                        foreach($query->rows AS $key => $value) {
                            array_push($customers, $value['customer_id']);
                        }
                    }
                }
                
                array_push($customers, (int)$this->customer->getId());
                
                if($this->getQuotationSuperAdmin()) {
                    $arrGroupMates = $this->getGroupMates((int)$this->customer->getGroupId());
                    foreach($arrGroupMates AS $gmKey => $gmVal) {
                        array_push($customers, $gmVal['customer_id']);
                    }
                }
                
                $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quotation` o WHERE customer_id IN (" . implode(",", $customers) . ") AND o.quotation_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row['total'];
                
               
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

	public function getTotalquotationProductsByquotationId($quotation_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quotation_product WHERE quotation_id = '" . (int)$quotation_id . "'");

		return $query->row['total'];
	}

	public function getLatestComment($quotation_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quotation_history  WHERE quotation_id = '".(int)$quotation_id."' ORDER BY quotation_history_id DESC LIMIT 1");
		return $query->row;
	}
	
	public function saveQuotationAttachment($quotation_id, $filename){
		$status = $this->config->get('quotation_pending_approval');
		$query = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = '$status', invoice_attachment = '$filename' WHERE quotation_id = '$quotation_id'";
		$this->db->query($query);
	}
        
        public function approveQuotation($quotation_id){
		$status = $this->config->get('quotation_quotation_approve_status_id');
		$query = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id  = '$status', approver_id = '".(int)$this->customer->getGroupId()."', approver_name = '".$this->customer->getFirstName()." ".$this->customer->getFirstName()."' WHERE quotation_id = '$quotation_id'";
		$this->db->query($query);
                $this->load->model('quotation/order');
                $this->model_quotation_order->addQuotationHistory($quotation_id, $status, '', true);
	}
        
        public function rejectQuotation($quotation_id, $reason){
		$status = $this->config->get('quotation_quotation_reject_status_id');
		$query = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = '$status', reject_reason = '$reason' WHERE quotation_id = '$quotation_id'";
		$this->db->query($query);
                $this->load->model('quotation/order');
                $this->model_quotation_order->addQuotationHistory($quotation_id, $status, '', true);
	}
	
	public function deleteQuotationAttachment($quotation_id){
		$status = $this->config->get('quotation_submitted');
		$query1 = $this->db->query("SELECT invoice_attachment FROM " . DB_PREFIX . "quotation WHERE quotation_id = '$quotation_id'");
		$filename = $query1->row['invoice_attachment'];
		
		$query2 = "UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = '$status', invoice_attachment = '' WHERE quotation_id = '$quotation_id'";
		$this->db->query($query2);
		unlink(DIR_UPLOAD.'quotation/'.$filename);
	}
	
	public function underreviewquotation($quotation_id, $quotation_under_review) {
		if($quotation_under_review == 1){
			$this->db->query("UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = 2, quotation_under_review = '" . (int)$quotation_under_review . "' WHERE quotation_id ='".(int)$quotation_id."'");
		}else{
			$this->db->query("UPDATE " . DB_PREFIX . "quotation SET quotation_status_id = 1, quotation_under_review = '" . (int)$quotation_under_review . "' WHERE quotation_id ='".(int)$quotation_id."'");
		}
		return true;
	}
}