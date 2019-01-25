<?php

class OcOptimizations_Catalog_DefaultModel extends TB_ExtensionModel
{
	/**
	 * @var array
	 */
	protected $cache_items;
	/**
	 * @var TB_Cache
	 */
	protected $cache;

	/**
	 * @var DB
	 */
	protected $dbh;

	public function getCache($dbh)
	{
		if (!isset(TB_Engine::instance()->getThemeData()->system)) {
			return null;
		}

		if ($this->canCache()) {
			$this->dbh = $dbh;

			return $this;
		}

		return false;
	}

	protected function canCache()
	{
		$system_settings = TB_Engine::instance()->getThemeData()->system;

		if (empty($system_settings['cache_db']) || !defined('TB_OPTIMIZATIONS_DATABASE') || !empty($_POST) || TB_RequestHelper::isAjaxRequest() || $this->ignoreRoute()) {
			return false;
		}

		$optimizations_database = unserialize(TB_OPTIMIZATIONS_DATABASE);
		$cache_items = array();

		foreach ($optimizations_database['cache_items'] as $item_name => $item_value) {
			if (isset($system_settings['cache_' . $item_name])) {
				$cache_items[$item_name] = !empty($system_settings['cache_' . $item_name]) ? (int) $system_settings['cache_' . $item_name . '_ttl'] : 0;
			} else {
				$cache_items[$item_name] = (int) $item_value['default'] ? (int) $item_value['ttl'] : 0;
			}

		}

		$this->cache_items = $cache_items;
		$this->cache = new TB_Cache(TB_Engine::instance()->getContext()->getTbCacheDir() . '/db');

		return true;
	}

    protected function ignoreRoute()
    {
        return in_array($this->extension->getThemeData()->route, array(
            'feed/google_base',
            'checkout/cart',
            'checkout/checkout',
            'checkout/success',
            'account/register',
            'account/login',
            'account/edit',
            'account/account',
            'account/password',
            'account/address',
            'account/address/update',
            'account/address/delete',
            'account/wishlist',
            'account/order',
            'account/download',
            'account/return',
            'account/return/insert',
            'account/reward',
            'account/voucher',
            'account/transaction',
            'account/newsletter',
            'account/logout',
            'affiliate/login',
            'affiliate/register',
            'affiliate/account',
            'affiliate/edit',
            'affiliate/password',
            'affiliate/payment',
            'affiliate/tracking',
            'affiliate/transaction',
            'affiliate/logout',
            'information/contact',
            'product/compare',
            'error/not_found'
        ));
    }

	public function getResult($sql)
	{
		$cache_key = false;

		if ($this->cache_items['categories_sql'] && preg_match('/SELECT.*FROM ' . DB_PREFIX . 'category(_path)? /is', $sql)) {
			$cache_key = 'categories_sql';
		} else
		if ($this->cache_items['products_sql'] && preg_match('/SELECT.*FROM ' . DB_PREFIX . 'product/is', $sql)) {
			$cache_key = 'products_sql';
		} else
		if ($this->cache_items['information_sql'] && preg_match('/SELECT.*FROM ' . DB_PREFIX . 'information/is', $sql)) {
			$cache_key = 'information_sql';
		} else
		if ($this->cache_items['manufacturer_sql'] && preg_match('/SELECT.*FROM ' . DB_PREFIX . 'manufacturer/is', $sql)) {
			$cache_key = 'manufacturer_sql';
		} else
		if ($this->cache_items['other_sql'] && !stripos($sql, 'SQL_CALC_FOUND_ROWS') && preg_match('/SELECT.*FROM ' . DB_PREFIX . '(layout_route|layout_module|language|oc_tax_rule)/is', $sql)) {
			$cache_key = 'other_sql';
		}

		if (false !== $cache_key) {
			$query_hash = md5($sql);
			if (!$result = $this->cache->get($cache_key . '_' . $query_hash)) {
				$result = $this->dbh->query($sql);
				$this->cache->set($cache_key . '_' . $query_hash, $result, $this->cache_items[$cache_key] * 60);
			}

			return $result;
		}

		return null;
	}
}