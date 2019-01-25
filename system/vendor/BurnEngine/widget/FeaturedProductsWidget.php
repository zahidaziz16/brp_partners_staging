<?php

class Theme_FeaturedProductsWidget extends AbstractProductsListingWidget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');
    protected $products = null;

    public function onFilter(array &$settings)
    {
        $langVars = array(
            'is_active'        => 1,
            'title'            => 'Featured Products',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        );
        $settings = array_replace($settings, $this->initLangVars($langVars, $settings));

        $default_vars = array(
            'sort_property'    => 'pd.name',
            'sort_order'       => 'asc',
            'filter_randomize' => 0,
            'filter_limit'     => 10,
            'product_ids'      => array()
        );
        $settings = array_replace($settings, $this->initFlatVars($default_vars, $settings));
    }

    public function render(array $view_data = array())
    {
        return empty($this->settings['product_ids']) ? '' : parent::render($view_data);
    }

    public function getProductIds()
    {
        return $this->settings['product_ids'];
    }

    protected function getProductsListing(array $options = array())
    {
        if (null === $this->products) {
            if (empty($this->settings['product_ids'])) {
                $this->products = array();

                return array();
            }

            $options = array_replace(
                array(
                    'product_ids'     => $this->settings['product_ids'],
                    'sort'            => $this->settings['sort_property'],
                    'order'           => $this->settings['sort_order'],
                    'start'           => 0,
                    'limit'           => $this->settings['filter_limit'],
                    'secondary_image' => false
                ), $options
            );

            if ($this->settings['filter_randomize']) {
                unset($options['sort'], $options['order']);
            }

            $this->products = parent::getProductsListing($options);
        }

        return $this->products;
    }
}