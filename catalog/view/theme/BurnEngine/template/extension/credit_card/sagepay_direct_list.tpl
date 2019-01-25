<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStartSystem('sagepay.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStartSystem('sagepay.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStartSystem('sagepay.page_content'); ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
    <tr>
      <td class="text-left"><?php echo $column_type; ?></td>
      <td class="text-left"><?php echo $column_digits; ?></td>
      <td class="text-right"><?php echo $column_expiry; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php if ($cards) { ?>
      <?php foreach ($cards  as $card) { ?>
        <tr>
          <td class="text-left"><?php echo $card['type']; ?></td>
          <td class="text-left"><?php echo $card['digits']; ?></td>
          <td class="text-right"><?php echo $card['expiry']; ?></td>
          <td class="text-right"><a href="<?php echo $delete . $card['card_id']; ?>" class="btn btn-danger"><?php echo $button_delete; ?></a></td>

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
  <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
  <div class="pull-right"><a href="<?php echo $add; ?>" class="btn btn-primary"><?php echo $button_new_card; ?></a></div>
</div>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>