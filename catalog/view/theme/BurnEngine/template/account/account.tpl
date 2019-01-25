<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/account.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/account.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/account.page_content'); ?>

<?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>

<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
<?php } ?>

<h2><?php echo $text_my_account; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
  <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
  <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
  <?php if ($tbData->common['wishlist_enabled']): ?>
  <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
  <?php endif; ?>
</ul>

<?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
<?php if ($credit_cards) { ?>
<h2><?php echo $text_credit_card; ?></h2>
<ul class="list-unstyled">
  <?php foreach ($credit_cards as $credit_card) { ?>
  <li><a href="<?php echo $credit_card['href']; ?>"><?php echo $credit_card['name']; ?></a></li>
  <?php } ?>
</ul>
<?php } ?>
<?php endif; ?>

<?php if ($tbData->common['checkout_enabled'] || $tbData->common['returns_enabled'] || $reward): ?>
<h2><?php echo $text_my_orders; ?></h2>
<ul class="list-unstyled">
  <?php if ($tbData->common['checkout_enabled']): ?>
  <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
  <li><a href="<?php echo $quotation; ?>"><?php echo $text_quotation; ?></a></li>
  <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
  <?php endif; ?>
  <?php if ($reward) { ?>
  <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
  <?php } ?>
  <?php if ($tbData->common['returns_enabled']): ?>
  <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
  <?php endif; ?>
  <?php if ($tbData->common['checkout_enabled']): ?>
  <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
  <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
  <?php endif; ?> 
</ul>
<?php endif; ?> 

<h2><?php echo $text_my_newsletter; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
</ul>

<script type="text/javascript" data-critical="1">
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('.list-unstyled'), function(el) {
    tbUtils.addClass(el, 'tb_list_1');
});
</script>

<?php echo $content_bottom; ?>

<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>