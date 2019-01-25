* {
  text-rendering: optimizeLegibility;
}
body {
  font: 13px/20px "Lucida Sans Unicode", "Lucida Grande", Arial, sans-serif;
  <?php if ($lang_dir == 'rtl') echo 'direction: rtl;'; ?>
}

/*  ---   Titles   ----------------------------------------------------------------------  */

h1, h2, h3,
.h1, .h2, .h3,
h4, h5, h6,
.h4, .h5, .h6,
legend,
.panel-heading,
.box-heading
{
  font-weight: normal;
  font-style: normal;
}
h1, .h1 {
  margin-bottom: <?php echo $base; ?>px;
  font-size: 26px;
}
h2, .h2,
legend,
.panel-heading,
.box-heading
{
  font-size: 16px;
}
h2, .h2,
legend
{
  margin-bottom: <?php echo $base; ?>px;
  font-size: 16px;
}
h3, .h3 {
  margin-bottom: <?php echo $base * 0.5; ?>px;
  font-size: 15px;
}
h4, .h4 {
  margin-bottom: <?php echo $base * 0.5; ?>px;
  font-size: 14px;
}
h5, .h5 {
  font-size: 12px;
}
h6, .h6 {
  font-size: 11px;
}

sup, sub {
  line-height: 0;
  font-size: 75%;
}
hr {
  height: 0;
  border: none;
  border-bottom: 1px solid;
  opacity: 0.2;
}

/*  ---   Text Utilities   --------------------------------------------------------------  */

small, .small    { font-size: 85%; }
mark, .mark      { padding: .2em; background-color: #fcf8e3; }
.text-left       { text-align: left;    }
.text-right      { text-align: right;   }
.text-center     { text-align: center;  }
.text-justify    { text-align: justify; }
.text-nowrap     { white-space: nowrap; }
.text-lowercase  { text-transform: lowercase;  }
.text-uppercase  { text-transform: uppercase;  }
.text-capitalize { text-transform: capitalize; }
.valign-top      { vertical-align: top;    }
.valign-middle   { vertical-align: middle; }
.valign-bottom   { vertical-align: bottom; }

.tb_disabled {
  position: relative;
}
.tb_disabled:after {
  content:    '';
  position:   absolute;
  z-index:    1;
  top:        0;
  right:      0;
  bottom:     0;
  left:       0;
  background: #fff;
  opacity:    0;
}



small {
  font-size: 75%;
}
blockquote,
p,
ul,
ol,
dl,
address,
blockquote,
table,
hr
{
  margin-bottom: <?php echo $base; ?>px;
}
h1:last-child, .h1:last-child,
h2:last-child, .h2:last-child,
h3:last-child, .h3:last-child,
h4:last-child, .h4:last-child,
h5:last-child, .h5:last-child,
h6:last-child, .h6:last-child,
blockquote:last-child,
p:last-child,
ul:last-child,
ol:last-child,
dl:last-child,
address:last-child,
blockquote:last-child,
table:last-child,
hr:last-child
{
  margin-bottom: 0;
}
table img {
  display: block;
  max-width: none;
  margin-left: auto;
  margin-right: auto;
}
p img,
a img
{
  display: inline-block;
  vertical-align: top;
}
*::-moz-selection,
*::-webkit-selection,
::selection
{
  color: #fff;
}

figure.image,
figure.image img:not(:last-child) {
  margin-bottom: <?php echo $base; ?>px;
}
figure.image figcaption {
  text-align: center;
  font-style: italic;
}
p img.left,
p img.pull-left,
p .image-holder.pull-left,
figure.image.pull-left
{
  float: left;
  margin: 0 <?php echo $base; ?>px <?php echo $base; ?>px 0;
}
img.center-block,
.center-block img,
figure .image-holder,
.center-block .image-holder,
figure img
{
  display: block;
  margin: 0 auto <?php echo $base; ?>px auto;
}
p.center-block:last-child img,
figure.center-block:last-child img
{
  margin-bottom: 0;
}
p img.right,
p img.pull-right,
p .image-holder.pull-right,
figure.image.pull-right {
  float: right;
  margin: 0 0 <?php echo $base; ?>px <?php echo $base; ?>px;
}
.pull-left img.pull-left,
.pull-right img.pull-right,
p > img.left:last-child:not([style*="margin-bottom"]),
p > img.pull-right:last-child:not([style*="margin-bottom"]),
p:last-child > img.center-block,
p.center-block:last-child > img,
p.center-block:last-child > .image-holder
{
  margin-bottom: 0 !important;
}
.tb_text_wrap p:not([class]) .image-holder:not(.pull-left):not(.pull-right):not(.center-block) {
  display: inline-block;
  margin-left:  0;
  margin-right: 0;
  vertical-align: top;
}
p:empty,
ul:empty
{
  display: none;
}
a {
  -webkit-transition: color 0.4s, background-color 0.4s;
          transition: color 0.4s, background-color 0.4s;
}

i.fa,
i[class*="fa-"]
{
  vertical-align: initial;
}
i.fa,
i.fa:before,
[class*="fa-"],
[class*="fa-"]:before
{
  line-height: inherit;
}
.btn i.fa {
  vertical-align: top;
}

.dropdown > .caret {
  display: none;
}
.sr-only {
  position: absolute;
  overflow: hidden;
  width: 1px;
  height: 1px;
  margin: -1px;
  padding: 0;
  clip: rect(0,0,0,0);
  border: 0;
}

span.required       { font-weight: bold; color: red; }
.tb_small           { font-size: 10px; }
a img.inline        { vertical-align: middle; }
.overflow_text      { overflow: auto; overflow-x: hidden; }

.tb_sep,
.pagination,
table,
.table,
span.clear.border:not([class*="tb_mb_"]) { margin-bottom: <?php echo $base * 1.5; ?>px; }

.border         { border-bottom-width: 1px; border-bottom-style: solid;  }
.border-dashed  { border-bottom-style: dashed; }
.border-dotted  { border-bottom-style: dotted; }
.border-double  { border-bottom-width: 3px; border-bottom-style: double; }

br.clear,
span.clear
{
  visibility: visible;
  display: block;
  height: 0;
  line-height: 0;
  font-size: 0;
}

p.tb_empty {
  text-align: center;
  margin-bottom: <?php echo $base * 1.5; ?>px;
  padding: <?php echo $base * 2; ?>px 0;
  font-size: <?php echo $base_font_size * 1.25; ?>px;
}
.help { font-size: <?php echo $base_font_size * 0.85; ?>px; opacity: 0.6; }

pre {
  word-wrap: break-word;
}
pre code {
  white-space: pre-wrap;
}

abbr[title],
abbr[data-original-title] {
  cursor: help;
}
.blockquote-reverse,
blockquote.pull-right {
  text-align: right;
}
.blockquote-reverse footer:before,
blockquote.pull-right footer:before,
.blockquote-reverse small:before,
blockquote.pull-right small:before,
.blockquote-reverse .small:before,
blockquote.pull-right .small:before {
  content: '';
}
.blockquote-reverse footer:after,
blockquote.pull-right footer:after,
.blockquote-reverse small:after,
blockquote.pull-right small:after,
.blockquote-reverse .small:after,
blockquote.pull-right .small:after {
  content: '\00A0 \2014';
}
.pre-scrollable {
  max-height: 340px;
  overflow-y: scroll;
}

/*  -----------------------------------------------------------------------------------------
    Lists
-----------------------------------------------------------------------------------------  */

/*  ---   Description list   ------------------------------------------------------------  */

.dl-horizontal dt {
  <?php if ($lang_dir == 'ltr'): ?>
  clear: left;
  float: left;
  margin-right: 10px;
  <?php else: ?>
  clear: right;
  float: right;
  margin-left: 10px;
  <?php endif; ?>
  font-weight: bold;
}
.dl-horizontal dd {
  overflow: hidden;
}

/*  ---   Unordered list   --------------------------------------------------------------  */

.tb_list_1,
.list-group
{
  list-style: none !important;
}
.tb_list_1 > li:not(.tb_nobullet):not(.tb_link) {
  display: table;
  width: 100%;
  table-layout: fixed;
}
.tb_list_1 > li > a {
  word-break: break-word;
  -ms-word-wrap: break-word;
      word-wrap: break-word;
}
.tb_list_1 > li.tb_link > a,
.list-group > a
{
  position: relative;
  display: table;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.tb_list_1 > li:not(.tb_nobullet):not(.tb_link):before,
.tb_list_1 > li.tb_link > a:before,
.list-group > a:before
{
  <?php if ($lang_dir == 'ltr'): ?>
  content: '\f105';
  <?php else: ?>
  content: '\f104';
  <?php endif; ?>
  width: <?php echo $base / 2 + 4; ?>px;
  font-size: 12px;
  font-family: FontAwesome;
}
.tb_list_1 > li:not(.tb_nobullet):not(.tb_link):before {
  display: table-cell;
  vertical-align: top;
}
.tb_list_1 > li.tb_link > a:before,
.list-group > a:before
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.tb_list_1:not(dropdown-menu) > li > .dropdown-menu.tb_ip_sm {
  margin-top: -<?php echo $base * 0.75; ?>px;
}

/*  -----------------------------------------------------------------------------------------
    Icons
-----------------------------------------------------------------------------------------  */

.tb_icon_10,
.tb_icon_16,
.tb_icon_24,
.tb_icon_32,
.btn.tb_icon_10,
.btn.tb_icon_16,
.btn.tb_icon_24,
.btn.tb_icon_32
{
  display: -ms-inline-flexbox;
  display: -webkit-inline-flex;
  display: inline-flex;
  vertical-align: top;
}
[class].tb_icon_10:before,
[class].tb_icon_16:before,
[class].tb_icon_24:before,
[class].tb_icon_32:before
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.5em !important;
  <?php else: ?>
  margin-right: 0.5em !important;
  <?php endif; ?>
}
.tb_icon_10:before {
  width: 11px;
  font-size: 10px;
}
.tb_icon_16:before {
  width: 17px;
  font-size: 16px;
}
.tb_icon_24:before {
  width: 25px;
  font-size: 24px;
}
.tb_icon_32:before {
  width: 32px;
  font-size: 32px;
}
.tb_icon {
  display: inline-block;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.5em;
  <?php else: ?>
  margin-left: 0.5em;
  <?php endif; ?>
  text-align: center;
  letter-spacing: 0;
  word-spacing: 0;
  vertical-align: top;
}
[class*="tb_icon_"]:before {
  margin-right: 0 !important;
  margin-left:  0 !important;
}
.btn svg {
  width: 1.4em;
}
.fa.fa-times:before,
.fa.fa-times-circle:before
{
  content: '\2716';
  content: '\274c';
  content: '\2715';
  content: '\00D7';
  font-family: FontAwesome, Tahoma, Arial, sans-serif;
  font-size: 0.99em;
  font-size: 1.35em;
}
.btn .fa-times:before,
.btn .fa-times-circle:before
{
  font-size: 22px;
  letter-spacing: 0;
  word-spacing: 0;
}
.btn-xs .fa-times:before,
.btn-xs .fa-times-circle:before
{
  font-size: 17px;
}
.btn-sm .fa-times:before,
.btn-sm .fa-times-circle:before
{
  font-size: 20px;
}
.btn-lg .fa-times:before,
.btn-lg .fa-times-circle:before
{
  font-size: 29px;
}
.tb_no_text > i.fa,
.tb_no_text[class*="fa-"]:before,
.tb_no_text > [class*="fa-"]:before,
.tb_no_text[class*="ico-"]:before,
.tb_no_text > [class*="ico-"]:before,
.tb_no_text > .tb_icon,
.tb_no_text > .tb_text > i.fa,
.tb_no_text > .tb_text > [class*="fa-"]:before,
.tb_no_text > .tb_text > .tb_icon
{
  margin-left: 0 !important;
  margin-right: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    OpenCart 1
-----------------------------------------------------------------------------------------  */

th.left, td.left     { <?php if ($lang_dir == 'ltr'): ?>text-align: left;<?php else:  ?>text-align: right;<?php endif; ?> }
th.right, td.right   { <?php if ($lang_dir == 'ltr'): ?>text-align: right;<?php else: ?>text-align: left;<?php endif; ?> }
th.center, td.center { <?php if ($lang_dir == 'ltr'): ?>text-align: left;<?php else:  ?>text-align: right;<?php endif; ?> }
