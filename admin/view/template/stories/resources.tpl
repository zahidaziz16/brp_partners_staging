<?php if (!$gteOc2): ?>
<link href="view/stories/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<link href="view/stories/styles/cp.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=Titillium+Web:400,300,600&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" rel="stylesheet" type="text/css">
<?php if ($gteOc2): ?>
<script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
<?php endif; ?>

<script type="text/javascript">

  $.fn.tabs = function() {
    var selector = this;

    this.each(function() {
      var obj = $(this);

      $(obj.attr('href')).hide();

      $(obj).click(function() {
        $(selector).removeClass('selected');

        $(selector).each(function(i, element) {
          $($(element).attr('href')).hide();
        });

        $(this).addClass('selected');

        $($(this).attr('href')).show();

        return false;
      });
    });

    $(this).show();

    $(this).first().click();
  };

  function generateUniqueId (length) {

    if (typeof length == "undefined") {
      length = 5;
    }

    var idstr = String.fromCharCode(Math.floor((Math.random()*25)+65));

    do {
      var ascicode = Math.floor((Math.random()*42)+48);

      if (ascicode < 58 || ascicode > 64) {
        idstr += String.fromCharCode(ascicode);
      }
    } while (idstr.length < length);

    return (idstr);
  }

  <?php if ($gteOc2): ?>
  function image_upload(field, preview) {
    $('#modal-image').remove();

    if (window.getSelection) {
      if (window.getSelection().empty) {  // Chrome
        window.getSelection().empty();
      } else if (window.getSelection().removeAllRanges) {  // Firefox
        window.getSelection().removeAllRanges();
      }
    } else if (document.selection) {  // IE?
      document.selection.empty();
    }

    var parent_id = "img_" + generateUniqueId();

    $("#" + preview).parent().attr("id", parent_id);
    $.ajax({
      url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&target=' + field + '&thumb=' + parent_id,
      dataType: 'html',
      success: function(html) {
        $('body').append('<div id="modal-image" class="modal">' + html + '</div>');
        $("#modal-image").on("click", "img", function() {
          $('#' + field).trigger("change");
        }).modal('show');
      }
    });
  }
  <?php else: ?>
  function image_upload(field, preview) {
    $('#dialog').remove();

    $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=' + getURLVar('token') + '&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

    $('#dialog').dialog({
      title: '<?php echo $text_image_manager; ?>',
      close: function (event, ui) {
        if ($('#' + field).attr('value')) {
          $.ajax({
            url: 'index.php?route=common/filemanager/image&token=' + getURLVar('token') + '&image=' + encodeURIComponent($('#' + field).attr('value')),
            dataType: 'text',
            success: function(data) {
              $('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
              $('#' + field).trigger("change");
            }
          });
        }
      },
      bgiframe: false,
      width: 700,
      height: 400,
      resizable: false,
      modal: false,
      open: function(event, ui) {
        $(event.target).parents("div.ui-dialog:first").wrap('<div class="tb_jquery_ui"></div>');
      }
    });
  }
  <?php endif; ?>
</script>