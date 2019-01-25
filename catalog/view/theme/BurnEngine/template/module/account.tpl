<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $heading_title; ?></h2>
</div>
<div class="panel-body">
  <div class="list-group">
    <?php if ($tbData->common['checkout_enabled']): ?>
    <?php if (!$logged) { ?>
    <a href="<?php echo $login; ?>" class="list-group-item"><?php echo $text_login; ?></a> <a href="<?php echo $register; ?>" class="list-group-item"><?php echo $text_register; ?></a> <a href="<?php echo $forgotten; ?>" class="list-group-item"><?php echo $text_forgotten; ?></a>
    <?php } ?>
    <a href="<?php echo $account; ?>" class="list-group-item"><?php echo $text_account; ?></a>
    <?php if ($logged) { ?>
    <a href="<?php echo $edit; ?>" class="list-group-item"><?php echo $text_edit; ?></a> <a href="<?php echo $password; ?>" class="list-group-item"><?php echo $text_password; ?></a>
    <?php } ?>
    <?php endif; ?>
    <a href="<?php echo $address; ?>" class="list-group-item"><?php echo $text_address; ?></a>
    <?php if ($tbData->common['wishlist_enabled']): ?>
    <a href="<?php echo $wishlist; ?>" class="list-group-item"><?php echo $text_wishlist; ?></a>
    <?php endif; ?>
    <?php if ($tbData->common['checkout_enabled']): ?>
    <a href="<?php echo $order; ?>" class="list-group-item"><?php echo $text_order; ?></a>
    <a href="<?php echo $download; ?>" class="list-group-item"><?php echo $text_download; ?></a>
    <a href="<?php echo $recurring; ?>" class="list-group-item"><?php echo $text_recurring; ?></a>
    <a href="<?php echo $reward; ?>" class="list-group-item"><?php echo $text_reward; ?></a>
    <?php endif; ?>
    <?php if ($tbData->common['returns_enabled']): ?>
    <a href="<?php echo $return; ?>" class="list-group-item"><?php echo $text_return; ?></a>
    <?php endif; ?>
    <?php if ($tbData->common['checkout_enabled']): ?>
    <a href="<?php echo $transaction; ?>" class="list-group-item"><?php echo $text_transaction; ?></a>
    <?php endif; ?>
    <a href="<?php echo $newsletter; ?>" class="list-group-item"><?php echo $text_newsletter; ?></a>
    <?php if ($logged) { ?>
    <a href="<?php echo $logout; ?>" class="list-group-item"><?php echo $text_logout; ?></a>
    <?php } ?>
  </div>
</div>
