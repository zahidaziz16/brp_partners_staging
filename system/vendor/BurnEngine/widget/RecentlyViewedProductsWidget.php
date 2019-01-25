<?php

class Theme_RecentlyViewedProductsWidget extends AbstractProductsListingWidget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $products = null;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'filter_limit' => 4
        ), $settings));

        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Recently Viewed Products',
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
        if (null === $this->products && isset($this->engine->getOcSession()->data['recently_viewed_product_ids'])) {
            $product_ids = $this->engine->getOcSession()->data['recently_viewed_product_ids'];

            if (empty($product_ids)) {
                $this->products = array();

                return array();
            }

            $product_ids = array_reverse($product_ids);
            $product_ids = array_splice($product_ids, 0, $this->settings['filter_limit']);

            if (empty($options)) {
                $options = array(
                    'product_ids'     => $product_ids,
                    'order'           => 'FIELD (p.product_id, ' . implode(',', $product_ids) . ')',
                    'secondary_image' => false
                );
            }

            $this->products = parent::getProductsListing($options);
        }

        return $this->products;
    }
}