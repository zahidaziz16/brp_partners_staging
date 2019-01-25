<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>

<div id="cart">
  <ul class="nav nav-responsive">
    <li class="dropdown">
      <?php if ($tbData['system.cart_menu']['sticky_style'] == 'compact'): ?>
      <a class="btn btn-<?php echo $tbData['system.cart_menu']['sticky_size']; ?> tb_no_text tb_no_caret tbStickyOnly" href="<?php echo $cart; ?>"><i class="<?php echo $tbData['system.cart_menu']['cart_icon']; ?>"></i></a>
      <?php endif; ?>
      <h3 class="heading">
        <a href="<?php echo $cart; ?>">
          <?php if ($tbData['system.cart_menu']['show_icon']): ?>
          <i class="tb_icon <?php echo $tbData['system.cart_menu']['cart_icon']; ?>"></i>
          <?php endif; ?>
          <?php if ($tbData['system.cart_menu']['show_label']): ?>
          <span class="tb_label"><?php echo $tbData->text_cart; ?></span>
          <?php endif; ?>
          <?php if ($tbData['system.cart_menu']['show_items']): ?>
          <span class="tb_items"><?php echo $tbData->extractCartText($text_items, 'count'); ?></span>
          <?php endif; ?>
          <?php if ($tbData['system.cart_menu']['show_total']): ?>
          <span class="tb_total border"><?php echo $tbData->extractCartText($text_items, 'total'); ?></span>
          <?php endif; ?>
        </a>
      </h3>
      <div class="dropdown-menu">
        <div class="content">
          <h3><?php echo $tbData->text_shopping_cart; ?></h3>
          <?php if ($products || $vouchers) { ?>
          <div class="mini-cart-info cart-info">
            <table class="table table-striped">
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="image"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?></td>
                <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
                  <?php } ?>
                  <?php } ?>
                  <?php if ($product['recurring']) { ?>
                  <br />
                  - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
                  <?php } ?></td>
                <td class="quantity">x<?php echo $product['quantity']; ?></td>
                <td class="total"><?php echo $product['total']; ?></td>
                <?php if ($tbData->OcVersionGte('2.1.0.0')): ?>
                <td class="remove"><button type="button" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-default btn-sm"><i class="fa fa-times"></i></button></td>
                <?php else: ?>
                <td class="remove"><button type="button" onclick="cart.remove('<?php echo $product['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-default btn-sm"><i class="fa fa-times"></i></button></td>
                <?php endif; ?>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td class="name" colspan="2"><?php echo $voucher['description']; ?></td>
                <td class="quantity">x1</td>
                <td class="total"><?php echo $voucher['amount']; ?></td>
                <td class="remove"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-default btn-sm"><i class="fa fa-times"></i></button></td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <div class="mini-cart-total cart-total">
            <table>
              <?php foreach ($totals as $total) { ?>
              <tr>
                <td class="right"><strong><?php echo $total['title']; ?></strong></td>
                <td class="right"><?php echo $total['text']; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <div class="checkout buttons">
            <a class="btn btn-sm" href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a>
          </div>
          <?php } else { ?>
          <div class="empty"><?php echo $text_empty; ?></div>
          <?php } ?>
          <script>
          tbUtils.removeClass(tbRootWindow.document.querySelector('.tb_wt_header_cart_menu_system .table-striped'), 'table-striped');
          Array.prototype.forEach.call(tbRootWindow.document.querySelectorAll('.tb_wt_header_cart_menu_system td .btn'), function(el) {
              tbUtils.removeClass(el, 'btn-danger btn-xs');
              tbUtils.addClass(el, 'btn-default btn-sm tb_no_text');
          });
          </script>
        </div>
      </div>
    </li>
  </ul>
</div>