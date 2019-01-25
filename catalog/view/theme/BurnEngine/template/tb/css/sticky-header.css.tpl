.tbSticky {
  z-index: 101;
  position: fixed !important;
  top: 0;
  -webkit-transition: background 0.3s, padding 0.3s;
          transition: background 0.3s, padding 0.3s;
  -webkit-transition-delay: 0.2s;
          transition-delay: 0.2s;
}
.tbSticky > .row-wrap {
  padding-top: 0;
  padding-bottom: 0;
}
#header.tbSticky.container-fluid,
#header.tbStickyScrolled.tb_sticky_width_full,
#sticky_header.tb_sticky_width_full
{
  left: 0;
  right: 0;
  width: 100%;
  max-width: none;
}
#header.container.tbSticky:not(.tbStickyScrolled),
#header.tbStickyScrolled.tb_sticky_width_fixed,
#sticky_header.tb_sticky_width_fixed
{
  left: 50%;
  width: 100%;
  max-width: <?php echo $width; ?>px;
  margin-left: -<?php echo $width / 2; ?>px;
}
@media (max-width: <?php echo $width + 80; ?>px) {
  #header.container.tbSticky:not(.tbStickyScrolled),
  #header.tbStickyScrolled.tb_sticky_width_fixed,
  #sticky_header.tb_sticky_width_fixed
  {
    width: auto;
    left: 30px;
    right: calc(30px - 100vw + 100%);
    margin: 0;
  }
}

/*  -----------------------------------------------------------------------------------------
    M I N I M A L
-----------------------------------------------------------------------------------------  */

#header:not(.tbSticky) > .tbStickyRow,
#header:not(.tbSticky) .tbStickyOnly,
.tbSticky > .tbStickyRow .tbStickyHide
{
  display: none !important;
}
.tbSticky > .tbStickyRow > .col {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  -ms-flex-wrap: nowrap;
  -webkit-flex-wrap: nowrap;
  flex-wrap: nowrap;
}
.tbSticky > .tbStickyRow .tbStickyShow {
  margin-top: 0;
  margin-bottom: 0;
}
.tbSticky > .tbStickyRow .tbStickyShow + .tbStickyShow {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 1.5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 1.5; ?>px;
  <?php endif; ?>
}
.tbSticky .tb_wt_header_search_system.tbStickyShow + .tb_wt_header_cart_menu_system.tbStickyShow {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
#header:not(.tbSticky) div:not(.tbStickyRow) .tbStickyOnly {
  display: none !important;
}

/*  Cart   ------------------------------------------------------------------------------  */

.tbSticky > .tbStickyRow #cart .btn + .heading > a {
  padding-left: 0;
  padding-right: 0;
}
.tbSticky > .tbStickyRow #cart .btn + .heading > a > .tb_items {
  position: absolute;
  top: -5px;
  <?php if ($lang_dir == 'ltr'): ?>
  left: auto;
  right: -7px;
  <?php else: ?>
  right: auto;
  left: -7px;
  <?php endif; ?>
  width: 16px;
  height: 16px;
  line-height: 16px !important;
  text-align: center;
  opacity: 1;
  font-family: Arial;
  font-weight: normal;
  font-size: 10px;
  color: #fff !important;
  background-color: #333;
  border-radius: 50%;
}
.tbSticky > .tbStickyRow #cart .btn + .heading > a > .tb_icon,
.tbSticky > .tbStickyRow #cart .btn + .heading > a > .tb_label,
.tbSticky > .tbStickyRow #cart .btn + .heading > a > .tb_total,
.tbSticky > .tbStickyRow #cart .btn + .heading > a > .tb_items:before,
.tbSticky > .tbStickyRow #cart .btn + .heading > a > .tb_items:after
{
  display: none;
}

/*  Nav   -------------------------------------------------------------------------------  */

.tbSticky > .tbStickyRow nav > .nav.nav-justified > li {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
.tbSticky > .tbStickyRow nav > .nav > li:before {
  content: none !important;
}

/*  -----------------------------------------------------------------------------------------
      D E F A U L T
-----------------------------------------------------------------------------------------  */

@keyframes sticky_hide {
  0%   {
    max-height: 200px;
  }
  100% {
    max-height: 0px;
  }
}
@keyframes sticky_show {
  0% {
    max-height: 0px;
  }
  100%   {
    max-height: 200px;
  }
}
#header.tbStickyScrolled .tbStickyHide {
  animation: sticky_hide 0.3s 0.2s both;
}
#header.tbStickyRestored .tbStickyHide {
  animation: sticky_show 0.3s 0.2s both;
}
#header.tbElementsHidden .tbStickyHide {
  overflow: hidden;
}
#header.tbSticky #cart > .nav > .dropdown > .btn {
  display: none !important;
}

/*  -----------------------------------------------------------------------------------------
      L A Y O U T
-----------------------------------------------------------------------------------------  */

#sticky_header.tb_content_fixed > .row {
  margin-right: auto;
  margin-left:  auto;
}

/*** Fill elements ***/

#sticky_header .tb_wt {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
}
#sticky_header .tbStickyFill {
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}

/*** Order elements ***/

#sticky_header .tbStickyPosition-1 {
  -ms-flex-order: 1;
  -webkit-order: 1;
  order: 1;
}
#sticky_header .tbStickyPosition-2 {
  -ms-flex-order: 2;
  -webkit-order: 2;
  order: 2;
}
#sticky_header .tbStickyPosition-3 {
  -ms-flex-order: 4;
  -webkit-order: 3;
  order: 3;
}
#sticky_header .tbStickyPosition-4 {
  -ms-flex-order: 4;
  -webkit-order: 4;
  order: 4;
}
#sticky_header .tbStickyPosition-5 {
  -ms-flex-order: 5;
  -webkit-order: 5;
  order: 5;
}
#sticky_header .tbStickyPosition-6 {
  -ms-flex-order: 6;
  -webkit-order: 6;
  order: 6;
}
#sticky_header .tbStickyPosition-7 {
  -ms-flex-order: 7;
  -webkit-order: 7;
  order: 7;
}
#sticky_header .tbStickyPosition-8 {
  -ms-flex-order: 8;
  -webkit-order: 8;
  order: 8;
}

/*  -----------------------------------------------------------------------------------------
      M O B I L E
-----------------------------------------------------------------------------------------  */

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  #sticky_header {
    display: none;
  }
}