<?php echo $header; ?>
<link href="view/stylesheet/select2.css" rel="stylesheet" />
<script src="view/javascript/select2/select2.min.js">
</script>
<script>
$(document).ready(function() {
    $('.select2').select2();
	/*$('.select2').select2({
  ajax: {
    url: 'controller/marketplace/lazadaCategory/AjaxAPI',
    data: function (params) {
      var query = {
        search: params.term,
        type: 'public'
      }

      // Query parameters will be ?search=[term]&type=public
      return query;
    }
  }
});*/
	//$("#test1").val('58').trigger('change');
	
	 var url_string = window.location.href; // www.test.com?filename=test
    var url = new URL(url_string);
    var token = url.searchParams.get("token");
	$(".select2").select2({
  ajax: {
    url: "index.php?route=marketplace/lazadaCategory/AjaxAPI",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page || 1,
		token: token
      };
    },
    processResults: function (data, params) {

      params.page = params.page || 1;

      return {
        results: data.items,
        pagination: {
          more: (params.page * 30) < data.total_count
        }
      };
    },
    cache: true
  },
  placeholder: 'Search for a repository',
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
});

function formatRepo (repo) {
  if (repo.loading) {
    return repo.text;
  }

  var markup = "<div class='select2-result-repository clearfix'>" +
    "<div class='select2-result-repository__meta'>" +
      "<div class='select2-result-repository__title'>" + repo.full_name + "</div>" +
  "</div></div>";

  return markup;
}

function formatRepoSelection (repo) {
  return repo.full_name || repo.text;
}
});
</script>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		  <a href="<?php echo $sync_category; ?>" data-toggle="tooltip" title="<?php echo $button_sync_category; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></a>
		  <a href="<?php echo $reset_category; ?>" data-toggle="tooltip" title="<?php echo $button_reset_category; ?>" class="btn btn-danger" onclick="return confirm('<?php echo $text_confirm_delete; ?>');"><i class="fa fa-trash"></i></a>
		  <button type="button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category').submit() : false;"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-category">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left" style="width:40%"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'sort_order') { ?>
                    <a><?php echo $column_lazada_category; ?></a>
                    <?php } else { ?>
                    <a><?php echo $column_lazada_category; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($categories) { ?>
                <?php foreach ($categories as $category) { ?>
                <tr>
                  <td class="text-left"><?php echo $category['name']; ?></td>
                  <td class="text-right">
				  <?php //echo $lazada_selectbox; ?>
				  <select name='lazada_select_<?php echo $category['category_id']; ?>' style='width:100%;' class='select2' id='<?php echo $category['category_id']; ?>'>
				  <?php if ($category['lazada_id'] != 0){ ?>
				  <option value='<?php echo $category['lazada_id']; ?>' selected = 'selected'><?php echo $category['lazada_full_path_name']; ?></option>
				  <?php } else { ?>
				  <option value='<?php echo $category['lazada_id']; ?>' selected = 'selected'>Not Set / Disabled</option>
				  <?php }?>
				  </select>
				  
				  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
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
</div>
<?php echo $footer; ?>