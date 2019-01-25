<?php $images = $theme_settings['payment_images']['rows']; ?>

<div class="tb_subpanel">

  <h2>Payment images</h2>
  <div class="s_sortable_holder tb_style_1 tbPaymentImagesContainer"><?php $i = 0; foreach ($images as $image): ?><div class="s_sortable_row tbImageRow">
      <?php $row_num = TB_Utils::genRandomString(); ?>
      <div class="s_actions">
        <div class="s_buttons_group">
          <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
        </div>
      </div>
      <h3 class="s_drag_area"><span class="tbRowTitle">Image <span><?php echo $i+1; ?></span></span></h3>
      <div class="s_sortable_contents">
      <div class="s_row_1">
        <label>Type</label>
        <div class="s_select">
          <select name="payment_images[rows][<?php echo $row_num; ?>][type]">
            <option value="image"<?php if ($image['type']  == 'image'):  ?> selected="selected"<?php endif; ?>>Image</option>
            <option value="seal"<?php if ($image['type']  == 'seal'):  ?> selected="selected"<?php endif; ?>>HTML</option>
          </select>
        </div>
      </div>
      <div class="s_row_1 opt_code">
        <label>Code</label>
        <textarea name="payment_images[rows][<?php echo $row_num; ?>][seal_code]" rows="4" cols="40"><?php echo $image['seal_code']; ?></textarea>
      </div>
      <div class="s_row_1 opt_image">
        <label>Image</label>
        <input type="hidden" name="payment_images[rows][<?php echo $row_num; ?>][file]" value="<?php echo $image['file']; ?>" id="payment_images_file_<?php echo $i; ?>" />
        <span class="tb_thumb">
          <img src="<?php echo $image['preview']; ?>" id="payment_images_file_preview_<?php echo $i; ?>" class="image" width="50" height="50" onclick="image_upload('payment_images_file_<?php echo $i; ?>', 'payment_images_file_preview_<?php echo $i; ?>');" />
        </span>
      </div>
      <div class="s_row_1 opt_image">
        <label>Link</label>
        <input type="text" name="payment_images[rows][<?php echo $row_num; ?>][link_url]" value="<?php echo $image['link_url']; ?>" />
        <span class="s_metric">
          <select class="s_metric" name="payment_images[rows][<?php echo $row_num; ?>][link_target]">
            <option value="_self"<?php  if ($image['link_target'] == '_self'):  ?> selected="selected"<?php endif; ?>>_self</option>
            <option value="_blank"<?php if ($image['link_target'] == '_blank'): ?> selected="selected"<?php endif; ?>>_blank</option>
          </select>
        </span>
      </div>
    </div>
  </div><?php $i++; endforeach; ?></div>

  <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddImage">Add Image</a>

  <div class="s_submit clearfix">
    <a class="s_button s_red s_h_40 tbSavePaymentImages">Save payment images</a>
  </div>

</div>

<script type="text/javascript">
(function ($) {
  var $container = $("#payment_images_settings");

  $container.find('.tbPaymentImagesContainer').sortable({
    handle: ".s_drag_area",
    tolerance: "pointer"
  });

  $container.on("click", ".tbAddImage", function() {

    var output = Mustache.render($("#payment_images_template").text(), {
      row_num: tbHelper.generateUniqueId(),
      row_title_num: $container.find(".tbImageRow").length + 1,
      no_image: $sReg.get("/tb/no_image")
    });

    $row = $(output).appendTo($container.find(".tbPaymentImagesContainer"));

    $row.find('select[name$="[type]"]').trigger("change");
    beautifyForm($row);

    return false;
  });

  $container.on("click", ".tbRemoveRow", function() {

    if (confirm("Are you sure?")) {
      var $row = $(this).closest(".tbImageRow");
      var $rowParent = $row.parent();

      $row.remove();

      $rowParent.find(".tbRow").each(function(i) {
        $(this).find(".tbRowTitle span").text(i + 1);
      });
    }

    return false;
  });

  $container.on("change", 'select[name$="[type]"]', function() {
    var $row = $(this).closest(".tbImageRow");

    $row.find('.opt_image').toggle($(this).val() == 'image');
    $row.find('.opt_code').toggle($(this).val() == 'seal');
  }).find('select[name$="[type]"]').trigger("change");

  $container.parent().on("click", ".tbSavePaymentImages", function() {
    $container.block({ message: '<h1>Saving settings</h1>' });
    setTimeout(function() {
      tbHelper.createCallbackRegister($(tbApp)).collectEvent('tbCp:beforeSave', function() {
        var form_json = $container.find(":input").serializeJSON();
        if (form_json.payment_images === undefined) {
          form_json.payment_images = [];
        }
        $.post($("#tb_cp_form").attr("action"), $.param({form_data: JSON.stringify(form_json)}), function(response) {
          $container.unblock();
        }, "json");
      });
    }, 50);
  });

  $(tbApp).on("tbCp:beforeSerialize.payment_images", function(e, $form) {
    $container.find(':input[name^="colors"]').attr("disabled", "disabled");
  });

  $(tbApp).on("tbCp:afterSave.payment_images", function(e, data, $form) {
    $container.find(':input[name^="colors"]').removeAttr("disabled");
  });

  beautifyForm($container);
})(jQuery);
</script>

<script type="text/template" id="payment_images_template">
  <div class="s_sortable_row tbImageRow">
    <div class="s_actions">
      <div class="s_buttons_group">
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
      </div>
    </div>
    <h3 class="s_drag_area"><span class="tbRowTitle">Image <span>{{row_title_num}}</span></span></h3>
    <div class="s_sortable_contents">
      <div class="s_row_1">
        <label>Type</label>
        <div class="s_select">
          <select name="payment_images[rows][{{row_num}}][type]">
            <option value="image">Image</option>
            <option value="seal">HTML</option>
          </select>
        </div>
      </div>
      <div class="s_row_1 opt_code">
        <label>Code</label>
        <textarea name="payment_images[rows][{{row_num}}][seal_code]" rows="4" cols="40"></textarea>
      </div>
      <div class="s_row_1 opt_image">
        <label>Image</label>
        <input type="hidden" name="payment_images[rows][{{row_num}}][file]" id="payment_images_file_{{row_num}}" />
        <span class="tb_thumb" style="width: 50px;">
          <img src="{{no_image}}" id="payment_images_file_preview_{{row_num}}" class="image" onclick="image_upload('payment_images_file_{{row_num}}', 'payment_images_file_preview_{{row_num}}');" />
        </span>
      </div>
      <div class="s_row_1 opt_image">
        <label>Link</label>
        <input type="text" name="payment_images[rows][{{row_num}}][link_url]" />
        <span class="s_metric">
          <select class="s_metric" name="payment_images[rows][{{row_num}}][link_target]">
            <option value="_self" selected="selected">_self</option>
            <option value="_blank">_blank</option>
          </select>
        </span>
      </div>
    </div>
  </div>
</script>
