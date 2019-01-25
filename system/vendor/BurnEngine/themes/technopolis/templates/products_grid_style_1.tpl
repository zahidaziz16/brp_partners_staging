<div>
  <input class="product-id_<?php echo $product['product_id']; ?>" type="hidden" value=""/>
  <div class="product-thumb tb_style_1">
    <?php echo $thumb; ?>
    <div>
      <div class="caption">
        <?php echo $title; ?>
        <?php echo $special_price_end; ?>
        <?php echo $description; ?>
        <?php echo $rating; ?>
      </div>
      <?php if ($button_cart || $button_wishlist || $button_compare || $price || $tax): ?>
      <span class="clear border tb_mt_20 tb_mb_20"></span>
      <div class="row tb_gut_xs_10 tb_gut_sm_10">
        <div class="col col-xs-12 col-sm-fill col-valign-middle">
          <?php echo $button_cart; ?>
          <?php echo $stock_status; ?>
        </div>
        <div class="col col-xs-12 col-sm-12 col-valign-middle">
          <?php echo $price; ?>
        </div>
      </div>
      <div class="button-group">
        <?php echo $button_wishlist; ?>
        <?php echo $button_compare; ?>
        <?php echo $button_quickview; ?>
      </div>
      <?php echo $tax; ?>
      <?php endif; ?>
    </div>
    <?php echo $label_sale; ?>
    <?php echo $label_new; ?>
  </div>
</div>