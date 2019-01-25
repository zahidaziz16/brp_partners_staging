<?php

class Theme_SpecialProductsWidget extends AbstractProductsListingWidget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $products = null;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Special Products',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'sort_property'            => 'pd.name',
            'sort_order'               => 'ASC',
            'filter_category'          => 0,
            'filter_category_children' => 0,
            'filter_randomize'         => 0,
            'filter_limit'             => 4
        ), $settings));
    }

    protected function getProductsListing(array $options = array())
    {
        if (null === $this->products) {

            $options = array_replace(array(
                'sort'            => $this->settings['sort_property'],
                'order'           => $this->settings['sort_order'],
                'start'           => 0,
                'limit'           => $this->settings['filter_limit'],
                'randomize'       => $this->settings['filter_randomize'],
                'secondary_image' => false
            ), $options);

            $limit = $options['limit'];

            if (isset($this->settings['filter_category']) && $this->settings['filter_category'] != 0) {
                unset($options['limit'], $options['start']);
            }

            /** @var Theme_Catalog_ProductsModel $productsModel */
            $productsModel = $this->getModel('products');
            $product_ids = $productsModel->getSpecialProductIds($options);

            if (empty($product_ids)) {
                return array();
            }

            $options['start'] = 0;
            $options['limit'] = $limit;
            $options['product_ids'] = $product_ids;

            if ($this->settings['filter_randomize']) {
                unset($options['sort'], $options['order']);
            } else {
                $options['order'] = 'FIELD(p.product_id, ' . implode(',', $product_ids) . ')';
            }

            $this->products = parent::getProductsListing($options);
        }

        return $this->products;
    }
}