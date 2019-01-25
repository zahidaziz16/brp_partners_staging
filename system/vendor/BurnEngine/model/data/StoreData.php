<?php

class StoreData
{
    public static function getSubcategoryListSettings(Config $config)
    {
        $config_group = version_compare(VERSION, '2.2.0.0', '>=') ? TB_Engine::getName() : 'config';

        return array(
            'style'          => 1,
            'text_align'     => 'center',
            'image_position' => 1,
            'image_width'    => $config->get($config_group . '_image_category_width'),
            'image_height'   => $config->get($config_group . '_image_category_height'),
            'restrictions'   => array(
                0 => array(
                    'max_width'     => 1200,
                    'items_per_row' => 8,
                    'items_spacing' => 30
                ),
                1 => array(
                    'max_width'     => 1050,
                    'items_per_row' => 7,
                    'items_spacing' => 30
                ),
                2 => array(
                    'max_width'     => 900,
                    'items_per_row' => 6,
                    'items_spacing' => 30
                ),
                3 => array(
                    'max_width'     => 750,
                    'items_per_row' => 5,
                    'items_spacing' => 30
                ),
                4 => array(
                    'max_width'     => 500,
                    'items_per_row' => 4,
                    'items_spacing' => 30
                ),
                5 => array(
                    'max_width'     => 400,
                    'items_per_row' => 3,
                    'items_spacing' => 30
                ),
                6 => array(
                    'max_width'     => 300,
                    'items_per_row' => 2,
                    'items_spacing' => 30
                )
            ),
            'product_count'      => 1,
            'is_slider'          => 0,
            'show_next_level'    => 0
        );
    }

    public static function getProductListSettings(Config $config, $list_type = null)
    {
        $common = array(
            'listing_layout'            => 'default',
            'products_style'            => 1,
            'product_inner_padding'     => 20,
            'exclude_thumbnail'         => 1,
            'cart_button_type'          => 'button',
            'cart_button_size'          => 30,
            'cart_button_icon'          => 'fa-shopping-cart',
            'cart_button_icon_size'     => 16,
            'cart_button_position'      => 1,
            'cart_button_offset'        => '',
            'cart_button_hover'         => 0,
            'compare_button_type'       => 'plain',
            'compare_button_size'       => 30,
            'compare_button_icon'       => 'fa-retweet',
            'compare_button_icon_size'  => 10,
            'compare_button_position'   => 1,
            'compare_button_offset'     => '',
            'compare_button_hover'      => 0,
            'wishlist_button_type'      => 'plain',
            'wishlist_button_size'      => 30,
            'wishlist_button_icon'      => 'fa-heart',
            'wishlist_button_icon_size' => 10,
            'wishlist_button_position'  => 1,
            'wishlist_button_offset'    => '',
            'wishlist_button_hover'     => 0,
            'quickview_button_type'     => 'plain',
            'quickview_button_size'     => 30,
            'quickview_button_icon'     => 'fa-search',
            'quickview_button_icon_size'=> 10,
            'quickview_button_position' => 1,
            'quickview_button_offset'   => '',
            'quickview_button_hover'    => 0,
            'elements_hover_action'     => 'append',
            'thumbs_hover_action'       => 'none',
            'price_design'              => 'plain',
            'price_size'                => 2,
            'hover_fade'                => 0
        );

        $config_group = version_compare(VERSION, '2.2.0.0', '>=') ? TB_Engine::getName() : 'config';

        $grid = array(
            'thumb_default'       => 1,
            'title_default'       => 1,
            'description_default' => 0,
            'price_default'       => 1,
            'tax_default'         => 0,
            'counter_default'     => 0,
            'cart_default'        => 0,
            'compare_default'     => 0,
            'wishlist_default'    => 0,
            'quickview_default'   => 0,
            'rating_default'      => 0,
            'label_sale_default'  => 1,
            'label_new_default'   => 1,
            'stock_default'       => 0,
            'image_width'         => $config->get($config_group . '_image_product_width'),
            'image_height'        => $config->get($config_group . '_image_product_height'),
            'description_limit'   => 250,
            'restrictions'        => array(
                0 => array(
                    'max_width'     => 1200,
                    'items_per_row' => 5,
                    'items_spacing' => 30
                ),
                1 => array(
                    'max_width'     => 900,
                    'items_per_row' => 4,
                    'items_spacing' => 30
                ),
                2 => array(
                    'max_width'     => 750,
                    'items_per_row' => 3,
                    'items_spacing' => 30
                ),
                3 => array(
                    'max_width'     => 450,
                    'items_per_row' => 2,
                    'items_spacing' => 30
                ),
                4 => array(
                    'max_width'     => 300,
                    'items_per_row' => 1,
                    'items_spacing' => 30
                )
            ),
            'thumb_hover'       => 1,
            'title_hover'       => 1,
            'description_hover' => 0,
            'price_hover'       => 1,
            'tax_hover'         => 1,
            'counter_hover'     => 0,
            'cart_hover'        => 1,
            'compare_hover'     => 1,
            'wishlist_hover'    => 1,
            'quickview_hover'   => 1,
            'rating_hover'      => 1,
            'label_sale_hover'  => 1,
            'label_new_hover'   => 1,
            'stock_hover'       => 1
        );

        $list = array(
            'cart_button_size'    => 30,
            'thumb_default'       => 1,
            'title_default'       => 1,
            'description_default' => 1,
            'price_default'       => 1,
            'tax_default'         => 1,
            'counter_default'     => 0,
            'cart_default'        => 1,
            'compare_default'     => 1,
            'wishlist_default'    => 1,
            'quickview_default'   => 1,
            'rating_default'      => 1,
            'label_sale_default'  => 1,
            'label_new_default'   => 1,
            'stock_default'       => 1,
            'image_width'         => $config->get($config_group . '_image_product_width'),
            'image_height'        => $config->get($config_group . '_image_product_height'),
            'description_limit'   => 250,
            'restrictions'        => array(
                0 => array(
                    'max_width'     => 1200,
                    'items_per_row' => 1,
                    'items_spacing' => 30
                )
            )
        );

        $compact = array(
            'thumb_default'       => 1,
            'title_default'       => 1,
            'description_default' => 0,
            'price_default'       => 1,
            'tax_default'         => 0,
            'counter_default'     => 0,
            'cart_default'        => 0,
            'compare_default'     => 0,
            'wishlist_default'    => 0,
            'quickview_default'   => 0,
            'rating_default'      => 1,
            'label_sale_default'  => 0,
            'label_new_default'   => 0,
            'stock_default'       => 0,
            'image_width'         => $config->get($config_group . '_image_product_width'),
            'image_height'        => $config->get($config_group . '_image_product_height'),
            'description_limit'   => 250
        );

        if (null == $list_type) {
            return array(
                'grid'    => $common + $grid,
                'list'    => $common + $list,
                'compact' => $common + $compact
            );
        } else {
            return $common + $$list_type;
        }
    }
}