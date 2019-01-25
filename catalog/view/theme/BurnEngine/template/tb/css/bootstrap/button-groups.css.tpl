.btn-group,
.btn-toolbar
{
  position: relative;
  display: inline-block;
  display: -moz-inline-box;
  display: -ms-inline-flexbox;
  display: -webkit-inline-flex;
  display: inline-flex;
      -ms-flex-wrap: nowrap !important;
  -webkit-flex-wrap: nowrap !important;
          flex-wrap: nowrap !important;
  vertical-align: middle;
}
.btn-group > .btn,
.btn-group > .btn-group,
.btn-toolbar > .btn,
.btn-toolbar > .btn-group,
.btn-toolbar > .input-group
{
  position: relative;
  display: inline-block;
  float: left;
  flex: 0 0 auto;
}
.btn-group > .btn:hover,
.btn-group > .btn:focus,
.btn-group > .btn:active,
.btn-group > .btn.active
{
  z-index: 2;
}
.btn-group > .btn:not(:first-child):not(:last-child):not(.dropdown-toggle) {
  border-radius: 0;
}
.btn-group > .btn:first-child {
  margin-left: 0;
}
.btn-group > .btn:first-child:not(:last-child):not(.dropdown-toggle) {
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
}
.btn-group > .btn:last-child:not(:first-child),
.btn-group > .dropdown-toggle:not(:first-child) {
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}
.btn-group > .btn-group:not(:first-child):not(:last-child) > .btn {
  border-radius: 0;
}
.btn-group > .btn-group:first-child > .btn:last-child,
.btn-group > .btn-group:first-child > .dropdown-toggle {
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
}
.btn-group > .btn-group:last-child > .btn:first-child {
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}

/*** Button Toolbar ***/

.btn-toolbar {
  margin-left: -<?php echo $base * 0.25; ?>px;
  margin-right: -<?php echo $base * 0.25; ?>px;
  padding-left: <?php echo $base * 0.25; ?>px;
}
.btn-toolbar > .btn,
.btn-toolbar > .btn-group,
.btn-toolbar > .input-group {
  margin-right: <?php echo $base * 0.25; ?>px;
}

/*** Vertical Buttons ***/

.btn-group-vertical {
  position: relative;
  display: inline-block;
  vertical-align: top;
}
.btn-group-vertical > .btn,
.btn-group-vertical > .btn-group,
.btn-group-vertical > .btn-group > .btn {
  display: block;
  float: none;
  width: 100%;
  max-width: 100%;
  border-right: none;
  border-left: none;
}
.btn-group-vertical > .btn,
.btn-group-vertical > .btn-group > .btn
{
  box-shadow: inset 0 -1px rgba(0, 0, 0, 0.15);
}
.btn-group-vertical > .btn-group > .btn {
  float: none;
}
.btn-group-vertical > .btn:not(:first-child):not(:last-child) {
  border-radius: 0;
}
.btn-group-vertical > .btn:first-child:not(:last-child) {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.btn-group-vertical > .btn:last-child:not(:first-child) {
  border-bottom-left-radius: 4px;
  border-top-right-radius: 0;
  border-top-left-radius: 0;
}
.btn-group-vertical > .btn-group:not(:first-child):not(:last-child) > .btn {
  border-radius: 0;
}
.btn-group-vertical > .btn-group:first-child:not(:last-child) > .btn:last-child,
.btn-group-vertical > .btn-group:first-child:not(:last-child) > .dropdown-toggle {
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.btn-group-vertical > .btn-group:last-child:not(:first-child) > .btn:first-child {
  border-top-right-radius: 0;
  border-top-left-radius: 0;
}

/*** Justified Buttons ***/

.btn-group-justified {
  display: table;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  width: 100%;
  table-layout: fixed;
  border-collapse: separate;
}
.btn-group-justified > .btn,
.btn-group-justified > .btn-group
{
  display: table-cell;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  float: none;
  width: 1%;
  flex: 1 1 auto;
  justify-content: center;
  padding-left: 0;
  padding-right: 0;
}
.btn-group-justified > .btn-group .btn {
  width: 100%;
}
[data-toggle="buttons"] > .btn input[type="radio"],
[data-toggle="buttons"] > .btn-group > .btn input[type="radio"],
[data-toggle="buttons"] > .btn input[type="checkbox"],
[data-toggle="buttons"] > .btn-group > .btn input[type="checkbox"] {
  position: absolute;
  clip: rect(0, 0, 0, 0);
  pointer-events: none;
}

/*** BurnEngine ***/

.btn-group > .btn,
.btn-group > .btn-group > .btn:first-child
{
  border-right: 1px solid rgba(0, 0, 0, 0.1);
  border-left: 1px solid rgba(0, 0, 0, 0.1);
}
.btn-group > .btn + .btn,
.btn-group > .btn + .btn-group,
.btn-group > .btn-group + .btn,
.btn-group > .btn-group + .btn-group
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -1px;
  <?php else: ?>
  margin-right: -1px;
  <?php endif; ?>
}
.btn-group > .btn:first-child {
  <?php if ($lang_dir == 'ltr'): ?>
  border-left: 0 none;
  <?php else: ?>
  border-right: 0 none;
  <?php endif; ?>
}
.btn-group > .btn-group:last-child > :last-child {
  <?php if ($lang_dir == 'ltr'): ?>
  border-right: 0 none;
  <?php else: ?>
  border-left: 0 none;
  <?php endif; ?>
}
.btn-group-vertical > .btn,
.btn-group-vertical > .btn-group > .btn {
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}
.btn-group-vertical > .btn:first-child,
.btn-group-vertical > .btn-group:first-child > .btn:first-child {
  border-top: none;
}
.btn-group-vertical > .btn:last-child,
.btn-group-vertical > .btn-group:last-child > .btn:last-child
{
  border-bottom: none;
}
.btn-group-vertical > .btn:not(:last-child):not(.active),
.btn-group-vertical > .btn-group:not(:last-child) > .btn:not(.active)
{
  box-shadow: none;
}
.btn-group-vertical > * {
  margin: 0;
}
.btn-group-vertical > * + * {
  margin-top: -1px;
}
.btn.active,
.btn-group.open .dropdown-toggle
{
  border-color: rgba(0, 0, 0, 0.3) !important;
  box-shadow:
    inset 0 3px 5px rgba(0, 0, 0, 0.05),
    inset 0 0 0 40px rgba(0, 0, 0, 0.2)
    !important;
}
