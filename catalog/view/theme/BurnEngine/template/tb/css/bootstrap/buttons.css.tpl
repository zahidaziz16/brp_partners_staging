.btn,
.button,
button,
[type=button],
[type=submit]
{
  position: relative;
  display: inline-block;
  height:        <?php echo $form_control_height; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $form_control_height) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height - 2, $base) - $form_control_height) / 2; ?>px;
  padding-left:  <?php echo ceil($form_control_height / 2); ?>px;
  padding-right: <?php echo ceil($form_control_height / 2); ?>px;
  line-height:   <?php echo $form_control_height; ?>px;
  text-align: center;
  vertical-align: middle;
  white-space: nowrap;
  cursor: pointer;
  -webkit-transition: color 0.2s, background 0.2s, box-shadow 0.2s, transform 0.2s, opacity 0.2s;
          transition: color 0.2s, background 0.2s, box-shadow 0.2s, transform 0.2s, opacity 0.2s;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
      user-select: none;
  -ms-touch-action: manipulation;
      touch-action: manipulation;
}
.btn a,
.button a
{
  color: inherit !important;
}

/*** Button Sizes ***/

.btn.btn-xs,
.btn-group-xs > .btn,
.input-group-xs > .input-group-btn > .btn
{
  height:        <?php echo $form_control_height_xs; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_xs - 2, $base) - $form_control_height_xs) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_xs - 2, $base) - $form_control_height_xs) / 2; ?>px;
  padding-left:  <?php echo ceil($form_control_height_xs / 2); ?>px;
  padding-right: <?php echo ceil($form_control_height_xs / 2); ?>px;
  line-height:   <?php echo $form_control_height_xs; ?>px;
  font-size:     <?php echo $base_button_size - ceil($base_button_size * 0.2); ?>px;
}
.btn.btn-sm,
.btn-group-sm > .btn,
.input-group-sm > .input-group-btn > .btn
{
  height:        <?php echo $form_control_height_sm; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $form_control_height_sm) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_sm - 2, $base) - $form_control_height_sm) / 2; ?>px;
  padding-left:  <?php echo ceil($form_control_height_sm / 2); ?>px;
  padding-right: <?php echo ceil($form_control_height_sm / 2); ?>px;
  line-height:   <?php echo $form_control_height_sm; ?>px;
  font-size:     <?php echo $base_button_size - floor($base_button_size * 0.15); ?>px;
}
.btn.btn-lg,
.btn-group-lg > .btn,
.input-group-lg > .input-group-btn > .btn
{
  height:        <?php echo $form_control_height_lg; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $form_control_height_lg) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_lg - 2, $base) - $form_control_height_lg) / 2; ?>px;
  padding-left:  <?php echo ceil($form_control_height_lg / 2); ?>px;
  padding-right: <?php echo ceil($form_control_height_lg / 2); ?>px;
  line-height:   <?php echo $form_control_height_lg; ?>px;
  font-size:     <?php echo $base_button_size + ceil($base_button_size * 0.15); ?>px;
}
.btn.btn-xl,
.btn-group-xl > .btn,
.input-group-xl > .input-group-btn > .btn
{
  height:        <?php echo $form_control_height_xl; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $form_control_height_xl) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_xl - 2, $base) - $form_control_height_xl) / 2; ?>px;
  padding-left:  <?php echo ceil($form_control_height_xl / 2); ?>px;
  padding-right: <?php echo ceil($form_control_height_xl / 2); ?>px;
  line-height:   <?php echo $form_control_height_xl; ?>px;
  font-size:     <?php echo $base_button_size + ceil($base_button_size * 0.2); ?>px;
}
.btn.btn-xxl,
.btn-group-xxl > .btn,
.input-group-xxl > .input-group-btn > .btn
{
  height:        <?php echo $form_control_height_xxl; ?>px;
  margin-top:    <?php echo ($tbData->calculateLineHeight($form_control_height_xxl - 2, $base) - $form_control_height_xxl) / 2; ?>px;
  margin-bottom: <?php echo ($tbData->calculateLineHeight($form_control_height_xxl - 2, $base) - $form_control_height_xxl) / 2; ?>px;
  padding-left:  <?php echo ceil($form_control_height_xxl / 2); ?>px;
  padding-right: <?php echo ceil($form_control_height_xxl / 2); ?>px;
  line-height:   <?php echo $form_control_height_xxl; ?>px;
  font-size:     <?php echo $base_button_size + ceil($base_button_size * 0.3); ?>px;
}

/*** Block buttons ***/

.btn-block {
  width: 100%;
}
.btn-block + .btn-block,
.btn-block + .btn-block.btn-xs
{
  margin-top: <?php echo ($tbData->calculateLineHeight($form_control_height     - 2, $base) - $form_control_height    ) / 2 + $base; ?>px;
}
.btn-block + .btn-block.btn-sm {
  margin-top: <?php echo ($tbData->calculateLineHeight($form_control_height_sm  - 2, $base) - $form_control_height_sm ) / 2 + $base; ?>px;
}
.btn-block + .btn-block.btn-lg {
  margin-top: <?php echo ($tbData->calculateLineHeight($form_control_height_lg  - 2, $base) - $form_control_height_lg ) / 2 + $base; ?>px;
}
.btn-block + .btn-block.btn-xl {
  margin-top: <?php echo ($tbData->calculateLineHeight($form_control_height_xl  - 2, $base) - $form_control_height_xl ) / 2 + $base; ?>px;
}
.btn-block + .btn-block.btn-xxl {
  margin-top: <?php echo ($tbData->calculateLineHeight($form_control_height_xxl - 2, $base) - $form_control_height_xxl) / 2 + $base; ?>px;
}

/*** Icon / Empty / Square Buttons ***/

.btn.tb_no_text {
  display: inline-block !important;
  width: <?php echo $base * 1.5; ?>px;
  padding: 0 !important;
}
.btn.tb_no_text:before {
  width: 100% !important;
  margin-left: 0 !important;
  margin-right: 0 !important;
}
.btn:not(input):empty, .btn.tb_no_text                 { width: <?php echo $form_control_height; ?>px; padding: 0; }
.btn:not(input).btn-xs:empty,  .btn.tb_no_text.btn-xs  { width: <?php echo $form_control_height_xs;  ?>px; }
.btn:not(input).btn-sm:empty,  .btn.tb_no_text.btn-sm  { width: <?php echo $form_control_height_sm;  ?>px; }
.btn:not(input).btn-lg:empty,  .btn.tb_no_text.btn-lg  { width: <?php echo $form_control_height_lg;  ?>px; }
.btn:not(input).btn-xl:empty,  .btn.tb_no_text.btn-xl  { width: <?php echo $form_control_height_xl;  ?>px; }
.btn:not(input).btn-xxl:empty, .btn.tb_no_text.btn-xxl { width: <?php echo $form_control_height_xxl; ?>px; }

.tb_no_text > span[data-tooltip] {
  overflow: hidden;
  z-index: 5;
  position: absolute;
  left: 0;
  right: 0;
  bottom: 100%;
  height: 0;
  margin: 0 -80px 0 -80px;
  line-height: <?php echo $base + 10; ?>px;
  letter-spacing: 0;
  text-align: center;
  text-indent: 0;
  font-size: 0;
  opacity: 0;
  -webkit-transition: opacity 0.3s ease-in-out, margin 0.3s ease-in-out;
          transition: opacity 0.3s ease-in-out, margin 0.3s ease-in-out;
}
.tb_no_text:hover > span[data-tooltip] {
  overflow: visible;
  display: block;
  height: auto;
  margin-bottom: 10px;
  opacity: 1;
}
.tb_no_text > span[data-tooltip]:before {
  content: attr(data-tooltip);
  display: inline-block;
  margin-bottom: 2px;
  padding: 0.1em 0.6em;
  line-height: 2em;
  text-transform: none;
  font-size: <?php echo min($base_font_size, 14); ?>px;
  white-space: nowrap;
  vertical-align: bottom;
  border-radius: 2px;
  box-shadow:
    0 1px 0 0 rgba(0, 0, 0, 0.1),
    0 0 0 1px rgba(0, 0, 0, 0.08),
    0 1px 5px 0 rgba(0, 0, 0, 0.2);
}
.tb_no_text > span[data-tooltip]:after {
  content: '';
  z-index: 60;
  position: absolute;
  top: 100%;
  left: 50%;
  right: auto;
  display: block;
  width: 9px;
  height: 9px;
  margin-top: -6px;
  margin-left: -5px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.15);
  border-right: 1px solid rgba(0, 0, 0, 0.15);
  background-clip: content-box;
  -webkit-transform: rotate(45deg);
          transform: rotate(45deg);
}

/*** BurnEngine ***/

.btn,
.button,
button,
[type=button],
[type=submit]
{
  border: none;
  border-radius: 3px;
  cursor: pointer !important;
  box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.15);
}
.btn.btn-plain {
  border-radius: 0;
  box-shadow: none;
}
