<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/wishlist.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/wishlist.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/wishlist.page_content', array('filter' => array('account/wishlist.products.filter|oc_module_products.filter', 'products' => &$products), 'data' => $data)); ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>

<?php if ($products) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-center"><?php echo $column_image; ?></td>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left"><?php echo $column_model; ?></td>
        <td class="text-right"><?php echo $column_stock; ?></td>
        <td class="text-right"><?php echo $column_price; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="text-center"><?php if ($product['thumb']) { ?>
          <a href="<?php echo $product['href']; ?>" class="thumbnail">
            <span class="image-holder" style="max-width: <?php echo $product['thumb_width']; ?>px;">
            <span style="padding-top: <?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%">
              <img src="<?php echo $product['thumb']; ?>"
                <?php if ($tbData->system['image_lazyload']): ?>
                data-src="<?php echo $product['thumb_original']; ?>"
                class="lazyload"
                <?php endif; ?>
                width="<?php echo $product['thumb_width']; ?>"
                height="<?php echo $product['thumb_height']; ?>"
                alt="<?php echo $product['name']; ?>"
                style="margin-top: -<?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%" />
              />
            </span>
            </span>
          </a>
          <?php } ?></td>
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
        <td class="text-left"><?php echo $product['model']; ?></td>
        <td class="text-right"><?php echo $product['stock']; ?></td>
        <td class="text-right"><?php if ($product['price']) { ?>
          <div class="price">
            <?php if (!$product['special']) { ?>
            <?php echo $product['price']; ?>
            <?php } else { ?>
            <b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s>
            <?php } ?>
          </div>
          <?php } ?></td>
        <td class="text-right"><button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_cart; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></button>
          <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php } else { ?>
        
<p class="tb_empty"><?php echo $text_empty; ?></p>

<?php } ?>
        
<div class="buttons clearfix">
  <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
</div>

<script type="text/javascript" data-critical="1">
tbUtils.removeClass(document.getElementById('{{widget_dom_id}}').querySelector('.table-bordered'), 'table-bordered');
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('td .btn'), function(el) {
    tbUtils.addClass(el, 'tb_no_text');
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>