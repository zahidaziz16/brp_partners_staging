<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/return.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/return.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/return.page_content', array('filter' => array('account/return.page_content.filter', 'returns' => &$returns), 'data' => $data)); ?>
<?php if ($returns) { ?>
<div class="tb_returns table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-right"><?php echo $column_return_id; ?></td>
        <td class="text-left"><?php echo $column_status; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-right"><?php echo $column_order_id; ?></td>
        <td class="text-left"><?php echo $column_customer; ?></td>
        <td></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($returns as $return) { ?>
      <tr>
        <td class="text-right">#<?php echo $return['return_id']; ?></td>
        <td class="text-left"><?php echo $return['status']; ?></td>
        <td class="text-left"><?php echo $return['date_added']; ?></td>
        <td class="text-right"><?php echo $return['order_id']; ?></td>
        <td class="text-left"><?php echo $return['name']; ?></td>
        <td><a href="<?php echo $return['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
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
    tbUtils.addClass(el, 'btn-default tb_no_text');
});
</script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>