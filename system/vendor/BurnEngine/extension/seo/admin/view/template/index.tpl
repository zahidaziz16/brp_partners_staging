<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#extension_seo_store">Store</a></li>
      <li><a href="#extension_seo_generator">Generator</a></li>
      <li><a href="#extension_seo_editor">Editor</a></li>
      <li><a href="#extension_seo_general">General</a></li>
      <?php $tbData->slotFlag('tb\extension.seo.main_navigation'); ?>
    </ul>
  </div>

  <div id="extension_seo_store" class="tb_subpanel">
    <h2>Store settings</h2>
    <?php if (!$seo_url_enabled): ?>
    <div class="s_server_msg s_msg_yellow clear">
      <p class="s_icon_16 s_exclamation_16">The language functionality has been disabled because <strong>Use SEO URLs</strong> OpenCart setting is turned off.</p>
    </div>
    <?php endif; ?>
    <fieldset<?php if (!$seo_url_enabled): ?> class="tb_disabled"<?php endif; ?>>
      <legend>Languages</legend>
      <div class="tb_wrap tb_gut_30">
        <div class="tb_col tb_1_2">
          <div class="s_row_1"<?php if (count($tbData->enabled_languages) == 1): ?> style="display: none;"<?php endif; ?>>
            <label for="seo_multilingual_keywords">Multilingual Keywords</label>
            <input type="hidden" name="seo[multilingual_keywords]" value="0" />
            <label class="tb_toggle"><input id="seo_multilingual_keywords" type="checkbox" name="seo[multilingual_keywords]" value="1"<?php if($seo['multilingual_keywords'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1"<?php if (count($tbData->enabled_languages) == 1): ?> style="display: none;"<?php endif; ?>>
            <label for="seo_language_prefix">Language url prefix</label>
            <input type="hidden" name="seo[language_prefix]" value="0" />
            <label class="tb_toggle"><input id="seo_language_prefix" type="checkbox" name="seo[language_prefix]" value="1"<?php if($seo['language_prefix'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1"<?php if (count($tbData->enabled_languages) == 1): ?> style="display: none;"<?php endif; ?>>
            <label for="seo_default_language_prefix">Default language url prefix</label>
            <input type="hidden" name="seo[default_language_prefix]" value="0" />
            <label class="tb_toggle"><input id="seo_default_language_prefix" type="checkbox" name="seo[default_language_prefix]" value="1"<?php if($seo['default_language_prefix'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1">
            <label for="seo_hreflang_tag">Use `hreflang` Tag</label>
            <input type="hidden" name="seo[hreflang_tag]" value="0" />
            <label class="tb_toggle"><input id="seo_hreflang_tag" type="checkbox" name="seo[hreflang_tag]" value="1"<?php if($seo['hreflang_tag'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
        </div>
        <div class="tb_col tb_1_2">
          <?php if (count($tbData->enabled_languages) > 1): ?>
          <?php foreach ($seo['language_prefix_codes'] as $code_name => $code_value): ?>
          <div class="s_row_1 tbLanguagePrefix<?php if ($seo['language_prefix'] == '0'): ?> tb_disabled<?php endif; ?>">
            <label><?php echo $tbData->enabled_languages[$code_name]['name']; ?> prefix</label>
            <input type="text" name="seo[language_prefix_codes][<?php echo $code_name; ?>]" value="<?php echo $code_value; ?>" size="5" />
            <span class="s_language_icon"><img src="<?php echo $tbData->enabled_languages[$code_name]['url']; ?>/<?php echo $tbData->enabled_languages[$code_name]['image']; ?>" /></span>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </fieldset>
    <?php if (count($tbData->enabled_languages) > 1): ?>
    <fieldset class="tb_tabs tb_tabs_inline tbSeoStoreMetaTabs">
      <legend>Store metadata</legend>
      <div class="tb_tabs_nav s_auto_cols s_h_30">
        <ul class="tb_tabs_nav clearfix">
          <?php foreach ($tbData->enabled_languages as $language): ?>
          <li class="s_language" data-language_code="<?php echo $language['code']; ?>">
            <a href="#extension_seo_store_meta_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
              <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
              <?php echo $language['code']; ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php foreach ($tbData->enabled_languages as $language): ?>
      <div id="extension_seo_store_meta_language_<?php echo $language['code']; ?>">
        <div class="s_row_1">
          <label>Meta Title</label>
          <div class="s_full clearfix">
            <input type="text" name="seo[store_meta][<?php echo $language['code']; ?>][title]" value="<?php echo $seo['store_meta'][$language['code']]['title']; ?>" />
          </div>
        </div>
        <div class="s_row_1">
          <label>Meta Description</label>
          <div class="s_full clearfix">
            <textarea name="seo[store_meta][<?php echo $language['code']; ?>][description]" rows="3"><?php echo $seo['store_meta'][$language['code']]['description']; ?></textarea>
          </div>
        </div>
        <div class="s_row_1">
          <label>Meta Keywords</label>
          <div class="s_full clearfix">
            <input type="text" name="seo[store_meta][<?php echo $language['code']; ?>][keyword]" value="<?php echo $seo['store_meta'][$language['code']]['keyword']; ?>" size="5" />
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </fieldset>
    <?php endif; ?>
    <fieldset>
      <legend>Product structured data</legend>
      <div class="tb_wrap tb_gut_30">
        <div class="tb_col tb_1_2">
          <div class="s_row_1">
            <label for="seo_google_microdata">Google Microdata</label>
            <input type="hidden" name="seo[google_microdata]" value="0" />
            <label class="tb_toggle"><input id="seo_google_microdata" type="checkbox" name="seo[google_microdata]" value="1"<?php if($seo['google_microdata'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1">
            <label for="seo_facebook_opengraph">Facebook Open Graph</label>
            <input type="hidden" name="seo[facebook_opengraph]" value="0" />
            <label class="tb_toggle"><input id="seo_facebook_opengraph" type="checkbox" name="seo[facebook_opengraph]" value="1"<?php if($seo['facebook_opengraph'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1">
            <label for="seo_twitter_card">Twitter Card</label>
            <input type="hidden" name="seo[twitter_card]" value="0" />
            <label class="tb_toggle"><input id="seo_twitter_card" type="checkbox" name="seo[twitter_card]" value="1"<?php if($seo['twitter_card'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <legend>Other</legend>
      <div class="s_row_1">
        <label for="seo_pretty_urls">Pretty System URLs</label>
        <input type="hidden" name="seo[pretty_urls]" value="0" />
        <label class="tb_toggle"><input id="seo_pretty_urls" type="checkbox" name="seo[pretty_urls]" value="1"<?php if($seo['pretty_urls'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php if ($seo_url_enabled): ?>
      <div class="s_row_1">
        <label for="seo_pretty_urls">Redirect to SEO urls</label>
        <input type="hidden" name="seo[redirect_to_seo]" value="0" />
        <label class="tb_toggle"><input id="seo_redirect_to_seo" type="checkbox" name="seo[redirect_to_seo]" value="1"<?php if($seo['redirect_to_seo'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        <p class="s_help right">Redirect to the corresponding seo url if the request is non-seo (index.php?route=...).</p>
      </div>
      <?php endif; ?>
    </fieldset>
    <div class="s_submit clearfix">
      <div class="left">
        <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
      </div>
      <div class="right">
        <a class="s_button s_red s_h_40 tbSaveSeoUrlSettings">Save Settings</a>
      </div>
    </div>
  </div>

  <div id="extension_seo_generator" class="tb_subpanel">
    <div class="tb_tabs tb_tabs_inline">

      <h2>Generator</h2>

      <div class="tb_tabs_nav s_h_40">
        <ul class="clearfix">
          <li><a href="#extension_seo_generator_products">Products</a></li>
          <li><a href="#extension_seo_generator_categories">Categories</a></li>
          <li><a href="#extension_seo_generator_information">Information Pages</a></li>
          <li><a href="#extension_seo_generator_manufacturers">Manufacturers</a></li>
          <?php if ($has_stories): ?>
          <li><a href="#extension_seo_generator_stories">Stories</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div id="extension_seo_generator_products">
        <?php foreach ($generator_items_data['product'] as $generator_data): ?>
          <?php require(tb_modification(dirname(__FILE__) . '/seo_generator_item.tpl')); ?>
        <?php endforeach; ?>
      </div>

      <div id="extension_seo_generator_categories">
        <?php foreach ($generator_items_data['category'] as $generator_data): ?>
          <?php require(tb_modification(dirname(__FILE__) . '/seo_generator_item.tpl')); ?>
        <?php endforeach; ?>
      </div>

      <div id="extension_seo_generator_information">
        <?php foreach ($generator_items_data['information'] as $generator_data): ?>
        <?php require(tb_modification(dirname(__FILE__) . '/seo_generator_item.tpl')); ?>
        <?php endforeach; ?>
      </div>

      <div id="extension_seo_generator_manufacturers">
        <?php foreach ($generator_items_data['manufacturer'] as $generator_data): ?>
        <?php require(tb_modification(dirname(__FILE__) . '/seo_generator_item.tpl')); ?>
        <?php endforeach; ?>
      </div>

      <?php if ($has_stories): ?>
      <div id="extension_seo_generator_stories">
        <?php foreach ($generator_items_data['story'] as $generator_data): ?>
        <?php require(tb_modification(dirname(__FILE__) . '/seo_generator_item.tpl')); ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="s_submit clearfix">
      <div class="left">
        <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
      </div>
      <div class="right">
        <a class="s_button s_red s_h_40 tbSaveSeoUrlSettings">Save Settings</a>
      </div>
    </div>
  </div>

  <div id="extension_seo_editor" class="tb_subpanel">

    <h2>Editor</h2>

    <div class="tb_tabs tb_tabs_inline">
      <div class="tb_tabs_nav s_h_40">
        <ul>
          <li aria-controls="extension_seo_editor_products"><a href="<?php echo $tbUrl->generate('default/editor', '&item=product'); ?>">Products</a></li>
          <li aria-controls="extension_seo_editor_categories"><a href="<?php echo $tbUrl->generate('default/editor', '&item=category'); ?>">Categories</a></li>
          <li aria-controls="extension_seo_editor_information"><a href="<?php echo $tbUrl->generate('default/editor', '&item=information'); ?>">Information</a></li>
          <li aria-controls="extension_seo_editor_manufacturers"><a href="<?php echo $tbUrl->generate('default/editor', '&item=manufacturer'); ?>">Manufacturers</a></li>
          <!-- <li><a href="#extension_seo_editor_blog">Blog</a></li> -->
        </ul>
      </div>

      <div id="extension_seo_editor_products"      class="tb_tabs tb_fly_tabs tbLanguageTabs" data-item="product"></div>
      <div id="extension_seo_editor_categories"    class="tb_tabs tb_fly_tabs tbLanguageTabs" data-item="category"></div>
      <div id="extension_seo_editor_information"   class="tb_tabs tb_fly_tabs tbLanguageTabs" data-item="information"></div>
      <div id="extension_seo_editor_manufacturers" class="tb_tabs tb_fly_tabs tbLanguageTabs" data-item="manufacturer"></div>
      <!-- <div id="extension_seo_editor_blog"></div> -->

    </div>
    <div class="s_submit clearfix">
      <div class="left">
        <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
      </div>
    </div>
  </div>

  <div id="extension_seo_general" class="tb_subpanel">
    <h2>General settings</h2>
    <div class="s_row_1"<?php if (count($tbData->enabled_languages) == 1): ?> style="display: none;"<?php endif; ?>>
      <label for="seo_multilingual_keyword">Fallback SEO Keyword</label>
      <input type="hidden" name="seo_general[multilingual_keyword]" value="0" />
      <label class="tb_toggle"><input id="seo_multilingual_keyword" type="checkbox" name="seo_general[multilingual_keyword]" value="1"<?php if($seo_general['multilingual_keyword'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      <p class="s_help right">Use default language value, if keyword field is empty for additional languages.</p>
    </div>
    <div class="s_row_1">
      <label for="seo_autofill_title_desc">Autofill title and description</label>
      <input type="hidden" name="seo_general[autofill_title_desc]" value="0" />
      <label class="tb_toggle"><input id="seo_autofill_title_desc" type="checkbox" name="seo_general[autofill_title_desc]" value="1"<?php if($seo_general['autofill_title_desc'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>

    <div class="s_submit clearfix">
      <div class="left">
        <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
      </div>
      <div class="right">
        <a class="s_button s_red s_h_40 tbSaveSeoUrlSettings">Save Settings</a>
      </div>
    </div>
  </div>

</div>

<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/jqueryui-editable/css/jqueryui-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/jqueryui-editable/js/jqueryui-editable.min.js"></script>
<script src="<?php echo $extension_admin_resource_url; ?>javascript/seo.js"></script>

