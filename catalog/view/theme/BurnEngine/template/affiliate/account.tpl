<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/account.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/account.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/account.page_content'); ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
<?php } ?>

<h2><?php echo $text_my_account; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
  <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
  <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
  <?php if ($tbData['affiliate']->isLogged()): ?>
  <li><a href="<?php echo $tbData['url']->link('affiliate/logout', '', 'SSL'); ?>"><?php echo $tbData->text_logout; ?></a></li>
  <?php endif; ?>
</ul>

<h2><?php echo $text_my_tracking; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $tracking; ?>"><?php echo $text_tracking; ?></a></li>
</ul>

<h2><?php echo $text_my_transactions; ?></h2>
<ul class="list-unstyled">
  <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
</ul>

<script type="text/javascript" data-critical="1">
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('.list-unstyled'), function(el) {
    tbUtils.addClass(el, 'tb_list_1');
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>