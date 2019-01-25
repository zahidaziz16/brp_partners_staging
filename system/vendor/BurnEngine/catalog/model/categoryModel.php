<?php

require_once dirname(__FILE__) . '/../../model/categoryModel.php';

class Theme_Catalog_CategoryModel extends Theme_CategoryModel
{
    public function getCategorySuccessorIds($category_id)
    {
        $category = $this->getCategoryFromFlatTree($category_id);
        if (false === $category) {
            return array();
        }

        return $category['successor_ids'];
    }

    public function initCategoryProductsSettings(&$products, $view_mode)
    {
        $themeData = $this->engine->getThemeData();

        $restrictions_json = array();
        if (isset($products['restrictions'])) {
            foreach ($products['restrictions'] as $restriction) {
                $restrictions_json[$restriction['max_width']] = array(
                    'items_per_row' => $restriction['items_per_row'],
                    'items_spacing' => $restriction['items_spacing']
                );
            }
            krsort($restrictions_json);
        }
        else {
            $restrictions_json['1200'] = array(
                'items_per_row' => 1,
                'items_spacing' => 30
            );
        }
        $products['restrictions_json'] = json_encode($restrictions_json);

        $products['listing_classes']  = 'tb_'        . $view_mode . '_view';
        $products['listing_classes'] .= $view_mode == 'list' ? ' tb_gut_30' : '';
        $products['listing_classes'] .= ' tb_style_' . $products['products_style'];

        if($products['products_style'] != 'plain' || ($products['products_style'] == 'plain' && $products['elements_hover_action'] != 'none')) {
            $products['listing_classes']  .= ' tb_product_p_' . $products['product_inner_padding'];
        }
        if($products['exclude_thumbnail']) {
            $products['listing_classes']  .= ' tb_exclude_thumb';
        }

        $products['price_classes']  = ' tb_size_' . $products['price_size'];
        $products['price_classes'] .= ' tb_'     . $products['price_design'];

        $products['show_thumb']       = $products['thumb_default']       || (isset($products['thumb_hover'])       && $products['thumb_hover']);
        $products['show_title']       = $products['title_default']       || (isset($products['title_hover'])       && $products['title_hover']);
        $products['show_description'] = $products['description_default'] || (isset($products['description_hover']) && $products['description_hover']);
        $products['show_price']       = $products['price_default']       || (isset($products['price_hover'])       && $products['price_hover']);
        $products['show_tax']         = $products['tax_default']         || (isset($products['tax_hover'])         && $products['tax_hover']);
        $products['show_counter']     = (isset($products['counter_default'])   && $products['counter_default'])   || (isset($products['counter_hover'])   && $products['counter_hover']);
        $products['show_quickview']   = (isset($products['quickview_default']) && $products['quickview_default']) || (isset($products['quickview_hover']) && $products['quickview_hover']);
        $products['show_cart']        = $products['cart_default']        || (isset($products['cart_hover'])        && $products['cart_hover']);
        $products['show_compare']     = $products['compare_default']     || (isset($products['compare_hover'])     && $products['compare_hover']);
        $products['show_wishlist']    = $products['wishlist_default']    || (isset($products['wishlist_hover'])    && $products['wishlist_hover']);
        $products['show_rating']      = $products['rating_default']      || (isset($products['rating_hover'])      && $products['rating_hover']);
        $products['show_label_sale']  = $products['label_sale_default']  || (isset($products['label_sale_hover'])  && $products['label_sale_hover']);
        $products['show_label_new']   = $products['label_new_default']   || (isset($products['label_new_hover'])   && $products['label_new_hover']);
        $products['show_stock']       = $products['stock_default']       || (isset($products['stock_hover'])       && $products['stock_hover']);

        if (empty($themeData->common['checkout_enabled'])) $products['show_cart']     = false;
        if (empty($themeData->common['compare_enabled']))  $products['show_compare']  = false;
        if (empty($themeData->common['wishlist_enabled'])) $products['show_wishlist'] = false;

        if ($this->engine->getOcConfig('config_customer_price') && !$this->engine->getOcCustomer()->isLogged()) {
            $products['show_price'] = false;
        }

        // Cart button classes
        $products['cart_button_classes']          = '';
        $products['cart_button_position_classes'] = '';
        $products['cart_button_offset_attr']      = '';

        if ($products['cart_button_type'] == 'button') {
            $products['cart_button_classes'] = 'btn btn-' . $products['cart_button_size'];
        }
        if ($products['cart_button_type'] == 'icon_button' && $view_mode != 'list') {
            $products['cart_button_classes'] = 'btn btn-' . $products['cart_button_size'];
        }
        if ($products['cart_button_type'] == 'box') {
            $products['cart_button_classes'] = 'btn btn-plain btn-' . $products['cart_button_size'];
        }
        if ($products['cart_button_type'] == 'icon_box' && $view_mode != 'list') {
            $products['cart_button_classes'] = 'btn btn-plain btn-' . $products['cart_button_size'];
        }
        if ($view_mode != 'list'
            && ($products['cart_button_type'] == 'icon_button'
                || $products['cart_button_type'] == 'icon_box'
                || $products['cart_button_type'] == 'icon_plain')
        ) {
            $products['cart_button_classes'] .= ' tb_no_text';
        }
        if ($products['cart_button_icon']) {
            $products['cart_button_classes'] .= ' tb_icon_' . $products['cart_button_icon_size'] . ' ' . $products['cart_button_icon'];
        }
        if ($products['cart_button_position'] != 'default' && $view_mode != 'list' && $view_mode != 'compact') {
            $products['cart_button_position_classes'] .= $products['cart_button_position'] != 'default' ? ' tb_pos_' . $products['cart_button_position'] : '';
            $products['cart_button_position_classes'] .= $products['cart_button_position'] != 'inline' && $products['cart_button_hover'] ? ' tb_hidden' : '';
            $products['cart_button_position_classes'] .= $products['cart_button_type'] == 'button' ? ' btn-' . $products['cart_button_size'] : '';
            $products['cart_button_position_classes'] .= $products['cart_button_type'] == 'icon_button' ? ' btn-' . $products['cart_button_size'] . ' tb_no_text' : '';
            $products['cart_button_position_classes'] .= $products['cart_button_type'] == 'box' ? ' btn-' . $products['cart_button_size'] : '';
            $products['cart_button_position_classes'] .= $products['cart_button_type'] == 'icon_box' ? ' btn-' . $products['cart_button_size'] . ' tb_no_text' : '';
            $products['cart_button_offset_attr']      .= $products['cart_button_offset'] ? ' style="margin: ' . $products['cart_button_offset'] . ';"' : '';
        }

        // Compare button classes
        $products['compare_button_classes']          = '';
        $products['compare_button_position_classes'] = '';
        $products['compare_button_offset_attr']      = '';

        if ($products['compare_button_type'] == 'button' && $view_mode != 'list') {
            $products['compare_button_classes'] = 'btn btn-' . $products['compare_button_size'];
        }
        if ($products['compare_button_type'] == 'icon_button' && $view_mode != 'list') {
            $products['compare_button_classes'] = 'btn btn-' . $products['compare_button_size'];
        }
        if ($products['compare_button_type'] == 'box' && $view_mode != 'list') {
            $products['compare_button_classes'] = 'btn btn-plain btn-' . $products['compare_button_size'];
        }
        if ($products['compare_button_type'] == 'icon_box' && $view_mode != 'list') {
            $products['compare_button_classes'] = 'btn btn-plain btn-' . $products['compare_button_size'];
        }
        if ($view_mode != 'list'
            && ($products['compare_button_type'] == 'icon_button'
                || $products['compare_button_type'] == 'icon_box'
                || $products['compare_button_type'] == 'icon_plain')
        ) {
            $products['compare_button_classes'] .= ' tb_no_text';
        }
        if ($view_mode == 'list') {
            $products['compare_button_icon_size'] = 10;
        }
        if ($products['compare_button_icon']) {
            $products['compare_button_classes'] .= ' tb_icon_' . $products['compare_button_icon_size'] . ' ' . $products['compare_button_icon'];
        }
        if ($products['compare_button_position'] != 'default' && $view_mode != 'list' && $view_mode != 'compact') {
            $products['compare_button_position_classes'] .= $products['compare_button_position'] != 'default' ? ' tb_pos_' . $products['compare_button_position'] : '';
            $products['compare_button_position_classes'] .= $products['compare_button_position'] != 'inline' && $products['compare_button_hover'] ? ' tb_hidden' : '';
            $products['compare_button_position_classes'] .= $products['compare_button_type'] == 'button' ? ' btn-' . $products['compare_button_size'] : '';
            $products['compare_button_position_classes'] .= $products['compare_button_type'] == 'icon_button' ? ' btn-' . $products['compare_button_size'] . ' tb_no_text' : '';
            $products['compare_button_position_classes'] .= $products['compare_button_type'] == 'box' ? ' btn-' . $products['compare_button_size'] : '';
            $products['compare_button_position_classes'] .= $products['compare_button_type'] == 'icon_box' ? ' btn-' . $products['compare_button_size'] . ' tb_no_text' : '';
            $products['compare_button_offset_attr']      .= $products['compare_button_offset'] ? ' style="margin: ' . $products['compare_button_offset'] . ';"' : '';
        }

        // Wishlist button classes
        $products['wishlist_button_classes']          = '';
        $products['wishlist_button_position_classes'] = '';
        $products['wishlist_button_offset_attr']      = '';

        if ($products['wishlist_button_type'] == 'button' && $view_mode != 'list') {
            $products['wishlist_button_classes'] = 'btn btn-' . $products['wishlist_button_size'];
        }
        if ($products['wishlist_button_type'] == 'icon_button' && $view_mode != 'list') {
            $products['wishlist_button_classes'] = 'btn btn-' . $products['wishlist_button_size'] . ' tb_no_text';
        }
        if ($products['wishlist_button_type'] == 'box' && $view_mode != 'list') {
            $products['wishlist_button_classes'] = 'btn btn-plain btn-' . $products['wishlist_button_size'];
        }
        if ($products['wishlist_button_type'] == 'icon_box' && $view_mode != 'list') {
            $products['wishlist_button_classes'] = 'btn btn-plain btn-' . $products['wishlist_button_size'] . ' tb_no_text';
        }
        if ($view_mode != 'list'
            && ($products['wishlist_button_type'] == 'icon_button'
                || $products['wishlist_button_type'] == 'icon_box'
                || $products['wishlist_button_type'] == 'icon_plain')
        ) {
            $products['wishlist_button_classes'] .= ' tb_no_text';
        }
        if ($view_mode == 'list') {
            $products['wishlist_button_icon_size'] = 10;
        }
        if ($products['wishlist_button_icon']) {
            $products['wishlist_button_classes'] .= ' tb_icon_' . $products['wishlist_button_icon_size'] . ' ' . $products['wishlist_button_icon'];
        }
        if ($products['wishlist_button_position'] != 'default' && $view_mode != 'list' && $view_mode != 'compact') {
            $products['wishlist_button_position_classes'] .= $products['wishlist_button_position'] != 'default' ? ' tb_pos_' . $products['wishlist_button_position'] : '';
            $products['wishlist_button_position_classes'] .= $products['wishlist_button_position'] != 'inline' && $products['wishlist_button_hover'] ? ' tb_hidden' : '';
            $products['wishlist_button_position_classes'] .= $products['wishlist_button_type'] == 'button' ? ' btn-' . $products['wishlist_button_size'] : '';
            $products['wishlist_button_position_classes'] .= $products['wishlist_button_type'] == 'icon_button' ? ' btn-' . $products['wishlist_button_size'] . ' tb_no_text' : '';
            $products['wishlist_button_position_classes'] .= $products['wishlist_button_type'] == 'box' ? ' btn-' . $products['wishlist_button_size'] : '';
            $products['wishlist_button_position_classes'] .= $products['wishlist_button_type'] == 'icon_box' ? ' btn-' . $products['wishlist_button_size'] . ' tb_no_text' : '';
            $products['wishlist_button_offset_attr']      .= $products['wishlist_button_offset'] ? ' style="margin: ' . $products['wishlist_button_offset'] . ';"' : '';
        }

        // Quickview button classes
        $products['quickview_button_classes']          = '';
        $products['quickview_button_position_classes'] = '';
        $products['quickview_button_offset_attr']      = '';
        isset($products['quickview_button_type'])      || $products['quickview_button_type']      = 'plain';
        isset($products['quickview_button_size'])      || $products['quickview_button_size']      = 'md';
        isset($products['quickview_button_icon'])      || $products['quickview_button_icon']      = 'fa-search';
        isset($products['quickview_button_icon_size']) || $products['quickview_button_icon_size'] = 10;
        isset($products['quickview_button_position'])  || $products['quickview_button_position']  = 'default';
        isset($products['quickview_button_offset'])    || $products['quickview_button_offset']    = '';
        isset($products['quickview_button_hover'])     || $products['quickview_button_hover']     = 0;

        if ($products['quickview_button_type'] == 'button' && $view_mode != 'list') {
            $products['quickview_button_classes'] = 'btn btn-' . $products['quickview_button_size'];
        }
        if ($products['quickview_button_type'] == 'icon_button' && $view_mode != 'list') {
            $products['quickview_button_classes'] = 'btn btn-' . $products['quickview_button_size'] . ' tb_no_text';
        }
        if ($products['quickview_button_type'] == 'box' && $view_mode != 'list') {
            $products['quickview_button_classes'] = 'btn btn-plain btn-' . $products['quickview_button_size'];
        }
        if ($products['quickview_button_type'] == 'icon_box' && $view_mode != 'list') {
            $products['quickview_button_classes'] = 'btn btn-plain btn-' . $products['quickview_button_size'] . ' tb_no_text';
        }
        if ($view_mode != 'list'
            && ($products['quickview_button_type'] == 'icon_button'
                || $products['quickview_button_type'] == 'icon_box'
                || $products['quickview_button_type'] == 'icon_plain')
        ) {
            $products['quickview_button_classes'] .= ' tb_no_text';
        }
        if ($view_mode == 'list') {
            $products['quickview_button_icon_size'] = 10;
        }
        if ($products['quickview_button_icon']) {
            $products['quickview_button_classes'] .= ' tb_icon_' . $products['quickview_button_icon_size'] . ' ' . $products['quickview_button_icon'];
        }
        if ($products['quickview_button_position'] != 'default' && $view_mode != 'list' && $view_mode != 'compact') {
            $products['quickview_button_position_classes'] .= $products['quickview_button_position'] != 'default' ? ' tb_pos_' . $products['quickview_button_position'] : '';
            $products['quickview_button_position_classes'] .= $products['quickview_button_position'] != 'inline' && $products['quickview_button_hover'] ? ' tb_hidden' : '';
            $products['quickview_button_position_classes'] .= $products['quickview_button_type'] == 'button' ? ' btn-' . $products['quickview_button_size'] : '';
            $products['quickview_button_position_classes'] .= $products['quickview_button_type'] == 'icon_button' ? ' btn-' . $products['quickview_button_size'] . ' tb_no_text' : '';
            $products['quickview_button_position_classes'] .= $products['quickview_button_type'] == 'box' ? ' btn-' . $products['quickview_button_size'] : '';
            $products['quickview_button_position_classes'] .= $products['quickview_button_type'] == 'icon_box' ? ' btn-' . $products['quickview_button_size'] . ' tb_no_text' : '';
            $products['quickview_button_offset_attr']      .= $products['quickview_button_offset'] ? ' style="margin: ' . $products['quickview_button_offset'] . ';"' : '';
        }

        if ($products['cart_button_position'] != 'default' && $products['cart_button_position'] != 'inline'
            && $products['compare_button_position'] != 'default' && $products['compare_button_position'] != 'inline'
            && $products['wishlist_button_position'] != 'default' && $products['wishlist_button_position'] != 'inline'
            && $products['quickview_button_position'] != 'default' && $products['quickview_button_position'] != 'inline'
            && $view_mode != 'list' && $view_mode != 'compact')
        {
            $products['listing_classes'] .= ' tb_buttons_2';
        }
        else {
            $products['listing_classes'] .= ' tb_buttons_1';

            if ($view_mode != 'list' && $view_mode != 'compact') {
                $products['listing_classes'] .= ' tb_buttons_config';
                !$products['show_cart']      || $products['cart_button_position']      != 'b' || $products['listing_classes'] .= '--cart_b';
                !$products['show_compare']   || $products['compare_button_position']   != 'b' || $products['listing_classes'] .= '--compare_b';
                !$products['show_wishlist']  || $products['wishlist_button_position']  != 'b' || $products['listing_classes'] .= '--wishlist_b';
                !$products['show_quickview'] || $products['quickview_button_position'] != 'b' || $products['listing_classes'] .= '--quickview_b';
            }
        }

        // Has description
        if (isset($products['description_default']) && $products['description_default']) {
            $products['listing_classes'] .= ' tb_has_active_description';
        }
        if (isset($products['description_hover']) && $products['description_hover']) {
            $products['listing_classes'] .= ' tb_has_hover_description';
        }

        // Active & Hover elements
        $products['active_elements'] = '';
        $products['hover_elements']  = '';

        $options = array(
            'thumb'       => '.image',
            'title'       => '.name, h4',
            'description' => '.description, p:not([class])',
            'price'       => '.price',
            'tax'         => '.price-tax',
            'counter'     => '.tb_counter',
            'cart'        => '.tb_button_add_to_cart',
            'compare'     => '.tb_button_compare',
            'wishlist'    => '.tb_button_wishlist',
            'quickview'   => '.tb_button_quickview',
            'rating'      => '.rating',
            'label_sale'  => '.tb_label_special',
            'label_new'   => '.tb_label_new',
            'stock'       => '.tb_label_stock_status'
        );

        foreach ($options as $option => $element) {
            if(empty($products[$option . '_default']) && isset($products[$option . '_hover']) && $products[$option . '_hover']) {
                $products['active_elements'] .= $element . ', ';
            }
            if(!empty($products[$option . '_default']) && (!isset($products[$option . '_hover']) || !$products[$option . '_hover'])) {
                $products['hover_elements'] .= $element . ', ';
            }
        }

        $products['active_elements'] = trim($products['active_elements'], ', ');
        $products['hover_elements']  = trim($products['hover_elements'], ', ');
    }
}