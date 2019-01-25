<?php $settings = $theme_settings['background']; ?>
<div class="tb_subpanel">

  <div class="tb_tabs tb_fly_tabs clearfix">

    <h2><span>Site Background</span></h2>

    <div class="tb_tabs_nav">
      <ul class="tb_nav clearfix">
        <li><a href="#background_settings_global">Global</a></li>
        <li><a href="#background_settings_page">Page</a></li>
        <li><a href="#background_settings_category">Category</a></li>
        <li><a href="#background_settings_category_options">Options</a></li>
      </ul>
    </div>

    <div id="background_settings_global" class="tbTabContents">
      <div class="s_box_1 clearfix">
        <?php echo $tbData->fetchTemplate('theme_background_image', array('section_name' => 'global', 'input_name_property' => 'background[global]', 'background' => $settings['global'])); ?>
      </div>
    </div>

    <div id="background_settings_page">
      <textarea style="display: none;" class="tbSiteBackgroundPages"><?php echo json_encode($settings['page']); ?></textarea>
      <div class="s_box_1 clearfix">
        <?php echo $tbData->fetchTemplate('theme_background_image', array('section_name' => 'page', 'input_name_property' => '', 'background' => $settings['default'])); ?>
      </div>
      <div class="tb_image_listing tb_grid_view clearfix tbPageBackgroundsList"></div>
    </div>

    <div id="background_settings_category" class="tbTabContents">

      <textarea style="display: none;"><?php echo json_encode($settings['category']); ?></textarea>

      <div class="s_box_1 clearfix">
        <?php echo $tbData->fetchTemplate('theme_background_image', array('section_name' => 'category', 'input_name_property' => '', 'background' => $settings['default'])); ?>
      </div>

      <div class="tb_image_listing tb_grid_view clearfix tbCategoryBackgroundsList">

        <?php foreach ($settings['category'] as $id => $background): ?>
        <div class="tb_image_row clearfix tbSiteBackgroundPreviewRow tbTooltipItem" item_id="<?php echo $id; ?>">
          <input type="hidden" name="background[category][<?php echo $id; ?>][type]" value="<?php echo $background['type']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][image]" value="<?php echo $background['image']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][position]" value="<?php echo $background['position']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][position_x]" value="<?php echo $background['position_x']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][position_y]" value="<?php echo $background['position_y']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][position_x_metric]" value="<?php echo $background['position_x_metric']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][position_y_metric]" value="<?php echo $background['position_y_metric']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][repeat]" value="<?php echo $background['repeat']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][attachment]" value="<?php echo $background['attachment']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][size]" value="<?php echo $background['size']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][size_x]" value="<?php echo $background['size_x']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][size_y]" value="<?php echo $background['size_y']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][size_x_metric]" value="<?php echo $background['size_x_metric']; ?>" />
          <input type="hidden" name="background[category][<?php echo $id; ?>][size_y_metric]" value="<?php echo $background['size_y_metric']; ?>" />
          <span class="tb_thumb<?php if (!$background['image']): ?> tb_no_background<?php endif; ?>">
            <img src="<?php echo $background['preview']; ?>" />
          </span>
          <h3><?php echo $background['category_name']; ?></h3>
          <div class="tb_info">
            <ul>
              <li class="s_icon_10 tb_name_10" title="Category"><?php echo $background['category_full_name']; ?></li>
              <li class="s_icon_10 tb_position_10" title="Position"><?php echo $background['position']; ?></li>
              <li class="s_icon_10 tb_repeat_10" title="Repeat"><?php echo $background['repeat']; ?></li>
              <li class="s_icon_10 tb_attachment_10" title="Attachment"><?php echo $background['attachment']; ?></li>
              <li class="s_icon_10 tb_bg_size_10" title="Size"><?php echo $background['size']; ?></li>
            </ul>
          </div>
          <div class="s_buttons_group s_h_20">
            <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditSiteBackground"></a>
            <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveSiteBackground"></a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div>

    <div id="background_settings_category_options">
      <div class="s_row_2">
        <label><strong>Default category background</strong></label>
        <div class="s_full clearfix">
          <label class="s_radio"><input type="radio" name="background[options][category_inherit]" value="global"<?php if ($settings['options']['category_inherit'] == 'global'): ?> checked="checked"<?php endif; ?> /> <span>Inherit from Global</span></label>
          <span class="clear"></span>
          <label class="s_radio"><input type="radio" name="background[options][category_inherit]" value="parent"<?php if ($settings['options']['category_inherit'] == 'parent'): ?> checked="checked"<?php endif; ?> /> <span>Inherit from Parent</span></label>
          <span class="clear"></span>
          <label class="s_radio"><input type="radio" name="background[options][category_inherit]" value="none"<?php if ($settings['options']['category_inherit'] == 'none'): ?> checked="checked"<?php endif; ?> /> <span>No background</span></label>
        </div>
      </div>

      <div class="s_row_2">
        <label><strong>Default page background</strong></label>
        <div class="s_full clearfix">
          <label class="s_radio"><input type="radio" name="background[options][page_inherit]" value="global"<?php if ($settings['options']['page_inherit'] == 'global'): ?> checked="checked"<?php endif; ?> /> <span>Inherit from Global</span></label>
          <span class="clear"></span>
          <label class="s_radio"><input type="radio" name="background[options][page_inherit]" value="none"<?php if ($settings['options']['page_inherit'] == 'none'): ?> checked="checked"<?php endif; ?> /> <span>No background</span></label>
        </div>
      </div>
    </div>

  </div>

  <div class="s_submit clearfix">
    <a class="s_button s_red s_h_40 tb_cp_form_submit"><?php echo $text_button_save_settings; ?></a>
  </div>

</div>

<script type="text/javascript">
(function ($, tbApp) {

  $(tbApp).on("tbCp:initTab-background_settings", function() {

    var initThemeBackgroundCategory = function($tabCategory) {

      var $categoryTypes  = $tabCategory.find(".tbSiteBackgroundType");
      var $elInheritInfo  = $tabCategory.find(".tbBgCategoryInheritInfo");
      var $imageForm      = $tabCategory.find(".tbSiteBackgroundImageForm");
      var categories      = JSON.parse($tabCategory.find("textarea").first().val());

      var resetInputs = function() {
        $imageForm.find('input[name$="[image]"]').val("");
        $imageForm.find('img[id$="preview"]').attr("src", $sReg.get("/tb/no_image"));
        $imageForm.find("select").find("option").filter(":selected").removeAttr("selected").end().end().trigger("change");
      };

      var disableForm = function() {
        $categoryTypes.find('input[name$="[type]"]').removeAttr("checked");
        $imageForm.toggleClass("tb_disabled", true);
        $categoryTypes.toggleClass("tb_disabled", true);
        $tabCategory.find(".tbAddSiteBackground").toggleClass("tb_disabled", true);
        $elInheritInfo.hide();
        resetInputs();
        $selectCategory.customValue(" - Choose category -");
      };

      var updateFormData = function(category_id) {
        if (typeof category_id == "undefined") {
          disableForm();

          return;
        }

        $tabCategory.find(".tbAddSiteBackground").toggleClass("tb_disabled", false);
        $categoryTypes.toggleClass("tb_disabled", false);

        if (typeof categories[category_id] == "undefined") {

          var inherit_from = "Global";

          if ($sReg.get("/tb/background/options/category_inherit") == "parent") {

            getCategoryFlatTree(function(categoryTree) {

              var categoryParent = categoryTree.getCategoryParent(category_id);

              if (null !== categoryParent) {
                inherit_from = categoryParent.name;
              }

              $elInheritInfo.show().find("span").text(inherit_from);
            });

          } else

          if ($sReg.get("/tb/background/options/category_inherit") == "global") {
            $elInheritInfo.show().find("span").text(inherit_from);
          }

          $categoryTypes.find('input[name$="[type]"][value="custom"]').attr("checked", "checked").trigger("change");
          resetInputs();
          $tabCategory.find(".tbBgCategoryAction").text("Add");
        } else {

          $tabCategory.find(".tbBgCategoryAction").text("Replace");
          $elInheritInfo.hide();
          $categoryTypes.find('input[name$="[type]"][value="' + categories[category_id].type + '"]').attr("checked", "checked").trigger("change");

          $.each(["position", "repeat", "attachment", "size"], function(index, value) {
            $imageForm.find('option[value="' + categories[category_id][value] + '"]').attr("selected", "selected");
            if ((value == "size" || value == "position") && categories[category_id][value] == "custom") {
              $.each(["x", "y", "x_metric", "y_metric"], function(iindex, vvalue) {
                $imageForm.find(":input[name$='[" + value + "_" + vvalue + "]']").val(categories[category_id][value + "_" + vvalue]);
              });
            }
          });

          $imageForm.find('input[name$="[image]"]').val(categories[category_id].image);
          $imageForm.find('img[id$="preview"]').attr("src", categories[category_id].preview);
        }
      };

      var addBackground = function(categoryTree) {

        var category_type = $categoryTypes.find('input[name$="[type]"]:checked').val();

        if (category_type == "custom" && !$imageForm.find('input[name$="[image]"]').val()) {
          return;
        }

        var category_id = $selectCategory.value();
        var item_parent_name, item_name_full;

        if (category_id != 0) {
          var category_parent = categoryTree.getCategoryParent(category_id);

          item_parent_name = category_parent !== null ? category_parent.name : "";
          item_name_full = categoryTree.getCategoryFullName(category_id);
        } else {
          item_parent_name = "";
          item_name_full = "All categories";
        }


        var categoryObj = {
          property_name:      "category",
          no_background:      category_type == "none",
          item_id:            category_id,
          item_name:          $selectCategory.label(),
          item_name_full:     $('<div/>').html(item_name_full).text(),
          item_parent_name:   item_parent_name,
          type:               category_type,
          preview:            $imageForm.find('img[id$="preview"]').attr("src"),
          image:              $imageForm.find('input[name$="[image]"]').val(),
          position:           $imageForm.find('select[name$="[position]"]').val(),
          position_x:         $imageForm.find('input[name$="[position_x]"]').val(),
          position_y:         $imageForm.find('input[name$="[position_y]"]').val(),
          position_x_metric:  $imageForm.find('select[name$="[position_x_metric]"]').val(),
          position_y_metric:  $imageForm.find('select[name$="[position_y_metric]"]').val(),
          repeat:             $imageForm.find('select[name$="[repeat]"]').val(),
          attachment:         $imageForm.find('select[name$="[attachment]"]').val(),
          size:               $imageForm.find('select[name$="[size]"]').val(),
          size_x:             $imageForm.find('input[name$="[size_x]"]').val(),
          size_y:             $imageForm.find('input[name$="[size_y]"]').val(),
          size_x_metric:      $imageForm.find('select[name$="[size_x_metric]"]').val(),
          size_y_metric:      $imageForm.find('select[name$="[size_y_metric]"]').val()
        };

        if (category_type == "none") {
          categoryObj.image = '';
          categoryObj.preview = $sReg.get("/tb/no_image");
        }

        if (typeof categories[category_id] != "undefined") {
          delete categories[category_id];
          $tabCategory.find('[item_id="' + category_id + '"]').remove();
        }

        var output = Mustache.render($("#site_background_image_template").text(), categoryObj);

        categories[category_id] = categoryObj;
        $tabCategory.find(".tbCategoryBackgroundsList").prepend(output);

        disableForm();
      };

      var $selectCategory = tbApp.createCategoryComboBox($tabCategory.find(".tbSelectSiteBackground"), {
        onSelect: function(event, ui) {
          updateFormData(ui.item.optionValue)
          ui.context.uiInput.trigger("blur");
        },
        customValue: " - Choose category -"
      });

      $tabCategory.find(".tbAddSiteBackground a").bind("click", function() {

        getCategoryFlatTree(function(categoryTree) {
          addBackground(categoryTree);
        });

        return false;
      });

      $categoryTypes.find('input[name$="[type]"]').bind("change", function() {
        $imageForm.toggleClass("tb_disabled", $(this).val() == "none");
        if ($(this).val() == "none") {
          resetInputs();
        }
      });

      $tabCategory.find(".tbCategoryBackgroundsList").on("click", ".tbEditSiteBackground", function() {
        var $row = $(this).closest(".tbSiteBackgroundPreviewRow");

        updateFormData($row.attr("item_id"));
        $selectCategory.customValue($row.find("h3").text(), $row.attr("item_id"));

        $tabCategory.find("select[name='[position]']").trigger("change");
        $tabCategory.find("select[name='[size]']").trigger("change");

        return false;
      });

      $tabCategory.find(".tbCategoryBackgroundsList").on("click", ".tbRemoveSiteBackground", function() {
        if (confirm("Are you sure ?")) {
          var category_id = $(this).parents(".tbSiteBackgroundPreviewRow").first().attr("item_id");

          $(this).parents(".tbSiteBackgroundPreviewRow").first().remove();
          delete categories[category_id];
          if (category_id == $selectCategory.value()) {
            disableForm();
          }
        }

        return false;
      });
    };

    var initThemeBackgroundPage = function($tabPage, eventDispatcher) {

      var $selectPage = $tabPage.find(".tbSelectSiteBackground");
      var $bgTypes    = $tabPage.find(".tbSiteBackgroundType");
      var $imageForm  = $tabPage.find(".tbSiteBackgroundImageForm");
      var bg_pages    = JSON.parse($tabPage.find("textarea.tbSiteBackgroundPages").first().val());
      var init        = false;
      var pages_html  = '<option value="0">Home</option>';

      $.each(JSON.parse('<?php echo json_encode_safe(array_column($tbData->getInformationPages(), 'title', 'information_id')); ?>'), function(id, title) {
        pages_html += '<option value="' + id + '">' + title + '</option>';
      });

      $selectPage.append(pages_html);

      var resetInputs = function() {
        $imageForm.find('input[name$="[image]"]').val("");
        $imageForm.find('img[id$="preview"]').attr("src", $sReg.get("/tb/no_image"));
        $imageForm.find("select").find("option").filter(":selected").removeAttr("selected").end().end().trigger("change");
      };

      var snapPageObj = function() {

        var $currentOption   = $selectPage.find("option").filter(":selected");
        var page_id          = $selectPage.val();
        var page_type        = $bgTypes.find('input[name$="[type]"]:checked').val();
        var no_background    = page_type == "none";

        var pageObj = {
          property_name:      "page",
          no_background:      no_background,
          item_id:            page_id,
          item_name:          $currentOption.text(),
          item_name_full:     $currentOption.text(),
          type:               page_type,
          preview:            $imageForm.find('img[id$="preview"]').attr("src"),
          image:              $imageForm.find('input[name$="[image]"]').val(),
          position:           $imageForm.find('select[name$="[position]"]').val(),
          position_x:         $imageForm.find('input[name$="[position_x]"]').val(),
          position_y:         $imageForm.find('input[name$="[position_y]"]').val(),
          position_x_metric:  $imageForm.find('select[name$="[position_x_metric]"]').val(),
          position_y_metric:  $imageForm.find('select[name$="[position_y_metric]"]').val(),
          repeat:             $imageForm.find('select[name$="[repeat]"]').val(),
          attachment:         $imageForm.find('select[name$="[attachment]"]').val(),
          size:               $imageForm.find('select[name$="[size]"]').val(),
          size_x:             $imageForm.find('input[name$="[size_x]"]').val(),
          size_y:             $imageForm.find('input[name$="[size_y]"]').val(),
          size_x_metric:      $imageForm.find('select[name$="[size_x_metric]"]').val(),
          size_y_metric:      $imageForm.find('select[name$="[size_y_metric]"]').val()
        };

        if (page_type == "none") {
          pageObj.image = '';
          pageObj.preview = $sReg.get("/tb/no_image");
        }

        return pageObj;
      };

      var addPageBackground = function(PageObj, prepend) {
        var output = Mustache.render($("#site_background_image_template").text(), PageObj);

        bg_pages[PageObj.item_id] = PageObj;
        if (typeof prepend == "undefined" || prepend == false) {
          $tabPage.find(".tbPageBackgroundsList").append(output);
        } else {
          $tabPage.find(".tbPageBackgroundsList").prepend(output);
        }

      };

      var removePageBackground = function(page_id) {
        if (typeof bg_pages[page_id] != "undefined") {
          delete bg_pages[page_id];
          $tabPage.find('[item_id="' + page_id + '"]').remove();
        }
      };

      eventDispatcher.addEventListener("showTab", function() {
        if (init) {
          return;
        }
        init = true;

        $.each(bg_pages, function(index, pageObj) {

          var item_name = $selectPage.find("option").filter('[value="' + index + '"]').text();

          pageObj.property_name  = "page";
          pageObj.item_id        = index;
          pageObj.item_name      = item_name;
          pageObj.item_name_full = item_name;
          pageObj.no_background  = pageObj.type == "none";

          addPageBackground(pageObj);
        });
      });

      $selectPage.bind("change", function() {
        var $selected_option = $selectPage.find("option").filter(":selected");
        var page_id          = $selectPage.val();

        if (!tbHelper.is_numeric($selected_option.attr("value"))) {
          $bgTypes.find('input[name$="[type]"]').removeAttr("checked");
          $imageForm.toggleClass("tb_disabled", true);
          $bgTypes.toggleClass("tb_disabled", true);
          $tabPage.find(".tbAddSiteBackground").toggleClass("tb_disabled", true);
          resetInputs();

          return false;
        }

        $tabPage.find(".tbAddSiteBackground").toggleClass("tb_disabled", false);
        $bgTypes.toggleClass("tb_disabled", false);

        if (typeof bg_pages[page_id] == "undefined") {
          $bgTypes.find('input[name$="[type]"][value="custom"]').attr("checked", "checked").trigger("change");
          resetInputs();
          $tabPage.find(".tbBgCategoryAction").text("Add");
        } else {
          $tabPage.find(".tbBgCategoryAction").text("Replace");
          $bgTypes.find('input[name$="[type]"][value="' + bg_pages[page_id].type + '"]').attr("checked", "checked").trigger("change");
          $.each(["position", "repeat", "attachment", "size"], function(index, value) {
            $imageForm.find('option[value="' + bg_pages[page_id][value] + '"]').attr("selected", "selected");
            if ((value == "size" || value == "position") && bg_pages[page_id][value] == "custom") {
              $.each(["x", "y", "x_metric", "y_metric"], function(iindex, vvalue) {
                $imageForm.find(":input[name$='[" + value + "_" + vvalue + "]']").val(bg_pages[page_id][value + "_" + vvalue]);
              });
            }
          });
          $imageForm.find('input[name$="[image]"]').val(bg_pages[page_id].image);
          $imageForm.find('img[id$="preview"]').attr("src", bg_pages[page_id].preview);
        }

        return false;

      });

      $tabPage.find(".tbAddSiteBackground a").bind("click", function() {

        var pageObj = snapPageObj();

        if (pageObj.type == "custom" && !pageObj.image) {
          return false;
        }

        removePageBackground(pageObj.item_id);
        addPageBackground(pageObj, true);

        $selectPage.find("option").filter(":selected").removeAttr("selected");
        $selectPage.trigger("change");
        $imageForm.toggleClass("tb_disabled", true);

        return false;
      });

      $bgTypes.find('input[name$="[type]"]').bind("change", function() {
        $imageForm.toggleClass("tb_disabled", $(this).val() == "none");
        if ($(this).val() == "none") {
          resetInputs();
        }
      });

      $tabPage.find(".tbPageBackgroundsList").on("click", ".tbEditSiteBackground", function() {
        var page_id = $(this).parents(".tbSiteBackgroundPreviewRow").first().attr("item_id");

        $selectPage.find("option").filter(":selected").removeAttr("selected");
        $selectPage.find('option[value="' + page_id + '"]').attr("selected", "selected");
        $selectPage.trigger("change");

        $tabPage.find("select[name='[position]']").trigger("change");
        $tabPage.find("select[name='[size]']").trigger("change");

        return false;
      });

      $tabPage.find(".tbPageBackgroundsList").on("click", ".tbRemoveSiteBackground", function() {
        if (confirm("Are you sure ?")) {
          var page_id = $(this).parents(".tbSiteBackgroundPreviewRow").first().attr("item_id");

          $(this).parents(".tbSiteBackgroundPreviewRow").first().remove();
          delete bg_pages[page_id];
          if (page_id == $selectPage.val()) {
            $selectPage.find("option").filter(":selected").removeAttr("selected");
            $selectPage.trigger("change");
            $imageForm.toggleClass("tb_disabled", true);
          }
        }

        return false;
      });

    };

    var initThemeBackgroundGlobal = function($tabGlobal) {

      var $imageForm  = $tabGlobal.find(".tbSiteBackgroundImageForm");

      var resetInputs = function() {
        $imageForm.find('input[name$="[image]"]').val("");
        $imageForm.find('img[id$="preview"]').attr("src", $sReg.get("/tb/no_image"));
        $imageForm.find("select").find("option").filter(":selected").removeAttr("selected").end().end().trigger("change");
      };

      $tabGlobal.find('input[name$="[type]"]').bind("change", function() {
        $imageForm.toggleClass("tb_disabled", $(this).val() == "none");
        if ($(this).val() == "none") {
          resetInputs();
        }
      }).filter(":checked").trigger("change");
    };

    function initThemeBackground() {

      var $container = $("#background_settings");
      var eventDispatcher = tbHelper.createEventDispatcher();

      beautifyForm($container);

      initThemeBackgroundGlobal($("#background_settings_global"));
      initThemeBackgroundPage($("#background_settings_page"), eventDispatcher);
      initThemeBackgroundCategory($("#background_settings_category"));

      $container.find("> div > .tb_tabs").tabs({
        activate: function(event, ui) {
          eventDispatcher.dispatchEvent("showTab", $(ui.newPanel));
          tbApp.cookie.set("tbStyleBackgroundTabs", ui.newTab.index());
        },
        active: tbApp.cookie.get("tbStyleBackgroundTabs", 0),
        create: function(event, ui) {
          eventDispatcher.dispatchEvent("showTab", $(ui.panel));
        }
      });

      $container.on("change", "select[name$='[position]']", function() {
        $(this).closest(".tbImageOptions").find(".tbPositionX, .tbPositionY").toggle($(this).val() == "custom");
      });

      $container.on("change", "select[name$='[size]']", function() {
        $(this).closest(".tbImageOptions").find(".tbSizeX, .tbSizeY").toggle($(this).val() == "custom");
      });
    }

    initThemeBackground();
  });

})(jQuery, tbApp);

</script>