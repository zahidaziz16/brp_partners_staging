<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <?php include dirname(__FILE__) . '/../resources.tpl'; ?>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
  </div>

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
          <li class="tb_selected"><a class="fa fa-tags" href="index.php?route=stories/tag&token=<?php echo $_GET['token']; ?>">Tag list</a></li>
          <!-- <li><a class="fa fa-puzzle-piece" href="index.php?route=module/stories&token=<?php echo $_GET['token']; ?>">Modules</a></li> -->
          <li><a class="fa fa-cogs" href="index.php?route=stories/index/settings&token=<?php echo $_GET['token']; ?>">Settings</a></li>
        </ul>
      </div>
    
      <div id="tb_cp_content" class="tb_panel">

        <h2><?php echo $text_tags; ?></h2>

        <div class="tb_actions">
          <?php if (!isset($button_insert)) $button_insert = $button_add; ?>
          <a class="tb_button tb_h_34 tb_white" onclick="location = '<?php echo $action_insert; ?>'"><i class="fa fa-plus"></i> <?php echo $button_insert; ?></a>
          <a class="tb_button tb_h_34 tb_red" onclick="$('#form').submit();"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a>
        </div>

        <form action="<?php echo $action_delete; ?>" method="post" id="form">
          <table class="tb_table" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th class="select" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                <th class="name"><a href="<?php echo $url_sort_name; ?>"<?php if ($sort == 'st.name'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_title; ?></a></th>
                <th class="language"><a href="<?php echo $url_sort_language; ?>"<?php if ($sort == 'st.language_id'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_language; ?></a></th>
                <th class="status"><a href="<?php echo $url_sort_status; ?>"<?php if ($sort == 'st.status'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_status; ?></a></th>
                <th class="date"><a href="<?php echo $url_sort_date_added; ?>"<?php if ($sort == 'st.date_added'): ?> class="<?php echo $order; ?>"<?php endif; ?>><?php echo $text_date; ?></a></th>
                <th class="action"><?php echo $text_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($tags): ?>
              <?php foreach ($tags as $tag): ?>
              <tr>
                <td class="select"><input type="checkbox" name="selected[]" value="<?php echo $tag['tag_id']; ?>" /></td>
                <td class="name"><?php echo $tag['name']; ?></td>
                <td class="language"><?php echo $tag['language_name']; ?></td>
                <td class="status"><?php echo $tag['status'] ? $text_enabled : $text_disabled; ?></td>
                <td class="date"><?php echo $tag['date_added']; ?></td>
                <td class="action"><a class="tb_button tb_h_30 tb_white" href="<?php echo $tag['edit_link']; ?>"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></a></td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
      </div>
    </div>

  </div>

</div>

<?php echo $footer; ?>