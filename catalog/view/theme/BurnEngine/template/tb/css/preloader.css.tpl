.tb_preload,
.tb_loading
{
  position: relative;
}
.tb_preload > * {
  transition: opacity 0.5s;
  transition-delay: 0.3s;
}
.tb_loading {
  overflow: hidden;
}
.tb_loading:before {
  content: '';
  z-index: 10;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: block;
  opacity: 0;
}
.tb_loading * {
  pointer-events: none;
}
.tb_loading > * {
  opacity: 0;
}
.tb_loading > span.wait,
.tb_loading > i.fa.fa-circle-o-notch.fa-spin,
.tb_loading > .tb_loading_bar
{
  opacity: 1;
}
.tb_preload:not(.tb_loading) > * {
  opacity: 1;
}
.tb_preload:not(.tb_loading) > .tb_loading_bar {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
}
span.wait,
i.fa.fa-circle-o-notch.fa-spin,
.tb_loading_bar
{
  position: relative;
  display: inline-block;
  transition-delay: 0s !important;
}
span.wait,
i.fa.fa-circle-o-notch.fa-spin
{
  width: 18px;
  height: 18px;
  line-height: 16px;
}
.tb_loading_bar {
  width: 30px;
  height: 30px;
}
span.wait:before,
span.wait:after,
i.fa.fa-circle-o-notch.fa-spin:before,
i.fa.fa-circle-o-notch.fa-spin:after,
.tb_loading_bar:before,
.tb_loading_bar:after
{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: block;
  margin: 0 !important;
  border-radius: 50%;
}
span.wait:after,
i.fa.fa-circle-o-notch.fa-spin:after,
.tb_loading_bar:after
{
  border: 2px solid;
  opacity: 0.15;
}
span.wait:before,
i.fa.fa-circle-o-notch.fa-spin:before,
.tb_loading_bar:before
{
  border-top: 2px solid;
  border-right: 2px solid;
  border-bottom: 2px solid transparent;
  border-left: 2px solid  transparent;
  border-collapse: collapse;
  opacity: 0.7;
  -webkit-animation-name: tb_rotate;
  -webkit-animation-duration: 0.5s;
  -webkit-animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
  animation-name: tb_rotate;
  animation-duration: 0.5s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
span.wait:before,
i.fa.fa-circle-o-notch.fa-spin:before
{
  -webkit-animation-duration: 0.7s;
          animation-duration: 0.7s;
}
.tb_loading > span.wait,
.tb_loading > i.fa.fa-circle-o-notch.fa-spin,
.tb_loading > .tb_loading_bar
{
  z-index: 100;
  position: absolute;
  left: 50%;
}
.tb_loading > .tb_loading_bar {
  top: 100px;
  margin-left: -15px;
}
.tb_loading > span.wait,
.tb_loading > i.fa.fa-circle-o-notch.fa-spin
{
  top: 50%;
  margin: -8px 0 0 -8px;
}
.tb_loading:not(.tb_preload) > *:not(.fa):not(.wait),
.tb_loading:not(.tb_preload) > *:not(.fa):not(.wait)
{
  overflow: hidden;
  visibility: hidden;
  /*
  height: 0;
  margin: 0;
  padding: 0;
  */
}
.tb_loading:not(.tb_preload) .btn
{
  opacity: 0 !important;
  -webkit-transition: none !important;
  transition: none !important;
}

/*  Position   --------------------------------------------------------------------------  */

span.wait,
i.fa.fa-circle-o-notch.fa-spin
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base / 2; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base / 2; ?>px;
  <?php endif; ?>
  vertical-align: middle;
}
select + span.wait,
select + i.fa.fa-circle-o-notch.fa-spin
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
  margin-top: -0.2em;
}
.button + span.wait,
.btn + span.wait,
.btn + i.fa.fa-circle-o-notch.fa-spin
{
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.btn span.wait,
.btn i.fa.fa-circle-o-notch.fa-spin
{
  margin: -0.1em 0 0 0;
}