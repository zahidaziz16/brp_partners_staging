<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/login.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/login.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('affiliate/login.page_content'); ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
<?php } ?>
        
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>

<div class="tb_description tb_sep">
  <?php echo $text_description; ?>
</div>

<span class="clear tb_sep border"></span>

<div class="row">
  <div class="col-sm-6">
    <div class="well">
      <h2><?php echo $text_new_affiliate; ?></h2>
      <div id="new_customer">
        <p><?php echo $text_register_account; ?></p>
      </div>
      <div class="buttons">
      <div class="pull-right">
        <a class="btn btn-primary" href="<?php echo $register; ?>"><?php echo $button_continue; ?></a></div>
      </div>
    </div>
  </div>

  <div class="col-sm-6">
    <div class="well">
      <h2><?php echo $text_returning_affiliate; ?></h2>
      <p><strong><?php echo $text_i_am_returning_affiliate; ?></strong></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset>
          <div class="form-group">
            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
          </div>
          <div class="form-group">
            <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
            <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
          </div>
        </fieldset>

        <div class="buttons">
          <div class="pull-left">
            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
          </div>
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#{{widget_dom_id}} input').keydown(function(e) {
  if (e.keyCode == 13) {
    $('#{{widget_dom_id}} form').submit();
  }
});
//--></script>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>