<div id="widget_featured_stories" class="s_widget_options_holder tb_cp">

  <h1 class="sm_title"><span>Featured Stories</span></h1>

  <form action="<?php echo $tbUrl->generateJs('Widget/convertFormDataToSettings'); ?>&class_name=<?php echo $widget->getClassName(); ?>" method="post">

    <input type="hidden" name="widget_id" value="<?php echo $widget->getId(); ?>" />
    <div class="tb_tabs tb_subpanel_tabs tbWidgetMainTabs">

      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#widget_settings_holder">Edit</a></li>
          <li><a href="#widget_product_choose_holder" add_modal_class="sm_wide">Choose</a></li>
          <li><a href="#widget_box_styles_holder">Box Styles</a></li>
          <li><a href="#widget_title_styles_holder">Title Styles</a></li>
          <li><a href="#widget_advanced_settings_holder">Advanced</a></li>
        </ul>
      </div>

      <div id="widget_settings_holder" class="tb_subpanel">

        <div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

          <h2>Edit Featured Stories</h2>

          <?php if (count($languages) > 1): ?>
          <ul class="tb_tabs_nav clearfix">
            <?php foreach ($languages as $language): ?>
            <li class="s_language">
              <a href="#widget_featured_stories_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
                <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php echo $language['code']; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php foreach ($languages as $language): ?>
          <?php $language_code = $language['code']; ?>
          <div id="widget_featured_stories_language_<?php echo $language_code; ?>" class="s_language_<?php echo $language_code; ?>">
            <div class="s_row_1">
              <label><strong>Active</strong></label>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="0" />
              <label class="tb_toggle"><input type="checkbox" name="widget_data[lang][<?php echo $language_code; ?>][is_active]" value="1"<?php if($settings['lang'][$language_code]['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <p class="s_help">Enables the Featured Products content block for the current language.</p>
            </div>

            <div class="s_row_1">
              <label><strong>Title</strong></label>
              <span class="s_language_icon"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
              <div class="s_full">
                <input type="text" name="widget_data[lang][<?php echo $language_code; ?>][title]" value="<?php echo $settings['lang'][$language_code]['title']; ?>" />
                <div class="s_text_align s_buttons_group">
                  <input id="text_title_align_left_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="left"<?php if ($settings['lang'][$language_code]['title_align'] == 'left') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-left" for="text_title_align_left_<?php echo $language_code; ?>"></label>
                  <input id="text_title_align_center_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="center"<?php if ($settings['lang'][$language_code]['title_align'] == 'center') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-center" for="text_title_align_center_<?php echo $language_code; ?>"></label>
                  <input id="text_title_align_right_<?php echo $language_code; ?>" class="tb_nostyle" type="radio" name="widget_data[lang][<?php echo $language_code; ?>][title_align]" value="right"<?php if ($settings['lang'][$language_code]['title_align'] == 'right') echo ' checked="checked"'; ?> />
                  <label class="s_button s_h_30 s_white s_icon_16 fa-align-right" for="text_title_align_right_<?php echo $language_code; ?>"></label>
                </div>
              </div>
            </div>

            <?php $row = $settings['lang'][$language_code]; ?>

            <div class="s_row_1 tbIconRow">
              <label><strong>Title Icon</strong></label>
              <div class="tbIcon s_h_30<?php if (!$row['title_icon']): ?> s_icon_holder<?php endif; ?>">
                <?php if ($row['title_icon']): ?>
                <span class="glyph_symbol <?php echo $row['title_icon']; ?>"></span>
                <?php endif; ?>
              </div>
              <?php if (!$row['title_icon']): ?>
              <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
              <?php else: ?>
              <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
              <?php endif; ?>
              <input type="hidden" name="widget_data[lang][<?php echo $language_code; ?>][title_icon]" value="<?php echo $row['title_icon']; ?>" />
              <input class="s_spinner s_ml_10" type="text" min="10" step="5" name="widget_data[lang][<?php echo $language_code; ?>][title_icon_size]" value="<?php echo $row['title_icon_size']; ?>" size="6" />
              <span class="s_metric">%</span>
              <span class="s_language_icon right"><img src="<?php echo $language['url'] . $language['image']; ?>" /></span>
            </div>

          </div>
          <?php endforeach; ?>

          <fieldset>
            <legend>Filter</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Randomize</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[filter_randomize]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[filter_randomize]" value="1"<?php if ($settings['filter_randomize'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend>Layout</legend>
            <div class="s_row_1 tbOptionRowViewMode">
              <label>Display</label>
              <div class="s_select">
                <select name="widget_data[view_mode]">
                  <option value="list"<?php if($settings['view_mode'] == 'list') echo ' selected="selected"';?>>List</option>
                  <option value="grid"<?php if($settings['view_mode'] == 'grid') echo ' selected="selected"';?>>Grid</option>
                </select>
              </div>
            </div>
            <div class="s_row_1 tbOptionRowRestrictions">
              <label>Distribution</label>
              <div class="s_full clearfix">
                <table class="tb_product_elements s_table_1" cellspacing="0">
                  <thead>
                  <tr class="s_open">
                    <th width="123">
                      <label><strong>Container width</strong></label>
                    </th>
                    <th class="align_left" width="123">
                      <label><strong>Items per row</strong></label>
                    </th>
                    <th class="align_left" width="123">
                      <label><strong>Spacing</strong></label>
                    </th>
                    <th class="align_left">
                    </th>
                  </tr>
                  </thead>
                  <tbody class="tbItemsRestrictionsWrapper">
                  <?php $i = 0; ?>
                  <?php foreach ($settings['restrictions'] as $row): ?>
                    <tr class="s_open s_nosep tbItemsRestrictionRow">
                      <td>
                        <input class="s_spinner" type="text" name="widget_data[restrictions][<?php echo $i; ?>][max_width]" value="<?php echo $row['max_width']; ?>" min="100" step="10" size="7" />
                        <span class="s_metric">px</span>
                      </td>
                      <td class="align_left">
                        <input class="s_spinner" type="text" name="widget_data[restrictions][<?php echo $i; ?>][items_per_row]" value="<?php echo $row['items_per_row']; ?>" min="1" max="12" size="5" />
                      </td>
                      <td class="align_left">
                        <input class="s_spinner" type="text" name="widget_data[restrictions][<?php echo $i; ?>][items_spacing]" value="<?php echo $row['items_spacing']; ?>" step="10" min="0" max="50" size="5" />
                        <span class="s_metric">px</span>
                      </td>
                      <td class="align_right">
                        <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveItemsRestrictionRow" href="javascript:;"></a>
                      </td>
                    </tr>
                    <?php $i++; ?>
                  <?php endforeach; ?>
                  </tbody>
                </table>
                <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 s_mt_20 tbAddItemsRestrictionRow" href="javascript:;">Add rule</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_pt_20">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show description</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[show_text]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[show_text]" value="1"<?php if ($settings['show_text'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show date</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[show_date]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[show_date]" value="1"<?php if ($settings['show_date'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show tags</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[show_tags]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[show_tags]" value="1"<?php if ($settings['show_tags'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show comments</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[show_comments]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[show_comments]" value="1"<?php if ($settings['show_comments'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
                <label>Description limit</label>
                <input class="s_spinner" type="text" name="widget_data[text_limit]" value="<?php echo $settings['text_limit']; ?>" size="7" />
                <span class="s_metric">chars</span>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend>Thumbnail</legend>
            <div class="tb_wrap tb_gut_30 ">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show thumb</label>
                <span class="clear"></span>
                <input type="hidden" name="widget_data[show_thumb]" value="0" />
                <label class="tb_toggle"><input type="checkbox" name="widget_data[show_thumb]" value="1"<?php if ($settings['show_thumb'] == '1'): ?> checked="checked"<?php endif; ?> /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbSettingsRow">
                <label>Thumb size</label>
                <input class="inline" type="text" name="widget_data[image_size_x]" value="<?php echo $settings['image_size_x']; ?>" size="2" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="widget_data[image_size_y]" value="<?php echo $settings['image_size_y']; ?>" size="2" /> <span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>

          <?php require tb_modification(dirname(__FILE__) . '/_product_slider_options.tpl'); ?>

        </div>

      </div>

      <div id="widget_product_choose_holder" class="tb_subpanel tb_has_sidebar clearfix">
        <h2>Choose Articles</h2>
        <?php echo $tbGet->storiesModalController->getStoryList($widget->getStoryIds()); ?>
        <div class="tbFeaturedProductIds">
          <?php foreach ($widget->getStoryIds() as $id): ?>
          <input type="hidden" name="widget_data[story_ids][]" value="<?php echo $id; ?>" />
          <?php endforeach; ?>
        </div>
      </div>

      <div id="widget_box_styles_holder" class="tb_subpanel tb_has_sidebar clearfix tbWidgetCommonOptions">
        <?php $custom_widget_title = 'Featured Stories'; ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_title_styles_holder" class="tb_subpanel tb_has_sidebar tb_cp clearfix tbWidgetCommonOptions">
        <?php $style_section_id = 'title'; ?>
        <?php require tb_modification(dirname(__FILE__) . '/_common_options.tpl'); ?>
      </div>

      <div id="widget_advanced_settings_holder" class="tb_subpanel">
        <?php require tb_modification(dirname(__FILE__) . '/_advanced.tpl'); ?>
      </div>

    </div>

    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tbWidgetUpdate" href="javascript:;">Update Settings</a>
    </div>

  </form>

</div>

<link rel="stylesheet" type="text/css" href="view/stories/styles/jquery.tagit.css" />
<script type="text/javascript" src="view/stories/javascript/jquery.tag-it.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    $("#widget_settings_holder").find(".tbLanguageTabs").first().tabs();

    var widgetIconListReplace = function($newIcon, $activeRow) {
      $activeRow
              .find(".tbIcon").removeClass("s_icon_holder").empty().append($newIcon).end()
              .find('input[name*="title_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
              .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
    };

    $("#widget_settings_holder").on("click", ".tbChooseIcon", function() {
      if ($(this).hasClass("tbRemoveIcon")) {
        $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
               .parents(".tbIconRow").first()
               .find('input[name*="title_icon"]:hidden').val("").end()
               .find(".tbIcon").addClass("s_icon_holder").empty();
      } else {
        tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
      }

      return false;
    });

    var $container = $("#widget_featured_stories");

    beautifyForm($container.find(".tbWidgetCustomStyles"));

    $container.find("input[name='filter_tags']").tagit({
      allowSpaces: true
      <?php if (!empty($settings['tags_names'])): ?>
      , availableTags: [<?php echo $settings['tags_names']; ?>]
      <?php endif; ?>
    });

    $container.find('input[name$="[show_text]"]').bind("change", function() {
      $container.find('input[name$="[text_limit]"]').closest(".tbSettingsRow").toggleClass("tb_disabled", !$(this).is(":checked"));
      $container.find('input[name$="[text_tooltip]"]').closest(".tbSettingsRow").toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger("change");

    $container.find('input[name$="[show_thumb]"]').bind("change", function() {
      $container.find('select[name$="[thumb_position]"]').closest(".tbSettingsRow").toggleClass("tb_disabled", !$(this).is(":checked"));
      $container.find('input[name$="[image_size_x]"]').closest(".tbSettingsRow").toggleClass("tb_disabled", !$(this).is(":checked"));
    }).trigger("change");

    $('.tbOptionRowViewMode select').bind('change', function() {
      $('.tbOptionRowRestrictions').toggle($(this).val() == 'grid');
    }).trigger('change');

    tbApp.initRestrictionRows($container, "widget_data");
});

function submitFeaturedUrl(url, $container, callback) {

  $container.find(".tbProductsListing").block();
  $.get(url, function(data) {
    $container.find(".tbProductsListing").replaceWith(data);
    beautifyForm($container);
    if ($.isFunction(callback)) {
      callback.call(this);
    }
    $container.find(".tbProductsListing").unblock();
  });
}

var $container = $("#widget_product_choose_holder");

$container.on("click", ".tbSubmitFilter", function() {
  var query = $.jurlp($container.find('input[name="filter_request_url"]').val()).query();
  var url = getStoriesFilterUrl() + "&sort=" + query.sort + "&order=" + query.order;

  submitFeaturedUrl(url, $container);

  return false;
});

$container.on("click", ".tbResetFilter", function() {
  $container.find(".tbFilterForm").clearForm().find(":hidden").val("");
  submitFeaturedUrl(getStoriesFilterUrl(), $container);

  return false;
});

$container.on("click", "div.pagination a", function() {
  var query = $.jurlp($(this).attr("href")).query();
  var url = getStoriesFilterUrl() + "&page=" + query.page + "&sort=" + query.sort + "&order=" + query.order;

  submitFeaturedUrl(url, $container);

  return false;
});

$container.on("click", ".tbProductsListingTable thead a", function() {
  var query = $.jurlp($(this).attr("href")).query();
  var url = getStoriesFilterUrl() + "&page=" + query.page + "&sort=" + query.sort + "&order=" + query.order;

  submitFeaturedUrl(url, $container);

  return false;
});

$container.on("click", ".tbButtonProductSelect", function() {
  var parent = $(this).parents("tr:first");
  var story_id = parent.find("input").val();

  if (!parent.hasClass("tb_selected")) {
    parent.addClass("tb_selected");
    $container.find(".tbFeaturedProductIds")
      .append('<input type="hidden" name="widget_data[story_ids][]" value="' + story_id + '" />');
  } else {
    parent.removeClass("tb_selected");
    $container.find('.tbFeaturedProductIds input[value="' + story_id + '"]').remove();
  }

  return false;
});

$container.on("keydown", ".tbFilterForm input", function(e) {
  if (e.keyCode == 13) {
    submitFeaturedUrl(getStoriesFilterUrl(), $container);
  }
});

function getStoriesFilterUrl() {
  var url = '<?php echo str_replace('&amp;', '&', $tbUrl->generate('storiesModal/getStoriesOnly')); ?>';
  var input_params = tbHelper.collectInputParams([
    "filter_text",
    "filter_tags",
    "filter_disabled",
    "filter_selected"
  ], $container);

  if (input_params != "") {
    url += "&" + input_params;
  }

  url += '&' + decodeURIComponent($.param({
    story_ids : jQuery.map($container.find(".tbFeaturedProductIds input").serializeArray(), function(obj) {
      return obj.value;
    })
  }));

  return url;
}
</script>