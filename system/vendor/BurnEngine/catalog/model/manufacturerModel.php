<?php

class Theme_Catalog_ManufacturerModel extends TB_ExtensionModel
{
    public function getManufacturersByCategoryIds(array $category_ids)
    {
        $sql = "SELECT DISTINCT(m.manufacturer_id), m.*
                FROM "       . DB_PREFIX . "manufacturer m
                LEFT JOIN "  . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)
                INNER JOIN " . DB_PREFIX . "product AS p ON m.manufacturer_id = p.manufacturer_id
                INNER JOIN " . DB_PREFIX . "product_to_category AS p2c ON p.product_id = p2c.product_id
                WHERE m2s.store_id = '" . (int) $this->engine->getOcConfig()->get('config_store_id') . "'
                      AND p2c.category_id IN (" . implode(',', array_filter($category_ids)) . ")
                ORDER BY m.sort_order, LCASE(m.name) ASC";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getManufacturers(array $options = array())
    {
        $sql = 'SELECT m.*
                FROM '      . DB_PREFIX . 'manufacturer m
                LEFT JOIN ' . DB_PREFIX . 'manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)
                WHERE m2s.store_id = ' . (int) $this->engine->getOcConfig()->get('config_store_id');

        if (isset($options['manufacturer_ids']) && !empty($options['manufacturer_ids'])) {
            $sql .= ' AND m.manufacturer_id IN (' . implode(',', array_filter($options['manufacturer_ids'])) . ')';
        }

        $sql .= ' ORDER BY m.sort_order, LCASE(m.name) ASC';

        $query = $this->db->query($sql);

        return $query->rows;
    }
}