<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStartSystem('account/address.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStartSystem('account/address.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStartSystem('account/address.page_content', array('filter' => array('account/address.page_content.filter', 'addresses' => &$addresses), 'data' => $data)); ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
<?php } ?>

<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>

<p><?php echo $text_address_book; ?></p>
<?php if ($addresses) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <?php foreach ($addresses as $result) { ?>
    <tr>
      <td class="text-left"><?php echo $result['address']; ?></td>
      <td class="text-right"><a href="<?php echo $result['update']; ?>" class="btn btn-info"><?php echo $button_edit; ?></a> &nbsp; <a href="<?php echo $result['delete']; ?>" class="btn btn-danger"><?php echo $button_delete; ?></a></td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php } else { ?>
<p class="tb_empty"><?php echo $text_empty; ?></p>
<?php } ?>
<div class="buttons clearfix">
  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
  <div class="pull-right"><a href="<?php echo $add; ?>" class="btn btn-primary"><?php echo $button_new_address; ?></a></div>
</div>

<script type="text/javascript" data-critical="1">
tbUtils.removeClass(document.getElementById('{{widget_dom_id}}').querySelector('.table-bordered'), 'table-bordered table-hover');
tbUtils.addClass(document.getElementById('{{widget_dom_id}}').querySelector('.table'), 'table-minimal');
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('td .btn'), function(el) {
    tbUtils.addClass(el, 'btn-sm btn-default');
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>