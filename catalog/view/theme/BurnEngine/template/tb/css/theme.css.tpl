.row-wrap {
  background-origin: border-box !important;
}

#header {
  position: relative;
  z-index: 50;
}
#header.tb_header_visible {
  margin-bottom: 0;
}
#header.tb_header_overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
}
#header.tb_header_transparent:not(.tbStickyScrolled),
#header.tb_header_transparent:not(.tbStickyScrolled) > .row-wrap,
#header.tb_header_transparent:not(.tbStickyScrolled) > .row-wrap > .row,
#header.tb_header_transparent:not(.tbStickyScrolled) > .row-wrap > .row > .col
{
  background: transparent none !important;
  box-shadow: none !important;
}
#bottom > .row,
#bottom > .row > .col,
#bottom > .row > .col > .row-wrap
{
  border-bottom-left-radius: inherit;
  border-bottom-right-radius: inherit;
}


.tb_grid_view .price .tb_decimal_point,
.tb_list_view .price .tb_decimal_point,
.tb_price.tb_fancy .tb_decimal_point,
.price.custom .tb_decimal_point
{
  display: none;
}
.tb_grid_view .price .tb_decimal,
.tb_price.tb_fancy .tb_decimal,
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

.main > .row > .col,
.sidebar > .row > .col
{
  display: block;
}

/*** Content Wrap ***/

#content > .row > .col {
  display: block;
}
@media (min-width: <?php echo ($screen_sm + 1); ?>px) {
  .main {
    order: 2;
  }
  #left_col {
    order: 1;
  }
  #right_col {
    order: 3;
  }
}
.row.tb_separate_columns > #left_col {
  <?php if ($lang_dir == 'ltr'): ?>
  border-left-style: none;
  border-left-width: 0;
  border-right-style: solid;
  border-right-width: 1px;
  <?php else: ?>
  border-right-style: none;
  border-right-width: 0;
  border-left-style: solid;
  border-left-width: 1px;
  <?php endif; ?>
}
.row.tb_separate_columns > #right_col {
  <?php if ($lang_dir == 'ltr'): ?>
  border-right-style: none;
  border-right-width: 0;
  border-left-style: solid;
  border-left-width: 1px;
  <?php else: ?>
  border-left-style: none;
  border-left-width: 0;
  border-right-style: solid;
  border-right-width: 1px;
  <?php endif; ?>
}

/*  -----------------------------------------------------------------------------------------
    B O T T O M   A R E A   ( S T A T I C   F O O T E R )
-----------------------------------------------------------------------------------------  */

#copy {
  font-size: 11px;
}
#payment_images {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: right;
  <?php else: ?>
  text-align: left;
  <?php endif; ?>
  word-spacing: -0.25em;
}
#payment_images .tb_payment {
  display: inline-block;
  word-spacing: normal;
  vertical-align: middle;
}
#payment_images .tb_payment img {
  display: inline-block;
  vertical-align: top;
}
#payment_images .tb_payment table {
  width: auto;
}
#payment_images .tb_payment + .tb_payment {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}


/*  -------------------------------------------------------------------------------------  */
/*  ---  Mobile Layout                          -----------------------------------------  */
/*  -------------------------------------------------------------------------------------  */
/*  ---  Max width: 767px                       -----------------------------------------  */

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {

  #copy,
  #payment_images
  {
    display: block;
    width: 100%;
    text-align: center;
  }
}

/*  -----------------------------------------------------------------------------------------
    L A Z Y   L O A D I N G
-----------------------------------------------------------------------------------------  */

img[data-src] {
  -webkit-transition: opacity 0.3s;
          transition: opacity 0.3s;
}
img[data-src].lazyload:not(.lazyloaded) {
  opacity: 0;
}

/*  -----------------------------------------------------------------------------------------
    M E N U
-----------------------------------------------------------------------------------------  */

body.is_logged .tb_menu_system_account_login,
body:not(.is_logged) .tb_menu_system_account_logout,
body.is_affiliate_logged .tb_menu_system_affiliate_login,
body:not(.is_affiliate_logged) .tb_menu_system_affiliate_logout
{
  display: none;
}

/*  Megamenu   --------------------------------------------------------------------------  */

.tb_text > .tb_icon {
  min-width: 1.5em;
  margin-left: 0;
  margin-right: 0;
}
.tb_text > .tb_icon > i {
  vertical-align: top;
}
[dir="ltr"] .tb_text > img.tb_icon,
[dir="ltr"] .tb_text > .tb_icon > i
{
  margin-left: 0 !important;
}
[dir="rtl"] .tb_text > img.tb_icon,
[dir="rtl"] .tb_text > .tb_icon > i
{
  margin-right: 0 !important;
}

.dropdown ul.tb_grid_view > li,
.tb_menu_all_categories .tb_subcategories > .tb_listing > .tb_menu_category > a
{
  display: block;
}
.dropdown .tb_menu_category > .thumbnail,
.dropdown .tb_subcategories > ul > li > .thumbnail
{
  <?php if ($lang_dir == 'ltr'): ?>
  float: right;
  <?php else: ?>
  float: left;
  <?php endif; ?>
  margin-bottom: 0;
}
.dropdown .tb_menu_category > .thumbnail:first-child,
.dropdown .tb_menu_category > .tb_toggle + .thumbnail,
.dropdown .tb_subcategories > ul > li > .thumbnail:first-child,
.dropdown .tb_subcategories > ul > li > .tb_toggle + .thumbnail
{
  float: none;
  margin-bottom: <?php echo $base * 0.5; ?>px;
}
.dropdown .tb_subcategories > .tb_grid {
  margin-top:  -20px;
  margin-left: -20px;
}
.dropdown .tb_subcategories > .tb_grid > li,
.dropdown .tb_subcategories > .tb_grid > div
{
      -ms-flex: 1 1 220px;
  -webkit-flex: 1 1 220px;
          flex: 1 1 220px;
  margin-top:   20px;
  padding-left: 20px;
}
.dropdown .tb_subcategories > .tb_multicolumn {
  -webkit-column-width: 180px;
     -moz-column-width: 180px;
          column-width: 180px;
  margin-top: -<?php echo $base; ?>px;
}
.dropdown .tb_subcategories > .tb_multicolumn > li:empty {
  display: none !important;
}
.dropdown .tb_subcategories > .tb_multicolumn.tb_list_1:first-child {
  margin-top: 0;
}
.dropdown .tb_subcategories > .tb_multicolumn:not(.tb_list_1) > * {
  display: table;
  width: 100%;
  margin-top: 0;
  padding-top: <?php echo $base; ?>px;
}
.dropdown .tb_subcategories > .tb_multicolumn > *:after {
  content: '';
  display: table;
  clear: both;
}
.dropdown .tb_subcategories > .tb_multicolumn:not(.tb_list_1) > * > :last-child {
  margin-bottom: 0 !important;
}
.dropdown .tb_subcategories .tb_multicolumn .h4,
.dropdown .tb_subcategories .tb_multicolumn .h4 .tb_text
{
  display: block;
}
.dropdown-menu .col-md-fill .tb_category_brands {
  margin: 0 -<?php echo $base; ?>px;
  padding: <?php echo $base; ?>px <?php echo $base; ?>px 0 <?php echo $base; ?>px;
}
.dropdown-menu .col-md-fill .tb_category_brands .tb_list_1 {
  margin-bottom: ;
}
.tb_images,
.tab-content > .tb_megamenu > .dropdown-menu > .tb_category_brands .tb_images
{
  text-align: center;
  margin-top: -10px;
  margin-left: -20px;
}
.tb_images > * {
  display: inline-block;
  margin-top: 10px;
  margin-left: 20px;
}
.tb_multicolumn {
  -webkit-column-width: 140px;
     -moz-column-width: 140px;
          column-width: 140px;
}
.tb_multicolumn *,
.tb_menu_brands .tb_letter
{
  -webkit-column-break-inside: avoid;
            page-break-inside: avoid;
                 break-inside: avoid;
}
.tb_multicolumn:not(.tb_list_1) > * + * {
  margin-top: <?php echo $base; ?>px;
}
.tb_menu_brands .tb_letter + .tb_letter {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.tb_menu_brands .tb_grid_view .tb_letter ul {
  margin-bottom: 0;
}
.tb_category_info_col * {
  color: inherit !important;
}
.tb_category_info_col .tb_desc {
  opacity: 0.9;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .tb_category_info_col {
    display: none;
  }
}
.tb_menu_banner {
  position: relative;
      -ms-flex-direction: column !important;
  -webkit-flex-direction: column !important;
          flex-direction: column !important;
}
.tb_menu_banner a {
  position: absolute;
  z-index: 1;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: block;
}
.dropdown-menu > .tb_menu_banner {
  width: auto;
  float: none;
  max-width: none;
  margin: 20px -20px -20px -20px;
  padding: 0;
}

@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
  .dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row:first-child:last-child {
    -ms-flex: 0 0 auto;
        flex: 0 0 auto;
    min-height: 100%;
  }
  .dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row:first-child:last-child .tb_subcategories {
    -ms-flex: 0 0 auto;
        flex: 0 0 auto;
  }
  .tb_menu_banner {
    -ms-flex-direction: row !important;
        flex-direction: row !important;
  }
}

/*  Dropdown tabs   ---------------------------------------------------------------------  */

.dropdown-menu > .tb_tabs > .nav-tabs {
  -ms-flex: 0 1 auto;
  -webkit-flex: 0 1 auto;
  flex: 0 1 auto;
}
.dropdown-menu > .tb_tabs > .nav-tabs > li {
  border-color: transparent !important;
}
.dropdown-menu > .tb_tabs > .nav-tabs > li {
  border-radius: 0;
}
.dropdown-menu > .tb_tabs.tabs-left > .nav-tabs {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}
.dropdown-menu > .tb_tabs.tabs-right > .nav-tabs {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}
.dropdown-menu > .tb_tabs.tabs-left > .nav-tabs > li:first-child {
  border-top-left-radius: inherit;
}
.dropdown-menu > .tb_tabs.tabs-left > .nav-tabs > li:last-child {
  border-bottom-left-radius: inherit;
}
.dropdown-menu > .tb_tabs.tabs-right > .nav-tabs > li:first-child {
  border-top-right-radius: inherit;
}
.dropdown-menu > .tb_tabs.tabs-right > .nav-tabs > li:last-child {
  border-top-bottom-radius: inherit;
}
.dropdown-menu > .tb_tabs > .nav-tabs > .dropdown.tb_hovered {
  margin-bottom: 0;
  padding-bottom: 0;
}
.dropdown-menu > .tb_tabs > .nav-tabs > .dropdown:after,
.dropdown-menu > .tb_tabs > .tab-content > .dropdown:after
{
  content: none;
}
.dropdown-menu > .tb_tabs > .tab-content {
  border: none !important;
}
.dropdown-menu > .tb_tabs > .tab-content > * {
  display: none;
}
.dropdown-menu > .tb_tabs > .nav-tabs > .dropdown > a,
.dropdown-menu > .tb_tabs > .tab-content,
.dropdown-menu > .tb_tabs > .tab-content .col
{
  display: -ms-flexbox !important;
  display: -webkit-flex !important;
  display: flex !important;
}
.dropdown-menu > .tb_tabs > .tab-content > .dropdown > .dropdown-menu {
  display: block;
  padding: 20px;
}
.dropdown-menu > .tb_tabs > .tab-content > .tb_opened,
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .row,
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu,
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row
{
  display: -ms-flexbox !important;
  display: -webkit-flex !important;
  display: flex !important;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu,
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row > .col-xs-12:first-child
{
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
      -ms-flex-wrap: nowrap;
  -webkit-flex-wrap: nowrap;
          flex-wrap: nowrap;
}
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row {
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
}
@media (-webkit-min-device-pixel-ratio:0) {
  .dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row {
        -ms-flex: 1 1 auto;
    -webkit-flex: 1 1 auto;
            flex: 1 1 auto;
  }
}
@-moz-document url-prefix() {
  .dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .row {
        -ms-flex: 1 1 auto;
    -webkit-flex: 1 1 auto;
            flex: 1 1 auto;
  }
}
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > *:not(.row) {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  margin: 0;
  padding: 20px;
}
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .clear {
  padding: 0;
}
.tb_tabbed_menu > .dropdown-menu,
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu
{
  padding: 0 !important;
}
.dropdown-menu > .tb_tabs > .tab-content > .tb_megamenu > .dropdown-menu > .tb_ip_20 {
  margin: 0;
}
.dropdown-menu > .tb_tabs > .tab-content > .dropdown > .dropdown-menu,
.dropdown-menu > .tb_tabs > .tab-content > .dropdown:hover > .dropdown-menu,
.dropdown-menu > .tb_tabs > .tab-content > .dropdown.tb_hovered > .dropdown-menu
{
  position: static;
  width: auto !important;
  margin: 0;
  box-shadow: none;
  opacity: 1 !important;
}
.dropdown-menu > .tb_tabs > .tab-content .col {
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
}
.dropdown-menu > .tb_tabs > .tab-content .tb_subcategories,
.dropdown-menu > .tb_tabs > .tab-content .tb_subcategories + .border,
.dropdown-menu > .tb_tabs > .tab-content .tb_category_brands,
.dropdown-menu > .tb_tabs > .tab-content .tb_category_info
{
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
  width: 100%;
}
.dropdown-menu > .tb_tabs > .tab-content .tb_subcategories {
  -ms-flex: 0 0 auto;
}
.dropdown-menu > .tb_tabs > .tab-content .tb_subcategories ~ .border,
.dropdown-menu > .tb_tabs > .tab-content .tb_subcategories ~ .tb_category_brands
{
      -ms-flex: 0 1 auto;
  -webkit-flex: 0 1 auto;
          flex: 0 1 auto;
  width: calc(100% + <?php echo $base * 2; ?>px);
}
.dropdown-menu > .tb_tabs > .tab-content .tb_subcategories ~ .tb_category_brands {
  width: 100%;
}
.tb_megamenu .tb_images {
  margin-top: 0;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .dropdown .tb_subcategories ~ .tb_category_brands {
    padding-bottom: <?php echo $base; ?>px;
  }
  .dropdown-menu > .tb_tabs > .tab-content .tb_subcategories,
  .dropdown-menu > .tb_tabs > .tab-content .tb_subcategories + .border,
  .dropdown-menu > .tb_tabs > .tab-content .tb_category_brands,
  .dropdown-menu > .tb_tabs > .tab-content .tb_category_info
  {
        -ms-flex: 0 0 auto;
    -webkit-flex: 0 0 auto;
            flex: 0 0 auto;
  }
}

.nav:not(.nav-responsive) > li > .dropdown-menu > .tb_tabs > .tab-content > li > a {
  display: none;
}
@media (min-width: <?php echo ($screen_sm + 1) . 'px'; ?>) {
  .nav.nav-responsive > li > .dropdown-menu > .tb_tabs > .tab-content > li > a {
    display: none;
  }
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .nav.nav-responsive > li > .dropdown-menu > .tb_tabs > .nav-tabs,
  .nav.nav-responsive .tb_menu_banner
  {
    display: none !important;
  }
  .nav.nav-responsive > li > .dropdown-menu > .tb_tabs > .tab-content,
  .nav.nav-responsive > li > .dropdown-menu > .tb_tabs > .tab-content > li
  {
    display: block !important;
  }
  .nav.nav-responsive > li > .dropdown-menu > .tb_tabs > .tab-content > li:not(.tb_active):not(.tb_hovered) > .dropdown-menu,
  .nav.nav-responsive > li > .dropdown-menu > .tb_tabs > .tab-content .tb_category_info_col
  {
    display: none !important;
  }
  .nav.nav-responsive .dropdown .tb_subcategories > .tb_multicolumn {
    margin-top: 0;
  }
  .nav.nav-responsive .col-xs-12 {
    width: 100% !important;
  }
  .nav.nav-responsive .tb_separate_columns.tb_ip_20 > .col:not(.tb_menu_banner) + .col {
    margin-top:  20px !important;
    padding-top: 20px;
  }
  .nav.nav-responsive .dropdown-menu > .clear.border {
    margin-left:  0;
    margin-right: 0;
  }
  .nav.nav-responsive .tb_menu_banner + .col {
    border-top: none;
  }
}

/*  -----------------------------------------------------------------------------------------
    U N S O R T E D
-----------------------------------------------------------------------------------------  */

.tb_vsep_0  > li { padding-top: 0;  padding-bottom: 0; }
.tb_vsep_xs > li, .tb_vsep_xs > li > a { padding-top: <?php echo $base * 0.1;  ?>px; padding-bottom: <?php echo $base * 0.1;  ?>px; }
.tb_vsep_sm > li, .tb_vsep_sm > li > a { padding-top: <?php echo $base * 0.25; ?>px; padding-bottom: <?php echo $base * 0.25; ?>px; }
.tb_vsep_md > li, .tb_vsep_md > li > a { padding-top: <?php echo $base * 0.5;  ?>px; padding-bottom: <?php echo $base * 0.5;  ?>px; }
.tb_vsep_lg > li, .tb_vsep_lg > li > a { padding-top: <?php echo $base * 0.75; ?>px; padding-bottom: <?php echo $base * 0.75; ?>px; }
.tb_vsep_xs > li > a { margin-top: -<?php echo $base * 0.1;  ?>px; margin-bottom: -<?php echo $base * 0.1;  ?>px; }
.tb_vsep_sm > li > a { margin-top: -<?php echo $base * 0.25; ?>px; margin-bottom: -<?php echo $base * 0.25; ?>px; }
.tb_vsep_md > li > a { margin-top: -<?php echo $base * 0.5;  ?>px; margin-bottom: -<?php echo $base * 0.5;  ?>px; }
.tb_vsep_lg > li > a { margin-top: -<?php echo $base * 0.75; ?>px; margin-bottom: -<?php echo $base * 0.75; ?>px; }
.tb_list_1.tb_vsep_xs > li:before { top: 12px; }
.tb_list_1.tb_vsep_sm > li:before { top: 15px; }
.tb_list_1.tb_vsep_md > li:before { top: 20px; }
.tb_list_1.tb_vsep_lg > li:before { top: 25px; }

/*  -----------------------------------------------------------------------------------------
    Q U I C K V I E W
-----------------------------------------------------------------------------------------  */

html.tb_quickview {
  overflow-x: hidden;
  overflow-y: scroll;
}
body[class*="quickview"],
body[class*="quickview"] #wrapper,
body[class*="quickview"] #content
{
  margin:  0 !important;
  padding: 0 !important;
  border:     none !important;
  box-shadow: none !important;
  background: none transparent !important;
}
.modal--quickview,
.modal--quickview .modal-body
{
  overflow: hidden;
  padding: 0;
}
.modal--quickview .ui-dialog-titlebar {
  display: none;
}
.modal--quickview iframe {
  display: block;
  width: calc(100% + 17px);
}
.modal--quickview:not(.ui-dialog) {
  display: block !important;
}
.modal--quickview:not(.in):not(.ui-dialog) {
  pointer-events: none;
}
body:not(.modal-open) .modal--quickview {
  position: absolute;
  top: 0;
  left: -2000px;
  width: 1200px;
  height: 1200px;
}
@media (min-width: 980px) {
  .modal--quickview .modal-dialog {
    width: 900px;
  }
}

/*  -----------------------------------------------------------------------------------------
    L A Z Y L O A D
-----------------------------------------------------------------------------------------  */

.container.lazyload,
.container-fluid.lazyload,
.row-wrap.lazyload,
.row-wrap.lazyload > .row,
.row-wrap.lazyload > .row > .col,
.row-wrap.lazyload .tb_wt,
.row-wrap.lazyload .tb_banner .tb_image
{
  background-image: none !important;
}

/*  -----------------------------------------------------------------------------------------
    S T I C K Y   S I D E B A R
-----------------------------------------------------------------------------------------  */

.col-sticky,
.col-sticky.is_stuck
{
  min-width: 0;
  margin-top: 0;
  margin-left:  0;
  margin-right: 0;
}
.is_stuck {
  min-width: 0 !important;
}
.tb_sticky_sidebar {
  min-width: 0;
}
.col-sticky > div {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}
.col-sticky > .row {
      -ms-flex-basis: 100%;
  -webkit-flex-basis: 100%;
          flex-basis: 100%;
}
