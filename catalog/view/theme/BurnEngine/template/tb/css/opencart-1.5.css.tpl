/*  -----------------------------------------------------------------------------------------
    C O M M O N
-----------------------------------------------------------------------------------------  */

.content:after,
.login-content:after
{
  content: '';
  clear: both;
  display: table;
}
.content .left,
.content .right,
.login-content > .left,
.login-content > .right
{
  width: 50%;
  padding-left: <?php echo $base * 0.75; ?>px;
}
.content .left,
.login-content > .left
{
  float: left;
  margin-left: -<?php echo $base * 0.75; ?>px;
}
.content .right:not(td):not(th),
.login-content > .right
{
  float: right;
}
.content + *,
.tb_text_wrap + .content
{
  margin-top: <?php echo $base * 1.5; ?>px;
}
.content + h2 {
  position: relative;
  padding-top: <?php echo $base * 1.5; ?>px;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .content,
  .login-content
  {
    margin-left: 0;
  }
  .content .left,
  .content .right,
  .login-content > .left,
  .login-content > .right
  {
    width: 100%;
    padding-left: 0;
    margin-left: 0;
  }
  .login-content > .left + .right,
  .content .left + .right
  {
    margin-top: <?php echo $base * 1.5; ?>px;
  }
}

table.list > thead > tr > td,
.cart-info thead > tr > td
{
  background-color: <?php echo $color_bg_str_2; ?>;
}


/*  -----------------------------------------------------------------------------------------
    F O R M S
-----------------------------------------------------------------------------------------  */

br + input[type=text],
br + input[type=password],
br + select,
br + textarea
{
  margin-top: <?php echo $base * 0.25; ?>px;
}
input[name=captcha] + br + img {
  margin-top: <?php echo $base * 0.5; ?>px;
}
input[type=checkbox] ~ input[type=checkbox],
input[type=checkbox] ~ input[type=radio],
input[type=radio] ~ input[type=radio],
input[type=radio] ~ input[type=checkbox]
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 10px;
  <?php else: ?>
  margin-right: 10px;
  <?php endif; ?>
}
input[type=checkbox] ~ br + input[type=checkbox],
input[type=radio] ~ br + input[type=checkbox],
input[type=checkbox] ~ br + input[type=radio],
input[type=radio] ~ br + input[type=radio]
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 0;
  <?php else: ?>
  margin-right: 0;
  <?php endif; ?>
}
input[type=checkbox] + label,
input[type=radio] + label
{
  display: inline;
  float: none;
  width: auto;
}
input[type=checkbox] ~ br + input[type=checkbox] + label,
input[type=radio] ~ br + input[type=checkbox] + label,
input[type=checkbox] ~ br + input[type=radio] + label,
input[type=radio] ~ br + input[type=radio] + label
{
  display: inline-block;
  margin-top: <?php echo $base * 0.5; ?>px;
  vertical-align: bottom;
}

table.form td {
  vertical-align: top;
  padding-bottom: <?php echo $base; ?>px;
  line-height: <?php echo $form_control_height; ?>px;
}
table.form td:first-child {
  width: 150px;
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  padding-left: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
  line-height: <?php echo $base; ?>px;
}
table.form :last-child > tr:last-child td {
  padding-bottom: 0;
}
table.form tr:last-child td:first-child {
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
}
table.form td:only-child {
  padding-bottom: <?php echo $base; ?>px;
}
table.form :last-child > tr:last-child td:only-child {
  padding-bottom: 0;
}
table.form td:only-child br + input[type=text],
table.form td:only-child br + input[type=email],
table.form td:only-child br + input[type=password],
table.form td:only-child br + select,
table.form td:only-child br + textarea
{
  margin-top: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
}
table.form td:only-child .button,
table.form td:only-child button
{
  margin-bottom: <?php echo $base; ?>px;
}
table.form input[type=checkbox],
table.form input[type=radio]
{
  vertical-align: middle;
  margin-top: -0.15em;
}
table.form td > input[type=checkbox],
table.form td > input[type=radio]
{
  margin-top: 0;
}
table.form .has-error td:last-child > :first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
}
table.form td .error {
  margin-top: 0;
  margin-bottom: 0;
}
table.radio {
  width: auto;
  table-layout: auto;
  padding: 0;
}
table.radio tr td {
  padding: <?php echo $base * 0.75; ?>px 0 0 0;
}
table.radio tr:first-child td
{
  padding: 0;
}
table.radio tr.highlight td {
  padding: <?php echo $base / 4; ?>px 0;
}
table.radio tr.highlight td:first-child {
  width: 10px;
  vertical-align: top;
}
table.radio label {
  width: auto;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
}
table.radio input {
  position: static;
  margin-top: 0 !important;
}

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  table.form td:first-child {
    padding-top: <?php echo $base / 2; ?>px;
    padding-bottom: <?php echo $base + $base / 2; ?>px;
  }
  table.form td > input[type=checkbox]:first-child + label,
  table.form td > input[type=radio]:first-child + label
  {
    margin-top: <?php echo $base / 2; ?>px;
  }
}
@media (max-width: <?php echo $screen_xs . 'px'; ?>) {
  table.form,
  table.form thead,
  table.form tbody,
  table.form tr,
  table.form td
  {
    display: block;
    width: 100%;
    margin-left: 0;
    margin-right: 0;
  }
  table.form td:first-child {
    padding-top: 0;
    padding-bottom: <?php echo $base / 4; ?>px !important;
  }
}

/*  -----------------------------------------------------------------------------------------
    C O M P O N E N T S
-----------------------------------------------------------------------------------------  */

/*  Alerts   ----------------------------------------------------------------------------  */

.success,
.attention,
.warning,
.information
{
  margin-bottom: <?php echo $base * 1.5; ?>px;
  padding: <?php echo $base; ?>px;
  text-align: center;
  font-size: 13px;
  font-weight: bold;
}
.success img[src*="close.png"],
.warning img[src*="close.png"],
.attention img[src*="close.png"],
.information img[src*="close.png"]
{
  display: none;
}
.success {
  color: green;
  background: #f4fbe4;
  border: 1px solid #e3f5bd;
}
.attention {
  color: #726300;
  background: #fffcd9;
  border: 1px solid #f0e190;
}
.warning {
  color: red;
  background: #ffede5;
  border: 1px solid #ffd8c3;
}
.information {
  color: #506778;
  background: #e9f6ff;
  border: 1px solid #c8e0f0;
}

/*  Loading indicator   -----------------------------------------------------------------  */

span.wait img {
  display: none !important;
}

/*  -----------------------------------------------------------------------------------------
    M O D U L E S
-----------------------------------------------------------------------------------------  */

.box-heading > * {
  line-height: inherit !important;
  letter-spacing: inherit !important;
  word-spacing: inherit !important;
  text-transform: inherit !important;
  font: inherit !important;
  color: inherit !important;
}
.box .box-heading img {
  display: inline-block;
  vertical-align: top;
}

/*  -----------------------------------------------------------------------------------------
    P A G E S
-----------------------------------------------------------------------------------------  */

table th.name,
table td.name
{
  width: 100%;
}

/*  ---   Account   ---------------------------------------------------------------------  */

.tb_addresses .content {
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: <?php echo $base; ?>px;
  <?php else: ?>
  padding-right: <?php echo $base; ?>px;
  <?php endif; ?>
}
.tb_addresses .content:before {
  content: '';
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
  display: block;
  height: 100%;
  border-left: 5px solid;
  opacity: 0.2;
}
.tb_addresses .content td {
  vertical-align: top;
}
.tb_addresses .content td:first-child:first-line {
  font-weight: bold;
}

.tb_downloads th:not(.name),
.tb_orders th,
.tb_order_history td:not(:last-child)
{
  width: 1px;
  white-space: nowrap;
}

/*** Downloads ***/

.tb_downloads td {
  vertical-align: middle;
}
.tb_downloads .name {
  min-width: 150px;
}

/*** Orders ***/

.tb_orders .actions {
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: right;
  <?php else: ?>
  text-align: left;
  <?php endif; ?>
}

/*  ---   Checkout   --------------------------------------------------------------------  */

.checkout-content {
  display: none;
  padding: <?php echo $base * 1.5; ?>px 0;
}

/*  ---   Product   ---------------------------------------------------------------------  */

/*** Info ***/

.description span {
  font-weight: bold;
}

/*** Images ***/

.tb_wt_product_images_system .left {
  display: none;
}

/*** Options ***/

.options br {
  display: none;
}
.options .option {
  overflow: hidden;
  margin-top: <?php echo $base; ?>px;
}
.options .option:first-child {
  margin-top: 0;
}
.options .option b,
.options .option .required
{
  display: inline-block;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
}
.options .option .required {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: 3px;
  <?php else: ?>
  padding-left: 3px;
  <?php endif; ?>
}
.options .option b {
  max-width: 31%;
}
.options .option:last-child .error {
  margin-bottom: 0;
}
.options .tb_checkbox_row .tb_group,
.options .tb_radio_row .tb_group
{
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
}
.options .tb_group:after {
  content: '';
  clear: both;
  display: table;
}
.options .option .tb_group,
.options #profile-description
{
  <?php if ($lang_dir == 'ltr'): ?>
  float: right;
  margin-right: -<?php echo $base * 0.5; ?>px;
  padding-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  float: left;
  margin-left: -<?php echo $base * 0.5; ?>px;
  padding-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
  width: 68.5%;
}
.options #profile-description:empty {
  display: none;
}
.options .tb_style_2 {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.options .tb_style_2 > span {
  -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.options .tb_style_2 > b
{
  -ms-flex: 1 0 auto;
  -webkit-flex: 1 0 auto;
          flex: 1 0 auto;
}
.options .tb_checkbox_row.tb_style_2 .tb_group,
.options .tb_radio_row.tb_style_2 .tb_group,
.options .tb_image_row.tb_style_2 .tb_group,
.options #profile-description
{
  -ms-flex-item-align: center;
   -webkit-align-self: center;
           align-self: center;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: 0;
  <?php else: ?>
  padding-left: 0;
  <?php endif; ?>
}
.options .option input[type=checkbox] {
  position: relative;
  top: -1px;
}
.options .option input[type=radio] + label + br,
.options .option input[type=checkbox] + label + br
{
  display: block;
  margin-bottom: <?php echo $base / 2; ?>px;
}
.options .option input[type=radio] + label + br + input,
.options .option input[type=checkbox] + label + br + input
{
  margin-left: 0;
  margin-right: 0;
}
.options .option input[type=radio] + label + br:last-child,
.options .option input[type=checkbox] + label + br:last-child
{
  margin-bottom: 0;
}
.options .option input[type=button] {
  height: <?php echo $base + 6; ?>px;
  line-height: <?php echo $base + 6; ?>px;
  margin-top: <?php echo $base / 4 - 3; ?>px;
  margin-bottom: <?php echo $base / 4 - 3; ?>px;
  padding: 0 <?php echo $base / 2; ?>px;
  text-transform: none;
  letter-spacing: 0;
  vertical-align: baseline;
}
.options .option input[type=button] + .wait {
  margin-top: -0.26em;
}
.options .option + .error,
.options .option > .error
{
  display: block;
  min-width: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 31.5%;
  padding-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-right: 31.5%;
  padding-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
  margin-top: <?php echo $base * 0.5; ?>px;
}
.options .option-image {
  table-layout: auto;
}
.options .option-image td label {
  width: auto;
}
.options .option-image td img {
  display: inline;
  float: none;
  vertical-align: top;
}
.options .option-image td:first-child + td label {
  margin-left: <?php echo $base / 2; ?>px;
  margin-right: <?php echo $base / 2; ?>px;
}
.options .option-image td:first-child + td + td {
  width: 100%;
}
.options #profile-description {
  display: block;
  margin-top: <?php echo $base / 4; ?>px;
}
.options .tb_checkbox_row.tb_style_2 input[type=checkbox],
.options .tb_radio_row.tb_style_2 input[type=radio]
{
  display: none;
}
.options .tb_radio_row.tb_style_2 label,
.options .tb_checkbox_row.tb_style_2 label
{
  display: block;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
  min-width: <?php echo $form_control_height; ?>px;
  text-align: center;
  cursor: pointer;
}
.options .tb_radio_row.tb_style_2 .tb_group,
.options .tb_checkbox_row.tb_style_2 .tb_group,
.options .tb_image_row.tb_style_2 .tb_group
{
  margin-top: -<?php echo $base / 2; ?>px;
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}
.options .tb_radio_row.tb_style_2 .tb_group br,
.options .tb_checkbox_row.tb_style_2 .tb_group br
{
  display: none;
}
.options .tb_image_row.tb_style_2 label {
  line-height: 10px;
}
.options .tb_image_row.tb_style_2 table,
.options .tb_image_row.tb_style_2 table tbody,
.options .tb_image_row.tb_style_2 table tr,
.options .tb_image_row.tb_style_2 table td
{
  display: block;
  width: auto;
}
.options .tb_image_row.tb_style_2 table tbody:after {
  content: '';
  display: table;
  clear: both;
}
.options .tb_image_row.tb_style_2 tr {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  margin: <?php echo $base / 2; ?>px <?php echo $base / 2; ?>px 0 0;
  <?php else: ?>
  float: right;
  margin: <?php echo $base / 2; ?>px 0 0 <?php echo $base / 2; ?>px;
  <?php endif; ?>
}
.options .tb_image_row.tb_style_2 td:first-child,
.options .tb_image_row.tb_style_2 td:first-child + td + td
{
  display: none;
}
.options .tb_image_row.tb_style_2 label {
  margin: 0 !important;
  padding: 3px;
  cursor: pointer;
}
.options .tb_image_row.tb_style_2 label img {
  padding: 1px;
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
.tb_max_w_300 .tb_checkbox_row,
.tb_max_w_300 .tb_radio_row,
.tb_max_w_300 .tb_image_row
{
  display: block;
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
.tb_max_w_300 .option .tb_group {
  padding-right: 0;
  padding-left: 0;
}

/*  ---   Search   ----------------------------------------------------------------------  */

#adv_search_box input[type=text],
#adv_search_box select,
#adv_search_box a.tb_button_1
{
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  margin-right: 10px;
  <?php else: ?>
  float: right;
  margin-left: 10px;
  <?php endif; ?>
}
#adv_search_box select {
  width: 150px;
}
#adv_search_box .tb_button_1 {
  width: 80px;
  margin-left: 0;
}
#adv_search_box .tb_button_1 .tb_text {
  text-align: center;
}
#adv_search_box > :last-child {
  margin-bottom: 0;
}

