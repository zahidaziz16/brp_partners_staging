<?php
if (!isset($style_section_id)) $style_section_id = 'box';
if (!isset($has_presets)) $has_presets = true;
$input_property = 'widget_data[box_styles]';
$options = $settings['box_styles'];
?>

<h2>Advanced</h2>

<?php if ($has_presets): ?>
<fieldset>
  <legend>Style preset</legend>
  <?php require tb_modification(dirname(__FILE__) . '/_presets_select.tpl'); ?>
</fieldset>
<?php endif; ?>

<fieldset>
  <legend>Extra class</legend>
  <div class="s_row_2">
    <div class="s_full clearfix">
      <input type="text" name="widget_data[box_styles][layout][extra_class]" value="<?php echo $options['layout']['extra_class']; ?>" />
    </div>
    <p class="s_help clear s_mb_0">Separate multiple classes with space.</p>
  </div>
</fieldset>
