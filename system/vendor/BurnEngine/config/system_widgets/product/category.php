<?php

return array(
    'label'        => 'Product Category',
    'route'        => 'product/category',
    'display'      => false,
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
            'label'  => 'Category thumb',
            'slot'   => 'category_thumb',
            'areas'  => 'content, intro, column_left, column_right',
            'locked' => false
        ),
        3 => array(
            'label'  => 'Category description',
            'slot'   => 'category_description',
            'areas'  => 'content, intro, column_left, column_right',
            'locked' => false
        ),
        4 => array(
            'label'  => 'Subcategories',
            'slot'   => 'subcategory_listing',
            'areas'  => 'content, intro, column_left, column_right',
            'class'  => 'Theme_SubcategoriesSystemWidget',
            'locked' => true
        ),
        5 => array(
            'label'  => 'Products',
            'slot'   => 'products',
            'areas'  => 'content',
            'class'  => 'Theme_ProductsSystemWidget',
            'locked' => true
        )
    )
);