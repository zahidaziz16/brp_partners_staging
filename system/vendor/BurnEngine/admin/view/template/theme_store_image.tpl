<h2>Image sizes</h2>

<fieldset id="store_product_image_settings">
  <div class="s_row_1">
    <label>Product thumb</label>
    <input class="inline" type="text" name="product[image][thumb_width]" value="<?php echo $product['image']['thumb_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][thumb_height]" value="<?php echo $product['image']['thumb_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <div class="s_row_1">
    <label>Product popup</label>
    <input class="inline" type="text" name="product[image][popup_width]" value="<?php echo $product['image']['popup_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][popup_height]" value="<?php echo $product['image']['popup_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <div class="s_row_1">
    <label>Product additional</label>
    <input class="inline" type="text" name="product[image][additional_width]" value="<?php echo $product['image']['additional_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][additional_height]" value="<?php echo $product['image']['additional_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <div class="s_row_1">
    <label>Compare</label>
    <input class="inline" type="text" name="product[image][compare_width]" value="<?php echo $product['image']['compare_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][compare_height]" value="<?php echo $product['image']['compare_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <div class="s_row_1">
    <label>Wishlist</label>
    <input class="inline" type="text" name="product[image][wishlist_width]" value="<?php echo $product['image']['wishlist_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][wishlist_height]" value="<?php echo $product['image']['wishlist_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <div class="s_row_1">
    <label>Cart</label>
    <input class="inline" type="text" name="product[image][cart_width]" value="<?php echo $product['image']['cart_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][cart_height]" value="<?php echo $product['image']['cart_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <div class="s_row_1">
    <label>Store</label>
    <input class="inline" type="text" name="product[image][location_width]" value="<?php echo $product['image']['location_width']; ?>" size="5" />
    <span class="s_input_separator">&nbsp;X&nbsp;</span>
    <input class="inline" type="text" name="product[image][location_height]" value="<?php echo $product['image']['location_height']; ?>" size="5" />
    <span class="s_metric">px</span>
  </div>

  <input type="hidden" name="product[image][related_width]" value="<?php echo $product['image']['related_width']; ?>" />
  <input type="hidden" name="product[image][related_height]" value="<?php echo $product['image']['related_height']; ?>" />
</fieldset>