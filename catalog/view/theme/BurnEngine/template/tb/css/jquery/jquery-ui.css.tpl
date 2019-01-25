
/*** Autocomplete ***/

.ui-autocomplete-input {
  position: relative;
}
.tb_jquery_ui .ui-icon {
  display: block;
  text-indent: -666px;
  overflow: hidden;
  background-repeat: no-repeat;
}

/*** Dialog ***/

.ui-dialog-content .buttons {
  width: auto;
  margin: 0 -2em -2em -2em;
  padding: 2em;
}
.ui-dialog-content ul:not([class]),
.ui-dialog-content ul:not([class]) li
{
  list-style: disc;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 1em;
  <?php else: ?>
  padding-right: 1em;
  <?php endif; ?>
}
.ui-dialog-content ul:not([class]) li {
  margin-bottom: <?php echo $base * 0.5; ?>px;
}
.ui-dialog-content ul:not([class]) li:last-child {
  margin-bottom: 0;
}

/*** Spinner ***/

.ui-spinner {
  width: auto;
}
.ui-spinner .ui-spinner-input {
  margin: -1px !important;
  padding: 0 !important;
}
.ui-spinner .btn-group-vertical {
  display: -ms-inline-flexbox;
  display: -webkit-inline-flex;
  display: inline-flex;
  -ms-flex-direction: column;
  -webkit-flex-direction: column;
  flex-direction: column;
}
.ui-spinner .ui-spinner-button {
  -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
  flex: 1 1 auto;
  width: 1.5em;
  min-width: <?php echo $base; ?>px;
  padding: 0;
}
.ui-spinner .ui-spinner-up {
  <?php if ($lang_dir == 'ltr'): ?>
  border-radius: 0 2px 0 0 !important;
  <?php else: ?>
  border-radius: 2px 0 0 0 !important;
  <?php endif; ?>
}
.ui-spinner .ui-spinner-down {
  <?php if ($lang_dir == 'ltr'): ?>
  border-radius: 0 0 2px 0 !important;
  <?php else: ?>
  border-radius: 0 0 0 2px !important;
  <?php endif; ?>
}

/*** Tooltip ***/

.ui-tooltip {
  z-index: 1000;
  position: absolute;
}
