
/*  -----------------------------------------------------------------------------------------
    C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

/*  Listing options   -------------------------------------------------------------------  */

.tb_listing_options {
  position: relative;
  clear: both;
  border-bottom: 1px solid transparent;
}
.tb_listing_options h4,
.tb_listing_options label
{
  float: none;
  margin: 0;
}
.tb_listing_options select {
  height: <?php echo $base * 1.5 - 6; ?>px;
  margin: 0;
  margin-top: 3px;
  margin-bottom: 3px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 2px;
  <?php else: ?>
  margin-right: 2px;
  <?php endif; ?>
  padding: <?php echo $base / 4 - 3 - $form_border_width; ?>px;
}
.product-filter {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  margin-left: -<?php echo $base; ?>px;
}
.product-filter b {
  font-weight: normal;
}
.product-filter > * {
  display: inline-block;
  -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
  flex: 0 0 auto;
  vertical-align: top;
  line-height: <?php echo $base * 1.5; ?>px;
  padding-left: <?php echo $base; ?>px;
}
.product-filter .display {
      -ms-flex: 1 0 auto;
  -webkit-flex: 1 0 auto;
          flex: 1 0 auto;
  margin-left: -<?php echo $base * 0.5; ?>px;
}
.product-filter .display > * {
  margin-left: <?php echo $base * 0.5; ?>px;
}
.product-filter .display .fa {
  font-size: 14px;
  vertical-align: top;
}
.product-filter .display .fa:before {
  margin-top: 0.07em;
  margin-bottom: -1px;
}
.product-filter .product-compare {
      -ms-flex: 1000 0 auto;
  -webkit-flex: 1000 0 auto;
          flex: 1000 0 auto;
}
.product-filter .limit select,
.product-filter .sort select
{
  width: auto;
}
.product-filter .limit select {
  min-width: 50px;
}
.product-filter .sort select {
  min-width: 120px;
}

/*** Responsive ***/

.tb_max_w_650 .product-filter,
.tb_max_w_350 .product-filter
{
  display: block !important;
  margin: -<?php echo $base; ?>px 0 0;
}
.tb_max_w_650 .product-filter:after,
.tb_max_w_350 .product-filter:after
{
  content: '';
  clear: both;
  display: table;
}
.tb_max_w_650 .product-filter > *,
.tb_max_w_350 .product-filter > *
{
  margin-top: <?php echo $base; ?>px;
  padding: 0 !important;
}
.tb_max_w_650 .product-filter > * {
  <?php if ($lang_dir == 'ltr'): ?>
  float : left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  min-width: 50%;
}
.tb_max_w_350 .product-filter > *
{
  display: block;
}
.tb_max_w_350 .product-filter select {
  width: 55%;
}

/*  Products table   --------------------------------------------------------------------  */

.cart-info table {
  margin-bottom: 0;
  table-layout: auto;
}
.cart-info td:not(.name) {
  width: 1px;
}
.cart-info td.model {
  min-width: 110px;
}
.cart-info thead td:not(.name) {
  white-space: nowrap;
}
.cart-info img {
  display: inline-block;
  vertical-align: top;
}
.cart-info .return,
.cart-info .image
{
  width: 10px;
}
.cart-info .return img
{
  vertical-align: text-top;
}
.cart-info tbody td {
  padding: <?php echo $base; ?>px <?php echo $base * 0.5; ?>px !important;
}
.cart-info tbody td:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: <?php echo $base; ?>px;
  <?php else: ?>
  padding-right: <?php echo $base; ?>px;
  <?php endif; ?>
}
.cart-info tbody td:last-child {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: <?php echo $base; ?>px;
  <?php else: ?>
  padding-left: <?php echo $base; ?>px;
  <?php endif; ?>
}
.cart-info .image img {
  max-width: <?php echo $base * 4; ?>px;
}
.cart-info .name {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
}
.cart-info .name > a {
  font-size: <?php echo $base_font_size + 1; ?>px;
  font-weight: bold;
}
.cart-info .name div,
.cart-info .name > small
{
  line-height: 1.2em;
  font-size: <?php echo $base_font_size; ?>px;
}
.cart-info .name div {
  margin-top: <?php echo $base * 0.25; ?>px;
}
.cart-info .name > small {
  opacity: 0.6;
}
.cart-info .name .text-danger {
  margin-top:    0;
  margin-bottom: 0;
}
.cart-info .name .text-danger:before {
  content: none;
}
.cart-info .quantity input[type=text],
.cart-info .quantity input[type=number]
{
  width: <?php echo $base * 2; ?>px;
  text-align: center;
}
.cart-info .quantity .ui-spinner {
  margin-left: 0;
  margin-right: 0;
}
.cart-info .quantity input[type="image"] {
  margin-top: -0.25em;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base / 2; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base / 2; ?>px;
  <?php endif; ?>
  background-color: transparent;
}
.cart-info .action a {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.cart-info span.stock {
  color: red;
  font-weight: bold;
}
.cart-info + .cart-total {
  margin-top: <?php echo $base; ?>px;
}
.cart-total {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: right;
  <?php else: ?>
  text-align: left;
  <?php endif; ?>
}
.cart-total table {
  display: inline-table;
  max-width: 300px;
}
.cart-total table td {
  padding: 0 !important;
  vertical-align: top;
}
.cart-total td:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
}
.cart-total td:first-child + td {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: right;
  <?php else: ?>
  text-align: left;
  <?php endif; ?>
}
.cart-total tr td:last-child {
  min-width: 130px;
}
.cart-total tr:last-child td {
  padding-top: <?php echo $base * 0.5; ?>px !important;
  line-height: <?php echo $base * 1.5; ?>px;
  font-size: <?php echo ceil($base_font_size * 1.35); ?>px;
}

/*** Mini ***/

.mini-cart-info {
  margin-bottom: <?php echo $base; ?>px;
}
.mini-cart-info table {
  table-layout: auto;
}
.mini-cart-info th:first-child,
.mini-cart-info td:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 0 !important;
  <?php else: ?>
  padding-right: 0 !important;
  <?php endif; ?>
}
.mini-cart-info tbody:first-child tr:first-child td {
  padding-top: 0 !important;
}
.mini-cart-info th:last-child,
.mini-cart-info td:last-child
{
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: 0 !important;
  <?php else: ?>
  padding-left: 0 !important;
  <?php endif; ?>
}
.mini-cart-info .image img {
  max-width: <?php echo $base * 3; ?>px;
}
.mini-cart-info .name > a {
  font-size: <?php echo $base_font_size; ?>px !important;
}
.mini-cart-info td {
  padding-top: <?php echo $base / 2; ?>px;
  padding-bottom: <?php echo $base / 2 - 1; ?>px;
}
.mini-cart-total {
  position: relative;
  margin-top: -<?php echo $base; ?>px !important;
  padding-top: <?php echo $base; ?>px;
}
.mini-cart-total table {
  float: none !important;
  max-width: none;
}
.mini-cart-total tr:last-child td {
  line-height: <?php echo $base; ?>px;
  font-size: <?php echo $base_font_size * 1.15; ?>px;
}
.mini-cart-total tr td:last-child {
  min-width: 100px;
}
.mini-cart-info ~ .buttons {
  display: block;
  text-align: center;
}
.mini-cart-info ~ .buttons .btn {
  margin-top: 0 !important;
}
.mini-cart-info ~ .buttons .btn:first-child {
  margin-right: <?php echo $base * 0.5; ?>px;
}


/*** Responsive ***/

.cart-info.tb_max_w_500 thead,
.cart-info.tb_max_w_500 .model,
.cart-info.tb_max_w_500 .price,
.cart-info.tb_max_w_300 thead,
.cart-info.tb_max_w_300 .model,
.cart-info.tb_max_w_300 .price
{
  display: none !important;
}
.cart-info.tb_max_w_500 table,
.cart-info.tb_max_w_500 tbody,
.cart-info.tb_max_w_500 tr,
.cart-info.tb_max_w_500 th,
.cart-info.tb_max_w_500 td,
.cart-info.tb_max_w_300 table,
.cart-info.tb_max_w_300 tbody,
.cart-info.tb_max_w_300 tr,
.cart-info.tb_max_w_300 th,
.cart-info.tb_max_w_300 td
{
  display: block;
  padding: 0;
  text-align: initial;
  border-width: 0;
  border-style: none;
  box-shadow: none;
}
.cart-info.tb_max_w_500 tr,
.cart-info.tb_max_w_300 tr
{
  position: relative;
  margin-bottom: <?php echo $base; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  padding: 0 130px <?php echo $base; ?>px 0;
  <?php else: ?>
  padding: 0 0 <?php echo $base; ?>px 130px;
  <?php endif; ?>
}
.cart-info.tb_max_w_500 tr:not(:last-child),
.cart-info.tb_max_w_300 tr:not(:last-child)
{
  border-bottom-width: 1px;
  border-bottom-style: solid;
}
.cart-info.tb_max_w_500 tr:last-child,
.cart-info.tb_max_w_300 tr:last-child
{
  margin-bottom: 0;
}
.cart-info.tb_max_w_500 tr:after,
.cart-info.tb_max_w_300 tr:after
{
  content: '';
  clear: both;
  display: table;
}
.cart-info.tb_max_w_500 .total,
.cart-info.tb_max_w_300 .total,
.wishlist-info.tb_max_w_500 td.price
{
  font-size: <?php echo $base_font_size * 1.25; ?>px;
}
.cart-info.tb_max_w_500 td,
.cart-info.tb_max_w_300 td
{
  padding: 0 !important;
}
.mini-cart-info.tb_max_w_500 ~ .cart-total table,
.mini-cart-info.tb_max_w_300 ~ .cart-total table
{
  width: 100%;
}
.mini-cart-info.tb_max_w_500 tr,
.mini-cart-info.tb_max_w_300 tr
{
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: 50px;
  <?php else: ?>
  padding-left: 50px;
  <?php endif; ?>
}

/*** Min width 500px ***/

.cart-info.tb_min_w_500 tbody:last-child > tr:last-child > td {
  border-bottom-width: 1px;
  border-bottom-style: solid;
}

/*** Max width 500px ***/

.cart-info.tb_max_w_500 .image {
  width: auto;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  margin-right: <?php echo $base; ?>px;
  <?php else: ?>
  float: right;
  margin-left: <?php echo $base; ?>px;
  <?php endif; ?>
  padding: 0 !important;
}
.cart-info.tb_max_w_500 .name,
.cart-info.tb_max_w_500 .total,
.wishlist-info.tb_max_w_500 .price
{
  width: auto;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: <?php echo $tbData['config']->get('config_image_cart_width') + 20; ?>px !important;
  <?php else: ?>
  padding-right: <?php echo $tbData['config']->get('config_image_cart_width') + 20; ?>px !important;
  <?php endif; ?>
}
.cart-info.tb_max_w_500 table tbody .name strong:after {
  content: attr(data-quantity);
  font-weight: normal;
}
.cart-info.tb_max_w_500 .quantity,
.cart-info.tb_max_w_500 .action,
.cart-info.tb_max_w_500 .return
{
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  right: 0;
  text-align: right;
  <?php else: ?>
  left: 0;
  text-align: left;
  <?php endif; ?>
  width: 40px;
}
.cart-info.tb_max_w_500 .action,
.cart-info.tb_max_w_500 .quantity
{
  width: auto;
}
.cart-info.tb_max_w_500 .quantity input {
  margin-bottom: 10px;
  text-align: center;
  line-height: <?php echo $base * 1.5; ?>px;
}
.cart-info.tb_max_w_500 .quantity input[type=image] {
  display: none;
}
.cart-info.tb_max_w_500 .return {
  top: <?php echo $base * 1.5; ?>px;
}
.cart-info.tb_max_w_500 .total,
.wishlist-info.tb_max_w_500 table tbody .price
{
  display: block !important;
}
.cart-info.tb_max_w_500 .total,
.wishlist-info.tb_max_w_500 td.price
{
  padding-top: <?php echo $base * 0.5; ?>px !important;
}
.cart-info.tb_max_w_500 .remove {
  position: absolute;
  top: <?php echo $base * 1.5; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  right: 0;
  <?php else: ?>
  left: 0;
  <?php endif; ?>
  width: auto;
}
.cart-info.tb_max_w_500 .remove > * {
  margin: 0;
}

/*** Max width 330px ***/

.cart-info.tb_max_w_300 td {
  padding: 0 !important;
}
.cart-info.tb_max_w_300 tbody tr {
  padding-left: 0;
  padding-right: 0;
}
.cart-info.tb_max_w_300 .image,
.cart-info.tb_max_w_300 .name,
.cart-info.tb_max_w_300 .total,
.wishlist-info.tb_max_w_300 td.price,
.cart-info.tb_max_w_300 .quantity,
.cart-info.tb_max_w_300 .stock,
.cart-info.tb_max_w_300 .action
{
  position: static;
  float: none;
  width: 100%;
  margin: 0;
  padding: 0;
  text-align: center !important;
}
.cart-info.tb_max_w_300 td[class]:not(:first-child) {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.cart-info.tb_max_w_300 .name {
  margin-top: <?php echo $base; ?>px;
}
.cart-info.tb_max_w_300 .image,
.cart-info.tb_max_w_300 .stock,
.cart-info.tb_max_w_300 .total,
.wishlist-info td.price
{
  margin-top: 0;
}
.cart-info.tb_max_w_300 .image img {
  max-width: none;
}
.cart-info.tb_max_w_300 .quantity {
  padding-top: <?php echo $base * 0.5; ?>px;
}
.cart-info.tb_max_w_300 .quantity {
  max-width: 130px;
  margin-left: auto;
  margin-right: auto;
}
.cart-info.tb_max_w_300 .remove {
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  right: 0;
  <?php else: ?>
  left: 0;
  <?php endif; ?>
  width: auto;
}

/*** Margins ***/

.cart-info + .buttons {
  margin-top: -<?php echo $base * 1.5; ?>px;
}
.cart-info + .buttons:before {
  content: none;
}

/*  Reviews   ---------------------------------------------------------------------------  */

.tb_review {
  overflow: hidden;
}
.tb_review .tb_meta {
  line-height: 0;
  text-align: justify;
}
.tb_review .tb_meta:after {
  content: ' ';
  display: inline-block;
  width: 90%;
  height: 0;
  line-height: 0;
  vertical-align: top;
}
.tb_review .tb_author,
.tb_review .rating
{
  display: inline-block;
  line-height: <?php echo $base; ?>px;
  vertical-align: top;
}
.tb_review .tb_author {
  margin-bottom: <?php echo $base * 0.5; ?>px;
}
.tb_review > p:first-child {
  margin-bottom: 0;
}
.tb_review > p + .tb_meta {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 35px;
  <?php else: ?>
  padding-right: 35px;
  <?php endif; ?>
}
.tb_review > p + .tb_meta,
.tb_review > p + .tb_meta .tb_author
{
  margin-bottom: 0;
}
.tb_review > p + .tb_meta .tb_author {
  margin-top: <?php echo $base * 0.5; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_review > p + .tb_meta .rating {
  vertical-align: bottom;
}
.tb_review .tb_author small {
  margin-left: 4px;
  font-size: 10px;
  color: #999;
}
.tb_review > p {
  clear: both;
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
}
.tb_review > p:first-child {
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 35px;
  <?php else: ?>
  padding-right: 35px;
  <?php endif; ?>
  font-style: italic;
}
.tb_review > p:first-child:before,
.tb_review > p:first-child:after
{
  position: absolute;
  width: 30px;
  height: 24px;
  line-height: 55px;
  font-family: Arial;
  font-size: 60px;
  font-style: italic;
  color: #000;
  opacity: 0.2;
}
.tb_review > p:first-child:before {
  <?php if ($lang_dir == 'ltr'): ?>
  content: '\201C';
  left: 0;
  <?php else: ?>
  content: '\201D';
  right: 0;
  <?php endif; ?>
  top: 0;
  text-indent: -7px;
}
.tb_listing > .tb_review:not(:first-child) {
  position: relative;
  margin-top: <?php echo $base; ?>px;
  padding-top: <?php echo $base; ?>px;
}

/*  Submit Buttons   --------------------------------------------------------------------  */

.buttons {
  position: relative;
  z-index: 1;
  clear: both;
  display: table;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  width: 100%;
  margin-top: <?php echo $base * 1.5; ?>px;
  padding-top: <?php echo $base * 1.5; ?>px;
}
.buttons:last-child {
  margin-bottom: 0;
}
.buttons {
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
.buttons > .row {
      -ms-flex: 1 1 100%;
  -webkit-flex: 1 1 100%;
          flex: 1 1 100%;
}
.buttons table {
  width: 100%;
}
.buttons .left,
.buttons .right,
.buttons .pull-left,
.buttons .pull-right
{
  display: table-cell;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  float: none !important;
  vertical-align: middle;
}
.buttons .right,
.buttons .pull-right
{
  -ms-flex-pack: end;
  -webkit-justify-content: flex-end;
  justify-content: flex-end;
}
.buttons .btn:not(.btn-xs):not(.btn-sm):not(.btn-lg):not(.btn-xl):not(.btn-xxl),
.buttons .button,
.buttons button,
.buttons [type=button],
.buttons [type=submit],
#button-cart,
#product_buy_quantity,
#product_buy #input-quantity
{
  height:        <?php echo $submit_button_height; ?>px;
  line-height:   <?php echo $submit_button_height; ?>px !important;
  margin-top:    <?php echo ($tbData->calculateLineHeight($submit_button_height - 2, $base) - $submit_button_height) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($submit_button_height - 2, $base) - $submit_button_height) / 2; ?>px;
  padding-left:  <?php echo ceil($submit_button_height / 2); ?>px;
  padding-right: <?php echo ceil($submit_button_height / 2); ?>px;
  font-size:     <?php echo $base_button_size + 1; ?>px;
}
.buttons .button,
.buttons .btn
{
  -ms-flex-order: 5;
  -webkit-order: 5;
  order: 5;
}
.pagination + .buttons,
.table-bordered + .buttons,
.pagination + .buttons:before,
.table-bordered + .buttons:before
{
  padding-top: 0;
  border-top-width: 0;
}
.tb_sep + .buttons,
fieldset + .buttons
{
  margin-top: 0;
}

/*** Mobile ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .buttons {
    display: block;
    text-align: inherit;
    margin-top: <?php echo $base; ?>px;
    padding-top: <?php echo $base; ?>px;
  }
  .buttons .left,
  .buttons .right,
  .buttons .pull-left,
  .buttons .pull-right
  {
    display: block;
    width: 100%;
    text-align: initial;
  }
  .buttons > * + * {
    margin-top: <?php echo ($tbData->calculateLineHeight($submit_button_height, $base) - $submit_button_height)/2 + $base; ?>px;
  }
  .buttons .checkbox,
  .buttons .radio
  {
    display: block;
    width: 100%;
    margin-left: 0;
    margin-right: 0;
    margin-top: 0;
    margin-bottom: <?php echo $base; ?>px;
  }
  .buttons i.fa.fa-circle-o-notch.fa-spin,
  .buttons i.fa.fa-circle-o-notch.fa-spin
  {
    <?php if ($lang_dir == 'ltr'): ?>
    margin-left: <?php echo $base * 0.5; ?>px;
    <?php else: ?>
    margin-right: <?php echo $base * 0.5; ?>px;
    <?php endif; ?>
    vertical-align: middle;
  }
  .buttons .tb_button_forgotten {
    display: block;
    float: none;
    margin-bottom: <?php echo $base; ?>px;
  }
  .buttons input[type=submit],
  .buttons input[type=button],
  .buttons button
  {
    line-height: 30px !important;
    -webkit-appearance: none;
  }
}
@media (max-width: <?php echo $screen_xs . 'px'; ?>) {
  .buttons .btn,
  .buttons .button
  {
    display: block;
    width: 100%;
  }
  .buttons .btn + .btn,
  .buttons .btn + .button,
  .buttons .button + .btn,
  .buttons .button + .button,
  .buttons .left + .right,
  .buttons .pull-left + .pull-right
  {
    margin-top: <?php echo $base * 0.5; ?>px !important;
  }
}



/*  -----------------------------------------------------------------------------------------
    M O D U L E S
-----------------------------------------------------------------------------------------  */

/*  Banner   ----------------------------------------------------------------------------  */

.tb_module_banner .banner {
  margin: 0 auto;
}
.tb_module_banner .banner > div {
  display: none;
}
.tb_module_banner .banner > div:first-child {
  position: static !important;
  display: block !important;
}

/*  Carousel   --------------------------------------------------------------------------  */

.tb_module_carousel a,
.tb_module_carousel img
{
  display: block;
  margin: 0 auto;
}

/*  Category   --------------------------------------------------------------------------  */

.tb_module_category li > ul {
  display: none;
}
.tb_module_category li > .active {
  font-weight: bold;
}
.tb_module_category li > .active + ul {
  display: block;
}

/*  -----------------------------------------------------------------------------------------
    P A G E S
-----------------------------------------------------------------------------------------  */

/*  Account   ---------------------------------------------------------------------------  */

.login-content .content,
#new_customer
{
  min-height: <?php echo $base * 9; ?>px;
}
#login_form fieldset,
.account-login form fieldset,
.affiliate-login form fieldset
{
  min-width: 0;
  min-height: <?php echo $base * 7; ?>px;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #content .login-content > .left + .right {
    position: relative;
    padding-top: <?php echo $base * 1.5; ?>px;
  }
  #content .login-content > .left + .right:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    border-top: 1px solid;
    opacity: 0.2;
  }
  #new_customer,
  #new_customer fieldset,
  #login_form fieldset,
  #content .login-content .content,
  #content .account-login form fieldset,
  #content .affiliate-login form fieldset
  {
    min-height: 0;
  }
  #content .login-content .buttons {
    padding-top: 0;
  }
  #content .login-content .buttons:before {
    content: none;
  }
}

.tb_reward_points th.date,
.tb_transactions th.date {
  white-space: nowrap;
}
.tb_reward_points_total,
.tb_balance_total
{
  display: inline-block;
  margin-top: -0.07em;
  vertical-align: top;
  font-size: <?php echo $base_font_size * 1.5; ?>px;
}

.tb_order_info .tb_products tfoot td {
  border: none;
  box-shadow: none;
}
.tb_order_info .tb_products tfoot > tr > td {
  padding-top: 0;
  padding-bottom: 0;
}
.tb_order_info .tb_products tfoot > tr:first-child > td {
  padding-top: <?php echo $base; ?>px;
}
.tb_order_info .tb_products tfoot > tr:last-child > td {
  padding-top: <?php echo $base * 0.5; ?>px;
  padding-bottom: <?php echo $base; ?>px;
}
.tb_order_info .tb_products tfoot tr:last-child td {
  font-size: 150%;
}


.tb_product_row {
  position: relative;
  clear: both;
}
#return-product .tb_product_row:first-child {
  margin-top: 0 !important;
  border-top: 1px solid #eee;
}
<?php if ($lang_dir == 'rtl'): ?>
#return-product .tb_product_row .tb_button_1_small.tb_ddd_bgr {
  float: left;
  margin-left: 0;
}
<?php endif; ?>

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #new_customer fieldset,
  #login_form fieldset
  {
    min-height: 0;
  }
  #login_form .tb_button_forgotten {
    display: table-footer-group;
    float: none;
  }
  #login_form .btn {
    display: table-header-group;
    float: none;
  }
}


/*  ---   Brands   ----------------------------------------------------------------------  */

.tb_alphabet_index {
  padding: <?php echo $base / 2; ?>px <?php echo $base; ?>px;
  border-bottom-width: 1px;
  border-bottom-style: solid;
}
.tb_alphabet_index p > * {
  display: inline-block;
  line-height: <?php echo $base; ?>px;
  vertical-align: top;
}
.tb_alphabet_index p > b {
  text-transform: uppercase;
  letter-spacing: 2px;
  font-weight: normal;
  font-size: 11px;
  color: #999;
}
.tb_alphabet_index a {
  padding: 0 5px;
  text-align: center;
  font-size: 15px;
}
.manufacturer-list {
  position: relative;
  clear: both;
  overflow: hidden;
  padding: <?php echo $base; ?>px 0;
}
.manufacturer-list:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  display: block;
  width: 100%;
  border-bottom-width: 1px;
  border-bottom-style: solid;
  opacity: 0.1;
}
.manufacturer-list:last-child:after {
  border-bottom: none;
}
.manufacturer-heading {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  margin-right: <?php echo $base; ?>px;
  <?php else: ?>
  float: right;
  margin-left: <?php echo $base; ?>px;
  <?php endif; ?>
  width: 50px;
  text-align: center;
  font-size: 24px;
}
.manufacturer-content {
  overflow: hidden;
}
.manufacturer-content ul {
  display: inline-block;
  width: 24.99999%;
  margin-bottom: 0;
  vertical-align: top;
}

@media (min-width: <?php echo ($screen_xs + 1); ?>px) and (max-width: <?php echo $screen_sm - 1; ?>px) {
  .manufacturer-content ul,
  .manufacturer-content ul > li
  {
    display: inline-table !important;
    width: auto !important;
    vertical-align: top;
  }
  .manufacturer-content ul:not(:last-child),
  .manufacturer-content ul > li:not(:last-child)
  {
    <?php if ($lang_dir == 'ltr'): ?>
    margin-right: 1em;
    <?php else: ?>
    margin-left: 1em;
    <?php endif; ?>
  }
}
@media (max-width: <?php echo $screen_xs - 1; ?>px) {
  .manufacturer-content ul {
    display: block;
    width: auto !important;
  }
}

/*  ---   Cart   ---------------------------------------------------------------  */

#cart_modules {
  margin-bottom: <?php echo $base * 1.5; ?>px;
}
#cart_modules .ui-state-default,
#cart_modules .ui-state-default *
{
  color: inherit !important;
}
#cart_modules .ui-accordion-content {
  margin: 0;
  padding: <?php echo $base * 1.5; ?>px 0;
}
#cart_modules .ui-accordion-content-active:last-child {
  padding-bottom: <?php echo $base * 1.5; ?>px;
}
#cart_modules > h2,
#cart_modules > div
{
  border-top-color:  transparent !important;
  border-left-color:  transparent !important;
  border-right-color: transparent !important;
}
#cart_modules form > label {
  display: block;
  float: none;
  width: auto;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
#cart_modules form > input[type=text] {
  margin-top: <?php echo $base / 2; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
#cart_modules form > .button,
#cart_modules form > .btn
{
  height: <?php echo $base * 1.5; ?>px;
  margin-top: <?php echo $base / 2; ?>px;
  padding: 0 <?php echo $base / 2; ?>px;
  line-height: <?php echo $base * 1.5; ?>px;
  vertical-align: bottom;
}
#shipping_quote table.radio {
  width: 100% !important;
  max-width: 500px;
}
#shipping_quote .button,
#shipping_quote .btn
{
  margin-top: 0;
}

/*** Mobile ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .cart-content {
    width: 100%;
  }
  .cart-total,
  .cart-total table {
    float: none;
    width: 100%;
  }
}

/*  ---   Checkout   --------------------------------------------------------------------  */

.checkout #confirm + .error {
  margin-top: -20px !important;
}
.checkout-heading
{
  position: relative;
  cursor: default !important;
}
.checkout-heading a {
  position: absolute;
  <?php if ($lang_dir == 'ltr'): ?>
  right: <?php echo $base; ?>px;
  <?php else: ?>
  left: <?php echo $base; ?>px;
  <?php endif; ?>
  text-transform: uppercase;
  font-size: <?php echo $base_font_size; ?>px;
  cursor: pointer;
}
.tb_new_customer_box,
.tb_login_box
{
  min-height: <?php echo $base * 11; ?>px;
}
.checkout select[size] {
  width: 100%;
  margin-bottom: <?php echo $base / 2; ?>px;
}
.checkout > div:last-child .checkout-content {
  padding-bottom: 0;
}

/*** Mobile ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .checkout-heading a {
    display: block;
    position: static;
  }
  .payment table {
    font-size: 12px;
  }
  .payment td {
    padding-bottom: <?php echo $base / 2; ?>px;
  }
  .payment input[type=text],
  .payment input[type=password],
  .payment select
  {
    display: inline-block;
  }
}

/*  ---   Compare   ---------------------------------------------------------------------  */

.compare-info tbody td:first-child {
  text-align: inherit;
  font-weight: bold;
}
.compare-info tbody:last-child td {
  padding-top: <?php echo $base; ?>px;
  padding-bottom: 0;
  border-bottom: 0;
}
.compare-info .description {
  white-space: normal !important;
}
.tb_compare_total .tb_items {
  margin-left:  0.15em;
  margin-right: 0.15em;
}
.tb_compare_total .tb_items:before {
  content: '(';
}
.tb_compare_total .tb_items:after {
  content: ')';
}

/*  ---   Payments   --------------------------------------------------------------------  */

.cart-discounts > div {
  padding: <?php echo $base; ?>px;
  border-width: 5px;
  border-style: solid;
}

/*  ---   Product   ---------------------------------------------------------------------  */

#product {
  position: relative;
}
#review .pagination {
  overflow: visible;
  margin-bottom: 0;
}
#review .pagination > * {
  padding-top: <?php echo $base; ?>px;
}
#review .pagination .links {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 20px;
  <?php else: ?>
  margin-left: 20px;
  <?php endif; ?>
}
#review .pagination .results:first-child:last-child {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  padding-top: <?php echo $base * 1.25; ?>px;
}
#review + .btn {
  <?php if ($lang_dir == 'ltr'): ?>
  clear: right;
  float: right;
  <?php else: ?>
  clear: left;
  float: left;
  <?php endif; ?>
  margin-top: <?php echo $base; ?>px;
}
#review .tb_empty {
  float: left;
  width: 100%;
  margin-bottom: 0;
}

/*** Product images ***/

#product_images .tb_zoom_box {
  position: absolute;
  z-index: 2;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: block;
}
.is_touch #product_images .tb_zoom_box {
  display: none;
}
@media (max-width: <?php echo $screen_md . 'px'; ?>) {
  #product_images .tb_zoom_box {
    display: none;
  }
}
#product_images .tb_zoom_click {
  cursor: url('<?php echo $theme_catalog_image_url; ?>cursor_zoom.cur'), auto !important;
}
#product_images .tb_zoom_click.tb_zoomed {
  z-index: 6;
  cursor: url('<?php echo $theme_catalog_image_url; ?>cursor_zoom_out.cur'), auto !important;
}
#product_images .tb_zoom_mouseover:hover,
#product_images .tb_zoom_drag:hover
{
  z-index: 4;
}
#product_images .tb_zoom_mouseover {
  cursor: move;
}
#product_images .mSCover {
  background-size: contain !important;
}

/*** Product reviews ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .tb_product_reviews {
    text-align: center;
  }
  .tb_product_reviews .tbAddReviewButton {
    margin-top: <?php echo $base; ?>px;
  }
}


/*  ---  Returns  -----------------------------------------------------------------------  */

.return-product,
.return-detail
{
  clear: both;
  margin-top: <?php echo $base; ?>px;
  margin-bottom: <?php echo $base; ?>px;
  margin-left: -30px;
  margin-right: -30px;
  padding-right: 30px;
}
.return-product:after,
.return-detail:after
{
  content: '';
  display: table;
  clear: both;
}
.return-product > *,
.return-detail > *
{
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  width: 33.3333%;
  padding-left: 30px;
}
.return-reason table {
  margin-top: <?php echo $base * 0.25; ?>px;
}
.return-reason input[type=radio] {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.5em;
  <?php else: ?>
  margin-left: 0.5em;
  <?php endif; ?>
}
.return-opened b {
  display: inline-block;
  margin-bottom: <?php echo $base * 0.25; ?>px;
}

/*** Mobile ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #return_request_info table {
    font-size: 11px;
  }
  .return-product > *,
  .return-detail > *
  {
    float: none;
    width: 100%;
    margin: 20px 0;
  }
  .return-product:after,
  .return-detail:after
  {
    content: none;
  }
}

/*  ---   Sitemap   ---------------------------------------------------------------------  */

.tb_sitemap {
  -webkit-column-count: 3;
     -moz-column-count: 3;
          column-count: 3;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #sitemap .tb_list_1 {
    -webkit-column-count: 1;
       -moz-column-count: 1;
            column-count: 1;
  }
}

/*  ---   Wishlist   --------------------------------------------------------------------  */

.account-wishlist .tb_system_page_content table td:first-child {
  width: 1px;
}
.wishlist_total .tb_items {
  margin-left:  0.15em;
  margin-right: 0.15em;
}
.wishlist_total .tb_items:before {
  content: '(';
}
.wishlist_total .tb_items:after {
  content: ')';
}
