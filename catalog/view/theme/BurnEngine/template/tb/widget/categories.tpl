<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body">

  <?php // G R I D  ?>

  <?php if ($grid): ?>
  <div class="tb_subcategories tb_listing tb_grid_view <?php echo $listing_classes; ?>">
    <?php foreach ($categories as $category): ?>
    <?php if ($category['top']): ?>
    <div>
      <div class="tb_subcategory tb_item">
        <a class="thumbnail" href="<?php echo $category['url']; ?>" title="<?php echo $category['name']; ?>">
          <span class="image-holder" style="max-width: <?php echo $category['thumb_width']; ?>px;">
          <span style="padding-top: <?php echo round($category['thumb_height'] / $category['thumb_width'], 4) * 100; ?>%">
            <img src="<?php echo $category['thumb']; ?>"
                 <?php if ($tbData->system['image_lazyload']): ?>
                 data-src="<?php echo $category['thumb_original']; ?>"
                 class="lazyload"
                 <?php endif; ?>
                 width="<?php echo $category['thumb_width']; ?>"
                 height="<?php echo $category['thumb_height']; ?>"
                 alt="<?php echo $category['name']; ?>"
                 style="margin-top: -<?php echo round($category['thumb_height'] / $category['thumb_width'], 4) * 100; ?>%"
            />
          </span>
          </span>
        </a>
        <h3>
          <a href="<?php echo $category['url']; ?>">
            <?php echo $category['name']; ?>
          </a>
        </h3>
        <?php if ($product_count): ?>
        <h4 class="tb_product_count">(<?php echo $category['products_count']; ?> <?php echo $tbData->text_subcategory_items; ?>)</h4>
        <?php endif; ?>
        <?php if ($category['children'] && $show_next_level): ?>
        <ul class="tb_list_1">
          <?php foreach ($category['children'] as $child): ?>
          <li><a href="<?php echo $child['url']; ?>"><?php echo $child['name']; ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <?php // L I S T  ?>

  <?php else:
  $class  = '';
  $class .= $level_1_style == 'list' ? 'tb_list_1' : '';
  $class .= $grid ? ' tb_listing tb_grid_view' : '';
  $class  = $class ? ' class="' . trim($class) . '"' : '';
  ?>
  <ul<?php echo $class; ?>>
    <?php foreach ($categories as $category): ?>
    <?php
      // Level 1
      $has_submenu = $category['children'] && ($level_2 == 'show' || $level_2 == 'accordion' || $level_2 == 'toggle' || $category['category_id'] == $tbData->category_id || in_array($tbData->category_id, $category['successor_ids']));
      $is_active   = $tbData->category_id && ($category['category_id'] == $tbData->category_id || $category['category_id'] == $tbData->category_top_id);
      $class       = '';
      $class      .= !$grid && ($level_2 == 'accordion' || $level_2 == 'toggle') ? 'tb_expandable' : '';
      $class      .= !$grid && $level_2 == 'accordion' ? ' tb_accordion' : '';
      $class      .= !$grid && $level_2 == 'toggle' ? ' tb_toggable' : '';
      $class      .= $is_active || $category['category_id'] == $tbData->category_id || in_array($tbData->category_id, $category['successor_ids']) ? ' tb_active' : '';
      $class       = $class ? ' class="' . trim($class) . '"' : '';
    ?>
    <?php if (($level_1 != 'hide' || $is_active) && (!$respect_top || $category['top'])): ?>
    <li<?php echo $class; ?>>
      <?php if ($has_submenu && ($level_2 == 'accordion' || $level_2 == 'toggle')): ?>
      <span class="tb_toggle tb_bg_str_2 tb_bg_hover_str_3 tb_color_str_4"></span>
      <?php endif; ?>
      <?php if ($level_1_style != 'hide'): ?>
      <?php if ($level_1_style != 'list'): ?>
      <<?php echo $level_1_style; ?>><a href="<?php echo $category['url']; ?>"><?php echo $category['name']; ?></a></<?php echo $level_1_style; ?>>
      <?php else: ?>
      <span><a href="<?php echo $category['url']; ?>"><?php echo $category['name']; ?></a></span>
      <?php endif; ?>
      <?php endif; ?>
      <?php if ($has_submenu && $level_2 != 'hide'): ?>
      <ul<?php if ($level_2_style == 'list') echo ' class="tb_list_1"'; ?>>
        <?php foreach ($category['children'] as $child): ?>
        <?php
          // Level 2
          $has_submenu = $child['children'] && ($level_3 == 'show' || $child['category_id'] == $tbData->category_id || in_array($tbData->category_id, $child['children_ids']));
          $is_active   = $tbData->category_id && ($child['category_id'] == $tbData->category_id || $child['category_id'] == $tbData->category_parent_id);
          $class       = '';
          $class      .= $is_active || ($child['category_id'] == $tbData->category_id || in_array($tbData->category_id, $child['children_ids'])) ? ' tb_active' : '';
          $class       = $class ? ' class="' . trim($class) . '"' : '';
        ?>
        <li<?php echo $class; ?>>
          <?php if ($level_2_style != 'list'): ?>
          <<?php echo $level_2_style; ?>><a href="<?php echo $child['url']; ?>"><?php echo $child['name']; ?></a></<?php echo $level_2_style; ?>>
          <?php else: ?>
          <span><a href="<?php echo $child['url']; ?>"><?php echo $child['name']; ?></a></span>
          <?php endif; ?>
          <?php if ($has_submenu && $level_3 != 'hide'): ?>
          <ul<?php if ($level_3_style == 'list') echo ' class="tb_list_1"'; ?>>
            <?php foreach ($child['children'] as $grandchild): ?>
            <?php
              // Level 3
              $is_active  = $grandchild['category_id'] == $tbData->category_id;
            ?>
            <li<?php if ($is_active): ?> class="tb_active"<?php endif; ?>>
              <?php if ($level_3_style != 'list'): ?>
              <<?php echo $level_3_style; ?>><a href="<?php echo $grandchild['url']; ?>"><?php echo $grandchild['name']; ?></a></<?php echo $level_3_style; ?>>
              <?php else: ?>
              <span><a href="<?php echo $grandchild['url']; ?>"><?php echo $grandchild['name']; ?></a></span>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>

</div>

<?php if (!$grid && ($level_2 == 'accordion' || $level_2 == 'toggle')): ?>
<script>
tbApp.onScriptLoaded(function() {

    var $container = $('#<?php echo $widget_dom_id; ?>');

    if ($container.find('.tb_expandable.tb_active').length) {
        $container.find('.tb_expandable:not(.tb_active) > ul').hide();
        $container.find('.tb_expandable.tb_active').addClass('tb_show');
    }
    else {
        $container.find('.tb_expandable:not(:first-child) > ul').hide();
        $container.find('.tb_expandable:first-child').addClass('tb_show');
    }

    <?php if ($level_2 == 'accordion'): ?>
    $container.find('.tb_accordion > .tb_toggle').bind("click", function() {
        if(!$(this).parent().hasClass('tb_show')) {
            $container.find(".tb_accordion > ul:visible").slideUp().parent('li').removeClass("tb_show");
            $(this).parent().addClass("tb_show").find('> ul').slideDown();
        }
    });
    <?php endif; ?>

    <?php if ($level_2 == 'toggle'): ?>
    $container.find('.tb_toggable > .tb_toggle').bind("click", function() {
        if(!$(this).parent().hasClass('tb_show')) {
            $(this).parent().addClass('tb_show').find('> ul').slideDown();
        } else {
            $(this).parent().removeClass('tb_show').find('> ul').slideUp();
        }
    });
    <?php endif; ?>

    $container.find('.tb_expandable').parent().addClass('tbInit');

});
</script>
<?php endif; ?>

<?php if ($grid && (!empty($within_group) || !$tbData->optimize_js_load)): ?>
<script type="text/javascript">
tbApp.exec<?php echo $widget->getDomId(); ?> = function() {
  tbApp.onScriptLoaded(function() {
    <?php // ADJUST PRODUCT SIZE ?>
    adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
  });
}
<?php if (empty($within_group)): ?>
tbApp.exec<?php echo $widget->getDomId(); ?>();
<?php endif; ?>
</script>
<?php endif; ?>
<?php if ($grid): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#<?php echo $widget->getDomId(); ?>', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>