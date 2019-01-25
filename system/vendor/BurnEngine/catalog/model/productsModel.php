<?php

class Theme_Catalog_ProductsModel extends TB_ExtensionModel
{
    public function mergeProducts(array &$products, array $options)
    {
        if (empty($products)) {
            return;
        }

        $ids = array();
        $new_products = array();
        foreach ($products as $product) {
            $ids[] = (int) $product['product_id'];
            $new_products[(int) $product['product_id']] = $product;
        }
        $ids = implode(',', $ids);

        $sql_select = '';
        if ($options['secondary_image']) {
            $sql_select = '(SELECT pi.image
                             FROM ' . DB_PREFIX . 'product_image AS pi
                             WHERE pi.product_id = p.product_id
                             ORDER BY pi.sort_order ASC
                             LIMIT 1) AS secondary_image,';
        }

        $sql = 'SELECT p.product_id, p.model, p.quantity, p.points , p.tax_class_id, p.date_available, p.date_added,
                       p.weight, p.weight_class_id, p.minimum, p.price AS price_num, p.image, pd.description,
                       ss.name AS stock_status, ss.stock_status_id,' . $sql_select . '
                       (SELECT price
                        FROM ' . DB_PREFIX . 'product_special ps
                        WHERE ps.product_id = p.product_id
                              AND ps.customer_group_id = ' . $this->getThemeModel()->getCustomerGroupId() . '
                              AND ( (ps.date_start = "0000-00-00" OR ps.date_start  < NOW()) AND (ps.date_end = "0000-00-00" OR ps.date_end > NOW()) )
                              ORDER BY ps.priority ASC, ps.price ASC LIMIT 1
                        ) AS special_num,
                        (SELECT AVG(rating) AS total
                         FROM ' . DB_PREFIX . 'review r1
                         WHERE r1.product_id = p.product_id AND r1.status = \'1\'
                         GROUP BY r1.product_id) AS rating
                FROM ' . DB_PREFIX . 'product AS p
                LEFT JOIN ' . DB_PREFIX . 'stock_status AS ss ON p.stock_status_id = ss.stock_status_id AND ss.language_id = ' . $this->context->getCurrentLanguage('id') . '
                LEFT JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id AND pd.language_id = ' . $this->context->getCurrentLanguage('id') . '
                WHERE p.product_id IN (' . $ids . ')';

        $query = $this->db->query($sql);

        if (!$query->num_rows) {
            return;
        }

        foreach ($query->rows as $product) {
            foreach (array('weight', 'length', 'width', 'height') as $prop) {
                if (!empty($new_products[$product['product_id']][$prop])) {
                    unset($product[$prop]);
                }
            }

            $new_products[$product['product_id']] = array_merge($new_products[$product['product_id']], $product);
        }

        $products = $new_products;
    }

    public function getProducts(array $data)
    {
        $customer_group_id = $this->getThemeModel()->getCustomerGroupId();

        $sql_select = '';
        if ($data['secondary_image']) {
            $sql_select = '(SELECT pi.image
                             FROM ' . DB_PREFIX . 'product_image AS pi
                             WHERE pi.product_id = p.product_id
                             ORDER BY pi.sort_order ASC
                             LIMIT 1) AS secondary_image,';
        }

        if (!empty($data['random_query'])) {
            $sql_select .= ' ' . rand(0, 9999999) . ',';
        }

        $sql = 'SELECT p.*, pd.name, pd.description, pd.meta_description, m.name AS manufacturer,' . $sql_select . '
                       (SELECT price
                        FROM ' . DB_PREFIX . "product_discount AS pd2
                        WHERE pd2.product_id = p.product_id
                            AND pd2.customer_group_id = '" . $customer_group_id . "'
                            AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW())
                            AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))
                        ORDER BY pd2.priority ASC, pd2.price ASC
                        LIMIT 1
                        ) AS discount,
                        (SELECT price
                         FROM " . DB_PREFIX . "product_special AS ps
                         WHERE ps.product_id = p.product_id
                            AND ps.customer_group_id = '" . $customer_group_id . "'
                            AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
                            AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
                         ORDER BY ps.priority ASC, ps.price ASC
                         LIMIT 1
                        ) AS special,
                        (SELECT AVG(rating) AS total
                         FROM " . DB_PREFIX . "review r1
                         WHERE r1.product_id = p.product_id
                            AND r1.status = '1'
                            GROUP BY r1.product_id
                        ) AS rating,
                        (SELECT ss.name
                         FROM " . DB_PREFIX . "stock_status ss
                         WHERE ss.stock_status_id = p.stock_status_id
                            AND ss.language_id = '" . (int) $this->getOcConfig()->get('config_language_id') . "'
                        ) AS stock_status,
                        (SELECT COUNT(*) AS total
                         FROM " . DB_PREFIX . "review r2
                         WHERE r2.product_id = p.product_id AND r2.status = '1'
                         GROUP BY r2.product_id
                        ) AS reviews
                FROM "       . DB_PREFIX . 'product AS p
                INNER JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id
                LEFT JOIN '  . DB_PREFIX . 'manufacturer AS m ON p.manufacturer_id = m.manufacturer_id
                INNER JOIN ' . DB_PREFIX . 'product_to_store p2s ON p.product_id = p2s.product_id';

        $sql .= '
                 WHERE p.status = 1
                    AND p.date_available <= NOW()
                    AND pd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id') .
                '   AND p2s.store_id = '   . (int) $this->getOcConfig()->get('config_store_id');

        if (!empty($data['category_ids'])) {
            $sql .= ' AND p.product_id IN (
                SELECT product_id
                FROM ' . DB_PREFIX . 'product_to_category
                WHERE category_id IN (' . implode(',', $data['category_ids']) . '))';
        }

        if (!empty($data['product_ids'])) {
            $sql .= ' AND p.product_id IN (' . implode(',', $data['product_ids']) . ')';
        }

        if (isset($data['order']) && strtoupper($data['order']) != 'DESC' && strtoupper($data['order']) != 'ASC') {
            $sql .= " ORDER BY " . $data['order'];
        } else
        if  (isset($data['sort']) && $data['sort'] == 'rating' && $data['order'] == 'ASC') {
            $sql .= ' ORDER BY -rating DESC';
        } else
        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . (isset($data['sort']) ? $data['sort'] : 'pd.name');
            $sql .= ' ' . (isset($data['order']) ? $data['order'] : 'ASC');
        }

        if (isset($data['start']) || isset($data['limit'])) {
            $sql .= " LIMIT " . abs((int) $data['start']) . "," . abs((int) $data['limit']);
        }

        $result = array();

        foreach ($this->db->query($sql)->rows as $product) {
            $product['price_num'] = $product['price'] = ($product['discount'] ? $product['discount'] : $product['price']);
            $product['special_num'] = $product['special'];

            if ($data['special_fields']) {
                $product['special_date_start'] = '';
                $product['special_date_end']   = '';
            }

            $result[$product['product_id']] = $product;
        }

        if ($data['special_fields'] && $result) {
            $special_fields = $this->dbHelper->getRecords('product_special', array('product_id' => array_keys($result)));

            foreach ($special_fields as $field) {
                if ($field['date_start'] != '0000-00-00') {
                    $result[$field['product_id']]['special_date_start'] = $field['date_start'];
                }

                if ($field['date_end'] != '0000-00-00') {
                    $result[$field['product_id']]['special_date_end'] = $field['date_end'];
                }
            }
        }

        return $result;
    }

    public function getProductInfo($product_id)
    {
        $sql = 'SELECT *
                FROM ' . DB_PREFIX . 'product
                WHERE product_id = ' . (int) $product_id;

        return $this->db->query($sql)->row;
    }

    public function getProductCategories($product_id)
    {
        static $products = array();

        if (isset($products[$product_id])) {
            return $products[$product_id];
        }

        $sql = 'SELECT cd.*
                FROM ' . DB_PREFIX . 'product_to_category AS ptc
                INNER JOIN ' . DB_PREFIX . 'category_description AS cd ON ptc.category_id = cd.category_id
                WHERE ptc.product_id = ' . (int) $product_id . ' 
                      AND cd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id');

        return $this->db->query($sql)->rows;
    }

    public function getBestsellerProductIds($limit)
    {
        $sql = "SELECT op.product_id, COUNT(*) AS total
                FROM " . DB_PREFIX . "order_product op
                LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)
                LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id)
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
                WHERE o.order_status_id > '0'
                    AND p.status = '1'
                    AND p.date_available <= NOW()
                    AND p2s.store_id = '" . (int) $this->getOcConfig()->get('config_store_id') . "'
                GROUP BY op.product_id
                ORDER BY total DESC
                LIMIT " . abs((int) $limit);

        $product_ids = array();
        foreach ($this->db->query($sql)->rows as $row) {
            $product_ids[] = $row['product_id'];
        }

        return $product_ids;
    }

    public function getSpecialProductIds($data)
    {
        $sql = 'SELECT ps.product_id
        ';

        if  (isset($data['sort']) && $data['sort'] == 'rating') {
            $sql .= ',
                        (SELECT AVG(rating) AS total
                         FROM ' . DB_PREFIX . 'review r1
                         WHERE r1.product_id = p.product_id
                            AND r1.status = 1
                            GROUP BY r1.product_id
                        ) AS rating
                    ';
        }

        $sql .= 'FROM ' . DB_PREFIX . 'product_special ps
                INNER JOIN ' . DB_PREFIX . 'product p ON (ps.product_id = p.product_id)
                ';
        if  (isset($data['sort']) && $data['sort'] == 'pd.name') {
            $sql .= 'INNER JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id
            ';
        }

        $sql .= 'INNER JOIN ' . DB_PREFIX . 'product_to_store p2s ON (p.product_id = p2s.product_id)
                WHERE p.status = 1 AND p.date_available <= NOW()
                      AND p2s.store_id = ' . (int) $this->getOcConfig()->get('config_store_id');

        if  (isset($data['sort']) && $data['sort'] == 'pd.name') {
            $sql .= ' AND pd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id');
        }

        $sql .= '     AND ps.customer_group_id = ' . (int) $this->getThemeModel()->getCustomerGroupId() . '
                      AND ((ps.date_start = "0000-00-00" OR ps.date_start < NOW())
                      AND (ps.date_end = "0000-00-00" OR ps.date_end > NOW()))
                GROUP BY ps.product_id';

        if  (isset($data['sort']) && $data['sort'] == 'p.price') {
            $data['sort'] = 'ps.price';
        }

        if ($data['randomize']) {
            $sql .= ' ORDER BY RAND()';
        } else
        if  (isset($data['sort']) && $data['sort'] == 'rating' && $data['order'] == 'ASC') {
            $sql .= ' ORDER BY -rating DESC';
        } else {
            $sql .= " ORDER BY " . (isset($data['sort']) ? $data['sort'] : 'pd.name');
            $sql .= ' ' . (isset($data['order']) ? $data['order'] : 'ASC');
        }

        if (isset($data['start']) || isset($data['limit'])) {
            $sql .= " LIMIT " . abs((int) $data['start']) . "," . abs((int) $data['limit']);
        }

        return array_column($this->db->query($sql)->rows, 'product_id');
    }

    public function getLatestReviews(array $options)
    {
        $limit = $options['filter_limit'];
        $customer_group_id = $this->getThemeModel()->getCustomerGroupId();

        $sql = 'SELECT r.rating,
                       r.text AS review_text,
                       r.date_added,
                       r.author,
                       p.price,
                       p.tax_class_id,
                       p.product_id,
                       p.image,
                       pd.name ';

        if ($options['show_average']) {
            $sql .= ',
                       (SELECT AVG(rating) AS total
                         FROM ' . DB_PREFIX . 'review
                         WHERE product_id = p.product_id AND status = 1
                         GROUP BY product_id
                        ) AS avg_rating ';
        }

        if ($options['show_price']) {
            $sql .= ',
                       (SELECT price
                         FROM ' . DB_PREFIX . 'product_discount AS pd2
                         WHERE pd2.product_id = p.product_id
                            AND pd2.customer_group_id = ' . $customer_group_id . '
                            AND pd2.quantity = 1 AND ((pd2.date_start = "0000-00-00" OR pd2.date_start < NOW())
                            AND (pd2.date_end = "0000-00-00" OR pd2.date_end > NOW()))
                        ORDER BY pd2.priority ASC, pd2.price ASC
                        LIMIT 1
                        ) AS discount,
                       (SELECT price
                         FROM ' . DB_PREFIX . 'product_special AS ps
                         WHERE ps.product_id = p.product_id
                            AND ps.customer_group_id = ' . $customer_group_id . '
                            AND ((ps.date_start = "0000-00-00" OR ps.date_start < NOW())
                            AND (ps.date_end = "0000-00-00" OR ps.date_end > NOW()))
                         ORDER BY ps.priority ASC, ps.price ASC
                         LIMIT 1
                        ) AS special ';
        }

        $sql .= 'FROM (
                     SELECT MAX(review_id) AS review_id, product_id
                     FROM ' . DB_PREFIX . 'review
                     WHERE status = 1
                     GROUP BY product_id
                ) AS max_r
                INNER JOIN ' . DB_PREFIX . 'review  AS r ON max_r.review_id  = r.review_id
                INNER JOIN ' . DB_PREFIX . 'product AS p ON r.product_id = p.product_id
                INNER JOIN ' . DB_PREFIX . 'product_to_store p2s ON p.product_id = p2s.product_id
                INNER JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id
                WHERE p.status = 1
                    AND pd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id') .'
                    AND p2s.store_id = ' . (int) $this->getOcConfig()->get('config_store_id');

        if (isset($options['category_ids']) && !empty($options['category_ids'])) {
            $sql .= ' AND p.product_id IN (
                SELECT product_id
                FROM ' . DB_PREFIX . 'product_to_category
                WHERE category_id IN (' . implode(',', $options['category_ids']) . '))';
        }

        $sql .= ' ORDER BY r.date_added DESC
                  LIMIT ' . $limit;

        return $this->db->query($sql)->rows;
    }

    public function getTotalProductsByManufacturerAndCategory($manufacturer_id, $category_id)
    {
        /** @var Theme_Catalog_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');

        $children_ids = $categoryModel->getCategorySuccessorIds($category_id);
        $ids = array_merge($children_ids, (array) $category_id);
        $sql = "SELECT COUNT(DISTINCT(p.product_id)) AS total
                FROM "       . DB_PREFIX . "product AS p
                INNER JOIN " . DB_PREFIX . "product_to_category AS p2c ON p2c.product_id = p.product_id AND p2c.category_id IN(" . implode(',', $ids) . ")
                LEFT JOIN "  . DB_PREFIX . "product_to_store p2s ON p.product_id = p2s.product_id
                WHERE p.status = '1' AND p.date_available <= NOW() AND p.manufacturer_id = '" . (int) $manufacturer_id . "'";
        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getProductsByManufacturerAndCategory(array $options)
    {
        /** @var Theme_Catalog_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');

        $ids = $categoryModel->getCategorySuccessorIds((int) $options['category_id']);
        array_push($ids, (int) $options['category_id']);

        $customer_group_id = $this->getThemeModel()->getCustomerGroupId();

        $sql_select = '';
        if ($options['secondary_image']) {
            $sql_select = '(SELECT pi.image
                             FROM ' . DB_PREFIX . 'product_image AS pi
                             WHERE pi.product_id = p.product_id
                             ORDER BY pi.sort_order ASC
                             LIMIT 1) AS secondary_image,';
        }

        $sql = "SELECT DISTINCT(p.product_id), p.*,
                       pd.name, pd.name, pd.description, pd.meta_description,
                       m.manufacturer_id, m.name AS manufacturer,
                       $sql_select
                       (SELECT price
                        FROM " . DB_PREFIX . "product_discount AS pd2
                        WHERE pd2.product_id = p.product_id
                            AND pd2.customer_group_id = '" . $customer_group_id . "'
                            AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW())
                            AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))
                        ORDER BY pd2.priority ASC, pd2.price ASC
                        LIMIT 1) AS discount,
                        (SELECT price
                         FROM " . DB_PREFIX . "product_special AS ps
                         WHERE ps.product_id = p.product_id
                            AND ps.customer_group_id = '" . $customer_group_id . "'
                            AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
                            AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
                         ORDER BY ps.priority ASC, ps.price ASC
                         LIMIT 1) AS special,
                        (SELECT AVG(rating) AS total
                         FROM " . DB_PREFIX . 'review r1
                         WHERE r1.product_id = p.product_id AND r1.status = 1
                         GROUP BY r1.product_id) AS rating,
                        (SELECT AVG(rating) AS total
                         FROM ' . DB_PREFIX . 'review r1
                         WHERE r1.product_id = p.product_id
                            AND r1.status = 1
                            GROUP BY r1.product_id
                        ) AS rating,
                        (SELECT ss.name
                         FROM ' . DB_PREFIX . 'stock_status ss
                         WHERE ss.stock_status_id = p.stock_status_id
                            AND ss.language_id = ' . (int) $this->getOcConfig()->get('config_language_id') . '
                        ) AS stock_status,
                        (SELECT COUNT(*) AS total
                         FROM ' . DB_PREFIX . 'review r2
                         WHERE r2.product_id = p.product_id AND r2.status = 1
                         GROUP BY r2.product_id
                        ) AS reviews
                FROM '       . DB_PREFIX . 'product p
                INNER JOIN ' . DB_PREFIX . 'product_to_category AS p2c ON p2c.product_id = p.product_id AND p2c.category_id IN(' . implode(',', $ids) . ')
                LEFT JOIN '  . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id)
                LEFT JOIN '  . DB_PREFIX . 'product_to_store p2s ON (p.product_id = p2s.product_id)
                LEFT JOIN '  . DB_PREFIX . 'manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
                LEFT JOIN '  . DB_PREFIX . 'stock_status ss ON (p.stock_status_id = ss.stock_status_id) AND ss.language_id = ' .    (int) $this->getOcConfig()->get('config_language_id') . '
                WHERE p.status = 1
                      AND p.date_available <= NOW()
                      AND pd.language_id = ' .    (int) $this->getOcConfig()->get('config_language_id') . '
                      AND p2s.store_id = ' .      (int) $this->getOcConfig()->get('config_store_id') . '
                      AND m.manufacturer_id = ' . (int) $options['manufacturer_id'];

        $sort_data = array(
            'p.date_added',
            'p.product_id',
            'pd.name',
            'p.sort_order',
            'special',
            'rating',
            'p.price',
            'p.model'
        );

        if (in_array($options['sort'], $sort_data)) {
            if ($options['sort'] == 'pd.name' || $options['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $options['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $options['sort'];
            }
        } else {
            $sql .= " ORDER BY p.sort_order";
        }

        if ($options['order'] == 'DESC') {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        $sql .= " LIMIT " . abs((int) $options['start']) . "," . abs((int) $options['limit']);

        $result = array();

        foreach ($this->db->query($sql)->rows as $product) {
            $product['price_num'] = $product['price'] = ($product['discount'] ? $product['discount'] : $product['price']);
            $product['special_num'] = $product['special'];
            $result[$product['product_id']] = $product;
        }

        return $result;
    }

    public function compatibilityProducts(&$products, TB_ViewDataBag $themeData, $options = array())
    {
        if ((!$this->engine->gteOc2() && !defined('TB_COMPATIBILITY_MOD')) || !count($products)) {
            return false;
        }

        $config_group = $this->engine->gteOc22() ? $this->engine->getConfigTheme() : 'config';

        $options = array_replace(array(
            'create_thumb' => false,
            'image_width'  => $this->engine->getOcConfig()->get($config_group . '_image_product_width'),
            'image_height' => $this->engine->getOcConfig()->get($config_group . '_image_product_height')
        ), $options);

        foreach ($products as &$product) {
            $product['original_image'] = $product['image'];
            if (!$options['create_thumb']) {
                $product['image'] = false;
            }

            $product['reviews'] = 0; // OC 1.5.x
        }

        $themeData['db_product_results'] = $products;

        $module = 'module/latest';
        if ($this->engine->gteOc23()) {
            $module = 'extension/' . $module;
        }

        $product_description_length = $this->engine->getOcConfig()->get($config_group . '_product_description_length');
        $this->engine->getOcConfig()->set($config_group . '_product_description_length', 65535);

        $this->engine->getThemeExtension()->loadController($module, array(
            'limit'        => 1,
            'position'     => '',
            'layout_id'    => '',
            'width'        => $options['image_width'],
            'height'       => $options['image_height'],
            'image_width'  => $options['image_width'],  // OC 1.5.x
            'image_height' => $options['image_height'], // OC 1.5.x
        ));

        $this->engine->getOcConfig()->set($config_group . '_product_description_length', $product_description_length);

        foreach ($themeData['db_product_results'] as $modified_product) {
            $products[$modified_product['product_id']] = array_merge($products[$modified_product['product_id']], $modified_product);

            if (!$options['create_thumb']) {
                unset($products[$modified_product['product_id']]['thumb']);
            }

            $products[$modified_product['product_id']]['image'] = $products[$modified_product['product_id']]['original_image'];
        }

        unset($themeData['db_product_results']);

        return true;
    }

    public function getSorts($url)
    {
        $sorts = array();

        $language = $this->registry->get('language');
        $language->load('product/category');

        $sorts[] = array(
            'text'  => $language->get('text_default'),
            'value' => 'p.sort_order-ASC',
            'href'  => $url . '&sort=p.sort_order&order=ASC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href'  => $url . '&sort=pd.name&order=ASC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href'  => $url . '&sort=pd.name&order=DESC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href'  => $url . '&sort=p.price&order=ASC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href'  => $url . '&sort=p.price&order=DESC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_rating_desc'),
            'value' => 'rating-DESC',
            'href'  => $url . '&sort=rating&order=DESC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_rating_asc'),
            'value' => 'rating-ASC',
            'href'  => $url . '&sort=rating&order=ASC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href'  => $url . '&sort=p.model&order=ASC'
        );

        $sorts[] = array(
            'text'  => $language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href'  => $url . '&sort=p.model&order=DESC'
        );

        return $sorts;
    }

    public function getLimits($url)
    {
        $limits = array();

        $limits[] = array(
            'text'  => $this->getOcConfig()->get($this->engine->gteOc2() ? 'config_product_limit' : 'config_catalog_limit'),
            'value' => $this->getOcConfig()->get($this->engine->gteOc2() ? 'config_product_limit' : 'config_catalog_limit'),
            'href'  => $url . '&limit=' . $this->getOcConfig()->get('config_catalog_limit')
        );

        $limits[] = array(
            'text'  => 25,
            'value' => 25,
            'href'  => $url . '&limit=25'
        );

        $limits[] = array(
            'text'  => 50,
            'value' => 50,
            'href'  => $url . '&limit=50'
        );

        $limits[] = array(
            'text'  => 75,
            'value' => 75,
            'href'  => $url . '&limit=75'
        );

        $limits[] = array(
            'text'  => 100,
            'value' => 100,
            'href'  => $url . '&limit=100'
        );

        return $limits;
    }
}