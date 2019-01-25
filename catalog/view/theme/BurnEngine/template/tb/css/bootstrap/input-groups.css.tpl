.input-group {
  position: relative;
  display: table;
  display: -ms-inline-flexbox;
  display: -webkit-inline-flex;
  display: inline-flex;
      -ms-flex-wrap: nowrap !important;
  -webkit-flex-wrap: nowrap !important;
          flex-wrap: nowrap !important;
  padding: 0;
  border-collapse: separate;
}
.input-group[class*="col-"] {
  float: none;
  padding-left: 0;
  padding-right: 0;
}
.input-group > * {
  display: flex !important;
  min-width: 0;
  margin-top:    -<?php echo $form_border_width; ?>px !important;
  margin-bottom: -<?php echo $form_border_width; ?>px !important;
}
.input-group > :first-child:not(input):not(:last-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left:  -<?php echo $form_border_width; ?>px;
  <?php else: ?>
  margin-right: -<?php echo $form_border_width; ?>px;
  <?php endif; ?>
}
.input-group > :last-child:not(input):not(:first-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: -<?php echo $form_border_width; ?>px;
  <?php else: ?>
  margin-left:  -<?php echo $form_border_width; ?>px;
  <?php endif; ?>
}
.input-group > input,
.input-group > select
{
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  border: 0 none !important;
  border: 0 none !important;
  background-color: transparent !important;
}
.input-group .form-control {
  position: relative;
  z-index: 2;
  float: left;
}
.input-group-addon,
.input-group-btn,
.input-group .form-control
{
  display: table-cell;
}
.input-group-addon,
.input-group-btn
{
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.input-group-addon:not(:first-child):not(:last-child),
.input-group-btn:not(:first-child):not(:last-child),
.input-group .form-control:not(:first-child):not(:last-child) {
  border-radius: 0;
}
.input-group-addon,
.input-group-btn
{
  position: relative;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  width: 1%;
  width: auto;
  white-space: nowrap;
  vertical-align: middle;
}
.input-group-btn,
.input-group-addon
{
  min-width: <?php echo $form_control_height; ?>px;
}
.input-group-addon {
  padding:   <?php echo $base * 0.25; ?>px <?php echo $base * 0.5; ?>px;
  text-align: center;
  border-radius: inherit;
  background-color: rgba(0, 0, 0, 0.07);
  background-origin: border-box;
}
.input-group-sm .input-group-btn,
.input-group-sm .input-group-addon
{
  min-width: <?php echo $form_control_height_sm; ?>px;
}
.input-group-lg .input-group-btn,
.input-group-lg .input-group-addon
{
  min-width: <?php echo $form_control_height_lg; ?>px;
}
.input-group-xl .input-group-btn,
.input-group-xl .input-group-addon
{
  min-width: <?php echo $form_control_height_xl; ?>px;
}
.input-group-addon:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  border-right: 1px solid rgba(0, 0, 0, 0.08) !important;
  <?php else: ?>
  border-left: 1px solid rgba(0, 0, 0, 0.08) !important;
  <?php endif; ?>
}
.input-group-addon:last-child {
  <?php if ($lang_dir == 'ltr'): ?>
  border-left: 1px solid rgba(0, 0, 0, 0.08) !important;
  <?php else: ?>
  border-right: 1px solid rgba(0, 0, 0, 0.08) !important;
  <?php endif; ?>
}
.input-group-addon input[type=checkbox],
.input-group-addon input[type=radio]
{
  margin-top: -0.075em;
  vertical-align: middle;
  align-self: center;
  margin-top: 0;
}
.input-group .form-control:first-child,
.input-group-addon:first-child,
.input-group-btn:first-child > .btn,
.input-group-btn:first-child > .btn-group > .btn,
.input-group-btn:first-child > .dropdown-toggle,
.input-group-btn:last-child > .btn:not(:last-child):not(.dropdown-toggle),
.input-group-btn:last-child > .btn-group:not(:last-child) > .btn {
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
}
.input-group-addon:first-child {
  border-right: 0;
}
.input-group .form-control:last-child,
.input-group-addon:last-child,
.input-group-btn:last-child > .btn,
.input-group-btn:last-child > .btn-group > .btn,
.input-group-btn:last-child > .dropdown-toggle,
.input-group-btn:first-child > .btn:not(:first-child),
.input-group-btn:first-child > .btn-group:not(:first-child) > .btn {
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}
.input-group-addon:last-child {
  border-left: 0;
}
.input-group-btn {
  position: relative;
  white-space: nowrap;
}
.input-group-btn > .btn {
  position: relative;
  z-index: 1;
  margin-top: 0 !important;
  margin-bottom: 0 !important;
  border-right: 1px solid rgba(0, 0, 0, 0.1);
  border-left: 1px solid rgba(0, 0, 0, 0.1);
}
.input-group-btn > .btn:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  border-left: 0 none;
  <?php else: ?>
  border-right: 0 none;
  <?php endif; ?>
}
.input-group-btn > .btn:last-child {
  <?php if ($lang_dir == 'ltr'): ?>
  border-right: 0 none;
  <?php else: ?>
  border-left: 0 none;
  <?php endif; ?>
}
.input-group-btn > .btn + .btn {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -1px;
  <?php else: ?>
  margin-right: -1px;
  <?php endif; ?>
}
.input-group-btn > .btn:hover,
.input-group-btn > .btn:focus,
.input-group-btn > .btn:active,
.input-group-btn > .btn:active
{
  z-index: 2;
}

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .input-group-btn > .btn {
    height: <?php echo $base * 2; ?>px;
    line-height: <?php echo $base * 2; ?>px;
  }
  .input-group-btn > .btn.tb_no_text {
    width: <?php echo $base * 2; ?>px;
  }
}