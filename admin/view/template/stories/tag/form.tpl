<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <?php include dirname(__FILE__) . '/../resources.tpl'; ?>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($error): ?>
  <div class="warning"><?php echo $error; ?></div>
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
    
      <div id="tb_cp_content" class="tb_subpanels tb_wrap tb_separate">

        <div id="tabs" class="vtabs tb_col tb_w_200">
          <?php if ($tag['name']): ?>
          <p class="h2"><?php echo $text_tags_edit; ?></p>
          <?php else: ?>
          <p class="h2"><?php echo $text_tags_insert; ?></p>
          <?php endif; ?>
          <a href="#tab-general"><?php echo $tab_general; ?></a>
          <a href="#tab-data"><?php echo $tab_data; ?></a>
        </div>

        <form class="tb_col tb_1_1 tb_panel" action="<?php echo $form_action; ?>" method="post" id="form">

          <div id="tab-general">
            <h2><?php echo $tab_general; ?></h2>
            <div class="tb_row_1">
              <label><?php echo $text_language; ?></label>
              <?php if ($action == 'update'): ?>
              <?php foreach ($languages as $language): ?>
                <?php if ($language['language_id'] == $tag['language_id']): ?>
                <?php echo $language['name']; ?>
                <input type="hidden" name="tag[language_id]" value="<?php echo $language['language_id']; ?>" />
                <?php endif; ?>
              <?php endforeach; ?>
              <?php else: ?>
              <select name="tag[language_id]">
                <?php foreach ($languages as $language): ?>
                <option <?php if ($language['language_id'] == $tag['language_id']): ?>selected="selected" <?php endif; ?>value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                <?php endforeach; ?>
              </select>
              <?php endif; ?>

            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_name; ?></label>
              <div class="tb_full clearfix">
                <input type="text" name="tag[name]" value="<?php echo $tag['name']; ?>" />
              </div>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_description; ?></label>
              <div class="tb_full clearfix">
                <textarea name="tag[description]" id="tag_description" rows="5"><?php echo $tag['description']; ?></textarea>
              </div>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_meta_title; ?></label>
              <div class="tb_full clearfix">
                <input type="text" name="tag[meta_title]" value="<?php echo $tag['meta_title']; ?>" />
              </div>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_meta_description; ?></label>
              <div class="tb_full clearfix">
                <textarea name="tag[meta_description]" rows="5"><?php echo $tag['meta_description']; ?></textarea>
              </div>
            </div>
          </div>

          <div id="tab-data">
            <h2><?php echo $tab_data; ?></h2>
            <div class="tb_row_1">
              <label><?php echo $text_keyword; ?></label>
              <input type="text" value="<?php echo $tag['keyword']; ?>" name="tag[keyword]" />
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_status; ?></label>
              <select name="tag[status]">
                <option <?php if ($tag['status'] == '1'): ?>selected="selected" <?php endif; ?>value="1"><?php echo $text_enabled; ?></option>
                <option <?php if ($tag['status'] == '0'): ?>selected="selected" <?php endif; ?>value="0"><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>

          <div class="tb_submit">
            <a class="tb_button tb_h_40 tb_red" onclick="$('#form').submit();"><?php echo $button_submit; ?></a>
            <a class="tb_button tb_h_40 tb_red" onclick="location = '<?php echo $cancel; ?>';"><?php echo $text_back_to_list; ?></a>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $('#tabs a').tabs();
    $('#language a').tabs();
  </script>

</div>


<?php echo $footer; ?>