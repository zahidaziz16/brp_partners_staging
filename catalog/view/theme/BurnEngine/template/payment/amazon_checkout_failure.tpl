<?php echo $header; ?>


<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout/failure.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('payment/amazon_checkout/failure.page_content'); ?>
<div class="tb_text_wrap">
  <p><?php echo $text_payment_failed ?></p>
</div>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>