<div class="row">
  <div class="col-sm-6">
    <div class="tb_new_customer_box tb_sep">
      <h2><?php echo $text_new_customer; ?></h2>
      <p><?php echo $text_checkout; ?></p>
      <div class="form-group">
        <div class="radio">
          <label>
            <?php if ($account == 'register') { ?>
            <input type="radio" name="account" value="register" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="account" value="register" />
            <?php } ?>
            <?php echo $text_register; ?></label>
        </div>
        <?php if ($checkout_guest) { ?>
        <div class="radio">
          <label>
            <?php if ($account == 'guest') { ?>
            <input type="radio" name="account" value="guest" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="account" value="guest" />
            <?php } ?>
            <?php echo $text_guest; ?></label>
        </div>
        <?php } ?>
      </div>
      <p><?php echo $text_register_account; ?></p>
    </div>
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-account" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
      </div>
    </div>
  </div>

  <div class="col-sm-6">
    <fieldset class="tb_login_box form-vertical" style="webkit-flex: 0 0 auto;flex: 0 0 auto;">
      <legend><?php echo $text_returning_customer; ?></legend>
      <p><?php echo $text_i_am_returning_customer; ?></p>
      <div class="form-group">
        <label class="control-label" for="input-email"><strong><?php echo $entry_email; ?></strong></label>
        <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
      </div>
      <div class="form-group">
        <label class="control-label" for="input-password"><strong><?php echo $entry_password; ?></strong></label>
        <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
      </div>
    </fieldset>
    <div class="buttons">
      <div class="pull-left">
        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
      <div class="pull-right">
        <input type="button" value="<?php echo $button_login; ?>" id="button-login" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
      </div>
    </div>

  </div>

</div>