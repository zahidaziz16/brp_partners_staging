button {
  overflow: visible;
}
button,
html input[type="button"],
input[type="reset"],
input[type="submit"]
{
  -webkit-appearance: button;
  cursor: pointer;
}
button[disabled],
html input[disabled]
{
  cursor: default;
}
button::-moz-focus-inner,
input::-moz-focus-inner
{
  border: 0;
  padding: 0;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button
{
  height: auto;
}
input[type="search"] {
  -webkit-appearance: textfield;
}
input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration
{
  -webkit-appearance: none;
}


fieldset {
  margin-bottom: <?php echo $base * 1.5; ?>px;
  border: 0 none;
}
fieldset legend {
  display: block;
  width: 100%;
  margin-bottom: <?php echo $base; ?>px;
}
fieldset legend + * {
  clear: both;
}
fieldset + fieldset {
  margin-top: <?php echo $base * 1.5; ?>px;
}
fieldset + fieldset legend {
  position: relative;
  padding-top: <?php echo $base * 1.5; ?>px;
}
fieldset:last-child,
fieldset > :last-child
{
  margin-bottom: 0;
  padding-bottom: 0;
}

label {
  display: inline-block;
  max-width: 100%;
  vertical-align: top;
}
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
.input-group,
.form-control
{
  width: 220px;
  max-width: 100%;
  border-width: <?php echo $form_border_width; ?>px;
  border-style: solid;
  border-radius: 2px;
  resize: none;
}
.tb_full .form-control {
  width: 100%;
}
.ui-spinner {
  height: <?php echo $form_control_height; ?>px;
}
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
.input-group
{
  height:        <?php echo $form_control_height; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $form_control_height) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $form_control_height) / 2; ?>px;
  padding:       <?php echo ($form_control_height - $base - $form_border_width * 2) / 2; ?>px <?php echo ($form_control_height - $base) / 2; ?>px;
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
}
textarea:not([style*="height"]) {
  height: auto;
}
select {
  padding-left:  <?php echo ($form_control_height - $base - $form_border_width * 2) / 2; ?>px;
  padding-right: <?php echo ($form_control_height - $base - $form_border_width * 2) / 2; ?>px;
}
input.input-sm,
select.input-sm,
textarea.input-sm,
.input-group-sm,
.input-group-sm input,
.form-group-sm .form-control
{
  height:        <?php echo $form_control_height_sm; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $form_control_height_sm) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $form_control_height_sm) / 2; ?>px;
  padding:       <?php echo ($form_control_height_sm - $base - $form_border_width * 2) / 2; ?>px <?php echo ($form_control_height_sm - $base) / 2; ?>px;
  font-size:     <?php echo $base_font_size - floor($base_font_size * 0.15); ?>px;
}
select.input-sm,
.form-group-sm select.form-control
{
  padding-left:  <?php echo ($form_control_height_sm - $base - $form_border_width * 2) / 2; ?>px;
  padding-right: <?php echo ($form_control_height_sm - $base - $form_border_width * 2) / 2; ?>px;
}
.form-group-sm .control-label {
  font-size:     <?php echo $base_font_size - ceil($base_font_size * 0.15); ?>px;
}

input.input-lg,
select.input-lg,
textarea.input-lg,
.input-group-lg,
.input-group-lg input,
.form-group-lg .form-control
{
  height:        <?php echo $form_control_height_lg; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $form_control_height_lg) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $form_control_height_lg) / 2; ?>px;
  padding:       <?php echo ($form_control_height_lg - $base - $form_border_width * 2) / 2; ?>px <?php echo ($form_control_height_lg - $base) / 2; ?>px;
  font-size:     <?php echo $base_font_size + ceil($base_font_size * 0.15); ?>px;
}
select.input-lg,
.form-group-lg select.form-control
{
  padding-left:  <?php echo ($form_control_height_lg - $base - $form_border_width * 2) / 2; ?>px;
  padding-right: <?php echo ($form_control_height_lg - $base - $form_border_width * 2) / 2; ?>px;
}
.form-group-lg .control-label {
  font-size:     <?php echo $base_font_size + ceil($base_font_size * 0.15); ?>px;
}
input.input-xl,
select.input-xl,
textarea.input-xl,
.input-group-xl,
.input-group-xl input,
.form-group-xl .form-control
{
  height:        <?php echo $form_control_height_xl; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $form_control_height_xl) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $form_control_height_xl) / 2; ?>px;
  padding:       <?php echo ($form_control_height_xl - $base - $form_border_width * 2) / 2; ?>px <?php echo ($form_control_height_xl - $base) / 2; ?>px;
  font-size:     <?php echo $base_font_size + ceil($base_font_size * 0.3); ?>px;
}
select.input-xl,
.form-group-xl select.form-control
{
  padding-left:  <?php echo ($form_control_height_xl - $base - $form_border_width * 2) / 2; ?>px;
  padding-right: <?php echo ($form_control_height_xl - $base - $form_border_width * 2) / 2; ?>px;
}
.form-group-xl .control-label {
  font-size:     <?php echo $base_font_size + ceil($base_font_size * 0.3); ?>px;
}

input[type=number] {
  -moz-appearance: textfield;
  -webkit-appearance: textfield;
}
input,
select,
textarea
{
  width: 220px;
}
input[type=radio],
input[type=checkbox],
input[type=submit],
input[type=button],
input[type=file],
input[type=image],
input[size]
{
  width: auto;
}
table:not(.form) input:not([type=radio]):not([type=checkbox]):not([size]),
table:not(.form) select,
table:not(.form) textarea,
table:not(.form) button,
table:not(.form) .ui-spinner,
table:not(.form) .input-group
{
  width: auto;
  margin-left: 0;
  margin-right: 0;
}
input + button,
input + .btn,
input + .button,
input + .btn
{
  vertical-align: top;
}
input[type=radio],
input[type=checkbox],
input[type=image]
{
  height: auto;
  vertical-align: middle;
}
input[type=radio],
input[type=checkbox]
{
  width:  <?php echo max($base_font_size, 13); ?>px;
  height: <?php echo max($base_font_size, 13); ?>px;
}
img[src*="captcha"] {
  vertical-align: top;
}
textarea {
  width: 350px;
  padding-bottom: <?php echo $base/2; ?>px !important;
  resize: vertical;
  vertical-align: top;
}
select[size],
textarea[rows]
{
  height: auto !important;
}
button {
  height: 40px;
  border: none;
  background: #ddd;
}
input[type=hidden],
.help-block:empty
{
  display: none;
}
input:hover,
input:focus,
textarea:hover,
textarea:focus,
select:hover,
select:focus,
button:hover,
button:focus
{
  outline: 0 none !important;
}
input:-webkit-autofill,
textarea:-webkit-autofill,
select:-webkit-autofill
{
  background-color: transparent;
}



label.tb_disabled, label.tb_disabled * {
  color: #999 !important;
}
label .tb_legend {
  margin: 0 !important;
  padding-top: 0;
  line-height: 11px;
}

.checkbox,
.radio,
.checkbox-inline,
.radio-inline
{
  position: relative;
      -ms-flex: 0 0 auto !important;
  -webkit-flex: 0 0 auto !important;
          flex: 0 0 auto !important;
  width: auto !important;
  min-width: 0 !important;
  min-height: <?php echo $form_control_height; ?>px;
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 1.4em;
  text-align: left;
  <?php else: ?>
  padding-right: 1.4em;
  text-align: right;
  <?php endif; ?>
  font-weight: normal;
}
.checkbox:before,
.radio:before,
label.checkbox-inline:before,
label.radio-inline:before
{
  content: '';
  display: none;
}
.checkbox,
.radio
{
  display: block;
  width: auto;
}
.checkbox-inline,
.radio-inline
{
  width: auto !important;
}
.radio-inline + .radio-inline,
.checkbox-inline + .checkbox-inline
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
}
.checkbox input,
.radio input,
.checkbox-inline input,
.radio-inline input
{
  position: absolute;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
  top: 50%;
  margin: -6px 0 0 0 !important;
}
.checkbox label,
.radio label
{
  vertical-align: top !important;
}
label.checkbox input,
label.checkbox-inline input
{
  margin: -6px 0 0 0 !important;
}
label.tb_image {
  overflow: hidden;
  display: table;
  width: 100% !important;
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}
label.tb_image img {
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  margin-right: 10px;
  <?php else: ?>
  float: right;
  margin-left: 10px;
  <?php endif; ?>
  max-width: none;
}
label.tb_image .tb_label {
  display: table-cell;
  width: 100%;
  vertical-align: middle;
}
.form-group {
  clear: both;
  margin-top: <?php echo $base; ?>px;
  margin-bottom: <?php echo $base; ?>px;
}
.form-group[class*="col-"] {
  margin-top: 0;
  margin-bottom: 0;
}
.form-group:first-child,
input[type="hidden"]:first-child + .form-group,
input[type="hidden"]:first-child + input[type="hidden"] + .form-group,
input[type="hidden"]:first-child + input[type="hidden"] + input[type="hidden"] + .form-group,
input[type="hidden"]:first-child + input[type="hidden"] + input[type="hidden"] + input[type="hidden"] + .form-group
{
  margin-top: 0;
}
.form-group:last-child {
  margin-bottom: 0;
}
.form-group:after
{
  content: '';
  display: table;
}
.form-group:after {
  clear: both;
}
.form-group[style*="display: none"],
fieldset[style*="display: none"]
{
  overflow: hidden;
  clear: both;
  display: block !important;
  height: 0;
  margin-top:    0 !important;
  margin-bottom: 0 !important;
}
.form-group[style*="display: none"]
{
  display: -webkit-flex !important;
  display: -ms-flexbox !important;
  display: flex !important;
}
fieldset[style*="display: none"] {
  margin-top: -<?php echo $base * 1.5; ?>px;
}
.form-group > label {
  vertical-align: baseline;
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
}
.form-group.form-group-sm > label {
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $base) / 2; ?>px;
}
.form-group.form-group-lg > label {
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $base) / 2; ?>px;
}
.form-group.form-group-xl > label {
  padding-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $base) / 2; ?>px;
}
.form-group > label[class*="col-"] {
  display: block;
  min-width: 100px;
  padding-left: 0;
  padding-right: 0;
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $base) / 2; ?>px;
}
.form-group.form-group-sm > label[class*="col-"] {
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $base) / 2; ?>px;
}
.form-group.form-group-lg > label[class*="col-"] {
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $base) / 2; ?>px;
}
.form-group.form-group-xl > label[class*="col-"] {
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $base) / 2; ?>px;
}

.form-group > label[class*="col-"] + div[class*="col-"] {
  display: block;
}
.form-group > label[class*="col-"] + div[class*="col-"] .input-group {
  display: inline-table;
  display: -webkit-inline-flex;
  display: inline-flex;
      -ms-flex-wrap: nowrap;
  -webkit-flex-wrap: nowrap;
          flex-wrap: nowrap;
  vertical-align: top;
}
.form-group > label + div[class*="col-"]:after {
  content: '';
  display: table;
  clear: both;
}
.form-group > div > input[type="radio"],
.form-group > div > input[type="checkbox"]
{
  -ms-flex-item-align: center;
  -webkit-align-self: center;
  align-self: center;
}
.form-group select + .help-block,
.form-group textarea + .help-block
{
  padding-top: <?php echo $base * 0.5; ?>px;
  font-size: <?php echo $base_font_size * 0.85; ?>px;
}
.form-group .help-block .text-danger {
  min-width: 0;
  margin: 0;
  padding: 0;
}
.checkbox > label,
.radio > label
{
  display: inline-block;
  float: none;
  width: auto !important;
  margin-left: 0;
  margin-right: 0;
}
.checkbox.image > label > *,
.radio.image > label > *
{
  vertical-align: middle;
}
.required > label:before {
  content: "* ";
  /*
  -ms-flex-item-align: start;
   -webkit-align-self: flex-start;
           align-self: flex-start;
  */
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.3em;
  <?php else: ?>
  margin-left: 0.3em;
  <?php endif; ?>
  font-weight: bold;
  color: #f00;
}
.product-info .form-group > label + div {
  overflow: hidden;
}

/*** Horizontal form ***/

.form-horizontal .radio,
.form-horizontal .checkbox,
.form-horizontal .radio-inline,
.form-horizontal .checkbox-inline
{
  margin-top:    0;
  margin-bottom: 0;
}
.form-horizontal .form-group {
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
  margin-top:  0;
  margin-left: 0;
}
.form-horizontal .form-group > label,
.form-horizontal .form-group > [class*="col-"]
{
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
      -ms-flex: 1 1 0px !important;
  -webkit-flex: 1 1 0px !important;
          flex: 1 1 0px !important;
  width: auto;
  margin-top:  0;
  margin-left: 0;
}
.form-horizontal .form-group > label {
  -ms-flex-item-align: start;
   -webkit-align-self: flex-start;
           align-self: flex-start;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  width:     150px;
  max-width: 150px;
  min-width: 100px;
  min-height: <?php echo $form_control_height; ?>px;
  padding-top: 0;
  padding-bottom: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  padding-left:  <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
}
.form-horizontal .form-group.form-group-sm > label {
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $base) / 2; ?>px;
}
.form-horizontal .form-group.form-group-lg > label {
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $base) / 2; ?>px;
}
.form-horizontal .form-group.form-group-xl > label {
  padding-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $base) / 2; ?>px;
}
.form-horizontal .form-group > div[class*="col-"] {
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
.form-horizontal .form-group > div[class*="col-"] > * {
      -ms-flex: 0 1 auto;
  -webkit-flex: 0 1 auto;
          flex: 0 1 auto;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
       -ms-flex-align: start;
  -webkit-align-items: flex-start;
          align-items: flex-start;
}
.form-horizontal .form-group > div[class*="col-"] > *:not(:last-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left:  <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.form-horizontal .form-group > .checkbox,
.form-horizontal .form-group > .radio
{
      -ms-flex: 1 1 100% !important;
  -webkit-flex: 1 1 100% !important;
          flex: 1 1 100% !important;
  width:        100% !important;
  max-width:    100%;
  margin-left:  0;
}

@media (min-width: 769px) {
  .form-horizontal .control-label {
    margin-bottom: 0;
  }
}

/*** Vertical form ***/

.form-vertical .form-group > label {
  display: block;
  float: none;
  width: 100%;
  padding-top: 0;
  font-weight: bold;
}
.form-vertical .form-group > label,
.form-vertical .form-group input:not([type=radio]):not([type=checkbox]),
.form-vertical .form-group select,
.form-vertical .form-group textarea,
.form-vertical .form-group .ui-spinner,
.form-vertical .form-group .input-group
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0;
  <?php else: ?>
  margin-left: 0;
  <?php endif; ?>
}
.form-vertical .form-group > .col-xs-12 {
  width: 100%;
  max-width: none;
}
<?php foreach ($breakpoints as $breakpoint): ?>
<?php if ($breakpoint != 'xs'): ?>
@media (min-width: <?php echo (${'screen_' . $breakpoint} + 1) . 'px'; ?>) {
  .form-vertical .form-group > .col-<?php echo $breakpoint; ?>-12 {
    width: 100%;
    max-width: none;
  }
}
<?php endif; ?>
<?php endforeach; ?>

.form-vertical .form-group > .col-sm-12
{
  margin-top: 0;
  margin-left: 0;
}
.form-vertical .help-block {
  margin-bottom: 0;
}

.form-inline label:not(.btn) {
  width: auto;
  margin-right: 0.5em;
  padding: 0;
  vertical-align: middle;
}
.form-inline .form-control {
  display: inline-block;
  width: auto;
  vertical-align: middle;
}
.form-inline .form-control-static {
  display: inline-block;
}
.form-inline .input-group {
  display: inline-table;
  display: inline-flex;
  vertical-align: middle;
}
.form-inline .input-group .input-group-addon,
.form-inline .input-group .input-group-btn,
.form-inline .input-group .form-control {
  width: auto;
}
.form-inline .input-group > .form-control {
  width: 100%;
}
.form-inline .control-label {
  margin-bottom: 0;
  vertical-align: middle;
}
.form-inline .radio,
.form-inline .checkbox {
  display: inline-block;
  margin-top: 0;
  margin-bottom: 0;
  vertical-align: middle;
}
.form-inline .radio label,
.form-inline .checkbox label {
  padding-left: 0;
}
.form-inline .has-feedback .form-control-feedback {
  top: 0;
}
.form-inline .form-group {
  display: inline-block;
  margin-top: 0;
  margin-bottom: 0;
  vertical-align: middle;
}
@media (max-width: <?php echo $screen_xs; ?>px) {
  .form-inline .form-group + .btn {
    width: 100%;
    margin-top: 20px;
  }
}

/*** BurnEngine ***/

.text-danger,
.error
{
  display: inline-block;
  min-width: 260px;
  max-width: 100%;
  margin-top: <?php echo $base/4; ?>px;
  margin-bottom: <?php echo $base/4; ?>px;
  font-size: 11px;
  color: red;
  vertical-align: top;
}
.text-danger:before,
.error:before
{
  content: '\f00d\0020\0020';
  font-family: "FontAwesome";
  font-size: 12px;
  vertical-align: top;
}
.text-danger:empty,
.error:empty
{
  display: none;
}
div:not(.input-group) + .text-danger {
  display: block;
  clear: both;
}
.tb_blocked {
  position: relative;
  min-height: 160px;
}
.tb_blocked:before {
  content: '';
  position: absolute;
  z-index: 10;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: block;
  background-color: #fff;
  opacity: 0.8;
}

/*  -----------------------------------------------------------------------------------------
    M O B I L E   max-width: 767px
-----------------------------------------------------------------------------------------  */

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
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
  .input-group
  {
    height: <?php echo $base * 2; ?>px;
    padding: <?php echo $base / 2 - 1; ?>px;
  }
  select {
    padding: <?php echo $base / 2 - 2; ?>px;
  }
  textarea {
    width: 100%;
    padding: <?php echo $base / 2 - 1; ?>px;
  }
  input[type=checkbox],
  input[type=radio]
  {
    margin-left: 0;
    margin-right: 0;
  }
  input[type=checkbox]:first-child + label ~ label,
  input[type=radio]:first-child + label ~ label
  {
    display: inline-block;
    margin-top: <?php echo $base / 2; ?>px;
  }
  input[type=checkbox] + label + br,
  input[type=radio] + label + br
  {
    display: block;
  }
  input[type=checkbox] + label + br:last-child,
  input[type=radio] + label + br:last-child
  {
    margin-bottom: <?php echo $base / 2; ?>px !important;
  }
  table.radio {
    width: 100%;
  }
  .text-danger,
  .error
  {
    min-width: 0;
  }

  .form-horizontal [class*="col-"] > input:not([type=radio]):not([type=checkbox]):not([type=button]),
  .form-horizontal [class*="col-"] > select,
  .form-horizontal [class*="col-"] > textarea,
  .form-horizontal [class*="col-"] > .input-group,
  .form-vertical .form-group [class*="col-"]
  {
    width: 100%;
  }
  .form-horizontal [class*="col-"] > select:not(:last-child) {
    <?php if ($lang_dir == 'ltr'): ?>
    margin-right: -60px;
    <?php else: ?>
    margin-left: -60px;
    <?php endif; ?>
  }
}

/*  -----------------------------------------------------------------------------------------
    M O B I L E   max-width: 480px
-----------------------------------------------------------------------------------------  */

@media (max-width: <?php echo $screen_xs . 'px'; ?>) {
  label,
  input:not([type=radio]):not([type=checkbox]),
  select,
  textarea,
  #country_id,
  #zone_id,
  select[name="customer_group_id"]
  {
    width: 100%;
    margin-left: 0;
    margin-right: 0;
  }
  label {
    width:      auto !important;
    max-width:  none !important;
    min-height: 0 !important;
  }
  input[type=checkbox] + label + br:last-child,
  input[type=radio] + label + br:last-child
  {
    display: none;
  }
  select:not(:last-child) {
    <?php if ($lang_dir == 'ltr'): ?>
    margin-right: -60px;
    <?php else: ?>
    margin-left: -60px;
    <?php endif; ?>
  }
  .form-group > label + div[class*="col-"]:not([class*="col-xs"]),
  .form-group > label + div[class*="col-"]:not([class*="col-xs"]) > div
  {
    clear: both;
      -ms-flex: 1 0 auto !important;
  -webkit-flex: 1 0 auto !important;
          flex: 1 0 auto !important;
      -ms-flex: 1 1 auto !important;
  -webkit-flex: 1 1 auto !important;
          flex: 1 1 auto !important;
    width: auto;
    max-width: none;
  }
  .form-horizontal .form-group {
    display: block;
    margin-top: 0;
  }
  .form-horizontal .form-group > label,
  .form-group > label + div[class*="col-"]:not([class*="col-xs"])
  {
    margin-top: 0;
    padding-top: 0 !important;
  }
  .form-horizontal .form-group > label {
    margin-bottom: <?php echo $base * 0.5; ?>px;
  }
  .form-inline .form-group {
    display: block;
    margin-right: 0 !important;
    margin-left:  0 !important;
  }
}
