<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <?php /*<button type="submit" form="form-tran" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>*/ ?>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-tran').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            <?php /*<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-transaction_type"><?php echo $entry_transaction_type; ?></label>
                <input type="text" name="filter_transaction_type" value="<?php echo $filter_transaction_type; ?>" placeholder="<?php echo $entry_transaction_type; ?>" id="input-transaction_type" class="form-control" />
              </div>
            </div>*/ ?>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="input-transaction_no"><?php echo $entry_transaction_no; ?></label>
                <input type="text" name="filter_transaction_no" value="<?php echo $filter_transaction_no; ?>" placeholder="<?php echo $entry_transaction_no; ?>" id="input-transaction_no" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-tran">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <?php /*
                  <td class="text-center">
                    <?php if ($sort == 'transaction_type') { ?>
                    	<a href="<?php echo $sort_transaction_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_transaction_type; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_transaction_type; ?>"><?php echo $column_transaction_type; ?></a>
                    <?php } ?>
                  </td>*/ ?>
                  <td class="text-center">
                  	<?php if ($sort == 'transaction_no') { ?>
                    	<a href="<?php echo $sort_transaction_no; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_transaction_no; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_transaction_no; ?>"><?php echo $column_transaction_no; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                  	<?php if ($sort == 'date_added') { ?>
                    	<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Transaction Date</a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_date_added; ?>">Transaction Date</a>
                    <?php } ?>
                  </td>
                  <?php /*
                  <td class="text-center"><?php echo $column_total_amount; ?></td>
                  <td class="text-center"><?php echo $column_net_amount; ?></td>
                  */ ?>
                  <td class="text-center">
                  	<?php if ($sort == 'total_item_lines') { ?>
                    	<a href="<?php echo $sort_total_item_lines; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_item_lines; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_total_item_lines; ?>"><?php echo $column_total_item_lines; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                  	<?php if ($sort == 'received_datetime') { ?>
                    	<a href="<?php echo $sort_received_datetime; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_received_datetime; ?></a>
                    <?php } else { ?>
                    	<a href="<?php echo $sort_received_datetime; ?>"><?php echo $column_received_datetime; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                    <?php echo $column_status; ?>
                  </td>
                  <td class="text-center"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($trans) { ?>
                <?php foreach ($trans as $tran) { ?>
                <tr>
                    <td class="text-center">
                        <?php if($tran["transaction_no"]!="" && $tran["ecowarehouse_sync_status"]=="-" && $tran["warehouseeco_sync_status"]=="-") { ?>
                            <?php if (in_array($tran['id'], $selected)) { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $tran['id']; ?>" checked="checked" />
                            <?php } else { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $tran['id']; ?>" />
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <?php /*
                    <td class="text-left">
                    	<?php echo $tran['transaction_type']; ?>
                    </td>
                    */ ?>
                    <td class="text-right">
                    	<?php echo $tran['transaction_no']; ?>
                    </td>
                    <td class="text-left">
                    	<?php echo $tran['date_added']; ?>
                    </td>
                    <?php /*
                    <td class="text-right">
                    	<?php echo $tran['total_amount']; ?>
                    </td>
                    <td class="text-right">
                    	<?php echo $tran['net_amount']; ?>
                    </td>
                    */ ?>
                    <td class="text-right">
                    	<?php echo $tran['total_item_lines']; ?>
                    </td>
                    <td class="text-left">
                    	<?php echo $tran['received_datetime']; ?>
                    </td>
                    <td class="text-left">
                    	<?php echo $tran['status']; ?>
                    </td>
                    <td class="text-right"><a href="<?php echo $tran['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
	var url = 'index.php?route=warehouse/transactions&token=<?php echo $token; ?>';
	var filter_transaction_type = $('input[name=\'filter_transaction_type\']').val();
	if (filter_transaction_type) {
		url += '&filter_transaction_type=' + encodeURIComponent(filter_transaction_type);
	}
	var filter_transaction_no = $('input[name=\'filter_transaction_no\']').val();
	if (filter_transaction_no) {
		url += '&filter_transaction_no=' + encodeURIComponent(filter_transaction_no);
	}
	location = url;
});
//--></script>
</div>
<?php echo $footer; ?>