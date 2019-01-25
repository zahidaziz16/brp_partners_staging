<?php

class Theme_CategoryModel extends TB_ExtensionModel
{
    protected $tree;
    protected $flat_tree;
    protected $max_level;

    /**
     * @var Url
     */
    protected $url;

    private $url_links = array();

    public function __construct(TB_Engine $engine, TB_Extension $extension)
    {
        parent::__construct($engine, $extension);

        $this->tree = null;
        $this->flat_tree = null;
        $this->max_level = 0;

        if ($engine->getContext()->getArea() == 'catalog') {
            $this->url = $this->registry->get('url');
        } else {
            $this->url = new Url($engine->getContext()->getBaseHttp());
        }
    }

    public function getCategoriesByParent($parent_id = 0)
    {
        $config = $this->engine->getOcConfig();

        $sql = 'SELECT *
                FROM '      . DB_PREFIX . 'category c
                LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id)
                LEFT JOIN ' . DB_PREFIX . 'category_to_store c2s ON (c.category_id = c2s.category_id)
                WHERE c.parent_id = ' . (int) $parent_id . '
                      AND cd.language_id = ' . (int) $config->get('config_language_id') . '
                      AND c2s.store_id = ' . (int) $config->get('config_store_id') . '
                      AND c.status = 1 AND c.sort_order <> -1
                ORDER BY c.sort_order, LCASE(cd.name)';
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCategoriesByParentWithTotalProducts($parent_id = 0)
    {
        $config = $this->engine->getOcConfig();

        $sql = 'SELECT c.*, cd.*, COUNT(p2c.category_id) AS products_count
                FROM '       . DB_PREFIX . 'category c
                INNER JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id)
                INNER JOIN ' . DB_PREFIX . 'category_to_store c2s ON (c.category_id = c2s.category_id)
                LEFT JOIN '  . DB_PREFIX . 'product_to_store AS p2s ON p2s.store_id = c2s.store_id
                LEFT JOIN '  . DB_PREFIX . 'product_to_category AS p2c ON c.category_id = p2c.category_id AND p2c.product_id = p2s.product_id
                WHERE c.parent_id = ' . (int) $parent_id . '
                      AND cd.language_id = ' . (int) $config->get('config_language_id') . '
                      AND c2s.store_id = ' . (int) $config->get('config_store_id') . '
                      AND c.status = 1 AND c.sort_order <> -1
                GROUP BY c.category_id
                ORDER BY c.sort_order, LCASE(cd.name)';
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCategoryTreeWithTotalProductsMaxLevel2()
    {
        $tree = $this->getCategoriesTree();
        if (empty($tree)) {
            return array();
        }

        $ids = array();
        foreach  ($tree as $category_id => $category) {
            $ids[] = $category_id;
            foreach ($category['children'] as $child_id => $child) {
                $ids[] = $child_id;
                unset($tree[$category_id]['children'][$child_id]['children']);
                unset($tree[$category_id]['children'][$child_id]['children_ids']);
            }
        }

        $config = $this->engine->getOcConfig();

        $sql = 'SELECT c.category_id, COUNT(p2c.category_id) AS products_count, GROUP_CONCAT(p2c.product_id) AS ids
                FROM '      . DB_PREFIX . 'category c
                INNER JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id)
                INNER JOIN ' . DB_PREFIX . 'category_to_store c2s ON (c.category_id = c2s.category_id)
                LEFT JOIN '  . DB_PREFIX . 'product_to_store AS p2s ON p2s.store_id = c2s.store_id
                LEFT JOIN '  . DB_PREFIX . 'product_to_category AS p2c ON c.category_id = p2c.category_id AND p2c.product_id = p2s.product_id
                WHERE c.category_id IN (' . implode(',', $ids) . ')
                      AND cd.language_id = ' . (int) $config->get('config_language_id') . '
                      AND c2s.store_id = ' . (int) $config->get('config_store_id') . '
                      AND c.status = 1 AND c.sort_order <> -1
                GROUP BY c.category_id
                ORDER BY c.sort_order, LCASE(cd.name)';
        $query = $this->db->query($sql);
        $hydrated = array();
        foreach ($query->rows as $row) {
            $row['ids'] = $row['ids'] != null ? explode(',', $row['ids']) : array();
            $hydrated[$row['category_id']] = $row;
        }

        foreach ($tree as $category_id => &$category) {
            $category['products_count'] = $hydrated[$category_id]['products_count'];
            foreach ($category['children'] as $child_id => &$child) {
                $child['products_count'] = $hydrated[$child_id]['products_count'];
                if ($a = count(array_intersect($hydrated[$child_id]['ids'], $hydrated[$category_id]['ids']))) {
                    $category['products_count'] += $child['products_count'] - $a;
                }
            }
        }

        return $tree;
    }

    public function getCategory($category_id)
    {
        $config = $this->engine->getOcConfig();

        $sql = 'SELECT *
                FROM ' . DB_PREFIX . 'category AS c
                LEFT JOIN ' . DB_PREFIX . 'category_description AS cd ON (c.category_id = cd.category_id)
                WHERE c.category_id = ' . (int) $category_id . '
                      AND cd.language_id = ' . (int) $config->get('config_language_id');
   		$query = $this->db->query($sql);

   		return $query->row;
   	}

    public function getAllCategoriesGroupedByParent($language_id = null, $include_disabled = false, $respect_store = true)
    {
        static $result = null;

        $config = $this->engine->getOcConfig();
        $include_disabled = $include_disabled ? 1 : 0;
        $respect_store = $respect_store ? 1 : 0;
        $mask = $include_disabled . $respect_store;

        if (null === $language_id) {
            $language_id = (int) $config->get('config_language_id');
        }

        if (null !== $result && isset($result[$mask][$language_id])) {
            return $result[$mask][$language_id];
        }

        $sql = 'SELECT *
                FROM '      . DB_PREFIX . 'category c
                LEFT JOIN ' . DB_PREFIX . 'category_description cd ON (c.category_id = cd.category_id)';

        if ($respect_store) {
            $sql .= ' INNER JOIN ' . DB_PREFIX . 'category_to_store c2s ON (c.category_id = c2s.category_id) ';
        }

        $sql .= "WHERE cd.language_id = '" . $language_id . "' AND
                       c.sort_order <> '-1'";

        if ($respect_store) {
            $sql .= ' AND c2s.store_id = ' . (int) $config->get('config_store_id');
        }

        if (!$include_disabled) {
            $sql .= ' AND c.status = 1';
        }

        $sql .= ' ORDER BY c.sort_order, LCASE(cd.name)';

        $query = $this->db->query($sql);

        if (null === $result) {
            $result = array(
                0 => array(),
                1 => array()
            );
        }

        $result[$mask][$language_id] = array();

        foreach ((array) $query->rows as $row) {
            $id = $row['parent_id'] ? $row['parent_id'] : '';
            $result[$mask][$language_id][$id][] = $row;
        }

        return $result[$mask][$language_id];
    }

    public function getTreeById($category_id)
    {
        $tree = $this->getCategoriesTree();
        if (isset($tree[$category_id])) {
            return $tree[$category_id];
        }

        $flat_tree = $this->getCategoriesFlatTree();
        if (!isset($flat_tree[$category_id])) {
            return false;
        }

        $path_arr = explode('_', $flat_tree[$category_id]['path']);
        $root_id = array_shift($path_arr);

        if (!isset($tree[$root_id])) {
            return false;
        }

        return $this->getCategoryTreeByPath($path_arr, $tree[$root_id]['children']);
    }

    public function getTreeByPath($path)
    {
        $tree = $this->getCategoriesTree();

        $path_arr = explode('_', $path);
        if (count($path_arr) == 1) {
            if (isset($tree[$path])) {
                return $tree[$path];
            } else {
                return false;
            }
        }
        $root_id = array_shift($path_arr);

        return $this->getCategoryTreeByPath($path_arr, $tree[$root_id]['children']);
    }

    private function getCategoryTreeByPath(array $path_arr, $tree)
    {
        if (count($path_arr) > 1) {
            $root_id = array_shift($path_arr);
            $result = $this->getCategoryTreeByPath($path_arr, $tree[$root_id]['children']);
        } else {
            $result = $tree[$path_arr[0]];
        }

        return $result;

    }

    public function getCategoriesTree()
    {
        if (null !== $this->tree) {
            return $this->tree;
        }

        $cache_key = 'category_tree.' . $this->context->getStoreId() . '.' . (int) $this->engine->getOcConfig()->get('config_language_id');
        $this->tree = $this->engine->getCacheVar($cache_key, array($this, 'buildCategoriesTree'));

        return $this->tree;
    }

    public function getCategoriesMaxLevel()
    {
        if (null === $this->tree) {
            $this->buildCategoriesTree();
        }

        return $this->max_level;
    }

    public function getCategoryLevels()
    {
        static $result = null;

        if (null !== $result) {
            return $result;
        }

        $result = array();
        $categories_max_level = $this->getCategoriesMaxLevel();
        for ($i = 1; $i < $categories_max_level; $i++) {
            $result[$i] = array(
                'label' => 'Level ' . $i . ' categories',
                'value' => 'level_' . $i,
                'level' => $i
            );
        }

        return $result;
    }

    public function buildCategoriesTree($language_id = null)
    {
        return $this->generateCategoriesTree($this->getAllCategoriesGroupedByParent($language_id));
    }

    public function getCategoryFromFlatTree($category_id)
    {
        $flat_tree = $this->getCategoriesFlatTree();
        if (isset($flat_tree[$category_id])) {
            return $flat_tree[$category_id];
        }

        return false;
    }

    public function getCategoriesFlatTree($store_id = null, $language_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        if (null !== $this->flat_tree) {
            return $this->flat_tree;
        }

        if (null === $language_id) {
            $language_id = (int) $this->engine->getOcConfig()->get('config_language_id');
        }

        $cache_key = 'category_flat_tree.' . $store_id . '.' . $language_id;
        $this->flat_tree = $this->engine->getCacheVar($cache_key, array($this, 'buildCategoriesFlatTree'), array('language_id' => $language_id));

        return $this->flat_tree;
    }

    public function buildCategoriesFlatTree($language_id = null, $include_disabled = false, $respect_store = true)
    {
        return $this->generateCategoriesFlatTree($this->getAllCategoriesGroupedByParent($language_id, $include_disabled, $respect_store));
    }

    private function generateCategoriesTree(array $data, $with_children = true, $value = array(), $level = 0, $current_path = '')
    {
        $level++;

        if ($level > $this->max_level) {
            $this->max_level = $level;
        }

        $parent_id = isset($value['category_id']) ? $value['category_id'] : '';

        if (!isset($data[$parent_id])) {
            return array();
        }

        $result = array();
        
        if ($this->context->getArea() == 'admin') {
            $isPetronasUser = true;
        }else {
            $isPetronasUser = $this->getOcModel('account/customer')->isPetronasUser();
        }
        
        
        foreach ($data[$parent_id] as $val) {
            
            if($val['petronas_only'] == 1 && !$isPetronasUser) {
                continue;
            }

            if (!$current_path) {
                $new_path = $val['category_id'];
            } else {
                $new_path = $current_path . '_' . $val['category_id'];
            }

            $children_ids = array();
            if (isset($data[$val['category_id']]) && $with_children) {
                foreach ($data[$val['category_id']] as $child) {
                    $children_ids[] = $child['category_id'];
                }
                $val['children'] = $this->generateCategoriesTree($data, $with_children, $val, $level, $new_path);
            } else {
                $val['children'] = array();
            }

            $successor_ids = array();
            foreach ($val['children'] as $child) {
                $successor_ids[] = (int) $child['category_id'];
                $successor_ids = array_merge($successor_ids, $child['successor_ids']);
            }

            if (!isset($this->url_links[$new_path])) {
                $this->url_links[$new_path] = $this->url->link('product/category', 'path=' . $new_path);
            }

            $result[$val['category_id']] = array(
                'category_id'   => $val['category_id'],
                'parent_id'     => $val['parent_id'],
                'name'          => $val['name'],
                'top'           => $val['top'],
                'image'         => $val['image'],
                'path'          => $new_path,
                'level'         => $level,
                'url'           => $this->url_links[$new_path],
                'children_ids'  => $children_ids,
                'successor_ids' => $successor_ids,
                'children'      => $val['children']
            );
        }

        return $result;
    }

    private function generateCategoriesFlatTree(array $data, $value = array(), $level = 0, $current_path = '')
    {
        $level++;

        $parent_id = isset($value['category_id']) ? $value['category_id'] : '';

        if (!isset($data[$parent_id])) {
            return array();
        }
        
        if ($this->context->getArea() == 'admin') {
            $isPetronasUser = true;
        }else {
            $isPetronasUser = $this->getOcModel('account/customer')->isPetronasUser();
        }
        $result = array();
        foreach ($data[$parent_id] as $val) {
            if($val['petronas_only'] == 1 && !$isPetronasUser) {
                continue;
            }
            
            if (!$current_path) {
                $new_path = $val['category_id'];
            } else {
                $new_path = $current_path . '_' . $val['category_id'];
            }

            if (!isset($this->url_links[$new_path])) {
                $this->url_links[$new_path] = $this->url->link('product/category', 'path=' . $new_path);
            }

            $result[$val['category_id']] = array(
                'category_id'   => $val['category_id'],
                'parent_id'     => $val['parent_id'],
                'name'          => $val['name'],
                'top'           => $val['top'],
                'image'         => $val['image'],
                'path'          => $new_path,
                'level'         => $level,
                'url'           => $this->url_links[$new_path],
                'children_ids'  => array(),
                'successor_ids' => array()
            );

            if (isset($data[$val['category_id']])) {
                foreach ($data[$val['category_id']] as $child) {
                    $result[$val['category_id']]['children_ids'][] = $child['category_id'];
                }

                $successors = $this->generateCategoriesFlatTree($data, $val, $level, $new_path);
                foreach ($successors as $successor) {
                    $result[$val['category_id']]['successor_ids'][] = (int) $successor['category_id'];
                }
            } else {
                $successors = array();
            }

            $result = $result + $successors;
        }

        return $result;
    }
}