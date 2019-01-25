<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('product/manufacturer/info.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('product/manufacturer/info.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Product listing ---------------------------------------------- ?>

<?php if ($tbData->slotStartSystem('product/manufacturer/info.products', array('filter' => array('product/manufacturer/info.products.filter|oc_system_products_filter', 'products' => &$products), 'data' => $data), true)): ?>
<?php $has_counter = false; ?>

<?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>

<?php if ($product_settings_context = $tbData['products_system']) extract($product_settings_context); ?>
<?php if ($products) { ?>
<?php if ($tbData['system.products']['filter']): ?>
<nav class="tb_listing_options">
  <div class="product-filter">
    <div class="display">
      <a<?php if ($tbData->product_listing_type == 'grid'): ?> class="tb_main_color"<?php endif; ?> href="javascript:;" data-view="grid"><i class="fa fa-th-large"></i> <?php echo $button_grid; ?></a>
      <a<?php if ($tbData->product_listing_type == 'list'): ?> class="tb_main_color"<?php endif; ?> href="javascript:;" data-view="list"><i class="fa fa-th-list"></i> <?php echo $button_list; ?></a>
    </div>
    <?php if ($tbData->common['compare_enabled']): ?>
    <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
    <?php endif; ?>
    <div class="limit"><?php echo $text_limit; ?>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort"><?php echo $text_sort; ?>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
</nav>
<?php endif; ?>

<div class="tb_products tb_listing <?php echo $listing_classes; ?>">
  <?php foreach ($products as $product) { ?>
  <?php $tbData->slotStart('products_listing.product', array('product' => $product, 'data' => $data, 'product_settings_context' => $product_settings_context)); ?>
  <div class="product-layout">
    <input class="product-id_<?php echo $product['product_id']; ?>" type="hidden" value="" />
    <div class="product-thumb">
      <?php if ($product['thumb'] && $show_thumb): $tbData->slotStart('products_listing.product.thumb'); ?>
      <div class="image"><a href="<?php echo $product['href']; ?>"><span style="max-width: <?php echo $product['thumb_width']; ?>px;"><span style="padding-top: <?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%"><img src="<?php echo $product['thumb']; ?>"<?php if ($tbData->system['image_lazyload']): ?> data-src="<?php echo $product['thumb_original']; ?>" class="lazyload"<?php endif; ?> width="<?php echo $product['thumb_width']; ?>" height="<?php echo $product['thumb_height']; ?>" alt="<?php echo $product['name']; ?>" style="margin-top: -<?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%" /></span></span></a></div>
      <?php if ($product['thumb_hover']): ?>
      <div class="image_hover"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif" data-src="<?php echo $product['thumb_hover']; ?>" width="<?php echo $product['thumb_width']; ?>" height="<?php echo $product['thumb_height']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
      <?php endif; ?>
      <?php $tbData->slotCaptureEcho(); endif; ?>
      <div>
        <div class="caption">
          <?php if ($show_title): $tbData->slotStart('products_listing.product.title'); ?>
          <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
          <?php $tbData->slotCaptureEcho(); endif; ?>
          <?php if ($product['description']): $tbData->slotStart('products_listing.product.description'); ?>
          <div class="description"><?php echo $product['description']; ?></div>
          <?php $tbData->slotCaptureEcho(); endif; ?>
          <?php if ($product['price']) { ?>
          <?php $tbData->slotStart('products_listing.product.price'); ?>
          <p class="price">
            <?php if (!$product['special']) { ?>
            <span class="price-regular"><?php echo $product['price']; ?></span>
            <?php } else { ?>
            <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
            <?php } ?>
          </p>
          <?php $tbData->slotCaptureEcho(); ?>
          <?php if ($product['tax']) { ?>
          <?php $tbData->slotStart('products_listing.product.tax'); ?>
          <span class="price-tax"><span><?php echo $text_tax; ?></span> <?php echo $product['tax']; ?></span>
          <?php $tbData->slotCaptureEcho(); ?>
          <?php } ?>
          <?php } ?>
          <?php if ($product['special'] && $product['special_date_end']): ?>
          <?php $tbData->slotStart('products_listing.product.special_price_end'); ?>
          <?php $has_counter = true; ?>
          <p class="tb_counter tb_style_1">
            <span class="tb_counter_label h4"><?php echo $tbData->text_offer_ending; ?></span>
            <span class="tb_counter_time" data-special-price-end-date="<?php echo date('Y/m/d', strtotime($product['special_date_end'])); ?>">
              <?php
              $total_seconds = strtotime($product['special_date_end']) - time();
              $days          = floor($total_seconds / 86400);
              $hours         = floor(($total_seconds % 86400) / 3600);
              $minutes       = floor((($total_seconds % 86400) % 3600 ) / 60);
              $seconds       = floor(((($total_seconds % 86400) % 3600 ) % 3600 ) / 60);
              ?>
              <span class="tb_counter_days"><?php echo $days; ?></span>
              <span class="tb_counter_hours"><?php echo $hours; ?></span>
              <span class="tb_counter_minutes"><?php echo $minutes; ?></span>
              <span class="tb_counter_seconds"><?php echo $seconds; ?></span>
            </span>
          </p>
          <?php $tbData->slotCaptureEcho(); ?>
          <?php endif; ?>
          <?php if ($product['rating']) { ?>
          <?php $tbData->slotStart('products_listing.product.rating'); ?>
          <div class="rating">
            <div class="tb_bar">
              <span class="tb_percent" style="width: <?php echo $product['rating'] * 20; ?>%;"></span>
              <span class="tb_base"></span>
            </div>
            <span class="tb_average"><?php echo $product['rating']; ?>/5</span>
          </div>
          <?php $tbData->slotCaptureEcho(); ?>
          <?php } ?>
        </div>
        <?php if ($product['show_cart'] || $show_wishlist || $show_compare): ?>
        <div class="button-group">
          <?php if ($product['show_cart']): $tbData->slotStart('products_listing.product.button_cart'); ?>
          <div class="tb_button_add_to_cart<?php echo $cart_button_position_classes; ?>"<?php echo $cart_button_offset_attr; ?>>
            <?php if ($tbData->OcVersionGte('2.0.2.0')): ?>
            <a class="<?php echo $cart_button_classes; ?>" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');">
            <?php else: ?>
            <a class="<?php echo $cart_button_classes; ?>" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>');">
            <?php endif; ?>
              <span data-tooltip="<?php echo $product['text_button_cart']; ?>"><?php echo $product['text_button_cart']; ?></span>
            </a>
          </div>
          <?php $tbData->slotCaptureEcho(); endif; ?>
          <?php if ($show_wishlist): $tbData->slotStart('products_listing.product.button_wishlist'); ?>
          <div class="tb_button_wishlist<?php echo $wishlist_button_position_classes; ?>"<?php echo $wishlist_button_offset_attr; ?>>
            <a class="<?php echo $wishlist_button_classes; ?>" href="javascript:;" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
              <span data-tooltip="<?php echo $tbData->text_wishlist; ?>"><?php echo $tbData->text_wishlist; ?></span>
            </a>
          </div>
          <?php $tbData->slotCaptureEcho(); endif; ?>
          <?php if ($show_compare): $tbData->slotStart('products_listing.product.button_compare'); ?>
          <div class="tb_button_compare<?php echo $compare_button_position_classes; ?>"<?php echo $compare_button_offset_attr; ?>>
            <a class="<?php echo $compare_button_classes; ?>" href="javascript:;" onclick="compare.add('<?php echo $product['product_id']; ?>');">
              <span data-tooltip="<?php echo $tbData->text_compare; ?>"><?php echo $tbData->text_compare; ?></span>
            </a>
          </div>
          <?php $tbData->slotCaptureEcho(); endif; ?>
          <?php if ($show_quickview): $tbData->slotStart('products_listing.product.button_quickview'); ?>
          <div class="tb_button_quickview<?php echo $quickview_button_position_classes; ?>"<?php echo $quickview_button_offset_attr; ?>>
            <a class="<?php echo $quickview_button_classes; ?>" href="javascript:;" onclick="tbQuickView('<?php echo $product['product_id']; ?>');">
              <span data-tooltip="<?php echo $tbData->text_quickview; ?>"><?php echo $tbData->text_quickview; ?></span>
            </a>
          </div>
          <?php $tbData->slotCaptureEcho(); endif; ?>
        </div>
        <?php endif; ?>
        <?php if ($product['show_stock']): $tbData->slotStart('products_listing.product.stock_status'); ?>
        <p class="tb_label_stock_status tb_label_stock_status_<?php echo $product['stock_status_id']; ?>"><?php echo $product['stock_status']; ?></p>
        <?php $tbData->slotCaptureEcho(); endif; ?>
      </div>
      <?php if ($product['show_label_sale']): $tbData->slotStart('products_listing.product.label_sale'); ?>
      <p class="tb_label_special"><?php echo $product['savings_text']; ?></p>
      <?php $tbData->slotCaptureEcho(); endif; ?>
      <?php if ($show_label_new && $product['is_new']): $tbData->slotStart('products_listing.product.label_new'); ?>
      <p class="tb_label_new"><?php echo $tbData->text_label_new; ?></p>
      <?php $tbData->slotCaptureEcho(); endif; ?>
    </div>
  </div>
  <?php $tbData->slotStopEcho(); ?>
  <?php } ?>
</div>

<div class="pagination">
  <?php echo str_replace('pagination', 'links', $pagination); ?>
  <?php if (!empty($results)): ?>
  <div class="results"><?php echo $results; ?></div>
  <?php endif; ?>
</div>

<?php } else { ?>
<p class="tb_empty"><?php echo $text_empty; ?></p>
<?php }?>

<script type="text/javascript">
tbApp.onScriptLoaded(function() {

    $('#{{widget_dom_id}}').on('click', '.display > a', function() {
      if ($(this).is('.tb_main_color')) {
        return false;
      }
      $.cookie('listingType', $(this).data('view'), { path: '/' });
      location.reload();
    });

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

    <?php // PRODUCT COUNTER ?>
    <?php if ($has_counter): ?>
    create_countdown('#{{widget_dom_id}}','<?php echo date('D M d Y H:i:s O'); ?>', '<?php echo date('Z') / 3600 - date('I'); ?>');
    <?php endif; ?>
});
</script>

<?php // ADJUST PRODUCT SIZE ?>
<?php if ($tbData->product_listing_type == 'grid'): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#{{widget_dom_id}}', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>

<?php echo $content_bottom; ?>

<?php $tbData->slotStop(); endif; ?>


<?php echo $footer; ?>