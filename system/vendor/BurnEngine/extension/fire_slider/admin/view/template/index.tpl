<div class="tb_subpanel">
  <h2>Fire Slider</h2>
  <table class="s_table_1 s_mb_30" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr>
      <th colspan="2">Sliders</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($sliders as $slider_id => $slider): ?>
    <tr class="s_open">
      <td class="align_left"><?php echo $slider['name']; ?></td>
      <td width="60">
        <div class="s_buttons_group right">
          <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditSlider" href="<?php echo $tbUrl->generateJs('default/editSlider', 'slider_id=' . $slider_id); ?>"></a>
          <a class="s_button s_white s_h_20 s_icon_10 s_copy_10 tbDuplicateSlider" href="<?php echo $tbUrl->generateJs('default/duplicateSlider', 'slider_id=' . $slider_id); ?>"></a>
          <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbDeleteSlider" href="<?php echo $tbUrl->generateJs('default/deleteSlider', 'slider_id=' . $slider_id); ?>"></a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddSlider">Add Slider</a>
</div>

<script type="text/javascript">

  (function($) {

    var $container = $("#tb_fireslider_tab_content");

    $container.find(".tbAddSlider").bind("click", function() {
      $container.block("<h1>Loading...</h1>");
      $container.load("<?php echo $tbUrl->generateJs('default/editSlider'); ?>", function() {
        $container.unblock();
      });

      return false;
    });

    $container.find(".tbEditSlider").bind("click", function() {

      $container.block("<h1>Loading...</h1>");
      $container.load($(this).attr("href"), function() {
        $("#tb_fireslider_tab_content").tabs({ active: -1 });
        $container.unblock();
      });

      return false;
    });

    $container.find(".tbDuplicateSlider").bind("click", function() {

      $container.block("<h1>Loading...</h1>");
      $.get($(this).attr("href"), function() {
        $("#tb_fireslider_tab_content").load($("#menu_fireslider > a").attr("href"));
      });

      return false;
    });

    $container.find(".tbDeleteSlider").bind("click", function() {

      if (confirm("Are you sure?")) {
        $container.block("<h1>Loading...</h1>");
        $.get($(this).attr("href"), function() {
          $("#tb_fireslider_tab_content").load($("#menu_fireslider > a").attr("href"));
        });
      }

      return false;
    });

  })(jQuery);

</script>
