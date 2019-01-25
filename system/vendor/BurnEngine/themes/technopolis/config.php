<?php

return array(
    'oc_image_dimensions' => array(
        'thumb_width'       => 700,
        'thumb_height'      => 800,
        'popup_width'       => 1200,
        'popup_height'      => 1200,
        'additional_width'  => 70,
        'additional_height' => 70,
        'compare_width'     => 90,
        'compare_height'    => 90,
        'wishlist_width'    => 50,
        'wishlist_height'   => 50,
        'cart_width'        => 70,
        'cart_height'       => 80,
        'location_width'    => 250,
        'location_height'   => 50,
        'category_width'    => 150,
        'category_height'   => 150,
        'product_width'     => 350,
        'product_height'    => 400,
        'related_width'     => 80,
        'related_height'    => 80
    ),
    'row_style_vars' => array(
        'layout' => array(
            'type'           => 'full',
            'margin_bottom'  => 40,
            'columns_gutter' => 40
        )
    ),
    'widget_title_style_vars' => array(
        'layout' => array(
            'margin_bottom' => 30
        )
    ),
    'builder_defaults'      => array(
        'content' => array(
            'rows' => array(
                0 => array(
                    'columns' => array(
                        0 => array(
                            'grid_proportion' => '1_1',
                            'widgets' => array(
                                0 => array(
                                    'label'    => 'Breadcrumbs',
                                    'slot'     => 'breadcrumbs',
                                    'class'    => 'Theme_BreadcrumbsSystemWidget',
                                    'settings' => array()
                                ),
                                1 => array(
                                    'label'    => 'Page Title',
                                    'slot'     => 'page_title',
                                    'class'    => 'Theme_PageTitleSystemWidget',
                                    'settings' => array()
                                )
                            )
                        )
                    ),
                    'settings' => array(
                        'layout' => array(
                            'margin_bottom' => 40,
                        )
                    )
                ),
                1 => array(
                    'columns' => array(
                        0 => array(
                            'grid_proportion' => '1_1',
                            'widgets' => array(
                                0 => array(
                                    'label'    => 'Page Content',
                                    'slot'     => 'page_content',
                                    'settings' => array()
                                )
                            )
                        )
                    ),
                    'settings' => array(
                        'layout' => array(
                            'margin_bottom' => 0,
                        )
                    )
                )
            )
        )
    ),
    'colors' => array(
        'inherit_menu' => array(
            1 => array(
                'inherit_key' => 'theme:main.secondary'
            ),
            2 => array(
                'inherit_key' => 'theme:main.tertiary'
            )
        ),
        'theme' => array(
            'main' => array(
                'secondary' => array(
                    'label'       => 'Secondary',
                    'elements'    => '
                        .tb_secondary_color,
                        .tb_hover_secondary_color:hover
                    ',
                    'property'      => 'color',
                    'color'         => '#77bbdb',
                    'important'     => 1,
                    'force_print'   => 0,
                    'can_inherit'   => 0,
                    'inherit'       => 0,
                    'inherit_key'   => '',
                    'render_before' => 'text',
                ),
                'secondary_bg_hidden_color' => array(
                    'label'       => 'Secondary bg',
                    'elements'    => '
                        .tb_secondary_color_bg,
                        .tb_hover_secondary_color_bg:hover
                    ',
                    'property'      => 'background-color',
                    'color'         => '#77bbdb',
                    'important'     => 1,
                    'force_print'   => 1,
                    'can_inherit'   => 1,
                    'inherit'       => 1,
                    'inherit_key'   => 'main.secondary',
                    'render_before' => 'text',
                ),
                'tertiary' => array(
                    'label'       => 'Tertiary',
                    'elements'    => '
                        .tb_tertiary_color,
                        .tb_hover_tertiary_color:hover
                    ',
                    'property'      => 'color',
                    'color'         => '#ffdd00',
                    'important'     => 1,
                    'force_print'   => 0,
                    'can_inherit'   => 0,
                    'inherit'       => 0,
                    'inherit_key'   => '',
                    'render_before' => 'text',
                ),
                'tertiary_bg_hidden_color' => array(
                    'label'       => 'Tertiary bg',
                    'elements'    => '
                        .tb_tertiary_color_bg,
                        .tb_hover_tertiary_color_bg:hover
                    ',
                    'property'      => 'background-color',
                    'color'         => '#ffdd00',
                    'important'     => 1,
                    'force_print'   => 1,
                    'can_inherit'   => 10,
                    'inherit'       => 1,
                    'inherit_key'   => 'main.tertiary',
                    'render_before' => 'text',
                ),
            ),
        ),
        'area'  => array(
            'area_content' => array(
                'body' => array(
                    'page_title' => array(
                        'label'       => 'Page title',
                        'elements'    => '
                            .tb_system_page_title > h1
                        ',
                        'property'      => 'color',
                        'color'         => '#333333',
                        'important'     => 0,
                        'force_print'   => 0,
                        'can_inherit'   => 1,
                        'inherit'       => 1,
                        'inherit_key'   => 'area:body.titles',
                    ),
                    'breadcrumbs_text' => array(
                        'label'       => 'Breadcrumbs text',
                        'elements'    => '
                            .tb_system_breadcrumbs
                        ',
                        'property'      => 'color',
                        'color'         => '#333333',
                        'important'     => 0,
                        'force_print'   => 0,
                        'can_inherit'   => 1,
                        'inherit'       => 1,
                        'inherit_key'   => 'area:body.text',
                    ),
                    'breadcrumbs_links' => array(
                        'label'       => 'Breadcrumbs links ',
                        'elements'    => '
                            .tb_system_breadcrumbs a:not(:hover)
                        ',
                        'property'      => 'color',
                        'color'         => '#333333',
                        'important'     => 0,
                        'force_print'   => 0,
                        'can_inherit'   => 1,
                        'inherit'       => 1,
                        'inherit_key'   => 'area:body.links',
                    ),
                    'breadcrumbs_links_hover' => array(
                        'label'       => 'Breadcrumbs links (hover)',
                        'elements'    => '
                            .tb_system_breadcrumbs a:hover
                        ',
                        'property'      => 'color',
                        'color'         => '#b92616',
                        'important'     => 0,
                        'force_print'   => 0,
                        'can_inherit'   => 1,
                        'inherit'       => 1,
                        'inherit_key'   => 'area:body.links_hover',
                    ),
                ),
            ),
        )
    ),
    'product_listing_layouts' => array(
        'grid' => array(
            0 => array(
                'name'     => 'Technopolis full info',
                'template' => 'products_grid_style_1',
                'disabled' => array(
                    'elements_hover_action' => 'none',
                    'thumb_default'       => '1',
                    'cart_default'        => '1',
                    'rating_default'      => '1',
                    'thumb_hover'         => '0',
                    'description_hover'   => '0',
                    'cart_hover'          => '0',
                    'rating_hover'        => '0'
                )
            ),
            1 => array(
                'name'     => 'Technopolis short info',
                'template' => 'products_grid_style_2',
                'disabled' => array(
                    'thumb_default'       => '1',
                    'cart_default'        => '1',
                    'rating_default'      => '1',
                    'thumb_hover'         => '0',
                    'description_hover'   => '0',
                    'cart_hover'          => '0',
                    'rating_hover'        => '0'
                )
            ),
        )
    ),
    'additional_icons' => array(
        'Categories'        => 'fonts/category',
        'Material Design'   => 'fonts/materialdesign'
    ),
    'form_border_width'    => 1,
    'form_control_height'  => 2,
    'submit_button_height' => 1.25,
    'copyright' => 'Copyright &copy; Printer Bullet, All Rights Reserved.'
);