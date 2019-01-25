<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<?php if ($tbData['system.currency']): ?>
<?php if (count($currencies) > 1) { ?>
<?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-currency">
<?php else: ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency">
<?php endif; ?>
  <ul class="nav nav-horizontal">
    <?php if ($tbData['system.currency']['menu_type'] == 'dropdown'): ?>
    <li class="tb_selected<?php if (count($currencies) > 1) echo ' dropdown'; ?>" aria-haspopup="true">
      <?php foreach ($currencies as $currency): ?>
      <?php if ($currency['code'] == $code): ?>
      <a href="javascript:;" title="<?php echo $currency['title']; ?>">
        <span class="tb_text"><?php echo $tbData->getCurrencySymbol($currency); ?></span>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
      <?php if (count($currencies) > 1): ?>
      <ul class="dropdown-menu tb_ip_xs tb_vsep_xs">
        <?php foreach ($currencies as $currency): ?>
        <?php if ($currency['code'] != $code): ?>
        <li>
          <a href="javascript:;" title="<?php echo $currency['title']; ?>" data-currency-code="<?php echo $currency['code']; ?>"><?php echo $tbData->getCurrencySymbol($currency); ?></a>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </li>
    <?php else: ?>
    <?php foreach ($currencies as $currency): ?>
    <li<?php if ($currency['code'] == $code): ?> class="tb_selected"<?php endif; ?>>
      <a href="javascript:;" title="<?php echo $currency['title']; ?>"<?php if ($currency['code'] != $code): ?> data-currency-code="<?php echo $currency['code']; ?>"<?php endif; ?>><?php echo $tbData->getCurrencySymbol($currency); ?></a>
    </li>
    <?php endforeach; ?>
    <?php endif; ?>
  </ul>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
<?php endif; ?>
