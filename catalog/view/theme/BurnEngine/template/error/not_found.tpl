<?php if ($tbData->route == 'product/category' || $tbData->route == 'information/information' || $tbData->route == 'product/product') { ob_end_clean(); header('HTTP/1.0 404 Not Found'); header('Location: ' . $tbData->url->link('error/not_found')); exit(); } ?>

<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStartSystem('error/not_found.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStartSystem('error/not_found.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStartSystem('error/not_found.page_content'); ?>
<div class="tb_text_wrap tb_sep">
  <p><?php echo $text_error; ?></p>
</div>

<div class="buttons">
  <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
</div>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>
