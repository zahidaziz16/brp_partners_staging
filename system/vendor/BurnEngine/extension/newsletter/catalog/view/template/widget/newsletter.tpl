<?php if ($title): ?>
<div class="panel-heading <?php echo $title_classes; ?> text-<?php echo $title_align; ?>">
  <h2 class="panel-title"><?php if ($title_icon): ?><span class="tb_icon <?php echo $title_icon; ?>" style="font-size: <?php echo $title_icon_size; ?>%;"></span><?php endif; ?><?php echo $title; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body">
  <?php if ($form_direction == 'group' && !$newsletter['show_name']): ?>
  <form class="form-vertical">
    <div class="form-group tbEmailRow">
      <div class="input-group input-group-<?php echo $form_size; ?>"<?php if(!empty($input_width)): ?> style="width: <?php echo $input_width; ?>;"<?php endif; ?>>
        <input class="form-control" type="text" name="email" value="" placeholder="<?php echo $text_email; ?>" />
        <span class="input-group-btn">
          <a class="btn btn-<?php echo $form_size; ?> tbNewsletterSubscribe"><?php echo $text_subscribe; ?></a>
        </span>
      </div>
    </div>
  </form>
  <?php else: ?>
  <form class="form-<?php echo ($form_direction == 'group' ? 'vertical' : $form_direction); ?>">
    <?php if ($newsletter['show_name']): ?>
    <div class="form-group form-group-<?php echo $form_size; ?> tbNameRow">
      <input class="form-control" type="text" name="name" value="" placeholder="<?php echo $text_name; ?>"<?php if(!empty($input_width)): ?> style="width: <?php echo $input_width; ?>;"<?php endif; ?> />
    </div>
    <?php endif; ?>
    <div class="form-group form-group-<?php echo $form_size; ?> tbEmailRow">
      <input class="form-control" type="text" name="email" value="" placeholder="<?php echo $text_email; ?>"<?php if(!empty($input_width)): ?> style="width: <?php echo $input_width; ?>;"<?php endif; ?> />
    </div>
    <a class="btn btn-<?php echo $form_size; ?> tbNewsletterSubscribe"><?php echo $text_subscribe; ?></a>
  </form>
  <?php endif; ?>
</div>

<script>
  tbApp.onWindowLoaded(function() {
    var $widget             = $("#<?php echo $widget->getDomId(); ?>"),
        show_name           = <?php echo (int) $newsletter['show_name']; ?>,
        subscribe_url       = '<?php echo $tbData->url->link('newsletter/subscribe'); ?>',
        text_subscribed     = '<?php echo $text_subscribed; ?>',
        text_subscribed_msg = '<?php echo $text_subscribed_msg; ?>';

    var validateEmail = function(email) {
      return /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email);
    };

    $widget.on("click", ".tbNewsletterSubscribe", function() {

      var email = $widget.find("input[name='email']").val(),
          name  = $widget.find("input[name='name']").val();

      $widget.find('.tbNameRow').removeClass('has-error').find('> p').remove();
      $widget.find('.tbEmailRow').removeClass('has-error').find('> p').remove();

      if (!validateEmail(email)) {
        $widget.find('.tbEmailRow').addClass('has-error').append('<p class="text-danger"><?php echo $text_invalid_email; ?></p>');
      }

      if (show_name && !name) {
        $widget.find('.tbNameRow').addClass('has-error').append('<p class="text-danger"><?php echo $text_enter_name; ?></p>');
      }

      if (!validateEmail(email) || (show_name && !name)) {

        return false;
      }

      $.post(subscribe_url, {
        name:  $widget.find("input[name='name']").val(),
        email: email
      }, function(response) {
        if (!response.success) {
          alert(response.message);
        } else {
          noty({
            text: '<h3>' + text_subscribed + '</h3><p>' + text_subscribed_msg + '</p>',
            layout: tbApp['/tb/msg_position'],
            closeOnSelfClick: true,
            modal: false,
            closeButton: true,
            timeout: false,
            animateOpen: {opacity: 'toggle'},
            animateClose: {opacity: 'toggle'},
            close_speed: 500,
            onClose: function() {
              $(document).unbind('touchmove.noty');
            }
          });
          $widget.find('form')[0].reset();
        }
      }, "json");
    });
  });
  tbUtils.onSizeChange(function() {

  });
</script>