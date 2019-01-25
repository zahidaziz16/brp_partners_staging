/*  -----------------------------------------------------------------------------------------
    H E A D E R
-----------------------------------------------------------------------------------------  */

/*  ---   Cart   ------------------------------------------------------------------------  */

#cart .heading {
  white-space: nowrap;
  font-size: inherit;
}
#cart .heading,
#cart .heading *
{
  margin-bottom: 0;
}
#cart .heading > a,
#cart .heading > a > *
{
  display: inline-block;
  vertical-align: top;
}
#cart .heading > a {
  letter-spacing: 0;
  word-spacing: 0;
}
#cart .heading > a > * {
  line-height: inherit !important;
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
}
#cart .heading .tb_icon {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.3em;
  <?php else: ?>
  margin-left: 0.3em;
  <?php endif; ?>
}
#cart .heading .tb_label {
  text-transform: uppercase;
}
#cart .heading .tb_items {
  opacity: 0.7;
}
#cart .heading .tb_items:before {
  content: '(';
}
#cart .heading .tb_items:after {
  content: ')';
}
#cart .heading .tb_label + .tb_total,
#cart .heading .tb_items + .tb_total
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 0.5em;
  padding-left: 0.5em;
  border-left-width: 1px;
  border-left-style: solid;
  <?php else: ?>
  margin-right: 0.5em;
  padding-right: 0.5em;
  border-right-width: 1px;
  border-right-style: solid;
  <?php endif; ?>
}
#cart .heading .tb_total {
  font-size: 18px;
  border-bottom: none !important;
}
#cart h4 {
  margin-bottom: 0;
}
#cart > a {
  display: inline-block;
  color: #fff;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
  vertical-align: top;
}
#cart > a > * {
  display: block;
}
#cart > a > :first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -5px;
  <?php else: ?>
  margin-right: -5px;
  <?php endif; ?>
}
#cart > a > .tb_label {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  line-height: 15px;
}
#cart .tb_items_count {
  display: block;
  font-size: 11px;
}
#cart .tb_grand_total {
  <?php if ($lang_dir == 'ltr'): ?>
  float: right;
  padding-left: 15px;
  <?php else: ?>
  float: left;
  padding-right: 15px;
  <?php endif; ?>
  line-height: 30px;
  font-size: 18px;
}
#cart .tb_grand_total:last-child {
  padding: 0;
}
#cart .dropdown-menu {
  width: 400px;
}
#cart .dropdown-menu h3 {
  margin-bottom: <?php echo $base; ?>px;
}
#cart.tb_hovered .dropdown-menu {
  display: block;
}
#cart .buttons {
  margin-top: <?php echo $base; ?>px;
  padding-top: <?php echo $base; ?>px;
  text-align: center;
}
#cart .btn {
  text-transform: none;
}

/*  ---   Currency   --------------------------------------------------------------------  */

.tb_wt_header_currency_menu_system li:not(:last-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_wt_header_currency_menu_system li img {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 3px;
  <?php else: ?>
  margin-left: 3px;
  <?php endif; ?>
}
.tb_wt_header_currency_menu_system .dropdown-menu {
  min-width: 120px;
}

/*  ---   Language   --------------------------------------------------------------------  */

.tb_wt_header_language_menu_system li {
  text-transform: capitalize;
  white-space: nowrap;
}
.tb_wt_header_language_menu_system li:not(:last-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_wt_header_language_menu_system li img {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 3px;
  <?php else: ?>
  margin-left: 3px;
  <?php endif; ?>
}
.tb_wt_header_language_menu_system.tb_code li {
  text-transform: uppercase;
}
.tb_wt_header_language_menu_system .dropdown-menu {
  min-width: 120px;
}

/*  ---   Logo   ------------------------------------------------------------------------  */

<?php $logo = $tbData['system.logo']; ?>

#site_logo {
  display: block;
}
#site_logo img {
  max-width:  <?php echo $width; ?>px;
  max-height: 500px;
  -webkit-transition: all 1.3s;
          transition: all 1.3s;
}

<?php if (!empty($logo['max_width_xs']) || !empty($logo['max_height_xs'])): ?>
@media (max-width: <?php echo $screen_xs . 'px'; ?>) {
  #site_logo img {
    <?php if (!empty($logo['max_width_xs'] )): ?>max-width: <?php  echo $logo['max_width_xs'];  ?>px;<?php endif; ?>
    <?php if (!empty($logo['max_height_xs'])): ?>max-height: <?php echo $logo['max_height_xs']; ?>px;<?php endif; ?>
  }
}
<?php endif; ?>
<?php if (!empty($logo['max_width_sm'] ) || !empty($logo['max_height_sm'])): ?>
@media (min-width: <?php echo ($screen_xs + 1) . 'px'; ?>) and (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #site_logo img {
    <?php if (!empty($logo['max_width_sm'] )): ?>max-width: <?php  echo $logo['max_width_sm'];  ?>px;<?php endif; ?>
    <?php if (!empty($logo['max_height_sm'])): ?>max-height: <?php echo $logo['max_height_sm']; ?>px;<?php endif; ?>
  }
}
<?php endif; ?>
<?php if (!empty($logo['max_width_md'] ) || !empty($logo['max_height_md'])): ?>
@media (min-width: <?php echo ($screen_sm + 1) . 'px'; ?>) and (max-width: <?php echo $screen_md . 'px'; ?>) {
  #site_logo img {
    <?php if (!empty($logo['max_width_md'] )): ?>max-width: <?php  echo $logo['max_width_md'];  ?>px;<?php endif; ?>
    <?php if (!empty($logo['max_height_md'])): ?>max-height: <?php echo $logo['max_height_md']; ?>px;<?php endif; ?>
  }
}
<?php endif; ?>
<?php if (!empty($logo['max_width_lg'] ) || !empty($logo['max_height_lg'])): ?>
@media (min-width: <?php echo ($screen_md + 1) . 'px'; ?>) {
  #site_logo img {
    <?php if (!empty($logo['max_width_lg'] )): ?>max-width: <?php  echo $logo['max_width_lg'];  ?>px;<?php endif; ?>
    <?php if (!empty($logo['max_height_lg'])): ?>max-height: <?php echo $logo['max_height_lg']; ?>px;<?php endif; ?>
  }
}
<?php endif; ?>

<?php if (!empty($logo['max_width_sticky'] ) || !empty($logo['max_height_sticky'])): ?>
.tbStickyScrolled #site_logo img,
.tbStickyRow #site_logo img
{
  <?php if (!empty($logo['max_width_sticky'] )): ?>max-width: <?php  echo $logo['max_width_sticky'];  ?>px;<?php endif; ?>
  <?php if (!empty($logo['max_height_sticky'])): ?>max-height: <?php echo $logo['max_height_sticky']; ?>px;<?php endif; ?>
}
<?php endif; ?>


/*  ---   Search   ----------------------------------------------------------------------  */

.tb_wt_header_search_system .tb_search_wrap {
  position: relative;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.tb_wt_header_search_system .tb_search_wrap > * {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  min-width: 0;
}
.tb_wt_header_search_system.tb_style_2 .tb_search_wrap,
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap
{
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: 10px;
  <?php else: ?>
  padding-left: 10px;
  <?php endif; ?>
}
.tb_wt_header_search_system input {
  float: none;
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}
.tb_wt_header_search_system.tb_style_3 input {
  text-indent: <?php echo $base * 1.25; ?>px;
}
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap.form-group-sm input {
  text-indent: <?php echo $base; ?>px;
}
.tb_wt_header_search_system .tb_search_button,
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap:before
{
  display: block;
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.tb_wt_header_search_system.tb_style_1 .tb_search_button,
.tb_wt_header_search_system.tb_style_4 .tb_search_wrap > input,
.tb_wt_header_search_system.tb_style_4 .tb_search_wrap > .twitter-typeahead
{
  position: absolute !important;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  right: 0;
  <?php else: ?>
  left: 0;
  <?php endif; ?>
  margin-left: 0;
  margin-right: 0;
}
.tb_wt_header_search_system.tb_style_1 .tb_search_button,
.tb_wt_header_search_system.tb_style_2 .tb_search_button
{
  padding-left: 0;
  padding-right: 0;
}
body #wrapper .tb_wt_header_search_system.tb_style_1 .tb_search_button[class],
body #wrapper .tb_wt_header_search_system.tb_style_1 .tb_search_button[class]:hover
{
  background: none transparent !important;
  box-shadow: none !important;
  border: none !important;
}
.tb_wt_header_search_system.tb_style_2 .tb_search_button,
.tb_wt_header_search_system.tb_style_3 .tb_search_button
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 10px;
  margin-right: -10px;
  <?php else: ?>
  margin-left: -10px;
  margin-right: 10px;
  <?php endif; ?>
}
.tb_wt_header_search_system.tb_style_3 .tb_search_button {
  width: auto !important;
  padding-right: 1em !important;
  padding-left: 1em !important;
}
.tb_wt_header_search_system.tb_style_4 .tb_search_button {
  box-shadow: none;
}
.tb_wt_header_search_system.tb_style_1 .tb_search_button:before,
.tb_wt_header_search_system.tb_style_2 .tb_search_button:before,
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap:before,
.tb_wt_header_search_system.tb_style_4 .tb_search_button:before
{
  vertical-align: top;
  letter-spacing: 0;
  word-spacing: 0;
}
.tb_wt_header_search_system.tb_style_3 .tb_search_button:before {
  content: attr(title);
}
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap:before {
  position: absolute;
  z-index: 1;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
  width:       <?php echo $form_control_height; ?>px;
  line-height: <?php echo $form_control_height; ?>px;
  text-align: center;
}
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap.form-group-sm:before {
  width:       <?php echo $form_control_height_sm; ?>px;
  margin-top:  <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $form_control_height_sm) / 2; ?>px;
  line-height: <?php echo $form_control_height_sm; ?>px;
}
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap.form-group-lg:before {
  width:       <?php echo $form_control_height_lg; ?>px;
  margin-top:  <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $form_control_height_lg) / 2; ?>px;
  line-height: <?php echo $form_control_height_lg; ?>px;
}
.tb_wt_header_search_system.tb_style_4 .tb_search_button:before,
.tb_wt_header_search_system.tb_style_4 .tb_search_wrap > input
{
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
  -webkit-transition-delay: 0.5s;
          transition-delay: 0.5s;
}
.tb_wt_header_search_system.tb_style_4:hover .tb_search_button:before,
.tb_wt_header_search_system.tb_style_4:hover .tb_search_wrap > input
{
  -webkit-transition-delay: 0s;
          transition-delay: 0s;
}
.tb_wt_header_search_system.tb_style_4 .tb_search_wrap > input {
  width: 100%;
  max-width: none;
  opacity: 0;
}
.tb_wt_header_search_system.tb_style_4:hover .tb_search_wrap > input {
  opacity: 1;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .tb_wt_header_search_system .tb_search_button,
  .tb_wt_header_search_system.tb_style_3 .tb_search_wrap:before
  {
    width: <?php echo $base * 2; ?>px !important;
    height: <?php echo $base * 2; ?>px;
    line-height: <?php echo $base * 2; ?>px !important;
  }
  .tb_wt_header_search_system .tb_search_button {
    font-size: 1.1em;
  }
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   C A T E G O R Y
-----------------------------------------------------------------------------------------  */

/*  ---   Subcategories   ---------------------------------------------------------------  */

.tb_subcategory {
  position: relative;
  overflow: hidden;
  display: block !important;
}
.tb_subcategory .thumbnail {
  max-width: 100%;
}
.tb_subcategory h3,
.tb_subcategory ul
{
  overflow: hidden;
}
.tb_subcategory h3 {
  margin: 0;
}
.tb_subcategory h3 + ul {
  padding-top: <?php echo $base * 0.5; ?>px;
}
.tb_subcategories.tb_grid_view .tb_subcategory {
  text-align: inherit;
}
.tb_subcategories.tb_cstyle_1 .tb_subcategory .thumbnail {
  margin-bottom: <?php echo $base; ?>px;
}
.tb_subcategories.tb_image_top .tb_subcategory .thumbnail {
  float: none;
}
.tb_subcategories.tb_image_left .tb_subcategory .thumbnail {
  float: left;
  margin-right: <?php echo $base; ?>px;
  margin-bottom: 0;
}
.tb_subcategories.tb_image_right .tb_subcategory .thumbnail {
  float: right;
  margin-left: <?php echo $base; ?>px;
  margin-bottom: 0;
}
.tb_subcategories.tb_cstyle_4 > *,
.tb_subcategories.tb_cstyle_5 > *
{
  overflow: hidden;
}
.tb_subcategories.tb_cstyle_4 .thumbnail,
.tb_subcategories.tb_cstyle_5 .thumbnail
{
  float: none;
  margin-bottom: 0;
}
.tb_subcategories.tb_cstyle_4 h3,
.tb_subcategories.tb_cstyle_5 h3
{
  z-index: 2;
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  font-size: 15px;
}
.tb_subcategories.tb_cstyle_4 .tb_subcategory h3 a,
.tb_subcategories.tb_cstyle_5 .tb_subcategory h3 a
{
  display: block;
  padding: 0.8em 1.2em;
}
.tb_subcategories.tb_cstyle_4 .tb_subcategory h3 {
  background: rgba(255, 255, 255, 0.7);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.12);
}
#intro [class] .tb_subcategories.tb_cstyle_4 .tb_subcategory h3 a,
#content [class] .tb_subcategories.tb_cstyle_4 .tb_subcategory h3 a
{
  color: #333 !important;
}
#intro [class] .tb_subcategories.tb_cstyle_4 .tb_subcategory:hover h3 a,
#intro [class] .tb_subcategories.tb_cstyle_4 .tb_subcategory h3:hover a,
#content [class] .tb_subcategories.tb_cstyle_4 .tb_subcategory:hover h3 a,
#content [class] .tb_subcategories.tb_cstyle_4 .tb_subcategory h3:hover a
{
  color: #fff !important;
  background: #333;
}
.tb_subcategories.tb_cstyle_5 .tb_subcategory h3 {
  background: rgba(0, 0, 0, 0.5);
}
#intro [class] .tb_subcategories.tb_cstyle_5 .tb_subcategory h3 a,
#content [class] .tb_subcategories.tb_cstyle_5 .tb_subcategory h3 a
{
  color: #fff !important;
}
#intro [class] .tb_subcategories.tb_cstyle_5 .tb_subcategory:hover h3 a,
#intro [class] .tb_subcategories.tb_cstyle_5 .tb_subcategory h3:hover a,
#content [class] .tb_subcategories.tb_cstyle_5 .tb_subcategory:hover h3 a,
#content [class] .tb_subcategories.tb_cstyle_5 .tb_subcategory h3:hover a
{
  color: #000 !important;
  background: rgba(255, 255, 255, 0.8) !important;
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   P A G E
-----------------------------------------------------------------------------------------  */

/*  Add to cart   -----------------------------------------------------------------------  */

.tb_wt_product_add_to_cart_system .tb_cart_wrap,
.tb_wt_product_add_to_cart_system .tb_purchase_button,
.tb_wt_product_add_to_cart_system .tb_actions
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
.tb_wt_product_add_to_cart_system .tb_cart_wrap,
.tb_wt_product_add_to_cart_system .tb_purchase_button
{
  margin-top: -<?php echo $base * 0.5; ?>px;
}
.tb_wt_product_add_to_cart_system .tb_cart_wrap > *,
.tb_wt_product_add_to_cart_system .tb_purchase_button > *
{
  min-width: 0;
  margin-top: <?php echo $base * 0.5; ?>px !important;
  vertical-align: middle;
}
.tb_wt_product_add_to_cart_system .tb_cart_wrap {
  margin-right: -20px;
}
.tb_wt_product_add_to_cart_system .tb_cart_wrap > * {
  margin-right: 20px;
}
.tb_wt_product_add_to_cart_system .tb_purchase_button {
      -ms-flex: 0 1 auto;
  -webkit-flex: 0 1 auto;
          flex: 0 1 auto;
  margin-top: 0 !important;
  margin-left: -10px;
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
}
.tb_wt_product_add_to_cart_system .tb_purchase_button > * {
  margin-left: 10px;
}
.tb_wt_product_add_to_cart_system .tb_purchase_button > .btn {
  min-width: 140px;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}
.tb_wt_product_add_to_cart_system .tb_purchase_button br {
  display: none !important;
}
.tb_wt_product_add_to_cart_system .tb_input_wrap {
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
  min-width: 80px;
}
.tb_wt_product_add_to_cart_system #product_buy_quantity,
.tb_wt_product_add_to_cart_system #input-quantity,
.tb_wt_product_add_to_cart_system .ui-spinner,
.tb_wt_product_add_to_cart_system .input-group
{
  display: -ms-inline-flexbox !important;
  display: -webkit-inline-flex !important;
  display: inline-flex !important;
  width: 100%;
  height: <?php echo $submit_button_height; ?>px;
  font-size: 16px;
}
.tb_wt_product_add_to_cart_system .input-group {
  margin-top: 0;
  margin-bottom: 0;
}
.tb_wt_product_add_to_cart_system .tb_purchase_button label {
  display: none !important;
  width: auto;
  height: 30px;
  line-height: 30px;
}
.tb_wt_product_add_to_cart_system .tb_purchase_button input[type="text"],
.tb_wt_product_add_to_cart_system .tb_purchase_button input[type="number"]
{
  text-align: center;
  font-size: 16px;
}
.tb_wt_product_add_to_cart_system .tb_purchase_button .ui-spinner .btn,
.tb_wt_product_add_to_cart_system .tb_purchase_button .bootstrap-touchspin .btn
{
  line-height: <?php echo $submit_button_height * 0.5 - 1; ?>px;
}
@media (max-width: <?php echo $screen_xs; ?>px) {
  .tb_wt_product_add_to_cart_system .tb_purchase_button .tb_button_add_to_cart {
    margin-right: 0;
    margin-left: 0;
  }
}
.tb_wt_product_add_to_cart_system .tb_actions {
      -ms-flex: 1 1 150px;
  -webkit-flex: 1 1 150px;
          flex: 1 1 150px;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.tb_wt_product_add_to_cart_system .tb_actions > * {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left:  <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_wt_product_add_to_cart_system .minimum {
  clear: both;
  padding-top: <?php echo $base * 0.5; ?>px;
  text-align: center;
  font-size: 11px;
}


/*  Discount   --------------------------------------------------------------------------  */

.tb_wt_product_discounts_system table:not([class]) {
  width: auto;
}
.tb_wt_product_discounts_system table:not([class]) thead {
  display: none;
}
.tb_wt_product_discounts_system table:not([class]) td:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  padding-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}


/*  ---   Price   -----------------------------------------------------------------------  */

.tb_wt_product_price_system .price {
  line-height: inherit !important;
}
.tb_wt_product_price_system .price > * {
  vertical-align: top;
}
.tb_wt_product_price_system .price-regular,
.tb_wt_product_price_system .price-new
{
  line-height: <?php echo $tbData->calculateLineHeight(24, $base); ?>px;
  font-size: 24px;
}
.tb_wt_product_price_system .price-old {
  line-height: <?php echo $tbData->calculateLineHeight(16, $base); ?>px;
  font-size: 16px;
}
.tb_wt_product_price_system .price-tax,
.tb_wt_product_price_system .reward
{
  font-size: <?php echo $base_font_size - 2; ?>px;
  display: block;
}
.tb_wt_product_price_system .price-savings {
  display: inline-block;
  margin-top: 0.4em;
  margin-bottom: 0;
  padding: 0 0.5em;
}

@media (max-width: <?php echo $screen_xs; ?>px) {
  .tb_wt_product_price_system {
    margin-bottom: <?php echo $base; ?>px;
  }
  .tb_wt_product_price_system,
  .tb_wt_product_price_system *
  {
    text-align: inherit;
  }
}

/*  ---   Review   ----------------------------------------------------------------------  */

.tbReviewForm input:not([type="radio"]):not([name="captcha"]),
.tbReviewForm textarea
{
  width: 100%;
}

/*  ---   Share   -----------------------------------------------------------------------  */

.tb_share_box,
.tb_share_box *:not(script)
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  -webkit-transition: none;
          transition: none;
}
.tb_share_box {
  margin-top: -<?php echo $base / 2; ?>px;
  margin-left: -<?php echo $base / 2; ?>px;
  margin-right: -<?php echo $base / 2; ?>px;
  padding-right: <?php echo $base / 2; ?>px;
}
.tb_share_box > * {
  display: inline-block;
  vertical-align: top;
  margin-top: <?php echo $base / 2; ?>px;
  margin-left: <?php echo $base / 2; ?>px;
}
.tb_share_box > * > *,
.tb_share_box > * > *[class],
.tb_share_box .tb_facebook > a > span
{
  vertical-align: top !important;
}

/*** Stumbleupon ***/

.tb_share_box .tb_stumbleupon iframe {
  margin-top: 1px !important;
}

/*** Linkedin ***/

.tb_share_box .tb_linkedin > span {
  height: 20px;
}
.tb_share_box .tb_linkedin > span > span:first-child > span > a > span > span {
  margin-top: -1px !important;
}
.tb_share_box .tb_linkedin > span > span:first-child + span > span > span {
  margin-top: -2px !important;
}

/*** Plusone ***/

.tb_share_box .tb_plusone,
.tb_share_box .tb_plusone div {
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  width: 70px !important;
}
.tb_share_box .tb_plusone iframe {
  width: 70px !important;
}
.tb_share_box .addthis_counter {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 10px;
  <?php else: ?>
  margin-left: 10px;
  <?php endif; ?>
}
.tb_share_box .addthis_toolbox > * {
  padding: 0;
}
.tb_share_box .addthis_toolbox > *,
.tb_share_box .addthis_toolbox > * + *
{
  display: inline-block;
  vertical-align: top;
}
