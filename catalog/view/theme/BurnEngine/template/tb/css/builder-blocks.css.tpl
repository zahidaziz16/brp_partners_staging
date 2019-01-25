.tb_wt {
  position: relative;
  clear: both;
  min-width: 0;
  border-radius: inherit;
}
.tb_wt:empty {
  display: none;
}
.tb_wt.display-inline-block {
  vertical-align: top;
}
.tb_wt.tb_content_inline {
  display: -ms-flexbox !important;
  display: -webkit-flex !important;
  display: flex !important;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.tb_wt.tb_content_inline .panel-heading {
  margin-bottom: 0;
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.tb_wt.tb_content_inline .panel-body {
  margin-bottom: 0;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}
<?php if ($lang_dir == 'ltr'): ?>
.tb_wt.tb_content_inline .panel-heading:not([class*="tb_mr_"]) {
  margin-right: 0.5em;
}
<?php else: ?>
.tb_wt.tb_content_inline .panel-heading:not([class*="tb_ml_"]) {
  margin-left: 0.5em;
}
<?php endif; ?>
.tb_wt.tb_content_inline .panel-heading > * {
  margin-bottom: 0;
}
.tb_wt.has_slider.tb_top_nav > h2,
.tb_wt.has_slider.tb_top_nav > div:not(.text-center) > h2
{
  padding-right: 60px;
}
.tb_wt.has_slider.tb_top_nav > h2.text-right,
.tb_wt.has_slider.tb_top_nav > div.text-right > h2
{
  padding-left: 60px;
}
.tb_wt > :last-child,
.row:not(.tb_separate_columns):not(.tb_ip_0) > .col > .tb_wt:not([class*="tb_mb_-"]):last-child
{
  margin-bottom: 0;
}

/*  -----------------------------------------------------------------------------------------
    B A N N E R
-----------------------------------------------------------------------------------------  */

.tb_wt_banner {
  overflow: hidden;
  padding: 0 !important;
}
.tb_wt_banner .tb_banner,
.tb_wt_banner .tb_image,
.tb_wt_banner .tb_image:before,
.tb_wt_banner > a
{
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: block;
  -webkit-transition: transform 0.3s ease-out;
          transition: transform 0.3s ease-out;
  border-radius: inherit;
}
.tb_wt_banner .tb_banner {
  z-index: 2;
}
.tb_wt_banner .tb_image:before {
  -webkit-transition: all 0.3s ease-out;
          transition: all 0.3s ease-out;
}
.tb_wt_banner .tb_text_wrap {
  display: table;
  width: 100%;
  height: 100%;
}
.tb_wt_banner .tb_text {
  display: table-cell;
  width: 100%;
  height: 100%;
}
.tb_wt_banner .tb_text > span {
  position: relative;
  display: block;
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
}
.is_touch .tb_wt_banner .tb_text > span {
  position: static;
  opacity: 1;
  visibility: visible;
  -webkit-transition: none;
          transition: none;
}
.tb_wt_banner .tb_text > span * {
  vertical-align: top;
}
.tb_wt_banner .tb_text > span.invisible {
  opacity: 0;
}
.tb_wt_banner:hover .tb_text > span.invisible,
.is_touch .tb_wt_banner .tb_text > span.invisible
{
  visibility: visible;
  opacity: 1;
}
.tb_wt_banner .tb_text > span + span {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.tb_wt_banner .tb_line_1 {
  line-height: <?php echo $tbData->calculateLineHeight(32, $base); ?>px;
  font-size: 32px;
}
.tb_wt_banner .tb_line_2 {
  line-height: <?php echo $tbData->calculateLineHeight(24, $base); ?>px;
  font-size: 24px;
}
.tb_wt_banner .tb_line_3 {
  line-height: <?php echo $tbData->calculateLineHeight(18, $base); ?>px;
  font-size: 18px;
}
.tb_wt_banner .tb_image {
  z-index: 1;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  border-radius: inherit;
}
.tb_wt_banner a {
  z-index: 3;
}
.tb_wt_banner .tb_image:before {
  content: '';
  opacity: 0;
}
.tb_wt_banner:hover .tb_image.tb_hover_color:before {
  opacity: 0.6;
}
.tb_wt_banner .tb_ratio {
  visibility: hidden;
  display: block;
  margin: 0 auto;
}
.tb_wt_banner .tb_ratio.tb_no_max_height {
  width: 100%;
}

/*  -----------------------------------------------------------------------------------------
    B L O C K   G R O U P
-----------------------------------------------------------------------------------------  */

.tb_wt_block_group.tb_equal_columns,
.tb_wt_block_group.tb_equal_columns > .row,
.tb_wt_block_group.tb_equal_columns > .row > .col > .display-block
{
  -ms-flex-item-align: stretch;
   -webkit-align-self: stretch;
           align-self: stretch;
}
.tb_wt_block_group.tb_equal_columns {
  display: -ms-flexbox !important;
  display: -webkit-flex !important;
  display: flex !important;
}
.tb_wt_block_group.tb_equal_columns > .row {
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
  min-width: 0;
}
.tb_wt_block_group.tb_equal_columns > .row > .col {
     -ms-flex-line-pack: stretch;
  -webkit-align-content: stretch;
          align-content: stretch;
}

/*  -----------------------------------------------------------------------------------------
    B R E A D C R U M B S
-----------------------------------------------------------------------------------------  */

.breadcrumb li {
  display: inline-block;
}
.breadcrumb li + li:before {
  content: '/';
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: 0.2em;
  margin-right: 0.4em;
  <?php else: ?>
  margin-left: 0.4em;
  margin-right: 0.2em;
  <?php endif; ?>
}

/*  -----------------------------------------------------------------------------------------
    C A L L   T O   A C T I O N
-----------------------------------------------------------------------------------------  */

.tb_wt_call_to_action > div {
  display: table-cell;
  vertical-align: middle;
}
.tb_wt_call_to_action .tb_description.tb_button_left  + .tb_button_holder { padding-right: 3em; }
.tb_wt_call_to_action .tb_description.tb_button_right + .tb_button_holder { padding-left: 3em; }

.tb_wt_call_to_action .tb_description.tb_button_bottom + .tb_button_holder {
  display: block;
  padding-top: <?php echo $base * 1.5; ?>px;
  text-align: center;
}
.tb_wt_call_to_action hr {
  margin-left: 15%;
  margin-right: 15%;
}
.tb_wt_call_to_action .tb_description :last-child {
  margin-bottom: 0 !important;
}

/*** Mobile ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .tb_wt_call_to_action .tb_button_holder {
    display: block !important;
    padding: 2em 0 0 0 !important;
    text-align: center;
  }
}

/*  -----------------------------------------------------------------------------------------
    C A T E G O R Y
-----------------------------------------------------------------------------------------  */

.tb_wt_categories .tb_expandable > * {
  margin-bottom: 0;
}
.tb_wt_categories ul:not(.tbInit) .tb_expandable:not(.tb_show):not(:first-child) > ul {
  display: none;
}
.tb_wt_categories .tb_toggle {
  display: block !important;
  <?php if ($lang_dir == 'ltr'): ?>
  float: right;
  <?php else: ?>
  float: left;
  <?php endif; ?>
  width: <?php echo $base; ?>px;
  height: <?php echo $base; ?>px;
  line-height: <?php echo $base; ?>px;
  text-align: center;
  font-size: 17px;
  cursor: pointer;
}
.tb_wt_categories .tb_toggle:before {
  content: '+'
}
.tb_wt_categories .tb_show > .tb_toggle:before {
  content: '-'
}
.tb_wt_categories .tb_accordion h2 {
  position: static;
}
.tb_wt_categories h2,
.tb_wt_categories h3,
.tb_wt_categories h4
{
  margin: 0;
}
.tb_wt_categories ul:not(.tb_list_1) > li + li,
.tb_wt_categories li > ul:first-child,
.tb_wt_categories li > :not(.tb_toggle) + ul
{
  padding-top: <?php echo $base * 0.5; ?>px;
}

/*** Grid ***/

.tb_wt_categories .tb_grid_view > li {
  display: block;
  padding-top: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    F A C E B O O K  /  T W I T T E R   B O X
-----------------------------------------------------------------------------------------  */

.tb_social_share,
#article_facebook_like,
.fb-like,
.fb-like span
{
  display: -ms-inline-flexbox !important;
  display: -webkit-inline-flex !important;
  display: inline-flex !important;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.tb_social_share:after {
  content: '\00a0';
}
.tb_social_share .fb_iframe_widget {
  vertical-align: baseline !important;
}
.fb-like iframe {
  max-width: none;
}
.tb_wt_facebook_likebox .tb_fb_likebox.tb_default {
  overflow: hidden;
  border: 1px solid;
}
.tb_wt_facebook_likebox .tb_fb_likebox.tb_default {
  height: 214px;
  max-height: 214px;
}
.tb_wt_facebook_likebox .tb_fb_likebox.tb_default.tb_small_header {
  height: 152px;
  max-height: 152px;
}
.tb_wt_facebook_likebox h2 + .tb_fb_likebox.tb_default {
  margin-top: <?php echo $base; ?>px;
}
.tb_wt_facebook_likebox .tb_fb_likebox.tb_default .tb_social_box_wrap {
  margin: -1px -2px -1px -1px;
}

/** custom FB box **/

.tb_social_box .tb_social_button {
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  right: 0;
  <?php else: ?>
  left: 0;
  <?php endif; ?>
  margin-top: <?php echo ($tbData->calculateLineHeight($base_h2_size, $base) - 20) * 0.5; ?>px;
}
h2.text-right + .tb_social_box .tb_social_button {
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  right: auto;
  <?php else: ?>
  right: 0;
  left: auto;
  <?php endif; ?>
}
.tb_pt_5  > .tb_social_box .tb_social_button { top: 5px;  }
.tb_pt_10 > .tb_social_box .tb_social_button { top: 10px; }
.tb_pt_15 > .tb_social_box .tb_social_button { top: 15px; }
.tb_pt_20 > .tb_social_box .tb_social_button { top: 20px; }
.tb_pt_25 > .tb_social_box .tb_social_button { top: 25px; }
.tb_pt_30 > .tb_social_box .tb_social_button { top: 30px; }
.tb_pt_35 > .tb_social_box .tb_social_button { top: 35px; }
.tb_pt_40 > .tb_social_box .tb_social_button { top: 40px; }
.tb_pt_45 > .tb_social_box .tb_social_button { top: 45px; }
.tb_pt_50 > .tb_social_box .tb_social_button { top: 50px; }
.tb_pl_5  > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 5px;<?php else: ?>left: 5px;<?php endif; ?>   }
.tb_pl_10 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 10px;<?php else: ?>left: 10px;<?php endif; ?> }
.tb_pl_15 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 15px;<?php else: ?>left: 15px;<?php endif; ?> }
.tb_pl_20 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 20px;<?php else: ?>left: 20px;<?php endif; ?> }
.tb_pl_25 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 25px;<?php else: ?>left: 25px;<?php endif; ?> }
.tb_pl_30 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 30px;<?php else: ?>left: 30px;<?php endif; ?> }
.tb_pl_35 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 35px;<?php else: ?>left: 35px;<?php endif; ?> }
.tb_pl_40 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 40px;<?php else: ?>left: 40px;<?php endif; ?> }
.tb_pl_45 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 45px;<?php else: ?>left: 45px;<?php endif; ?> }
.tb_pl_50 > .tb_social_box .tb_social_button { <?php if ($lang_dir == 'ltr'): ?>right: 50px;<?php else: ?>left: 50px;<?php endif; ?> }

.tb_social_box .tb_social_button > div,
.tb_social_box .tb_social_button > div > span,
.tb_social_box .tb_social_button > div > iframe
{
  display: inline-block !important;
  height: 20px !important;
  vertical-align: top !important;
}
.tb_social_box .tb_social_button > div {
  margin-top: -3px;
  vertical-align: middle !important;
}


.tb_fb_likebox.tb_custom > div > div > div,
.tb_fb_likebox.tb_custom ._4s7c
{
  height: auto !important;
}
.tb_fb_likebox.tb_custom .hidden_elem {
  display: none !important;
}
.tb_fb_likebox.tb_custom .lfloat {
  float: left;
}
.tb_fb_likebox.tb_custom .rfloat {
  float: right;
}
.tb_social_box img {
  display: block;
}
.tb_social_box .tb_profile .thumbnail {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base; ?>px;
  <?php endif; ?>
}
.tb_social_box .tb_profile h3 {
  margin-bottom: 0;
}
.tb_social_box .tb_profile h3 small {
  font-size: <?php echo $base_font_size; ?>px;
  opacity: 0.8;
}
.tb_fb_likebox.tb_custom ._8o,
.tb_fb_likebox.tb_custom  ._8o .img
{
  display: block;
}
.tb_fb_likebox.tb_custom ._8r {
  margin: 0 <?php echo $base / 2; ?>px 0 0;
}
.tb_fb_likebox.tb_custom ._8u > * {
  display: inline;
}
.tb_social_box .plm {
  overflow: hidden;
  clear: both;
  padding: 0 0 <?php echo $base; ?>px 0;
}
.tb_social_box .plm * {
  display: inline;
}
.tb_social_box .tb_profile + .plm {
  clear: none;
  margin-top: <?php echo $base * 0.25; ?>px;
}
.tb_fb_likebox.tb_custom .plm a {
  font-weight: bold;
}

/*** facepile ***/

.tb_social_box .uiList {
  overflow: hidden;
  clear: both;
  display: block;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
  -ms-flex-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  margin-top: -<?php echo $base; ?>px;
  margin-left: -<?php echo $base * 0.75; ?>px;
  text-align: justify;
  letter-spacing: 10px;
}
.tb_social_box.tb_show_title .uiList {
  margin-top: -<?php echo $base * 0.5; ?>px;
}
.tb_social_box .tb_profile + .plm + .uiList {
  margin-top: 0;
}
.tb_social_box.tb_show_title .tb_profile + .plm + .uiList {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.tb_social_box .uiList:after {
  content: '';
  display: inline-block;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  width: 100%;
}
.tb_social_box .uiList li {
  display: inline-block;
  width: 50px;
  margin-left: <?php echo $base * 0.75; ?>px;
  padding-top: <?php echo $base; ?>px;
  vertical-align: top;
}
.tb_social_box.tb_show_border .uiList li {
  width: 60px;
}
.tb_social_box.tb_show_title .uiList li {
  padding-top: <?php echo $base * 0.5; ?>px;
}
.tb_social_box .uiList li a,
.tb_social_box .uiList li img
{
  display: block;
}
.tb_social_box.tb_show_title .uiList li .link:after {
  content: attr(title);
  overflow: hidden;
  display: block;
  width: 100%;
  max-width: 100%;
  height: <?php echo $base; ?>px;
  text-align: center;
  word-spacing: 0;
  letter-spacing: 0;
  font-size: 10px;
}
.tb_social_box .uiList li img {
  width: 100%;
  height: auto;
}
.tb_social_box.tb_show_border .uiList li img {
  padding: 5px;
  background: #fff;
}
.tb_social_box.tb_max_w_200 .tb_fb_like {
  position: static;
  margin: 0 0 <?php echo $base * 0.5; ?>px 0;
}
.tb_social_box.tb_max_w_200 .plm {
  margin-top: 0;
}

.no_title > .tb_social_box {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
.no_title > .tb_social_box .plm {
  -ms-flex-order: 1;
   -webkit-order: 1;
           order: 1;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  padding-bottom: <?php echo $base; ?>px;
}
.no_title > .tb_social_box .tb_social_button {
  position: static;
  -ms-flex-order: 2;
   -webkit-order: 2;
           order: 2;
  margin: 0;
}
.no_title > .tb_social_box .uiList {
  -ms-flex-order: 3;
   -webkit-order: 3;
           order: 3;
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}

/*  -----------------------------------------------------------------------------------------
    F I R E   S L I D E R
-----------------------------------------------------------------------------------------  */

.tb_wt_fire_slider {
  position: static;
}
.tb_wt_fire_slider .tb_placeholder {
  display: none;
}
.tb_wt_fire_slider .mSButtons {
  opacity: 0;
}
.tb_wt_fire_slider:hover .mSButtons {
  opacity: 1;
}
.mightySlider {
  direction: ltr;
}
.mSCaption {
  direction: <?php echo $lang_dir; ?>;
}
body > .tb_wt_fire_slider {
  margin: 0;
}
[id*="FireSlider"] .mSPages {
  padding-bottom: 20px;
}

/*  -----------------------------------------------------------------------------------------
    G A L L E R Y
-----------------------------------------------------------------------------------------  */

.tb_gallery {
  position: relative;
  overflow: hidden;
}

/*  Slider  ------------------------------------------------------------------------------ */

.tb_gallery .tb_slides {
  overflow: hidden;
}
.tb_gallery.tb_thumbs_vertical .tb_slides {
  position: absolute;
  top: 0;
  height: 100%;
}
.tb_gallery.tb_thumbs_vertical .tb_slides:last-child {
  width: 100%;
  margin: 0 !important;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_position_right .tb_slides {
  left: 0;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_position_left .tb_slides {
  right: 0;
}

/*  Thumbnails  -------------------------------------------------------------------------- */

.tb_gallery .tb_thumbs ul > li > img {
  opacity: 0.5;
  -webkit-transition: opacity 0.3s;
  transition: opacity 0.3s;
}
.no_touch .tb_gallery .tb_thumbs ul > li:hover > img,
.tb_gallery .tb_thumbs ul > li.active > img
{
  opacity: 1;
}

/*** Horizontal ***/

.tb_gallery.tb_thumbs_horizontal .tb_thumbs_wrap {
  width: 100%;
}

/*** 1px spacing ***/

.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_1px .tb_thumbs_wrap {
  margin-top: 1px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_1px.tb_thumbs_crop .tb_thumbs > div {
  margin-left: -1px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_1px.tb_thumbs_crop .tb_thumbs ul > li {
  padding-left: 1px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_1px:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-left: 1px;
}

/*** xs spacing ***/

.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_xs .tb_thumbs_wrap {
  margin-top: <?php echo $base * 0.25; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_xs.tb_thumbs_crop .tb_thumbs > div {
  margin-left: -<?php echo $base * 0.25; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_xs.tb_thumbs_crop .tb_thumbs ul > li {
  padding-left: <?php echo $base * 0.25; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_xs:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-left: <?php echo $base * 0.25; ?>px;
}

/*** sm spacing ***/

.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_sm .tb_thumbs_wrap {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_sm.tb_thumbs_crop .tb_thumbs > div {
  margin-left: -<?php echo $base * 0.5; ?>px;
  padding-top: <?php echo $base * 0.5; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_sm.tb_thumbs_crop .tb_thumbs ul > li {
  padding-left: <?php echo $base * 0.5; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_sm:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-left: <?php echo $base * 0.5; ?>px;
}

/*** md spacing ***/

.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_md .tb_thumbs_wrap {
  margin-top: <?php echo $base; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_md.tb_thumbs_crop .tb_thumbs > div {
  margin-left: -<?php echo $base; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_md.tb_thumbs_crop .tb_thumbs ul > li {
  padding-left: <?php echo $base; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_md:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-left: <?php echo $base; ?>px;
}

/*** lg spacing ***/

.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_lg .tb_thumbs_wrap {
  margin-top: <?php echo $base * 1.5; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_lg.tb_thumbs_crop .tb_thumbs > div {
  margin-left: -<?php echo $base * 1.5; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_lg.tb_thumbs_crop .tb_thumbs ul > li {
  padding-left: <?php echo $base * 1.5; ?>px;
}
.tb_gallery.tb_thumbs_horizontal.tb_thumbs_spacing_lg:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-left: <?php echo $base * 1.5; ?>px;
}

/*** Vertical ***/

.tb_gallery.tb_thumbs_vertical .tb_thumbs_wrap {
  position: absolute;
  top: 0;
  bottom: 0;
}
.tb_gallery.tb_thumbs_vertical .tb_thumbs > div {
  padding-top: 0 !important;
}
.tb_gallery.tb_thumbs_vertical .tb_thumbs .mSSlideElement {
  clear: left;
}
.tb_gallery.tb_thumbs_vertical .tb_thumbs ul > li {
  float: left;
  width: 100%;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_position_right .tb_thumbs_wrap {
  right: 0;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_position_left .tb_thumbs_wrap {
  left: 0;
}

/*** 1px spacing ***/

.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_1px.tb_thumbs_crop .tb_thumbs_wrap {
  top: -1px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_1px.tb_thumbs_crop .tb_thumbs ul > li {
  padding-top: 1px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_1px:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-top: 1px;
}

/*** xs spacing ***/

.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_xs.tb_thumbs_crop .tb_thumbs_wrap {
  top: -<?php echo $base * 0.25; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_xs.tb_thumbs_crop .tb_thumbs ul > li {
  padding-top: <?php echo $base * 0.25; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_xs:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-top: <?php echo $base * 0.25; ?>px;
}

/*** sm spacing ***/

.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_sm.tb_thumbs_crop .tb_thumbs_wrap {
  top: -<?php echo $base * 0.5; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_sm.tb_thumbs_crop .tb_thumbs ul > li {
  padding-top: <?php echo $base * 0.5; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_sm:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-top: <?php echo $base * 0.5; ?>px;
}

/*** md spacing ***/

.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_md.tb_thumbs_crop .tb_thumbs_wrap {
  top: -<?php echo $base; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_md.tb_thumbs_crop .tb_thumbs ul > li {
  padding-top: <?php echo $base; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_md:not(.tb_thumbs_crop) .tb_thumbs ul > li:not(:first-child) {
  margin-top: <?php echo $base; ?>px;
}

/*** lg spacing ***/

.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_lg.tb_thumbs_crop .tb_thumbs_wrap {
  top: -<?php echo $base * 1.5; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_lg.tb_thumbs_crop .tb_thumbs ul > li {
  padding-top: <?php echo $base * 1.5; ?>px;
}
.tb_gallery.tb_thumbs_vertical.tb_thumbs_spacing_lg:not(.tb_thumbs_crop) .tb_thumbs  ul > li:not(:first-child) {
  margin-top: <?php echo $base * 1.5; ?>px;
}

/*  Prev / next buttons  ----------------------------------------------------------------- */

.tb_gallery.tb_nav_visibility_hover .tb_slides .mSButtons {
  opacity: 0;
}
.tb_gallery.tb_nav_visibility_hover .tb_slides:hover .mSButtons {
  opacity: 1;
}

/*  Pagination  -------------------------------------------------------------------------- */

.tb_gallery.tb_dots_outside {
  padding-bottom: <?php echo $base * 1.5; ?>px;
}
.tb_gallery:not(.tb_dots_outside) .tb_pagination {
  padding-bottom: 20px;
}

/*  Loading  ----------------------------------------------------------------------------- */

.tb_gallery .wait {
  width: 40px;
  height: 40px;
}

/*  Captions  ---------------------------------------------------------------------------- */

.tb_gallery .tb_caption {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100% !important;
  height: auto !important;
  padding: 20px !important;
  line-height: inherit !important;
  opacity: 0;
}
.tb_gallery .tb_caption:after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  width: 100%;
  height: 100%;
}
.tb_gallery .tb_caption .tb_text {
  position: relative;
  z-index: 1;
}

/*  Grid gallery  ------------------------------------------------------------------------ */

.tb_gallery.tb_grid_view a {
  position: relative;
  display: block;
  text-align: center;
}
.tb_gallery.tb_grid_view a .tb_icon {
  position: absolute;
  top: 50%;
  left: 50%;
  width: <?php echo $base; ?>px;
  height: <?php echo $base; ?>px;
  margin: -<?php echo $base * 0.5; ?>px 0 0 -<?php echo $base * 0.5; ?>px;
  text-align: center;
  text-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  font-size: 25px;
  color: #fff;
  opacity: 0;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
.tb_gallery.tb_grid_view a:hover span {
  display: block;
  opacity: 1;
}

/*  Fullscreen button  ------------------------------------------------------------------- */

.tb_gallery .tb_fullscreen_button {
  position: absolute;
  z-index: 5;
  margin: 0;
  border-radius: 0;
  box-shadow: none;
  -webkit-transition: all 0.2s ease-in-out;
          transition: all 0.2s ease-in-out;
}

/*** View on hover ***/

.tb_gallery.tb_fullscreen_button_hover .tb_fullscreen_button {
  opacity: 0;
}
.is_touch .tb_gallery.tb_fullscreen_button_hover .tb_fullscreen_button,
.tb_gallery.tb_fullscreen_button_hover:hover .tb_fullscreen_button
{
  opacity: 1;
}

/*** Button positions ***/

.tb_gallery.tb_fullscreen_button_position_tr .tb_fullscreen_button {
  top: 0;
  right: 0;
}
.tb_gallery.tb_fullscreen_button_position_br .tb_fullscreen_button {
  bottom: 0;
  right: 0;
}
.tb_gallery.tb_fullscreen_button_position_bl .tb_fullscreen_button {
  bottom: 0;
  left: 0;
}
.tb_gallery.tb_fullscreen_button_position_tl .tb_fullscreen_button {
  top: 0;
  left: 0;
}

/*  -----------------------------------------------------------------------------------------
    G O O G L E   M A P S
-----------------------------------------------------------------------------------------  */

.tb_map_wrap > span.tb_loading_wrap {
  position: absolute;
  left: 50%;
  display: block;
  margin-left: -8px;
}
.tb_map_wrap > span.tb_loading_wrap > * {
  margin: 1px 0 0 0;
}
.tb_wt_google_maps.tb_full {
  position: static;
}
.tb_wt_google_maps.tb_full .tb_map_holder {
  position: absolute;
  left: 0;
}
.tb_map_holder {
  overflow: hidden;
  position: relative;
  width: 100%;
  height: 100%;
}
.tb_map_holder.tb_style_2 {
  z-index: 1;
  box-shadow: 5px 5px 0 0 rgba(0, 0, 0, 0.1);
}
.tb_map_holder.tb_style_2:before {
  box-shadow:
    0 1px 0 0 rgba(255, 255, 255, 0.5) inset,
    0 -10px 20px 0 rgba(0, 0, 0, 0.1) inset;
}
.tb_map iframe {
  display: block;
  width: 100%;
}

/*  -----------------------------------------------------------------------------------------
    I C O N   L I S T
-----------------------------------------------------------------------------------------  */

.tb_icon_list {
  margin-bottom: 0;
}
.tb_icon_list > li {
  display: block;
}
.tb_icon_list li .tb_icon_wrap {
  display: -webkit-inline-flex;
  display: inline-flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
.tb_icon_list li .tb_icon {
  margin: 0;
}
.tb_icon_list li .tb_icon:before {
  margin-left: 0;
  margin-right: 0;
}
.tb_icon_list li .tb_description > :last-child {
  margin-bottom: 0;
}
.tb_icon_list.tb_description_tooltip .tb_description_wrap {
  display: none !important;
}
.tb_icon_list.tb_description_tooltip span.tb_icon {
  cursor: help;
}
.tb_icon_list .tb_icon.tb_style_1 {
  border-radius: 3px;
  box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.2);
}
.tb_icon_list .tb_icon.tb_style_1:before {
  margin-top: -1px;
}
.tb_icon_list .tb_icon.tb_style_2 {
  border-radius: 100px;
}
.tb_icon_list .tb_icon.tb_style_4,
.tb_icon_list .tb_icon.tb_style_5
{
  border-width: 2px;
  border-style: solid;
  background-color: transparent !important;
}
.tb_icon_list .tb_icon.tb_style_5 {
  border-radius: 50%;
}
.tb_icon_list .tb_icon.tb_style_4:before,
.tb_icon_list .tb_icon.tb_style_5:before
{
  margin-top: -2px;
}

/*** Inline view ***/

.tb_icon_list.tb_inline_view li {
  display: -webkit-inline-flex;
  display: inline-flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  vertical-align: top;
}
.tb_icon_list.tb_inline_view li .tb_description_wrap
{
  display: inline-block;
  vertical-align: top;
}
.tb_icon_list.tb_inline_view.text-justify > ul {
  line-height: 0;
  text-align: justify;
}
.tb_icon_list.tb_inline_view.text-justify > ul:after {
  content: ' ';
  display: inline-block;
  width: 99%;
  vertical-align: top;
}

/*** List view ***/

.tb_icon_list.tb_list_view ul {
  display: table;
  width: 100%;
}
.tb_icon_list.tb_list_view li {
  display: table-row;
}
.tb_icon_list.tb_list_view li > * {
  display: table-cell;
  vertical-align: middle;
}
.tb_icon_list.tb_list_view li:last-child > * {
  padding-bottom: 0;
}
.tb_icon_list.tb_list_view li .tb_icon {
  display: block;
  margin: 0 auto;
}
.tb_icon_list.tb_list_view li .tb_description {
  display: inline-block;
  width: 100%;
}
.tb_icon_list.tb_list_view.tb_description_right {
  direction: ltr;
}
.tb_icon_list.tb_list_view.tb_description_left {
  direction: rtl;
}
.tb_icon_list.tb_list_view.tb_description_left li,
.tb_icon_list.tb_list_view.tb_description_right li
{
  <?php if ($lang_dir == 'ltr'): ?>
  direction: ltr;
  <?php else: ?>
  direction: rtl;
  <?php endif; ?>
}
.tb_icon_list.tb_list_view.tb_description_right li .tb_description_wrap {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: 1em;
  <?php else: ?>
  padding-right: 1em;
  <?php endif; ?>
}
.tb_icon_list.tb_list_view.tb_description_left li .tb_description_wrap {
  padding-right: 15px;
}
.tb_icon_list.tb_list_view.tb_icons_top li .tb_icon {
  vertical-align: top;
}
.tb_icon_list.tb_list_view.tb_icons_top li .tb_icon_wrap {
  vertical-align: top;
}

/*** Grid view ***/

.tb_icon_list.tb_grid_view li {
  direction: ltr;
}
.tb_icon_list.tb_grid_view li .tb_icon_wrap {
  text-align: center;
}
.tb_icon_list.tb_grid_view li .tb_icon_wrap .tb_icon {
  margin: 0 !important;
  vertical-align: middle;
}
.tb_icon_list.tb_grid_view .tb_description_wrap {
  <?php if ($lang_dir == 'ltr'): ?>
  direction: ltr;
  <?php else: ?>
  direction: rtl;
  <?php endif; ?>
}
.tb_icon_list.tb_grid_view.tb_description_bottom li {
  text-align: center;
}
.tb_icon_list.tb_grid_view.tb_description_bottom li .tb_icon_wrap {
  margin: 0 auto 20px auto;
}
.tb_icon_list.tb_grid_view.tb_description_bottom li .tb_icon_wrap:last-child {
  margin-bottom: 0;
}
.tb_icon_list.tb_grid_view.tb_description_right li,
.tb_icon_list.tb_grid_view.tb_description_left li
{
  position: relative;
}
.tb_icon_list.tb_grid_view.tb_description_right .tb_icon_wrap,
.tb_icon_list.tb_grid_view.tb_description_left .tb_icon_wrap
{
  position: absolute;
  top: 50%;
  margin-left: 0;
  margin-right: 0;
}
.tb_icon_list.tb_grid_view.tb_description_left .tb_icon_wrap {
  right: 0;
}
.tb_icon_list.tb_grid_view.tb_description_bottom .tb_icon {
  margin: 0;
}
.tb_icon_list.tb_grid_view.tb_icons_top .tb_icon_wrap {
  top: 0;
  margin-top: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    L A T E S T   R E V I E W S
-----------------------------------------------------------------------------------------  */

.tb_wt_latest_reviews:hover {
  position: relative;
  z-index: 100;
  z-index: 40;
}
.tb_wt_latest_reviews .product-thumb {
  background-color: transparent !important;
}
.tb_wt_latest_reviews .caption {
      -ms-flex: 1 1 0px !important;
  -webkit-flex: 1 1 0px !important;
          flex: 1 1 0px !important;
}
.tb_wt_latest_reviews .caption > .tb_review {
  min-width: 100%;
}
.tb_wt_latest_reviews .tb_slider_pagination {
  margin-top: <?php echo $base * 0.5; ?>px;
}

/*  -----------------------------------------------------------------------------------------
    L A T E S T   T W E E T S
-----------------------------------------------------------------------------------------  */

.tb_wt_latest_tweets .panel-heading {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.tb_wt_latest_tweets .panel-heading.text-right {
  direction: rtl;
}
.tb_wt_latest_tweets .panel-title {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}
.tb_wt_latest_tweets .panel-title .tb_icon {
  word-spacing:   0 !important;
  letter-spacing: 0 !important;
}
.tb_wt_latest_tweets .panel-heading > a.twitter-follow-button {
  display: none;
}
.tb_wt_latest_tweets > iframe {
  <?php if ($lang_dir == 'rtl'): ?>
  float: left !important;
  <?php endif; ?>
  margin-top: <?php echo ($tbData->calculateLineHeight($base_h2_size, $base) - 20) * 0.5; ?>px;
}
.tb_tweet {
  box-shadow: none !important;
}
.tb_tweet h3 {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: <?php echo $base * 3; ?>px;
  <?php else: ?>
  padding-left: <?php echo $base * 3; ?>px;
  <?php endif; ?>
}
.tb_tweet h3 small {
  vertical-align: top;
  font-size: 11px;
}
.tb_tweet .tb_status {
  margin-bottom: 0;
}
.tb_tweet .tb_date {
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  right: 0;
  <?php else: ?>
  left: 0;
  <?php endif; ?>
  white-space: nowrap;
}
.tb_tweet .tb_actions {
  position: absolute;
  bottom: <?php echo $base; ?>px;
  left: <?php echo $base; ?>px;
  right: <?php echo $base; ?>px;
  display: none;
}
.tb_tweet:hover .tb_actions {
  display: block;
}
.tb_tweet .tb_item_info {
  overflow: hidden;
  position: relative;
}
.tb_listing.tb_has_hover > .tb_tweet:hover {
  z-index: 2;
  margin: 0 -<?php echo $base; ?>px -<?php echo $base * 2.5; ?>px -<?php echo $base; ?>px !important;
  padding: <?php echo $base; ?>px;
  background: #fff;
  border-radius: 2px;
  box-shadow:
  0 1px 0 0 rgba(0, 0, 0, 0.1),
  0 0 0 1px rgba(0, 0, 0, 0.08),
  0 1px 5px 0 rgba(0, 0, 0, 0.2) !important;
}
.tb_listing.tb_has_hover > .tb_tweet:first-child:hover {
  margin-top: -<?php echo $base; ?>px !important;
}
.tb_listing.tb_has_hover > .tb_tweet:hover {
  padding-bottom: <?php echo $base * 2.5; ?>px;
}

.tb_listing.tb_style_1 > .tb_tweet .thumbnail {
  <?php if ($lang_dir == 'ltr'): ?>
  margin: 0 <?php echo $base; ?>px 0 0;
  <?php else: ?>
  margin: 0 0 0 <?php echo $base; ?>px;
  <?php endif; ?>
}
.tb_listing.tb_style_1 > .tb_tweet .thumbnail img {
  width: <?php echo $base * 3; ?>px;
}

.tb_tweets .tb_tweet + .tb_tweet {
  margin-top: <?php echo $base; ?>px !important;
}
.tb_tweets.tb_style_2,
.tb_tweets.tb_style_2 > .tb_tweet
{
  border: none !important;
}
.tb_listing.tb_style_2 > .tb_tweet .thumbnail {
  <?php if ($lang_dir == 'ltr'): ?>
  margin: 0 <?php echo $base * 0.5; ?>px 0 0;
  <?php else: ?>
  margin: 0 0 0 <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_listing.tb_style_2 > .tb_tweet .thumbnail img {
  width: <?php echo $base * 2; ?>px;
}
.tb_listing.tb_style_2 > .tb_tweet .tb_item_info {
  overflow: visible;
}
.tb_listing.tb_style_2 > .tb_tweet h3 small {
  display: block;
}
.tb_listing.tb_style_2 > .tb_tweet .tb_status {
  clear: both;
}

.tb_listing.tb_style_3 .tb_tweet .tb_item_info {
  overflow: visible;
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 3; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 3; ?>px;
  <?php endif; ?>
}
.tb_listing.tb_style_3 .tb_tweet .tb_item_info:before {
  content: '\201C';
  <?php if ($lang_dir == 'ltr'): ?>
  left: -<?php echo $base * 3; ?>px;
  <?php else: ?>
  right: -<?php echo $base * 3; ?>px;
  <?php endif; ?>
  position: absolute;
  top: -<?php echo $base * 0.5; ?>px;
  width: 35px;
  height: 50px;
  line-height: 100px;
  text-align: center;
  font-size: 100px;
  font-family: Arial;
  font-style: normal;
  opacity: 0.15;
}
.tb_listing.tb_style_3 .tb_tweet .tb_status,
.tb_listing.tb_style_3 .tb_tweet .tb_date
{
  position: static;
  display: inline;
}

/*  -----------------------------------------------------------------------------------------
    M A I N   N A V I G A T I O N
-----------------------------------------------------------------------------------------  */

.tb_wt_header_main_navigation_system {
  position: static;
}

/******    Manufacturers    **************************/

.tb_wt_manufacturers {
  -webkit-transition: height 0.5s ease-in-out;
  transition: height 0.5s ease-in-out;
}
.tb_manufacturers.tb_grid_view {
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
.tb_wt_manufacturers:not(.has_slider) .panel-body {
  position: relative;
}

/*  -----------------------------------------------------------------------------------------
    M E N U
-----------------------------------------------------------------------------------------  */

.tb_wt_menu .tb_selected > a,
.tb_wt_menu .tb_selected > span
{
  font-weight: bold;
}
.col-align-center > .tb_wt_menu.display-inline-block .nav-horizontal {
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
}
.col-align-end > .tb_wt_menu.display-inline-block .nav-horizontal {
            -ms-flex-pack: end;
  -webkit-justify-content: flex-end;
          justify-content: flex-end;
}
.tb_wt_menu .h2,
.tb_wt_menu .h3,
.tb_wt_menu .h4
{
  display: table;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.tb_wt_menu .h2,
.tb_wt_menu .h2 + .tb_list_1,
.tb_wt_menu .h2 + ul > li:last-child > ul,
.tb_wt_menu .h2 + ul > li:last-child > h3:last-child
{
  margin-bottom: <?php echo $base; ?>px;
}
.tb_wt_menu .h3,
.tb_wt_menu .h3 + .tb_list_1,
.tb_wt_menu .h4,
.tb_wt_menu .h4 + .tb_list_1
{
  margin-bottom: <?php echo $base * 0.5; ?>px;
}
.tb_wt_menu .nav-stacked > li:last-child > .h2:last-child,
.tb_wt_menu .nav-stacked > li:last-child > .h3:last-child,
.tb_wt_menu .nav-stacked > li:last-child > .h4:last-child,
.tb_wt_menu .nav-stacked > li:last-child > .tb_list_1,
.tb_wt_menu > nav > ul > li:last-child > ul > li:last-child > .tb_list_1:last-child
{
  margin-bottom: 0;
}
.tb_wt_menu > ul:last-child {
  margin-bottom: 0 !important;
}
.tb_wt_menu nav,
.tb_wt_menu nav > .nav
{
  border-radius: inherit;
}
.tb_wt_menu .nav-stacked.tb_separate_menus > li > a {
  margin-bottom: 0;
}

/*  -----------------------------------------------------------------------------------------
    N E W S L E T T E R
-----------------------------------------------------------------------------------------  */

.tb_wt_newsletter .form-group {
  max-width: 100%;
}
.tb_wt_newsletter .form-inline .form-group {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 1em;
  <?php else: ?>
  margin-left: 1em;
  <?php endif; ?>
}
.tb_wt_newsletter .form-inline .form-group {
  position: relative;
}
.tb_wt_newsletter .form-inline .form-group .text-danger {
  position: absolute;
  bottom: -<?php echo $base * 1.25; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  <?php else: ?>
  right: 0;
  <?php endif; ?>
  min-width: 0 !important;
  text-align: initial;
}

/*  -----------------------------------------------------------------------------------------
    P R O D U C T   S L I D E R
-----------------------------------------------------------------------------------------  */

.tb_wt_product_slider .panel-body > .row > .col-xs-12:first-child {
  display: block;
}
@media (max-width: <?php echo $screen_sm; ?>px) {
  .tb_wt_product_slider .col-xs-12:first-child {
        -ms-flex: 1 1 180px;
    -webkit-flex: 1 1 180px;
            flex: 1 1 180px;
  }
  .tb_wt_product_slider .col-xs-12:last-child {
        -ms-flex: 1 1 250px;
    -webkit-flex: 1 1 250px;
            flex: 1 1 250px;
  }
}
.tb_wt_product_slider .product-thumb {
  background-color: transparent !important;
}
.tb_wt_product_slider .tb_slider_controls {
  overflow: visible !important;
  visibility: visible;
}
.tb_wt_product_slider .tb_slider_controls a {
  z-index: 1;
  margin-left:  0 !important;
  margin-right: 0 !important;
}
.tb_wt_product_slider .tb_slider_controls .tb_prev {
  left: -15% !important;
}
.tb_wt_product_slider .tb_slider_controls .tb_next {
  right: -15% !important;
}
.tb_wt_product_slider .tb_item_thumb_wrap {
  position: relative;
  max-width: 100%;
  padding-bottom: 100%;
  background: #fff;
  border-radius: 50%;
}
.tb_wt_product_slider .tb_item_thumb_wrap > div {
  position: absolute;
  top: 15%;
  right: 15%;
  bottom: 15%;
  left: 15%;
}
.tb_wt_product_slider .tb_item_thumb .tb_products,
.tb_wt_product_slider .tb_item_thumb .swiper-container,
.tb_wt_product_slider .tb_item_thumb .swiper-wrapper,
.tb_wt_product_slider .tb_item_thumb .swiper-slide,
.tb_wt_product_slider .tb_item_thumb .product-thumb .image
{
  margin:  0 !important;
  padding: 0 !important;
}
.tb_wt_product_slider .tb_item_thumb .tb_products,
.tb_wt_product_slider .tb_item_thumb .swiper-container,
.tb_wt_product_slider .tb_item_thumb .swiper-wrapper
{
  height: 100% !important;
}
.tb_wt_product_slider .tb_item_thumb .product-thumb .image {
  display:  -ms-flexbox;
  display: -webkit-flex;
  display:         flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
            -ms-flex-pack: center;
  -webkit-justify-content: center;
          justify-content: center;
  width: 100%;
  max-width: none;
}
.tb_wt_product_slider .tb_item_thumb .product-thumb .image + div {
  display: none;
}

.tb_wt_product_slider .tb_item_info {
  -webkit-transition: opacity 0.3s;
          transition: opacity 0.3s;
  opacity: 0;
}
.tb_wt_product_slider .tb_item_info.tbShowInfo {
  opacity: 1;
}
.tb_wt_product_slider .tb_item_info .image {
  display: none;
}

.tb_wt_product_slider .swiper-wrapper,
.tb_wt_product_slider .swiper-slide > div
{
  border: none !important;
  box-shadow: none !important;
}
.tb_wt_product_slider .product-layout,
.tb_wt_product_slider .product-thumb
{
  height: 100%;
  padding: 0;
  background-color: transparent !important;
}

@media (max-width: <?php echo $screen_sm; ?>px) {
  .tb_wt_product_slider .tb_item_info .description {
    display: none;
  }
}

/*  -----------------------------------------------------------------------------------------
    S E P A R A T O R
-----------------------------------------------------------------------------------------  */

.tb_separator {
  overflow: hidden;
  position: relative;
}
.tb_separator .tb_title {
  z-index: 2;
  position: relative;
  display: inline-block;
  padding: 0 1em;
  vertical-align: top;
}
.tb_separator .tb_title .border {
  z-index: 1;
  position: absolute;
  top: 50%;
  width: 1000px;
}
.tb_separator .tb_title .tb_position_left {
  left: -1000px;
}
.tb_separator .tb_title .tb_position_right {
  right: -1000px;
}
.tb_separator > span.border {
  left: 0;
  width: 100%;
  margin-bottom: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    T A B S  /  A C C O R D I O N
-----------------------------------------------------------------------------------------  */

.tb_wt_group.tb_tabs_style_3 {
  position: static;
}
<?php if ($lang_dir == 'ltr'): ?>
.tb_wt_group.tabs-right { direction: rtl; }
<?php endif; ?>
<?php if ($lang_dir == 'rtl'): ?>
.tb_wt_group.tabs-left  { direction: ltr; }
<?php endif; ?>

.tb_wt_group .tb_wt:hover {
  z-index: auto;
}
.tb_wt_group .nav.nav-tabs:not([class*="tb_mb_"]) {
  margin-bottom: 0;
}

/*  -----------------------------------------------------------------------------------------
    T E X T
-----------------------------------------------------------------------------------------  */

.tb_text_wrap br {
  display: block;
}
.tb_text_wrap ul:not(.list-unstyled),
.tb_text_wrap ol:not(.list-unstyled)
{
  list-style-position: inside;
}
.tb_text_wrap ul:not(.list-unstyled) {
  list-style-type: disc;
}
.tb_text_wrap ul:not(.list-unstyled) ul:not(.list-unstyled),
.tb_text_wrap ul:not(.list-unstyled) ol:not(.list-unstyled),
.tb_text_wrap ol:not(.list-unstyled) ul:not(.list-unstyled),
.tb_text_wrap ol:not(.list-unstyled) ol:not(.list-unstyled)
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base; ?>px;
  <?php endif; ?>
}
.tb_text_wrap ol:not(.list-unstyled) {
  padding-left: 15px;
  list-style: decimal;
}
.tb_text_wrap blockquote {
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  padding: <?php echo $base * 0.75; ?>px <?php echo $base; ?>px <?php echo $base * 0.75; ?>px <?php echo $base * 4 - 5; ?>px;
  <?php else: ?>
  padding: <?php echo $base * 0.75; ?>px <?php echo $base * 4 - 5; ?>px <?php echo $base * 0.75; ?>px <?php echo $base; ?>px;
  <?php endif; ?>
  line-height: <?php echo $base * 1.5; ?>px;
  font-size: <?php echo $base_font_size * 1.3; ?>px;
  font-style: italic;
}
.tb_text_wrap blockquote:before {
  <?php if ($lang_dir == 'ltr'): ?>
  content: '\201C';
  left: <?php echo $base; ?>px;
  <?php else: ?>
  content: '\201D';
  right: <?php echo $base; ?>px;
  <?php endif; ?>
  position: absolute;
  top: <?php echo $base / 4; ?>px;
  width: 35px;
  height: 50px;
  line-height: 100px;
  text-align: center;
  font-size: 100px;
  font-family: Arial;
  font-style: normal;
  opacity: 0.2;
}
.tb_text_wrap blockquote:after {
  content: '';
  position: absolute;
  top: 0;
  <?php if ($lang_dir == 'ltr'): ?>
  left: 0;
  border-right: 5px solid;
  <?php else: ?>
  right: 0;
  border-left: 5px solid;
  <?php endif; ?>
  width: 0;
  height: 100%;
  opacity: 0.2
}
.tb_text_wrap blockquote.pull-left {
  margin: 0 <?php echo $base; ?>px <?php echo $base; ?>px 0;
}
.tb_text_wrap blockquote.pull-right {
  margin: 0 0 <?php echo $base; ?>px <?php echo $base; ?>px;
}
.tb_text_wrap > :last-child {
  margin-bottom: 0;
}
