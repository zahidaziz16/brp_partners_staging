<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $heading_title; ?></h2>
</div>
<div class="panel-body">
  <ul class="tb_list_1">
    <?php foreach ($informations as $information) { ?>
    <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
    <?php } ?>
    <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
    <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
  </ul>
</div>
