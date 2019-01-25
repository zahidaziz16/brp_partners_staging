<?php

class Theme_RandomProductsWidget extends AbstractProductsListingWidget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $products = null;

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Random Products',
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
                    'sort'            => 'RAND()',
                    'random_query'    => true,
                    'start'           => 0,
                    'limit'           => $this->settings['filter_limit'],
                    'secondary_image' => false
                );
            }

            $this->settings['filter_randomize'] = 1;

            $this->products = parent::getProductsListing($options);
        }

        return $this->products;
    }
}