<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class Theme_Admin_DefaultModel extends Theme_DefaultModel
{
    public function getProducts($data = array(), $added_ids = array())
    {
        $added_ids[] = 0;
        $sql = "SELECT IF( p.product_id IN ( " . implode(',', (array) $added_ids) . " ) ,  '1',  '0' ) AS added, p . * , pd . *
                    FROM " . DB_PREFIX . "product p
                    LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                    INNER JOIN " . DB_PREFIX . "product_to_store AS pts ON p.product_id = pts.product_id AND pts.store_id = " . $this->context->getStoreId() . "
                    WHERE pd.language_id = '" . (int) $this->getOcConfig()->get('config_language_id') . "'";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
            $sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
        }

        if (isset($data['filter_price_less']) && !is_null($data['filter_price_less'])) {
            $sql .= " AND p.price < '" . $this->db->escape($data['filter_price_less']) . "'";
        }

        if (isset($data['filter_price_more']) && !is_null($data['filter_price_more'])) {
            $sql .= " AND p.price > '" . $this->db->escape($data['filter_price_more']) . "'";
        }

        if (isset($data['filter_category_id']) && !empty($data['filter_category_id'])) {
            $sql .= " AND EXISTS(SELECT 1 FROM "  . DB_PREFIX . "product_to_category WHERE category_id = '" . (int) $data['filter_category_id'] . "' AND product_id = p.product_id)";
        }

        if (!isset($data['filter_disabled']) || is_null($data['filter_disabled'])) {
            $sql .= " AND p.status = '1'";
        }

        if (isset($data['filter_selected']) && !is_null($data['filter_selected'])) {
            $sql .= " AND p.product_id IN ( " . implode(',', (array) $added_ids) . " )";
        }

        if (isset($data['filter_specials']) && !is_null($data['filter_specials'])) {
            $sql .= " AND EXISTS(SELECT 1 FROM "  . DB_PREFIX . "product_special WHERE product_id = p.product_id)";
        }

        $sort_data = array(
            'p.date_added',
            'p.price',
            'pd.name',
            'p.quantity'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.name";
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalProducts($data = array(), $added_ids = array())
    {
        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total
                FROM " . DB_PREFIX . "product p
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                INNER JOIN " . DB_PREFIX . "product_to_store AS pts ON p.product_id = pts.product_id AND pts.store_id = " . $this->context->getStoreId() . "
                WHERE pd.language_id = '" . (int) $this->getOcConfig()->get('config_language_id') . "'";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_model']) && !is_null($data['filter_model'])) {
            $sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
        }

        if (isset($data['filter_price_less']) && !is_null($data['filter_price_less'])) {
            $sql .= " AND p.price < '" . $this->db->escape($data['filter_price_less']) . "'";
        }

        if (isset($data['filter_price_more']) && !is_null($data['filter_price_more'])) {
            $sql .= " AND p.price > '" . $this->db->escape($data['filter_price_more']) . "'";
        }

        if (isset($data['filter_category_id']) && !empty($data['filter_category_id'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM "  . DB_PREFIX . "product_to_category WHERE category_id = '" . $this->db->escape($data['filter_category_id']) . "')";
        }

        if (!isset($data['filter_disabled']) || is_null($data['filter_disabled'])) {
            $sql .= " AND p.status = '1'";
        }

        if (isset($data['filter_selected']) && !is_null($data['filter_selected'])) {
            if (empty($added_ids)) {
                return 0;
            }
            $sql .= " AND p.product_id IN ( " . implode(',', (array) $added_ids) . " )";
        }

        if (isset($data['filter_specials']) && !is_null($data['filter_specials'])) {
            $sql .= " AND EXISTS(SELECT 1 FROM "  . DB_PREFIX . "product_special WHERE product_id = p.product_id)";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getProductOptions(array $types = array())
    {
        $sql = 'SELECT *, GROUP_CONCAT(ov.image SEPARATOR "") AS value_images
                FROM `' . DB_PREFIX . 'option` o
                LEFT JOIN ' . DB_PREFIX . 'option_description od ON (o.option_id = od.option_id)
                LEFT JOIN ' . DB_PREFIX . 'option_value AS ov ON (o.option_id = ov.option_id) 
                WHERE od.language_id = ' . (int) $this->getOcConfig()->get('config_language_id');

        if (!empty($types)) {
            foreach ($types as &$type) {
                $type = "'" . $this->db->escape($type) . "'";
            }
            $sql .= " AND o.type IN ( " . implode(',', $types) . " )";
        }

        $sql .= ' GROUP BY o.option_id';

        $result = array();

        foreach ($this->db->query($sql)->rows as $row) {
            $row['has_images']         = !empty($row['value_images']);
            $result[$row['option_id']] = $row;
        }

        return $result;
    }

    public function getAllInformationPages()
    {
        $sql = 'SELECT *
                FROM ' . DB_PREFIX . 'information';

        return $this->db->query($sql)->rows;
    }

    public function getAllManufacturers()
    {
        $sql = 'SELECT *
                FROM ' . DB_PREFIX . 'manufacturer';

        return $this->db->query($sql)->rows;
    }

    public function getAllProducts()
    {
        $sql = 'SELECT *
                FROM ' . DB_PREFIX . 'product';

        return $this->db->query($sql)->rows;
    }

    public function getAllStores()
    {
        static $stores = null;

        if (null !== $stores) {
            return $stores;
        }

        /** @var ModelSettingStore $ocSettingStoreModel */
        $ocSettingStoreModel = $this->getOcModel('setting/store');

        $stores = array_merge(array(
            0 => array(
                'store_id' => 0,
                'name'     => $this->engine->getDbSettingsHelper('setting')->getKey('config_name', 0, 'config') . ' (Default)'
            )
        ), $ocSettingStoreModel->getStores());

        $key = 'config_' . ($this->engine->gteOc22() ? 'theme' : 'template');

        foreach ($stores as &$store) {
            $store['has_theme'] = $this->engine->getDbSettingsHelper('setting')->getKey($key, $store['store_id'], 'config') == $this->context->getBasename();
        }

        return $stores;
    }

    public function getCurrentStore()
    {
        foreach ($this->getAllStores() as $store) {
            if ($store['store_id'] == $this->context->getStoreId()) {
                return $store;
            }
        }

        return false;
    }

    public function getNoImage()
    {
        return $this->getOcModel('tool/image')->resize('no_image.jpg', 100, 100);
    }

}
