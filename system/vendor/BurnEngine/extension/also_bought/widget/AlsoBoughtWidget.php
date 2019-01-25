<?php

class Theme_AlsoBoughtWidget extends AbstractProductsListingWidget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $products = null;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'filter_source'    => 'cart',
            'filter_randomize' => 0,
            'filter_limit'     => 4,
            'sort_property'    => 'pd.name',
            'sort_order'       => 'asc'
        ), $settings));

        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Also Bought Products',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));
    }

    public function allowAddToAreaContentCache()
    {
        return false;
    }

    protected function getProductsListing(array $options = array())
    {
        if (null === $this->products) {

            $product_ids = array();

            if ($this->settings['filter_source'] == 'product' && $this->themeData->product_id) {
                $product_ids[] = $this->themeData->product_id;
            } else {
                foreach ($this->engine->getOcCart()->getProducts() as $product) {
                    $product_ids[] = (int) $product['product_id'];
                }
            }

            if (empty($product_ids)) {
                $this->products = array();

                return array();
            }

            $options = array_replace(
                array(
                    'sort'            => $this->settings['sort_property'],
                    'order'           => $this->settings['sort_order'],
                    'start'           => 0,
                    'limit'           => $this->settings['filter_limit'],
                    'randomize'       => $this->settings['filter_randomize'],
                    'secondary_image' => false
                ), $options
            );

            $cache_key = 'also_bought_products.' . md5(implode(',', $product_ids)) . '.' .
                         $this->settings['filter_limit'] . '.' .
                         $this->language_code . '.' .
                         $this->engine->getCurrentStoreId() . '.' .
                         $this->getThemeModel()->getCustomerGroupId();

            if (!$also_bought_product_ids = $this->engine->getCacheVar($cache_key)) {
                /** @var AlsoBought_Catalog_DefaultModel $defaultModel */
                $defaultModel = $this->extension->getModel('default');
                $also_bought_product_ids = $defaultModel->getAlsoBoughtProductsIds($product_ids, $options);

                $this->engine->setCacheVar($cache_key, $also_bought_product_ids, 240*60);
            }

            if (empty($also_bought_product_ids)) {
                return array();
            }

            if ($this->settings['filter_randomize']) {
                unset($options['sort'], $options['order']);
            }

            $options['product_ids'] = $also_bought_product_ids;

            $this->products = parent::getProductsListing($options);
        }

        return $this->products;
    }
}