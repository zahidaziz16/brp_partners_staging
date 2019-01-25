<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('account/download.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('account/download.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('account/download.page_content'); ?>
<?php if ($downloads) { ?>
<div class="tb_downloads table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th class="id"><?php echo $column_order_id; ?></th>
        <th class="name"><?php echo $column_name; ?></th>
        <th class="size"><?php echo $column_size; ?></th>
        <th class="date"><?php echo $column_date_added; ?></th>
        <th class="download">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($downloads as $download): ?>
      <tr>
        <td class="id"><?php echo $download['order_id']; ?></td>
        <td class="name"><?php echo $download['name']; ?></td>
        <td class="size"><?php echo $download['size']; ?></td>
        <td class="date"><?php echo $download['date_added']; ?></td>
        <td class="download"><a href="<?php echo $download['href']; ?>" data-toggle="tooltip" title="<?php echo $button_download; ?>" class="btn btn-primary"><i class="fa fa-cloud-download"></i></a></td>
      </tr>
      <?php endforeach; ?>
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
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>