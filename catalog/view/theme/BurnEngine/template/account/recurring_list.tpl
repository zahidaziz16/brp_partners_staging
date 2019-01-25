<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/recurring.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/recurring.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/recurring.page_content'); ?>
<?php if ($recurrings) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
        <td class="text-right"><?php echo $column_order_recurring_id; ?></td>
        <td class="text-left"><?php echo $column_product; ?></td>
        <td class="text-left"><?php echo $column_status; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-right"></td>
        <?php else: ?>
        <td class="text-left"><?php echo $column_recurring_id; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?php echo $column_status; ?></td>
        <td class="text-left"><?php echo $column_product; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($recurrings as $recurring) { ?>
      <tr>
        <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
        <td class="text-right">#<?php echo $recurring['order_recurring_id']; ?></td>
        <td class="text-left"><?php echo $recurring['product']; ?></td>
        <td class="text-left"><?php echo $recurring['status']; ?></td>
        <td class="text-left"><?php echo $recurring['date_added']; ?></td>
        <td class="text-right"><a href="<?php echo $recurring['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
        <?php else: ?>
        <td class="text-left">#<?php echo $recurring['id']; ?></td>
        <td class="text-left"><?php echo $recurring['date_added']; ?></td>
        <td class="text-left"><?php echo $status_types[$recurring['status']]; ?></td>
        <td class="text-left"><?php echo $recurring['name']; ?></td>
        <td class="text-right"><a href="<?php echo $recurring['href']; ?>" class="btn btn-info"><?php echo $button_view; ?></a></td>
        <?php endif; ?>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="pagination"><?php echo str_replace('pagination', 'links', $pagination); ?></div>
      
<?php } else { ?>
      
<p class="tb_empty"><?php echo $text_empty; ?></p>
      
<?php } ?>

<div class="buttons clearfix">
  <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
</div>

<script type="text/javascript" data-critical="1">
tbUtils.removeClass(document.getElementById('{{widget_dom_id}}').querySelector('.table-bordered'), 'table-bordered');
Array.prototype.forEach.call(document.getElementById('{{widget_dom_id}}').querySelectorAll('.btn-info'), function(el) {
    tbUtils.removeClass(el, 'btn-info');
    tbUtils.addClass(el, 'btn-default');
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>