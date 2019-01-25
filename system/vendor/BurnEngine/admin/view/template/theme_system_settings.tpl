<?php $system = $theme_settings['system']; ?>
<div class="tb_tabs tb_subpanel_tabs">

  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <li><a href="#system_settings_cache">Cache</a></li>
      <li><a href="#system_settings_performance">Page load</a></li>
      <li><a href="#system_settings_compatibility">Compatibility</a></li>
      <li><a href="#system_settings_info">Info</a></li>
      <?php // <li><a href="#system_settings_pages">Pages</a></li> ?>
    </ul>
  </div>

  <div id="system_settings_cache" class="tb_subpanel tb_has_sidebar">
    <div class="tb_tabs clearfix">

      <div class="tb_tabs_nav s_box_1">
        <h3>Cache</h3>
        <div class="s_actions s_pt_5">
          <input type="hidden" name="system[cache_enabled]" value="0" />
          <label class="tb_toggle tb_toggle_small"><input id="system_settings_cache_enabled" type="checkbox" name="system[cache_enabled]" value="1"<?php if($system['cache_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <ul class="tb_nav clearfix">
          <li><a href="#system_settings_cache_general_tab">General</a></li>
          <?php if ($system['tb_optimizations_mod']): ?>
          <li><a href="#system_settings_cache_sql_tab">Database</a></li>
          <?php endif; ?>
          <li><a href="#system_settings_cache_content_tab">Content blocks</a></li>
        </ul>
        <div class="align_center s_pt_20 tbClearCache" style="margin: 0 -10px;">
          <div class="btn-group dropdown">
            <a class="s_button s_h_30 s_white s_icon_10 s_trash_10 tbClearAllCache" href="<?php echo $tbUrl->generate('default/clearCache'); ?>">Clear all</a>
            <div class="btn-group">
              <a class="s_button s_h_30 s_white s_split fa-caret-down" href="javascript:;"></a>
              <ul class="s_submenu ui-autocomplete">
                <li><a href="<?php echo $tbUrl->generate('default/clearDbCache'); ?>" class="tbClearDatabaseCache">Database cache</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div id="system_settings_cache_general_tab">
        <fieldset>
          <h2>General cache</h2>
          <div class="s_row_1 tbSetting">
            <label for="system_settings_cache_js">Javascript</label>
            <input type="hidden" name="system[cache_js]" value="0" />
            <label class="tb_toggle"><input id="system_settings_cache_js" type="checkbox" name="system[cache_js]" value="1"<?php if($system['cache_js'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tbSetting">
            <label for="system_settings_cache_styles">CSS</label>
            <input type="hidden" name="system[cache_styles]" value="0" />
            <label class="tb_toggle"><input id="system_settings_cache_styles" type="checkbox" name="system[cache_styles]" value="1"<?php if($system['cache_styles'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tbSetting">
            <label for="system_settings_cache_settings">Configuration</label>
            <input type="hidden" name="system[cache_settings]" value="0" />
            <label class="tb_toggle"><input id="system_settings_cache_settings" type="checkbox" name="system[cache_settings]" value="1"<?php if($system['cache_settings'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tbSetting">
            <label for="system_settings_cache_menu">Menu</label>
            <input type="hidden" name="system[cache_menu]" value="0" />
            <label class="tb_toggle"><input id="system_settings_cache_menu" type="checkbox" name="system[cache_menu]" value="1"<?php if($system['cache_menu'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tbSetting" style="display: none">
            <label for="system_settings_cache_classes" >System classes</label>
            <input type="hidden" name="system[cache_classes]" value="0" />
            <label class="tb_toggle"><input id="system_settings_cache_classes" type="checkbox" name="system[cache_classes]" value="1"<?php if($system['cache_classes'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
        </fieldset>
      </div>

      <?php if ($system['tb_optimizations_mod']): ?>
      <div id="system_settings_cache_sql_tab">
        <h2>Database</h2>
        <div class="s_row_1 tbSetting">
          <label for="system_settings_cache_db">Enable database cache</label>
          <input type="hidden" name="system[cache_db]" value="0" />
          <label class="tb_toggle"><input id="system_settings_cache_db" type="checkbox" name="system[cache_db]" value="1"<?php if($system['cache_db'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <fieldset>
          <legend>Cache items</legend>
          <?php foreach ($tbData->optimizations_database['cache_items'] as $cache_key => $cache_value): ?>
          <div class="s_row_1 tb_live_row_1 tbSetting">
            <label for="system_settings_cache_<?php echo $cache_key; ?>"><?php echo $cache_value['label']; ?></label>
            <input type="hidden" name="system[cache_<?php echo $cache_key; ?>]" value="0" />
            <label class="tb_toggle"><input id="system_settings_cache_<?php echo $cache_key; ?>" type="checkbox" name="system[cache_<?php echo $cache_key; ?>]" value="1"<?php if($system['cache_' . $cache_key] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input class="s_spinner" type="text" name="system[cache_<?php echo $cache_key; ?>_ttl]" value="<?php echo $system['cache_' . $cache_key . '_ttl']; ?>" size="7" min="1" />
            <span class="s_metric">min</span>
          </div>
          <?php endforeach; ?>

        </fieldset>
      </div>
      <?php endif; ?>

      <div id="system_settings_cache_content_tab">
        <h2>Content cache</h2>
        <div class="s_row_1 tbSetting">
          <label for="system_settings_cache_content">Enable content cache</label>
          <input type="hidden" name="system[cache_content]" value="0" />
          <label class="tb_toggle"><input id="system_settings_cache_content" type="checkbox" name="system[cache_content]" value="1"<?php if($system['cache_content'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>

        <fieldset>
          <legend>Theme blocks</legend>
          <div class="tb_wrap tb_gut_30 s_mt_-20 left">
            <?php $has_module_blocks = false; ?>
            <?php foreach ($tbData->cache_widget_names as $class_name => $widget_name): ?>
            <?php if (0 !== strpos($class_name, 'oc_')): ?>
            <div class="s_row_1 tb_col tb_1_2 s_mt_20 tbSetting">
              <label for="system_settings_cache_content"><?php echo $widget_name; ?></label>
              <input type="hidden" name="system[cache_widgets][<?php echo $class_name; ?>][enabled]" value="0" />
              <label class="tb_toggle"><input id="system_settings_cache_content" type="checkbox" name="system[cache_widgets][<?php echo $class_name; ?>][enabled]" value="1"<?php if($system['cache_widgets'][$class_name]['enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input class="s_spinner" type="text" name="system[cache_widgets][<?php echo $class_name; ?>][ttl]" value="<?php echo $system['cache_widgets'][$class_name]['ttl']; ?>" size="7" />
              <span class="s_metric">min</span>
            </div>
            <?php endif; ?>
            <?php if (0 === strpos($class_name, 'oc_')) $has_module_blocks = true; ?>
            <?php endforeach; ?>
          </div>
        </fieldset>

        <?php if ($has_module_blocks): ?>
        <fieldset>
          <legend>Module blocks</legend>
          <div class="tb_wrap tb_gut_30 s_mt_-20<?php if (count($tbData->cache_widget_names) > 1) echo ' left'; ?>">
            <?php foreach ($tbData->cache_widget_names as $class_name => $widget_name): ?>
            <?php if (0 === strpos($class_name, 'oc_')): ?>
            <div class="s_row_1 tb_col tb_1_2 s_mt_20 tbSetting">
              <label for="system_settings_cache_content"><?php echo $widget_name; ?></label>
              <input type="hidden" name="system[cache_widgets][<?php echo $class_name; ?>][enabled]" value="0" />
              <label class="tb_toggle"><input id="system_settings_cache_content" type="checkbox" name="system[cache_widgets][<?php echo $class_name; ?>][enabled]" value="1"<?php if($system['cache_widgets'][$class_name]['enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input class="s_spinner" type="text" name="system[cache_widgets][<?php echo $class_name; ?>][ttl]" value="<?php echo $system['cache_widgets'][$class_name]['ttl']; ?>" size="7" min="1" />
              <span class="s_metric">min</span>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <?php endif; ?>

      </div>

    </div>
  </div>

  <div id="system_settings_performance" class="tb_subpanel tb_has_sidebar clearfix">
    <div class="tb_tabs">
      <div class="tb_tabs_nav s_box_1">
        <h3>Page load</h3>
        <ul class="tb_nav clearfix">
          <li><a href="#system_settings_performance_general">General</a></li>
          <li><a href="#system_settings_performance_critical_css">Critical CSS</a></li>
        </ul>
      </div>
      <div id="system_settings_performance_general">
        <h2>Page Load Performance</h2>
        <div class="s_row_1">
          <label for="system_settings_image_lazyload">Image lazy loading</label>
          <input type="hidden" name="system[image_lazyload]" value="0" />
          <label class="tb_toggle"><input id="system_settings_image_lazyload" type="checkbox" name="system[image_lazyload]" value="1"<?php if($system['image_lazyload'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <div class="s_row_1 tb_wrap">
          <div class="s_row_1 tb_col tb_1_2">
            <label for="system_settings_bg_lazyload">Bg lazy loading</label>
            <input type="hidden" name="system[bg_lazyload]" value="0" />
            <label class="tb_toggle"><input id="system_settings_bg_lazyload" type="checkbox" name="system[bg_lazyload]" value="1"<?php if($system['bg_lazyload'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tb_col tb_1_2">
            <label class="inline" for="system_settings_bg_lazyload_expand">Below the fold</label>
            <input class="s_spinner" type="text" name="system[bg_lazyload_expand]" value="<?php echo $system['bg_lazyload_expand']; ?>" size="7" min="-1000"  max="1000" step="10" />
            <span class="s_metric">px</span>
          </div>
        </div>
        <div class="s_row_1 tb_wrap">
          <div class="s_row_1 tb_col tb_1_2">
            <label for="system_settings_js_lazyload">Script lazy loading</label>
            <input type="hidden" name="system[js_lazyload]" value="0" />
            <label class="tb_toggle"><input id="system_settings_js_lazyload" type="checkbox" name="system[js_lazyload]" value="1"<?php if($system['js_lazyload'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tb_col tb_1_2">
            <label class="inline" for="system_settings_js_lazyload_expand">Below the fold</label>
            <input class="s_spinner" type="text" name="system[js_lazyload_expand]" value="<?php echo $system['js_lazyload_expand']; ?>" size="7" min="-1000" max="1000" step="10" />
            <span class="s_metric">px</span>
          </div>
        </div>
        <div class="s_row_1">
          <label for="system_settings_optimize_js_load">Optimize JS</label>
          <input type="hidden" name="system[optimize_js_load]" value="0" />
          <label class="tb_toggle"><input id="system_settings_optimize_js_load" type="checkbox" name="system[optimize_js_load]" value="1"<?php if($system['optimize_js_load'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <div class="s_row_1">
          <label for="system_settings_defer_js_load">Defer JS</label>
          <input type="hidden" name="system[defer_js_load]" value="0" />
          <label class="tb_toggle"><input id="system_settings_defer_js_load" type="checkbox" name="system[defer_js_load]" value="1"<?php if($system['defer_js_load'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <div class="s_row_1 tbSetting">
          <label for="system_settings_combine_js">Combine JS</label>
          <input type="hidden" name="system[combine_js]" value="0" />
          <label class="tb_toggle"><input id="system_settings_combine_js" type="checkbox" name="system[combine_js]" value="1"<?php if($system['combine_js'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <div class="s_row_1">
          <label for="system_settings_minify_js">Minify JS</label>
          <input type="hidden" name="system[minify_js]" value="0" />
          <label class="tb_toggle"><input id="system_settings_minify_js" type="checkbox" name="system[minify_js]" value="1"<?php if($system['minify_js'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <div class="s_row_1">
          <label for="system_settings_minify_css">Minify CSS</label>
          <input type="hidden" name="system[minify_css]" value="0" />
          <label class="tb_toggle"><input id="system_settings_minify_css" type="checkbox" name="system[minify_css]" value="1"<?php if($system['minify_css'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
        <div class="s_row_1">
          <label for="system_settings_minify_html">Minify HTML</label>
          <input type="hidden" name="system[minify_html]" value="0" />
          <label class="tb_toggle"><input id="system_settings_minify_html" type="checkbox" name="system[minify_html]" value="1"<?php if($system['minify_html'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
        </div>
      </div>
      <div id="system_settings_performance_critical_css">
        <h2>Critical CSS</h2>
        <div class="s_full clearfix">
          <textarea name="system[critical_css]" rows="15"><?php echo $system['critical_css']; ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <div id="system_settings_compatibility" class="tb_subpanel">

    <h2>Compatibility</h2>

    <fieldset>
      <legend class="s_mb_0">Javascript</legend>
      <?php if (!$tbData['gteOc2']): ?>
      <div class="s_row_1 tb_live_row_1">
        <label for="system_compatibility_load_colorbox">Load colorbox.js</label>
        <input type="hidden" name="system[compatibility_colorbox]" value="0" />
        <label class="tb_toggle"><input id="system_compatibility_load_colorbox" type="checkbox" name="system[compatibility_colorbox]" value="1"<?php if($system['compatibility_colorbox'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php endif; ?>
      <?php if ($tbData['gteOc2']): ?>
      <div class="s_row_1 tb_live_row_1">
        <label for="system_compatibility_load_moment_js">Load moment.js</label>
        <input type="hidden" name="system[compatibility_moment_js]" value="0" />
        <label class="tb_toggle"><input id="system_compatibility_load_moment_js" type="checkbox" name="system[compatibility_moment_js]" value="1"<?php if($system['compatibility_moment_js'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php endif; ?>
    </fieldset>

    <?php if ($system['tb_optimizations_mod'] && !empty($tbData->optimizations_compatibility)): ?>
    <fieldset>
      <legend class="s_mb_0">OpenCart Performance +</legend>
      <?php foreach ($tbData->optimizations_compatibility as $compatibility_key => $compatibility_value): ?>
      <div class="s_row_1 tb_live_row_1">
        <label for="system_compatibility_<?php echo $compatibility_key; ?>"><?php echo $compatibility_value['label']; ?></label>
        <input type="hidden" name="system[compatibility_<?php echo $compatibility_key; ?>]" value="0" />
        <label class="tb_toggle"><input id="system_compatibility_<?php echo $compatibility_key; ?>" type="checkbox" name="system[compatibility_<?php echo $compatibility_key; ?>]" value="1"<?php if($system['compatibility_' . $compatibility_key] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
      </div>
      <?php endforeach; ?>
    </fieldset>
    <?php endif; ?>

    <fieldset>
      <legend class="s_mb_0">Disable page load optimization</legend>
      <div class="s_row_1 tb_live_row_1">
        <label for="system_compatibility_exclude_routes">Routes</label>
        <div class="s_full clearfix">
          <textarea id="system_compatibility_exclude_routes" name="system[optimize_exclude]" rows="6"><?php echo $system['optimize_exclude']; ?></textarea>
          <p class="s_help">Place each route on a new row.</p>
        </div>
      </div>
    </fieldset>

    <!-- mods -->

  </div>

  <div id="system_settings_info" class="tb_subpanel">

    <h2>Info</h2>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>Current store</strong></div>
      <div class="tb_col tb_4_5"><?php echo $tbData->current_store['name']; ?></div>
    </div>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>Current theme</strong></div>
      <div class="tb_col tb_4_5"><?php echo $tbData->theme_info['name']; ?> v<?php echo $tbData->theme_info['version']; ?> <?php if (!empty($theme_settings['install_info']['install_date'])): ?><small class="s_small">/installed on <?php echo $theme_settings['install_info']['install_date']; ?>/</small><?php endif; ?></div>
    </div>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>BurnEngine version</strong></div>
      <div class="tb_col tb_4_5"><?php echo $tbData->engine_version; ?> <?php if (!empty($tbData->engine_log_install['install_date'])): ?><small class="s_small">/installed on <?php echo $tbData->engine_log_install['date']; ?>/</small><?php endif; ?></div>
    </div>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>OpenCart version</strong></div>
      <div class="tb_col tb_4_5"><?php echo VERSION; ?></div>
    </div>

    <?php if(class_exists('VQMod') && property_exists('VQMod', '_vqversion')): ?>
    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>vQmod version</strong></div>
      <div class="tb_col tb_4_5"><?php echo VQMod::$_vqversion; ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($_SERVER["SERVER_SOFTWARE"])): ?>
    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>HTTP Server</strong></div>
      <div class="tb_col tb_4_5"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></div>
    </div>
    <?php endif; ?>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>MySQL server version</strong></div>
      <div class="tb_col tb_4_5"><?php echo $tbData->mysql_version; ?></div>
    </div>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>PHP version</strong></div>
      <div class="tb_col tb_4_5"><?php echo phpversion(); ?></div>
    </div>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>PHP memory limit</strong></div>
      <div class="tb_col tb_4_5"><?php echo $tbData->memory_limit;?> MB</div>
    </div>

    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>PHP Zip extension</strong></div>
      <div class="tb_col tb_4_5"><?php if (extension_loaded('zip')): ?>Yes<?php else: ?><span class="color_red">No</span><?php endif; ?></div>
    </div>

    <?php if (count($tbData->missing_files)): ?>
    <div class="tb_wrap s_mb_10">
      <div class="tb_col tb_1_5"><strong>Missing files</strong></div>
      <div class="tb_col tb_4_5">
        <ul class="tb_code_block">
          <?php foreach ($tbData->missing_files as $file): ?>
          <li><?php echo $file; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <?php /*
  <div id="system_settings_pages" class="tb_subpanel">

    <h2>Custom system pages</h2>
    <div class="s_sortable_holder tb_style_1 tbSystemPages"><?php foreach ($system['pages'] as $hash => $system_page): ?><div class="s_sortable_row s_nodrag tbSystemPage">
        <div class="s_actions">
          <div class="s_buttons_group">
            <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
          </div>
        </div>
        <div class="s_sortable_contents">
          <div class="s_row_1">
            <label>Label</label>
            <input type="text" name="system[pages][<?php echo $hash; ?>][label]" value="<?php echo $system_page['label'];?>" />
          </div>
          <div class="s_row_1">
            <label>Route</label>
            <input type="text" name="system[pages][<?php echo $hash; ?>][route]" value="<?php echo $system_page['route'];?>" />
          </div>
          <div class="s_row_1">
            <label>System blocks</label>
            <div class="s_full clearfix">
              <?php foreach (array('breadcrumbs' => 'Breadcrumbs', 'page_title' => 'Page title', 'page_content' => 'Page content') as $system_page_value => $system_page_title): ?>
              <input type="hidden" name="system[pages][<?php echo $hash; ?>][widgets][<?php echo $system_page_value; ?>]" value="0" />
              <label class="s_checkbox">
                <input type="checkbox" name="system[pages][<?php echo $hash; ?>][widgets][<?php echo $system_page_value; ?>]" value="1"<?php if ($system_page['widgets'][$system_page_value] == 1): ?> checked="checked<?php endif; ?>" />
                <span><?php echo $system_page_title; ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div><?php endforeach; ?></div>
    <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddSystemPage" href="javascript:;">Add Page</a>
  </div>
  */ ?>

</div>

<div class="s_submit clearfix">
  <a class="s_button s_red s_h_40 tb_cp_form_submit">Save settings</a>
</div>

<script type="text/javascript">

(function ($, tbApp) {

  $(tbApp).on("tbCp:initTab-system_settings", function() {

    var $system_settings             = $("#system_settings");
    var $system_settings_cache       = $("#system_settings_cache");
    var $system_settings_performance = $("#system_settings_performance");

    $system_settings.find("> .tb_tabs").tabs();
    $system_settings_cache.find("> .tb_tabs").tabs();
    $system_settings_performance.find("> .tb_tabs").tabs();

    beautifyForm($system_settings);

    $system_settings_cache.find(':checkbox[name="system[cache_styles]"]').bind("change", function() {
      if (!$(this).is(":checked")) {
        $system_settings_cache.find(':checkbox[name="system[cache_settings]"]').removeProp("checked");
      }
      $system_settings_cache.find(':checkbox[name="system[cache_settings]"]').closest(".tbSetting").toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger("change");

    $system_settings_cache.find(':checkbox[name="system[cache_enabled]"]').bind("change", function() {
      $system_settings_cache.find(".tbSetting").not($(this).closest(".tbSetting")).toggleClass("tb_disabled", !$(this).is(":checked"));
      if ($(this).is(":checked")) {
        $system_settings_cache.find(':checkbox[name="system[cache_styles]"]').trigger("change");
      }
    }).trigger("change");

    $system_settings.find(".tbClearCache")
      .on("click", ".tbClearDatabaseCache", function() {
        $("#tb_cp_content").block({ message: '<h1>Clearing</h1>' });
        $.getJSON($(this).attr("href"), function() {
          $("#tb_cp_content").unblock();
        });

        return false;
      })
      .on("click", ".tbClearAllCache", function() {
        $("#tb_cp_content").block({ message: '<h1>Clearing</h1>' });
        $.getJSON($(this).attr("href"), function() {
          $("#tb_cp_content").unblock();
        });

        return false;
      });

    $("#system_settings_performance_general")
      .on("change", ":checkbox[name='system[minify_html]']", function() {
        if ($(this).prop("checked")) {
          $("#system_settings_minify_js").prop("checked", true);
          $("#system_settings_optimize_js_load").prop("checked", true);
        }
      })
      .on("change", ":checkbox[name='system[minify_js]']", function() {
        if (!$(this).prop("checked")) {
          $("#system_settings_minify_html").prop("checked", false);
        }
      })
      .on("change", ":checkbox[name='system[optimize_js_load]']", function() {
        if (!$(this).prop("checked")) {
          $("#system_settings_defer_js_load").prop("checked", false);
          $("#system_settings_minify_html").prop("checked", false);
        }
      })
      .on("change", ":checkbox[name='system[defer_js_load]']", function() {
        if ($(this).prop("checked")) {
          $("#system_settings_optimize_js_load").prop("checked", true);
        }
      });

    $("#system_settings_pages .tbAddSystemPage").bind("click", function() {
      $(Mustache.render($("#system_page_template").text(), {
        hash: tbHelper.generateUniqueId().toLowerCase()
      })).appendTo($("#system_settings_pages").find(".tbSystemPages"));

      return false;
    });

    $("#system_settings_pages").on("click", ".tbRemoveRow", function() {
      confirm("Are you sure?") && $(this).closest(".tbSystemPage").remove();

      return false;
    });

  });

})(jQuery, tbApp);

</script>