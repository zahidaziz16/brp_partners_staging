<?php

return array(
    'label'        => 'Products by manufacturer',
    'route'        => 'product/manufacturer/info',
    'display'      => true,
    'ssl'          => false,
    'merge_global' => array('header'),
    'widgets'      => array(
        0 => array(
            'label'  => 'Breadcrumbs',
            'slot'   => 'breadcrumbs',
            'areas'  => 'intro, content, column_left, column_right',
            'class'  => 'Theme_BreadcrumbsSystemWidget',
            'locked' => false
        ),
        1 => array(
            'label'  => 'Page title',
            'slot'   => 'page_title',
            'areas'  => 'intro, content, column_left, column_right',
            'class'  => 'Theme_PageTitleSystemWidget',
            'locked' => false
        ),
        2 => array(
            'label'  => 'Products',
            'slot'   => 'products',
            'areas'  => 'content',
            'class'  => 'Theme_ProductsSystemWidget',
            'locked' => true
        )
    )
);