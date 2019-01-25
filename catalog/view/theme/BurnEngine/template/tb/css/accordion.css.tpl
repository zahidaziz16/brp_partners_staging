/*** Accordion ***/

.tb_accordion > * {
  margin: 0;
}
.tb_accordion_content > div {
  display: none;
}
.tb_accordion_content > span.tb_title:first-child + div,
.tb_accordion > h2:first-child + div
{
  display: block;
}
.panel-group > .panel > .panel-heading {
  margin-bottom: 0;
}
.tb_accordion .tb_title,
.tb_accordion > h2,
.checkout-heading,
.panel-group > .panel > .panel-heading .panel-title,
.panel-group > .panel > .panel-heading .panel-title [data-parent]
{
  position: relative;
  z-index: 30;
  display: block;
  outline: none !important;
  cursor: pointer;
}
.panel-group > .panel > .panel-heading .panel-title:not([data-parent]) {
  cursor: default;
}
.tb_accordion .ui-accordion-header .ui-icon,
.checkout-heading:before,
.panel-group > .panel > .panel-heading [data-toggle]:before
{
  display: inline-block;
  width: 0.8em;
  text-align: center;
  font-weight: normal;
  font-family: "FontAwesome";
  vertical-align: top;
}
.tb_accordion .ui-accordion-header .ui-icon:before,
.checkout-heading:before,
.panel-group > .panel > .panel-heading [data-toggle]:not(.has_icon):before
{
  <?php if ($lang_dir == 'ltr'): ?>
  content: '\f105';
  <?php else: ?>
  content: '\f104';
  <?php endif; ?>
  float: none;
  /*
  margin-bottom: -0.056em;
  padding-top: 0.055em;
  */
  margin-top: -0.055em;
  vertical-align: top;
}
.checkout-heading:before,
.panel-group > .panel > .panel-heading [data-toggle]:before
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
  padding-top: 0;
}
.tb_accordion .ui-accordion-header.ui-state-active .ui-icon:before,
.panel-group > .panel > .panel-heading [data-toggle]:not(.collapsed):before,
.checkout-heading.tb_opened:before
{
  <?php if ($lang_dir == 'ltr'): ?>
  -webkit-transform: rotate(90deg);
          transform: rotate(90deg);
  <?php else: ?>
  -webkit-transform: rotate(-90deg);
          transform: rotate(-90deg);
  <?php endif; ?>
  margin-top: 0;
}
.tb_accordion .tb_wt {
  margin-bottom: 0;
}

/*** Accordion style 1 ***/

.tb_accordion.tb_style_1:not(.panel-group) {
  margin-bottom: -1px;
  border-bottom-width: 1px;
  border-bottom-style: solid;
}
.tb_accordion.tb_style_1 .tb_title,
.tb_accordion.tb_style_1 > h2,
.tb_accordion.tb_style_1 .tb_title + div,
.tb_accordion.tb_style_1 > h2 + div,
.panel-group:not(.tb_style_2) > .panel > .panel-heading > .panel-title,
.panel-group:not(.tb_style_2) > .panel > .panel-heading [data-toggle],
.panel-group:not(.tb_style_2) > .panel > .panel-collapse,
.checkout-heading
{
  border-width: 1px;
  border-style: solid;
  border-color: transparent;
}
.tb_accordion.tb_style_1 .tb_title,
.tb_accordion.tb_style_1 > h2,
.checkout-heading,
.panel-group:not(.tb_style_2) > .panel > .panel-heading .panel-title,
.panel-group:not(.tb_style_2) > .panel > .panel-heading [data-toggle]
{
  padding: <?php echo $base * 0.5 - 1; ?>px <?php echo $base - 1; ?>px;
  border-bottom-color: rgba(0, 0, 0, 0.12) !important;
}
.panel-group:not(.tb_style_2) > .panel > .panel-heading [data-toggle]:not(.panel-title) {
  margin: -<?php echo $base * 0.5; ?>px -<?php echo $base; ?>px;
}
.tb_accordion.tb_style_1 .tb_title:not(:first-child),
.tb_accordion.tb_style_1 .tb_title + div > div,
.tb_accordion.tb_style_1 .ui-accordion-content[style*="display: none"] + .ui-accordion-header,
.panel-group:not(.tb_style_2) > .panel:not(:first-child) > .panel-heading .panel-title,
.panel-group:not(.tb_style_2) > .panel:not(:first-child) > .panel-heading [data-toggle]
{
  border-top-color: transparent !important;
}
.tb_accordion.tb_style_1 .ui-accordion-header.last:not(.ui-accordion-header-active) {
  border-bottom-color: transparent !important;
}
.tb_accordion .tb_title + div,
.panel-group:not(.tb_style_2) > .panel > .panel-collapse
{
  border-top: none !important;
  border-bottom-width: 0 !important;
  border-bottom-style: none !important;
}
.tb_accordion:not(.tb_style_2) .tb_accordion_content > div > .panel-body,
.panel-group:not(.tb_style_2) > .panel > .panel-collapse > .panel-body
{
  margin: 0 -1px;
}
.tb_accordion:not(.tb_style_2) .tb_accordion_content > div > .panel-body:not([class*="tb_pt_"]),
.panel-group:not(.tb_style_2) > .panel > .panel-collapse > .panel-body:not([class*="tb_pt_"])
{
  padding-top: <?php echo $base * 1.5; ?>px;
  padding-bottom: <?php echo $base * 1.5; ?>px;
}
.panel-group.tb_style_1 > .panel:last-child > .panel-collapse {
  margin-bottom: -1px;
  border-bottom-width: 1px !important;
  border-bottom-style: solid !important;
}

/*** Accordion style 2 ***/

.tb_accordion.tb_style_2 .tb_title,
.tb_accordion.tb_style_2 > h2,
.panel-group.tb_style_2 > .panel > .panel-title
{
  padding-top: <?php echo $base * 0.25; ?>px;
  padding-bottom: <?php echo $base * 0.25; ?>px;
}
.tb_accordion.tb_style_2 .ui-accordion-header,
.panel-group.tb_style_2 > .panel > .panel-title,
.panel-group.tb_style_2 > .panel > .panel-heading [data-toggle]
{
  background-color: transparent !important;
}
.panel-group.tb_style_2 > .panel > .panel-collapse > .panel-body:not([class*="tb_pt_"]) {
  padding-top: <?php echo $base; ?>px;
  padding-bottom: <?php echo $base; ?>px;
}
.tb_accordion.tb_style_2 > div > .panel-body,
.panel-group.tb_style_2 > .panel:last-child > .panel-collapse > .panel-body
{
  padding-bottom: 0 !important;
}
