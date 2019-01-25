/*  -----------------------------------------------------------------------------------------
    T Y P E
-----------------------------------------------------------------------------------------  */

[class*="ico-mdi"]:before {
  margin-top:     0.06em;
  margin-bottom: -0.06em;
}
.ico-mdi-shopping:before,
.ico-mdi-basket:before,
.ico-mdi-car:before
{
  margin-top:    0;
  margin-bottom: 0;
}
.ico-mdi-cash-multiple:before {
  margin-top:   -0.07em;
  margin-bottom: 0.07em;
}

/*  -----------------------------------------------------------------------------------------
    H E A D E R
-----------------------------------------------------------------------------------------  */

.tbSticky {
  border-radius: 0 0 6px 6px;
  box-shadow:
    0 1px 0 rgba(0, 0, 0, 0.05),
    0 0 10px rgba(0, 0, 0, 0.1);
}
.tb_wt_header_search_system.tb_style_2 .tb_search_wrap,
.tb_wt_header_search_system.tb_style_3 .tb_search_wrap
{
  padding-left:  0;
  padding-right: 0;
}
.tb_wt_header_search_system.tb_style_2 .tb_search_button,
.tb_wt_header_search_system.tb_style_3 .tb_search_button
{
  margin-left:  0;
  margin-right: 0;
}

.tbSticky .tbMainCategories .nav:not(.nav-tabs) > li > a {
  padding-left:  0 !important;
  padding-right: 0 !important;
}

@media (max-width: 1000px) {
  .tbMainNavigation .nav-justified > li {
        -ms-flex: 0 0 auto !important;
    -webkit-flex: 0 0 auto !important;
            flex: 0 0 auto !important;
  }
}

/*  -----------------------------------------------------------------------------------------
    F O O T E R
-----------------------------------------------------------------------------------------  */

#bottom .col-md-auto {
  flex: 1 1 auto;
}
#copy {
  text-align: center;
}
#copy br {
  display: none;
}

/*  -----------------------------------------------------------------------------------------
    C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

/*  Breadcrumbs   ------------------------------------------------------------------------ */

.breadcrumb li:last-child:not(:nth-child(2)):not(:nth-child(3)) a {
  display: none;
}

/*  Common   ----------------------------------------------------------------------------- */

.tb_no_text > span:before,
.tb_no_text > span:after
{
  box-shadow: none;
  border: none;
}

/*  Forms   ------------------------------------------------------------------------------ */

input[type=text],
input[type=number],
input[type=email],
input[type=tel],
input[type=date],
input[type=datetime],
input[type=color],
input[type=password],
input[type=search],
select,
textarea,
select.form-control,
.form-control,
.input-group
{
  width: 300px;
  transition: color 0.3s, border 0.3s, background 0.3s;
}
.btn {
  box-shadow: none;
}

/*  Dropdowns   -------------------------------------------------------------------------- */

.dropdown-menu {
  margin-left: 0;
  margin-right: 0;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
}
.nav-justified-dropdown > .dropdown:not(.tb_megamenu) > .dropdown-menu {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0 !important;
  <?php else: ?>
  margin-right: 0 !important;
  <?php endif; ?>
}
.dropdown-toggle ~ .dropdown-menu {
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
}

/*** Level 1 ***/

.dropdown-menu {
  -webkit-transition: opacity 0.3s, transform 0.3s;
          transition: opacity 0.3s, transform 0.3s;
}
.dropdown:after {
  content: none !important;
}

/*** Inner level ***/

.dropdown-menu .dropdown:not(.tb_hovered):hover > .dropdown-menu,
.nav-stacked   .dropdown:not(.tb_hovered):hover > .dropdown-menu,
.tb_list_1:not(dropdown-menu) > li.dropdown:not(.tb_hovered):hover > .dropdown-menu
{
  <?php if ($lang_dir == 'ltr'): ?>
  transform: translateX(20px);
  <?php else: ?>
  transform: translateX(-20px);
  <?php endif; ?>
}
.dropdown-menu .tb_hovered > .dropdown-menu,
.nav-stacked   .tb_hovered > .dropdown-menu,
.tb_list_1:not(dropdown-menu) > li.tb_hovered > .dropdown-menu
{
  transform: translateX(0);
}

.tbMainNavigation nav > ul > li {
  padding-bottom: 0 !important;
  margin-bottom:  0 !important;
}
.tbMainNavigation nav > ul > li:hover > a,
.tbMainNavigation nav > ul > li.tb_hovered > a
{
  z-index: 51;
}
.tbMainNavigation nav > ul > li:hover > a,
.tbMainNavigation nav > ul > li.tb_hovered > a
{
  z-index: 51;
}
.tbMainNavigation nav > ul > li > .tb_toggle
{
  z-index: 52 !important;
}

/*  Listing   ---------------------------------------------------------------------------- */

.tb_grid_view .product-thumb,
.tb_grid_view .product-thumb .button-group
{
  text-align: initial;
}
.tb_grid_view .product-thumb .caption * {
            -ms-flex-pack: start;
  -webkit-justify-content: flex-start;
          justify-content: flex-start;
}
.tb_grid_view .product-thumb .rating {
  margin-left:  0;
  margin-right: 0;
}
.product-thumb .row .button-group {
  margin-top: -10px !important;
}
.product-thumb .row .tb_button_add_to_cart {
  margin-top: 0;
}
.product-thumb .row .price-old {
  font-size: 80%;
}

.tb_list_view .product-thumb .image + div {
  -ms-flex-item-align: center;
   -webkit-align-self: center;
           align-self: center;
}
.tb_grid_view .tb_style_1 .caption ~ .row .col-sm-fill {
      -ms-flex: 1 0 auto;
  -webkit-flex: 1 0 auto;
          flex: 1 0 auto;
}

.tb_compact_view h4,
.tb_compact_view .name
{
  font-size: 14px;
}

.tb_label_special,
.tb_label_new
{
  text-shadow: none;
}
.tbMediumSaleLabel .tb_label_special,
.tbBigSaleLabel .tb_label_special
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
  text-align: center;
  border-radius: 50%;
  -webkit-transform: rotate(-15deg);
          transform: rotate(-15deg);
}
.tbMediumSaleLabel .tb_label_special {
  width: 60px;
  height: 60px;
  line-height: <?php echo $tbData->calculateLineHeight(18, $base); ?>px;
  font-size: 18px;
}
.tbBigSaleLabel .tb_label_special
{
  width: 80px;
  height: 80px;
  line-height: <?php echo $tbData->calculateLineHeight(24, $base); ?>px;
  font-size: 24px;
}

/*  Modal / Dialog   --------------------------------------------------------------------- */

.modal-content {
  box-shadow: 0 0 40px rgba(0,0,0,0.15);
}

/*  Tooltip   ---------------------------------------------------------------------------- */

.tooltip-inner {
  font-size: 13px;
}

/*  -----------------------------------------------------------------------------------------
    S Y S T E M   B L O C K S
-----------------------------------------------------------------------------------------  */

#cart .heading {
  position: relative;
}
#cart .heading .tb_icon {
  margin: 0 !important;
}
#cart .heading .tb_icon + .tb_items {
  position: absolute;
  left: 18px;
  top: -8px;
  width:  16px;
  height: 16px;
  margin: 0;
  line-height: 16px !important;
  text-align: center;
  font-weight: normal !important;
  font-size: 10px !important;
  color: #fff !important;
  background: #333;
  border-radius: 50%;
  opacity: 1 !important;
}
#cart .heading .tb_icon + .tb_items:before,
#cart .heading .tb_icon + .tb_items:after
{
  content: none !important;
}
#cart .heading .tb_items + .tb_total {
  border: none !important;
  padding: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    S L I D E R   /   C A R O U S E L
-----------------------------------------------------------------------------------------  */

#wrapper .has_slider.tb_side_nav .tb_grid_view.tb_multiline:not(.tb_slider),
#wrapper .has_slider.tb_side_nav .tb_grid_view.tb_style_bordered.tb_multiline,
#wrapper :not(.panel-body) > .has_slider.tb_side_nav.no_title .tb_grid_view.tb_multiline:not(.tb_slider),
#wrapper .tb_side_nav .tb_slider
{
  margin-left:   0 !important;
  margin-right:  0 !important;
  padding-left:  0 !important;
  padding-right: 0 !important;
}
<?php foreach ($grid_gutter as $gutter): ?>
#wrapper .has_slider.tb_side_nav .tb_grid_view.tb_gut_<?php echo $gutter; ?>.tb_multiline:not(.tb_slider):not(.tb_style_bordered) {
  margin-left:  -<?php echo $gutter; ?>px !important;
}
<?php endforeach; ?>
.tb_side_nav .tb_slider_controls {
  position: absolute;
  z-index: auto;
  top: 0;
  left: 0;
  overflow: hidden;
  width: 100%;
  height: 100%;
}
.tb_side_nav .tb_next,
.tb_side_nav .tb_prev
{
  z-index: 3;
  top: 50%;
  width: <?php echo $base * 2; ?>px;
  height: <?php echo $base * 3; ?>px;
  margin-top: -<?php echo $base * 1.5; ?>px !important;
  margin-left:  0 !important;
  margin-right: 0 !important;
  line-height: <?php echo $base * 3; ?>px;
  color: #fff !important;
  background-color: rgba(0,0,0,0.3);
  opacity: 0;
  -webkit-transition: all 0.2s;
          transition: all 0.2s;
}
.tb_side_nav:hover .tb_next,
.tb_side_nav:hover .tb_prev
{
  opacity: 1;
}
.tb_side_nav .tb_next:hover,
.tb_side_nav .tb_prev:hover
{
  background-color: rgba(0,0,0,0.5);
}
.tb_side_nav .tb_next {
  right: -60px;
}
.tb_side_nav .tb_prev {
  left: -60px;
}
.tb_side_nav:hover .tb_next {
  right: 0;
}
.tb_side_nav:hover .tb_prev {
  left: 0;
}

/*  -----------------------------------------------------------------------------------------
    S T O R I E S
-----------------------------------------------------------------------------------------  */

.tb_article .tb_meta + .tb_text_wrap {
  margin-top: 0;
  padding-top: 0;
}
.tb_article .tb_meta + .tb_text_wrap:after {
  content: none;
}
.tb_grid_view .tb_article > * {
  -ms-flex-order: 10;
   -webkit-order: 10;
           order: 10;
}
.tb_grid_view .tb_article > .thumbnail {
  -ms-flex-order: 1;
   -webkit-order: 1;
           order: 1;
}

/*  -----------------------------------------------------------------------------------------
    M O B I L E
-----------------------------------------------------------------------------------------  */

@media (max-width: 768px) {
  #wrapper {
    overflow: visible;
    margin-top: 40px;
  }
}

