<?php
		
if( class_exists( 'VQMod' ) ) {
	require_once VQMod::modCheck( modification( DIR_SYSTEM . 'library/mfilter_helper.php' ) );
} else {
	require_once modification( DIR_SYSTEM . 'library/mfilter_helper.php' );
}

class Mfilter_Plus {
	
	private $_version	= '1.2.6';
	
    private $_ctrl;
	
	private $_settings;
	
	private $_values = NULL;
	
	private static $_instance;
	
	private $_cache = array();
	
	////////////////////////////////////////////////////////////////////////////
	
	public function baseJoin( array $skip = array() ) {
		$sql = '';
		
		/*if( ! in_array( 'pmv', $skip ) && ( $this->_values['attribute'] || $this->_values['option'] || $this->_values['filter'] ) ) {
			$sql .= sprintf("
				INNER JOIN
					`" . DB_PREFIX . "product_mfilter_values` AS `pmv`
				ON
					`pmv`.`product_id` = `p`.`product_id`
			" );
		}*/
		
		return $sql;
	}
	
	public function baseConditions( & $conditions ) {
		
	}
	
	private function hasFilters() {
		if( $this->_hasFilters === NULL ) {
			$this->_hasFilters = version_compare( VERSION, '1.5.5', '>=' );
		}
		
		return $this->_hasFilters;
	}
	
	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	public function attribsToSQL( $join, $attribs ) {		
		$sql = array();
		
		foreach( $attribs as $kk => $val ) {
			list( $key ) = explode( '-', $kk );
			
			$sql2 = array();
			foreach( $val as $val2 ) {
				foreach( $val2 as $val3 ) {
					$val3 = is_array( $val3 ) ? $val3[0] : $val3;
					
					if( strpos( $val3, '&quot;' ) !== false ) {
						$val4 = md5( str_replace( '&quot;', '"', $this->_trim( $val3 ) ) );
						
						if( isset( $this->_values['attribute'][$val4.':'.$key] ) ) {
							$sql2[] = 'FIND_IN_SET( ' . $this->_values['attribute'][$val4.':'.$key] . ', `p`.`mfilter_values` )';
						}
					}
					
					$val3 = $this->_trim( $val3 );
					
					if( Mfilter_Helper::i()->isSeoEnabled() && isset( $this->_values['attribute'][$val3.':'.$key] ) && $this->_values['attribute'][$val3.':'.$key] != '-1' ) {
						$sql2[] = 'FIND_IN_SET( ' . $this->_values['attribute'][$val3.':'.$key] . ', `p`.`mfilter_values` )';
					} else {
						$val3 = md5( $val3 );
						
						if( isset( $this->_values['attribute'][$val3.':'.$key] ) ) {
							$sql2[] = 'FIND_IN_SET( ' . $this->_values['attribute'][$val3.':'.$key] . ', `p`.`mfilter_values` )';
						}
					}
				}
			}
			
			if( $sql2 ) {
				$sql[] = '(' . implode( ! empty( $this->_settings['type_of_condition'] ) && $this->_settings['type_of_condition'] == 'and' ? ' AND ' : ' OR ', $sql2 ) . ')';
			}
		}
		
		if( $sql ) {			
			return $join . implode( ' AND ', $sql );
		}
		
		return '';
	}
	
	public function inStockStatus() {
		return $inStockStatus = empty( $this->_settings['in_stock_status'] ) ? 7 : $this->_settings['in_stock_status'];
	}
	
	public function stockStatusValues() {
		if( empty( $this->_ctrl->request->get['mfp'] ) ) {
			return array();
		}
		
		preg_match( '/stock_status\[([0-9,]+)\]/', $this->_ctrl->request->get['mfp'], $matches );
		
		if( empty( $matches[1] ) ) {
			return array();
		}
		
		return explode( ',', $matches[1] );
	}
	
	public function optionsToSQL( $join, $options, & $conditionsIn = NULL, & $conditionsOut = NULL ) {
		$sql = array();
		//$ids = array();
		
		foreach( $options as $val ) {
			$sql2 = array();
			
			foreach( $val as $val2 ) {
				$val2 = explode( ',', $val2 );
				
				foreach( $val2 as $val3 ) {
					if( isset( $this->_values['option'][$val3] ) ) {
						$sql3 = 'FIND_IN_SET( ' . $this->_values['option'][$val3] . ', `p`.`mfilter_values` )';
						
						if( ! empty( $this->_settings['stock_for_options_plus'] ) ) {
							if( ! empty( $this->_settings['in_stock_default_selected'] ) || in_array( $this->inStockStatus(), $this->stockStatusValues() ) ) {
								//$ids[] = $val3;
								$sql3 .= " AND ( SELECT	SUM(`quantity`) FROM `" . DB_PREFIX . "product_option_value` AS `pov` WHERE `p`.`product_id` = `pov`.`product_id` AND `option_value_id` = '" . $val3 . "' ) > 0";

								$sql3 = '( ' . $sql3 . ' )';
							}
						}
						
						$sql2[] = $sql3;
					}
				}
			}
			
			if( $sql2 ) {
				$sql[] = '(' . implode( ! empty( $this->_settings['type_of_condition'] ) && $this->_settings['type_of_condition'] == 'and' ? ' AND ' : ' OR ', $sql2 ) . ')';
			}
		}
		
		/*if( $ids && $conditionsIn !== NULL ) {
			$conditionsIn[] = "`p`.`product_id` IN(
				SELECT 
					`pov`.`product_id` 
				FROM 
					`" . DB_PREFIX . "product_option_value` AS `pov` 
				WHERE 
					`pov`.`option_value_id` IN(" . implode( ',', $ids ) . ") AND 
					`pov`.`quantity` > 0
			)";
		}*/
		
		if( $sql ) {		
			return $join . implode( ' AND ', $sql );
		}
		
		return '';
	}
	
	public function filtersToSQL( $join, $filters ) {
		$sql = array();
		
		foreach( $filters as $val ) {
			$sql2 = array();
			
			foreach( $val as $val2 ) {
				$val2 = explode( ',', $val2 );
				
				foreach( $val2 as $val3 ) {
					if( isset( $this->_values['filter'][$val3] ) ) {
						$sql2[] = 'FIND_IN_SET( ' . $this->_values['filter'][$val3] . ', `p`.`mfilter_values` )';
					}
				}
			}
			
			if( $sql2 ) {
				$sql[] = '(' . implode( ! empty( $this->_settings['type_of_condition'] ) && $this->_settings['type_of_condition'] == 'and' ? ' AND ' : ' OR ', $sql2 ) . ')';
			}
		}
		
		if( $sql ) {
			return $join . implode( ' AND ', $sql );
		}
		
		return '';
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	private function _trim( $val ) {
		$val = stripslashes( $val );
		$val = preg_replace( "/^'|'$/", '', $val );
		
		return $val;
	}
	
	public function setValues( $attribs, $options, $filters ) {
		if( ! $attribs && ! $options && ! $filters ) {
			return $this;
		}
		
		if( $this->_values !== NULL ) {
			return $this;
		}
		
		$this->_values = array(
			'attribute'	=> array(),
			'option'	=> array(),
			'filter'	=> array()
		);
		$value		= array();
		$value_id	= array();
		
		foreach( $attribs as $key => $val ) {
			$key = explode('-', $key);
			$key = $key[0];
					
			foreach( $val as $val2 ) {
				$value = array_merge( $value, $val2 );
				
				foreach( $val2 as $val3 ) {
					if( is_array( $val3 ) ) {
						foreach( $val3 as $val4 ) {
							$val4 = $this->_trim( $val4 );
							
							$this->_values['attribute'][md5( $val4 ).':'.$key] = '-1';
							
							if( Mfilter_Helper::i()->isSeoEnabled() ) {
								$this->_values['attribute'][$val4.':'.$key] = '-1';
							}
						}
					} else {
						$val3 = $this->_trim( $val3 );
						
						$this->_values['attribute'][md5( $val3 ).':'.$key] = '-1';
							
						if( Mfilter_Helper::i()->isSeoEnabled() ) {
							$this->_values['attribute'][$val3.':'.$key] = '-1';
						}
					}
				}
			}
		}
		
		foreach( $options as $val ) {
			$value_id = array_merge( $value_id, $val );
		}
		
		foreach( $filters as $val ) {
			$value_id = array_merge( $value_id, $val );
		}
		
		if( ! $value && ! $value_id ) {
			return $this;
		}
		
		$sql = '';
		
		if( $value ) {
			$seo_value = array();
			
			foreach( $value as $k => $val ) {
				if( is_array( $val ) ) {
					$val = $val[0];
				}
				
				$value[$k] = "'" . md5( $this->_trim( $val ) ) . "'";
				
				if( Mfilter_Helper::i()->isSeoEnabled() ) {
					$seo_value[] = "( `seo_value` = " . $val . " AND ( `language_id` = " . (int)$this->config->get('config_language_id') . " OR `language_id` IS NULL ) )";
				}
				
				if( strpos( $val, '&quot;' ) !== false ) {
					$value[] = "'" . md5( str_replace( '&quot;', '"', $this->_trim( $val ) ) ) . "'";
				}
			}
			
			$sql .= '( ';
			$sql .= sprintf( '`value` IN(%s)', implode( ',', $value ) );
			
			if( $seo_value ) {
				$sql .= ' OR ' . implode( ' OR ', $seo_value );
			}
			
			$sql .= ' )';
		}
		
		if( is_array( $value_id ) && null != ( $value_id = $this->_removeEmptyVals( $value_id ) ) ) {
			$sql .= $sql ? ' OR ' : '';
			$sql .= sprintf( '`value_id` IN(%s)', implode( ',', $value_id ) );
		}
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "mfilter_values` WHERE " . $sql;
		
		foreach( $this->db->query( $sql )->rows as $row ) {
			$this->_values[$row['type']][$row['type']=='attribute'?$row['value'].':'.$row['value_id']:$row['value_id']] = $row['mfilter_value_id'];
			
			if( Mfilter_Helper::i()->isSeoEnabled() ) {
				$this->_values[$row['type']][$row['seo_value'].':'.$row['value_id']] = $row['mfilter_value_id'];
			}
		}
		
		return $this;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	public static function getInstance( $ctrl, array $settings = array() ) {
		if( ! self::$_instance )
			self::$_instance = new Mfilter_Plus( $ctrl, $settings );
		
		return self::$_instance;
	}
    
    private function __construct( $ctrl, array $settings = array() ) {
        $this->_ctrl = $ctrl;
		$this->_settings = $settings ? $settings : $this->config->get('mega_filter_settings');
		
		MFilter_Helper::create( $ctrl );
	}
    
    public function __get( $name ) {
        return $this->_ctrl->{$name};
    }
	
	/**
	 * Instalacja 
	 */
	public function install() {
		$version = $this->config->get('mfilter_plus_version');
		
		$this->db->query('
			CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'mfilter_values` (
				`mfilter_value_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				`type` ENUM("attribute","option","filter") NOT NULL,
				`value` CHAR(32) NULL DEFAULT NULL,
				`seo_value` VARCHAR(255) NULL DEFAULT NULL,
				`value_id` INT(11) NULL DEFAULT NULL,
				`value_group_id` INT(11) NULL DEFAULT NULL,
				`language_id` INT(11) NULL DEFAULT NULL,
				PRIMARY KEY (`mfilter_value_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1'
		);
		
		$this->db->query('
			CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'mfilter_tags` (
				`mfilter_tag_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				`tag` CHAR(32) NOT NULL,
				PRIMARY KEY (`mfilter_tag_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1'
		);
		
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		
		// main store
		$this->model_setting_setting->editSetting('mfilter_plus_version', array(
			'mfilter_plus_version' => $this->version()
		));
			
		// other stores
		foreach( $this->model_setting_store->getStores() as $result ) {
			$this->model_setting_setting->editSetting('mfilter_plus_version', array(
				'mfilter_plus_version' => $this->_version
			), $result['store_id']);
		}
		
		if( $this->_addColumn( 'product', 'mfilter_values', 'TEXT NOT NULL DEFAULT ""' ) ) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD FULLTEXT(`mfilter_values`)");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "mfilter_values` ADD UNIQUE( `type`, `value`, `value_id`, `language_id` )");
			
			if( $this->_addColumn( 'product', 'mfilter_tags', 'TEXT NOT NULL DEFAULT ""' ) ) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD FULLTEXT(`mfilter_tags`)");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "mfilter_tags` ADD INDEX( `tag` )");
			}
			
			return true;
		} else {
			$reindex = false;
			
			if( version_compare( $version, '1.0.1', '<' ) ) {
				$reindex = true;
			}
			
			if( version_compare( $version, '1.0.3', '<' ) ) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "mfilter_values` ADD UNIQUE( `type`, `value`, `value_id` )");
				
				$reindex = true;
			}
			
			if( version_compare( $version, '1.0.9', '<' ) ) {
				$reindex = true;
			}
			
			if( version_compare( $version, '1.2.2', '<' ) ) {
				if( $this->_addColumn( 'product', 'mfilter_tags', 'TEXT NOT NULL DEFAULT ""' ) ) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD FULLTEXT(`mfilter_tags`)");
					$this->db->query("ALTER TABLE `" . DB_PREFIX . "mfilter_tags` ADD INDEX( `tag` )");
					
					$reindex = true;
				}
			}
			
			if( version_compare(  $version, '1.2.4', '<' ) ) {
				if( $this->_addColumn( 'mfilter_values', 'seo_value', 'VARCHAR(255) NULL DEFAULT NULL' ) ) {
					$this->_addColumn( 'mfilter_values', 'value_group_id', 'INT(11) NULL DEFAULT NULL' );
					$this->_addColumn( 'mfilter_values', 'language_id', 'INT(11) NULL DEFAULT NULL' );
					
					foreach( $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "_mfilter_values`")->rows as $row ) {
						if( $row['Column_name'] == 'type' ) {
							$this->db->query("ALTER TABLE `" . DB_PREFIX . "_mfilter_values` DROP INDEX `" . $row['Key_name'] . "`");
							
							break;
						}
					}
					
					$this->db->query("ALTER TABLE `" . DB_PREFIX . "mfilter_values` ADD UNIQUE( `type`, `value`, `value_id`, `language_id`)");
					
					$reindex = true;
				}
			}
			
			return $reindex;
		}
	}
	
	/**
	 * Install progress
	 */
	public function installprogress() {
		$steps	= array( 'attribute', 'option' );
		$cache = DIR_CACHE . 'mfilter_plus-installprogress';
		
		if( $this->hasFilters() ) {
			$steps[] = 'filter';
		}
		
		$steps[] = 'tag';
		$steps[] = 'product';
		
		if( file_exists( $cache ) ) {
			$this->session->data['mfilter_plus_install'] = unserialize( file_get_contents( $cache ) );
		}
		
		if( empty( $this->session->data['mfilter_plus_install'] ) ) {
			$this->session->data['mfilter_plus_install'] = array(
				'step'	=> 0,
				'idx'	=> 0,
				'_idx'	=> 0
			);
			
			$this->db->query( "TRUNCATE `" . DB_PREFIX . "mfilter_values`" );
			$this->db->query( "UPDATE `" . DB_PREFIX . "product` SET `mfilter_values` = ''" );
			
			$this->db->query( "TRUNCATE `" . DB_PREFIX . "mfilter_tags`" );
			$this->db->query( "UPDATE `" . DB_PREFIX . "product` SET `mfilter_tags` = ''" );
		}
		
		$step		= $this->session->data['mfilter_plus_install']['step'];
		$idx		= $this->session->data['mfilter_plus_install']['idx'];
		$_idx		= $this->session->data['mfilter_plus_install']['_idx'];
		$limit		= 100;
		$_limit		= 100;
		$progress	= 0;
		$_progress	= 0;
		$sql		= NULL;
		$rows		= 0;
		
		switch( $steps[$step] ) {
			case 'attribute' : {
				if( empty( $this->_settings['attribute_separator'] ) ) {
					$sql = "SELECT REPLACE(REPLACE(TRIM(`text`), '\r', ''), '\n', '') AS `val`, `attribute_id` AS `val_id` FROM `" . DB_PREFIX . "product_attribute` GROUP BY `val`, `val_id`";
				} else {
					$sql = "SELECT `text` AS `val`, `attribute_id` AS `val_id` FROM `" . DB_PREFIX . "product_attribute` GROUP BY `val`, `val_id`";
				}
				$rows = $this->db->query("SELECT COUNT(*) AS `c` FROM(SELECT * FROM `" . DB_PREFIX . "product_attribute` GROUP BY `text`) AS t");
				$rows = $rows->row['c'];
				
				break;
			}
			case 'option' : {
				$sql = "SELECT `option_value_id` AS `val_id`, `option_id` AS `val_group_id` FROM `" . DB_PREFIX . "option_value`";
				$rows = $this->db->query("SELECT COUNT(*) AS `c` FROM `" . DB_PREFIX . "option_value`");
				$rows = $rows->row['c'];
				
				break;
			}
			case 'filter' : {
				$sql = "SELECT `filter_id` AS `val_id`, `filter_group_id` AS `val_group_id` FROM `" . DB_PREFIX . "filter_description`";
				$rows = $this->db->query("SELECT COUNT(*) AS `c` FROM `" . DB_PREFIX . "filter`");
				$rows = $rows->row['c'];
				
				break;
			}
			case 'tag' : {
				$sql = "SELECT `tag` AS `val` FROM `" . DB_PREFIX . "product_description`";
				$rows = $this->db->query("SELECT COUNT(*) AS `c` FROM `" . DB_PREFIX . "product_description`");
				$rows = $rows->row['c'];
				
				break;
			}
		}
		
		if( $sql ) {
			$sql .= ' LIMIT ' . ( $limit * $idx ) . ', ' . $limit;
			
			foreach( $this->db->query( $sql )->rows as $row ) {
				$values = array();
				
				if( $steps[$step] == 'attribute' ) {
					$values = empty( $this->_settings['attribute_separator'] ) ? array( $row['val'] ) : array_map( 'trim', explode( $this->_settings['attribute_separator'], $row['val'] ) );
				} else if( $steps[$step] == 'tag' ) {
					$values = array_map( 'trim', explode( ',', $row['val'] ) );
				} else {
					$values[] = array( 'val_id' => $row['val_id'], 'val_group_id' => $row['val_group_id'] );
				}
				
				foreach( $values as $value ) {
					$val = NULL;
					$seo = NULL;
					$val_id = NULL;
					$val_group_id = NULL;
					
					if( $steps[$step] == 'attribute' ) {
						$val = md5( $value );
						$seo = Mfilter_Helper::i()->convertValueToSeo( $value, false );
						$val_id = $row['val_id'];
					} else if( $steps[$step] == 'tag' ) {
						$val = $value;
					} else {
						$val_id = $value['val_id'];
						$val_group_id = $value['val_group_id'];
					}

					if( ! $val && ! $val_id ) continue;

					if( $steps[$step] == 'tag' ) {
						if( $this->db->query(sprintf("
							SELECT 
								* 
							FROM 
								`" . DB_PREFIX . "mfilter_tags` 
							WHERE 
								`tag` = '%s'", 
							$this->db->escape( $val )
						))->num_rows ) continue;
						
						$this->db->query(sprintf("
							INSERT INTO
								`" . DB_PREFIX . "mfilter_tags`
							SET
								`tag` = '%s'
							", 
							$this->db->escape( $val )
						));
					} else {
						foreach( $this->seoValues( $seo, $steps[$step], $val_id ) as $row2 ) {
							if( $this->db->query(sprintf("
								SELECT 
									* 
								FROM 
									`" . DB_PREFIX . "mfilter_values` 
								WHERE 
									`type` = '%s' AND `value` %s AND `value_id` %s AND `language_id` %s AND `value_group_id` %s", 
								$this->db->escape($steps[$step]), 
								$val === NULL ? 'IS NULL' : "= '" . $this->db->escape( $val ) . "'",
								$val_id === NULL ? 'IS NULL' : "= " . $val_id,
								$row2['language_id'] === NULL ? 'IS NULL' : "= " . $row2['language_id'],
								$val_group_id === NULL ? 'IS NULL' : "= " . $val_group_id
							))->num_rows ) continue;
						
							$this->db->query(sprintf("
								INSERT INTO
									`" . DB_PREFIX . "mfilter_values`
								SET
									`type` = '%s',
									`value` = %s,
									`seo_value` = %s,
									`value_id` = %s,
									`value_group_id` = %s,
									`language_id` = %s
								", 
								$this->db->escape($steps[$step]), 
								$val === NULL ? 'NULL' : "'" . $this->db->escape( $val ) . "'",
								$row2['seo'] === NULL ? 'NULL' : "'" . $this->db->escape( $row2['seo'] ) . "'",
								$val_id === NULL ? 'NULL' : $val_id,
								$val_group_id === NULL ? 'NULL' : $val_group_id,
								$row2['language_id'] === NULL ? 'NULL' : $row2['language_id']
							));
							
							if( $steps[$step] != 'attribute' ) {
								$lastId = $this->db->getLastId();

								while( $lastId && $row2['seo'] != null && $this->db->query(sprintf("
									SELECT
										*
									FROM
										`" . DB_PREFIX . "mfilter_values`
									WHERE
										`type` = '%s' AND
										`seo_value` = '%s' AND
										`language_id` %s AND
										`value_group_id` %s AND
										`mfilter_value_id` != " . $lastId . "
									",
									$this->db->escape($steps[$step]),
									$this->db->escape($row2['seo']),
									$row2['language_id'] === null ? 'IS NULL' : "= " . $row2['language_id'],
									$val_group_id === null ? 'IS NULL' : "= " . $val_group_id
								))->num_rows ) {
									$row2['seo'] .= '-' . $lastId;

									$this->db->query( "UPDATE `" . DB_PREFIX . "mfilter_values` SET `seo_value` = '" . $this->db->escape($row2['seo']) . "' WHERE `mfilter_value_id` = " . $lastId );
								}
							}
						}
					}
				}
			}
			
			$idx++;
			$_progress = 100;
		} else {
			$rows = $this->db->query("SELECT COUNT(*) AS `c` FROM `" . DB_PREFIX . "product`")->row['c'];
			$limit = 10;
			$_rows = 0;
			
			foreach( $this->db->query( "SELECT * FROM `" . DB_PREFIX . "product` LIMIT " . ( $limit * $idx ) . ', ' . $limit )->rows as $row ) {
				$this->updateProduct( $row['product_id'] );
			}
					
			$_idx++;
					
			$_progress = $_rows ? round( $_idx * $_limit / $_rows * 100, 2 ) : 100;
					
			if( $_progress >= 100 ) {
				$idx++;
				$_idx = 0;
			}
		}
		
		$progress = $rows ? round( $idx * $limit / $rows * 100, 3 ) : 100;
			
		if( $progress >= 100 ) {
			$step++;
			$idx = 0;
			$progress = 0;
			$_progress = 0;
		}
		
		$return = array(
			'success'	=> false,
			'steps'		=> count( $steps ),
			'progress'	=> $progress > 100 ? 100 : $progress,
			'_progress'	=> $_progress > 100 ? 100 : $_progress,
			'idx'		=> $idx,
			'_idx'		=> $_idx,
			'step'		=> $step + 1
		);
			
		if( $step >= count( $steps ) ) {
			unset( $this->session->data['mfilter_plus_install'] );
			
			if( file_exists( $cache ) ) {
				unlink( $cache );
			}
			
			$return['success'] = true;
		} else {
			$this->session->data['mfilter_plus_install']['idx'] = $idx;
			$this->session->data['mfilter_plus_install']['_idx'] = $_idx;
			$this->session->data['mfilter_plus_install']['step'] = $step;
			
			file_put_contents( $cache, serialize(array(
				'idx' => $idx,
				'_idx' => $_idx,
				'step' => $step,
				'progress' => $return['progress'],
				'_progress' => $return['_progress'],
				'steps' => $return['steps']
			)));
		}
		
		return $return;
	}
	
	private function seoValues( $seo, $type, $value_id ) {
		if( ! in_array( $type, array( 'option', 'filter' ) ) ) {
			return array( array( 'seo' => $seo, 'language_id' => null ) );
		}
		
		$sql = null;
		
		if( $type == 'option' ) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "option_value_description` WHERE `option_value_id` = " . (int) $value_id;
		} else if( $type == 'filter' ) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "filter_description` WHERE `filter_id` = " . (int) $value_id;
		}
		
		$seo = array();
			
		foreach( $this->db->query( $sql )->rows as $row ) {
			$name = Mfilter_Helper::i()->convertValueToSeo( $row['name'], false );
				
			if( isset( $seo[$name] ) ) {
				$seo[$name]['language_id'] = null;
			} else {
				$seo[$name] = array( 
					'seo' => $name,
					'language_id' => $row['language_id']
				);
			}
		}
		
		return $seo;
	}
	
	/**
	 * Uninstall
	 */
	public function uninstall() {
		$this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'mfilter_values`');
		
		$this->_removeColumn( 'product', 'mfilter_values' );
		
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		
		unset( $this->session->data['mfilter_plus_install'] );
			
		// main store
		$this->model_setting_setting->deleteSetting( 'mfilter_plus_version' );
			
		// other stores		
		foreach( $this->model_setting_store->getStores() as $result ) {
			$this->model_setting_setting->deleteSetting( 'mfilter_plus_version', $result['store_id']);
		}	
	}
	
	/**
	 * Dodaj kolumnę do tabeli
	 * 
	 * @param string $table
	 * @param string $column
	 * @param string $type
	 * @return boolean 
	 */
	private function _addColumn( $table, $column, $type ) {		
		$query = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . $table . '` LIKE "' . $column . '"');
		
		if( ! $query->num_rows ) {
			$this->db->query( 'ALTER TABLE `' . DB_PREFIX . $table . '` ADD `' . $column . '` ' . $type );
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Usuń kolumnę z tabeli
	 * 
	 * @param string $table
	 * @param string $column
	 * @return bool 
	 */
	private function _removeColumn( $table, $column ) {		
		$query = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . $table . '` LIKE "' . $column . '"');
		
		if( $query->num_rows ) {
			$this->db->query('ALTER TABLE `' . DB_PREFIX . $table . '` DROP `' . $column . '`');
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Wersja
	 * 
	 * @return string 
	 */
	public function version() {
		return $this->_version;
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	public function updateProduct( $product_id ) {
		$attribs		= $this->db->query( "SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = " . (int) $product_id );
		$options		= $this->db->query( "
			SELECT 
				* 
			FROM 
				`" . DB_PREFIX . "product_option_value` AS `pov`
			INNER JOIN
				`" . DB_PREFIX . "option_value_description` AS `ovd`
			ON
				`pov`.`option_value_id` = `ovd`.`option_value_id`
			WHERE 
				`pov`.`product_id` = " . (int) $product_id
		);
		$filters		= $this->hasFilters() ? $this->db->query( "
			SELECT 
				* 
			FROM 
				`" . DB_PREFIX . "product_filter` AS `pf`
			INNER JOIN
				`" . DB_PREFIX . "filter_description` AS `fd`
			ON
				`pf`.`filter_id` = `fd`.`filter_id`
			WHERE 
				`pf`.`product_id` = " . (int) $product_id 
		) : NULL;
		$tags			= $this->db->query( "SELECT product_id, tag FROM `" . DB_PREFIX . "product_description` WHERE `product_id` = " . (int) $product_id );
		$before			= array();
		$after			= array();
		$beforeTag		= array();
		$afterTag		= array();
		
		$this->load->model('module/mega_filter');
		
		if( $attribs->num_rows || $options->num_rows || ( $filters && $filters->num_rows ) || $tags->num_rows ) {		
			$conditions		= array( 'attribs' => array(), 'options' => array(), 'filters' => array(), 'tags' => array() );
			$items			= array();

			foreach( $attribs->rows as $attrib ) {
				if( empty( $this->_settings['attribute_separator'] ) ) {
					$attrib['text'] = array( trim( $attrib['text'] ) );
				} else {
					$attrib['text'] = html_entity_decode($attrib['text'], ENT_QUOTES, 'UTF-8');
					$attrib['text'] = array_map( 'trim', explode( $this->_settings['attribute_separator'], $attrib['text'] ) );
					
					foreach( $attrib['text'] as $k => $v ) {
						$attrib['text'][$k] = htmlspecialchars($attrib['text'][$k], ENT_COMPAT, 'UTF-8');
					}
				}
				
				foreach( $attrib['text'] as $txt ) {
					if( $txt === '' ) continue;
					
					$txt = str_replace( array( "\n", "\r" ), '', $txt );
					$md5 = md5( $txt );
					$seo = Mfilter_Helper::i()->convertValueToSeo( $txt, false );
					$key = $seo . ':' . $attrib['attribute_id'];

					if( ! isset( $items['attribute'][$key] ) ) {
						$conditions['attribs'][] = "( `value` = '" . $this->db->escape( $md5 ) . "' AND `value_id` = " . $attrib['attribute_id'] . ')';

						$items['attribute'][$key] = array( 
							'v' => $md5, 
							'seo' => $seo, 
							'id' => $attrib['attribute_id'],
							'gid' => null,
							'language_id' => null,
						);
					}
				}
			}

			foreach( $options->rows as $option ) {
				$conditions['options'][] = $option['option_value_id'];
				
				$seo = Mfilter_Helper::i()->convertValueToSeo( $option['name'], false );
				$key = $seo . ':' . $option['option_id'] . ':' . $option['option_value_id'];
				
				if( isset( $items['option'][$key] ) ) {
					$items['option'][$key]['language_id'] = null;
				} else {
					$items['option'][$key] = array(
						'id' => $option['option_value_id'],
						'gid' => $option['option_id'],
						'seo' => $seo,
						'language_id' => $option['language_id']
					);
				}
			}

			if( $filters ) {
				foreach( $filters->rows as $filter ) {
					$conditions['filters'][] = $filter['filter_id'];
					
					$seo = Mfilter_Helper::i()->convertValueToSeo( $filter['name'], false );
					$key = $seo . ':' . $filter['filter_group_id'] . ':' . $filter['filter_id'];
					
					if( isset( $items['filter'][$key] ) ) {
						$items['filter'][$key]['language_id'] = null;
					} else {
						$items['filter'][$key] = array(
							'id' => $filter['filter_id'],
							'gid' => $filter['filter_group_id'],
							'seo' => $seo,
							'language_id' => $filter['language_id']
						);
					}
				}
			}
			
			foreach( $tags->rows as $tag ) {
				$tag['tag'] = array_map( 'trim', explode( ',', $tag['tag'] ) );
				
				foreach( $tag['tag'] as $v ) {
					if( $v === '' ) continue;
					
					if( ! empty( $items['tags'] ) && in_array( $v, $items['tags'] ) ) continue;
					
					$conditions['tags'][] = "`tag` = '" . $this->db->escape( $v ) . "'";
					$items['tags'][] = $v;
				}
			}
			
			$sql = array();
			$sqlTag = array();

			if( $conditions['attribs'] ) {
				$sql[] = "( `type` = 'attribute' AND (" . implode( ' OR ', array_unique( $conditions['attribs'] ) ) . ") )";
			}
				
			if( $conditions['options'] ) {
				$sql[] = "( `type` = 'option' AND `value_id` IN(" . implode( ',', array_unique( $conditions['options'] ) ) . ") )";
			}

			if( $conditions['filters'] ) {
				$sql[] = "( `type` = 'filter' AND `value_id` IN(" . implode( ',', array_unique( $conditions['filters'] ) ) . ") )";
			}
			
			if( $conditions['tags'] ) {
				$sqlTag[] = '( ' . implode( ' OR ', $conditions['tags'] ) . ' )';
			}

			$values	= array();

			if( $sql ) {
				$sql	= "SELECT * FROM `" . DB_PREFIX . "mfilter_values` WHERE " . implode( ' OR ', $sql );
				
				foreach( $this->db->query( $sql )->rows as $row ) {
					$values[$row['type']][($row['type']=='attribute'?$row['value'].':'.$row['value_id']:$row['value_id']).':'.$row['language_id']] = $row['mfilter_value_id'];
				}
			}
			
			if( $sqlTag ) {
				$sqlTag = "SELECT * FROM `" . DB_PREFIX . "mfilter_tags` WHERE " . implode( ' OR ', $sqlTag );
				
				foreach( $this->db->query( $sqlTag )->rows as $row ) {
					$values['tags'][$row['tag']] = $row['mfilter_tag_id'];
				}
			}
			
			foreach( $items as $type => $vals ) {
				foreach( $vals as $val ) {
					if( $type == 'tags' ) {
						if( ! isset( $values['tags'][$val] ) ) {
							$this->db->query(sprintf("
								INSERT INTO
									`" . DB_PREFIX . "mfilter_tags`
								SET
									`tag` = '%s'
								",
								$this->db->escape( $val )
							));
							
							$afterTag[] = (string) $this->db->getLastId();
							$values['tags'][$val] = (string) $this->db->getLastId();
						} else {
							$afterTag[] = $values['tags'][$val];
						}
					} else {
						$key = $type == 'attribute' ? $val['v'] . ':' . $val['id'] : $val['id'];
						$key .= ':' . $val['language_id'];
						
						if( ! isset( $values[$type][$key] ) ) {
							$this->db->query(sprintf("
								INSERT INTO
									`" . DB_PREFIX . "mfilter_values`
								SET
									`type` = '%s',
									`value` = %s,
									`seo_value` = '%s',
									`value_id` = %s,
									`value_group_id` = %s,
									`language_id` = %s
								",
								$type,
								$type == 'attribute' ? "'" . $this->db->escape( $val['v'] ) . "'" : 'NULL',
								$this->db->escape( $val['seo'] ),
								$val['id'],
								$val['gid'] === null ? 'NULL' : $val['gid'],
								$val['language_id'] === null ? 'NULL' : $val['language_id']
							));

							$lastId = $this->db->getLastId();

							while( $lastId && $this->db->query(sprintf("
								SELECT
									*
								FROM
									`" . DB_PREFIX . "mfilter_values`
								WHERE
									`type` = '%s' AND
									`seo_value` = '%s' AND
									`language_id` %s AND
									`mfilter_value_id` != " . $lastId . "
								",
								$this->db->escape( $type ),
								$this->db->escape( $val['seo'] ),
								$val['language_id'] === null ? 'IS NULL' : " = " . $val['language_id']
							))->num_rows ) {
								$val['seo'] .= '-' . $lastId;

								$this->db->query( "UPDATE `" . DB_PREFIX . "mfilter_values` SET `seo_value` = '" . $this->db->escape($val['seo']) . "' WHERE `mfilter_value_id` = " . $lastId );
							}

							$after[] = (string) $lastId;
							$values[$type][$key] = (string) $lastId;
						} else {
							$after[] = (string) $values[$type][$key];
						}
					}
				}
			}

			sort( $after, SORT_NUMERIC );
			sort( $afterTag, SORT_NUMERIC );
		}
		
		$product = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = " . (int) $product_id )->row;
		
		$before = explode( ',', $product['mfilter_values'] );
		$beforeTag = explode( ',', $product['mfilter_tags'] );
		
		$this->db->query(sprintf("
			UPDATE
				`" . DB_PREFIX . "product`
			SET
				`mfilter_values` = '%s'
			WHERE
				`product_id` = %s
		", implode( ',', array_unique( $after ) ), $product_id));
		
		$this->db->query(sprintf("
			UPDATE
				`" . DB_PREFIX . "product`
			SET
				`mfilter_tags` = '%s'
			WHERE
				`product_id` = %s
		", implode( ',', array_unique( $afterTag ) ), $product_id));
		
		$diff = array_diff( $before, $after );
		$diffTag = array_diff( $beforeTag, $afterTag );
		
		if( $diff ) {
			foreach( $diff as $id ) {
				if( ! $this->db->query(sprintf("SELECT * FROM `" . DB_PREFIX . "product` WHERE FIND_IN_SET( '%s', `mfilter_values` ) LIMIT 1", $id))->num_rows ) {
					$this->_deleteMFilterValues(array($id));
				}
			}
		}
		
		if( $diffTag ) {
			foreach( $diffTag as $id ) {
				if( ! $this->db->query(sprintf("SELECT * FROM `" . DB_PREFIX . "product` WHERE FIND_IN_SET( '%s', `mfilter_tags` ) LIMIT 1", $id))->num_rows ) {
					$this->_deleteMFilterTags(array($id));
				}
			}
		}
	}
	
	private function _removeEmptyVals( $arr ) {
		foreach( $arr as $k => $v ) {
			if( $v === '' ) {
				unset( $arr[$k] );
			}
		}
		
		return $arr;
	}
	
	private function _getMFilterValueIds( $type, $values ) {
		$mfilter_value_ids = array();
		$sql = sprintf("
			SELECT * FROM `" . DB_PREFIX . "mfilter_values` WHERE `type` = '%s' AND
		", $type);

		if( $type == 'attribute' ) {
			$vals = array();

			foreach( $values as $k => $v ) {
				$v = empty( $this->_settings['attribute_separator'] ) ? array( $v ) : array_map( 'trim', explode( $this->_settings['attribute_separator'], $v ) );

				foreach( $v as $v2 ) {
					$vals[] = "'" . $this->db->escape( $v2 ) . "'";
				}
			}

			if( ! $vals ) {
				$sql = NULL;
			} else {
				$sql .= sprintf( "`value` IN(%s)", implode( ',', $vals ) );
			}
		} else if( null != ( $values = $this->_removeEmptyVals( $values ) ) ) {
			$sql .= sprintf( "`value_id` IN(%s)", implode( ',', $values ) );
		} else {
			$sql = NULL;
		}

		if( $sql ) {
			foreach( $this->db->query( $sql )->rows as $row ) {
				$mfilter_value_ids[] = $row['mfilter_value_id'];
			}
		}
		
		return $mfilter_value_ids;
	}
	
	private function _deleteMFilterValues( $mfilter_ids ) {
		if( ! $mfilter_ids )
			return;
		
		foreach( $mfilter_ids as $k => $id ) {
			$mfilter_ids[$k] = (int) $id;
			
			$this->db->query(str_replace( '{val}', (int) $id, "
				UPDATE
					`" . DB_PREFIX . "product`
				SET
					`mfilter_values` = CASE 
						WHEN `mfilter_values` LIKE '%,{val},%'
							THEN REPLACE( `mfilter_values`, ',{val},', ',' )
						WHEN `mfilter_values` LIKE '{val},%'
							THEN REPLACE( `mfilter_values`, '{val},', '' )
						WHEN `mfilter_values` LIKE ',{val}%'
							THEN REPLACE( `mfilter_values`, ',{val}', '' )
					END
				WHERE
					FIND_IN_SET( '{val}', `mfilter_values` )
			"));
		}
		
		$this->db->query(sprintf("
			DELETE FROM
				`" . DB_PREFIX . "mfilter_values`
			WHERE
				`mfilter_value_id` IN(%s)
		", implode( ',', $mfilter_ids)));
	}
	
	private function _deleteMFilterTags( $mfilter_ids ) {
		if( ! $mfilter_ids )
			return;
		
		foreach( $mfilter_ids as $k => $id ) {
			$mfilter_ids[$k] = (int) $id;
			
			$this->db->query(str_replace( '{val}', (int) $id, "
				UPDATE
					`" . DB_PREFIX . "product`
				SET
					`mfilter_tags` = CASE 
						WHEN `mfilter_tags` LIKE '%,{val},%'
							THEN REPLACE( `mfilter_tags`, ',{val},', ',' )
						WHEN `mfilter_tags` LIKE '{val},%'
							THEN REPLACE( `mfilter_tags`, '{val},', '' )
						WHEN `mfilter_tags` LIKE ',{val}%'
							THEN REPLACE( `mfilter_tags`, ',{val}', '' )
					END
				WHERE
					FIND_IN_SET( '{val}', `mfilter_tags` )
			"));
		}
		
		$this->db->query(sprintf("
			DELETE FROM
				`" . DB_PREFIX . "mfilter_tags`
			WHERE
				`mfilter_tag_id` IN(%s)
		", implode( ',', $mfilter_ids)));
	}
	
	// COMMON //////////////////////////////////////////////////////////////////
	
	private function editParameter( $type, $data, $beforeEdit ) {
		$after = array();
		$before = array();
		
		$parameters = array();
		
		$_key = $type;
		
		if( $type == 'option' ) {
			$_key = 'option_value';
		}
		
		foreach ($data[$_key] as $row ) {
			if( $row[$_key . '_id'] ) {
				$after[] = $row[$_key.'_id'];
				
				foreach( $row[$_key.'_description'] as $language_id => $row2 ) {
					if( isset( $parameters[$row2['name']] ) ) {
						$parameters[$row2['name']]['ids'][] = $row[$_key.'_id'];
						$parameters[$row2['name']]['language_id'] = null;
					} else {
						$parameters[$row2['name']] = array(
							'ids' => array( $row[$_key.'_id'] ),
							'name' => $row2['name'],
							'language_id' => $language_id
						);
					}
				}
			}
		}
		
		foreach( $beforeEdit as $row ) {
			$before[$row[$_key.'_id']] = $row[$type=='filter' ? 'filter_group_id' : 'option_id'];
		}
		
		if( null != ( $diff = array_diff( array_keys( $before ), $after ) ) ) {		
			$this->_deleteMFilterValues( $this->_getMFilterValueIds( $type, $diff ) );
		}
		
		if( $before ) {
			$group_id = reset( $before );
		
			$values = array();
			
			foreach( $this->db->query(sprintf("
					SELECT
						*
					FROM
						`" . DB_PREFIX . "mfilter_values`
					WHERE
						`type` = '%s' AND
						`value_id` IN(%s)
					ORDER BY
						`mfilter_value_id` ASC
				",
				$type,
				implode(',', $after)
			))->rows as $row ) {
				$values[$row['value_id']][$row['language_id']] = array(
					'id' => $row['mfilter_value_id'],
					'language_id' => $row['language_id'],
					'seo' => $row['seo_value']
				);
			}
			
			foreach( $parameters as $parameter ) {
				$seo = Mfilter_Helper::i()->convertValueToSeo( $parameter['name'], false );
				
				foreach( $parameter['ids'] as $id ) {
					if( isset( $values[$id][$parameter['language_id']] ) ) {
						if( strcmp( $values[$id][$parameter['language_id']]['seo'], $seo ) !== 0 ) {
							$this->db->query( "UPDATE `" . DB_PREFIX . "mfilter_values` SET " . $this->arrToQuery(array(
								'seo_value' => $this->findUniqueSeoValue(array(
									'type' => $type,
									'value_group_id' => $group_id
								), $seo, array(
									'mfilter_value_id' => $values[$id][$parameter['language_id']]['id']
								))
							)) . " WHERE `mfilter_value_id` = " . $values[$id][$parameter['language_id']]['id'] );
						}
					} else if( isset( $values[$id] ) ) {
						$new_ids = array();

						if( $parameter['language_id'] ) {
							if( empty( $values[$id][null]['updated'] ) ) {
								$this->db->query( "UPDATE `" . DB_PREFIX . "mfilter_values` SET " . $this->arrToQuery(array(
									'seo_value' => $this->findUniqueSeoValue(array(
										'type' => $type,
										'value_group_id' => $group_id
									), $seo, array(
										'mfilter_value_id' => $values[$id][null]['id'] 
									)),
									'language_id' => $parameter['language_id']
								)) . " WHERE `mfilter_value_id` = " . $values[$id][null]['id'] );

								$values[$id][null]['updated'] = true;
							} else {
								$this->db->query( "INSERT INTO`" . DB_PREFIX . "mfilter_values` SET " . $this->arrToQuery(array(
									'type' => $type,
									'seo_value' => $this->findUniqueSeoValue(array(
										'type' => $type,
										'value_group_id' => $group_id
									), $seo),
									'value_id' => $id,
									'value_group_id' => $group_id,
									'language_id' => $parameter['language_id']
								)));

								$new_ids[$values[$id][null]['id']][] = $this->db->getLastId();
							}
						} else {
							$first = reset( $values[$id] );

							$this->db->query( "UPDATE `" . DB_PREFIX . "mfilter_values` SET " . $this->arrToQuery(array(
								'seo_value' => $this->findUniqueSeoValue(array(
									'type' => $type,
									'value_group_id' => $group_id
								), $seo, array(
									'mfilter_value_id' => $first['id'] 
								)),
								'language_id' => null
							)) . " WHERE `mfilter_value_id` = " . $first['id'] );

							$this->db->query("DELETE FROM `" . DB_PREFIX . "mfilter_values` WHERE `type` = '" . $type . "' AND `value_id` = '" . $id . "' AND `language_id` IS NOT NULL");
						}

						foreach( $new_ids as $find_id => $insert_ids ) {
							$this->db->query("
								UPDATE 
									`" . DB_PREFIX . "product` 
								SET 
									`mfilter_values` = CONCAT( 
										IF( `mfilter_values` = '' OR `mfilter_values` IS NULL, '', CONCAT( `mfilter_values`, ',' ) ), 
										'" . implode( ',', $insert_ids ) . "' 
									)
								WHERE
									FIND_IN_SET( " . $find_id . ", `mfilter_values` )
							");
						}
					}
				}
			}
		}
	}
	
	private function findUniqueSeoValue( array $data, $seo, array $except = array() ) {
		$vals = array();
		
		foreach( $data as $col => $val ) {
			$vals[] = "`" . $col . "` " . ( $val === null ? 'IS NULL' : " = '" . $this->db->escape( $val ) . "'" );
		}
		
		foreach( $except as $col => $val ) {
			$vals[] = "`" . $col . "` != '" . $this->db->escape( $val ) . "'";
		}
		
		while( $this->db->query(sprintf("
			SELECT
				*
			FROM
				`" . DB_PREFIX . "mfilter_values`
			WHERE
				%s AND `seo_value` = '%s'
			",
			implode( ' AND ', $vals ),
			$this->db->escape( $seo )
		))->num_rows ) {
			$seo .= '-' . ( isset( $except['mfilter_value_id'] ) ? $except['mfilter_value_id'] : rand(100,999) );
		}
		
		return $seo;
	}
	
	private function arrToQuery( $arr ) {
		$vals = array();
		
		foreach( $arr as $col => $val ) {
			$vals[] = "`" . $col . "` = " . ( $val === null ? 'NULL' : "'" . $this->db->escape( $val ) . "'" );
		}
		
		return implode( ',', $vals );
	}
	
	private function deleteParam( $type, $id ) {
		$ids = array();
		$k1 = array(
			'filter' => 'filter_group_id',
			'option' => 'option_id',
			'attribute' => 'attribute_id',
		);
		$k2 = array(
			'filter' => 'filter_id',
			'option' => 'option_value_id',
			'attribute' => 'attribute_id',
		);
		$k3 = array(
			'filter' => 'filter',
			'option' => 'option_value',
			'attribute' => 'attribute',
		);
		
		foreach( $this->db->query( "SELECT * FROM `" . DB_PREFIX . $k3[$type] . "` WHERE `" . $k1[$type] . "` = " . (int) $id )->rows as $row ) {
			$ids[] = $row[$k2[$type]];
		}
		
		if( ! $ids )
			return;
		
		$this->_deleteMFilterValues( $this->_getMFilterValueIds( $type, $ids ) );
	}
	
	// FILTERS /////////////////////////////////////////////////////////////////
	
	public function deleteFilter( $filter_group_id ) {
		if( ! $this->hasFilters() )
			return;
		
		$this->deleteParam( 'filter', $filter_group_id );
	}
	
	public function editFilter( $data, $beforeFilterEdit ) {
		if( ! $this->hasFilters() )
			return;
		
		$this->editParameter( 'filter', $data, $beforeFilterEdit );
	}
	
	// OPTIONS /////////////////////////////////////////////////////////////////
	
	public function deleteOption( $option_id ) {
		$this->deleteParam('option', $option_id);
	}
	
	public function editOption( $data, $beforeOptionEdit ) {
		$this->editParameter( 'option', $data, $beforeOptionEdit );
	}
	
	// Attributes //////////////////////////////////////////////////////////////
	
	public function deleteAttribute( $attribute_id ) {
		$this->deleteParam('attribute', $attribute_id);
	}
	
	public function editAttribute( $data, $attribute_id ) {
		
	}
}