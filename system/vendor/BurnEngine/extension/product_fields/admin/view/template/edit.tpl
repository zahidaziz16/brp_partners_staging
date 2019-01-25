<div class="tb_tabs tb_fly_tabs tbLanguageTabs">

  <h2>Edit field</h2>

  <style id="extension_product_fields_settings_lang_styles">
  #extension_product_fields_settings [data-language_code]:not([data-language_code="<?php echo $tbData->first_language_code; ?>"]) {
    display: none;
  }
  </style>
  <?php if (!$field['defaults']): ?>
  <style id="extension_product_fields_settings_tabs_styles">
  #extension_product_fields_settings .tbLanguageTabs > .tb_tabs_nav {
    visibility: hidden;
  }
  </style>
  <?php endif; ?>

  <div class="s_row_1 s_mb_30">
    <label>Active</label>
    <input type="hidden" name="field[is_active]" value="0" />
    <label class="tb_toggle"><input type="checkbox" name="field[is_active]" value="1"<?php if($field['is_active'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
  </div>

  <div class="s_row_1 s_mb_30">
    <label>Block name</label>
    <input type="text" name="field[block_name]" value="<?php echo $field['block_name']; ?>" />
  </div>

  <ul class="tb_tabs_nav clearfix">
    <?php foreach ($languages as $language): ?>
    <li class="s_language">
      <a href="#extension_product_fields_settings_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
        <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>

  <?php foreach ($languages as $language): ?>
  <div id="extension_product_fields_settings_language_<?php echo $language['code']; ?>" data-language_code="<?php echo $language['code'];; ?>"></div>
  <?php endforeach; ?>

  <input type="hidden" name="field[id]" value="<?php echo $field['id'];; ?>" />

  <div class="tbFieldDefaults s_mb_30">
    <?php foreach ($field['defaults'] as $uid => $default): ?>
    <fieldset class="tbFieldRow" data-uid="<?php echo $uid; ?>">
      <div class="s_actions">
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
      </div>
      <legend>Default</legend>
      <div class="tb_wrap tb_gut_30">
        <div class="tb_col tb_2_3">
          <div class="s_row_2 tbDefaultValue">
            <label>Content</label>
            <?php foreach ($languages as $language_code => $language): ?>
            <img class="right" src="<?php echo $language['url'] . $language['image']; ?>" data-language_code="<?php echo $language_code; ?>" style="margin-top: -22px !important;" />
            <div class="s_full clearfix" data-language_code="<?php echo $language_code; ?>">
              <textarea id="product_field_row_text_<?php echo $language_code . '_' . $uid; ?>" class="tbCKE" name="field[defaults][<?php echo $uid; ?>][lang][<?php echo $language_code; ?>][content]" cols="30"><?php echo $default['lang'][$language_code]['content']; ?></textarea>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="tb_col tb_1_3">
          <div class="s_row_2">
            <label>Available for</label>
            <label class="s_radio"><input type="radio" name="field[defaults][<?php echo $uid; ?>][available_for]" value="all"<?php if ($default['available_for'] == 'all'): ?> checked="checked"<?php endif; ?> /> <span>All products</span></label>
            <label class="s_radio"><input type="radio" name="field[defaults][<?php echo $uid; ?>][available_for]" value="category"<?php if ($default['available_for'] == 'category'): ?> checked="checked"<?php endif; ?> /> <span>Products from category</span></label>
            <?php if ($default['available_for'] == 'category'): ?>
            <textarea class="tbAvailableCategories" style="display: none;"><?php echo json_encode($default['categories']); ?></textarea>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </fieldset>
    <?php endforeach; ?>
  </div>

  <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddDefault">Add Default</a>
</div>

<div class="s_submit clearfix">
  <div class="left">
    <a class="s_button s_white s_h_40 tbButtonBackToFields">Back to field list</a>
  </div>
  <div class="right">
    <a class="s_button s_red s_h_40 tbSaveField" href="#">Save</a>
</div>

<textarea class="categoryTree" style="display: none;"><?php echo $categories_json; ?></textarea>

<script>
  (function($) {

    var $container = $("#extension_product_fields_settings");

    $container.find(".tbLanguageTabs").first().tabs({
      activate: function(event, ui) {
        $("#extension_product_fields_settings_lang_styles").html('#extension_product_fields_settings [data-language_code]:not([data-language_code="' + ui.newPanel.data("language_code") + '"]) { display: none; }');
      }
    });

    $container.find(".tbButtonBackToFields").bind("click", function() {
      $container.parent().block("<h1>Loading...</h1>");
      $container.load("<?php echo $tbUrl->generateJs('default/index'); ?>", function() {
        $container.parent().unblock();
      });
    });

    $container.find(".tbAddDefault").bind("click", function() {

      var languages = [];

      $.each($sReg.get("/tb/languages"), function(index, value) {
        languages.push(value);
      });

      var output = Mustache.render($("#product_field_row_template").text(), {
        uid:       tbHelper.generateUniqueId(5),
        languages: languages
      });

      var $row = $(output).appendTo($container.find(".tbFieldDefaults"));

      $row.find("textarea.tbCKE").each(function() {
        initRichEditor($(this).attr("id"));
      });

      $('#extension_product_fields_settings .tbLanguageTabs > .tb_tabs_nav').css('visibility', 'visible');
    });

    $container.find(".tbSaveField").bind("click", function() {
      $container.parent().block();

      for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
      }

      $.post($sReg.get("/tb/url/product_fields/default/saveField"), $container.find(":input[name^='field']").serializeArray(), function() {
        $container.parent().unblock();
      }, "json");

      return false;
    });

    $container.find(".tbFieldDefaults").on("click", "input[name$='[available_for]']", function() {
      var $parent = $(this).parent().parent();
      var $categorySelect = $parent.find(".tbCategorySelect");

      if ($(this).val() == 'category') {

        if ($categorySelect.length) {
          $categorySelect.show();

          return;
        }

        var uid = $parent.closest(".tbFieldRow").data("uid");

        getCategoryTreeOptions(function(html) {
          $parent.append('<div class="s_full tbCategorySelect"><select class="tb_1_1" multiple="multiple" size="10" name="field[defaults][' + uid + '][categories][]">' + html + '</select></div>');
        });
      } else {
        $categorySelect.hide();
      }
    });

    $container.find(".tbFieldDefaults").on("click", ".tbRemoveRow", function() {

      if (!confirm("Are you sure?")) {
        return false;
      }

      $(this).closest(".tbFieldRow").remove();

      if ($('.tbFieldRow').length == 0) {
        $('#extension_product_fields_settings .tbLanguageTabs > .tb_tabs_nav').css('visibility', 'hidden');
      }

      return false;
    });

    $container.find(".tbFieldRow textarea.tbCKE").each(function() {
      initRichEditor($(this).attr("id"));
    });

    $container.find(".tbAvailableCategories").each(function() {
      var categories = JSON.parse($(this).val());
      var $row = $(this).closest(".tbFieldRow");
      var $parent = $(this).parent();

      getCategoryTreeOptions(function(html) {
        $parent.append('<div class="s_full tbCategorySelect"><select class="tb_1_1" multiple="multiple" size="10" name="field[defaults][' + $row.data("uid") + '][categories][]">' + html + '</select></div>');
        $row.find(".tbCategorySelect option").each(function() {
          if (categories.indexOf($(this).val()) != -1) {
            $(this).prop("selected", true);
          }
        });
      });
    });

    function initRichEditor(element_id) {
      CKEDITOR.replace(element_id, {
        height: 138,
        customConfig:              '<?php echo $theme_admin_javascript_relative_url; ?>ckeditor/custom/config.js',
        contentsCss:               '<?php echo $theme_admin_javascript_relative_url; ?>ckeditor/custom/styles.css',
        filebrowserBrowseUrl:      '<?php echo $tbData['fileManagerUrl']; ?>',
        filebrowserImageBrowseUrl: '<?php echo $tbData['fileManagerUrl']; ?>',
        filebrowserImageUploadUrl: null
      });
    }

  })(jQuery);
</script>

<script type="text/template" id="product_field_row_template">
  <fieldset class="tbFieldRow" data-uid="{{uid}}">
    <div class="s_actions">
      <a href="javascript:;" class="tbRemoveRow s_button s_white s_h_20 s_icon_10 s_delete_10"></a>
    </div>
    <legend>Default</legend>
    <div class="tb_wrap tb_gut_30">
      <div class="tb_col tb_2_3">
        <div class="s_row_2 tbDefaultValue">
          <label>Content</label>
          {{#languages}}
          <img class="right" src="{{url}}{{image}}" data-language_code="{{code}}" style="margin-top: -22px !important;" />
          <div class="s_full clearfix" data-language_code="{{code}}">
            <textarea id="product_field_row_text_{{code}}_{{uid}}" class="tbCKE" name="field[defaults][{{uid}}][lang][{{code}}][content]" cols="30"></textarea>
          </div>
          {{/languages}}
        </div>
      </div>
      <div class="tb_col tb_1_3">
        <div class="s_row_2">
          <label>Available for</label>
          <label class="s_radio"><input type="radio" name="field[defaults][{{uid}}][available_for]" value="all" checked="checked" /> <span>All products</span></label>
          <label class="s_radio"><input type="radio" name="field[defaults][{{uid}}][available_for]" value="category" /> <span>Products from category</span></label>
        </div>
      </div>
    </div>
  </fieldset>
</script>
