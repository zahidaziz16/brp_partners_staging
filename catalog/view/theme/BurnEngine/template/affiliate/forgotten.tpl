<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/forgotten.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/forgotten.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/forgotten.page_content'); ?>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
  <fieldset>
    <legend><?php echo $text_your_email; ?></legend>
    <p><?php echo $text_email; ?></p>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
      <div class="col-sm-10">
        <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
      </div>
    </div>
  </fieldset>
          
  <div class="buttons clearfix">
    <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>
