/*  -----------------------------------------------------------------------------------------
    P R O D U C T   L A Y O U T
-----------------------------------------------------------------------------------------  */

.product-thumb,
.product-thumb > *,
.tb_grid_view .product-thumb .caption,
.tb_grid_view .product-thumb .caption > *,
.tb_list_view .product-thumb .caption
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.product-thumb .wait.wait {
  display: none;
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   E L E M E N T S
-----------------------------------------------------------------------------------------  */

.product-thumb,
.product-thumb > .image + div,
.product-thumb > .image_hover + div,
.product-thumb > .image-wrap + div,
.product-thumb .caption
{
  position: relative;
}
.product-thumb .image.tb_flip,
.product-thumb .image.tb_overlay
{
  display: block;
}
.product-thumb .image .tb_front,
.product-thumb .image .tb_back
{
  margin: 0 !important;
  padding: 0 !important;
}
.product-thumb .image a,
.product-thumb .image-wrap a
{
  display: block;
  text-align: center;
}
.product-thumb .image span {
  display: block;
  font-size: 0;
  margin-left: auto;
  margin-right: auto;
}
.product-thumb .image span span {
  width: 100%;
  height: 0;
  margin-left: auto;
  margin-right: auto;
}
.product-thumb .caption > * {
  margin-bottom: 0 !important;
}
.product-thumb .tb_item_info_hover {
  bottom: 0;
  right: 0;
}

.tb_label_stock_status {
  opacity: 0.5;
}

/*** IMAGE ***/

.image_hover:not(.tb_back) {
  display: none !important;
}

/*** PRICE ***/

.price {
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
.price-old {
  position: relative;
  display: inline-block;
}
.price-old:before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  display: block;
  width: 100%;
  margin-top: -1px;
  border-bottom: 1px solid;
}
.price-tax {
  display: block;
}

.product-thumb p:not([class]) {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
}
.product-thumb div:empty,
.product-thumb p:empty,
.product-thumb ul:empty
.product-thumb ol:empty,
.product-thumb li:empty
{
  display: none;
}
.product-thumb .tb_label_special,
.product-thumb .tb_label_new
{
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
  z-index: 4;
  display: block;
  min-width: 32px;
  height: 20px;
  padding: 0 5px;
  line-height: 20px;
  text-align: center;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
  font-weight: bold;
  font-weight: 600;
}
.product-thumb .tb_label_special > span span {
  font-weight: normal;
}
.product-thumb .tb_label_special small {
  display: none;
}
.product-thumb .tb_label_new {
  background: #90c819;
  color: #fff;
  text-transform: uppercase;
}
.product-thumb .tb_label_special + .tb_label_new {
  top: 20px;
}
.product-thumb .button-group {
  -ms-flex-order: 10;
  -webkit-order: 10;
  order: 10;
}
.product-thumb .tb_button_add_to_cart {
  margin-top: 0;
  margin-bottom: <?php echo $base / 2; ?>px;
}
.product-thumb .tb_label_stock_status {
  margin-bottom: 0;
  font-size: <?php echo $base_font_size; ?>px;
}

.tb_grid_view .price .tb_decimal_point,
.tb_list_view .price .tb_decimal_point,
.price.custom .tb_decimal_point
{
  display: none;
}
.tb_grid_view .price .tb_decimal,
.tb_list_view .price .tb_decimal,
.price.custom .tb_decimal
{
  position: relative;
  top: -0.25em;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 1px;
  <?php else: ?>
  margin-right: 1px;
  <?php endif; ?>
  font-size: 0.6em;
  vertical-align: top;
}


/*** Review ***/

.product-thumb .tb_review {
  clear: both;
}
.tb_review_tooltip .price {
  display: table-footer-group;
}
.tb_review_tooltip .tb_review {
  display: table-header-group;
}
.tb_review_tooltip .tb_review > p + .tb_meta {
  padding-left: 0;
  padding-right: 0;
}
.tb_review_tooltip .product-thumb .tb_review > p,
.tb_review_tooltip .product-thumb .tb_review .tb_author
{
  display: none;
}
.product-thumb .tooltip-inner .tb_review > p,
.product-thumb .tooltip-inner .tb_review .tb_author
{
  display: block;
}
.product-thumb .tooltip-inner .tb_review .tb_meta {
  text-indent: 35px;
}
.tb_review_tooltip .tb_rating_holder,
.tb_review_tooltip .tb_review p + .tb_meta .tb_rating_holder
{
  margin-top: 0;
  margin-bottom: 0;
  cursor: help;
}
.tb_review_tooltip .tb_rating_holder .tb_rating,
.tb_review_tooltip .tb_rating_holder .tb_rating *
{
  font-size: 12px;
}
.tb_review_tooltip .tb_rating_holder .tb_average {
  margin-top: 0;
  font-weight: normal;
  font-size: 0.85em;
}

/*  ---   Effects ( flip / overlay )   --------------------------------------------------  */

@media screen and (-webkit-min-device-pixel-ratio: 0) {
  .product-thumb.tb_overlay,
  .product-thumb.tb_flip
  {
    position: relative;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
  }
  .product-thumb.tb_overlay .tb_front,
  .product-thumb.tb_flip .tb_front
  {
    height: auto;
  }
}

/*  -----------------------------------------------------------------------------------------
    L I S T   V I E W
-----------------------------------------------------------------------------------------  */

.tb_list_view .product-thumb {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  width: 100%;
}
.tb_list_view .product-thumb > div {
  float: none;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  vertical-align: top;
}
.tb_list_view .product-thumb .image + div {
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
  -ms-flex-item-align: start;
   -webkit-align-self: flex-start;
           align-self: flex-start;
}
.tb_list_view .product-thumb .image {
  display: block;
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  max-width: 50%;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base; ?>px;
  <?php endif; ?>
}
<?php foreach ($grid_gutter as $gutter): ?>
.tb_list_view.tb_gut_<?php echo $gutter; ?> .product-thumb .image {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $gutter; ?>px;
  <?php else: ?>
  margin-left: <?php echo $gutter; ?>px;
  <?php endif; ?>
}
<?php endforeach; ?>
.tb_list_view .product-thumb .caption {
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  margin-top: -<?php echo $base * 0.5; ?>px;
}
.tb_list_view .product-thumb .caption > * {
  -ms-flex-order: 5;
   -webkit-order: 5;
           order: 5;
}
.tb_list_view .product-thumb .caption > * + * {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.tb_list_view .product-thumb .name,
.tb_list_view .product-thumb h4,
.tb_list_view .product-thumb .caption > h4
{
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  -ms-flex-order: 1;
   -webkit-order: 1;
           order: 1;
  margin-top: <?php echo $base * 0.5; ?>px;
  margin-bottom: <?php echo $base; ?>px;
}
.tb_list_view .product-thumb .price {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  -ms-flex-order: 2;
   -webkit-order: 2;
           order: 2;
  margin: <?php echo $base * 0.5; ?>px 0 0 0 !important;
}
.tb_list_view .product-thumb .description,
.tb_list_view .product-thumb p:not(class),
.tb_list_view .product-thumb .rating,
.tb_list_view .product-thumb .button-group
{
  clear: both;
      -ms-flex: 1 1 100%;
  -webkit-flex: 1 1 100%;
          flex: 1 1 100%;
}
.tb_list_view .product-thumb > .rating {
  margin-top: -<?php echo $base * 0.5; ?>px;
  margin-bottom: -<?php echo $base * 0.5; ?>px;
}
.tb_list_view .product-thumb .tb_label_stock_status,
.tb_list_view .product-thumb .button-group
{
  -ms-flex-item-align: center;
  -webkit-align-self: center;
  align-self: center;
}
.tb_list_view .product-thumb .button-group {
  -ms-flex: 1 1 100%;
  -webkit-flex: 1 1 100%;
  flex: 1 1 100%;
  margin-top: <?php echo $base; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: right;
  <?php else: ?>
  text-align: left;
  <?php endif; ?>
}
.tb_list_view .tb_label_stock_status {
  margin-top: <?php echo $base; ?>px;
}
.tb_list_view .product-thumb .tb_button_add_to_cart {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  margin-bottom: 0;
}
.tb_list_view .product-thumb .tb_button_add_to_cart .btn {
  position: relative;
}
.tb_list_view .product-thumb .tb_button_add_to_cart .btn.btn-sm {
  top: <?php echo $base; ?>px;
}
.tb_list_view .product-thumb .tb_button_add_to_cart .btn.btn-md {
  top: <?php echo $base * 0.25; ?>px;
}
.tb_list_view .product-thumb .tb_label_stock_status {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
}
.tb_list_view .image .image {
  max-width: none;
  margin: 0;
}
@media (max-width: <?php echo $screen_xs; ?>px) {
  .tb_list_view .product-thumb {
    display: block !important;
  }
  .tb_list_view .image {
    max-width: none !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    margin-bottom: <?php echo $base; ?>px !important;
  }
  .tb_list_view .caption {
    clear: both;
  }
}

/*  -----------------------------------------------------------------------------------------
    G R I D   V I E W
-----------------------------------------------------------------------------------------  */

.tb_grid_view .product-thumb {
  width: 100%;
  text-align: center;
}
.tb_grid_view .product-thumb,
.tb_grid_view .product-thumb > *,
.tb_grid_view .product-thumb .caption
{
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
}
.tb_grid_view .product-thumb .image,
.tb_grid_view .product-thumb .image-wrap
{
  margin-bottom: <?php echo $base; ?>px;
}
.tb_grid_view .product-thumb .image-wrap .image {
  margin-bottom: 0 !important;
}
.tb_grid_view .product-thumb .image,
.tb_grid_view .product-thumb .image *,
.tb_grid_view .product-thumb .image-wrap
{
  -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
  flex: 0 0 auto;
}
.tb_grid_view .product-thumb > div:not(.image):not(.image-wrap):not(.thumbnail),
.tb_grid_view .product-thumb .caption,
.tb_grid_view .product-thumb .name + :not(.description),
.tb_grid_view .product-thumb .description + *
{
  -ms-flex: 1 0 auto;
  -webkit-flex: 1 0 auto;
  flex: 1 0 auto;
}
.tb_grid_view .product-thumb .caption {
  max-width: 100%;
}
.tb_grid_view .product-thumb .rating {
  -ms-flex: 1000 0 auto;
  -webkit-flex: 1000 0 auto;
  flex: 1000 0 auto;
}
.tb_grid_view:not(.tb_style_plain) .product-thumb .name + :not(.description),
.tb_grid_view:not(.tb_style_plain) .product-thumb .description + *,
.tb_grid_view:not(.tb_style_plain) .product-thumb .rating
{
  -ms-flex-align: end;
  -webkit-align-items: flex-end;
  align-items: flex-end;
}
.tb_grid_view .product-thumb .description {
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
  text-align: initial;
  word-wrap: break-word;
}
.tb_grid_view .product-thumb .image:last-child,
.tb_grid_view .product-thumb .image-wrap:last-child
{
  margin-bottom: 0;
}
.tb_grid_view .product-thumb .image_hover {
  width: 100%;
}
.tb_grid_view .product-thumb .caption.col {
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
}
.tb_grid_view .product-thumb .caption * {
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
.tb_grid_view .product-thumb  .image + div > * + *:not(.clear):not(.row),
.tb_grid_view .product-thumb  .image-wrap + div > * + *:not(.clear):not(.row),
.tb_grid_view .product-thumb .caption > * + *:not(.clear):not(.row)
{
  margin-top: <?php echo $base * 0.5; ?>px;
  margin-bottom: 0;
}
.tb_grid_view:not(.tb_style_plain) .product-thumb .price {
  -ms-flex-order: 5;
   -webkit-order: 5;
           order: 5;
  margin-bottom: 0;
}
.tb_grid_view .product-thumb .price {
  margin-left:  -3px;
  margin-right: -3px;
}
.tb_grid_view .product-thumb .price-regular,
.tb_grid_view .product-thumb .price-old,
.tb_grid_view .product-thumb .price-new
{
  margin: 0 3px;
}
.tb_grid_view .product-thumb .price-tax {
  -ms-flex-order: 6;
  -webkit-order: 6;
  order: 6;
  margin-top: 0;
  margin-bottom: 0;
  font-size: 11px;
}
.tb_grid_view .product-thumb .name a {
  display: block;
}
.tb_grid_view .product-thumb p:not([class]) {
  padding: <?php echo $base * 0.5; ?>px 0;
}
.tb_grid_view .product-thumb .rating {
  margin-left: auto;
  margin-right: auto;
}
.tb_grid_view .product-thumb .tb_button_add_to_cart {
  margin-bottom: 0;
}
.tb_grid_view .product-thumb .tb_button_add_to_cart .btn {
  vertical-align: top;
}
.tb_grid_view .product-thumb .product-thumb.tb_item_short:hover {
  margin: -20px -10px -21px -10px;
}
.tb_grid_view .product-thumb .button-group {
  /* overflow: hidden; */
  clear: both;
  width: auto;
  margin-top: 0 !important;
  margin-left:  -10px;
  margin-right: -10px;
  padding-left:  10px;
  text-align: center;
  font-size: 11px;
  color: #999;
}
.tb_grid_view .product-thumb .button-group > div {
  display: inline-block;
  margin-right: 10px;
  margin-top: <?php echo $base * 0.5; ?>px;
  white-space: nowrap;
}
.tb_grid_view .product-thumb .button-group > div.tb_button_add_to_cart {
  display: block;
  white-space: normal;
}
.tb_grid_view .product-thumb .tb_label_stock_status {
  line-height: <?php echo $base * 1.5; ?>px;
  text-align: center;
}
.tb_grid_view .tb_item_hovered {
  z-index: 40;
  position: absolute;
  top: -10%;
  width: 120% !important;
  right: -10%;
  max-width: none;
  margin: 0;
  padding: <?php echo $base; ?>px;
  background: #fff;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
}
<?php foreach ($grid_gutter as $gutter): ?>
.tb_grid_view.tb_gut_<?php echo $gutter; ?> .tb_item_hovered {
  margin-right: -<?php echo $gutter / 2; ?>px;
}
.tb_grid_view.tb_style_bordered .tb_item_hovered {
  margin-right: 0;
}
<?php endforeach; ?>
.tb_grid_view .tb_item_hovered .product-thumb {
  position: static;
  width: auto !important;
  margin: -<?php echo $base; ?>px;
}
.tb_grid_view .tb_item_hovered * {
  z-index: 40;
}
.tb_grid_view .tb_item_hovered .button-group .tb_button_add_to_cart {
  display: block;
}
.tb_grid_view .tb_item_hovered .button-group .tb_button_wishlist,
.tb_grid_view .tb_item_hovered .button-group .tb_button_compare
{
  display: inline-block;
}

/*** Compatibility ***/

.tb_grid_view > div > *:not(.product-thumb),
.product-grid > div > *:not(.product-thumb),
.box-product  > div > *:not(.product-thumb)
{
  width: 100%;
  -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
  flex: 0 0 auto;
}
.tb_grid_view > div > .image a,
.tb_grid_view > div > .image-wrap a,
.product-grid .image a,
.product-grid .image-wrap a,
.box-product .image a,
.box-product .image-wrap a
{
  display: block;
  text-align: center;
}

/*  -----------------------------------------------------------------------------------------
    C O M P A C T   V I E W
-----------------------------------------------------------------------------------------  */

.tb_compact_view .product-thumb {
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  padding: 0 !important;
}
.tb_compact_view .product-thumb .image + div {
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
}
/*
.tb_compact_view .product-thumb .caption {
  width: 100%;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
.tb_compact_view .product-thumb .caption > * {
  width: 100%;
}
*/
.tb_compact_view .product-thumb .tb_bar,
.tb_compact_view .product-thumb .tb_average
{
  font-size: calc(1em - 1px);
}
.tb_compact_view .product-thumb .tb_average {
  font-size: calc(1em - 2px);
}
.tb_compact_view .product-thumb .tb_review,
.tb_compact_view .product-thumb .price
{
  display: block !important;
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   P A D D I N G
-----------------------------------------------------------------------------------------  */

<?php for($product_padding = 5; $product_padding <= 50; $product_padding += 5): ?>
.tb_product_p_<?php echo $product_padding; ?>  .product-thumb {
  padding: <?php echo $product_padding; ?>px;
}
<?php endfor; ?>

/*  ---   Effects ( flip / overlay )   --------------------------------------------------  */

<?php for($product_padding = 5; $product_padding <= 50; $product_padding += 5): ?>
.tb_product_p_<?php echo $product_padding; ?> .product-thumb .tb_front,
.tb_product_p_<?php echo $product_padding; ?>  .product-thumb .tb_back
{
  margin: -<?php echo $product_padding; ?>px;
  padding: <?php echo $product_padding; ?>px;
}
<?php endfor; ?>

/*  ---   Thumbnail out of padding   ----------------------------------------------------  */

<?php for($product_padding = 5; $product_padding <= 50; $product_padding += 5): ?>
.tb_product_p_<?php echo $product_padding; ?>.tb_exclude_thumb  .product-thumb .image,
.tb_product_p_<?php echo $product_padding; ?>.tb_exclude_thumb  .product-thumb .image-wrap
{
  margin: -<?php echo $product_padding; ?>px;
}
<?php endfor; ?>
.tb_grid_view[class*="tb_product_p_"].tb_exclude_thumb .product-thumb .image:last-child,
.tb_grid_view[class*="tb_product_p_"].tb_exclude_thumb .product-thumb .image-wrap:last-child
{
  margin-bottom: 0;
}

/***   Grid view   ***/

.tb_grid_view.tb_product_p_5.tb_exclude_thumb  .product-thumb > div:not(:first-child) .caption { padding-top: 10px; }
.tb_grid_view.tb_product_p_10.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 20px; }
.tb_grid_view.tb_product_p_15.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 30px; }
.tb_grid_view.tb_product_p_20.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 40px; }
.tb_grid_view.tb_product_p_25.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 50px; }
.tb_grid_view.tb_product_p_30.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 60px; }
.tb_grid_view.tb_product_p_35.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 70px; }
.tb_grid_view.tb_product_p_40.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 80px; }
.tb_grid_view.tb_product_p_45.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 80px; }
.tb_grid_view.tb_product_p_50.tb_exclude_thumb .product-thumb > div:not(:first-child) .caption { padding-top: 80px; }
.tb_grid_view.tb_exclude_thumb .product-thumb > .caption:first-child,
.tb_grid_view.tb_exclude_thumb .product-thumb > div:first-child > .caption { padding-top: 0 !important; }

/***   List view   ***/

@media (min-width: <?php echo ($screen_xs + 1); ?>px) {
  <?php for($product_padding = 5; $product_padding <= 50; $product_padding += 5): ?>
  .tb_list_view.tb_product_p_<?php echo $product_padding; ?>.tb_exclude_thumb .product-thumb .image {
    <?php if ($lang_dir == 'ltr'): ?>
    margin-right: <?php echo $product_padding; ?>px;
    <?php else: ?>
    margin-left: <?php echo $product_padding; ?>px;
    <?php endif; ?>
  }
  <?php endfor; ?>
}
@media (max-width: <?php echo $screen_xs; ?>px) {
  <?php for($product_padding = 5; $product_padding <= 50; $product_padding += 5): ?>
  .tb_list_view.tb_product_p_<?php echo $product_padding; ?>.tb_exclude_thumb .product-thumb .image {
    <?php if ($lang_dir == 'ltr'): ?>
    margin-right: -<?php echo $product_padding; ?>px;
    <?php else: ?>
    margin-left: -<?php echo $product_padding; ?>px;
    <?php endif; ?>
  }
  <?php endfor; ?>
  .tb_list_view .button-group {
    text-align: initial !important;
  }
  .tb_list_view .button-group .tb_button_add_to_cart:not(:last-child) {
    float: none;
    margin-bottom: <?php echo $base * 0.5; ?>px;
  }
  .tb_list_view .button-group .tb_button_add_to_cart a {
    top: auto !important;
  }
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   B U T T O N S
-----------------------------------------------------------------------------------------  */

.product-thumb .button-group > .tb_hidden {
  overflow: hidden;
  display: inline-block !important;
  height: 0;
  opacity: 0;
}
.product-thumb:hover .button-group > .tb_hidden,
.is_touch .product-thumb .button-group > .tb_hidden
{
  overflow: visible;
  height: auto;
  opacity: 1;
}
.product-thumb .button-group > .tb_pos_inline {
  display: inline-block !important;
  vertical-align: middle;
}
.product-thumb .button-group > .tb_pos_br,
.product-thumb .button-group > .tb_pos_b,
.product-thumb .button-group > .tb_pos_bl
{
  z-index: 5;
  position: absolute;
  top: -<?php echo $base * 3.5; ?>px;
  -webkit-transition: opacity 0.3s ease-in-out;
          transition: opacity 0.3s ease-in-out
}
.button-group > .tb_pos_br .btn,
.button-group > .tb_pos_b  .btn,
.button-group > .tb_pos_bl .btn
{
  margin-top: 0;
  margin-bottom: 0;
}
.product-thumb .button-group > .tb_pos_br { right: 0;  }
.product-thumb .button-group > .tb_pos_bl { left: 0;   }

.tb_grid_view.tb_buttons_1 .product-thumb .button-group > .tb_pos_br,
.tb_grid_view.tb_buttons_1 .product-thumb .button-group > .tb_pos_b,
.tb_grid_view.tb_buttons_1 .product-thumb .button-group > .tb_pos_bl
{
  margin: 0;
}
.tb_grid_view.tb_buttons_1 .tb_pos_br.tb_hidden        ~ .tb_pos_br,
.tb_grid_view.tb_buttons_1 .tb_pos_br.tb_hidden.btn-xs ~ .tb_pos_br,
.tb_grid_view.tb_buttons_1 .tb_pos_br.tb_hidden.btn-sm ~ .tb_pos_br,
.tb_grid_view.tb_buttons_1 .tb_pos_br.tb_hidden.btn-lg ~ .tb_pos_br { right: 0; }

.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-xs + .tb_pos_br { right: <?php echo $form_control_height_xs + $base * 0.5; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-sm + .tb_pos_br { right: <?php echo $form_control_height_sm + $base * 0.5; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-md + .tb_pos_br { right: <?php echo $form_control_height    + $base * 0.5; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-lg + .tb_pos_br { right: <?php echo $form_control_height_lg + $base * 0.5; ?>px !important; }

.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-xs + .tb_pos_br + .tb_pos_br { right: <?php echo $form_control_height_xs * 2 + $base; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-sm + .tb_pos_br + .tb_pos_br { right: <?php echo $form_control_height_sm * 2 + $base; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-md + .tb_pos_br + .tb_pos_br { right: <?php echo $form_control_height    * 2 + $base; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_br.btn-lg + .tb_pos_br + .tb_pos_br { right: <?php echo $form_control_height_lg * 2 + $base; ?>px !important; }

.tb_grid_view.tb_buttons_1 .tb_pos_bl.tb_hidden        ~ .tb_pos_bl,
.tb_grid_view.tb_buttons_1 .tb_pos_bl.tb_hidden.btn-xs ~ .tb_pos_bl,
.tb_grid_view.tb_buttons_1 .tb_pos_bl.tb_hidden.btn-sm ~ .tb_pos_bl,
.tb_grid_view.tb_buttons_1 .tb_pos_bl.tb_hidden.btn-lg ~ .tb_pos_bl { left: 0; }

.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-xs + .tb_pos_bl { left: <?php echo $form_control_height_xs + $base * 0.5; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-sm + .tb_pos_bl { left: <?php echo $form_control_height_sm + $base * 0.5; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-md + .tb_pos_bl { left: <?php echo $form_control_height    + $base * 0.5; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-lg + .tb_pos_bl { left: <?php echo $form_control_height_lg + $base * 0.5; ?>px !important; }

.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-xs + .tb_pos_bl + .tb_pos_bl { left: <?php echo $form_control_height_xs * 2 + $base; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-sm + .tb_pos_bl + .tb_pos_bl { left: <?php echo $form_control_height_sm * 2 + $base; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-md + .tb_pos_bl + .tb_pos_bl { left: <?php echo $form_control_height    * 2 + $base; ?>px !important; }
.tb_grid_view.tb_buttons_1 .tb_pos_bl.btn-lg + .tb_pos_bl + .tb_pos_bl { left: <?php echo $form_control_height_lg * 2 + $base; ?>px !important; }

.tb_grid_view.tb_buttons_1 .tb_pos_b { left: 50%; }
.tb_grid_view.tb_buttons_1 .tb_pos_b.btn-xs:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_xs * 0.5; ?>px; }
.tb_grid_view.tb_buttons_1 .tb_pos_b.btn-sm:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_sm * 0.5; ?>px; }
.tb_grid_view.tb_buttons_1 .tb_pos_b.btn-md:not([style*="margin"]) { margin-left: -<?php echo $form_control_height    * 0.5; ?>px; }
.tb_grid_view.tb_buttons_1 .tb_pos_b.btn-lg:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_lg * 0.5; ?>px; }

.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"])      { margin-left: -<?php echo $form_control_height_xs * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"])      { margin-left: -<?php echo $form_control_height_sm * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"])      { margin-left: -<?php echo $form_control_height    * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"])      { margin-left: -<?php echo $form_control_height_lg * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"])     { margin-left: -<?php echo $form_control_height_xs * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"])     { margin-left: -<?php echo $form_control_height_sm * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"])     { margin-left: -<?php echo $form_control_height    * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"])     { margin-left: -<?php echo $form_control_height_lg * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"])    { margin-left: -<?php echo $form_control_height_xs * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"])    { margin-left: -<?php echo $form_control_height_sm * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"])    { margin-left: -<?php echo $form_control_height    * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"])    { margin-left: -<?php echo $form_control_height_lg * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_xs * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_sm * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"]) { margin-left: -<?php echo $form_control_height    * 1.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_lg * 1.5 + $base * 0.5; ?>px; }

.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_wishlist.tb_pos_b.btn-xs:not([style*="margin"])       { margin-left:  <?php echo $form_control_height_xs * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_wishlist.tb_pos_b.btn-sm:not([style*="margin"])       { margin-left:  <?php echo $form_control_height_sm * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_wishlist.tb_pos_b.btn-md:not([style*="margin"])       { margin-left:  <?php echo $form_control_height    * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--wishlist_b .tb_button_wishlist.tb_pos_b.btn-lg:not([style*="margin"])       { margin-left:  <?php echo $form_control_height_lg * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_quickview.tb_pos_b.btn-xs:not([style*="margin"])     { margin-left:  <?php echo $form_control_height_xs * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_quickview.tb_pos_b.btn-sm:not([style*="margin"])     { margin-left:  <?php echo $form_control_height_sm * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_quickview.tb_pos_b.btn-md:not([style*="margin"])     { margin-left:  <?php echo $form_control_height    * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--compare_b--quickview_b .tb_button_quickview.tb_pos_b.btn-lg:not([style*="margin"])     { margin-left:  <?php echo $form_control_height_lg * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-xs:not([style*="margin"])    { margin-left:  <?php echo $form_control_height_xs * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-sm:not([style*="margin"])    { margin-left:  <?php echo $form_control_height_sm * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-md:not([style*="margin"])    { margin-left:  <?php echo $form_control_height    * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--cart_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-lg:not([style*="margin"])    { margin-left:  <?php echo $form_control_height_lg * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-xs:not([style*="margin"]) { margin-left:  <?php echo $form_control_height_xs * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-sm:not([style*="margin"]) { margin-left:  <?php echo $form_control_height_sm * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-md:not([style*="margin"]) { margin-left:  <?php echo $form_control_height    * 0.5 + $base * 0.5; ?>px; }
.tb_buttons_config--compare_b--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-lg:not([style*="margin"]) { margin-left:  <?php echo $form_control_height_lg * 0.5 + $base * 0.5; ?>px; }

.tb_buttons_config--cart_b--compare_b   .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_compare.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_compare.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_wishlist.tb_pos_b.btn-xs:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_xs + $base * 0.25; ?>px; }
.tb_buttons_config--cart_b--compare_b   .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_compare.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_compare.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_wishlist.tb_pos_b.btn-sm:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_sm + $base * 0.25; ?>px; }
.tb_buttons_config--cart_b--compare_b   .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_compare.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_compare.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_wishlist.tb_pos_b.btn-md:not([style*="margin"]) { margin-left: -<?php echo $form_control_height    + $base * 0.25; ?>px; }
.tb_buttons_config--cart_b--compare_b   .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_add_to_cart.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_compare.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_compare.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_wishlist.tb_pos_b.btn-lg:not([style*="margin"]) { margin-left: -<?php echo $form_control_height_lg + $base * 0.25; ?>px; }

.tb_buttons_config--cart_b--compare_b   .tb_button_compare.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_wishlist.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_quickview.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_wishlist.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_quickview.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-xs:not([style*="margin"]),
.tb_buttons_config--cart_b--compare_b   .tb_button_compare.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_wishlist.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_quickview.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_wishlist.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_quickview.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-sm:not([style*="margin"]),
.tb_buttons_config--cart_b--compare_b   .tb_button_compare.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_wishlist.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_quickview.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_wishlist.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_quickview.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-md:not([style*="margin"]),
.tb_buttons_config--cart_b--compare_b   .tb_button_compare.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--cart_b--wishlist_b  .tb_button_wishlist.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--cart_b--quickview_b .tb_button_quickview.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--compare_b--wishlist_b   .tb_button_wishlist.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--compare_b--quickview_b  .tb_button_quickview.tb_pos_b.btn-lg:not([style*="margin"]),
.tb_buttons_config--wishlist_b--quickview_b .tb_button_quickview.tb_pos_b.btn-lg:not([style*="margin"]) { margin-left:  <?php echo $base * 0.25; ?>px; }

.tb_grid_view.tb_buttons_2 .product-thumb .button-group {
  z-index: 5;
  position: absolute;
  left: <?php echo $base * 0.5; ?>px;
  right: <?php echo $base * 0.5; ?>px;
  top: -<?php echo $base * 3; ?>px;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
  margin: 0 !important;
  padding: 0 0 0 10px !important;
}
.tb_grid_view.tb_buttons_2 .product-thumb .button-group > * {
  position: static;
  top: 0;
  display: inline-block;
  text-align: center;
  vertical-align: top;
}
.tb_grid_view.tb_buttons_2 .product-thumb .button-group .tb_pos_br {
  float: right;
}
.tb_grid_view.tb_buttons_2 .product-thumb .button-group .tb_pos_b {
  position: static;
}
.tb_grid_view.tb_buttons_2 .product-thumb .button-group .tb_pos_bl {
  float: left;
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   L I S T   S T Y L E S
-----------------------------------------------------------------------------------------  */

/*  ---   Default (plain style) listing   -----------------------------------------------  */

.tb_listing.tb_style_plain div:not(.tb_back) > .product-thumb {
  margin: 0;
  padding: 0 !important;
  background-color: transparent !important;
}
.tb_listing.tb_list_view.tb_style_plain div:not(.tb_back) > .product-thumb .image,
.tb_listing.tb_list_view.tb_style_plain div:not(.tb_back) > .product-thumb .image-wrap
{
  margin-top: 0;
  margin-bottom: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 0;
  <?php else: ?>
  margin-right: 0;
  <?php endif; ?>
}
.tb_listing.tb_grid_view.tb_style_plain div:not(.tb_item_hovered):not(.tb_back) > .product-thumb .image,
.tb_listing.tb_grid_view.tb_style_plain div:not(.tb_item_hovered):not(.tb_back) > .product-thumb .image-wrap
{
  margin-top: 0;
  margin-right: 0;
  margin-left: 0;
}
.tb_grid_view.tb_style_plain .tb_item_hovered {
  top: -15%;
  right: -15%;
  width: 130% !important;
}
.tb_grid_view.tb_style_plain.tb_exclude_thumb .tb_item_hovered {
  top: -12%;
  right: -5% !important;
  width: 110% !important;
}
.tb_grid_view.tb_style_plain.tb_exclude_thumb .tb_item_hovered .image,
.tb_grid_view.tb_style_plain.tb_exclude_thumb .tb_item_hovered .image-wrap
{
  margin-top: -<?php echo $base; ?>px;
  margin-left: -<?php echo $base; ?>px;
  margin-right: -<?php echo $base; ?>px;
}

/*  -----------------------------------------------------------------------------------------
    Q U I C K V I E W
-----------------------------------------------------------------------------------------  */

.tb_button_quickview a {
  position: relative;
}
.tb_button_quickview a .wait {
  display: block !important;
  position: absolute;
  top: 50%;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
}
.tb_button_quickview a.btn .wait {
  left: auto;
  right: auto;
}
.tb_button_quickview a.tb_no_text .wait {
  <?php if ($lang_dir == 'ltr'): ?>
  left: 50%;
  <?php else: ?>
  margin-right: -7px;
  <?php endif; ?>
}
.tb_button_quickview a.tb_icon_10 .wait {
  width:  14px;
  height: 14px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin: -7px 0 0 -2px;
  <?php else: ?>
  margin: -7px -2px 0 0;
  <?php endif; ?>
}
.tb_button_quickview a.tb_icon_10.tb_no_text .wait {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -7px;
  <?php else: ?>
  margin-right: -7px;
  <?php endif; ?>
}
.tb_button_quickview a.tb_icon_16 .wait {
  width:  18px;
  height: 18px;
  margin: -9px 0 0 -1px;
}
.tb_button_quickview a.tb_icon_16.tb_no_text .wait {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -9px;
  <?php else: ?>
  margin-right: -9px;
  <?php endif; ?>
}
.tb_button_quickview a.tb_icon_24 .wait {
  width:  22px;
  height: 22px;
  margin: -11px 0 0 0;
}
.tb_button_quickview a.tb_icon_24.tb_no_text .wait {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -11px;
  <?php else: ?>
  margin-right: -11px;
  <?php endif; ?>
}
.tb_button_quickview a.tb_icon_10 .wait,
.tb_button_quickview a.tb_icon_10 .wait:before,
.tb_button_quickview a.tb_icon_10 .wait:after,
.tb_button_quickview a.tb_icon_16 .wait,
.tb_button_quickview a.tb_icon_16 .wait:before,
.tb_button_quickview a.tb_icon_16 .wait:after
{
  border-width: 1px;
}
.tb_quickview_loading .tb_button_quickview a[class*="tb_icon"]:before {
  opacity: 0;
}
