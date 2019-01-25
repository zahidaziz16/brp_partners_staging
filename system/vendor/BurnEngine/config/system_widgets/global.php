<?php

return array(
    'widgets' => array(
        0 => array(
            'label'  => 'Cart Menu',
            'slot'   => 'cart_menu',
            'areas'  => 'header',
            'class'  => 'Theme_HeaderCartMenuSystemWidget',
            'locked' => true
        ),
        1 => array(
            'label'  => 'Currency Menu',
            'slot'   => 'currency',
            'areas'  => 'header',
            'class'  => 'Theme_HeaderCurrencyMenuSystemWidget',
            'locked' => true
        ),
        2 => array(
            'label'  => 'Language Menu',
            'slot'   => 'language',
            'areas'  => 'header',
            'class'  => 'Theme_HeaderLanguageMenuSystemWidget',
            'locked' => true
        ),
        3 => array(
            'label'  => 'Search',
            'slot'   => 'search',
            'areas'  => 'header',
            'class'  => 'Theme_HeaderSearchSystemWidget',
            'locked' => true
        ),
        4 => array(
            'label'  => 'Site Logo',
            'slot'   => 'logo',
            'areas'  => 'header',
            'class'  => 'Theme_HeaderLogoSystemWidget',
            'locked' => true
        ),
        5 => array(
            'label'  => 'Welcome',
            'slot'   => 'welcome',
            'areas'  => 'header',
            'class'  => 'Theme_HeaderWelcomeSystemWidget',
            'locked' => true
        ),
        6 => array(
            'label'  => 'Breadcrumbs',
            'slot'   => 'breadcrumbs',
            'areas'  => 'intro, content, column_left, column_right',
            'class'  => 'Theme_BreadcrumbsSystemWidget',
            'locked' => false
        ),
        7 => array(
            'label'  => 'Page title',
            'slot'   => 'page_title',
            'areas'  => 'intro, content, column_left, column_right',
            'class'  => 'Theme_PageTitleSystemWidget',
            'locked' => false
        ),
        9 => array(
            'label'  => 'Page Content',
            'slot'   => 'page_content',
            'areas'  => 'content',
            'locked' => true
        )
    ),
    'builder_defaults' => array(
        'content' => array(
            'rows' => array(
                0 => array(
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
                            'margin_bottom'   => 0,
                        )
                    )
                )
            )
        ),
        'content_quickview' => array(
            'rows' => array(
                0 => array(
                    'columns' => array(
                        0 => array(
                            'widgets' => array(
                                0 => array(
                                    'label'  => 'Images',
                                    'slot'   => 'product_images',
                                    'class'  => 'Theme_ProductImagesSystemWidget',
                                    'settings' => array()
                                )
                            ),
                            'grid_proportion' => '1_2',
                            'settings' => array(
                                'viewport' => array(
                                    'xs' => array(
                                        'layout' => array(
                                            'grid_proportion' => '1_1'
                                        )
                                    ),
                                    'sm' => array(
                                        'layout' => array(
                                            'grid_proportion' => '1_2'
                                        )
                                    ),
                                    'md' => array(
                                        'layout' => array(
                                            'grid_proportion' => '1_2'
                                        )
                                    )
                                )
                            )
                        ),
                        1 => array(
                            'widgets' => array(
                                0 => array(
                                    'label'  => 'Page title',
                                    'slot'   => 'page_title',
                                    'class'  => 'Theme_PageTitleSystemWidget',
                                    'settings' => array(
                                        'box_styles' => array(
                                            'layout' => array(
                                                'margin_bottom'   => 30
                                            )
                                        )
                                    )
                                ),
                                1 => array(
                                    'label'  => 'Info',
                                    'slot'   => 'product_info',
                                    'class'  => 'Theme_ProductInfoSystemWidget',
                                    'settings' => array(
                                        'box_styles' => array(
                                            'layout' => array(
                                                'margin_bottom'   => 30
                                            )
                                        )
                                    )
                                ),
                                2 => array(
                                    'label'  => 'Options',
                                    'slot'   => 'product_options',
                                    'class'  => 'Theme_ProductOptionsSystemWidget',
                                    'settings' => array(
                                        'box_styles' => array(
                                            'layout' => array(
                                                'margin_bottom'   => 30
                                            )
                                        )
                                    )
                                ),
                                3 => array(
                                    'label'    => 'Add to cart',
                                    'slot'     => 'product_buy',
                                    'class'    => 'Theme_ProductAddToCartSystemWidget',
                                    'settings' => array()
                                ),
                            ),
                            'grid_proportion' => '1_2',
                            'settings' => array(
                                'viewport' => array(
                                    'xs' => array(
                                        'layout' => array(
                                            'grid_proportion' => '1_1'
                                        )
                                    ),
                                    'sm' => array(
                                        'layout' => array(
                                            'grid_proportion' => '1_2'
                                        )
                                    ),
                                    'md' => array(
                                        'layout' => array(
                                            'grid_proportion' => '1_2'
                                        )
                                    )
                                )
                            )
                        )
                    ),
                    'settings' => array(
                        'layout' => array(
                            'margin_bottom'  => 0,
                            'padding_top'    => 30,
                            'padding_right'  => 30,
                            'padding_bottom' => 30,
                            'padding_left'   => 30
                        )
                    )
                )
            )
        )
    )
);

