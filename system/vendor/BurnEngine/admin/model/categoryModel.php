<?php

require_once dirname(__FILE__) . '/../../model/categoryModel.php';

class Theme_Admin_CategoryModel extends Theme_CategoryModel
{
    public function storeHasCategories()
    {
        $sql = 'SELECT *
                FROM '       . DB_PREFIX . 'category c
                INNER JOIN ' . DB_PREFIX . 'category_to_store c2s ON (c.category_id = c2s.category_id)
                WHERE c2s.store_id = ' . $this->context->getStoreId() . ' AND c.status = 1
                LIMIT 1';

        return $this->db->query($sql)->num_rows > 0;
    }

    public function getAllCategories()
    {
        $sql = 'SELECT *
                FROM ' . DB_PREFIX . 'category';

        return $this->db->query($sql)->rows;
    }
}