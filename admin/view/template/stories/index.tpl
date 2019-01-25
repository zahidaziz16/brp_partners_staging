<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <?php include dirname(__FILE__) . '/resources.tpl'; ?>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($success): ?>
  <div class="success alert alert-success">
    <i class="fa fa-check-circle"></i>
    <?php echo $success; ?>
    <?php if ($gteOc2): ?>
    <button data-dismiss="alert" class="close" type="button">×</button>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <div id="tb_cp" class="tb_cp">

    <div id="tb_cp_header">
      <h1><?php echo $heading_title; ?><span id="tb_cp_theme_version">v1.0.0</span></h1>
    </div>
    
    <div id="tb_cp_content_wrap">

      <div id="tb_cp_main_menu">
        <ul>
          <li class="tb_selected"><a class="fa fa-file-text" href="index.php?route=stories/index&token=<?php echo $_GET['token']; ?>">Story list</a></li>
          <li><a class="fa fa-tags" href="index.php?route=stories/tag&token=<?php echo $_GET['token']; ?>">Tag list</a></li>
          <!-- <li><a class="fa fa-puzzle-piece" href="index.php?route=module/stories&token=<?php echo $_GET['token']; ?>">Modules</a></li> -->
          <li><a class="fa fa-cogs" href="index.php?route=stories/index/settings&token=<?php echo $_GET['token']; ?>">Settings</a></li>
        </ul>
      </div>

      <div id="tb_cp_content" class="tb_wrap tb_separate">
        
        <div class="tb_panel tb_col tb_1_1">
          <h2><?php echo $text_stories_index_title; ?></h2>

          <div class="tb_actions">
            <?php if (!isset($button_insert)) $button_insert = $button_add; ?>
            <a class="tb_button tb_h_34 tb_white" onclick="location = '<?php echo $action_insert; ?>'"><i class="fa fa-plus"></i> <?php echo $button_insert; ?></a>
            <a class="tb_button tb_h_34 tb_red" onclick="$('#form').submit();"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a>
          </div>

          <form action="<?php echo $action_delete; ?>" method="post" id="form">
            <table class="tb_table" cellpadding="0" cellspacing="0">
              <thead>
                <tr>
                  <th class="select" width="10"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                  <th class="name"><a href="<?php echo $url_sort_title; ?>"<?php if ($sort == 'sd.title'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_title; ?></a></th>
                  <th class="status"><a href="<?php echo $url_sort_status; ?>"<?php if ($sort == 's.status'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_status; ?></a></th>
                  <th class="date"><a href="<?php echo $url_sort_date_added; ?>"<?php if ($sort == 's.date_added'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_date; ?></a></th>
                  <th class="tags"><?php echo $text_tags; ?></th>
                  <th class="action"><?php echo $text_action; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($stories): ?>
                <?php foreach ($stories as $story): ?>
                <tr>
                  <td class="select"><input type="checkbox" name="selected[]" value="<?php echo $story['story_id']; ?>" /></td>
                  <td class="name"><?php echo $story['title']; ?></td>
                  <td class="status"><?php echo $story['status'] ? $text_enabled : $text_disabled; ?></td>
                  <td class="date"><?php echo $story['date_added']; ?></td>
                  <td class="tags"><?php echo $story['tags']; ?></td>
                  <td class="action"><a class="tb_button tb_h_30 tb_white" href="<?php echo $story['edit_link']; ?>"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></a></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </form>
          <div class="pagination"><?php echo $pagination; ?></div>
        </div>

        <div class="tb_panel tb_col tb_w_260">
          <form action="<?php echo $request_url; ?>" method="get" id="filter_form">
            <div class="tb_data_filter tbFilterForm">
              <h3><?php echo $text_stories_filter; ?></h3>
              <?php /*
              <div class="tb_row_2 tb_wrap">
                <div class="tb_col tb_1_2">
                  <input type="text" name="filter_date_from" value="<?php echo $filter_date_from; ?>" placeholder="<?php echo $text_date_from; ?>" />
                </div>
                <div class="tb_col tb_1_2">
                  <input type="text" name="filter_date_to" value="<?php echo $filter_date_to; ?>" placeholder="<?php echo $text_date_to; ?>" />
                </div>
              </div>
              */ ?>
              <div class="tb_row_2">
                <input class="tb_1_1" type="text" name="filter_text" value="<?php echo $filter_text; ?>" placeholder="Text" />
              </div>
              <div class="tb_row_2">
                <select name="filter_status">
                  <option value="">- <?php echo $text_status; ?> -</option>
                  <option value="1"<?php if ($filter_status == '1'): ?> selected="selected"<?php endif; ?>><?php echo $text_enabled; ?></option>
                  <option value="0"<?php if ($filter_status == '0'): ?> selected="selected"<?php endif; ?>><?php echo $text_disabled; ?></option>
                </select>
              </div>
              <div class="tb_row_2">
                <label class="s_checkbox">
                  <input type="checkbox" name="filter_sticky" value="1"<?php if($filter_sticky == 1): ?> checked="checked"<?php endif; ?> />
                  <span><?php echo $text_sticky_only; ?></span>
                </label>
              </div>
              <br />
              <div class="tb_wrap">
                <div class="tb_col tb_1_2">
                  <a class="tb_button tb_h_30 tb_1_1 tb_white tbSubmitFilter"><?php echo $text_apply; ?></a>
                </div>
                <div class="tb_col tb_1_2">
                  <a class="tb_button tb_h_30 tb_1_1 tb_white" href="<?php echo $index_url; ?>"><?php echo $text_reset; ?></a>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>

  </div>

</div>

<script type="text/javascript">
  $("#tb_cp_content").find(".tbSubmitFilter").bind("click", function() {
    window.location = $("#filter_form").attr("action") + "&" + $("#filter_form").serialize();
  });
</script>

<?php if (!empty($refresh_modifications)): ?>
<script>
  $.get("<?php echo $refresh_mods_url; ?>", function() {
    $.get("<?php echo $check_maintenance_url; ?>", function() {
      location.reload();
    });
  });
</script>
<?php return; ?>
<?php endif; ?>

<?php echo $footer; ?>