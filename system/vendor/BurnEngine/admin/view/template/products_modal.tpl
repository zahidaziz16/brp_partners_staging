<div class="tb_wrap tb_gut_30 tbProductsModalContainer">

  <div class="tb_col tb_grid_3_4">
    <?php require(tb_modification(dirname(__FILE__) . '/products_modal_listing.tpl')); ?>
  </div>

  <div class="tb_col tb_grid_1_4">
    <div class="tb_data_filter s_box_1 tbFilterForm">
      <h3><?php echo $text_title_product_filter; ?></h3>
      <div class="s_row_2">
        <label>Category</label>
        <input type="hidden" name="filter_category_id" value="" />
        <select class="tb_nostyle tbComboBox"></select>
      </div>
      <div class="s_row_1">
        <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Name" />
        <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="Model" />
      </div>
      <div class="s_row_1 clearfix">
        <input type="text" name="filter_price_more" value="<?php echo $filter_price_more; ?>" placeholder="Min price" style="width: 80px;" />
        <input class="right" type="text" name="filter_price_less" value="<?php echo $filter_price_less; ?>" placeholder="Max price" style="width: 80px;" />
      </div>
      <div class="s_row_1 clearfix">
        <input type="text" name="filter_quantity_more" value="<?php echo $filter_quantity_more; ?>" placeholder="Min quantity" />
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
        <label class="s_checkbox">
          <input type="checkbox" name="filter_specials" value="1"<?php if($filter_specials == 1): ?> checked="checked"<?php endif; ?> />
          <span>Specials only</span>
        </label>
      </div>
      <div class="s_row_1 align_right">
        <a class="s_button s_h_30 s_white tb_grid_1_1 s_mb_10 tbSubmitFilter">Apply Filter</a>
        <a class="s_button s_h_30 s_white tb_grid_1_1 tbResetFilter">Reset</a>
      </div>
    </div>
  </div>

</div>
