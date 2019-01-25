<?php

class Theme_Catalog_BrandCategoryController extends TB_CatalogController
{
    public function index()
    {
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/category');

        $category_id = (int) $this->getArrayKey('c_id', $this->request->get, 0);
        $category_info = $this->model_catalog_category->getCategory($category_id);

        $manufacturer_id = (int) $this->getArrayKey('man_id', $this->request->get, 0);
        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

        if (!$category_info || !$manufacturer_info || $category_id == 0 || $manufacturer_id == 0) {
            return false;
        }

        $this->document->setTitle($category_info['name'] . ' / ' . $manufacturer_info['name']);
        $this->document->setDescription($category_info['meta_description']);
        $this->document->setKeywords($category_info['meta_keyword']);

        $this->data = array_merge(
            $this->data,
            $this->language->load('product/category'),
            $this->language->load('product/manufacturer'),
            $this->language->load('product/compare')
        );

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home'),
            'text'      => $this->language->get('text_home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href'      => $this->url->link('product/category', 'path=' . $category_id),
            'text'      => $category_info['name'],
            'separator' => $this->language->get('text_separator')
        );

        $this->data['category_name']     = $category_info['name'];
        $this->data['manufacturer_name'] = $manufacturer_info['name'];
        $this->data['text_compare']      = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

        $this->data['breadcrumbs'][] = array(
            'href'      => html_entity_decode($_SERVER['REQUEST_URI']),
            'text'      => $manufacturer_info['name'],
            'separator' => $this->language->get('text_separator')
        );

        $this->data['thumb'] = '';
        if ($manufacturer_info['image']) {
            $this->data['thumb'] = $this->getOcModel('tool/image')->resize($manufacturer_info['image'], 80, 80);
        }

        $options = array();

        if ($this->engine->gteOc22()) {
            $default_limit = $this->config->get('theme_default_product_limit');
        } else {
            $default_limit = $this->config->get($this->engine->gteOc2() ? 'config_product_limit' : 'config_catalog_limit');
        }

        if (!$default_limit) {
            $default_limit = 15;
        }

        $options['limit'] = $this->getArrayKey('limit', $this->request->get, $default_limit);
        $options['page']  = $this->getArrayKey('page', $this->request->get, 1);
        $options['sort']  = $this->getArrayKey('sort', $this->request->get, 'p.sort_order');
        $options['order'] = $this->getArrayKey('order', $this->request->get, 'ASC');
        $options['start'] = ($options['page'] - 1) * $options['limit'];
        $options['manufacturer_id'] = $manufacturer_id;
        $options['category_id'] = $category_id;
        $options['secondary_image'] = in_array($this->engine->getThemeData()->category_products_current['thumbs_hover_action'], array('overlay', 'flip'));

        /** @var Theme_Catalog_ProductsModel $productsModel */
        $productsModel = $this->getModel('products');

        $product_total = $productsModel->getTotalProductsByManufacturerAndCategory($manufacturer_id, $category_id);
        $products = $productsModel->getProductsByManufacturerAndCategory($options);
        $products_modified = $productsModel->compatibilityProducts($products, $this->themeData);
        $config = $this->engine->getOcConfig();

        foreach ($products as &$product) {
            if (!$products_modified) {
                if (($config->get('config_customer_price') && $this->engine->getOcCustomer()->isLogged()) || !$config->get('config_customer_price')) {
                    $tax = $this->engine->getOcTax()->calculate($product['price'], $product['tax_class_id'], $config->get('config_tax'));
                    $product['price'] = $this->engine->getOcCurrency()->format($tax, $this->themeData->currency_code);
                } else {
                    $product['price'] = false;
                }

                if ($product['special_num']) {
                    $tax = $this->engine->getOcTax()->calculate($product['special'], $product['tax_class_id'], $config->get('config_tax'));
                    $product['special'] = $this->engine->getOcCurrency()->format($tax, $this->themeData->currency_code);
                } else {
                    $product['special'] = false;
                }

                $product['href'] = $this->engine->getOcUrl()->link('product/product', 'product_id=' . $product['product_id']);
            }

            $product['secondary_image'] = $options['secondary_image'] ? $product['secondary_image'] : false;
            $product['reviews'] = sprintf($this->data['text_reviews'], (int) $product['reviews']);
        }

        $this->engine->getThemeExtension()->getPlugin('product')->addProductData($products, $this->themeData->category_products_current);
        $this->data['products'] = $products;
        $this->data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($options['page'] - 1) * $options['limit']) + 1 : 0, ((($options['page'] - 1) * $options['limit']) > ($product_total - $options['limit'])) ? $product_total : ((($options['page'] - 1) * $options['limit']) + $options['limit']), $product_total, ceil($product_total / $options['limit']));

        $current_url = $this->themeData->link($this->getRouteByName('category_manufacturer'), 'c_id=' . $category_id . '&man_id=' . $manufacturer_id);

        $sorts_url = $current_url;
        if (isset($this->request->get['limit'])) {
            $sorts_url .= '&limit=' . (int) $this->request->get['limit'];
        }
        $this->data['sorts'] = $this->getModel('products')->getSorts($sorts_url);

        $limits_url = $current_url;
        if (isset($this->request->get['sort'])) {
            $limits_url .= '&sort=' . (string) $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $limits_url .= '&order=' . (string) $this->request->get['order'];
        }
        $this->data['limits'] = $this->getModel('products')->getLimits($limits_url);


        $pagination_url = $limits_url;
        if (isset($this->request->get['limit'])) {
            $pagination_url .= '&limit=' . (int) $this->request->get['limit'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page  = $options['page'];
        $pagination->limit = $options['limit'];
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $pagination_url . '&page={page}';

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $options['sort'];
        $this->data['order'] = $options['order'];
        $this->data['limit'] = $options['limit'];
        $this->data['url_list_listing_type'] = $pagination_url . '&setProductListingType=list&page=' . $options['page'];
        $this->data['url_grid_listing_type'] = $pagination_url . '&setProductListingType=grid&page=' . $options['page'];
        $this->data['url_product_compare'] = $this->url->link('product/compare');

        $heading_title = $this->translate('text_manufacturer_products');
        $heading_title = str_replace('{{manufacturer}}', $manufacturer_info['name'], $heading_title);
        $heading_title = str_replace('{{category}}', $category_info['name'], $heading_title);
        $this->data['heading_title'] = $heading_title;

        $this->data['products_route'] = 'category/manufacturer';
        $this->data['products_filter_name'] = 'category/manufacturer';

        $this->engine->getThemeExtension()->fetchTemplate('tb/brand_category', array('thumb' => $this->data['thumb']), true);

        $this->renderTemplate('product/special');
    }
}