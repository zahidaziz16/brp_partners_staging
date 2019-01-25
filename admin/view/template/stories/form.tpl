<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <?php include tb_modification(DIR_APPLICATION . 'view/template/stories/resources.tpl'); ?>
  <link href="view/stories/styles/ckeditor.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="view/stories/styles/jquery.tagit.css" />

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
      <h1><?php echo $heading_title; ?></h1>
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

      <div id="tb_cp_content" class="tb_subpanels tb_wrap tb_separate">

        <div id="tabs" class="vtabs tb_col tb_w_200">
          <?php foreach ($languages as $language) {
                $base_lang = $language['language_id'];

                break;
          } ?>
          <?php if (isset($story['lang'][$base_lang]['title'])): ?>
          <p class="h2"><?php echo $text_stories_edit; ?></p>
          <?php else: ?>
          <p class="h2"><?php echo $text_stories_insert; ?></p>
          <?php endif; ?>
          <a href="#tab-general"><?php echo $tab_general; ?></a>
          <a href="#tab-data"><?php echo $tab_data; ?></a>
          <a href="#tab-design"><?php echo $tab_design; ?></a>
          <a onclick="location = '<?php echo $cancel; ?>';"><i class="fa fa-arrow-left tb_f_11"></i> <?php echo $text_back_to_list; ?></a>
        </div>

        <form class="tb_col tb_1_1 tb_panel" action="<?php echo $action; ?>" method="post" id="form">

          <div id="tab-general">
            <h2><?php echo $tab_general; ?></h2>

            <?php if (count($languages) > 1): ?>
            <div id="language" class="tb_langs tb_fly_tabs">
              <?php foreach ($languages as $language): ?>
              <a href="#tab-language-<?php echo $language['language_id']; ?>">
                <img src="<?php echo $language['image_url']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['code']; ?>
              </a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php foreach ($languages as $language): ?>
            <div id="tab-language-<?php echo $language['language_id']; ?>">
              <div class="tb_row_1">
                <label><?php echo $text_title; ?></label>
                <div class="tb_full clearfix">
                  <input type="text" name="story[lang][<?php echo $language['language_id']; ?>][title]" value="<?php if (isset($story['lang'][$language['language_id']])) echo $story['lang'][$language['language_id']]['title']; ?>" />
                </div>
              </div>
              <span class="clear border_ddd"></span>
              <div class="tb_row_1">
                <label><?php echo $text_teaser; ?></label>
                <div class="tb_full clearfix">
                  <textarea name="story[lang][<?php echo $language['language_id']; ?>][teaser]" id="teaser-<?php echo $language['language_id']; ?>" rows="5" cols="100"><?php if (isset($story['lang'][$language['language_id']])) echo $story['lang'][$language['language_id']]['teaser']; ?></textarea>
                </div>
              </div>
              <span class="clear border_ddd"></span>
              <div class="tb_row_1">
                <label><?php echo $text_description; ?></label>
                <div class="tb_full clearfix">
                  <textarea name="story[lang][<?php echo $language['language_id']; ?>][description]" id="description-<?php echo $language['language_id']; ?>" rows="5"><?php if (isset($story['lang'][$language['language_id']])) echo $story['lang'][$language['language_id']]['description']; ?></textarea>
                </div>
              </div>
              <span class="clear border_ddd"></span>
              <div class="tb_row_1">
                <label><?php echo $text_tags; ?></label>
                <div class="tb_full clearfix">
                  <input type="text" value="<?php if (isset($story['lang'][$language['language_id']])) echo $story['lang'][$language['language_id']]['tags']; ?>" name="story[lang][<?php echo $language['language_id']; ?>][tags]" />
                </div>
              </div>
              <span class="clear border_ddd"></span>
              <div class="tb_row_1">
                <label><?php echo $text_meta_title; ?></label>
                <div class="tb_full clearfix">
                  <input type="text" name="story[lang][<?php echo $language['language_id']; ?>][meta_title]" value="<?php if (isset($story['lang'][$language['language_id']])) echo $story['lang'][$language['language_id']]['meta_title']; ?>" />
                </div>
              </div>
              <span class="clear border_ddd"></span>
              <div class="tb_row_1">
                <label><?php echo $text_meta_description; ?></label>
                <div class="tb_full clearfix">
                  <textarea name="story[lang][<?php echo $language['language_id']; ?>][meta_description]" rows="5"><?php if (isset($story['lang'][$language['language_id']])) echo $story['lang'][$language['language_id']]['meta_description']; ?></textarea>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <div id="tab-data">
            <h2><?php echo $tab_data; ?></h2>
            <div class="tb_row_1">
              <label><?php echo $text_stores; ?></label>
              <div class="tb_full clearfix">
                <div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <?php foreach (array_merge(array(0 => array('store_id' => 0, 'name' => $text_default)), $stores) as $store): ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <input type="checkbox" name="story[stores][]" value="<?php echo $store['store_id']; ?>"<?php if (in_array($store['store_id'], $story['stores'])): ?> checked="checked"<?php endif; ?> />
                    <?php echo $store['name']; ?>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label for="story_sticky"><?php echo $text_sticky; ?></label>
              <input type="hidden" name="story[sticky]" value="0" />
              <label class="tb_toggle"><input id="story_sticky" type="checkbox" name="story[sticky]" value="1"<?php if($story['sticky'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_keyword; ?></label>
              <div class="left">
                <input type="text" value="<?php echo $story['keyword']; ?>" name="story[keyword]" size="45" />
                <?php if ($language_keywords): ?>
                    <img src="<?php echo $language_keywords['original']['image_url']; ?>" title="<?php echo $language_keywords['original']['name']; ?>" /><br />
                    <?php foreach($language_keywords['additional'] as $language ): ?>
                    <span class="clear"></span>
                    <br />
                    <input type="text" name="story[language_keyword][<?php echo $language['id']; ?>]" value="<?php echo $language['keyword']; ?>" size="45" /> <img src="<?php echo $language['image_url']; ?>" title="<?php echo $language['name']; ?>" />
                    <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_image; ?></label>
              <div class="tb_thumb">
                <img src="<?php echo $story['thumb']; ?>" id="thumb" />
                <input type="hidden" name="story[image]" value="<?php echo $story['image']; ?>" id="image" />
                <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
              </div>
            </div>
            <span class="clear border_ddd"></span>
            <div class="tb_row_1">
              <label><?php echo $text_status; ?></label>
              <select name="story[status]">
                <option <?php if ($story['status'] == '1'): ?>selected="selected" <?php endif; ?>value="1"><?php echo $text_enabled; ?></option>
                <option <?php if ($story['status'] == '0'): ?>selected="selected" <?php endif; ?>value="0"><?php echo $text_disabled; ?></option>
              </select>
            </div>
          </div>

          <div id="tab-design">
            <h2><?php echo $tab_design; ?></h2>
            <table class="tb_table" cellpadding="0" cellspacing="0">
              <thead>
                <tr>
                  <th class="store"><?php echo $text_stores; ?></th>
                  <th class="layout"><?php echo $text_layout_override; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach (array_merge(array(0 => array('store_id' => 0, 'name' => $text_default)), $stores) as $store): ?>
                <tr>
                  <td class="left"><?php echo $store['name']; ?></td>
                  <td class="left">
                    <select name="story[layouts][<?php echo $store['store_id']; ?>][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout): ?>
                      <option value="<?php echo $layout['layout_id']; ?>"<?php if (isset($story['layouts'][$store['store_id']]) && $story['layouts'][$store['store_id']] == $layout['layout_id']): ?> selected="selected"<?php endif; ?>><?php echo $layout['name']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="tb_submit">
            <a onclick="$('#form').submit();" class="tb_button tb_h_40 tb_red"><?php echo $button_submit; ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="tb_button tb_h_40 tb_red"><?php echo $button_cancel; ?></a>
          </div>

        </form>
      </div>
    </div>
  </div>

</div>

<?php if ($gteOc2): ?>
<script type="text/javascript" src="view/stories/javascript/jquery-ui-1.10.4.custom.min.js"></script>
<?php endif; ?>
<script type="text/javascript" src="view/stories/javascript/jquery.tag-it.min.js"></script>
<script type="text/javascript">
  $('#tabs a').tabs();
  $('#language a').tabs();

  <?php foreach ($languages as $language): ?>
  $("#tab-general").find("input[name='story[lang][<?php echo $language['language_id']; ?>][tags]']").tagit({
    allowSpaces: true
    <?php if (isset($all_tags[$language['language_id']]) && $all_tags[$language['language_id']]): ?>
    , availableTags: [<?php echo $all_tags[$language['language_id']]; ?>]
    <?php endif; ?>
  });
  <?php endforeach; ?>
</script>
<script src="//cdn.ckeditor.com/4.5.11/full/ckeditor.js"></script>
<script type="text/javascript">

<?php
$domain = HTTP_SERVER;
$domain = str_replace('https:', '', $domain);
$domain = str_replace('http:', '', $domain);
?>

CKEDITOR.plugins.addExternal( 'lineutils', '<?php echo $domain; ?>view/stories/javascript/ckeditor/plugins/lineutils/', 'plugin.js' );
CKEDITOR.plugins.addExternal( 'widget', '<?php echo $domain; ?>view/stories/javascript/ckeditor/plugins/widget/', 'plugin.js' );
CKEDITOR.plugins.addExternal( 'image2', '<?php echo $domain; ?>view/stories/javascript/ckeditor/plugins/image2/', 'plugin.js' );

<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description-<?php echo $language['language_id']; ?>', {
  customConfig:              '<?php echo $domain; ?>view/stories/javascript/ckeditor/custom/config.js',
  contentsCss:               '<?php echo $domain; ?>view/stories/javascript/ckeditor/custom/styles.css',
  filebrowserBrowseUrl:      '<?php echo $fileManager_url; ?>',
  filebrowserImageBrowseUrl: '<?php echo $fileManager_url; ?>',
  filebrowserImageUploadUrl: null,
  filebrowserImageUploadUrl: null,
  extraPlugins:              'lineutils',
  extraPlugins:              'widget',
  extraPlugins:              'image2'
});
<?php } ?>

</script>

<?php echo $footer; ?>