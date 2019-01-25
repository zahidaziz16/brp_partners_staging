<div class="tb_data_holder tbProductsListing">
  <input type="hidden" name="filter_request_url" value="<?php echo $filter_request_url; ?>" />
  <table class="s_table_1 tbProductsListingTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr>
      <th width="20">&nbsp;</th>
      <th class="align_left" colspan="2">
        <?php if ($sort == 'pd.name'): ?>
        <a class="tb_order tb_<?php echo strtolower($order); ?>" href="<?php echo $url_sort_name; ?>">Product</a>
        <?php else: ?>
        <a class="tb_order" href="<?php echo $url_sort_name; ?>">Product</a>
        <?php endif; ?>
      </th>
      <th class="align_center" width="55">
        <?php if ($sort == 'p.quantity'): ?>
        <a class="tb_order tb_<?php echo strtolower($order); ?>" href="<?php echo $url_sort_quantity; ?>">Qty.</a>
        <?php else: ?>
        <a class="tb_order" href="<?php echo $url_sort_quantity; ?>">Qty.</a>
        <?php endif; ?>
      </th>
      <th class="align_center" width="55">
        <?php if ($sort == 'p.price'): ?>
        <a class="tb_order tb_<?php echo strtolower($order); ?>" href="<?php echo $url_sort_price; ?>">Price</a>
        <?php else: ?>
        <a class="tb_order" href="<?php echo $url_sort_price; ?>">Price</a>
        <?php endif; ?>
      </th>
    </tr>
    </thead>
    <tbody>
    <?php if ($products): ?>
    <?php foreach ($products as $product): ?>
    <tr class="s_open<?php if ($product['added']) echo ' tb_selected'; ?>">
      <td>
        <input type="hidden" value="<?php echo $product['product_id']; ?>" />
        <div class="tbButtonProductSelect">
          <a class="s_button_add s_button s_white s_h_20 s_icon_10 s_plus_10" href="javascript:;"></a>
          <a class="s_button_added s_button s_green s_h_20 s_icon_10 tb_tick_white_10" href="javascript:;"></a>
          <a class="s_button_remove s_button s_white s_h_20 s_icon_10 s_delete_10" href="javascript:;"></a>
        </div>
      </td>
      <td width="40"><img width="40" height="40" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></td>
      <td class="align_left"><strong><?php echo $product['name']; ?></strong><br /><span class="color_999"><?php echo $product['model']; ?></span></td>
      <td><?php echo $product['quantity']; ?></td>
      <td>
        <?php if ($product['special']): ?>
        <span style="text-decoration:line-through"><?php echo $product['price']; ?></span><br/><span style="color:#b00;"><?php echo $product['special']; ?></span>
        <?php else: ?>
        <?php echo $product['price']; ?>
        <?php endif; ?>
      </td>
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