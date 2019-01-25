<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<?php if ($product_settings_context = $tbData->category_products_current) extract($product_settings_context); ?>
<?php $tbData->slotStart('module/featured.products', array('filter' => array('module/featured.products.filter|oc_module_products.filter', 'products' => &$products), 'data' => $data)); ?>
<?php if ($products): ?>
<?php if ($heading_title): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $heading_title; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body">
  <div class="tb_products tb_listing <?php echo $listing_classes; ?>">
    <?php foreach ($products as $product) { ?>
    <div class="product-layout">
      <input class="product-id_<?php echo $product['product_id']; ?>" type="hidden" value="" />
      <div class="product-thumb">
        <?php if ($product['thumb'] && $show_thumb) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><span style="max-width: <?php echo $product['thumb_width']; ?>px;"><span style="padding-top: <?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%"><img src="<?php echo $product['thumb']; ?>"<?php if ($tbData->system['image_lazyload']): ?> data-src="<?php echo $product['thumb_original']; ?>" class="lazyload"<?php endif; ?> width="<?php echo $product['thumb_width']; ?>" height="<?php echo $product['thumb_height']; ?>" alt="<?php echo $product['name']; ?>" style="margin-top: -<?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%" /></span></span></a></div>
        <?php if ($product['thumb_hover']): ?>
        <div class="image_hover"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif" data-src="<?php echo $product['thumb_hover']; ?>" width="<?php echo $product['thumb_width']; ?>" height="<?php echo $product['thumb_height']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php endif; ?>
        <?php } ?>
        <div>
          <div class="caption">
            <?php if ($show_title): ?>
            <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            <?php endif; ?>
            <?php if ($product['description']): ?>
            <div class="description"><?php echo $product['description']; ?></div>
            <?php endif; ?>
            <?php if ($product['price']) { ?>
            <p class="price">
              <?php if (!$product['special']) { ?>
              <span class="price-regular"><?php echo $product['price']; ?></span>
              <?php } else { ?>
              <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
              <?php } ?>
            </p>
            <?php if ($product['tax']) { ?>
            <span class="price-tax"><span><?php echo $text_tax; ?></span> <?php echo $product['tax']; ?></span>
            <?php } ?>
            <?php } ?>
            <?php if ($product['rating']) { ?>
            <div class="rating">
              <div class="tb_bar">
                <span class="tb_percent" style="width: <?php echo $product['rating'] * 20; ?>%;"></span>
                <span class="tb_base"></span>
              </div>
              <span class="tb_average"><?php echo $product['rating']; ?>/5</span>
            </div>
            <?php } ?>
          </div>
          <?php if ($product['show_cart'] || $show_wishlist || $show_compare): ?>
          <div class="button-group">
            <?php if ($product['show_cart']): ?>
            <div class="tb_button_add_to_cart<?php echo $cart_button_position_classes; ?>"<?php echo $cart_button_offset_attr; ?>>
              <?php if ($tbData->OcVersionGte('2.0.2.0')): ?>
              <a class="<?php echo $cart_button_classes; ?>" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');">
              <?php else: ?>
              <a class="<?php echo $cart_button_classes; ?>" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>');">
              <?php endif; ?>
                <span data-tooltip="<?php echo $product['text_button_cart']; ?>"><?php echo $product['text_button_cart']; ?></span>
              </a>
            </div>
            <?php endif; ?>
            <?php if ($show_wishlist): ?>
            <div class="tb_button_wishlist<?php echo $wishlist_button_position_classes; ?>"<?php echo $wishlist_button_offset_attr; ?>>
              <a class="<?php echo $wishlist_button_classes; ?>" href="javascript:;" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
                <span data-tooltip="<?php echo $tbData->text_wishlist; ?>"><?php echo $tbData->text_wishlist; ?></span>
              </a>
            </div>
            <?php endif; ?>
            <?php if ($show_compare): ?>
            <div class="tb_button_compare<?php echo $compare_button_position_classes; ?>"<?php echo $compare_button_offset_attr; ?>>
              <a class="<?php echo $compare_button_classes; ?>" href="javascript:;" onclick="compare.add('<?php echo $product['product_id']; ?>');">
                <span data-tooltip="<?php echo $tbData->text_compare; ?>"><?php echo $tbData->text_compare; ?></span>
              </a>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <?php if ($product['show_stock']): ?>
          <p class="tb_label_stock_status"><?php echo $product['stock_status']; ?></p>
          <?php endif; ?>
        </div>
        <?php if ($product['show_label_sale']): ?>
        <p class="tb_label_special"><?php echo $product['savings_text']; ?></p>
        <?php endif; ?>
        <?php if ($show_label_new && $product['is_new']): ?>
        <p class="tb_label_new"><?php echo $tbData->text_label_new; ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
tbApp.init{{widget_dom_id}} = function() {
    tbApp.onScriptLoaded(function() {

        if (!tbUtils.is_touch) {

            <?php // THUMB HOVER ?>
            <?php if ($thumbs_hover_action != 'none'): ?>
            thumb_hover('#{{widget_dom_id}}', '<?php echo $thumbs_hover_action; ?>')
            <?php endif; ?>

            <?php // THUMB ZOOM ?>
            <?php if ($thumbs_hover_action == 'zoom'): ?>
            $('#{{widget_dom_id}}').find('.tb_zoom > img').elevateZoom({
              zoomType:           'inner',
              zoomWindowFadeIn:   300,
              zoomWindowFadeOut:  300,
              cursor:             'crosshair'
            });
            <?php endif; ?>

            <?php // PRODUCT HOVER ?>
            <?php if ($elements_hover_action != 'none'): ?>
            item_hover('#{{widget_dom_id}}', '<?php echo $active_elements; ?>', '<?php echo $hover_elements; ?>', '<?php echo $elements_hover_action; ?>');
            <?php endif; ?>

        }

    });

}
tbApp.exec{{widget_dom_id}} = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ADJUST PRODUCT SIZE ?>
        <?php if (isset($view_mode) && $view_mode == 'grid'): ?>
        adjustItemSize('#{{widget_dom_id}}', <?php echo $restrictions_json; ?>);
        <?php endif; ?>
    });
}

if (!{{within_group}}) {
    tbApp.init{{widget_dom_id}}();
    tbApp.exec{{widget_dom_id}}();
}

</script>

<?php if (isset($view_mode) && $view_mode == 'grid'): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#{{widget_dom_id}}', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>
<?php endif; ?>
<?php $tbData->slotStopEcho(); ?>