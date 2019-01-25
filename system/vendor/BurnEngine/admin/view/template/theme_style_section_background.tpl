<fieldset class="tb_background_color">
  <div class="s_actions">
    <label class="tb_toggle tb_toggle_small"><input type="checkbox" class="tbBackgroundInherit"<?php if (!empty($background['solid_color_inherit_key'])): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
    <label class="inline">Inherit</label>
  </div>
  <legend>Solid Color</legend>
  <div class="tb_wrap tb_gut_10">
    <div class="s_row_1 tbColorItem tb_col<?php if (!empty($background['solid_color_inherit_key'])): ?> tb_disabled tb_inherit" parent_id="color_item_<?php echo str_replace(array(':', '.'), '_', $background['solid_color_inherit_key']); ?><?php endif; ?>">
      <span class="dropdown tbInheritMenuButton"></span>
      <div class="colorSelector">
        <div style="background-color: <?php echo $background['solid_color']; ?>"></div>
      </div>
      <input type="text" name="<?php echo $input_property; ?>[background][solid_color]" value="<?php echo $background['solid_color']; ?>" />
      <?php if (!empty($background['solid_color_inherit_key'])): ?>
      <input type="hidden" name="<?php echo $input_property; ?>[background][solid_color_inherit_key]" value="<?php echo $background['solid_color_inherit_key']; ?>" />
      <?php endif; ?>
    </div>
    <div class="s_row_1 tb_col tb_1_4">
      <input class="s_spinner" type="text" name="<?php echo $input_property; ?>[background][solid_color_opacity]" min="0" max="100" value="<?php echo $background['solid_color_opacity']; ?>" size="6" />
      <span class="s_metric">%</span>
    </div>
  </div>
</fieldset>

<h3 class="tb_background_listing_title">Backgrounds</h3>

<fieldset class="tb_background_listing tb_style_2 s_p_0 tbGradientListing"<?php if (!count($background['rows'])): ?> style="display: none;" <?php endif; ?>>
  <div class="tb_tabs tb_vtabs clearfix">
    <div class="tb_tabs_nav">
      <ul class="clearfix">
        <?php $bg_row_num = 0; ?>
        <?php foreach ($background['rows'] as $bg_row_key => $bg_row): ?>
        <li><a href="#<?php echo $section; ?>_row_<?php echo $bg_row_key; ?>">#<?php echo $bg_row_num+1; ?></a></li>
        <?php $bg_row_num++; ?>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php $bg_row_num = 0; ?>
    <?php foreach ($background['rows'] as $bg_row_key => $bg_row): ?>
    <div id="<?php echo $section; ?>_row_<?php echo $bg_row_key; ?>" class="tb_<?php echo $bg_row['background_type']; ?>_listing tb_list_view tbTabContent" background_type="<?php echo $bg_row['background_type']; ?>">
      <?php if ($bg_row['background_type'] == 'gradient'): ?>
      <?php require(tb_modification(dirname(__FILE__) . '/theme_style_section_bg_gradient.tpl')); ?>
      <?php else: ?>
      <?php require(tb_modification(dirname(__FILE__) . '/theme_style_section_bg_image.tpl')); ?>
      <?php endif; ?>
    </div>
    <?php $bg_row_num++; ?>
    <?php endforeach; ?>
  </div>

</fieldset>

<span class="clear border_ddd s_mb_30"></span>

<a href="javascript:;" class="s_button s_h_30 s_icon_10 s_plus_10 s_white left s_mr_20 tbAddGradient">Add Gradient</a>
<a href="javascript:;" class="s_button s_h_30 s_icon_10 s_plus_10 s_white left s_mr_20 tbAddImage">Add Image</a>


