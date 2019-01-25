<div class="tb_wrap tb_gut_30 tbProductsModalContainer">

  <div class="tb_col tb_grid_3_4">
    <?php require(tb_modification(dirname(__FILE__) . '/stories_modal_listing.tpl')); ?>
  </div>

  <div class="tb_col tb_grid_1_4">
    <div class="tb_data_filter s_box_1 tbFilterForm">
      <h3>Stories filter</h3>
      <!--
      <div class="s_row_1">
        <input type="text" name="filter_tags" value="<?php echo $filter_tags; ?>" placeholder="Tags" />
      </div>
      -->
      <div class="s_row_1">
        <input type="text" name="filter_text" value="<?php echo $filter_text; ?>" placeholder="Text" />
      </div>
      <div class="s_row_1 clearfix">
        <label class="s_checkbox">
          <input type="checkbox" name="filter_disabled" value="1"<?php if($filter_disabled == 1): ?> checked="checked"<?php endif; ?> />
          <span>Show disabled</span>
        </label>
        <label class="s_checkbox">
          <input type="checkbox" name="filter_selected" value="1"<?php if($filter_selected == 1): ?> checked="checked"<?php endif; ?> />
          <span>Selected only</span>
        </label>
      </div>
      <div class="s_row_1 align_right">
        <a class="s_button s_h_30 s_white tb_grid_1_1 s_mb_10 tbSubmitFilter">Apply Filter</a>
        <a class="s_button s_h_30 s_white tb_grid_1_1 tbResetFilter">Reset</a>
      </div>
    </div>
  </div>

</div>
