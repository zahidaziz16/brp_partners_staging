<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<?php if ($tbData['system.language']): ?>
<?php $tbData->slotFilter('module/language.filter', $languages); ?>
<?php if (count($languages) > 1) { ?>
<?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language">
<?php else: ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="language">
<?php endif; ?>
  <ul class="nav nav-horizontal">
    <?php if ($tbData['system.language']['menu_type'] == 'dropdown'): ?>
    <li class="tb_selected<?php if (count($languages) > 1) echo ' dropdown'; ?>" aria-haspopup="true">
      <?php foreach ($languages as $language): ?>
      <?php if ($language['code'] == $code): ?>
      <a href="javascript:;">
        <?php if ($tbData['system.language']['label_has_flag']): ?>
        <img src="<?php echo $language['url'] . $language['image']; ?>" width="16" height="16" alt="<?php echo $language['name']; ?>" />
        <?php endif; ?>
        <span class="tb_text"><?php echo $tbData->getLanguageSymbol($language); ?></span>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
      <ul class="dropdown-menu tb_ip_xs tb_vsep_xs">
        <?php foreach ($languages as $language): ?>
        <?php if ($language['code'] != $code): ?>
        <li>
          <a href="javascript:;" data-language-code="<?php echo $language['code']; ?>">
            <?php if ($tbData['system.language']['label_has_flag']): ?>
            <img src="<?php echo $language['url'] . $language['image']; ?>" width="16" height="16" alt="<?php echo $language['name']; ?>" />
            <?php endif; ?>
            <?php echo $tbData->getLanguageSymbol($language); ?>
          </a>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php else: ?>
    <?php foreach ($languages as $language): ?>
    <li<?php if ($language['code'] == $code): ?> class="tb_selected"<?php endif; ?>>
      <a href="javascript:;"<?php if ($language['code'] != $code): ?> data-language-code="<?php echo $language['code']; ?>"<?php endif; ?>>
        <?php if ($tbData['system.language']['label_has_flag']): ?>
        <img src="<?php echo $language['url'] . $language['image']; ?>" width="16" height="16" alt="<?php echo $language['name']; ?>" />
        <?php endif; ?>
        <?php echo $tbData->getLanguageSymbol($language); ?>
      </a>
    </li>
    <?php endforeach; ?>
    <?php endif; ?>
  </ul>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
<?php endif; ?>
