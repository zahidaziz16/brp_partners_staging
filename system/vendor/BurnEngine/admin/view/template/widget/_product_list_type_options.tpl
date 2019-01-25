<fieldset>
  <legend>Listing</legend>
  <div class="tb_wrap">
    <div class="s_row_2 tb_col tb_1_5">
      <label>Style</label>
      <div class="s_full clearfix">
        <div class="s_select">
          <select name="widget_data[view_mode]">
            <option value="grid"<?php if ($settings['view_mode'] == 'grid'): ?> selected="selected"<?php endif; ?>>Grid</option>
            <option value="list"<?php if ($settings['view_mode'] == 'list'): ?> selected="selected"<?php endif; ?>>List</option>
            <option value="compact"<?php if ($settings['view_mode'] == 'compact'): ?> selected="selected"<?php endif; ?>>Compact</option>
          </select>
        </div>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tbGridMaxRowsWrap">
      <label>Max rows</label>
      <input class="s_spinner" type="text" name="widget_data[grid_max_rows]" value="<?php echo $settings['grid_max_rows']; ?>" size="5" min="0" max="10" />
    </div>
  </div>
</fieldset>

<script>
$(document).ready(function() {
    $('[name="widget_data[view_mode]"]').bind('change', function() {
        $(this).closest('fieldset').find('.tbGridMaxRowsWrap').toggle($(this).val() == 'grid');
    });
});
</script>