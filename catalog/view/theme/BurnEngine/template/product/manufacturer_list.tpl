<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('product/manufacturer.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('product/manufacturer.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('product/manufacturer.page_content'); ?>

<?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>

<?php if ($categories) { ?>

<div class="tb_alphabet_index tb_bg_str_2 border">
  <p><b><?php echo $text_index; ?></b>
    <?php foreach ($categories as $category) { ?>
    &nbsp;&nbsp;&nbsp;<a href="index.php?route=product/manufacturer#<?php echo $category['name']; ?>"><b><?php echo $category['name']; ?></b></a>
    <?php } ?>
  </p>
</div>

<?php foreach ($categories as $category) { ?>
<div class="manufacturer-list">
  <div class="manufacturer-heading"><?php echo $category['name']; ?><a id="<?php echo $category['name']; ?>"></a></div>
  <div class="manufacturer-content">
    <?php if ($category['manufacturer']) { ?>
    <?php for ($i = 0; $i < count($category['manufacturer']);) { ?>
    <ul class="tb_list_1">
      <?php $j = $i + ceil(count($category['manufacturer']) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($category['manufacturer'][$i])) { ?>
      <li><a href="<?php echo $category['manufacturer'][$i]['href']; ?>"><?php echo $category['manufacturer'][$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<?php } ?>

<?php } else { ?>

<p class="tb_empty"><?php echo $text_empty; ?></p>

<div class="buttons">
  <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
</div>

<?php } ?>

<?php echo $content_bottom; ?>

<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>