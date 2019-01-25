<ul class="tbModifiedMenu" style="display: none;">
  <?php foreach ($modified as $key => $section): ?>

  <?php if (isset($section['title']) && !empty($section['items'])): ?>
  <li class="tb_label ui-state-disabled"><?php echo $section['title']; ?></li>
  <?php endif; ?>

  <?php if (!empty($section['items'])): ?>
  <?php foreach ($section['items'] as $option): ?>
  <li><a href="javascript:;" <?php echo $key; ?>_id="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></a><span class="ui-combobox-remove">remove</span></li>
  <?php endforeach; ?>
  <?php endif; ?>

  <?php endforeach; ?>
</ul>