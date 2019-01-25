<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <?php include dirname(__FILE__) . '/resources.tpl'; ?>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($error): ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php endif; ?>

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
          <!-- <li><a class="fa fa-puzzle-piece" href="index.php?route=module/stories&token=<?php echo $_GET['token']; ?>">Modules</a></li> -->
          <li class="tb_selected"><a class="fa fa-cogs" href="index.php?route=stories/index/settings&token=<?php echo $_GET['token']; ?>">Settings</a></li>
        </ul>
      </div>

      <div id="tb_cp_content" class="tb_subpanels tb_wrap tb_separate">

        <div id="tabs" class="vtabs tb_col tb_w_200">
          <p class="h2"><?php echo $text_settings; ?></p>
          <?php foreach ($stores as $store): ?>
          <a href="#tab-store-<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></a>
          <?php endforeach; ?>
          <a href="#tab-store-general"><?php echo $text_general; ?></a>
        </div>

        <form class="tb_col tb_1_1 tb_panel" action="<?php echo $action; ?>" method="post" id="form">

          <div id="tab-store-general">
            <h2><?php echo $text_general; ?> <?php echo $text_settings; ?></h2>

            <fieldset>
              <?php if (!defined('TB_SEO_MOD')): ?>
              <div class="tb_row_1">
                <label for="auto_seo_url"><?php echo $text_auto_seo_keyword; ?></label>
                <input type="hidden" name="settings[general][auto_seo_url]" value="0" />
                <label class="tb_toggle"><input id="auto_seo_url" type="checkbox" name="settings[general][auto_seo_url]" value="1"<?php if($settings[0]['auto_seo_url'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
              <div class="tb_row_1">
                <label for="skip_existing_seo_url"><?php echo $text_skip_existing_keywords; ?></label>
                <input type="hidden" name="settings[general][skip_existing_seo_url]" value="0" />
                <label class="tb_toggle"><input id="skip_existing_seo_url" type="checkbox" name="settings[general][skip_existing_seo_url]" value="1"<?php if($settings[0]['skip_existing_seo_url'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              </div>
              <?php endif; ?>
              <div class="tb_row_1">
                <label><?php echo $text_keyword_listing; ?></label>
                <input type="text" name="settings[general][keyword]" value="<?php echo $settings[0]['keyword']; ?>" />
              </div>
            </fieldset>

          </div>

          <?php foreach ($stores as $store): ?>
          <div id="tab-store-<?php echo $store['store_id']; ?>">
            <h2><?php echo $store['name']; ?> <?php echo $text_settings; ?></h2>

            <?php if (count($languages) > 1): ?>
            <div id="store-<?php echo $store['store_id']; ?>-languages" class="tb_langs tb_fly_tabs">
              <?php foreach ($languages as $language): ?>
              <a href="#tab-store-<?php echo $store['store_id']; ?>-language-<?php echo $language['language_id']; ?>">
                <img src="<?php echo $language['image_url']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['code']; ?>
              </a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <fieldset>
              <legend><?php echo $text_articles_listing; ?></legend>
              <?php foreach ($languages as $language): ?>
              <div id="tab-store-<?php echo $store['store_id']; ?>-language-<?php echo $language['language_id']; ?>">
                <div class="tb_row_1">
                  <label><?php echo $text_page_title; ?></label>
                  <span class="tb_language_icon"><img src="<?php echo $language['image_url']; ?>"></span>
                  <div class="tb_full clearfix">
                    <input type="text" name="settings[<?php echo $store['store_id']; ?>][lang][<?php echo $language['language_id']; ?>][page_title]" value="<?php echo $settings[$store['store_id']]['lang'][$language['language_id']]['page_title']; ?>" />
                  </div>
                </div>
                <div class="tb_row_1">
                  <label><?php echo $text_meta_title; ?></label>
                  <span class="tb_language_icon"><img src="<?php echo $language['image_url']; ?>"></span>
                  <div class="tb_full clearfix">
                    <input type="text" name="settings[<?php echo $store['store_id']; ?>][lang][<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo $settings[$store['store_id']]['lang'][$language['language_id']]['meta_title']; ?>" />
                  </div>
                </div>
                <div class="tb_row_1">
                  <label><?php echo $text_meta_description; ?></label>
                  <span class="tb_language_icon"><img src="<?php echo $language['image_url']; ?>"></span>
                  <div class="tb_full clearfix">
                    <textarea name="settings[<?php echo $store['store_id']; ?>][lang][<?php echo $language['language_id']; ?>][meta_description]" rows="5" cols="100"><?php echo $settings[$store['store_id']]['lang'][$language['language_id']]['meta_description']; ?></textarea>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
              <div class="tb_row_1">
                <label><?php echo $text_image_size; ?></label>
                <input class="inline" size="4" type="text" name="settings[<?php echo $store['store_id']; ?>][image_list_width]" value="<?php echo $settings[$store['store_id']]['image_list_width']; ?>" />
                <span class="tb_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" size="4" type="text" name="settings[<?php echo $store['store_id']; ?>][image_list_height]" value="<?php echo $settings[$store['store_id']]['image_list_height']; ?>" />
                <span class="tb_metric">px</span>
              </div>
              <div class="tb_row_1">
                <label><?php echo $text_stories_per_page; ?></label>
                <input size="4" type="text" name="settings[<?php echo $store['store_id']; ?>][stories_per_page]" value="<?php echo $settings[$store['store_id']]['stories_per_page']; ?>" />
              </div>
              <div class="tb_row_1">
                <label><?php echo $text_text_limit; ?></label>
                <input size="4" type="text" name="settings[<?php echo $store['store_id']; ?>][text_limit]" value="<?php echo $settings[$store['store_id']]['text_limit']; ?>" />
                <span class="tb_metric"><?php echo $text_characters; ?></span>
              </div>
              <div class="tb_row_1">
                <label><?php echo $text_thumbnail_position; ?></label>
                <select name="settings[<?php echo $store['store_id']; ?>][thumbnail_position]">
                  <option value="top"<?php if($settings[$store['store_id']]['thumbnail_position'] == 'top') echo ' selected="selected"';?>><?php echo $text_thumbnail_position_top; ?></option>
                  <option value="left"<?php if($settings[$store['store_id']]['thumbnail_position'] == 'left') echo ' selected="selected"';?>><?php echo $text_thumbnail_position_left; ?></option>
                  <option value="right"<?php if($settings[$store['store_id']]['thumbnail_position'] == 'right') echo ' selected="selected"';?>><?php echo $text_thumbnail_position_right; ?></option>
                </select>
              </div>
            </fieldset>

            <fieldset>
              <legend><?php echo $text_article_page; ?></legend>
              <div class="tb_row_1">
                <label><?php echo $text_image_size; ?></label>
                <input class="inline" size="4" type="text" name="settings[<?php echo $store['store_id']; ?>][image_description_width]" value="<?php echo $settings[$store['store_id']]['image_description_width']; ?>" />
                <span class="tb_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" size="4" type="text" name="settings[<?php echo $store['store_id']; ?>][image_description_height]" value="<?php echo $settings[$store['store_id']]['image_description_height']; ?>" />
                <span class="tb_metric">px</span>
              </div>
              <div class="tb_row_1">
                <label><?php echo $text_comments; ?></label>
                <select class="tbFormOptionComments" name="settings[<?php echo $store['store_id']; ?>][comments]">
                  <option value="disabled"<?php if($settings[$store['store_id']]['comments'] == 'disabled') echo ' selected="selected"';?>><?php echo $text_disabled; ?></option>
                  <option value="disqus"<?php if($settings[$store['store_id']]['comments'] == 'disqus') echo ' selected="selected"';?>>Disqus</option>
                  <option value="facebook"<?php if($settings[$store['store_id']]['comments'] == 'facebook') echo ' selected="selected"';?>>Facebook</option>
                </select>
              </div>
              <div class="tb_row_1 tbFormRowDisqusShortname">
                <label><?php echo $text_disqus_shortname; ?></label>
                <input type="text" name="settings[<?php echo $store['store_id']; ?>][disqus_shortname]" value="<?php echo $settings[$store['store_id']]['disqus_shortname']; ?>" />
              </div>
              <div class="tb_row_1">
                <label><?php echo $text_social_share; ?></label>
                <div class="tb_full clearfix">
                  <textarea cols="200" rows="5" name="settings[<?php echo $store['store_id']; ?>][social_share]"><?php echo $settings[$store['store_id']]['social_share']; ?></textarea>
                </div>
              </div>
            </fieldset>

          </div>
          <?php endforeach; ?>

          <div class="tb_submit">
            <a onclick="$('#form').submit();" class="tb_button tb_red tb_h_40"><?php echo $button_submit; ?></a>
          </div>
        </form>

      </div>
    </div>

  </div>

</div>

<script type="text/javascript">
  $('#tabs a').tabs();
  <?php foreach ($stores as $store): ?>
  $('#store-<?php echo $store['store_id']; ?>-languages a').tabs();
  <?php endforeach; ?>
  $('.tbFormOptionComments').bind('change', function() {
      if ($(this).val() == 'disqus') {
          $(this).parent().next('.tbFormRowDisqusShortname').show();
      }
      else {
          $(this).parent().next('.tbFormRowDisqusShortname').hide();
      }
  }).trigger('change');
</script>

<?php echo $footer; ?>