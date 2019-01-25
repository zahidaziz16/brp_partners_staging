/*  -----------------------------------------------------------------------------------------
    M F P
-----------------------------------------------------------------------------------------  */

.mfilter-box.mfilter-box.mfilter-box,
.mfilter-box *:not(input):not(.btn),
.mfilter-box.mfilter-box *:not(input):not(.btn),
.mfilter-box.mfilter-box.mfilter-box *:not(input):not(.btn),
.mfilter-box.mfilter-box.mfilter-box.mfilter-box input[type="checkbox"],
.mfilter-box.mfilter-box.mfilter-box.mfilter-box input[type="radio"],
.mfilter-box.mfilter-box.mfilter-box *:before,
.mfilter-box.mfilter-box.mfilter-box *:after
{
  margin: 0;
  padding: 0;
  border: none !important;
  background: transparent none !important;
}
.mfilter-box .mfilter-option.mfilter-price {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
}
.mfilter-box .mfilter-option.mfilter-price > * {
  width: 100%;
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.mfilter-box .mfilter-option.mfilter-price .mfilter-price-inputs {
  -ms-flex-order: 10;
   -webkit-order: 10;
           order: 10;
  padding-top: <?php echo $base; ?>px !important;
}
.mfilter-box .mfilter-option.mfilter-price .mfilter-price-inputs input {
  display: inline-block;
  width: 60px !important;
  min-width: 0 !important;
  max-width: none !important;
  height: auto;
  padding: 0 <?php echo $base * 0.25 - 1; ?>px !important;
  background: #eee;
}
#wrapper #content .mfilter-price-slider,
#wrapper #content #mfilter-price-slider,
#wrapper #content #mfilter-price-slider *,
#wrapper #content .mfilter-slider-slider,
#wrapper #content .mfilter-slider-slider *
{
  margin:  0 !important;
  padding: 0 !important;
  background: transparent none !important;
  border: none !important;
  box-shadow: none !important;
}
#wrapper #content #mfilter-price-slider,
#wrapper #content .mfilter-slider-slider
{
  height: 6px !important;
  margin-top:   <?php echo $base; ?>px !important;
  margin-left:  <?php echo $base / 2; ?>px !important;
  margin-right: <?php echo $base / 2; ?>px !important;
}
#wrapper #content #mfilter-price-slider.ui-slider:after,
#wrapper #content .mfilter-slider-slider .ui-slider:after
{
  margin-left:  -<?php echo $base / 2; ?>px !important;
  margin-right: -<?php echo $base / 2; ?>px !important;
  border-top: 6px solid !important;
}
#wrapper #content #mfilter-price-slider .ui-slider-range,
#wrapper #content .mfilter-slider-slider .ui-slider-range
{
  background-color: #333 !important;
}
#wrapper #content #mfilter-price-slider span,
#wrapper #content #mfilter-price-slider .ui-slider-handle,
#wrapper #content .mfilter-slider-slider .ui-slider-handle
{
  top: 0;
  width:  <?php echo $base; ?>px !important;
  height: <?php echo $base; ?>px !important;
  margin-top: -<?php echo $base / 2 - 3; ?>px !important;
  margin-left: -<?php echo $base / 2; ?>px !important;
  background: <?php echo $main_color; ?> !important;
  border-radius: 0 !important;
}


.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-heading-content {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-heading-text {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
  font-weight: normal;
  font-size: 16px;
  font-family: Montserrat;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-head-icon,
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-head-icon:before
{
  min-width: 20px;
  content: '\274c';
  content: '\2716';
  content: '\2715';
  display: inline-block;
  height: auto !important;
  line-height: inherit;
  text-align: center;
  vertical-align: top;
  font-family: FontAwesome;
  font-style: normal;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-heading.mfilter-collapsed .mfilter-head-icon {
  -webkit-transform: rotate(-45deg);
      -ms-transform: rotate(-45deg);
          transform: rotate(-45deg);
}

.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-content > ul > li + li {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-content > ul > li .mfilter-opts-container {
  padding: <?php echo $base * 0.5; ?>px 0;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-content > ul > li .mfilter-button-more {
  padding-bottom: <?php echo $base * 0.5; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-content > ul > li:last-child .mfilter-opts-container:last-child {
  padding-bottom: 0;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-iscroll {
  overflow: visible !important;
  height: auto !important;
  max-height: none !important;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-iscroll .mfilter-options {
  -webkit-transform: none !important;
      -ms-transform: none !important;
          transform: none !important;
}

.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-option.mfilter-tb-as-tr {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  line-height: <?php echo $base * 1.5; ?>px;
  margin-left: -<?php echo $base * 0.5; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-option.mfilter-tb-as-tr > * {
  margin-left: <?php echo $base * 0.5; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-option.mfilter-tb-as-tr > label {
  display: block;
  max-width: none;
  -webkit-flex: 1 1 auto;
      -ms-flex: 1 1 auto;
          flex: 1 1 auto;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-col-input,
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-col-count
{
  -webkit-flex: 0 1 auto;
      -ms-flex: 0 1 auto;
          flex: 0 1 auto;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-input-active .mfilter-col-count {
  display: none;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-col-input input {
  margin-top: -1px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-counter {
  display: inline-block;
  width: auto !important;
  min-width: 20px;
  height: auto !important;
  padding: 0 0.2em;
  text-align: center;
  text-indent: 0 !important;
  vertical-align: top;
  font-size: 11px;
  color: #999;
  background-color: #eee !important;
  border-radius: 2px;
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-counter:before,
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-counter:after
{
  content: none !important;
}

.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-tb > ul {
  margin-top:  -<?php echo $base * 0.5; ?>px;
  margin-left: -<?php echo $base * 0.5; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-option.mfilter-image {
  margin-top:  <?php echo $base * 0.5; ?>px;
  margin-left: <?php echo $base * 0.5; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-option.mfilter-image label {
  padding: 2px;
  background: <?php echo $color_bg_str_3; ?> !important;
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-option.mfilter-image label:hover {
  background: <?php echo $color_bg_str_5; ?> !important;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-option.mfilter-image-checked label,
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-option.mfilter-image-checked label:hover
{
  background: <?php echo $main_color; ?> !important;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-image .mfilter-option.mfilter-image img {
  vertical-align: top;
  border: 1px solid #fff !important;
}

.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-button-top {
  padding-bottom: <?php echo $base; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-button-bottom {
  position: relative;
  margin-top: <?php echo $base; ?>px;
  padding-top: <?php echo $base + 1; ?>px;
}
.mfilter-box.mfilter-box.mfilter-box.mfilter-box .mfilter-button-bottom:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  display: block;
  border-top: 1px solid !important;
  opacity: 0.15;
}

.mfilter-reset-icon,
.mfilter-reset-icon:before
{
  display: inline-block;
  content: '\f021';
  width: auto !important;
  height: inherit !important;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.2em !important;
  <?php else: ?>
  margin-left: 0.2em !important;
  <?php endif; ?>
  line-height: inherit !important;
  vertical-align: top;
  font-family: FontAwesome;
  font-style: normal;
}