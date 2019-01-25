<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $heading_title; ?></h2>
</div>
<div class="panel-body">
  <ul class="tb_list_1">
    <?php foreach ($categories as $category) { ?>
    <li>
      <?php if ($category['category_id'] == $category_id) { ?>
      <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
      <?php } else { ?>
      <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php } ?>
      <?php if ($category['children']) { ?>
      <ul class="tb_list_1">
        <?php foreach ($category['children'] as $child) { ?>
        <li>
          <?php if ($child['category_id'] == $child_id) { ?>
          <a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
