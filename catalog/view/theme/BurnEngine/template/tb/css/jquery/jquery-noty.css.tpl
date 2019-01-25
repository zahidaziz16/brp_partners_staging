.noty_modal {
  position: fixed;
  z-index: 9999998;
  top: 0;
  right: 0;
  width: 100%;
  height: 100%;
  background: #fff;
  opacity: 0.9;
}
.noty_cont,
.noty_bar
{
  position: fixed;
  z-index: 9999999;
  width: <?php echo $base * 15; ?>px;
  margin: 0;
}
.noty_bar {
  display: none;
  width: 100%;
}
.noty_layout_topRight     { top: 0; right: 15px; }
.noty_layout_topLeft      { top: 0; left: 15px;  }
.noty_layout_bottomLeft   { bottom: 0; left: 15px;  }
.noty_layout_bottomRight  { bottom: 0; right: 15px; }
.noty_layout_topCenter    { top: 0; left: 50%; margin-left: -150px; }
.noty_layout_bottomCenter { bottom: 0; left: 50%; margin-left: -150px; }

.noty_cont .noty_bar {
  position: static;
  margin: 0;
}
.noty_bar .noty_close {
  cursor: pointer;
}


.noty_message {
  position: relative;
  margin: <?php echo $base * 0.75; ?>px 0;
  padding: <?php echo $base * 0.75; ?>px;
  border-radius: 2px;
  box-shadow:
    0 1px 0 0 rgba(0, 0, 0, 0.1),
    0 0 0 1px rgba(0, 0, 0, 0.08),
    0 1px 5px 0 rgba(0, 0, 0, 0.2);
}
.noty_text,
.noty_text_body
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
}
.noty_text:after {
  content: '';
  display: table;
  clear: both;
}
.noty_text .tb_icon {
  font-size: 19px;
  color: #fff;
}
.noty_text h3 {
      -ms-flex: 1 0 100%;
  -webkit-flex: 1 0 100%;
          flex: 1 0 100%;
  margin-bottom: <?php echo $base; ?>px;
}
.noty_text .thumbnail {
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
  height: <?php echo $base * 3; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base; ?>px;
  <?php endif; ?>
}
.noty_text .thumbnail img {
  width: auto;
  height: 100%;
}
.noty_text > p,
.noty_text_body
{
      -ms-flex: 1 1 auto;
  -webkit-flex: 1 1 auto;
          flex: 1 1 auto;
}
.noty_text_body > p {
  overflow: hidden;
      -ms-flex: 1 1 0px;
  -webkit-flex: 1 1 0px;
          flex: 1 1 0px;
}
.noty_text p a {
  font-weight: 600;
}
.noty_text .error {
  width: 100%;
  min-width: 0;
  margin: 0;
}
.noty_buttons {
  text-align: center;
  padding-top: <?php echo $base * 0.5; ?>px;
}
.noty_buttons button {
  margin-top: <?php echo $base * 0.5; ?>px;
}
.noty_buttons button + button {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-left: <?php echo $base * 0.25; ?>px;
  <?php else: ?>
  margin-right: <?php echo $base * 0.25; ?>px;
  <?php endif; ?>
}
.noty_message h2 {
  margin-left: 5px;
  margin-right: 5px;
  padding-top: 0;
  padding-bottom: 0;
  line-height: 20px;
  font-size: 18px;
  font-weight: normal;
}
.noty_message .noty_close {
  z-index: 100;
  position: absolute;
  top: <?php echo $base * 0.75; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  right: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  left: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
  letter-spacing: 0;
  word-spacing: 0;
}
.noty_message .noty_close:after {
  display: none;
}
.noty_message h3 {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-right: <?php echo $tbData->calculateLineHeight($base_font_size * 1.2, $base); ?>px;
  <?php else: ?>
  padding-left:  <?php echo $tbData->calculateLineHeight($base_font_size * 1.2, $base); ?>px;
  <?php endif; ?>
  font-size: <?php echo round($base_font_size * 1.2); ?>px;
}
.noty_message .tb_icon,
.noty_message .noty_close.noty_close
{
  width:       <?php echo $tbData->calculateLineHeight($base_font_size * 1.2, $base); ?>px;
  height:      <?php echo $tbData->calculateLineHeight($base_font_size * 1.2, $base); ?>px;
  line-height: <?php echo $tbData->calculateLineHeight($base_font_size * 1.2, $base); ?>px;
  text-align: center;
}
.noty_message .tb_icon {
  padding: 2px;
  font-size: inherit;
  border-radius: 50%;
  vertical-align: middle;
}
.noty_message .tb_icon:before {
  font-size: 0.8em;
}

/*** Mobile ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .noty_cont {
    position: static;
    width: auto;
    height: auto;
  }
  .noty_bar {
    position: fixed !important;
    top: 15px;
    left: 15px;
    right: 15px;
    bottom: 15px;
    width: auto;
  }
  .noty_message {
    margin: 0;
  }
  .noty_text {
              -ms-flex-pack: center;
    -webkit-justify-content: center;
            justify-content: center;
  }
  .noty_text h3 {
    text-align: center;
    padding-right: 0;
    padding-left:  0;
  }
  .noty_text p {
    max-width: 320px;
  }
}
