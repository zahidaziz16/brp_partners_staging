<ul class="tb_tabs_nav clearfix tbSeoEditorTabs">
  <?php foreach ($languages as $language): ?>
  <li class="s_language" data-language_code="<?php echo $language['code']; ?>">
    <a href="#extension_seo_editor_<?php echo $item; ?>_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
      <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" title="<?php echo $language['name']; ?>" />
      <?php echo $language['code']; ?>
    </a>
  </li>
  <?php endforeach; ?>
</ul>

<?php foreach ($languages as $language): ?>
<div id="extension_seo_editor_<?php echo $item; ?>_language_<?php echo $language['code']; ?>">
  <?php echo $data['editor_' . $language['code']]; ?>
</div>
<?php endforeach; ?>