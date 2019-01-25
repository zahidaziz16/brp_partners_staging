<?php

return array(
    'label'        => 'Product',
    'route'        => 'product/product',
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
            'label'  => 'Add to cart',
            'slot'   => 'product_buy',
            'areas'  => 'content',
            'class'  => 'Theme_ProductAddToCartSystemWidget',
            'locked' => true
        ),
        3 => array(
            'label'  => 'Attributes',
            'slot'   => 'product_attributes',
            'areas'  => 'content',
            'class'  => 'Theme_ProductAttributesSystemWidget',
            'locked' => true
        ),
        4 => array(
            'label'  => 'Description',
            'slot'   => 'product_description',
            'areas'  => 'content',
            'class'  => 'Theme_ProductDescriptionSystemWidget',
            'locked' => true
        ),
        5 => array(
            'label'  => 'Discount',
            'slot'   => 'product_discounts',
            'areas'  => 'content',
            'class'  => 'Theme_ProductDiscountsSystemWidget',
            'locked' => true
        ),
        6 => array(
            'label'  => 'Images',
            'slot'   => 'product_images',
            'areas'  => 'content',
            'class'  => 'Theme_ProductImagesSystemWidget',
            'locked' => true
        ),
        7 => array(
            'label'  => 'Info',
            'slot'   => 'product_info',
            'areas'  => 'content',
            'class'  => 'Theme_ProductInfoSystemWidget',
            'locked' => true
        ),
        8 => array(
            'label'  => 'Options',
            'slot'   => 'product_options',
            'areas'  => 'content',
            'class'  => 'Theme_ProductOptionsSystemWidget',
            'locked' => true
        ),
        9 => array(
            'label'  => 'Price',
            'slot'   => 'product_price',
            'areas'  => 'content',
            'class'  => 'Theme_ProductPriceSystemWidget',
            'locked' => true
        ),
        10 => array(
            'label'  => 'Rating',
            'slot'   => 'product_reviews_summary',
            'areas'  => 'content',
            'class'  => 'Theme_ProductRatingSystemWidget',
            'locked' => true
        ),
        11 => array(
            'label'  => 'Related Products',
            'slot'   => 'related_products',
            'areas'  => 'content, column_left, column_right',
            'class'  => 'Theme_RelatedProductsSystemWidget',
            'locked' => true
        ),
        12 => array(
            'label'  => 'Reviews',
            'slot'   => 'product_reviews',
            'areas'  => 'content',
            'class'  => 'Theme_ProductReviewsSystemWidget',
            'locked' => true
        ),
        13 => array(
            'label'  => 'Share buttons',
            'slot'   => 'product_share',
            'areas'  => 'content',
            'class'  => 'Theme_ProductShareSystemWidget',
            'locked' => true
        ),
        14 => array(
            'label'  => 'Special Price Countdown',
            'slot'   => 'special_price_counter',
            'areas'  => 'content',
            'class'  => 'Theme_ProductSpecialPriceCounterSystemWidget',
            'locked' => true
        ),
        15 => array(
            'label'  => 'Tags',
            'slot'   => 'product_tags',
            'areas'  => 'content',
            'class'  => 'Theme_ProductTagsSystemWidget',
            'locked' => true
        )
    )
);