<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('product/product.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('product/product.page_title', array('filter' => array('product/product.page_title.filter', 'heading_title' => &$heading_title), 'data' => $data)); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Product Images ----------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_images', array('data' => $data)); ?>
<?php if ($images || $thumb):
$config_group = version_compare(VERSION, '2.2.0.0', '>=') ? TB_Engine::getName() : 'config';
$image_preview_width  = $tbData['config']->get($config_group . '_image_thumb_width');
$image_preview_height = $tbData['config']->get($config_group . '_image_thumb_height');
$image_full_width     = $tbData['config']->get($config_group . '_image_popup_width');
$image_full_height    = $tbData['config']->get($config_group . '_image_popup_height');
$gallery              = $tbData['system.product_images'];
?>

<style scoped>
<?php if ($images): ?>
<?php if ($gallery['nav_position'] == 'bottom' || $gallery['nav_style'] != 'thumbs'): ?>
#product_images { padding-top: {{ratio_plus}}; }
#product_images .tb_slides { margin-top: {{ratio_minus}}; }
#product_images .tb_thumbs_wrap { padding-top: {{ratio_thumbs}}; }
<?php else: ?>
#product_images { padding-top: {{ratio_plus}}; }
<?php endif; ?>
<?php else: ?>
#product_images { padding-top: {{ratio_single}}%; }
#product_images .tb_slides { margin-top: -{{ratio_single}}%; }
<?php endif; ?>
</style>

<?php if ($thumb || $images) { ?>
<ul class="thumbnails">
  <?php if ($thumb) { ?>
  <li><a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
  <?php } ?>
  <?php if ($images) { ?>
  <?php foreach ($images as $image) { ?>
  <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
  <?php } ?>
  <?php } ?>
</ul>
<?php } ?>

<div id="product_images" class="tb_gallery{{gallery_css_classes}}">

  <div class="tb_slides">
    <?php // Zoom Area ?>
    <?php if ($tbData['system.product_images']['zoom']): ?>
    <span class="tb_zoom_box tb_zoom_<?php echo $tbData['system.product_images']['zoom_trigger']; ?>"></span>
    <?php endif; ?>

    <?php // Fullscreen button ?>
    <?php if ($tbData['system.product_images']['fullscreen']): ?>
    <a href="javascript:;" class="tb_fullscreen_button btn btn-<?php echo $tbData['system.product_images']['fullscreen_button_size']; ?> tb_no_text tbGoFullscreen">
      <i class="tb_icon <?php echo $tbData['system.product_images']['fullscreen_button_icon']; ?>" style="font-size: <?php echo $tbData['system.product_images']['fullscreen_button_icon_size']; ?>px;"></i>
    </a>
    <?php endif; ?>

    <?php // Slides ?>
    <div class="frame productSlide" data-mightyslider="width: <?php echo $image_preview_width; ?>, height: <?php echo $image_preview_height; ?>">
      <div>
        <div data-mightyslider="type: 'image', cover: '<?php echo $popup; ?>', thumbnail: '<?php echo $thumb; ?>'"></div>
        <?php foreach ($images as $image): ?>
        <div data-mightyslider="type: 'image', cover: '<?php echo $image['popup']; ?>', thumbnail: '<?php echo $image['thumb']; ?>'"></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php if ($images && $gallery['nav'] && $gallery['nav_style'] == 'thumbs'): ?>

  <div class="tb_thumbs_wrap">
    <div class="tb_thumbs">
      <div class="has_slider">
        <ul class="tb_listing tb_grid_view tb_size_<?php echo $gallery['nav_thumbs_num']; ?>"></ul>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($images && $gallery['nav'] && $gallery['nav_style'] != 'thumbs'): ?>
  <ul class="tb_pagination mSPages">
  </ul>
  <?php endif; ?>

</div>

<script type="text/javascript">
tbApp.onScriptLoaded(function() {

    // Gallery

    var $slider = new mightySlider(
        '#product_images .frame',
        {
            speed:             500,
            easing:            'easeOutExpo',
            viewport:          'fill',
            autoScale:         1,
            preloadMode:       'instant',
            navigation: {
                slideSize:     '100%',
                keyboardNavBy: 'slides'
            },
            commands: {
                thumbnails:    <?php echo $gallery['nav'] && $gallery['nav_style'] == 'thumbs' && $images ? 1 : 0; ?>,
                pages:         <?php echo $gallery['nav'] && $gallery['nav_style'] != 'thumbs' && $images ? 1 : 0; ?>,
                buttons:       <?php echo $gallery['nav'] && $gallery['nav_buttons'] ? 1 : 0; ?>
            },
            <?php if ($gallery['nav'] && $gallery['nav_style'] != 'thumbs'): ?>
            pages: {
                pagesBar:      '#product_images .tb_pagination',
                activateOn:    'click'
            },
            <?php endif; ?>
            dragging: {
                swingSync:     5,
                swingSpeed:    0.2
            },
            <?php if ($gallery['nav'] && $gallery['nav_style'] == 'thumbs'): ?>
            thumbnails: {
                thumbnailsBar:     '#product_images .tb_thumbs ul',
                thumbnailsButtons: 0,
                horizontal:        <?php echo $gallery['nav_position'] == 'bottom' ? 1 : 0; ?>,
                thumbnailNav:      'centered',
                thumbnailSize:     '<?php echo (100 / $gallery['nav_thumbs_num']); ?>%'
            },
            <?php endif; ?>
            classes: {
                loaderClass:   'tb_loading_bar'
            }
        }
    );

    <?php if ($gallery['zoom']): ?>
    function zoom_preview() {
        $('#{{widget_dom_id}} .tb_zoom_box.tb_zoom_click').removeClass('tb_zoomed');
        $('#{{widget_dom_id}} .tb_zoom_box').trigger('zoom.destroy').zoom({
            url: $slider.slides[$slider.relative.activeSlide].options.cover,
            on:  '<?php echo $gallery['zoom_trigger']; ?>'
        });
    }

    $('#{{widget_dom_id}} .tb_zoom_box.tb_zoom_click').bind('click', function(){
        if ($(this).hasClass('tb_zoomed')) {
            $(this).removeClass('tb_zoomed');
        } else {
            $(this).addClass('tb_zoomed');
        }
    });

    $slider.one('coverLoaded', function (eventName) {
      $('#product_images .tb_thumbs ul').removeClass('tb_grid_view tb_size_1 tb_size_2 tb_size_3 tb_size_4 tb_size_5 tb_size_6 tb_size_7 tb_size_8');
    });

    $slider.on('load moveEnd', function (eventName) {
        zoom_preview();
    });
    <?php endif; ?>

    $slider.init();
    $slider.activatePage(0);

    <?php if ($tbData['system.product_images']['fullscreen']): ?>

    // Fullscreen gallery

    var fullscreen_gallery_items = [
      {
        src:  '<?php echo $popup; ?>',
        w:    <?php echo $image_full_width; ?>,
        h:    <?php echo $image_full_height; ?>,
        msrc: '<?php echo $thumb; ?>'
      }
      <?php foreach ($images as $image): ?>
      ,{
        src:  '<?php echo $image['popup']; ?>',
        w:    <?php echo $image_full_width; ?>,
        h:    <?php echo $image_full_height; ?>,
        msrc: '<?php echo $image['thumb']; ?>'
      }
      <?php endforeach; ?>
    ];

    $('#{{widget_dom_id}} .tbGoFullscreen').bind('click', function() {
      lightbox_gallery('{{widget_dom_id}}', $slider, false, fullscreen_gallery_items);
    });
    <?php endif; ?>

    // Gallery changes detection

    var myInterval = null;

    jQuery('#content').on('change', ':input', function() {
        var callback = function() {

            var gallery,
                new_gallery = false,
                $images_src = $('#{{widget_dom_id}} .thumbnails');

            fullscreen_gallery_items = [];

            $images_src.find('a').each(function(index) {
                gallery += '<div data-mightyslider="type: \'image\', cover: \'' + $(this).attr('href') + '\', thumbnail: \'' + $(this).find('img').attr('src') + '\'"></div>';

                fullscreen_gallery_items.push({
                  src:  $(this).attr('href'),
                  w:    <?php echo $image_full_width; ?>,
                  h:    <?php echo $image_full_height; ?>,
                  msrc: $(this).find('img').attr('src')
                });

                if ($(this).attr('href') != $slider.slides[index].options.cover) {
                    new_gallery = true;
                }
            });

            if ($images_src.find('a').length != $slider.slides.length) {
                new_gallery = true;
            }

            if (new_gallery) {
                var slides_num = $slider.slides.length;

                $slider.off('load');
                for (var i = 0; i < slides_num; i++) {
                    $slider.remove('.mSSlide');
                }
                $slider.add(gallery);
                $slider.on('load', function (eventName) {
                  zoom_preview();
                });
            }

            return new_gallery;
        };

        clearInterval(myInterval);

        if (jQuery.active) {
            $(document).one("ajaxStop.product-images", function() {
                var i = 0;

                myInterval = setInterval(function () {
                    if (callback() || i == 5) {
                        clearInterval(myInterval);
                    }
                    i++;
                }, 150);
            });
        } else {
            setTimeout(function() {
                callback();
            }, 100);
        }
    });

});
</script>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Info ------------------------------------------------- ?>

<?php $product_info = array(); ?>
<?php $tbData->slotStart('product/product.product_info', array('filter' => array('product/product.product_info.filter', 'button_cart' => &$button_cart, 'product_info' => &$product_info), 'data' => $data)); ?>

<?php ${'content_top'} = ${'content_bottom'} = ''; ?>

<?php echo $content_top; ?>

<dl class="dl-horizontal">
  <dt><?php echo $text_model; ?></dt>
  <dd><?php echo $model; ?></dd>
  <?php if ($reward): ?>
  <dt><?php echo $text_reward; ?></dt>
  <dd><?php echo $reward; ?></dd>
  <?php endif; ?>
  <?php if ($tbData->common['manufacturers_enabled'] && $manufacturer): ?>
  <dt><?php echo $text_manufacturer; ?></dt>
  <dd><a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></dd>
  <?php endif; ?>
</dl>

<?php echo $content_bottom; ?>

<?php $tbData->slotStop(); ?>


<?php // Product Price ------------------------------------------------ ?>

<?php $tbData->slotStart('product/product.product_price', array('data' => $data)); ?>
<?php if ($price) { ?>
<?php if ($tbData['system.product_price']['show_label']): ?>
<span class="tb_label"><?php echo $tbData->text_price; ?></span>
<?php endif; ?>

<div class="price">
  <?php $price = $tbData->priceFormat($price); ?>
  <?php if (!$special) { ?>
  <span class="price-regular"><?php echo $price; ?></span>
  <?php } else { ?>
  <?php $special = $tbData->priceFormat($special); ?>
  <span class="price-old"><?php echo $price; ?></span>
  <?php if ($tbData['system.product_price']['old_price_new_line']): ?>
  <span class="clear"></span>
  <?php endif; ?>
  <span class="price-new"><?php echo $special; ?></span>
  <?php } ?>
</div>

<?php if (!$tbData['system.product_price']['show_tax']) $tax = false; ?>
<?php if ($tax) { ?>
<span class="price-tax"><?php echo $text_tax; ?> <span><?php echo $tax; ?></span></span>
<?php } ?>

<?php if (!$tbData['system.product_price']['show_reward']) $points = false; ?>
<?php if ($points) { ?>
<span class="reward"><?php echo $text_points; ?> <?php echo $points; ?></span>
<?php } ?>

<?php if ($tbData->product_savings && $tbData['system.product_price']['show_savings']): ?>
<p class="price-savings">
<?php if ($tbData['system.product_price']['show_savings_sum']): ?>
<?php echo $tbData->product_you_save; ?>
<?php else: ?>
<?php echo $tbData->product_savings; ?>
<?php endif; ?>
</p>
<?php endif; ?>

<?php if (!$tbData['config']->get('config_customer_price') || ($tbData['config']->get('config_customer_price') && $tbData['customer']->isLogged())): ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#content').find('select[name="profile_id"], :input[name^="option"], :input[name^="quantity"]').change(function(){
        $.ajax({
            type: 'post',
            url: 'index.php?route=tb/getProductPrice',
            dataType: 'json',
            data: $('#content :checked, #content select, #content :input[name^="quantity"], #content :input[name^="product_id"]'),
            success: function (data) {
                if (typeof data.error != "undefined") {
                    return;
                }

                var $priceWrap = $('.tb_wt_product_price_system');

                if (!$priceWrap.find('.tb_integer')) {
                    return;
                }

                if ($priceWrap.has('.price-old').length) {
                    $priceWrap.find('.price-old').html(data.price);
                    $priceWrap.find('.price-new').html(data.special);
                    $priceWrap.find('.price-savings strong').text(data.savings_sum);
                } else {
                    $priceWrap.find('.price-regular').html(data.price);
                }
                $priceWrap.find(".price-tax span").html(data.subtotal);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
});
</script>
<?php endif; ?>
<?php } ?>
<?php $tbData->slotStop(); ?>


<?php // Product Discounts -------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_discounts', array('data' => $data)); ?>
<?php if ($price): ?>
<?php if ($discounts) { ?>
<?php if ($tbData['system.product_discounts']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $tbData->text_product_discount; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body">
  <table{{table_classes}}>
    <thead>
      <tr>
        <th><?php echo $tbData->text_product_order_quantity; ?></th>
        <th><?php echo $tbData->text_product_price_per_item; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($discounts as $discount): ?>
      <tr>
        <td><?php echo sprintf($tbData->text_product_discount_items, $discount['quantity']); ?></td>
        <td><?php echo $discount['price']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php } ?>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Options ---------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_options', array('data' => $data)); ?>
<?php if ($options || $recurrings): ?>
<?php if ($tbData['system.product_options']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $text_option; ?></h2>
</div>
<?php endif; ?>

<?php $tbData->slotFilter('product/product_options.filter', $options, array('data' => $data)); ?>
<div class="options panel-body form-horizontal">

  <?php foreach ($options as $option) { ?>

  <?php // SELECT  ?>
  <?php if ($option['type'] == 'select') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
          <?php if ($option_value['price']) { ?>
          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
          <?php } ?>
        </option>
        <?php } ?>
      </select>
    </div>
  </div>
  <?php } ?>

  <?php // RADIO  ?>
  <?php if ($option['type'] == 'radio') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?><?php if (isset($option['tb_css_classes']) && !empty($option['tb_css_classes'])) echo' tb_' . $option['tb_css_classes']; ?>">
    <?php if ($tbData->OcVersionGte('2.3.0.0') && !empty($option['tb_css_classes']) && $option['tb_css_classes'] == 'style_2' && isset($option['image_width'])): ?>
    <style scoped>
    #input-option<?php echo $option['product_option_id']; ?> img {
      width: <?php echo $option['image_width']; ?>px;
      height: <?php echo $option['image_height']; ?>px;
    }
    </style>
    <?php endif; ?>
    <label class="control-label"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <div id="input-option<?php echo $option['product_option_id']; ?>">
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <div class="radio<?php if (!empty($option_value['image']) && $tbData->OcVersionGte('2.3.0.0')): ?> image<?php endif; ?>">
          <label<?php if (isset($option['tb_css_classes']) && !empty($option['tb_css_classes']) && $option['tb_css_classes'] == 'style_2'): ?> class="tb_bg_str_2 tb_bg_hover_str_3"<?php endif; ?><?php if ($option_value['price']) { ?> title="<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>"<?php } ?>>
            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
            <?php if ($tbData->OcVersionGte('2.3.0.0')): ?>
            <?php if ($option_value['image']) { ?>
            <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
            <?php } ?>
            <?php endif; ?>
            <span>
              <?php echo $option_value['name']; ?>
              <?php if ($option_value['price']) { ?><span>
              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
              </span><?php } ?>
            </span>
          </label>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php // CHECKBOX  ?>
  <?php if ($option['type'] == 'checkbox') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?><?php if (isset($option['tb_css_classes']) && !empty($option['tb_css_classes'])) echo' tb_' . $option['tb_css_classes']; ?>">
    <?php if ($tbData->OcVersionGte('2.2.0.0') && !empty($option['tb_css_classes']) && $option['tb_css_classes'] == 'style_2' && isset($option['image_width'])): ?>
    <style scoped>
    #input-option<?php echo $option['product_option_id']; ?> img {
      width: <?php echo $option['image_width']; ?>px;
      height: <?php echo $option['image_height']; ?>px;
    }
    </style>
    <?php endif; ?>
    <label class="control-label"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <div id="input-option<?php echo $option['product_option_id']; ?>">
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
        <div class="checkbox<?php if ($option_value['image']) echo ' image'; ?>">
        <?php else: ?>
        <div class="checkbox<?php if (!empty($option_value['image']) && $tbData->OcVersionGte('2.2.0.0')): ?> image<?php endif; ?>">
        <?php endif; ?>
          <label<?php if (isset($option['tb_css_classes']) && !empty($option['tb_css_classes']) && $option['tb_css_classes'] == 'style_2'): ?> class="tb_bg_str_2 tb_bg_hover_str_3"<?php endif; ?><?php if ($option_value['price']) { ?> title="<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>"<?php } ?>>
            <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
            <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
            <?php if ($option_value['image']) { ?>
            <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
            <?php } ?>
            <?php endif; ?>
            <span>
              <?php echo $option_value['name']; ?>
              <?php if ($option_value['price']) { ?><span>
              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
              </span><?php } ?>
            </span>
          </label>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php // IMAGE  ?>
  <?php if ($option['type'] == 'image') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?><?php if (isset($option['tb_css_classes']) && !empty($option['tb_css_classes'])) echo' tb_' . $option['tb_css_classes']; ?>">
    <?php if (!empty($option['tb_css_classes']) && $option['tb_css_classes'] == 'style_2' && isset($option['image_width'])): ?>
    <style scoped>
    #input-option<?php echo $option['product_option_id']; ?> img {
      width: <?php echo $option['image_width']; ?>px;
      height: <?php echo $option['image_height']; ?>px;
    }
    </style>
    <?php endif; ?>
    <label class="control-label"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <div id="input-option<?php echo $option['product_option_id']; ?>">
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <div class="radio image">
          <label<?php if (isset($option['tb_css_classes']) && !empty($option['tb_css_classes']) && $option['tb_css_classes'] == 'style_2'): ?> class="tb_bg_str_2 tb_bg_hover_str_3" title="<?php echo $option_value['name']; ?><?php if ($option_value['price']) { ?> (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)<?php } ?>"<?php endif; ?>>
            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
            <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" />
            <span>
              <?php echo $option_value['name']; ?>
              <?php if ($option_value['price']) { ?><span>
              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
              </span><?php } ?>
            </span>
          </label>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php // TEXT  ?>
  <?php if ($option['type'] == 'text') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
    </div>
  </div>
  <?php } ?>

  <?php // TEXTAREA  ?>
  <?php if ($option['type'] == 'textarea') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
    <div class="col-sm-10">
      <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
    </div>
  </div>
  <?php } ?>

  <?php // FILE  ?>
  <?php if ($option['type'] == 'file') { ?>
  <?php $hasFile = true; ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
      <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
    </div>
  </div>
  <?php } ?>

  <?php // DATE  ?>
  <?php if ($option['type'] == 'date') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-group date">
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
        <span class="input-group-btn">
          <button class="btn btn-default tb_no_text" type="button"><i class="fa fa-calendar"></i></button>
        </span>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php // DATETIME  ?>
  <?php if ($option['type'] == 'datetime') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <div class="input-group datetime">
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
        <span class="input-group-btn">
          <button type="button" class="btn btn-default tb_no_text"><i class="fa fa-calendar"></i></button>
        </span>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php // TIME  ?>
  <?php if ($option['type'] == 'time') { ?>
  <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
    <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
    <div class="col-sm-9">
      <div class="input-group time">
        <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
        <span class="input-group-btn">
          <button type="button" class="btn btn-default tb_no_text"><i class="fa fa-calendar"></i></button>
        </span>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php } ?>

  <?php // RECURRING  ?>
  <?php if ($recurrings) { ?>
  <div class="form-group required">
    <label><?php echo $text_payment_recurring ?></label>
    <div class="col-sm-10">
      <select name="recurring_id" class="form-control">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($recurrings as $recurring) { ?>
        <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
        <?php } ?>
      </select>
      <div class="help-block" id="recurring-description"></div>
    </div>
  </div>
  <?php } ?>

</div>

<script>

// Datetimepicker

$('.date').datetimepicker({
  pickTime: false
});
$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true,
  icons: {
    time: 'fa fa-clock-o',
    date: 'fa fa-calendar',
    up:   'fa fa-angle-up',
    down: 'fa fa-angle-down'
  }
});
$('.time').datetimepicker({
  pickDate: false,
  icons: {
    up:   'fa fa-angle-up',
    down: 'fa fa-angle-down'
  }
});

// Option select

$('.options .tb_style_2 .radio').bind('click', function() {
    $(this).closest('.form-group').find('.tb_checked').removeClass('tb_checked tb_main_color_bg').addClass('tb_bg_hover_str_3');
    $(this).find('> label').removeClass('tb_bg_hover_str_3').addClass('tb_checked tb_main_color_bg');
});
$('.options .tb_style_2 .checkbox').bind('click', function(e) {
  $(this).find('> label').toggleClass('tb_checked tb_main_color_bg', $(this).find('input[type="checkbox"]').prop("checked"));
});

// File upload

$('button[id^=\'button-upload\']').on('click', function() {
  var node = this;

  $('#form-upload').remove();

  $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

  $('#form-upload input[name=\'file\']').trigger('click');

  if (typeof timer != 'undefined') {
    clearInterval(timer);
  }

  timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);
      $.ajax({
        url: 'index.php?route=tool/upload',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-upload')[0]),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $(node).after('<i class="fa fa-circle-o-notch fa-spin"></i>');
          $(node).attr('disabled', true);
        },
        success: function(json) {
          $('.text-danger').remove();
          $(node).next('.fa-spin').remove();
          $(node).attr('disabled', false);

          if (json['error']) {
            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
          }

          if (json['success']) {
            alert(json['success']);

            $(node).parent().find('input').attr('value', json['code']);
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }, 500);
});

// Reccuring

$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
  $.ajax({
    url: 'index.php?route=product/product/getRecurringDescription',
    type: 'post',
    data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
    dataType: 'json',
    beforeSend: function() {
      $('#recurring-description').html('');
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if (json['success']) {
        $('#recurring-description').html(json['success']);
      }
    }
  });
});
</script>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Buy -------------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_buy', array('data' => $data)); ?>
<?php if (($price && $button_cart) || $tbData->common['wishlist_enabled'] || $tbData->common['compare_enabled']): ?>
<div id="product"></div>
<div class="tb_cart_wrap">
  <?php if ($price && $button_cart): ?>
  <div class="tb_purchase_button">
      
      <?php //if($config_using_warehouse_module) { ?>
      <button style="white-space:normal;height:70px;line-height:30px;" type="button" data-toggle="modal" data-target="#Modal01" class="btn btn-primary btn-lg btn-block" onclick="javascript:callLoadingImg();apiCheckStock('<?php echo $config_using_warehouse_module;?>', '<?php echo $product_id;?>', '<?php echo $data_source;?>', '<?php echo $matching_code;?>', '<?php echo $model;?>');">Check Stock&nbsp;Levels</button>
            <?php //}
            //$product_id
            //$data_source
            //$model
            //$matching_code
            //echo "<pre>";print_r($data_source);echo "</pre>";
            ?>
            <script type="text/javascript">
			function callLoadingImg() {
				if(document.getElementById("stock_count1")) {
					document.getElementById("stock_count1").innerHTML = '<img src="admin/view/image/data_sync/loading.gif" />';
				} if(document.getElementById("stock_count2")) {
					document.getElementById("stock_count2").innerHTML = '<img src="admin/view/image/data_sync/loading.gif" />';
				}
			}
			function apiCheckStock(using_warehouse, product_id, data_source, matching_code, model){
				//matching_code = "R04-09|HPB5L24A|HPB3Q11A";
				//matching_code = "R04-09|HPB3Q11A";
				$.ajax({
					url: 'index.php?route=product/product/ajaxAPI',
					type: 'post',
					data: 'apitype=stock_levels&using_warehouse=' + encodeURIComponent(using_warehouse) + '&product_id=' + encodeURIComponent(product_id) + '&data_source=' + encodeURIComponent(data_source) + '&matching_code=' + encodeURIComponent(matching_code) + '&model=' + encodeURIComponent(model),
					dataType: 'json',
					beforeSend: function() {
						//$('#recurring-description').html('');
					},
					success: function(json) {
						//$('.alert, .text-danger').remove();
						var jsonData = json.data;
						if(jsonData!="") {
							//jsonData = JSON.stringify(jsonData);
							//var arrData = JSON.parse(jsonData);
							if(jsonData.length==1) {
								//console.log(jsonData[0]["Bal"].replace(".0000",""));
								if(jsonData[0]["Bal"].replace(".0000","")!="") {
									var intBal = parseInt(jsonData[0]["Bal"].replace(".0000",""));
									if(!(/^[0-9]+$/.test(String(intBal)))) {
										$('#stock_count_full').html('<div class="col-md-4">Stock Available</div><div id="stock_count1" class="col-md-8">'+intBal+'</div>');
									} else if(intBal != 0) {
										$('#stock_count_full').html('<div class="col-md-9">Stock Available</div><div id="stock_count1" class="col-md-3">'+intBal+'</div>');
										//$('#stock_count1').html(intBal);
									} else {
										$('#stock_count_full').html('<div class="col-md-9">Stock Available</div><div id="stock_count1" class="col-md-3"><?php echo $stock; ?></div>');
										//$('#stock_count1').html("<?php echo $stock; ?>");
									}
								} else {
									$('#stock_count1').html("<?php echo $stock; ?>");
								}
							} else {
								var arrMatchingCodes = matching_code.split("|");
								//console.log(arrMatchingCodes.length);//return false;
								$.each(jsonData, function( index, value ) {
									//console.log(value["Bal"].replace(".0000",""));return false;
									//console.log(value["prod_code"]+"::::"+arrMatchingCodes.indexOf(value["prod_code"]));//return false;
									if(arrMatchingCodes.indexOf(value["prod_code"])!=-1) {
										//console.log(value["prod_code"]+"::::"+arrMatchingCodes.indexOf(value["prod_code"])+"::::"+value["Bal"].replace(".0000",""));//return false;
										console.log(value["Bal"]);									
										if(value["Bal"].replace(".0000","")!="") {
											$('#stock_count1').html(value["Bal"].replace(".0000",""));
										} else {
											$('#stock_count1').html(0);
										}
									} else {
										$('#stock_count1').html(0);
									}
								});
							}
						} else {
							$('#stock_count1').html(0);
						}	
					}
				});
			}
			</script>
            
<div class="modal fade" id="Modal01" role="dialog">
    <div class="modal-dialog" style="max-width:500px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h4 class="modal-title"><strong>Stock Levels</strong></h4>
            </div>
            <div class="modal-body">
                <div id="stock_count_full" class="row">
                    <div class="col-md-9">
                        Stock Available
                    </div>
                    <div id="stock_count1" class="col-md-3">
                        <img src="admin/view/image/data_sync/loading.gif" />
                    </div>
                </div>
            </div>
		</div>    
    </div>
</div>
  </div>
  <div class="tb_purchase_button">
    <label class="control-label" for="input-quantity"><?php echo $entry_qty; ?></label>
    <div class="tb_input_wrap">
      <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" data-min="<?php echo $minimum; ?>" />
      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
    </div>
    <br />
    <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn"><?php echo $button_cart; ?></button>
  </div>
  <?php endif; ?>
  <div class="tb_purchase_button">
  <div class="tb_actions">
    <?php if ($tbData->common['wishlist_enabled']): ?>
    <div class="tb_button_wishlist">
      <a class="tb_icon_10 fa-heart" href="javascript:;" onclick="wishlist.add('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
    </div>
    <?php endif; ?>
    <?php if ($tbData->common['compare_enabled']): ?>
    <div class="tb_button_compare">
      <a class="tb_icon_10 fa-retweet" href="javascript:;" onclick="compare.add('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
    </div>
    <?php endif; ?>
  </div>
  </div>
</div>
<?php if ($minimum > 1) { ?>
<div class="minimum"><?php echo $text_minimum; ?></div>
<?php } ?>

<script type="text/javascript">
tbApp.onScriptLoaded(function() {
    $('#input-quantity').TouchSpin({
        max: 1000000000,
        verticalbuttons: true,
        verticalupclass: 'fa fa-caret-up',
        verticaldownclass: 'fa fa-caret-down'
    });
});

$('#button-cart').on('click', function() {
    var url          = window.location.href,
        button_width = $('#button-cart').width(),
        button_text  = $('#button-cart').text();

    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: $('.product-info input[type=\'text\'], .product-info input[type=\'number\'], .product-info input[type=\'date\'], .product-info input[type=\'datetime\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
        dataType: 'json',
        beforeSend: function() {
            $('#button-cart').attr('disabled', true);
            $('#button-cart').text('');
            $('#button-cart').width(button_width);
            $('#button-cart').append('<i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        success: function(json) {
            $('.alert, .text-danger').remove();
            $('.form-group').removeClass('has-error');

            setTimeout(function(){
                $('#button-cart').next('.fa-spin').remove();
                $('#button-cart').css('width','');
                $('#button-cart').text(button_text);
                $('#button-cart').attr('disabled', false);
            },500);

            if (json['error']) {
                var errors = '';

                if (json['error']['option']) {
                    for (i in json['error']['option']) {
                        var element = $('#input-option' + i.replace('_', '-'));
            
                        element.parents('.form-group').first().find('> label + div').append('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                    }
                }
                if (json['error']['recurring']) {
                    $('select[name="recurring_id"]').after('<span class="error">' + json['error']['recurring'] + '</span>');
                }
                // Highlight any found errors
                $('.text-danger').each(function() {
                    $(this).parents('.form-group').first().addClass('has-error');
                });
                // Popup any found errors
                // displayNotice('product', 'failure', 'product', errors);
            }
            if (json['success']) {
                $.get('index.php?route=common/cart/info', function(result) {
                    var $container = $(tbRootWindow.document).find('.tb_wt_header_cart_menu_system');

                    $container.find('.heading').replaceWith($(result).find('.heading').clone());
                    $container.find('.content').replaceWith($(result).find('.content').clone());

                    tbApp.triggerResizeCallbacks();
                });

                displayNotice('product', 'success', <?php echo $product_id; ?>, json['success']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
</script>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Special price counter ---------------------------------------- ?>

<?php $tbData->slotStart('product/product.special_price_counter', array('data' => $data)); ?>
<?php if (!empty($product_info['special_date_end'])): ?>
<div class="tb_counter tb_style_1">
  <span class="tb_counter_label h4"><?php echo $tbData->text_offer_ending; ?></span>
  <span class="tb_counter_time" data-special-price-end-date="<?php echo date('Y/m/d', strtotime($product_info['special_date_end'])); ?>">
    <?php
    $total_seconds = strtotime($product_info['special_date_end']) - time();
    $days          = floor($total_seconds / 86400);
    $hours         = floor(($total_seconds % 86400) / 3600);
    $minutes       = floor((($total_seconds % 86400) % 3600 ) / 60);
    $seconds       = floor(((($total_seconds % 86400) % 3600 ) % 3600 ) / 60);
    ?>
    <span class="tb_counter_days"><?php echo $days; ?></span>
    <span class="tb_counter_hours"><?php echo $hours; ?></span>
    <span class="tb_counter_minutes"><?php echo $minutes; ?></span>
    <span class="tb_counter_seconds"><?php echo $seconds; ?></span>
  </span>
</div>

<script type="text/javascript">
tbApp.onScriptLoaded(function() {
  create_countdown('#{{widget_dom_id}}','<?php echo date('D M d Y H:i:s O'); ?>', '<?php echo date('Z') / 3600 - date('I'); ?>');
});
</script>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Reviews Summary -------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_reviews_summary', array('data' => $data)); ?>
<?php if ($review_status): ?>
<?php if ($rating): ?>
<div class="rating responsive" data-sizes="320,0">
  <div class="tb_bar">
    <span class="tb_percent" style="width: <?php echo $rating * 20; ?>%;"></span>
    <span class="tb_base"></span>
  </div>
  <span class="tb_average"><?php echo $rating; ?>/5</span>
  <span class="tb_total">(<?php echo $reviews; ?>)</span>
  <a class="tb_review_write tb_main_color border-color" data-toggle="modal" data-target="#tbReviewFormWrap">
    <span class="tb_icon fa-pencil"></span><?php echo $text_write; ?>
  </a>
</div>
<?php else: ?>
<div class="rating responsive" data-sizes="320,0">
  <div class="tb_bar">
    <span class="tb_base"></span>
  </div>
  <span class="tb_total"><?php echo $tbData->text_product_not_yet_rated; ?></span>
  <a class="tb_review_write tb_main_color border-color" data-toggle="modal" data-target="#tbReviewFormWrap">
    <span class="tb_icon fa-pencil"></span><?php echo $text_write; ?>
  </a>
</div>
<?php endif; ?>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Share ------------------------------------------------ ?>

<?php $tbData->slotStart('product/product.product_share', array('data' => $data)); ?>
<?php if ($tbData['system.product_share']['button_facebook']
       || $tbData['system.product_share']['button_twitter']
       || $tbData['system.product_share']['button_google']
       || $tbData['system.product_share']['button_pinterest']
       || $tbData['system.product_share']['button_stumbleupon']
       || $tbData['system.product_share']['button_linkedin']
       || $tbData['system.product_share']['button_custom']): ?>
<?php if ($tbData['system.product_share']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $tbData->text_product_share; ?></h2>
</div>
<?php endif; ?>
<?php if (!$tbData['system.product_share']['button_custom']): ?>
<div class="panel-body tb_share_box">
  <?php if ($tbData['system.product_share']['button_facebook']): ?>
  <div class="tb_facebook">
    <a class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="<?php if (!empty($tbData['system.product_share']['button_facebook_share'])): ?>true<?php else: ?>false<?php endif; ?>"></a>
  </div>
  <?php endif; ?>
  <?php if ($tbData['system.product_share']['button_twitter']): ?>
  <div class="tb_twitter">
    <a class="twitter-share-button" href="https://twitter.com/share">Tweet</a>
  </div>
  <?php endif; ?>
  <?php if ($tbData['system.product_share']['button_google']): ?>
  <div class="tb_gplus" style="width: 57px;">
    <a class="g-plusone" data-size="medium"></a>
  </div>
  <?php endif; ?>
  <?php if ($tbData['system.product_share']['button_pinterest']): ?>
  <div class="tb_pinterest">
    <a href="//www.pinterest.com/pin/create/button/?url=<?php echo $tbData->current_url; ?>&media=<?php echo $thumb; ?>" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
  </div>
  <?php endif; ?>
  <?php if ($tbData['system.product_share']['button_stumbleupon']): ?>
  <div class="tb_stumbleupon">
    <su:badge layout="1"></su:badge>
  </div>
  <?php endif; ?>
  <?php if ($tbData['system.product_share']['button_linkedin']): ?>
  <div class="tb_social_button tb_linkedin">
    <script src="//platform.linkedin.com/in.js" type="text/javascript">lang: <?php if($tbData->facebook['locale']): ?><?php echo $tbData->facebook['locale']; ?><?php else: ?>en_EN<?php endif; ?></script>
    <script type="IN/Share" data-url="<?php echo $tbData->current_url; ?>" data-counter="right"></script>
  </div>
  <?php endif; ?>
</div>
<script>
tbApp.onScriptLoaded(function() {

    <?php // Tweet button ?>
    <?php if ($tbData['system.product_share']['button_twitter']): ?>
    var loadTwitter = function () {
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
    };
    <?php endif; ?>

    <?php // Google +1 ?>
    <?php if ($tbData['system.product_share']['button_google']): ?>
    var loadGplus = function () {
        (function(){var po=document.createElement('script');po.type='text/javascript';po.async=true;po.src='https://apis.google.com/js/platform.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();
    };
    <?php endif; ?>

    <?php // Pin button ?>
    <?php if ($tbData['system.product_share']['button_pinterest']): ?>
    var loadPinterest = function () {
        (function(){var po=document.createElement('script');po.type='text/javascript';po.async=true;po.src='//assets.pinterest.com/js/pinit.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();
    };
    <?php endif; ?>

    <?php // Stumble Upon ?>
    <?php if ($tbData['system.product_share']['button_stumbleupon']): ?>
    var loadSU = function () {
        (function(){var li=document.createElement('script');li.type='text/javascript';li.async=true;li.src=('https:'==document.location.protocol?'https:':'http:')+'//platform.stumbleupon.com/1/widgets.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(li,s);})();
    };
    <?php endif; ?>

    <?php if ($tbData->system['js_lazyload']): ?>
    $(document).on('lazybeforeunveil', function(e) {
        if ($(e.target).filter('#{{widget_dom_id}}').length) {

            <?php // Facebook like ?>
            <?php if ($tbData['system.product_share']['button_facebook']): ?>
            var parseFBXML = function() {
                FB.XFBML.parse(document.getElementById('{{widget_dom_id}}'));
            };
            if (window.FB_XFBML_parsed === undefined) {
                window.FB_XFBML_parsed = parseFBXML;
            } else {
                parseFBXML();
            }
            <?php endif; ?>

            <?php // Tweet button ?>
            <?php if ($tbData['system.product_share']['button_twitter']): ?>
            loadTwitter();
            <?php endif; ?>

            <?php // Google +1 ?>
            <?php if ($tbData['system.product_share']['button_google']): ?>
            loadGplus();
            <?php endif; ?>

            <?php // Pin button ?>
            <?php if ($tbData['system.product_share']['button_pinterest']): ?>
            loadPinterest();
            <?php endif; ?>

            <?php // Stumble Upon ?>
            <?php if ($tbData['system.product_share']['button_stumbleupon']): ?>
            loadSU();
            <?php endif; ?>
        }
    });
    <?php else: ?>

    <?php // Tweet button ?>
    <?php if ($tbData['system.product_share']['button_twitter']): ?>
    loadTwitter();
    <?php endif; ?>

    <?php // Google +1 ?>
    <?php if ($tbData['system.product_share']['button_google']): ?>
    loadGplus();
    <?php endif; ?>

    <?php // Pin button ?>
    <?php if ($tbData['system.product_share']['button_pinterest']): ?>
    loadPinterest();
    <?php endif; ?>

    <?php // Stumble Upon ?>
    <?php if ($tbData['system.product_share']['button_stumbleupon']): ?>
    loadSU();
    <?php endif; ?>

    <?php endif; ?>
});
</script>
<?php else: ?>
<div class="tb_share_box_custom">
  <?php echo $tbData['system.product_share']['button_custom'] ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Description ------------------------------------------ ?>

<?php $tbData->slotStart('product/product.product_description', array('data' => $data)); ?>
<?php if ($tbData['system.product_description']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $tab_description; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body tb_product_description tb_text_wrap">
  <?php echo $description; ?>
</div>

<?php // Rich Snippets ------------------------------------------------ ?>

<?php if (!isset($tbData['seo_settings']['google_microdata']) || !empty($tbData['seo_settings']['google_microdata'])): ?>
<script type="application/ld+json">
{
  "@context":         "http://schema.org/",
  "@type":            "Product",
  "name":             "<?php echo $heading_title; ?>",
  "image":            "<?php echo $thumb; ?>",
  "description":      "<?php echo utf8_substr(strip_tags($description), 0, 200); ?>",
  <?php if ($tbData->common['manufacturers_enabled'] && $manufacturer): ?>
  "brand":{
    "@type":          "Thing",
    "name":           "<?php echo $manufacturer; ?>"
  },
  <?php endif; ?>
  <?php if ($rating): ?>
  "aggregateRating":{
    "@type":          "AggregateRating",
    "ratingValue":    "<?php echo $rating; ?>",
    "reviewCount":    "<?php echo filter_var($reviews, FILTER_SANITIZE_NUMBER_INT); ?>"
  },
  <?php endif; ?>
  "offers":{
    "@type":          "Offer",
    "priceCurrency":  "<?php echo $tbData->currency_code; ?>",
    <?php if (!$special): ?>
    "price":          "<?php echo $product_info['price_num']; ?>",
    <?php else: ?>
    "price":          "<?php echo $product_info['special_num']; ?>",
    <?php endif; ?>
    "availability":   "<?php echo strip_tags($stock); ?>",
    "seller":{
      "@type":        "Organization",
      "name":         "Executive Objects"
    }
  }
}
</script>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Attributes ------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_attributes', array('data' => $data)); ?>
<?php if ($attribute_groups): ?>
<?php if ($tbData['system.product_attributes']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $tab_attribute; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body tb_product_attributes">
  <table class="table">
    <?php foreach ($attribute_groups as $attribute_group): ?>
    <thead>
      <tr>
        <th colspan="2"><?php echo $attribute_group['name']; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($attribute_group['attribute'] as $attribute): ?>
      <tr>
        <td width="30%"><?php echo $attribute['name']; ?></td>
        <td><?php echo $attribute['text']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <?php endforeach; ?>
  </table>
</div>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php // Product Reviews ---------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_reviews', array('data' => $data)); ?>
<?php if ($review_status) { ?>
<?php if ($tbData['system.product_reviews']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $tab_review; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body tb_product_reviews">
  <div id="review"></div>
  <a class="btn" data-toggle="modal" data-target="#tbReviewFormWrap"><?php echo $text_write; ?></a>
  <div id="tbReviewFormWrap" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $text_write; ?></h2>
          <a class="close" href="javascript:;" data-dismiss="modal">
            <svg><use xlink:href="<?php echo $tbData->current_url; ?>#close" /></svg>
          </a>
        </div>
        <div class="modal-body">
          <?php if ($review_guest) { ?>
          <form class="tbReviewForm form-vertical">
            <div class="form-group required">
              <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
              <div class="col-sm-12">
                <?php if ($tbData->OcVersionGte('2.2.0.0')): ?>
                <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />
                <?php else: ?>
                <input type="text" name="name" value="" id="input-name" class="form-control" />
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
              <div class="col-sm-12 tb_full">
                <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                <div class="help-block"><?php echo $text_note; ?></div>
              </div>
            </div>
            <div class="form-group required">
              <label class="control-label"><?php echo $entry_rating; ?></label>
              <div class="col-sm-12">
                <?php echo $entry_bad; ?>&nbsp;&nbsp;&nbsp;
                <input type="radio" name="rating" value="1" />
                &nbsp;
                <input type="radio" name="rating" value="2" />
                &nbsp;
                <input type="radio" name="rating" value="3" />
                &nbsp;
                <input type="radio" name="rating" value="4" />
                &nbsp;
                <input type="radio" name="rating" value="5" />
                &nbsp;<?php echo $entry_good; ?></div>
            </div>
            <?php if ($tbData->OcVersionGte('2.1.0.0')): ?>
            <?php echo $captcha; ?>
            <?php elseif ($tbData->OcVersionGte('2.0.2.0')): ?>
            <?php if ($site_key) { ?>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
              </div>
            </div>
            <?php } ?>
            <?php else: ?>
            <div class="form-group required">
              <label class="control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
              <div class="col-sm-12">
                <input type="text" name="captcha" value="" id="input-captcha" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <img src="index.php?route=tool/captcha" alt="" id="captcha" />
            </div>
            <?php endif; ?>
            <span class="clear tb_sep"></span>
            <div class="buttons clearfix">
              <div class="pull-right">
                <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
              </div>
            </div>
          </form>
          <?php } else { ?>
          <p class="tb_text_wrap tb_empty"><?php echo $text_login; ?></p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
tbApp.onScriptLoaded(function() {
    $('#review').delegate('.pagination a', 'click', function(e) {
      e.preventDefault();
      $('#review').fadeOut('slow');

      $('#review').load(this.href);

      $('#review').fadeIn('slow');

    });

    $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

    $('#tbReviewFormWrap').detach().appendTo('body');

    $('.tbReviewForm .buttons .btn').bind('click', function() {
        $.ajax({
            url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
            type: 'post',
            dataType: 'json',
            <?php if ($tbData->OcVersionGte('2.0.2.0')): ?>
            data: $(".tbReviewForm").serialize(),
            <?php else: ?>
            data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
            <?php endif; ?>
            beforeSend: function() {
                $('.tbReviewForm').addClass('tb_blocked tb_loading');
                $('.tbReviewForm').prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('.tbReviewForm .buttons .tb_button').attr('disabled', true);
            },
            complete: function() {
                $('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
                $('input[name=\'captcha\']').val('');
            },
            success: function(json) {
                $('.tbReviewForm .alert').remove();
                $('.tbReviewForm').find('> .fa-spin').remove();
                $('.tbReviewForm').removeClass('tb_blocked tb_loading');
                $('.tbReviewForm .tb_submit .tb_button').attr('disabled', false);

                if (json['error']) {
                    $('.tbReviewForm').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('.tbReviewForm').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    $('.tbReviewForm').hide();

                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                    $('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
                    $('input[name=\'captcha\']').val('');
                    if (typeof grecaptcha != 'undefined' && grecaptcha.reset !== undefined) {
                        grecaptcha.reset();
                    }

                    var closeDialogTimeout = setTimeout(function(){
                        $("#tbReviewFormWrap").modal('hide');
                    }, 4000);

                    $("#tbReviewFormWrap").on('hidden.bs.modal', function() {
                        $('#tbReviewFormWrap .alert').remove();
                        $('.tbReviewForm').show();
                        clearTimeout(closeDialogTimeout);
                    });
                }
            }
        });
    });
});
</script>
<?php } ?>
<?php $tbData->slotStop(); ?>


<?php // Related Products --------------------------------------------- ?>

<?php if ($tbData->slotStartSystem('product/product.related_products', array('filter' => array('product/product.related_products.filter', 'products' => &$products), 'data' => $data), true)): ?>
<?php if ($product_settings_context = $tbData['products_related']) extract($product_settings_context); ?>
<?php if ($products) { ?>
<?php if ($tbData['system.related_products']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $tbData->text_product_tab_related; ?></h2>
</div>
<?php endif; ?>
<div class="panel-body">
  <div class="tb_products tb_listing <?php echo $listing_classes; ?>">
    <?php $has_counter = false; ?>
    <?php foreach ($products as $product) { ?>
    <?php $tbData->slotStart('products_listing.product', array('product' => $product, 'data' => $data, 'product_settings_context' => $product_settings_context)); ?>
    <div class="product-layout">
      <input class="product-id_<?php echo $product['product_id']; ?>" type="hidden" value="" />
      <div class="product-thumb">
        <?php if ($product['thumb'] && $show_thumb): $tbData->slotStart('products_listing.product.thumb'); ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><span style="max-width: <?php echo $product['thumb_width']; ?>px;"><span style="padding-top: <?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%"><img src="<?php echo $product['thumb']; ?>"<?php if ($tbData->system['image_lazyload']): ?> data-src="<?php echo $product['thumb_original']; ?>" class="lazyload"<?php endif; ?> width="<?php echo $product['thumb_width']; ?>" height="<?php echo $product['thumb_height']; ?>" alt="<?php echo $product['name']; ?>" style="margin-top: -<?php echo round($product['thumb_height'] / $product['thumb_width'], 4) * 100; ?>%" /></span></span></a></div>
        <?php if ($product['thumb_hover']): ?>
        <div class="image_hover"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif" data-src="<?php echo $product['thumb_hover']; ?>" width="<?php echo $product['thumb_width']; ?>" height="<?php echo $product['thumb_height']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php endif; ?>
        <?php $tbData->slotCaptureEcho(); endif; ?>
        <div>
          <div class="caption">
            <?php if ($show_title): $tbData->slotStart('products_listing.product.title'); ?>
            <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
            <?php $tbData->slotCaptureEcho(); endif; ?>
            <?php if ($product['description']): $tbData->slotStart('products_listing.product.description'); ?>
            <div class="description"><?php echo $product['description']; ?></div>
            <?php $tbData->slotCaptureEcho(); endif; ?>
            <?php if ($product['price']) { ?>
            <?php $tbData->slotStart('products_listing.product.price'); ?>
            <p class="price">
              <?php if (!$product['special']) { ?>
              <span class="price-regular"><?php echo $product['price']; ?></span>
              <?php } else { ?>
              <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
              <?php } ?>
            </p>
            <?php $tbData->slotCaptureEcho(); ?>
            <?php if ($product['tax']) { ?>
            <?php $tbData->slotStart('products_listing.product.tax'); ?>
            <span class="price-tax"><span><?php echo $text_tax; ?></span> <?php echo $product['tax']; ?></span>
            <?php $tbData->slotCaptureEcho(); ?>
            <?php } ?>
            <?php } ?>
            <?php if ($product['special'] && $product['special_date_end']): ?>
            <?php $tbData->slotStart('products_listing.product.special_price_end'); ?>
            <?php $has_counter = true; ?>
            <p class="tb_counter tb_style_1">
              <span class="tb_counter_label h4"><?php echo $tbData->text_offer_ending; ?></span>
              <span class="tb_counter_time" data-special-price-end-date="<?php echo date('Y/m/d', strtotime($product['special_date_end'])); ?>">
                <?php
                $total_seconds = strtotime($product['special_date_end']) - time();
                $days          = floor($total_seconds / 86400);
                $hours         = floor(($total_seconds % 86400) / 3600);
                $minutes       = floor((($total_seconds % 86400) % 3600 ) / 60);
                $seconds       = floor(((($total_seconds % 86400) % 3600 ) % 3600 ) / 60);
                ?>
                <span class="tb_counter_days"><?php echo $days; ?></span>
                <span class="tb_counter_hours"><?php echo $hours; ?></span>
                <span class="tb_counter_minutes"><?php echo $minutes; ?></span>
                <span class="tb_counter_seconds"><?php echo $seconds; ?></span>
              </span>
            </p>
            <?php $tbData->slotCaptureEcho(); ?>
            <?php endif; ?>
            <?php if ($product['rating']) { ?>
            <?php $tbData->slotStart('products_listing.product.rating'); ?>
            <div class="rating">
              <div class="tb_bar">
                <span class="tb_percent" style="width: <?php echo $product['rating'] * 20; ?>%;"></span>
                <span class="tb_base"></span>
              </div>
              <span class="tb_average"><?php echo $product['rating']; ?>/5</span>
            </div>
            <?php $tbData->slotCaptureEcho(); ?>
            <?php } ?>
          </div>
          <?php if ($product['show_cart'] || $show_wishlist || $show_compare): ?>
          <div class="button-group">
            <?php if ($product['show_cart']): $tbData->slotStart('products_listing.product.button_cart'); ?>
            <div class="tb_button_add_to_cart<?php echo $cart_button_position_classes; ?>"<?php echo $cart_button_offset_attr; ?>>
              <?php if ($tbData->OcVersionGte('2.0.2.0')): ?>
              <a class="<?php echo $cart_button_classes; ?>" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');">
              <?php else: ?>
              <a class="<?php echo $cart_button_classes; ?>" href="javascript:;" onclick="cart.add('<?php echo $product['product_id']; ?>');">
              <?php endif; ?>
                <span data-tooltip="<?php echo $product['text_button_cart']; ?>"><?php echo $product['text_button_cart']; ?></span>
              </a>
            </div>
            <?php $tbData->slotCaptureEcho(); endif; ?>
            <?php if ($show_wishlist): $tbData->slotStart('products_listing.product.button_wishlist'); ?>
            <div class="tb_button_wishlist<?php echo $wishlist_button_position_classes; ?>"<?php echo $wishlist_button_offset_attr; ?>>
              <a class="<?php echo $wishlist_button_classes; ?>" href="javascript:;" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
                <span data-tooltip="<?php echo $tbData->text_wishlist; ?>"><?php echo $tbData->text_wishlist; ?></span>
              </a>
            </div>
            <?php $tbData->slotCaptureEcho(); endif; ?>
            <?php if ($show_compare): $tbData->slotStart('products_listing.product.button_compare'); ?>
            <div class="tb_button_compare<?php echo $compare_button_position_classes; ?>"<?php echo $compare_button_offset_attr; ?>>
              <a class="<?php echo $compare_button_classes; ?>" href="javascript:;" onclick="compare.add('<?php echo $product['product_id']; ?>');">
                <span data-tooltip="<?php echo $tbData->text_compare; ?>"><?php echo $tbData->text_compare; ?></span>
              </a>
            </div>
            <?php $tbData->slotCaptureEcho(); endif; ?>
            <?php if ($show_quickview): $tbData->slotStart('products_listing.product.button_quickview'); ?>
            <div class="tb_button_quickview<?php echo $quickview_button_position_classes; ?>"<?php echo $quickview_button_offset_attr; ?>>
              <a class="<?php echo $quickview_button_classes; ?>" href="javascript:;" onclick="tbQuickView('<?php echo $product['product_id']; ?>');">
                <span data-tooltip="<?php echo $tbData->text_quickview; ?>"><?php echo $tbData->text_quickview; ?></span>
              </a>
            </div>
            <?php $tbData->slotCaptureEcho(); endif; ?>
          </div>
          <?php endif; ?>
          <?php if ($product['show_stock']): $tbData->slotStart('products_listing.product.stock_status'); ?>
          <p class="tb_label_stock_status"><?php echo $product['stock_status']; ?></p>
          <?php $tbData->slotCaptureEcho(); endif; ?>
        </div>
        <?php if ($product['show_label_sale']): $tbData->slotStart('products_listing.product.label_sale'); ?>
        <p class="tb_label_special"><?php echo $product['savings_text']; ?></p>
        <?php $tbData->slotCaptureEcho(); endif; ?>
        <?php if ($show_label_new && $product['is_new']): $tbData->slotStart('products_listing.product.label_new'); ?>
        <p class="tb_label_new"><?php echo $tbData->text_label_new; ?></p>
        <?php $tbData->slotCaptureEcho(); endif; ?>
      </div>
    </div>
    <?php $tbData->slotStopEcho(); ?>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">

tbApp.init{{widget_dom_id}} = function() {
    tbApp.onScriptLoaded(function() {

        if (!tbUtils.is_touch) {

            <?php // THUMB HOVER ?>
            <?php if ($thumbs_hover_action != 'none'): ?>
            thumb_hover('#{{widget_dom_id}}', '<?php echo $thumbs_hover_action; ?>');
            <?php endif; ?>

            <?php // THUMB ZOOM ?>
            <?php if ($thumbs_hover_action == 'zoom'): ?>
            $('#{{widget_dom_id}}').find('.tb_zoom > img').elevateZoom({
                zoomType:           'inner',
                zoomWindowFadeIn:   300,
                zoomWindowFadeOut:  300,
                cursor:             'crosshair'
            });
            <?php endif; ?>

            <?php // PRODUCT HOVER ?>
            <?php if (!(!empty($slider) && $elements_hover_action == 'append') && $elements_hover_action != 'none'): ?>
            item_hover('#{{widget_dom_id}}', '<?php echo $active_elements; ?>', '<?php echo $hover_elements; ?>', '<?php echo $elements_hover_action; ?>');
            <?php endif; ?>

        }

        <?php // PRODUCT SLIDER ?>
        <?php if (!empty($slider)): ?>
        tbApp.itemSlider{{widget_dom_id}} = createItemSlider('#{{widget_dom_id}}', <?php echo count($products); ?>, <?php echo $slider_step; ?>, <?php echo $slider_speed; ?>, <?php $slider_pagination ? print '\'#{{widget_dom_id}} .tb_slider_pagination\'' : print 'false' ; ?>, <?php echo $restrictions_json; ?>, <?php echo $slider_autoplay; ?>, <?php echo $slider_loop; ?>);

        if (tbApp.itemSlider{{widget_dom_id}}SwiperPromiseCallback !== undefined) {
            tbApp.itemSlider{{widget_dom_id}}.swiperPromise.done(tbApp.itemSlider{{widget_dom_id}}SwiperPromiseCallback);
        }
        <?php endif; ?>

        <?php // PRODUCT COUNTER ?>
        <?php if ($has_counter): ?>
        <?php if (!empty($slider)): ?>
        tbApp.itemSlider{{widget_dom_id}}.swiperPromise.done(function() {
          create_countdown('#{{widget_dom_id}}','<?php echo date('D M d Y H:i:s O'); ?>', '<?php echo date('Z') / 3600 - date('I'); ?>');
        });
        <?php else: ?>
        create_countdown('#{{widget_dom_id}}','<?php echo date('D M d Y H:i:s O'); ?>', '<?php echo date('Z') / 3600 - date('I'); ?>');
        <?php endif; ?>
        <?php endif; ?>

    });
};
tbApp.exec{{widget_dom_id}} = function() {
    tbApp.onScriptLoaded(function() {
        <?php // ADJUST PRODUCT SIZE ?>
        <?php if ($view_mode == 'grid'): ?>
        if ({{within_group}} || (!{{optimize_js_load}} && !<?php echo (int) $tbData->system['js_lazyload']; ?>)) {
            adjustItemSize('#{{widget_dom_id}}', <?php echo $restrictions_json; ?>);
        }
        <?php endif; ?>

        <?php // REFRESH SLIDER ?>
        <?php if (!empty($slider)): ?>
        tbApp.itemSlider{{widget_dom_id}}.refresh();
        <?php endif; ?>
    });
};

if (!{{within_group}}) {
    if (!<?php echo (int) $tbData->system['js_lazyload']; ?>) {
        tbApp.init{{widget_dom_id}}();
        tbApp.exec{{widget_dom_id}}();
    } else {
        $(document).on('lazybeforeunveil', function(e) {
            if ($(e.target).filter('#{{widget_dom_id}}').length) {
                tbApp.init{{widget_dom_id}}();
                tbApp.exec{{widget_dom_id}}();
            }
        });
    }
}
</script>
<?php if ($view_mode == 'grid'
          && empty($within_group)
          && ($tbData->optimize_js_load || $tbData->system['js_lazyload'])
          ): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#{{widget_dom_id}}', <?php echo $restrictions_json; ?>);
</script>
<?php endif; ?>
<?php } ?>
<?php $tbData->slotStop(); endif; ?>


<?php // Product Tags ------------------------------------------------- ?>

<?php $tbData->slotStart('product/product.product_tags', array('data' => $data)); ?>
<?php if ($tags): ?>
<?php if ($tbData['system.product_tags']['block_title']): ?>
<div class="panel-heading">
  <h2 class="panel-title"><?php echo $text_tags; ?></h2>
</div>
<?php endif; ?>
<ul class="panel-body tb_tags tb_style_label clearfix">
  <?php foreach ($tags as $tag): ?>
  <li><a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a></li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
<?php $tbData->slotStop(); ?>

<?php echo $footer; ?>