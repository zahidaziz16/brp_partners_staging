<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>

<ul<?php if ($level_1_style == 'list') echo ' class="tb_list_1"'; ?>>
  <?php foreach ($tag_tree as $tag): ?>
  <li<?php if ($tag['css_class']) echo ' class="' . $tag['css_class'] . '"'; ?>>
    <?php if ($level_1_style != 'list'): ?>
    <<?php echo $level_1_style; ?>><a href="<?php echo $tag['url']; ?>"><?php echo $tag['label']; ?></a></<?php echo $level_1_style; ?>>
    <?php else: ?>
    <span><a href="<?php echo $tag['url']; ?>"><?php echo $tag['label']; ?></a></span>
    <?php endif; ?>
    <?php if (isset($tag['children'])): ?>
    <ul<?php if ($level_2_style == 'list') echo ' class="tb_list_1"'; ?>>
      <?php foreach ($tag['children'] as $child): ?>
      <li<?php if ($child['css_class']) echo ' class="' . $child['css_class'] . '"'; ?>>
        <?php if ($level_2_style != 'list'): ?>
        <<?php echo $level_2_style; ?>><a href="<?php echo $child['url']; ?>"><?php echo $child['label']; ?></a></<?php echo $level_2_style; ?>>
        <?php else: ?>
        <span><a href="<?php echo $child['url']; ?>"><?php echo $child['label']; ?></a></span>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </li>
  <?php endforeach; ?>
</ul>