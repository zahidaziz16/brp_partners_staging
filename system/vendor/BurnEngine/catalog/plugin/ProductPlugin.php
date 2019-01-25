<?php

class Theme_Catalog_ProductPlugin extends TB_ExtensionPlugin
{
    public function configure(TB_ViewDataBag $themeData)
    {
        $dispatcher = $this->eventDispatcher;

        $dispatcher->connect('product/product.product_info.filter', array($this, 'filterProduct'));
        $dispatcher->connect('product/product_options.filter',      array($this, 'filterProductOptions'));
        $dispatcher->connect('oc_module_products.filter',           array($this, 'filterOcModuleProducts'));
        $dispatcher->connect('viewSlot:products_listing.product',   array($this, 'productListingLayout'));
    }

    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        $this->bootstrap('common');
        $this->bootstrap('layout_builder');

        $themeData->registerStylesheetResource('javascript/photoswipe/photoswipe.css');
        $themeData->registerStylesheetResource('javascript/photoswipe/default-skin/default-skin.css', null, array('url(' => 'url(' . $this->context->getThemeCatalogResourceUrl() . 'javascript/photoswipe/default-skin/'));
        $themeData->registerStylesheetResource('stylesheet/swiper.css');

        if ($themeData->route == 'product/product') {
            $this->engine->getExtension('fire_slider')->registerMightySlider();

            if ($themeData['system.product_images']['fullscreen']) {
                $themeData->registerJavascriptResource('javascript/photoswipe/photoswipe.min.js');
                $themeData->registerJavascriptResource('javascript/photoswipe/photoswipe-ui-default.min.js');
            }

            if ($themeData['system.product_images'] && !empty($themeData['system.product_images']['zoom'])) {
                $themeData->registerJavascriptResource('javascript/jquery.zoom.min.js');
            }
        }

        if ($themeData->route == 'product/product' && !empty($this->engine->getOcRequest()->get['product_id'])) {

            $product_layout_design_id = 'design_1';
            $product_layout_column_ratio_left = '1_2';
            $product_layout_column_ratio_right = '1_2';
            $product = $this->getSetting('product');

            if (isset($product['designs']['layout'][$themeData->layout_id])) {
                $product_layout_design_id = $product['designs']['layout'][$themeData->layout_id]['design'];
                $ratios = explode('+', $product['designs']['layout'][$themeData->layout_id]['column_ratio']);
                $product_layout_column_ratio_left = $ratios[0];
                $product_layout_column_ratio_right = $ratios[1];
            }

            $themeData->product_layout_design_id = $product_layout_design_id;
            $themeData->product_layout_column_ratio_left = $product_layout_column_ratio_left;
            $themeData->product_layout_column_ratio_right = $product_layout_column_ratio_right;

            if (!isset($this->engine->getOcSession()->data['recently_viewed_product_ids'])) {
                $this->engine->getOcSession()->data['recently_viewed_product_ids'] = array();
            }

            $product = $this->getProduct((int) $this->engine->getOcRequest()->get['product_id']);

            if ($product) {
                $this->engine->getOcSession()->data['recently_viewed_product_ids'][] = (int) $this->engine->getOcRequest()->get['product_id'];
                $this->engine->getOcSession()->data['recently_viewed_product_ids'] = array_unique($this->engine->getOcSession()->data['recently_viewed_product_ids']);
            }

            if ($product && $product['special'] && $product['price']) {
                $themeData->product_savings = sprintf($themeData->text_product_savings, round((1 - $product['special'] / $product['price'] ) * 100));
                $themeData->product_you_save = sprintf($themeData->text_you_save, $this->engine->getOcCurrency()->format(1, $themeData->currency_code, $product['price'] - $product['special']));
            } else {
                $themeData->product_savings = false;
            }

            $this->setMeta($product);
        }
    }

    protected function setMeta($product)
    {
        $themeData = $this->getThemeData();
        $themeData->fbMeta = $themeData->twitterMeta = '';

        if (!$product) {
            return;
        }

        $fb_meta = '';
        $twitter_meta = '';

        if ($product['image']) {
            $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';

            $width  = $this->engine->getOcConfig()->get($config_group . '_image_thumb_width');
            $height = $this->engine->getOcConfig()->get($config_group . '_image_thumb_height');
            
            if((strpos($product['image'], "brp.com.my")===false)) {
                $thumb  = $this->getThemeModel()->resizeImage($product['image'], $width, $height);
            } else {
                $thumb  = $this->getThemeModel()->resizeImage($product['image'], $width, $height, "", true);
            }
            
        } else {
            $thumb = $themeData->res_url . '/image/no_image.jpg';
        }

        $stock       = $product['quantity'] > 0 ? 'instock' : 'oos';
        $description = substr(htmlspecialchars(strip_tags(html_entity_decode($product['description'], ENT_COMPAT, 'UTF-8'))), 0, 300);

        // Facebook Open Graph
        $fb_meta .= '<meta property="og:type" content="product" />' . "\n";
        $fb_meta .= '<meta property="og:url" content="' . $this->engine->getOcUrl()->link('product/product', 'product_id=' . $product['product_id']) . '" />' . "\n";
        $fb_meta .= '<meta property="og:title" content="' . $product['name'] . '" />' . "\n";
        $fb_meta .= '<meta property="og:description" content="' . $description . '" />' . "\n";
        $fb_meta .= '<meta property="og:image" content="' . TB_Utils::escapeHtmlImage($thumb) . '" />' . "\n";
        $fb_meta .= '<meta property="product:availability" content="' . $stock . '" />' . "\n";
        $fb_meta .= '<meta property="product:brand" content="' . $product['manufacturer'] . '" />' . "\n";
        $fb_meta .= '<meta property="product:price:amount" content="' . $product['price'] . '" />' . "\n";
        $fb_meta .= '<meta property="product:price:currency" content="' . $themeData['currency_code'] . '" />' . "\n";
        if ($product['special']) {
            $fb_meta .= '<meta property="product:sale_price:amount" content="' . $product['special'] . '" />' . "\n";
            $fb_meta .= '<meta property="product:sale_price:currency" content="' . $themeData['currency_code'] . '" />' . "\n";
        }

        // Twitter Cards
        $twitter_meta .= '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        if (!empty($themeData['twitter']['username'])) {
            $twitter_meta .= '<meta name="twitter:site" content="@' . $themeData['twitter']['username'] . '" />' . "\n";
            $twitter_meta .= '<meta name="twitter:creator" content="@' . $themeData['twitter']['username'] . '" />' . "\n";
        }
        $twitter_meta .= '<meta name="twitter:title" content="' . $product['name'] . '" />' . "\n";
        $twitter_meta .= '<meta name="twitter:description" content="' . $description . '" />' . "\n";
        $twitter_meta .= '<meta name="twitter:image" content="' . $thumb . '" />' . "\n";

        $themeData->fbMeta      = $fb_meta;
        $themeData->twitterMeta = $twitter_meta;
    }

    public function filterOcModuleProducts(sfEvent $event, array $data)
    {
        $view_settings = $this->getThemeData()->category_products_current;

        if ($this->getThemeData()->route == 'product/compare') {
            foreach ($view_settings as $key => &$setting) {
                if (strpos($key, 'show_') === 0) {
                    $setting = true;
                }
            }
        }

        $this->modifyProducts($data['products'], $view_settings);
    }

    public function filterProduct(sfEvent $event, array $data)
    {
        $product_info = $this->getProduct($event['data']['product_id']);

        $stock_status_id = 0;
        if ($product_info['quantity'] <= 0) {
            foreach ($this->getModel('default')->getStockStatuses() as $status) {
                if ($status['name'] == $product_info['stock_status']) {
                    $stock_status_id = $status['stock_status_id'];
                    break;
                }
            }
        } else {
            $stock_status_id = 'in_stock';
        }

        $data['product_info'] = array(
            'price_num'       => $product_info['price'],
            'special_num'     => $product_info['special'],
            'stock_status_id' => $stock_status_id
        );

        if ($product_info['quantity'] < 1) {

            if (!$this->engine->getOcConfig()->get('config_stock_checkout') ||  $this->getThemeData()->store_common['disable_checkout_stock_status_name'] == $event['data']['stock']) {
                $data['button_cart'] = false;

                return;
            }

            if ($this->getThemeData()->store_common['preorder_stock_status_name'] == $event['data']['stock']) {
                $data['button_cart'] = $this->extension->translate('text_status_preorder');
            }

            if ($this->getThemeData()->store_common['backorder_stock_status_name'] == $event['data']['stock']) {
                $data['button_cart'] = $this->extension->translate('text_status_backorder');
            }
        }

        if ($product_info['special']) {
            $data['product_info']['special_date_end'] = '';

            $special_field = $this->engine->getDbHelper()->getRecord('product_special',
                'product_id = ' .  $event['data']['product_id'] . ' AND date_end > NOW() AND customer_group_id = ' . $this->getThemeModel()->getCustomerGroupId()
            );

            if ($special_field) {
                $data['product_info']['special_date_end'] = $special_field['date_end'];
            }
        }

        if (!$this->getThemeData()->common['checkout_enabled']) {
            $data['button_cart'] = false;

            return;
        }
    }

    public function filterProductOptions(sfEvent $event, $options)
    {
        if ($options) {
            $themeData = $this->getThemeData();

            foreach ($options as &$option) {
                $option['tb_css_classes'] = '';
                if (isset($themeData['product']['designs']['option'][$option['option_id']])) {
                    $option_settings = $themeData['product']['designs']['option'][$option['option_id']];
                    $option['tb_css_classes'] = $option_settings['style_id'];
                    if (!empty($option_settings['image_width']) && !empty($option_settings['image_height'])) {
                        $option['image_width'] = $option_settings['image_width'];
                        $option['image_height'] = $option_settings['image_height'];
                    }
                }
            }
        }

        return $options;
    }

    public function modifyProducts(array &$products, $settings)
    {
        /** @var Theme_Catalog_ProductsModel $productsModel */
        $productsModel = $this->getModel('products');
        $productsModel->mergeProducts($products, array(
            'secondary_image' => in_array($settings['thumbs_hover_action'], array('overlay', 'flip'))
        ));

        $this->addProductData($products, $settings);
    }

    public function addProductData(array &$products, $settings)
    {
        $config = $this->engine->getOcConfig();
        $themeData = $this->getThemeData();

        $resize_images = false;
        $img_width = $settings['image_width'];
        $img_height = $settings['image_height'];

        $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';

        if ($config->get($config_group . '_image_product_width') != $img_width || $config->get($config_group . '_image_product_height') != $img_height) {
            $resize_images = true;
        }

        $time = time();
        $label_new_days = $themeData->common['label_new_days'];
        $i = 1;

        foreach ($products as $key => &$product) {

            if (empty($product['product_id'])) {
                unset($products[$key]);
                continue;
            }

            if (!$settings['show_price']) {
                $product['price'] = false;
            }

            if (!$settings['show_rating']) {
                $product['rating'] = false;
            }

            if (!$settings['show_description']) {
                $product['description'] = false;
            }

            if (empty($settings['show_counter'])) {
                $product['special_date_end'] = false;
            }

            $product['tb_classes']  = 'tb_id_' . $product['product_id'];
            if (!isset($product['tax'])) {
                $product['tax'] = false;
            }

            if ($product['price'] && (!$config->get('config_customer_price') || $this->engine->getOcCustomer()->isLogged())) {
                $price_with_tax = $themeData->priceExtract($product['price']);
                if (false === $product['tax'] && !(float) $product['special_num'] && $config->get('config_tax') && $product['price_num'] != $price_with_tax) {
                    $product['tax'] = $this->engine->getOcCurrency()->format($product['price_num'], $themeData->currency_code);
                }

                $product['price_num'] = $price_with_tax;
            } else {
                $product['price_num'] = false;
            }

            if ($product['price'] && (float) $product['special_num']) {
                $special_with_tax = $themeData->priceExtract($product['special']);
                if (false === $product['tax'] && $config->get('config_tax') && $product['special_num'] != $special_with_tax) {
                    $product['tax'] = $this->engine->getOcCurrency()->format($product['special_num'], $themeData->currency_code);
                }
                $product['special_num'] = $special_with_tax;
            } else {
                $product['special_num'] = false;
            }

            if ($product['special_num'] && !isset($product['special_date_end'])) {
                $product['special_date_end'] = false;

                $special_field = $this->engine->getDbHelper()->getRecord('product_special',
                    'product_id = ' .  $product['product_id'] . ' AND date_end > NOW() AND customer_group_id = ' . $this->getThemeModel()->getCustomerGroupId()
                );

                if ($special_field) {
                    $product['special_date_end'] = $special_field['date_end'];
                }
            }

            if ($product['special_num'] && $product['price_num']) {
                $product['savings_num'] = round((1 - $product['special_num'] / $product['price_num'] ) * 100);
            } else {
                $product['savings_num'] = false;
            }

            $product['savings_text']    = sprintf($themeData->text_product_savings, (int) $product['savings_num']);
            $product['show_label_sale'] = $settings['show_label_sale'] && $product['savings_num'] !== false;
            $product['thumb_width']     = $img_width;
            $product['thumb_height']    = $img_height;

            if ($settings['show_thumb']) {
                if ((!isset($product['thumb']) || $resize_images) && $product['image']) {
                    if((strpos($product['image'], "brp.com.my")===false)) {
                            $product['thumb'] = $this->getThemeModel()->resizeImage($product['image'], $img_width, $img_height);
                    } else {
                            $product['thumb'] = $this->getThemeModel()->resizeImage($product['image'], $img_width, $img_height, "", true);
                    }
                } else
                if (!isset($product['thumb'])) {
                    $product['thumb'] = false;
                } else
                if ($product['image']) {
                    $filename = DIR_IMAGE . 'cache/' . dirname($product['image']) . '/' . basename($product['thumb']);

                    if (!is_file($filename)) {
                        $filename = $product['thumb'];

                        if (preg_match('/(.*)\.(?:jpe?g|png|gif)/', $filename, $matches)) {
                            $filename_tmp = $matches[0];

                            if (preg_match('/\/' . basename(DIR_IMAGE) . '\/cache\/(.*)/', $filename_tmp, $matches)) {
                                $filename_tmp = DIR_IMAGE . 'cache/' . urldecode($matches[1]);

                                if (is_file($filename_tmp)) {
                                    $filename = $filename_tmp;
                                }
                            }
                        }
                    }

                    if (filter_var($filename, FILTER_VALIDATE_URL) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
                        $context = stream_context_create(array(
                            'http' => array(
                                'header' => 'Authorization: Basic ' . base64_encode("{$_SERVER['PHP_AUTH_USER']}:{$_SERVER['PHP_AUTH_PW']}")
                            )
                        ));
                        $image = file_get_contents($filename, false, $context);
                    list($product['thumb_width'], $product['thumb_height']) = (strpos($product['image'], "brp.com.my")===false ? getimagesize($filename) : getimagesize(str_replace(' ', '%20', $filename)));
                    } else {
                        list($product['thumb_width'], $product['thumb_height']) = getimagesize($filename);
                    }

                    if (!$product['thumb_width']) {
                        $product['thumb_width'] = $img_width;
                    }

                    if (!$product['thumb_height']) {
                        $product['thumb_height'] = $img_height;
                    }
                }
            } else {
                $product['thumb'] = false;
            }

            if ($product['thumb'] && $themeData->system['image_lazyload']) {
                $product['thumb_original'] = $product['thumb'];
                $product['thumb'] = $themeData->theme_catalog_image_url . 'pixel.gif';
            }

            if ($settings['thumbs_hover_action'] == 'zoom') {
                $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';
                
                if((strpos($product['image'], "brp.com.my")===false)) {
                    $product['thumb_zoom'] = $this->getThemeModel()->resizeImage($product['image'], $config->get($config_group . '_image_thumb_width'), $config->get($config_group . '_image_thumb_height'));
                } else {
                    $product['thumb_zoom'] = $this->getThemeModel()->resizeImage($product['image'], $config->get($config_group . '_image_thumb_width'), $config->get($config_group . '_image_thumb_height'), "", true);
                }
            } else {
                $product['thumb_zoom'] = false;
            }

            if (($settings['thumbs_hover_action'] == 'overlay' || $settings['thumbs_hover_action'] == 'flip') && !empty($product['secondary_image'])) {
                if((strpos($product['secondary_image'], "brp.com.my")===false)) {
                    $product['thumb_hover'] = $this->getThemeModel()->resizeImage($product['secondary_image'], $img_width, $img_height);
                } else {
                    $product['thumb_hover'] = $this->getThemeModel()->resizeImage($product['secondary_image'], $img_width, $img_height, "", true);
                }
                
            } else {
                $product['thumb_hover'] = false;
            }

            if (!empty($product['description']) && $settings['description_limit'] > 0) {
                $product['description'] = tb_trim($product['description'], '.');
                $product['description'] = strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'));
                if ($product['description']) {
                    $product['description'] = utf8_substr($product['description'], 0, $settings['description_limit']) . '...';
                }
            }

            if ($product['special']) {
                $product['special'] = $themeData->priceFormat($product['special']);
            }

            if ($product['price']) {
                $product['price'] = $themeData->priceFormat($product['price']);
            }

            if ($product['tax']) {
                $product['tax'] = $themeData->priceFormat($product['tax']);
            }

            if (!$settings['show_tax']) {
                $product['tax'] = false;
            }

            $date1 = strtotime($product['date_available']);
            $date2 = strtotime($product['date_added']);

            $is_new = $label_new_days > ($time - ($date1 > $date2 ? $date1 : $date2)) / 86400;
            $product['is_new'] = $is_new ? 1 : 0;
            $product['rating'] = $config->get('config_review_status') ? round($product['rating'], 1) : false;

            $lang = $this->engine->loadOcTranslation();
            $text_button_cart = $lang['button_cart'];

            if (isset($product['quantity']) && $product['quantity'] < 1) {
                if ($themeData->store_common['preorder_stock_status_name'] == $product['stock_status']) {
                    $text_button_cart = $this->extension->translate('text_status_preorder');
                }

                if ($themeData->store_common['backorder_stock_status_name'] == $product['stock_status']) {
                    $text_button_cart = $this->extension->translate('text_status_backorder');
                }
            }

            $product['text_button_cart'] = $text_button_cart;
            $product['show_stock'] = $settings['show_stock'] && $product['quantity'] < 1 && !$config->get('config_stock_checkout');
            $product['show_cart'] = isset($product['quantity'])
                                    && ($product['quantity'] > 0 || $this->engine->getOcConfig()->get('config_stock_checkout'))
                                    && $themeData->common['checkout_enabled']
                                    && $settings['show_cart']
                                    && $product['price']
                                    && (
                                        !$themeData->store_common['disable_checkout_stock_status_name']
                                        || $themeData->store_common['disable_checkout_stock_status_name'] != $product['stock_status']
                                        || (
                                            $product['quantity'] > 0
                                            && $themeData->store_common['disable_checkout_stock_status_name'] == $product['stock_status']
                                        )
                                    );
            $i++;
        }
    }

    public function productListingLayout(TB_ViewSlotEvent $event)
    {
        $templates = $this->engine->getThemeConfig('product_listing_layouts');
        $data = $event['data'];

        if (!isset($data['view_mode'])) {
            $data['view_mode'] = $this->getThemeData()->product_listing_type;
        }

        if (!isset($templates[$data['view_mode']])) {
            return;
        }

        if (empty($data['listing_layout'])) {
            if (isset($event['product_settings_context']) && !empty($event['product_settings_context']['listing_layout'])) {
                $data['listing_layout'] = $event['product_settings_context']['listing_layout'];
            } else
            if (!empty($this->getThemeData()->products_system['listing_layout'])) {
                $data['listing_layout'] = $this->getThemeData()->products_system['listing_layout'];
            } else
            if (!empty($this->getThemeData()->products_related['listing_layout'])) {
                $data['listing_layout'] = $this->getThemeData()->products_related['listing_layout'];
            }
        }

        $template_name = false;
        foreach ($templates[$data['view_mode']] as $layout) {
            if ($layout['template'] == $data['listing_layout']) {
                $template_name = $layout['template'];

                break;
            }
        }

        if (!$template_name) {
            return;
        }

        $view_data = array(
            'product' => $event['product']
        );

        $elements = array(
            'thumb',
            'title',
            'description',
            'price',
            'special_price_end',
            'tax',
            'rating',
            'button_cart',
            'button_wishlist',
            'button_compare',
            'button_quickview',
            'stock_status',
            'label_sale',
            'label_new'
        );

        foreach ($this->getThemeData()->viewSlot->getKeys() as $key) {
            if (0 !== strpos($key, 'products_listing.product.')) {
                continue;
            }

            list(, $element) = explode('products_listing.product.', $key);

            if (!in_array($element, $elements)) {
                array_push($elements, $element);
            }
        }

        foreach ($elements as $element) {
            $view_data[$element] = $this->getThemeData()->viewSlot->getContent('products_listing.product.' . $element);
            $this->getThemeData()->viewSlot->removeContent('products_listing.product.' . $element);
        }

        $product_layout = $this->engine->getThemeExtension()->fetchTemplate($this->context->getThemeDir() . '/templates/' . $template_name . '.tpl', $view_data);

        $event->setContent($product_layout);
    }
    
    protected function getProduct($product_id) 
    {
      	static $products = array();
    	
      	if (!isset($products[$product_id])) {
            $product_info = $this->getOcModel('catalog/product')->getProduct($product_id);

  	        if ($product_info) {
  	            if ($product_info['image']) {
                    $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';
  	                $width = $this->engine->getOcConfig()->get($config_group . '_image_thumb_width');
  	                $height = $this->engine->getOcConfig()->get($config_group . '_image_thumb_height');
	                
	                if((strpos($product_info['image'], "brp.com.my")===false)) {
                            $product_info['thumb'] = $this->getThemeModel()->resizeImage($product_info['image'], $width, $height);
                        } else {
                            $product_info['thumb'] = $this->getThemeModel()->resizeImage($product_info['image'], $width, $height, "", true);
                        }
                        
  	            } else {
  	                $product_info['thumb'] = $this->getThemeData()->res_url . '/image/no_image.jpg';
  	            }

                if (($this->engine->getOcConfig()->get('config_customer_price') && $this->engine->getOcCustomer()->isLogged()) || !$this->engine->getOcConfig()->get('config_customer_price')) {
                    $price = $this->engine->getOcTax()->calculate($product_info['price'], $product_info['tax_class_id'], $this->engine->getOcConfig()->get('config_tax'));
                    $product_info['price'] = (float) $this->engine->getOcCurrency()->format($price, $this->getThemeData()->currency_code, '', false);
                }

                if ((float) $product_info['special']) {
                    $special = $this->engine->getOcTax()->calculate($product_info['special'], $product_info['tax_class_id'], $this->engine->getOcConfig()->get('config_tax'));
                    $product_info['special'] = (float) $this->engine->getOcCurrency()->format($special, $this->getThemeData()->currency_code, '', false);
                }
            }

	          $products[$product_id] = $product_info;	
    	  }

    	  return $products[$product_id];
    }    
}