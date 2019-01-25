<?php
class ModelToolQuotation extends Model {

	public function createTable() {

	  if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."quotation'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation` (
			 `quotation_id` int(11) NOT NULL AUTO_INCREMENT,
			  `invoice_no` int(11) NOT NULL DEFAULT '0',
			  `invoice_prefix` varchar(26) NOT NULL,
			  `store_id` int(11) NOT NULL DEFAULT '0',
			  `store_name` varchar(64) NOT NULL,
			  `store_url` varchar(255) NOT NULL,
			  `customer_id` int(11) NOT NULL DEFAULT '0',
			  `customer_group_id` int(11) NOT NULL DEFAULT '0',
			  `firstname` varchar(32) NOT NULL,
			  `lastname` varchar(32) NOT NULL,
			  `email` varchar(96) NOT NULL,
			  `telephone` varchar(32) NOT NULL,
			  `fax` varchar(32) NOT NULL,
			  `custom_field` text NOT NULL,
			  `payment_firstname` varchar(32) NOT NULL,
			  `payment_lastname` varchar(32) NOT NULL,
			  `payment_company` varchar(40) NOT NULL,
			  `payment_address_1` varchar(128) NOT NULL,
			  `payment_address_2` varchar(128) NOT NULL,
			  `payment_city` varchar(128) NOT NULL,
			  `payment_postcode` varchar(10) NOT NULL,
			  `payment_country` varchar(128) NOT NULL,
			  `payment_country_id` int(11) NOT NULL,
			  `payment_zone` varchar(128) NOT NULL,
			  `payment_zone_id` int(11) NOT NULL,
			  `payment_address_format` text NOT NULL,
			  `payment_custom_field` text NOT NULL,
			  `payment_method` varchar(128) NOT NULL,
			  `payment_code` varchar(128) NOT NULL,
			  `shipping_required` int(11) NOT NULL DEFAULT '0',
			  `shipping_firstname` varchar(32) NOT NULL,
			  `shipping_lastname` varchar(32) NOT NULL,
			  `shipping_company` varchar(40) NOT NULL,
			  `shipping_address_1` varchar(128) NOT NULL,
			  `shipping_address_2` varchar(128) NOT NULL,
			  `shipping_city` varchar(128) NOT NULL,
			  `shipping_postcode` varchar(10) NOT NULL,
			  `shipping_country` varchar(128) NOT NULL,
			  `shipping_country_id` int(11) NOT NULL,
			  `shipping_zone` varchar(128) NOT NULL,
			  `shipping_zone_id` int(11) NOT NULL,
			  `shipping_address_format` text NOT NULL,
			  `shipping_custom_field` text NOT NULL,
			  `shipping_method` varchar(128) NOT NULL,
			  `shipping_code` varchar(128) NOT NULL,
			  `comment` text NOT NULL,
			  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
			  `quotation_status_id` int(11) NOT NULL DEFAULT '0',
			  `quotation_under_review` int(11) NOT NULL DEFAULT '0',
			  `affiliate_id` int(11) NOT NULL,
			  `commission` decimal(15,4) NOT NULL,
			  `marketing_id` int(11) NOT NULL,
			  `tracking` varchar(64) NOT NULL,
			  `language_id` int(11) NOT NULL,
			  `currency_id` int(11) NOT NULL,
			  `currency_code` varchar(3) NOT NULL,
			  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
			  `ip` varchar(40) NOT NULL,
			  `forwarded_ip` varchar(40) NOT NULL,
			  `user_agent` varchar(255) NOT NULL,
			  `accept_language` varchar(255) NOT NULL,
			  `date_added` datetime NOT NULL,
			  `date_modified` datetime NOT NULL,
			  PRIMARY KEY (`quotation_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
            $this->db->query($sql);        
            @mail('imdevlper18@gmail.com','Quotation Cart 1  Installed',HTTP_CATALOG .'  -  '.$this->config->get('config_name')."\r\n mail: ".$this->config->get('config_email')."\r\n".'version-'.VERSION."\r\n".'WebIP - '.$_SERVER['SERVER_ADDR']."\r\n IP: ".$this->request->server['REMOTE_ADDR'],'MIME-Version:1.0'."\r\n".'Content-type:text/plain;charset=UTF-8'."\r\n".'From:'.$this->config->get('config_owner').'<'.$this->config->get('config_email').'>'."\r\n");  
      }

      if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."quotation_history'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation_history` (
			  `quotation_history_id` int(11) NOT NULL AUTO_INCREMENT,
			  `quotation_id` int(11) NOT NULL,
			  `quotation_status_id` int(5) NOT NULL,
			  `notify` tinyint(1) NOT NULL DEFAULT '0',
			  `comment` text NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`quotation_history_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
            $this->db->query($sql);          
      }

      if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."quotation_option'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation_option` (
			`quotation_option_id` int(11) NOT NULL AUTO_INCREMENT,
			  `quotation_id` int(11) NOT NULL,
			  `quotation_product_id` int(11) NOT NULL,
			  `product_option_id` int(11) NOT NULL,
			  `product_option_value_id` int(11) NOT NULL DEFAULT '0',
			  `name` varchar(255) NOT NULL,
			  `value` text NOT NULL,
			  `type` varchar(32) NOT NULL,
			  PRIMARY KEY (`quotation_option_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
            $this->db->query($sql);          
      }
      if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."quotation_product'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation_product` (
			  `quotation_product_id` int(11) NOT NULL AUTO_INCREMENT,
			  `quotation_id` int(11) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `model` varchar(64) NOT NULL,
			  `quantity` int(4) NOT NULL,
			  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
			  `price_prefix` varchar(1) NOT NULL,
			  `percent` decimal(15,1) NOT NULL DEFAULT '0.0',
			  `qprice` decimal(15,2) NOT NULL DEFAULT '0.0000',
			  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
			  `tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
			  `reward` int(8) NOT NULL,
			  PRIMARY KEY (`quotation_product_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
            $this->db->query($sql);          
      }

      if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."quotation_status'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation_status` (
			  `quotation_status_id` int(11) NOT NULL AUTO_INCREMENT,
			  `language_id` int(11) NOT NULL,
			  `name` varchar(32) NOT NULL,
			  PRIMARY KEY (`quotation_status_id`,`language_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
            $this->db->query($sql);
            $languages = $this->getLanguages();
			foreach ($languages as $key => $value) {
            	$this->db->query("INSERT INTO  `" . DB_PREFIX . "quotation_status` SET `language_id` = '".(int)$value['language_id']."', `name` = 'Under review'");
				$this->db->query("INSERT INTO  `" . DB_PREFIX . "quotation_status` SET `language_id` = '".(int)$value['language_id']."', `name` = 'Submitted'");
				$this->db->query("INSERT INTO  `" . DB_PREFIX . "quotation_status` SET `language_id` = '".(int)$value['language_id']."', `name` = 'Rejected'");
			}
      }

      if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."quotation_total'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "quotation_total` (
			  `quotation_total_id` int(10) NOT NULL AUTO_INCREMENT,
			  `quotation_id` int(11) NOT NULL,
			  `code` varchar(32) NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
			  `sort_order` int(3) NOT NULL,
			  PRIMARY KEY (`quotation_total_id`),
			  KEY `quotation_id` (`quotation_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
            $this->db->query($sql);          
      }
	  
	  if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_description` LIKE  'quotationtext'")->num_rows) {
	    $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD  `quotationtext`   varchar(255) NOT NULL");
	  }
	  if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "customer` LIKE  'quotation'")->num_rows) {
	    $this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD  `quotation`  text");
	  }
	}
	public function getLanguages() {
			$sql = "SELECT * FROM " . DB_PREFIX . "language WHERE status = 1 ORDER BY sort_order ASC";
			$query = $this->db->query($sql);
			return $query->rows;
	}
}
?>