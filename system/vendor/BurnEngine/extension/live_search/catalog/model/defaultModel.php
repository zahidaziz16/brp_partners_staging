<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class LiveSearch_Catalog_DefaultModel extends LiveSearch_DefaultModel
{
    public function search($query, $limit)
    {
        $query = $this->db->escape(strtolower($query));

        $sql = 'SELECT p.product_id
                FROM '       . DB_PREFIX . 'product AS p
                INNER JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id
                WHERE p.status = 1
                    AND p.date_available <= NOW()
                    AND pd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id') . '
                    AND LOWER(pd.description) LIKE "%' . $query . '%" COLLATE utf8_unicode_ci
                ORDER BY pd.name ASC
                LIMIT ' . $limit;

        $product_ids = array_column($this->db->query($sql)->rows, 'product_id');

        if (empty($product_ids)) {
            return array();
        }

        return $this->getProducts(' AND p.product_id IN (' . implode(',', $product_ids) . ')');
    }

    public function seed()
    {
        return $this->getProducts(' AND p.date_available <= NOW()');
    }

    protected function getProducts($where)
    {
        $sql = 'SELECT p.product_id, p.model, p.price, p.image, pd.name, p.quantity, p.stock_status_id, p.tax_class_id, m.name AS manufacturer,
                    (SELECT price
                     FROM ' . DB_PREFIX . 'product_discount AS pd2
                     WHERE pd2.product_id = p.product_id
                        AND pd2.customer_group_id = ' . $this->getThemeModel()->getCustomerGroupId() . '
                        AND pd2.quantity = 1 AND ((pd2.date_start = "0000-00-00" OR pd2.date_start < NOW())
                        AND (pd2.date_end = "0000-00-00" OR pd2.date_end > NOW()))
                     ORDER BY pd2.priority ASC, pd2.price ASC
                     LIMIT 1
                    ) AS discount,
                    (SELECT price
                     FROM ' . DB_PREFIX . 'product_special AS ps
                     WHERE ps.product_id = p.product_id
                        AND ps.customer_group_id = ' . $this->getThemeModel()->getCustomerGroupId() . '
                        AND ((ps.date_start = "0000-00-00" OR ps.date_start < NOW())
                        AND (ps.date_end = "0000-00-00" OR ps.date_end > NOW()))
                     ORDER BY ps.priority ASC, ps.price ASC
                     LIMIT 1
                    ) AS special
                FROM '       . DB_PREFIX . 'product AS p
                INNER JOIN ' . DB_PREFIX . 'product_to_store p2s ON (p.product_id = p2s.product_id)
                INNER JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id
                LEFT JOIN '  . DB_PREFIX . 'manufacturer AS m ON p.manufacturer_id = m.manufacturer_id
                WHERE p.status = 1
                    AND p.date_available <= NOW()
                    AND pd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id') . $where . '
                    AND p2s.store_id = ' .   (int) $this->getOcConfig()->get('config_store_id') . '
                ORDER BY pd.name ASC';

        $result = array();

        foreach ($this->db->query($sql)->rows as $product) {
            $product['price_num'] = $product['price'] = ($product['discount'] ? $product['discount'] : $product['price']);
            $product['special_num'] = $product['special'];
            $result[$product['product_id']] = $product;
        }

        return $result;
    }
}