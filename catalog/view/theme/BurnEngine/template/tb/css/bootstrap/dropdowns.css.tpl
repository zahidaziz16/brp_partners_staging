.dropdown {
  position: relative;
}
.dropdown > a,
.nav-stacked .tb_link > a
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.dropdown > a > .tb_text {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.3em;
  <?php else: ?>
  margin-left: 0.3em;
  <?php endif; ?>
  word-wrap: break-word;
  text-rendering: initial;
  font-feature-settings: initial;
  -webkit-font-feature-settings: initial;
  font-kerning: initial;
}
.dropdown > a:not(.tb_no_caret):after,
.dropdown-toggle:after
{
  direction: ltr;
  content: '\f0d7';
  -ms-flex-item-align: center;
   -webkit-align-self: center;
           align-self: center;
  vertical-align: top;
  font-family: FontAwesome;
  font-size: 12px;
  opacity: 0.3;
  cursor: pointer;
}
.nav.tb_nocaret .dropdown > a:after,
.nav.tb_nocaret .dropdown-toggle:after
{
  content: none;
}
.nav.tb_nocaret .dropdown > a > .tb_text {
  margin-left:  0;
  margin-right: 0;
}
.dropdown-toggle:after {
  <?php if ($lang_dir == 'ltr'): ?>
  margin: 0 0 0 0.5em;
  <?php else: ?>
  margin: 0 0.5em 0 0;
  <?php endif; ?>
}
.dropdown-toggle .sr-only {
  position: static;
  display: inline-block;
  width: 0;
  height: 100%;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: -0.5em;
  <?php else: ?>
  margin-left: -0.5em;
  <?php endif; ?>
  vertical-align: top;
}
.dropdown:hover > a:after,
.dropdown-toggle:hover:after
{
  opacity: 1;
}
.nav-stacked .dropdown > a > .tb_text,
.dropdown-menu .dropdown > a > .tb_text,
.tb_list_1 > .dropdown > a > .tb_text
{
  -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
  flex: 1 1 0px;
}
.nav-stacked .dropdown > a:after,
.dropdown-menu .dropdown > a:after,
.tb_list_1 > .dropdown > a:after
{
  <?php if ($lang_dir == 'ltr'): ?>
  content: '\a0 \f0da';
  <?php else: ?>
  content: '\a0 \f0d9';
  <?php endif; ?>
}

/*** Dropdown menu ***/

.dropdown-menu {
  overflow-x: visible;
  position: absolute;
  top: 100%;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
  display: none;
  min-width: <?php echo $base * 10; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
  list-style: none !important;
  -webkit-transition: opacity 0.2s;
          transition: opacity 0.2s;
}
.tb_hovered > .dropdown-menu,
.open > .dropdown-menu
{
  z-index: 50;
  display: block;
}
.no_touch .dropdown:hover > .dropdown-menu {
  opacity: 0;
  display: block;
}
.dropdown:not(.tb_hovered):not(.open):hover > .dropdown-menu {
  left: -10000px;
  top: -10000px;
}
.open > .dropdown-menu,
.tb_hovered > .dropdown-menu
{
  opacity: 1 !important;
}
.open > .dropdown-menu {
  -webkit-transform: none !important;
          transform: none !important;
}
.dropdown-menu.tb_ip_xs { padding: <?php echo $base * 0.5;  ?>px; }
.dropdown-menu.tb_ip_sm { padding: <?php echo $base * 0.75; ?>px; }
.dropdown-menu.tb_ip_lg { padding: <?php echo $base * 1.25; ?>px; }
.dropdown-menu.tb_ip_xl { padding: <?php echo $base * 1.5;  ?>px; }
.dropdown-menu.tb_vsep_xs { padding-top: <?php echo $base * 0.9;  ?>px; padding-bottom: <?php echo $base * 0.9;  ?>px; }
.dropdown-menu.tb_vsep_sm { padding-top: <?php echo $base * 0.75; ?>px; padding-bottom: <?php echo $base * 0.75; ?>px; }
.dropdown-menu.tb_vsep_md { padding-top: <?php echo $base * 0.5;  ?>px; padding-bottom: <?php echo $base * 0.5;  ?>px; }
.dropdown-menu.tb_ip_xs.tb_vsep_xs { padding-top: <?php echo $base * 0.4;  ?>px; padding-bottom: <?php echo $base * 0.4;  ?>px; }
.dropdown-menu.tb_ip_xs.tb_vsep_sm { padding-top: <?php echo $base * 0.25; ?>px; padding-bottom: <?php echo $base * 0.25; ?>px; }
.dropdown-menu.tb_ip_xs.tb_vsep_md { padding-top: 0; padding-bottom: 0; }
.dropdown-menu.tb_ip_sm.tb_vsep_xs { padding-top: <?php echo $base * 0.74; ?>px; padding-bottom: <?php echo $base * 0.74; ?>px; }
.dropdown-menu.tb_ip_sm.tb_vsep_sm { padding-top: <?php echo $base * 0.5;  ?>px; padding-bottom: <?php echo $base * 0.5;  ?>px; }
.dropdown-menu.tb_ip_sm.tb_vsep_md { padding-top: <?php echo $base * 0.25; ?>px; padding-bottom: <?php echo $base * 0.25; ?>px; }
.dropdown-menu.tb_ip_md.tb_vsep_xs { padding-top: <?php echo $base * 1.24; ?>px; padding-bottom: <?php echo $base * 1.24; ?>px; }
.dropdown-menu.tb_ip_md.tb_vsep_sm { padding-top: <?php echo $base * 1;    ?>px; padding-bottom: <?php echo $base * 1;    ?>px; }
.dropdown-menu.tb_ip_md.tb_vsep_md { padding-top: <?php echo $base * 0.75; ?>px; padding-bottom: <?php echo $base * 0.75; ?>px; }
.dropdown-menu.tb_ip_lg.tb_vsep_xs { padding-top: <?php echo $base * 1.4;  ?>px; padding-bottom: <?php echo $base * 1.4;  ?>px; }
.dropdown-menu.tb_ip_lg.tb_vsep_sm { padding-top: <?php echo $base * 1.25; ?>px; padding-bottom: <?php echo $base * 1.25; ?>px; }
.dropdown-menu.tb_ip_lg.tb_vsep_md { padding-top: <?php echo $base * 1;    ?>px; padding-bottom: <?php echo $base * 1;    ?>px; }

.dropdown-menu-right {
  left: auto;
  right: 0;
}
.dropdown-menu-left {
  left: 0;
  right: auto;
}
.dropdown-menu:not(.nav-stacked) > li,
.dropdown-menu:not(.nav-stacked) > li > a,
.tb_multicolumn.tb_list_1 > li,
.tb_multicolumn.tb_list_1 > li > a
{
  padding-top: <?php echo $base * 0.25; ?>px;
  padding-bottom: <?php echo $base * 0.25; ?>px;
}
.dropdown-menu > li > a {
  clear: both;
  display: table;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  min-width: 100%;
}
.dropdown-menu:not(.nav-stacked) > li > a,
.tb_multicolumn.tb_list_1 > li > a
{
  margin-top: -<?php echo $base * 0.25; ?>px;
  margin-bottom: -<?php echo $base * 0.25; ?>px;
}
.dropdown > a > img,
.dropdown-menu > li > a > img
{
  -ms-flex-item-align: center;
  -webkit-align-self: center;
  align-self: center;
}
.dropdown-menu:not(.nav-stacked) > li:first-child {
  margin-top: -<?php echo $base * 0.25; ?>px;
}
.dropdown-menu:not(.nav-stacked) > li:last-child {
  margin-bottom: -<?php echo $base * 0.25; ?>px;
}
.dropdown-menu > .divider {
  overflow: hidden;
  height: 0;
  margin: <?php echo $base * 0.5; ?>px -<?php echo $base; ?>px 0 -<?php echo $base; ?>px;
  padding-top: 0 !important;
  padding-bottom: <?php echo $base * 0.5; ?>px !important;
  border-top: 1px solid;
  opacity: 0.2;
}
.dropdown-menu .disabled {
  opacity: 0.3;
  cursor: not-allowed;
}
.dropdown-header {
  opacity: 0.5;
  text-transform: uppercase;
}
.dropdown-backdrop {
  position: fixed;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  z-index: 990;
}
.dropup .dropdown-menu {
  top: auto;
  bottom: 100%;
  margin-bottom: 1px;
}
.dropdown-header:before,
.divider:before
{
  content: none !important;
}
.nav-justified-dropdown > .dropdown:not(.tb_megamenu) > .dropdown-menu {
  left: 0;
  right: 0;
  width: auto;
  min-width: 0;
}

/*** Inner dropdown ***/

.dropdown-menu .dropdown-menu,
.nav-stacked .dropdown-menu,
.tb_list_1:not(dropdown-menu) > li > .dropdown-menu
{
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  left: calc(100% - 1px);
  <?php else: ?>
  right: calc(100% - 1px);
  <?php endif; ?>
}
.dropdown-menu .dropdown-menu {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left:  <?php echo $base + 1; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base + 1; ?>px;
  <?php endif; ?>
}

/*** Megamenu ***/

.tb_megamenu > .dropdown-menu {
  width: 1000px;
}
.dropdown-menu > .tb_ip_20 {
  margin: -<?php echo $base; ?>px;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .dropdown-menu > .tb_ip_20 {
    margin: 0;
  }
}
.nav:not(.nav-stacked) > .tb_megamenu > .dropdown-menu {
  left: 0 !important;
  right: auto !important;
}

/*** Style ***/

.dropdown:after {
  z-index: 60;
  position: absolute;
  top: 100%;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  right: auto;
  <?php else: ?>
  right: 0;
  left: auto;
  <?php endif; ?>
  display: none !important;
  width: 10px;
  height: 10px;
  margin-top: -6px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 12px;
  <?php else: ?>
  margin-right: 12px;
  <?php endif; ?>
  border: 1px solid transparent;
  border-top-color: rgba(0, 0, 0, 0.15);
  border-left-color: rgba(0, 0, 0, 0.15);
  background: #fff;
  background-clip: content-box;
  -webkit-transform: rotate(45deg);
          transform: rotate(45deg);
  -webkit-transition: opacity 0.2s;
          transition: opacity 0.2s;
}
.tb_hidden_menu:after {
  content: none !important;
  display: none !important;
}
.dropdown:hover:after,
.dropdown.tb_hovered:after
{
  content: '';
  opacity: 0;
  display: block !important;
}
.dropdown:hover:after {
  opacity: 0;
}
.dropdown.tb_hovered:after {
  opacity: 1;
}
.dropdown-menu {
  margin-left: 1px;
  margin-right: 1px;
  padding: <?php echo $base; ?>px;
  background: #fff;
  border-radius: 2px;
  box-shadow:
    0 1px 0 0 rgba(0, 0, 0, 0.1),
    0 0 0 1px rgba(0, 0, 0, 0.08),
    0 1px 5px 0 rgba(0, 0, 0, 0.2);
}
.nav:not(.nav-stacked) > .dropdown {
  margin-bottom: -<?php echo $base * 0.5; ?>px;
  padding-bottom: <?php echo $base * 0.5; ?>px;
}
.nav-justified-dropdown > .dropdown:not(.tb_megamenu) > .dropdown-menu {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 1px !important;
  <?php else: ?>
  margin-right: 1px !important;
  <?php endif; ?>
}
.nav-stacked .dropdown:after,
.dropdown-menu .dropdown:after,
.tb_list_1 > .dropdown:after
{
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  left: calc(100% - 1px);
  <?php else: ?>
  right: calc(100% - 1px);
  <?php endif; ?>
  margin-top: <?php echo $base * 0.75 - 5; ?>px;
  border-top: 1px solid rgba(0, 0, 0, 0.15);
  border-left: 1px solid rgba(0, 0, 0, 0.15);
  background-clip: content-box;
  -webkit-transform: rotate(-45deg);
          transform: rotate(-45deg);
}
.nav-stacked .dropdown:after,
.tb_list_1 > .dropdown:after
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: -6px;
  <?php else: ?>
  margin-right: -6px;
  <?php endif; ?>
}
.dropdown-menu .dropdown:after
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base - 5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base - 5; ?>px;
  <?php endif; ?>
}
.dropdown-menu .dropdown-menu
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base + 1; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base + 1; ?>px;
  <?php endif; ?>
}
.dropdown-menu .dropdown-menu,
.tb_list_1:not(dropdown-menu) > li > .dropdown-menu
{
  margin-top: -<?php echo $base * 0.75; ?>px;
}
.dropdown-menu.nav-stacked > .dropdown > .dropdown-menu {
  margin-top: -<?php echo $base; ?>px;
}
.dropdown-menu.nav-stacked > .dropdown:after {
  margin-top: <?php echo $base * 0.5 - 5; ?>px;
}

/*  -----------------------------------------------------------------------------------------
    A C C E N T   L A B E L
-----------------------------------------------------------------------------------------  */

.dropdown-menu .tb_accent_label,
.tb_list_1 > li > a > .tb_accent_label
{
  top: auto !important;
  left: auto;
  right: auto;
  margin-top: -<?php echo $base * 0.25 - 2; ?>px !important;
}

/*  -----------------------------------------------------------------------------------------
    C U S T O M   M E N U   T Y P E S
-----------------------------------------------------------------------------------------  */

.nav .tb_text,
.dropdown .tb_text
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.nav .tb_text > img.tb_icon,
.dropdown .tb_text > img.tb_icon
{
  -ms-flex-item-align: center;
   -webkit-align-self: center;
           align-self: center;
}
