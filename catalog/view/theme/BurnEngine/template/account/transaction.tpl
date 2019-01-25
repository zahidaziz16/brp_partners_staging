<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/transaction.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/transaction.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/transaction.page_content'); ?>
<p><?php echo $text_total; ?> <strong class="tb_balance_total tb_main_color"><?php echo $total; ?></strong></p>

<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-right"><?php echo $column_amount; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions  as $transaction) { ?>
      <tr>
        <td class="text-left"><?php echo $transaction['date_added']; ?></td>
        <td class="text-left"><?php echo $transaction['description']; ?></td>
        <td class="text-right"><?php echo $transaction['amount']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="pagination">
  <?php echo str_replace('pagination', 'links', $pagination); ?>
  <?php if (!empty($results)): ?>
  <div class="results"><?php echo $results; ?></div>
  <?php endif; ?>
</div>

<div class="buttons clearfix">
  <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
</div>

<script type="text/javascript" data-critical="1">
tbUtils.removeClass(document.getElementById('{{widget_dom_id}}').querySelector('.table-bordered'), 'table-bordered');
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>