<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStartSystem('information/information.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStartSystem('information/information.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStartSystem('information/information.page_content'); ?>

<?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>

<?php if ($description): ?>
<div class="tb_text_wrap">
  <?php echo $description; ?>
</div>
<?php endif; ?>

<?php echo $content_bottom; ?>

<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>