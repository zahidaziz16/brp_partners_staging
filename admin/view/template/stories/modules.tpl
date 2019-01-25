<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <?php include dirname(__FILE__) . '/resources.tpl'; ?>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

  <?php if ($success): ?>
  <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <div id="tb_cp" class="tb_cp">

    <div id="tb_cp_header">
      <h1><?php echo $heading_title; ?><span id="tb_cp_theme_version">v1.0.0</span></h1>
    </div>

    <div id="tb_cp_content_wrap">

      <div id="tb_cp_main_menu">
        <ul>
          <li><a class="fa fa-file-text" href="index.php?route=stories/index&token=<?php echo $_GET['token']; ?>">Story list</a></li>
          <li><a class="fa fa-tags" href="index.php?route=stories/tag&token=<?php echo $_GET['token']; ?>">Tag list</a></li>
          <li class="tb_selected"><a class="fa fa-puzzle-piece" href="index.php?route=module/stories&token=<?php echo $_GET['token']; ?>">Modules</a></li>
          <li><a class="fa fa-cogs" href="index.php?route=stories/index/settings&token=<?php echo $_GET['token']; ?>">Settings</a></li>
        </ul>
      </div>

      <div id="tb_cp_content" class="tb_panel">

        <h2><?php echo $text_module; ?></h2>

        <div class="tb_actions">
          <a onclick="addModule();" class="tb_button tb_h_34 tb_white"><i class="fa fa-plus tb_f_14"></i> <?php echo $button_add_module; ?></a>
        </div>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table id="module" class="tb_table" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th class="layout" width="150"><?php echo $entry_layout; ?></th>
                <th class="position" width="150"><?php echo $entry_position; ?></th>
                <th class="status" width="150"><?php echo $entry_status; ?></th>
                <th class="order" width="150"><?php echo $entry_sort_order; ?></th>
                <th class="action"></th>
              </tr>
            </thead>
            <tbody>
              <?php $module_row = 0; ?>
              <?php foreach ($modules as $module) { ?>
              <tr id="module-row<?php echo $module_row; ?>">
                <td class="layout"><select class="tb_1_1" name="stories_module[<?php echo $module_row; ?>][layout_id]">
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="position"><select class="tb_1_1" name="stories_module[<?php echo $module_row; ?>][position]">
                    <?php if ($module['position'] == 'content_top') { ?>
                    <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                    <?php } else { ?>
                    <option value="content_top"><?php echo $text_content_top; ?></option>
                    <?php } ?>
                    <?php if ($module['position'] == 'content_bottom') { ?>
                    <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                    <?php } else { ?>
                    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                    <?php } ?>
                    <?php if ($module['position'] == 'column_left') { ?>
                    <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                    <?php } else { ?>
                    <option value="column_left"><?php echo $text_column_left; ?></option>
                    <?php } ?>
                    <?php if ($module['position'] == 'column_right') { ?>
                    <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                    <?php } else { ?>
                    <option value="column_right"><?php echo $text_column_right; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="status"><select class="tb_1_1" name="stories_module[<?php echo $module_row; ?>][status]">
                    <?php if ($module['status']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="order"><input class="tb_1_1" type="text" name="stories_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                <td class="action"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="tb_button tb_h_34 tb_red"><i class="fa fa-trash-o"></i> <?php echo $button_remove; ?></a></td>
              </tr>
              <?php $module_row++; ?>
              <?php } ?>
            </tbody>
          </table>

          <div class="tb_submit">
            <a class="tb_button tb_h_40 tb_red" onclick="$('#form').submit();"><?php echo $button_save; ?></a>
            <a class="tb_button tb_h_40 tb_red" onclick="location = '<?php echo $cancel; ?>';"><?php echo $button_cancel; ?></a>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript"><!--
  var module_row = <?php echo $module_row; ?>;

  function addModule() {
      html =  '<tr id="module-row' + module_row + '">';
      html += '  <td class="layout"><select class="tb_1_1" name="stories_module[' + module_row + '][layout_id]">';
      <?php foreach ($layouts as $layout) { ?>
      html += '    <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
      <?php } ?>
      html += '  </select></td>';
      html += '  <td class="position"><select class="tb_1_1" name="stories_module[' + module_row + '][position]">';
      html += '    <option value="content_top"><?php echo $text_content_top; ?></option>';
      html += '    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
      html += '    <option value="column_left"><?php echo $text_column_left; ?></option>';
      html += '    <option value="column_right"><?php echo $text_column_right; ?></option>';
      html += '  </select></td>';
      html += '  <td class="status"><select class="tb_1_1" name="stories_module[' + module_row + '][status]">';
      html += '    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
      html += '    <option value="0"><?php echo $text_disabled; ?></option>';
      html += '  </select></td>';
      html += '  <td class="order"><input class="tb_1_1" type="text" name="stories_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
      html += '  <td class="action"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="tb_button tb_h_34 tb_red"><i class="fa fa-trash-o"></i> <?php echo $button_remove; ?></a></td>';
      html += '</tr>';

      $('#module tbody').append(html);

      module_row++;
  }
  //--></script>

</div>

<?php echo $footer; ?>