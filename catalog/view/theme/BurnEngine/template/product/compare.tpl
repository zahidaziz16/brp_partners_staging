<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('product/compare.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('product/compare.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('product/compare.page_content', array('filter' => array('product/compare.products.filter|oc_module_products.filter', 'products' => &$products), 'data' => $data)); ?>

<?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>

<?php if ($products) { ?>
<div class="compare-info table-responsive">
  <table class="table">
    <thead>
      <tr>
        <td class="compare-product" colspan="<?php echo count($products) + 1; ?>"><?php echo $text_product; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $text_name; ?></td>
        <?php foreach ($products as $product) { ?>
        <td class="name"><a href="<?php echo $products[$product['product_id']]['href']; ?>"><?php echo $products[$product['product_id']]['name']; ?></a></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_image; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php if ($products[$product['product_id']]['thumb']) { ?>
          <a href="<?php echo $product['href']; ?>">
            <span class="image-holder" style="max-width: <?php echo $products[$product['product_id']]['thumb_width']; ?>px;">
            <span style="padding-top: <?php echo round($products[$product['product_id']]['thumb_height'] / $products[$product['product_id']]['thumb_width'], 4) * 100; ?>%">
              <img src="<?php echo $products[$product['product_id']]['thumb']; ?>"<?php if ($tbData->system['image_lazyload']): ?> data-src="<?php echo $products[$product['product_id']]['thumb_original']; ?>" class="lazyload"<?php endif; ?> width="<?php echo $products[$product['product_id']]['thumb_width']; ?>" height="<?php echo $products[$product['product_id']]['thumb_height']; ?>" alt="<?php echo $products[$product['product_id']]['name']; ?>"  style="margin-top: -<?php echo round($products[$product['product_id']]['thumb_height'] / $products[$product['product_id']]['thumb_width'], 4) * 100; ?>%" /></span></span>
            </span>
            </span>
          </a>
          <?php } ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_price; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php if ($products[$product['product_id']]['price']) { ?>
          <?php if (!$products[$product['product_id']]['special']) { ?>
          <?php echo $products[$product['product_id']]['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $products[$product['product_id']]['price']; ?></span> <span class="price-new"><?php echo $products[$product['product_id']]['special']; ?></span>
          <?php } ?>
          <?php } ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_model; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php echo $products[$product['product_id']]['model']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_manufacturer; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php echo $products[$product['product_id']]['manufacturer']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_availability; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php echo $products[$product['product_id']]['availability']; ?></td>
        <?php } ?>
      </tr>
      <?php if ($review_status) { ?>
      <tr>
        <td><?php echo $text_rating; ?></td>
        <?php foreach ($products as $product) { ?>
        <td>
          <div class="rating">
            <div class="tb_bar">
              <span class="tb_percent" style="width: <?php echo $products[$product['product_id']]['rating'] * 20; ?>%;"></span>
              <span class="tb_base"></span>
            </div>
            <span class="tb_average"><?php echo $products[$product['product_id']]['rating']; ?>/5</span>
          </div>
          <span class="tb_total">(<?php echo $products[$product['product_id']]['reviews']; ?>)</span>
        </td>
        <?php } ?>
      </tr>
      <?php } ?>
      <tr>
        <td><?php echo $text_summary; ?></td>
        <?php foreach ($products as $product) { ?>
        <td class="description"><?php echo $products[$product['product_id']]['description']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_weight; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php echo $product['weight']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td><?php echo $text_dimension; ?></td>
        <?php foreach ($products as $product) { ?>
        <td><?php echo $product['length']; ?> x <?php echo $product['width']; ?> x <?php echo $product['height']; ?></td>
        <?php } ?>
      </tr>
    </tbody>
    <?php foreach ($attribute_groups as $attribute_group) { ?>
    <thead>
      <tr>
        <td class="compare-attribute" colspan="<?php echo count($products) + 1; ?>"><?php echo $attribute_group['name']; ?></td>
      </tr>
    </thead>
    <?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
    <tbody>
      <tr>
        <td><?php echo $attribute['name']; ?></td>
        <?php foreach ($products as $product) { ?>
        <?php if (isset($products[$product['product_id']]['attribute'][$key])) { ?>
        <td><?php echo $products[$product['product_id']]['attribute'][$key]; ?></td>
        <?php } else { ?>
        <td></td>
        <?php } ?>
        <?php } ?>
      </tr>
    </tbody>
    <?php } ?>
    <?php } ?>
    <tbody>
      <tr>
        <td></td>
        <?php foreach ($products as $product) { ?>
        <td>
          <?php if ($product['show_cart']): ?>
          <?php if ($product['quantity'] < 1 && !$tbData['config']->get('config_stock_checkout')): ?>
          <p class="tb_label_stock_status tb_pt_5 tb_pb_5"><?php echo $product['stock_status']; ?></p>
          <?php else: ?>
          <div class="tb_button_add_to_cart">
            <?php if ($tbData->OcVersionGte('2.0.2.0')): ?>
            <a class="btn" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><?php echo $product['text_button_cart']; ?></a>
            <?php else: ?>
            <a class="btn" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>');"><?php echo $product['text_button_cart']; ?></a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <span class="clear tb_mb_10"></span>
          <?php endif; ?>
          <a class="tb_main_color tb_icon fa-times" href="<?php echo $product['remove']; ?>"> <?php echo $button_remove; ?></a>
        </td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
</div>

<div class="buttons">
  <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_continue; ?></a></div>
</div>

<?php } else { ?>

<p class="tb_empty"><?php echo $text_empty; ?></p>

<div class="buttons">
  <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_continue; ?></a></div>
</div>
<?php } ?>

<?php echo $content_bottom; ?>

<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>