<?php

class Theme_Admin_ProductsModalController extends TB_AdminController
{
    public function index()
    {
        $products_ids = array();
        if (isset($this->request->get['products_ids'])) {
            $products_ids = (array) $this->request->get['products_ids'];
        }

        $this->setOutput($this->getProductsList($products_ids));
    }

    public function getProductsOnly()
    {
        $products_ids = array();
        if (isset($this->request->get['products_ids'])) {
            $products_ids = (array) $this->request->get['products_ids'];
        }

        $this->setOutput($this->getProductsList($products_ids, false));
    }

    public function getProductsList(array $product_ids = array(), $with_filter = true)
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $language = $this->load->language('catalog/product');
        $this->data = array_merge($this->data, $language);

        $pagination_limit = $this->getArrayKey('pagination_limit', $this->request->get, 8);
        $page = $this->getArrayKey('page', $this->request->get, 1);

        $default_data = array(
            'filter_category_id'   => null,
            'filter_name'          => null,
            'filter_model'         => null,
            'filter_price_less'    => null,
            'filter_price_more'    => null,
            'filter_quantity_more' => null,
            'filter_disabled'      => null,
            'filter_selected'      => null,
            'filter_specials'      => null,
            'sort'                 => 'p.date_added',
            'order'                => 'DESC',
            'page'                 => $page,
            'start'                => ($page - 1) * $pagination_limit,
            'limit'                => $pagination_limit
        );

        $data = TB_FormHelper::initFlatVarsSimple($default_data, $this->request->get);

        $product_total = $this->getThemeModel()->getTotalProducts($data, $product_ids);
        $results = $this->getThemeModel()->getProducts($data, $product_ids);

        $this->data['products'] = array();
        foreach ($results as $result) {
            // BRP Customized Part
            if((strpos($result['image'], "brp.com.my")===false)) {
                    if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                        $image = $this->model_tool_image->resize($result['image'], 40, 40);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
                    }
            } else {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resizeBRP($result['image'], 40, 40);
                        //$image = $result['image'];
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
                    }
            }

            $special = false;
            $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
            if ($product_specials) {
                $special = reset($product_specials);
                if(($special['date_start'] != '0000-00-00' && $special['date_start'] > date('Y-m-d')) || ($special['date_end'] != '0000-00-00' && $special['date_end'] < date('Y-m-d'))) {
                    $special = false;
                } else {
                    $special = number_format($special['price'], 2);
                }
            }

            $this->data['products'][] = array(
                'product_id' => $result['product_id'],
                'name'       => $result['name'],
                'model'      => $result['model'],
                'price'      => number_format($result['price'], 2),
                'special'    => $special,
                'image'      => $image,
                'added'      => $result['added'],
                'quantity'   => $result['quantity'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected'])
            );
        }

        $filter_url = $this->buildUrl($data, array(
            'filter_category_id',
            'filter_name',
            'filter_model',
            'filter_price_less',
            'filter_price_more',
            'filter_quantity_more',
            'filter_disabled',
            'filter_selected',
            'filter_specials'
        ));

        $order = $data['order'] == 'ASC' ? 'DESC' : 'ASC';
        $sort_url = (empty($filter_url) ? '' : $filter_url . '&') . 'page=' . $page . '&order=' . $order . '&sort=';

        $this->data['url_sort_name'] = $this->tbUrl->generate('productsModal/index', $sort_url . 'pd.name');
        $this->data['url_sort_quantity'] = $this->tbUrl->generate('productsModal/index', $sort_url . 'p.quantity');
        $this->data['url_sort_price'] = $this->tbUrl->generate('productsModal/index', $sort_url . 'p.price');

        $request_url = (empty($filter_url) ? '' : $filter_url . '&') . $this->buildUrl($data, array('sort', 'order'));

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page  = $page;
        $pagination->limit = $pagination_limit;
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $this->tbUrl->generate('productsModal/index', 'page={page}&' . $request_url);

        $this->data = array_merge($this->data, $data);
        $this->data['pagination'] = $pagination->render();
        $this->data['filter_request_url'] = $this->tbUrl->generate('productsModal/index', $request_url);

        $template = $with_filter ? 'products_modal' : 'products_modal_listing';

        return $this->fetchTemplate($template, $this->data);
    }
}