<?php

class Theme_BestsellerProductsWidget extends AbstractProductsListingWidget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $products = null;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Best Sellers',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'filter_category'          => 0,
            'filter_category_children' => 0,
            'filter_limit'             => 4
        ), $settings));
    }

    protected function getProductsListing(array $options = array())
    {
        if (null === $this->products) {
            if (empty($options)) {
                $options = array(
                    'start'           => 0,
                    'limit'           => $this->settings['filter_limit'],
                    'secondary_image' => false
                );
            }

            $limit = $options['limit'];

            if (isset($this->settings['filter_category']) && $this->settings['filter_category'] != 0) {
                unset($options['limit'], $options['start']);
            }

            /** @var Theme_Catalog_ProductsModel $productsModel */
            $productsModel = $this->getModel('products');
            $product_ids = $productsModel->getBestsellerProductIds($limit);

            if (empty($product_ids)) {
                return array();
            }

            $options['start'] = 0;
            $options['limit'] = $limit;
            $options['product_ids'] = $product_ids;

            if ($product_ids) {
                $options['order'] = 'FIELD(p.product_id, ' . implode(',', $product_ids) . ')';
            }

            $this->products = parent::getProductsListing($options);
        }

        return $this->products;
    }
}