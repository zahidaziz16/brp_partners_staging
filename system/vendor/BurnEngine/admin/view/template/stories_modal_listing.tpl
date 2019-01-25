<div class="tb_data_holder tbProductsListing">
  <input type="hidden" name="filter_request_url" value="<?php echo $filter_request_url; ?>" />
  <table class="s_table_1 tbProductsListingTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr>
      <th width="20">&nbsp;</th>
      <th class="align_left" colspan="2">
        <a class="tb_order<?php if ($sort == 'sd.title') echo ' tb_' . strtolower($order); ?>" href="<?php echo $url_sort_name; ?>">Story</a>
      </th>
      <th class="align_center" width="55">
        <a class="tb_order<?php if ($sort == 's.date_added') echo ' tb_' . strtolower($order); ?>" href="<?php echo $url_sort_quantity; ?>">Date</a>
      </th>
    </tr>
    </thead>
    <tbody>
    <?php if ($stories): ?>
    <?php foreach ($stories as $story): ?>
    <tr class="s_open<?php if ($story['added']) echo ' tb_selected'; ?>">
      <td>
        <input type="hidden" value="<?php echo $story['story_id']; ?>" />
        <div class="tbButtonProductSelect">
          <a class="s_button_add s_button s_white s_h_20 s_icon_10 s_plus_10" href="javascript:;"></a>
          <a class="s_button_added s_button s_green s_h_20 s_icon_10 tb_tick_white_10" href="javascript:;"></a>
          <a class="s_button_remove s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;"></a>
        </div>
      </td>
      <td width="40"><img src="<?php echo $story['image']; ?>" alt="<?php echo $story['title']; ?>" /></td>
      <td class="align_left"><strong><?php echo $story['title']; ?></strong><br /><span class="color_999"><?php echo $story['tags']; ?></span></td>
      <td><?php echo $story['date_added']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>
  <div class="pagination"><?php echo $pagination; ?></div>
</div>