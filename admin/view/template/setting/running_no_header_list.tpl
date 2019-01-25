<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <?php /*
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-page').submit() : false;"><i class="fa fa-trash-o"></i></button>
      	*/ ?>
      </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="input-module_uid"><?php echo $entry_module_uid; ?></label>
                <input type="text" name="filter_module_uid" value="<?php echo $filter_module_uid; ?>" placeholder="<?php echo $entry_module_uid; ?>" id="input-module_uid" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-page">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <?php /*
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>*/ ?>
                  <td class="text-center">
                  	<?php if ($sort == 'module_uid') { ?>
                    	<a href="<?php echo $sort_module_uid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_module_uid; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_module_uid; ?>"><?php echo $column_module_uid; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                  	<?php if ($sort == 'current') { ?>
                    	<a href="<?php echo $sort_total_item_lines; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_current; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_current; ?>"><?php echo $column_current; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                  	<?php if ($sort == 'padding') { ?>
                    	<a href="<?php echo $sort_padding; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_padding; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_padding; ?>"><?php echo $column_padding; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                  	<?php if ($sort == 'prefix') { ?>
                    	<a href="<?php echo $sort_prefix; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_prefix; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_prefix; ?>"><?php echo $column_prefix; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                  	<?php if ($sort == 'suffix') { ?>
                    	<a href="<?php echo $sort_suffix; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_suffix; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_suffix; ?>"><?php echo $column_suffix; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($runns) { ?>
                <?php foreach ($runns as $runn) { ?>
                <tr>
                	<?php /*
                    <td class="text-center">
                    	<?php if (in_array($runn['id'], $selected)) { ?>
                        	<input type="checkbox" name="selected[]" value="<?php echo $runn['id']; ?>" checked="checked" />
                        <?php } else { ?>
                        	<input type="checkbox" name="selected[]" value="<?php echo $runn['id']; ?>" />
                        <?php } ?>
                    </td>*/ ?>
                    <td class="text-center">
                    	<?php echo $runn['module_uid']; ?>
                    </td>
                    <td class="text-right">
                    	<?php echo $runn['current']; ?>
                    </td>
                    <td class="text-right">
                    	<?php echo $runn['padding']; ?>
                    </td>
                    <td class="text-center">
                    	<?php echo $runn['prefix']; ?>
                    </td>
                    <td class="text-center">
                    	<?php echo $runn['suffix']; ?>
                    </td>
                    <td class="text-right"><a href="<?php echo $runn['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=setting/running_no&token=<?php echo $token; ?>';
	var filter_module_uid = $('input[name=\'filter_module_uid\']').val();
	if (filter_module_uid) {
		url += '&filter_module_uid=' + encodeURIComponent(filter_module_uid);
	}
	location = url;
});
//--></script>
</div>
<?php echo $footer; ?>