<?php
//$kuler = Kuler::getInstance();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php echo $header; ?>
<style>
.quotation-panel {
background-color: rgba(51,51,51, 0.1) !important;
color: black !important;
}
</style>

  <?php $tbData->slotStart('checkout/quotation.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php $tbData->slotStart('checkout/quotation.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

  <?php $tbData->slotStart('checkout/quotation.page_content'); ?>
  <?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>


      <div class="panel-group" id="accordion">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title quotation-panel" ><?php echo $text_title;?></h4>
          </div>
          <div class="panel-collapse collapse " id="collapse-quotation-form">
            <div class="panel-body"></div>
          </div>
        </div>
      </div>
	  
      <?php echo $content_bottom; ?>
    <?php echo $column_right; ?>
	
<?php $tbData->slotStop(); ?>

<script type="text/javascript">
$(document).on('change', 'input[name=\'account\']', function() {
	$('#collapse-quotation-form').parent().find('.panel-heading .panel-title').html('<a href="#collapse-quotation-form" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle quotation-panel"><?php echo $text_title; ?> <i class="fa fa-caret-down"></i></a>');
});

$(document).ready(function() {
    $.ajax({
        url: 'index.php?route=checkout/quotation_form',
        dataType: 'html',
        success: function(html) {
            $('#collapse-quotation-form .panel-body').html(html);
            
			$('#collapse-quotation-form').parent().find('.panel-heading .panel-title').html('<a href="#collapse-quotation-form" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle quotation-panel"><?php echo $text_title; ?> <i class="fa fa-caret-down"></i></a>');
			
			$('a[href=\'#collapse-quotation-form\']').trigger('click');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Payment Address  
$(document).delegate('#button-quotation-submit', 'click', function() {
    $.ajax({
        url: 'index.php?route=checkout/quotation_form/save',
        type: 'post',
        data: $('#collapse-quotation-form input[type=\'text\'], #collapse-quotation-form input[type=\'date\'], #collapse-quotation-form input[type=\'datetime-local\'], #collapse-quotation-form input[type=\'time\'], #collapse-quotation-form input[type=\'password\'], #collapse-quotation-form input[type=\'checkbox\']:checked, #collapse-quotation-form input[type=\'radio\']:checked, #collapse-quotation-form input[type=\'hidden\'], #collapse-quotation-form textarea, #collapse-quotation-form select'),
        dataType: 'json',
        beforeSend: function() {
        	$('#button-quotation-submit').button('loading');
		},  
        complete: function() {
			$('#button-quotation-submit').button('reset');
        },          
        success: function(json) {
            $('.form-group').removeClass('has-error');
            $('.alert, .text-danger').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-quotation-form .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));
					
					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}
				// Highlight any found errors
				$('.text-danger').parent().parent().addClass('has-error');				
            } else {
				$.ajax({
                    url: 'index.php?route=checkout/confirm',
                    dataType: 'html',
					cache: false,
					type: 'post',
                    success: function(data) {
						//console.log(data);
                        $.ajax({
							type: 'get',
							url: 'index.php?route=extension/payment/free_checkout/confirm',
							cache: false,
							beforeSend: function() {
								$('#button-quotation-submit').button('loading');
							},
							complete: function() {
								$('#button-quotation-submit').button('reset');
							},
							success: function() {
								location = '<?php echo $continue; ?>';
							}
						});
					},
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }     
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});

</script>

<?php echo $footer; ?>
