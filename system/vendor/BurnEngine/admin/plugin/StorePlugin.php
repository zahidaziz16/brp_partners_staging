<?php

require_once TB_THEME_ROOT . '/model/data/StoreData.php';

class Theme_Admin_StorePlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'store';
    }

    public function filterSettings(array &$store_settings)
    {
        $default_settings = array(
            'common' => array(
                'preorder_stock_status_id'          => 0,
                'backorder_stock_status_id'         => 0,
                'disable_checkout_stock_status_id'  => 0,
                'product_listing_view_mode'         => 'grid',
                'product_listing_items_per_page'    => 15,
                'product_listing_description_limit' => 100

            ),
            'category' => array()
        );

        $store_settings = TB_FormHelper::initFlatVarsSimple($default_settings, $store_settings);

        $store_settings['common'] = TB_FormHelper::initFlatVarsSimple($default_settings['common'], $store_settings['common']);

        if  (!isset($store_settings['category'])) {
            $store_settings['category'] = array();
        }

        $default_settings = array(
            'subcategories' => StoreData::getSubcategoryListSettings($this->engine->getOcConfig()),
            'products'      => StoreData::getProductListSettings($this->engine->getOcConfig())
        );

        $cs = &$store_settings['category'];
        $cs = TB_FormHelper::initFlatVarsSimple($default_settings, $cs);
        $cs['subcategories'] = TB_FormHelper::initFlatVarsSimple($default_settings['subcategories'], $cs['subcategories']);
        $cs['products'] = TB_FormHelper::initFlatVarsSimple($default_settings['products'], $cs['products']);
        $cs['products']['grid'] = TB_FormHelper::initFlatVarsSimple($default_settings['products']['grid'], $cs['products']['grid']);
        $cs['products']['list'] = TB_FormHelper::initFlatVarsSimple($default_settings['products']['list'], $cs['products']['list']);
        $cs['products']['compact'] = TB_FormHelper::initFlatVarsSimple($default_settings['products']['compact'], $cs['products']['compact']);
    }

    public function setDataForView(&$store_settings, TB_ViewDataBag $themeData)
    {
        $themeData->store_category_default = $store_settings['category'];
        $themeData->stock_statuses = $this->getOcModel('localisation/stock_status')->getStockStatuses();
    }

    public function saveData($post_data, $theme_settings)
    {
        $store_post = $post_data[$this->getConfigKey()];
        $store_db = $this->getThemeModel()->getSetting('store', array());

        if (isset($store_post['category'])) {
            $store_db['category'] = (array) $store_post['category'];
            $view_mode = $store_post['common']['product_listing_view_mode'];

            $config_group = $this->engine->gteOc22() ? $this->engine->getConfigTheme() : 'config';
            $this->engine->getDbSettingsHelper('setting')->persistGroup('config', array(
                $config_group . '_image_category_width'  => $store_db['category']['subcategories']['image_width'],
                $config_group . '_image_category_height' => $store_db['category']['subcategories']['image_height'],
                $config_group . '_image_product_width'   => $store_db['category']['products'][$view_mode]['image_width'],
                $config_group . '_image_product_height'  => $store_db['category']['products'][$view_mode]['image_height']
            ), $this->context->getStoreId());

            if (isset($store_db['category']['products']['grid']['restrictions'])) {
                foreach ($store_db['category']['products']['grid']['restrictions'] as $i => &$row) {
                    settype($row['max_width'], 'integer');
                    settype($row['items_per_row'], 'integer');
                    settype($row['items_spacing'], 'integer');

                    if (empty($row['max_width']) || empty($row['items_per_row'])) {
                        unset($store_db['category']['products']['grid']['restrictions'][$i]);
                    }
                }
                if (empty($store_db['category']['products']['grid']['restrictions'])) {
                    $store_db['category']['products']['grid']['restrictions'] = $theme_settings['store']['category']['products']['grid']['restrictions'];
                }
            }

            if (isset($store_db['category']['subcategories']['restrictions'])) {
                foreach ($store_db['category']['subcategories']['restrictions'] as $i => &$row) {
                    settype($row['max_width'], 'integer');
                    settype($row['items_per_row'], 'integer');
                    settype($row['items_spacing'], 'integer');

                    if (empty($row['max_width']) || empty($row['items_per_row'])) {
                        unset($store_db['category']['subcategories']['restrictions'][$i]);
                    }
                }

                if (empty($store_db['category']['subcategories']['restrictions'])) {
                    $store_db['category']['subcategories']['restrictions'] = $theme_settings['store']['category']['subcategories']['restrictions'];
                }
            }
        }

        if (isset($store_post['common'])) {
            $store_db['common'] = $store_post['common'];

            if ($this->engine->gteOc22()) {
                $this->engine->getDbSettingsHelper('setting')->persistGroup(TB_Engine::getName(), array(
                    TB_Engine::getName() . '_product_limit'              => $store_db['common']['product_listing_items_per_page'],
                    TB_Engine::getName() . '_product_description_length' => $store_db['common']['product_listing_description_limit']
                ), $this->context->getStoreId());
            }
        }

        return array(
            $this->getConfigKey() => $store_db
        );
    }
}