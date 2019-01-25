<div id="extension_product_fields_settings" class="tb_subpanel">
  <h2>Extra Product Fields</h2>

  <table class="s_table_1 s_mb_30 tbFieldList" cellpadding="0" cellspacing="0" border="0">
    <thead>
      <tr>
        <th colspan="2">Field name</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($fields)): ?>
      <?php foreach ($fields as $field): ?>
      <tr class="s_open">
        <td class="align_left"><?php echo $field['block_name']; ?></td>
        <td width="60">
          <div class="s_buttons_group right">
            <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditField" href="<?php echo $tbUrl->generateJs('default/editField', 'id=' . $field['id']); ?>"></a>
            <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbDeleteField" href="<?php echo $tbUrl->generateJs('default/deleteField', 'id=' . $field['id']); ?>"></a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php else: ?>
      <tr class="s_open">
        <td class="align_center" colspan="2">
          There are no extra product fields.
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddField">Add Field</a>

  <div class="s_submit clearfix">
    <div class="left">
      <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
    </div>
  </div>
</div>


<script>
  (function($) {

    var $container = $("#extension_product_fields_settings");

    $container.find(".tbAddField").bind("click", function() {
      $container.parent().block("<h1>Loading...</h1>");
      $container.load("<?php echo $tbUrl->generateJs('default/editField'); ?>", function() {
        $container.parent().unblock();
      });

      return false;
    });

    $container.find(".tbFieldList").on("click", ".tbEditField", function() {
      $container.parent().block("<h1>Loading...</h1>");
      $container.load($(this).attr("href"), function() {
        $container.parent().unblock();
      });

      return false;
    });

    $container.find(".tbFieldList").on("click", ".tbDeleteField", function() {
      if (!confirm("Are you sure?")) {
        return false;
      }

      $container.parent().block("<h1>Loading...</h1>");
      $.get($(this).attr("href"), function() {
        $container.load("<?php echo $tbUrl->generateJs('default/index'); ?>", function() {
          $container.parent().unblock();
        });
      });


      return false;
    });

  })(jQuery);
</script>
