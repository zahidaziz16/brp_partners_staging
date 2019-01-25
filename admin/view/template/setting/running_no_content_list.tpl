<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_header; ?></h3>
      </div>
      
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-details" class="form-horizontal">
            <div class="tab-content">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-module_uid"><?php echo $entry_module_uid; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="module_uid" value="<?php echo $module_uid; ?>" placeholder="<?php echo $entry_module_uid; ?>" id="input-module_uid" class="form-control" <?php if($save_type=="edit") { ?> readonly="readonly" <?php } ?> />
                  <?php if ($error_module_uid) { ?>
                  <div class="text-danger"><?php echo $error_module_uid; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-current"><?php echo $entry_current; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="current" value="<?php echo $current; ?>" placeholder="<?php echo $entry_current; ?>" id="input-current" class="form-control" onkeypress="return strictlyNumberOnly(event, true);" />
                  <?php if ($error_current) { ?>
                  <div class="text-danger"><?php echo $error_current; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-padding"><?php echo $entry_padding; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="padding" value="<?php echo $padding; ?>" placeholder="<?php echo $entry_padding; ?>" id="input-padding" class="form-control" onkeypress="return strictlyNumberOnly(event, false);"  />
                  <?php if ($error_padding) { ?>
                  <div class="text-danger"><?php echo $error_padding; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-prefix"><?php echo $entry_prefix; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="prefix" value="<?php echo $prefix; ?>" placeholder="<?php echo $entry_prefix; ?>" id="input-prefix" class="form-control" />
                  <?php if ($error_prefix) { ?>
                  <div class="text-danger"><?php echo $error_prefix; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-suffix"><?php echo $entry_suffix; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="suffix" value="<?php echo $suffix; ?>" placeholder="<?php echo $entry_suffix; ?>" id="input-suffix" class="form-control" />
                  <?php if ($error_suffix) { ?>
                  <div class="text-danger"><?php echo $error_suffix; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <button type="submit" id="button-save" class="btn btn-primary pull-right"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
function form_submit() {
	var isValid = true;
	if(isValid) {
		document.forms.form_transaction_header.submit();
	}
}
//--></script>
<script type="text/javascript"><!--
$('#button-sync').on('click', function(e) {
	$('#form-details').attr('action', this.getAttribute('formAction'));
	$('#form-details').submit();
});
$('#button-sync2').on('click', function(e) {
	$('#form-details').attr('action', this.getAttribute('formAction'));
	$('#form-details').submit();
});
//--></script> 
</div>
<?php echo $footer; ?>