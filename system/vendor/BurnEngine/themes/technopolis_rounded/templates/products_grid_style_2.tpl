<div>
  <input class="product-id_<?php echo $product['product_id']; ?>" type="hidden" value=""/>
  <div class="product-thumb">
    <?php echo $thumb; ?>
    <div>
      <div class="caption">
        <div class="row tb_gut_xs_10 tb_gut_sm_10">
          <div class="col col-xs-12 col-sm-fill col-valign-middle">
            <?php echo $title; ?>
          </div>
          <div class="col col-xs-12 col-sm-12 col-valign-middle">
            <?php echo $price; ?>
          </div>
        </div>
        <?php echo $description; ?>
        <?php echo $tax; ?>
        <?php echo $rating; ?>
        <?php echo $special_price_end; ?>
      </div>
      <?php if ($button_cart || $button_wishlist || $button_compare): ?>
      <div class="button-group">
        <?php echo $button_cart; ?>
        <?php echo $button_wishlist; ?>
        <?php echo $button_compare; ?>
        <?php echo $button_quickview; ?>
      </div>
      <?php endif; ?>
      <?php echo $stock_status; ?>
    </div>
    <?php echo $label_sale; ?>
    <?php echo $label_new; ?>
  </div>
</div>