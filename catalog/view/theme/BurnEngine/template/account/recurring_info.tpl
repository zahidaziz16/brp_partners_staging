<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/recurring/info.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/recurring/info.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/recurring/info.page_content'); ?>
<?php if (!$tbData->OcVersionGte('2.2.0.0')): ?>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php endif; ?>

<table class="table table-bordered table-hover">
  <thead>
  <tr>
    <td class="text-left" colspan="2"><?php echo $text_recurring_detail; ?></td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
    <td class="text-left" style="width: 50%;"><b><?php echo $text_order_recurring_id; ?></b> #<?php echo $order_recurring_id; ?><br />
      <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
      <b><?php echo $text_status; ?></b> <?php echo $status; ?><br />
      <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?></td>
    <td class="text-left" style="width: 50%;"><b><?php echo $text_order_id; ?></b> <a href="<?php echo $order; ?>">#<?php echo $order_id; ?></a><br />
      <b><?php echo $text_product; ?></b> <a href="<?php echo $product; ?>"><?php echo $product_name; ?></a><br />
      <b><?php echo $text_quantity; ?></b> <?php echo $product_quantity; ?></td>
    <?php else: ?>
    <td class="text-left" style="width: 50%;">
      <p><b><?php echo $text_recurring_id; ?></b> #<?php echo $recurring['order_recurring_id']; ?></p>
      <p><b><?php echo $text_date_added; ?></b> <?php echo $recurring['date_added']; ?></p>
      <p><b><?php echo $text_status; ?></b> <?php echo $status_types[$recurring['status']]; ?></p>
      <p><b><?php echo $text_payment_method; ?></b> <?php echo $recurring['payment_method']; ?></p>
    </td>
    <td class="left" style="width: 50%; vertical-align: top;">
      <p><b><?php echo $text_product; ?></b><a href="<?php echo $recurring['product_link']; ?>"><?php echo $recurring['product_name']; ?></a></p>
      <p><b><?php echo $text_quantity; ?></b> <?php echo $recurring['product_quantity']; ?></p>
      <p><b><?php echo $text_order; ?></b><a href="<?php echo $recurring['order_link']; ?>">#<?php echo $recurring['order_id']; ?></a></p>
    </td>
    <?php endif; ?>
  </tr>
  </tbody>
</table>
<table class="table table-bordered table-hover">
  <thead>
  <tr>
    <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
    <td class="text-left"><?php echo $text_description; ?></td>
    <td class="text-left"><?php echo $text_reference; ?></td>
    <?php else: ?>
    <td class="text-left"><?php echo $text_recurring_description; ?></td>
    <td class="text-left"><?php echo $text_ref; ?></td>
    <?php endif; ?>
  </tr>
  </thead>
  <tbody>
  <tr>
    <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
    <td class="text-left" style="width: 50%;"><?php echo $recurring_description; ?></td>
    <td class="text-left" style="width: 50%;"><?php echo $reference; ?></td>
    <?php else: ?>
    <td class="text-left" style="width: 50%;">
      <p style="margin:5px;"><?php echo $recurring['recurring_description']; ?></p></td>
    <td class="text-left" style="width: 50%;">
      <p style="margin:5px;"><?php echo $recurring['reference']; ?></p></td>
    <?php endif; ?>
  </tr>
  </tbody>
</table>
<?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
<h2><?php echo $text_transaction; ?></h2>
<?php else: ?>
<h2><?php echo $text_transactions; ?></h2>
<?php endif; ?>
<table class="table table-bordered table-hover">
  <thead>
  <tr>
    <td class="text-left"><?php echo $column_date_added; ?></td>
    <td class="text-center"><?php echo $column_type; ?></td>
    <td class="text-right"><?php echo $column_amount; ?></td>
  </tr>
  </thead>
  <tbody>
  <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
  <?php if ($transactions) { ?>
  <?php foreach ($transactions as $transaction) { ?>
  <tr>
    <td class="text-left"><?php echo $transaction['date_added']; ?></td>
    <td class="text-left"><?php echo $transaction['type']; ?></td>
    <td class="text-right"><?php echo $transaction['amount']; ?></td>
  </tr>
  <?php } ?>
  <?php } else { ?>
  <tr>
    <td colspan="3" class="text-center"><?php echo $text_no_results; ?></td>
  </tr>
  <?php } ?>
  <?php else: ?>
  <?php if (!empty($recurring['transactions'])) { ?><?php foreach ($recurring['transactions'] as $transaction) { ?>
  <tr>
    <td class="text-left"><?php echo $transaction['date_added']; ?></td>
    <td class="text-center"><?php echo $transaction_types[$transaction['type']]; ?></td>
    <td class="text-right"><?php echo $transaction['amount']; ?></td>
  </tr>
  <?php } ?><?php }else{ ?>
  <tr>
    <td colspan="3" class="text-center"><?php echo $text_empty_transactions; ?></td>
  </tr>
  <?php } ?>
  <?php endif; ?>
  </tbody>
</table>

<?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
<?php echo $recurring; ?>
<?php else: ?>
<?php echo $buttons; ?>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>