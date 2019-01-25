<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('information/contact.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('information/contact.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('information/contact.page_content'); ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
  <fieldset>
    <h3><?php echo $text_contact; ?></h3>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
      <div class="col-sm-10">
        <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
        <?php if ($error_name) { ?>
        <div class="text-danger"><?php echo $error_name; ?></div>
        <?php } ?>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
      <div class="col-sm-10">
        <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
        <?php if ($error_email) { ?>
        <div class="text-danger"><?php echo $error_email; ?></div>
        <?php } ?>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-enquiry"><?php echo $entry_enquiry; ?></label>
      <div class="col-sm-10">
        <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"><?php echo $enquiry; ?></textarea>
        <?php if ($error_enquiry) { ?>
        <div class="text-danger"><?php echo $error_enquiry; ?></div>
        <?php } ?>
      </div>
    </div>
    <?php if ($tbData->OcVersionGte('2.1.0.0')): ?>
    <?php echo $captcha; ?>
    <?php elseif ($tbData->OcVersionGte('2.0.2.0')): ?>
    <?php if ($site_key) { ?>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
        <?php if ($error_captcha) { ?>
        <div class="text-danger"><?php echo $error_captcha; ?></div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
    <?php else: ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-captcha"><strong><?php echo $entry_captcha; ?></strong></label>
      <div class="col-sm-10">
        <input type="text" name="captcha" id="input-captcha" class="form-control" />
        <span class="clear"></span>
        <br />
        <img src="index.php?route=tool/captcha" alt="" />
        <?php if ($error_captcha) { ?>
        <div class="text-danger"><?php echo $error_captcha; ?></div>
        <?php } ?>
      </div>
    </div>
    <?php endif; ?>
  </fieldset>

  <div class="buttons">
    <div class="pull-right">
      <input class="btn btn-primary" type="submit" value="<?php echo $button_submit; ?>" />
    </div>
  </div>

</form>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>
