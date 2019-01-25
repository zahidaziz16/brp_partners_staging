/*  -----------------------------------------------------------------------------------------
    C O M M O N
-----------------------------------------------------------------------------------------  */

td .btn + .btn {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 0.5em;
  <?php else: ?>
  margin-right: 0.5em;
  <?php endif; ?>
}
.form-horizontal .col-sm-offset-2 .g-recaptcha {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 180px;
  <?php else: ?>
  padding-right: 180px;
  <?php endif; ?>
}

/*  -----------------------------------------------------------------------------------------
    P A G E S
-----------------------------------------------------------------------------------------  */

/*  Account & Affiliate   ---------------------------------------------------------------  */

.tb_page_account_login .tb_system_page_content .row > div,
#collapse-checkout-option .row > div,
.tb_page_account_login .tb_system_page_content .well
{
  display:  -ms-flexbox !important;
  display: -webkit-flex !important;
  display:         flex !important;
}
.tb_page_account_login .tb_system_page_content .well,
#collapse-checkout-option .row > div,
#collapse-checkout-option .tb_new_customer_box
{
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
  -ms-flex-item-align: stretch;
   -webkit-align-self: stretch;
           align-self: stretch;
      -ms-flex-wrap: nowrap;
  -webkit-flex-wrap: nowrap;
          flex-wrap: nowrap;
}
.tb_page_account_login .tb_system_page_content .well #new_customer,
.tb_page_checkout_checkout .tb_system_page_content .tb_new_customer_box,
#collapse-checkout-option .tb_new_customer_box
{
      -ms-flex: 1 0 auto;
  -webkit-flex: 1 0 auto;
          flex: 1 0 auto;
}
#collapse-checkout-option .buttons {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.account-login form fieldset label,
.affiliate-login form fieldset label
{
  display: block;
  font-weight: bold;
}

@media (max-width: <?php echo $screen_sm; ?>px) {
  .tb_page_account_login .tb_system_page_content .row > div,
  .tb_page_affiliate_login .tb_system_page_content .row > div,
  #collapse-checkout-option .row > div
  {
    width: 100%;
  }
}

.account-account .tb_system_page_content .list-unstyled + h2,
.affiliate-account .tb_system_page_content .list-unstyled + h2
{
  position: relative;
  margin-top: <?php echo $base * 1.5; ?>px;
  padding-top: <?php echo $base * 1.5; ?>px;
}
.account-order      .tb_system_page_content td:nth-child(7),
.account-order-info .tb_system_page_content td:nth-child(6),
.account-recurring  .tb_system_page_content td:nth-child(5),
.account-return     .tb_system_page_content td:nth-child(6),
.account-wishlist   .tb_system_page_content td:nth-child(6),
.account-address    .tb_system_page_content td:nth-child(2)
{
  width: 1px;
  white-space: nowrap;
}
.account-address    .tb_system_page_content td:nth-child(2) {
  vertical-align: top;
}
.account-address .tb_system_page_content td:first-child:first-line {
  font-weight: bold;
}
body[class*="account"]   td .btn i,
body[class*="account"]   td .btn i:before,
body[class*="affiliate"] td .btn i,
body[class*="affiliate"] td .btn i:before
{
  margin: 0;
}
@media (min-width: <?php echo $screen_sm + 1 . 'px'; ?>) {
  .account-order-info tfoot td:not([colspan]),
  #collapse-checkout-confirm td:not([colspan])
  {
    max-width: 200px;
  }
}
.account-order-info tfoot tr:not(:first-child) > td,
#collapse-checkout-confirm tfoot tr:not(:first-child) > td
{
  padding-top: 0;
}
.account-order-info tfoot tr:not(:last-child) > td,
#collapse-checkout-confirm tfoot tr:not(:last-child) > td
{
  padding-bottom: 0;
}
.account-order-info tfoot tr:last-child > td,
#collapse-checkout-confirm tfoot tr:last-child > td
{
  padding-top: <?php echo $base * 0.5; ?>px !important;
  line-height: <?php echo $base * 1.5; ?>px;
  font-size: <?php echo ceil($base_font_size * 1.35); ?>px;
}
#collapse-checkout-confirm .buttons {
  margin-top: 0;
}

/*  Cart & Checkout   -------------------------------------------------------------------  */

.checkout-cart .tb_system_page_content .panel-group .panel-heading a,
.checkout-cart .tb_system_page_content .panel-group .panel-heading a[class],
.checkout-checkout .tb_system_page_content .panel-group .panel-heading a,
.checkout-checkout .tb_system_page_content .panel-group .panel-heading a[class]
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  -ms-flex-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  color: inherit !important;
  background-color: transparent !important;
  border-color: transparent !important;
}
.checkout-cart .tb_system_page_content .panel-group .panel-heading a i,
.checkout-cart .tb_system_page_content .panel-group .panel-heading a i:before,
.checkout-checkout .tb_system_page_content .panel-group .panel-heading a i,
.checkout-checkout .tb_system_page_content .panel-group .panel-heading a i:before
{
  margin: 0;
}
.checkout-cart .tb_system_page_content .panel-group .panel-heading a.collapsed i,
.checkout-cart .tb_system_page_content .panel-group .panel-heading a:not([aria-expanded]) i,
.checkout-checkout .tb_system_page_content .panel-group .panel-heading a.collapsed i
{
  -webkit-transform: rotate(-90deg);
          transform: rotate(-90deg);
}
.checkout-cart .tb_system_page_content .panel-group .panel-heading a:before,
.checkout-checkout .tb_system_page_content .panel-group .panel-heading a:before
{
  content: none !important;
}
.checkout-cart .tb_system_page_content .panel-group .panel .panel-collapse,
.checkout-checkout .tb_system_page_content .panel-group .panel-collapse
{
  border-top-color: transparent !important;
  border-right-color: transparent !important;
  border-left-color: transparent !important;
}
.checkout-cart .tb_system_page_content .panel-group .panel:not(:last-child) .panel-collapse:not(.in),
.checkout-checkout .tb_system_page_content .panel-group .panel-collapse
{
  border-bottom-color: transparent !important;
}
.checkout-checkout .tb_system_page_content .panel-group .panel:last-child .panel-body {
  padding-bottom: 0 !important;
}
#payment-existing select,
#shipping-existing select
{
  width: 100%;
  margin-bottom: <?php echo $base; ?>px;
}
#shipping_quote p:not(:first-child),
#shipping_quote .radio + h2,
#collapse-shipping-method p:not(:first-child),
#collapse-shipping-method .radio + h2,
#collapse-payment-method p:not(:first-child),
#collapse-payment-method .radio + h2
{
  margin-top: <?php echo $base; ?>px;
}
#shipping_quote p + .radio,
#collapse-shipping-method p + .radio
{
  margin-top: -<?php echo $base; ?>px;
}
#collapse-shipping-method textarea,
#collapse-payment-method textarea
{
  width: 100%;
}
#button-shipping {
  margin-top: <?php echo $base; ?>px !important;
}
#cart_form + h2,
#cart_form + h2 + p
{
  display: none;
}
#cart_form .name .label {
  margin-top: <?php echo $base * 0.5; ?>px;
  vertical-align: bottom;
}
.checkout-cart .tb_system_page_content .cart-total {
  margin-top: <?php echo $base * 1.5; ?>px;
}
#collapse-coupon label,
#collapse-reward label,
#collapse-voucher label
{
  display: block;
  float: none;
  width: auto;
  max-width: none;
  margin: 0 0 <?php echo $base * 0.5; ?>px 0;
  padding: 0;
}
#collapse-coupon .input-group,
#collapse-reward .input-group,
#collapse-voucher .input-group
{
  width: 350px;
}

/*  -----------------------------------------------------------------------------------------
    C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

/*  Autocomplete   ----------------------------------------------------------------------  */

.autocomplete-menu {
  z-index: 2;
}
.autocomplete-menu a {
  -webkit-transition: none;
          transition: none;
}
.autocomplete-menu a:hover,
.autocomplete-menu a:focus
{
  margin-left: -<?php echo $base; ?>px;
  margin-right: -<?php echo $base; ?>px;
  padding-left: <?php echo $base; ?>px;
  padding-right: <?php echo $base; ?>px;
  background-color: rgba(0, 0, 0, 0.06);
  box-shadow:
    inset 0 -1px 0 rgba(0, 0, 0, 0.05),
    inset 0  1px 0 rgba(0, 0, 0, 0.05);
  -webkit-transition: color 0.3s, background 0.3s;
          transition: color 0.3s, background 0.3s;
}

/*  Breadcrumbs   -----------------------------------------------------------------------  */

.breadcrumb li:first-child a:before {
  content: '<?php echo $tbData->text_home; ?>';
}
.breadcrumb i.fa {
  display: none;
}

/*  Datetimepicker   --------------------------------------------------------------------  */

.bootstrap-datetimepicker-widget {
  opacity: 1 !important;
  display: block;
  padding: 0 !important;
}
.bootstrap-datetimepicker-widget[style*="display: none"],
.bootstrap-datetimepicker-widget[style*="display:none"],
.bootstrap-datetimepicker-widget:not([style*="absolute"])
{
  opacity: 0 !important;
  display: none !important;
}
.bootstrap-datetimepicker-widget:before,
.bootstrap-datetimepicker-widget:after
{
  content: none !important;
}
.bootstrap-datetimepicker-widget * {
  border-radius: inherit;
}
.bootstrap-datetimepicker-widget th {
  background-color: transparent;
}
.bootstrap-datetimepicker-widget th,
.bootstrap-datetimepicker-widget td,
.bootstrap-datetimepicker-widget td span
{
  padding: 0 !important;
  border-radius: 0 !important;
  vertical-align: middle;
  -webkit-transition: opacity 0.3s, color 0.3s, background-color 0.3s;
          transition: opacity 0.3s, color 0.3s, background-color 0.3s;
}
.bootstrap-datetimepicker-widget td span {
  margin: 2px !important;
  border-radius: 0 !important;
}
.bootstrap-datetimepicker-widget .prev,
.bootstrap-datetimepicker-widget .next,
.bootstrap-datetimepicker-widget .picker-switch
{
  line-height: <?php echo $base * 1.5; ?>px;
}
.bootstrap-datetimepicker-widget .prev,
.bootstrap-datetimepicker-widget .next
{
  width: <?php echo $base * 1.5; ?>px;
  padding-bottom: 0.12em !important;
  line-height: 10px;
  font-family: "Open Sans", Tahoma, Arial, sans-serif !important;
  font-weight: normal !important;
  font-size: 22px;
}
.bootstrap-datetimepicker-widget thead .prev:hover,
.bootstrap-datetimepicker-widget thead .next:hover,
.bootstrap-datetimepicker-widget thead .picker-switch:hover
{
  background-color: rgba(0, 0, 0, 0.2) !important;
}
.bootstrap-datetimepicker-widget table {
  display: block;
}
.bootstrap-datetimepicker-widget table thead,
.bootstrap-datetimepicker-widget table tbody
{
  display: table;
  width: 100%;
  padding: 10px;
  border-collapse: separate;
  border-spacing: 3px;
  border-bottom-left-radius: 0 !important;
  border-bottom-right-radius: 0 !important;
}
.bootstrap-datetimepicker-widget table tbody:not(:first-child) {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 0 !important;
  border-top-right-radius: 0 !important;
}
.bootstrap-datetimepicker-widget .datepicker .dow {
  padding-top: <?php $base * 0.5; ?>px;
  opacity: 0.7;
}
.bootstrap-datetimepicker-widget .datepicker .day {
  height: <?php echo $base * 1.5; ?>px;
}
.bootstrap-datetimepicker-widget .datepicker-years td,
.bootstrap-datetimepicker-widget .datepicker-years td:hover,
.bootstrap-datetimepicker-widget .datepicker-months td,
.bootstrap-datetimepicker-widget .datepicker-months td:hover,
.bootstrap-datetimepicker-widget .timepicker-picker td,
.bootstrap-datetimepicker-widget .timepicker-picker td:hover
{
  background-color: transparent !important;
}
.bootstrap-datetimepicker-widget .old,
.bootstrap-datetimepicker-widget .new
{
  opacity: 0.5 !important;
  background-color: transparent !important;
}
.bootstrap-datetimepicker-widget .datepicker-months tbody,
.bootstrap-datetimepicker-widget .datepicker-years tbody
{
  padding: 7px 0;
}
.bootstrap-datetimepicker-widget .collapse:not(:last-child) table tbody {
  padding-bottom: 0;
}
.bootstrap-datetimepicker-widget .collapse:last-child table tbody {
  padding-top: 0;
}
.bootstrap-datetimepicker-widget .picker-switch.accordion-toggle {
  padding: 10px;
}
body .bootstrap-datetimepicker-widget .btn[class] {
  height: auto !important;
  font-size: 17px;
  color: inherit !important;
  box-shadow: none !important;
  border: none !important;
  border-radius: 0 !important;
  background-color: transparent !important;
}
body .bootstrap-datetimepicker-widget .btn[class]:hover {
  color: inherit !important;
  background-color: rgba(0, 0, 0, 0.1) !important;
}
.bootstrap-datetimepicker-widget a[data-action] {
  padding: 0 !important;
}
body .bootstrap-datetimepicker-widget .btn[class] span,
body .bootstrap-datetimepicker-widget .btn[class] span:hover
{
  color: inherit !important;
  background-color: transparent !important;
}
.bootstrap-datetimepicker-widget .timepicker-picker tr td a.btn {
  width: 54px;
}
.bootstrap-datetimepicker-widget .timepicker-picker tr:first-child td,
.bootstrap-datetimepicker-widget .timepicker-picker tr:first-child td span,
.bootstrap-datetimepicker-widget .timepicker-picker tr:last-child td,
.bootstrap-datetimepicker-widget .timepicker-picker tr:last-child td span
{
  height: <?php echo $base * 1.5; ?>px !important;
  line-height: <?php echo $base * 1.5; ?>px !important;
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   P A G E
-----------------------------------------------------------------------------------------  */

/*  Info   ------------------------------------------------------------------------------  */

.tb_wt_product_info_system dl dt,
.tb_wt_product_info_system dl dd
{
  float: none;
  clear: none;
  display: inline;
}
.tb_wt_product_info_system dl dt {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
}
.tb_wt_product_info_system dl dd:after {
  content: '';
  display: block;
}

/*  Images   ----------------------------------------------------------------------------  */

.product-info .thumbnails {
  display: none;
}

/*  Options   ---------------------------------------------------------------------------  */

.options .form-group {
  margin-top:  0;
  margin-left: 0;
}
.options .form-group > label,
.options .form-group > div
{
  margin-top:  0 !important;
  margin-left: 0 !important;
}
.options .form-group > label {
  width:     120px;
  max-width: 120px;
}
.options.options .form-group > div {
  max-width: none;
      -ms-flex-basis: 160px !important;
  -webkit-flex-basis: 160px !important;
          flex-basis: 160px !important;
}
.options .tb_style_1 .image + .image {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.options .checkbox.image > label input,
.options .radio.image > label input,
.options .checkbox.image > label img,
.options .radio.image > label img
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
}
.options .tb_style_1 .checkbox.image,
.options .tb_style_1 .radio.image
{
  padding-top: 0;
  padding-bottom: 0;
}

/*** Custom Style ***/

.options .tb_style_2 > div {
  display: -ms-flexbox !important;
  display: -webkit-flex !important;
  display: flex !important;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.options .tb_style_2 .control-label {
  -ms-flex-item-align: start;
  -webkit-align-self: flex-start;
          align-self: flex-start;
}
.options .tb_style_2 > div > div:not(.text-danger) {
  margin-top: -<?php echo $base * 0.5; ?>px;
  margin-left: -<?php echo $base * 0.5; ?>px;
}
.options .tb_style_2 > div > div:after {
  content: '';
  clear: both;
  display: table;
}

/*** Custom style - button checkbox / radio ***/

.options .tb_style_2 .checkbox,
.options .tb_style_2 .radio
{
  display: block;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  margin-top: <?php echo $base * 0.5; ?>px;
  margin-left: <?php echo $base * 0.5; ?>px;
  padding: 0 !important;
}
.options .tb_style_2 .checkbox > label,
.options .tb_style_2 .radio > label
{
  display: block;
  height: <?php echo $form_control_height; ?>px;
  min-width: <?php echo $form_control_height; ?>px;
  margin: 0 !important;
  padding: 0 <?php echo $form_control_height * 0.2; ?>px;
  line-height: <?php echo $form_control_height; ?>px;
  text-align: center;
  cursor: pointer;
}
.options .tb_style_2 .checkbox > label span span,
.options .tb_style_2 .radio    > label span span,
.options .tb_style_2 .checkbox > label img ~ span,
.options .tb_style_2 .radio    > label img ~ span,
.options .tb_style_2 .checkbox.image > label div,
.options .tb_style_2 .checkbox > label input[type=checkbox],
.options .tb_style_2 .radio > label input[type=radio]
{
  display: none;
}

/*** Custom style - image checkbox / radio ***/

.options .tb_style_2 .checkbox.image > label,
.options .tb_style_2 .radio.image > label
{
  width: auto;
  height: auto;
  min-width: 0;
  padding: 3px;
  line-height: normal !important;
  cursor: pointer;
}
.options .tb_style_2 .checkbox.image > label img,
.options .tb_style_2 .radio.image > label img
{
  margin: 0;
  padding: 1px;
  vertical-align: top;
  background: #fff;
}

/*** Mobile ***/

.tb_max_w_300 .option b,
.tb_max_w_300 .option .required
{
  float: none;
  margin-bottom: <?php echo $base * 0.5; ?>px;
  padding-top: 0;
  padding-bottom: 0;
}
.tb_max_w_300 .option .required {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: 0;
  <?php else: ?>
  padding-left: 0;
  <?php endif; ?>
}
.tb_max_w_300 .option b {
  max-width: none;
}
.tb_max_w_300 .tb_checkbox_row .tb_group,
.tb_max_w_300 .tb_radio_row .tb_group
{
  padding-top: 0;
  padding-bottom: 0;
}
.tb_max_w_300 .option .tb_group,
.tb_max_w_300 #profile-description
{
  float: none;
  width: auto;
  margin-left: 0;
  margin-right: 0;
}
.tb_max_w_300 .option + .error,
.tb_max_w_300 .option > .error
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 0;
  padding-left: 0;
  <?php else: ?>
  margin-right: 0;
  padding-right: 0;
  <?php endif; ?>
}
.tb_max_w_300 .option input[type=button] {
  float: none;
}

/*  ---   Search   ----------------------------------------------------------------------  */

#adv_search_box > div {
  width: auto;
}
#adv_search_box > div:last-child {
  width: 100%;
  max-width: none;
  margin-top: <?php echo $base; ?>px;
}
.tb_max_w_550 #adv_search_box > div {
  width: calc(100% - 20px);
  max-width: none;
}
.tb_max_w_550 #adv_search_box > div:not(:first-child) {
  margin-top: <?php echo $base; ?>px;
}
.tb_min_w_550 #adv_search_box input[type=text],
.tb_min_w_550 #adv_search_box select
{
  width: 250px;
}
#adv_search_box .btn {
  width: 80px;
  margin-left: 0;
}
#adv_search_box .tb_button_1 .tb_text {
  text-align: center;
}
#adv_search_box > :last-child {
  margin-bottom: 0;
}
