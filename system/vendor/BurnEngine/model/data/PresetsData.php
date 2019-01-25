<?php

require_once dirname(__FILE__) . '/MainFontsData.php';

class PresetsData
{
    public static function getColors($style_section_id)
    {
        return call_user_func(__CLASS__.'::get' . ucfirst(TB_Utils::camelize($style_section_id)) . 'Colors');
    }

    public static function getFonts($style_section_id)
    {
        $fonts = call_user_func(__CLASS__.'::get' . ucfirst($style_section_id) . 'Fonts');
        foreach ($fonts as $key => $value) {
            $fonts[$key] = array_merge(MainFontsData::getDefaultFontItem(), $value);
        }

        return $fonts;
    }

    /* ------------------------------------------------------
       Define groups
    ------------------------------------------------------ */

    public static function getBoxColorGroups()
    {
        return array(
            'body'          => 'Common',
            'table'         => 'Table',
            'forms'         => 'Form',
            'buttons'       => 'Buttons',
            'dropdown_menu' => 'Dropdown menu',
            'separator'     => 'Separator',
            'banner'        => 'Banner',
            'pagination'    => 'Pagination',
            'products'      => 'Products',
            'ui_tabs'       => 'Tabs',
            'ui_accordion'  => 'Accordion',
            'gallery'       => 'Gallery',
            'carousel'      => 'Carousel'
        );
    }

    public static function getBoxFontGroups()
    {
        return array(
            'body'      => 'Common',
            'product'   => 'Product',
            'separator' => 'Separator',
            'banner'    => 'Banner'
        );
    }

    /* ------------------------------------------------------
       Define colors / fonts
    ------------------------------------------------------ */

    protected static function getBodyColors()
    {
        $default_colors = array(
            'body' => MainColorsData::getColors('main')
        );

        $preset_colors  = array(
            'body' => array(
                '_label' => 'Body',
                'accent' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.accent'
                ),
                'accent_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.accent_hover'
                ),
                'accent_bg' => array(
                    'hidden'      => 1,
                    'inherit_key' => 'body.accent'
                ),
                'accent_bg_hover' => array(
                    'hidden'      => 1,
                    'inherit_key' => 'body.accent_hover'
                ),
                'text' => array(
                    'elements'    => '',
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text'
                ),
                'links' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.links'
                ),
                'links_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.links_hover'
                ),
                'text_links' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text_links'
                ),
                'text_links_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text_links_hover'
                ),
                'titles' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.titles'
                ),
                'column_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.column_border'
                ),
                'subtle_base' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.subtle_base'
                ),
            )
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getTableColors()
    {
        $default_colors = array (
            'table__thead' => MainColorsData::getColors('tables_thead'),
            'table__tbody' => MainColorsData::getColors('tables_tbody'),
        );

        $preset_colors  = array(
            'table__thead' => array(
                '_label' => 'Table head',
                'th_text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_thead.th_text'
                ),
                'th_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_thead.th_bg'
                ),
                'th_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_thead.th_border'
                )
            ),
            'table__tbody' => array(
                '_label' => 'Table body / footer',
                'td_text' => array(
                    'inherit_key' => 'theme:main.text'
                ),
                'td_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_bg'
                ),
                'td_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_border'
                ),
                'td_bg_zebra' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_bg_zebra'
                ),
                'td_bg_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:tables_tbody.td_bg_hover'
                )
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getFormsColors()
    {
        return array (
            'forms'   => MainColorsData::getColors('forms')
        );
    }

    protected static function getButtonsColors()
    {
        $default_colors = array (
            'buttons' => MainColorsData::getColors('buttons')
        );

        $preset_colors  = array(
            'buttons' => array(
                '_label' => 'Buttons',
                'button' => array(
                    'inherit_key' => 'theme:buttons.button'
                ),
                'button_hover' => array(
                    'inherit_key' => 'theme:buttons.button_hover'
                ),
                'button_default_hover' => array(
                    'inherit_key' => 'theme:buttons.button_default_hover'
                )
            )
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getDropdownMenuColors()
    {
        $default_colors = array (
            'dropdown_menu' => MainColorsData::getColors('dropdown_menu')
        );

        $preset_colors  = array(
            'dropdown_menu' => array(
                '_label' => 'Dropdown Menu',
                'accent' => array(
                    'inherit_key' => 'theme:dropdown_menu.accent'
                ),
                'accent_hover' => array(
                    'inherit_key' => 'theme:dropdown_menu.accent_hover'
                ),
                'accent_bg' => array(
                    'hidden'      => '1'
                ),
                'accent_bg_hover' => array(
                    'hidden'      => 1,
                ),
                'text' => array(
                    'inherit_key' => 'theme:dropdown_menu.text'
                ),
                'links' => array(
                    'inherit_key' => 'theme:dropdown_menu.links'
                ),
                'links_hover' => array(
                    'inherit_key' => 'theme:dropdown_menu.links_hover'
                ),
                'text_links' => array(
                    'inherit_key' => 'theme:dropdown_menu.text_links'
                ),
                'text_links_hover' => array(
                    'inherit_key' => 'theme:dropdown_menu.text_links_hover'
                ),
                'titles' => array(
                    'inherit_key' => 'theme:dropdown_menu.titles'
                ),
                'titles_hover' => array(
                    'inherit_key' => 'theme:dropdown_menu.titles_hover'
                ),
                'bullets' => array(
                    'inherit_key' => 'theme:dropdown_menu.bullets'
                ),
                'header' => array(
                    'inherit_key' => 'theme:dropdown_menu.header'
                ),
                'divider' => array(
                    'inherit_key' => 'theme:dropdown_menu.divider'
                ),
                'column_border' => array(
                    'inherit_key' => 'theme:dropdown_menu.column_border'
                ),
                'subtle_base' => array(
                    'inherit_key' => 'theme:dropdown_menu.subtle_base'
                ),
                'bg' => array(
                    'inherit_key' => 'theme:dropdown_menu.bg'
                ),
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getSeparatorColors()
    {
        return array(
            'separator' => array(
                '_label' => 'Separator',
                'title' => array(
                    'label'       => 'Title',
                    'elements'    => '
                        .tb_separator .tb_title
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.titles'
                ),
                'title_bg' => array(
                    'label'       => 'Title Background',
                    'elements'    => '
                        .tb_separator .tb_title
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'theme:main.accent'
                ),
                'border_color' => array(
                    'label'       => 'Border',
                    'elements'    => '
                        .tb_separator .border
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.column_border'
                )
            )
        );
    }

    protected static function getBannerColors()
    {
        return array(
            'banner' => array(
                '_label' => 'Banner',
                'line_1' => array(
                    'label'       => 'Line 1',
                    'elements'    => '.tb_line_1',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text'
                ),
                'line_2' => array(
                    'label'       => 'Line 2',
                    'elements'    => '.tb_line_2',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text'
                ),
                'line_3' => array(
                    'label'       => 'Line 3',
                    'elements'    => '.tb_line_3',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.text'
                ),
                'hover_bg' => array(
                    'label'       => 'Hover bg',
                    'elements'    => '.tb_image:before',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:main.accent'
                )
            )
        );
    }

    protected static function getPaginationColors()
    {
        $default_colors = array (
            'pagination' => MainColorsData::getColors('pagination')
        );

        $preset_colors  = array(
            'pagination' => array(
                '_label' => 'Pagination',
                'links_text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text'
                ),
                'links_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg'
                ),
                'links_text_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text_hover'
                ),
                'links_bg_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg_hover'
                ),
                'links_text_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text_active'
                ),
                'links_bg_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg_active'
                ),
                'results' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.results'
                )
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getProductsColors()
    {
        $default_colors = array (
            'products__listing'       => MainColorsData::getColors('product_listing'),
            'products__listing_hover' => MainColorsData::getColors('product_listing_hover')
        );

        $preset_colors  = array(
            'products__listing' => array(
                '_label' => 'Product box',
                'product_text' => array(
                    'inherit_key' => 'theme:product_listing.product_text'
                ),
                'product_links' => array(
                    'inherit_key' => 'theme:product_listing.product_links'
                ),
                'product_links_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_links_hover'
                ),
                'product_title' => array(
                    'inherit_key' => 'theme:product_listing.product_title'
                ),
                'product_title_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_title_hover'
                ),
                'product_price' => array(
                    'inherit_key' => 'theme:product_listing.product_price'
                ),
                'product_promo_price' => array(
                    'inherit_key' => 'theme:product_listing.product_promo_price'
                ),
                'product_old_price' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_old_price'
                ),
                'product_tax_price' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_tax_price'
                ),
                'rating_percent' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_percent'
                ),
                'rating_base' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.rating_base'
                ),
                'rating_text' => array(
                    'inherit_key' => 'theme:product_listing.rating_text'
                ),
                'product_add_to_cart_bg' => array(
                    'inherit_key' => 'theme:product_listing.product_add_to_cart_bg'
                ),
                'product_add_to_cart_text' => array(
                    'inherit_key' => 'theme:product_listing.product_add_to_cart_text'
                ),
                'product_add_to_cart_bg_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_add_to_cart_bg_hover'
                ),
                'product_add_to_cart_text_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_add_to_cart_text_hover'
                ),
                'product_compare_bg' => array(
                    'inherit_key' => 'theme:product_listing.product_compare_bg'
                ),
                'product_compare_text' => array(
                    'inherit_key' => 'theme:product_listing.product_compare_text'
                ),
                'product_compare_bg_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_compare_bg_hover'
                ),
                'product_compare_text_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_compare_text_hover'
                ),
                'product_wishlist_bg' => array(
                    'inherit_key' => 'theme:product_listing.product_wishlist_bg'
                ),
                'product_wishlist_text' => array(
                    'inherit_key' => 'theme:product_listing.product_wishlist_text'
                ),
                'product_wishlist_bg_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_wishlist_bg_hover'
                ),
                'product_wishlist_text_hover' => array(
                    'inherit_key' => 'theme:product_listing.product_wishlist_text_hover'
                ),
                'product_sale_label_bg' => array(
                    'inherit_key' => 'theme:product_listing.product_sale_label_bg'
                ),
                'product_sale_label_text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_sale_label_text'
                ),
                'product_new_label_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_new_label_bg'
                ),
                'product_new_label_text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_new_label_text'
                ),
                'product_separator' => array(
                    'inherit_key' => 'theme:product_listing.product_separator'
                ),
                'product_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing.product_bg'
                ),
            ),
            'products__listing_hover' => array(
                '_label' => 'Product box (hover)',
                'product_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_text'
                ),
                'product_links' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_links'
                ),
                'product_links_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_links_hover'
                ),
                'product_title' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_title'
                ),
                'product_title_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_title_hover'
                ),
                'product_price' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_price'
                ),
                'product_promo_price' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_promo_price'
                ),
                'product_old_price' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_old_price'
                ),
                'product_tax_price' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_tax_price'
                ),
                'rating_percent' => array(
                    'inherit_key' => 'theme:product_listing_hover.rating_percent'
                ),
                'rating_base' => array(
                    'inherit_key' => 'theme:product_listing_hover.rating_base'
                ),
                'rating_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.rating_text'
                ),
                'product_add_to_cart_bg' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_add_to_cart_bg'
                ),
                'product_add_to_cart_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_add_to_cart_text'
                ),
                'product_add_to_cart_bg_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_add_to_cart_bg_hover'
                ),
                'product_add_to_cart_text_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_add_to_cart_text_hover'
                ),
                'product_compare_bg' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_compare_bg'
                ),
                'product_compare_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_compare_text'
                ),
                'product_compare_bg_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_compare_bg_hover'
                ),
                'product_compare_text_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_compare_text_hover'
                ),
                'product_wishlist_bg' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_wishlist_bg'
                ),
                'product_wishlist_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_wishlist_text'
                ),
                'product_wishlist_bg_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_wishlist_bg_hover'
                ),
                'product_wishlist_text_hover' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_wishlist_text_hover'
                ),
                'product_sale_label_bg' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_sale_label_bg'
                ),
                'product_sale_label_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_sale_label_text'
                ),
                'product_new_label_bg' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_new_label_bg'
                ),
                'product_new_label_text' => array(
                    'inherit_key' => 'theme:product_listing_hover.product_new_label_text'
                ),
                'product_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:product_listing_hover.product_bg'
                ),
            )
        );

        $engine_config = TB_Engine::instance()->getThemeConfig();
        $engine_config = isset($engine_config['colors']['presets']) ? $engine_config['colors']['presets']['products'] : array();

        return array_replace_recursive(array_replace_recursive($default_colors, $preset_colors), $engine_config);
    }

    protected static function getUiTabsColors()
    {
        $default_colors = array (
            'ui_tabs' => MainColorsData::getColors('ui_tabs')
        );

        $preset_colors  = array(
            'ui_tabs' => array(
                '_label' => 'Tabs',
                'header_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.header_bg'
                ),
                'header_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.header_border'
                ),
                'content_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.content_border'
                ),
                'clickable_text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_text'
                ),
                'clickable_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_bg'
                ),
                'clickable_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_border'
                ),
                'clickable_text_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_text_hover'
                ),
                'clickable_bg_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_bg_hover'
                ),
                'clickable_border_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_border_hover'
                ),
                'clickable_text_active' => array(
                    'inherit_key' => 'theme:ui_tabs.clickable_text_active'
                ),
                'clickable_bg_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_bg_active'
                ),
                'clickable_border_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_tabs.clickable_border_active'
                ),
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getUiAccordionColors()
    {
        $default_colors = array (
            'ui_accordion' => MainColorsData::getColors('ui_accordion')
        );

        $preset_colors  = array(
            'ui_accordion' => array(
                '_label' => 'Accordion',
                'content_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.content_border'
                ),
                'clickable_text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_text'
                ),
                'clickable_bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_bg'
                ),
                'clickable_border' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_border'
                ),
                'clickable_text_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_text_hover'
                ),
                'clickable_bg_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_bg_hover'
                ),
                'clickable_border_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_border_hover'
                ),
                'clickable_text_active' => array(
                    'inherit_key' => 'theme:ui_accordion.clickable_text_active'
                ),
                'clickable_bg_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_bg_active'
                ),
                'clickable_border_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:ui_accordion.clickable_border_active'
                ),
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getGalleryColors()
    {
        $default_colors = array (
            'gallery__navigation'        => MainColorsData::getColors('gallery_navigation'),
            'gallery__pagination'        => MainColorsData::getColors('gallery_pagination'),
            'gallery__fullscreen_button' => MainColorsData::getColors('gallery_fullscreen_button'),
            'gallery__caption'           => MainColorsData::getColors('gallery_caption')
        );

        $preset_colors  = array(
            'gallery__navigation' => array(
                '_label' => 'Gallery Prev/Next buttons',
                'button_default' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_default'
                ),
                'button_bg_default' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_bg_default'
                ),
                'button_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_hover'
                ),
                'button_bg_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_bg_hover'
                ),
            ),
            'gallery__pagination' => array(
                '_label' => 'Gallery Pagination',
                'default' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.default'
                ),
                'hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.hover'
                ),
                'active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.active'
                )
            ),
            'gallery__fullscreen_button' => array(
                '_label' => '"Go fullscreen" button',
                'fbutton_default' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_default'
                ),
                'fbutton_bg_default' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_bg_default'
                ),
                'fbutton_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_hover'
                ),
                'fbutton_bg_hover' => array(
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_bg_hover'
                ),
            ),
            'gallery__caption' => array(
                '_label' => 'Caption',
                'text' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_caption.text'
                ),
                'bg' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_caption.bg'
                ),
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getCarouselColors()
    {
        $default_colors = array (
            'carousel__nav' => MainColorsData::getColors('carousel_nav'),
            'carousel__pagination' => MainColorsData::getColors('carousel_pagination'),
        );

        $preset_colors  = array(
            'carousel__nav' => array(
                '_label' => 'Carousel navigation',
                'button_default' => array(
                    'inherit_key' => 'theme:carousel_nav.button_default'
                ),
                'button_hover' => array(
                    'inherit_key' => 'theme:carousel_nav.button_hover'
                ),
                'button_inactive' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_nav.button_inactive'
                ),
            ),
            'carousel__pagination' => array(
                '_label' => 'Carousel pagination',
                'pagination_default' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_default'
                ),
                'pagination_hover' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_hover'
                ),
                'pagination_active' => array(
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:carousel_pagination.pagination_active'
                )
            ),
        );

        return array_replace_recursive($default_colors, $preset_colors);
    }

    protected static function getBodyFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Body',
                'elements'          => '',
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
            ),
            'h1' => array(
                'section_name'      => 'H1',
                'elements'          => 'h1, .h1',
                'size'              => 26,
                'line-height'       => 30
            ),
            'h2' => array(
                'section_name'      => 'H2 / Legend',
                'elements'          => '
                    h2,
                    .h2,
                    legend,
                    .tb_slider_controls
                ',
                'size'              => 16,
                'line-height'       => 20
            ),
            'h3' => array(
                'section_name'      => 'H3',
                'elements'          => 'h3, .h3',
                'size'              => 15,
                'line-height'       => 20
            ),
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => '
                    h4, .h4,
                    .product-thumb .name,
                    .box-product .name,
                    .product-grid .name,
                    .product-list .name
                ',
                'size'              => 14,
                'line-height'       => 20
            ),
            'buttons' => array(
                'section_name'      => 'Buttons',
                'elements'          => '
                    .btn,
                    .button,
                    button,
                    input[type="button"],
                    input[type="submit"],
                    input[type="reset"]
                ',
                'transform'         => 'uppercase',
                'size'              => 14,
                'has_line_height'   => false
            ),
        );
    }

    protected static function getProductFonts()
    {
        return array(
            'product_title' => array(
                'section_name'      => 'Product title',
                'elements'          => '
                    .product-thumb h4,
                    .product-thumb .name,
                    .box-product .name,
                    .product-grid .name,
                    .product-list .name
                ',
                'size'              => 14,
                'line-height'       => 20
            ),
            'product_price' => array(
                'section_name'      => 'Product price',
                'elements'          => '
                    .product-thumb .price,
                    .product-info .price
                ',
                'size'              => 18,
                'line-height'       => 30
            ),
            'product_buttons' => array(
                'section_name'      => 'Product buttons',
                'elements'          => '
                    .product-thumb .btn
                ',
                'size'              => 13,
                'has_line_height'   => false
            )
        );
    }

    protected static function getSeparatorFonts()
    {
        return array(
            'separator_title' => array(
                'section_name'      => 'Separator Title',
                'elements'          => '.tb_separator .tb_title',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 11,
                'line-height'       => 20,
                'letter-spacing'    => 1,
                'word-spacing'      => '',
                'transform'         => 'uppercase',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            )
        );
    }

    protected static function getBannerFonts()
    {
        return array(
            'line_1' => array(
                'section_name'      => 'Line 1',
                'elements'          => ' .tb_line_1',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 32,
                'line-height'       => 40,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'line_2' => array(
                'section_name'      => 'Line 2',
                'elements'          => ' .tb_line_2',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 24,
                'line-height'       => 30,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'line_3' => array(
                'section_name'      => 'Line 3',
                'elements'          => ' .tb_line_3',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 18,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            )
        );
    }

    /* ------------------------------------------------------
       Defaults
    ------------------------------------------------------ */

    protected static function getBoxColors()
    {
        // return self::getBodyColors();
    }

    protected static function getBoxFonts()
    {
        // return self::getBodyFonts();
        return array();
    }

    protected static function getTitleColors()
    {
        return array(
            'title' => array(
                '_label' => 'Title',
                'title' => array(
                    'label'       => 'Box title',
                    'elements'    => '.panel-heading, .box-heading',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'inherit_key' => 'theme:main.titles'
                )
            )
        );
    }

    protected static function getTitleFonts()
    {
        return array(
            'title' => array(
                'section_name'      => 'Box Title',
                'elements'          => '.panel-heading, .box-heading, .tb_slider_controls > a',
                'size'              => 18,
                'line-height'       => 20,
            )
        );
    }
}