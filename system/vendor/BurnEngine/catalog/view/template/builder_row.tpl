<div<?php echo $wrap_attributes; ?>>
  <div<?php echo $row_attributes; ?>>
    <?php foreach ($columns as $column): ?>
    <div<?php echo $column['column_attributes']; ?>><?php echo $column['html_contents']; ?></div>
    <?php endforeach; ?>
  </div>
</div>