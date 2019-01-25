<?php

class LiveSearch_Catalog_LiveSearchController extends TB_CatalogController
{
    public function search()
    {
        if (empty($this->request->get['query'])) {
            $this->setOutput(json_encode(array()));

            return;
        }

        $query = strip_tags($this->request->get['query']);

        /** @var LiveSearch_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');

        $settings = $defaultModel->getSettings();
        $products = $defaultModel->search($query, $settings['max_results']);

        $this->setOutput(json_encode($this->prepareProducts($products)));
    }

    public function seed()
    {
        /** @var LiveSearch_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');
        $products = $defaultModel->seed();

        $this->setOutput(json_encode($this->prepareProducts($products)));
    }

    protected function prepareProducts(array $products)
    {
        if (empty($products)) {
            return array();
        }

        /** @var LiveSearch_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');
        $settings = $defaultModel->getSettings();

        foreach ($products as &$product) {
            // Needed for compatibilityProducts as the module/latest.php seeks these keys
            $product['rating']      = '';
            $product['description'] = '';
        }

        /** @var Theme_Catalog_ProductsModel $productsModel */
        $productsModel = $this->engine->getThemeExtension()->getModel('products');
        $products_modified = $productsModel->compatibilityProducts($products, $this->themeData, array(
            'create_thumb' => $settings['show_image'],
            'image_width'  => $settings['image_width'],
            'image_height' => $settings['image_height']
        ));
        $config = $this->engine->getOcConfig();

        $result = array();
        $base_url = $this->context->getBaseHttpsIf();
        $base_url_length = strlen($base_url);
        $image_url = $this->context->getImageUrl() . 'cache/' . ($this->engine->gteOc2() ? 'catalog/' : 'data/');
        $image_url_length = strlen($image_url);

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

                if (!empty($product['image'])) {
                    $product['thumb'] = $this->getThemeModel()->resizeImage($product['image'], $settings['image_width'], $settings['image_height']);
                }

                $product['href'] = $this->engine->getOcUrl()->link('product/product', 'product_id=' . $product['product_id']);
            }

            $tokens = array();

            if ($product['manufacturer']) {
                $tokens[] = $product['manufacturer'];
            }

            if (0 === strpos($product['href'], $base_url)) {
                $product['href'] = substr($product['href'], $base_url_length);
            }

            if (isset($product['thumb']) && 0 === strpos($product['thumb'], $image_url)) {
                $product['thumb'] = '**' . substr($product['thumb'], $image_url_length);
            } else {
                $product['thumb'] = false;
            }

            $result[] = array(
                'id' => $product['product_id'],
                'nm' => $product['name'],
                'md' => $settings['show_model'] || $settings['search_in']['model'] ? $product['model']   : false,
                'pr' => $settings['show_price'] ? $product['price']   : false,
                'sp' => $settings['show_price'] ? $product['special'] : false,
                'ur' => $product['href'],
                'im' => $product['thumb'],
                'tk' => implode(' ', $tokens)
            );
        }

        return $result;
    }
}