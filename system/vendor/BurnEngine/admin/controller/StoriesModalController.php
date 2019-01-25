<?php

class Theme_Admin_StoriesModalController extends TB_AdminController
{
    public function index()
    {
        $story_ids = array();
        if (isset($this->request->get['story_ids'])) {
            $story_ids = (array) $this->request->get['story_ids'];
        }

        $this->setOutput($this->getStoryList($story_ids));
    }

    public function getStoriesOnly()
    {
        $story_ids = array();
        if (isset($this->request->get['story_ids'])) {
            $story_ids = (array) $this->request->get['story_ids'];
        }

        $this->setOutput($this->getStoryList($story_ids, false));
    }

    public function getStoryList(array $story_ids = array(), $with_filter = true)
    {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $language = $this->load->language('catalog/product');
        $this->data = array_merge($this->data, $language);

        $pagination_limit = 8;
        $page = $this->getArrayKey('page', $this->request->get, 1);

        $default_data = array(
            'filter_text'     => '',
            'filter_tags'     => null,
            'filter_disabled' => null,
            'filter_selected' => null,
            'sort'            => 's.date_added',
            'order'           => 'DESC',
            'page'            => $page,
            'start'           => ($page - 1) * $pagination_limit,
            'limit'           => $pagination_limit,
            'added_story_ids' => $story_ids
        );

        $data = TB_FormHelper::initFlatVarsSimple($default_data, $this->request->get);

        if (empty($data['filter_disabled'])) {
            $data['filter_status'] = 1;
        }

        $results = $this->getOcModel('stories/index')->getStories($data);
        $product_total = $this->getOcModel('stories/index')->getFoundRows();

        $this->data['stories'] = array();
        foreach ($results as $result) {

            if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 60, 60);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 60, 60);
            }

            $this->data['stories'][] = array(
                'story_id'   => $result['story_id'],
                'title'      => $result['title'],
                'tags'       => $result['tags'],
                'image'      => $image,
                'added'      => isset($result['added']) ? $result['added'] : array(),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'selected'   => isset($this->request->post['selected']) && in_array($result['story_id'], $this->request->post['selected'])
            );
        }

        $filter_url = $this->buildUrl($data, array(
            'filter_tags',
            'filter_disabled',
            'filter_selected',
        ));

        $order = $data['order'] == 'ASC' ? 'DESC' : 'ASC';
        $sort_url = (empty($filter_url) ? '' : $filter_url . '&') . 'page=' . $page . '&order=' . $order . '&sort=';

        $this->data['url_sort_name'] = $this->tbUrl->generate('storiesModal/index', $sort_url . 'sd.title');
        $this->data['url_sort_quantity'] = $this->tbUrl->generate('storiesModal/index', $sort_url . 's.date_added');

        $request_url = (empty($filter_url) ? '' : $filter_url . '&') . $this->buildUrl($data, array('sort', 'order'));

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page  = $page;
        $pagination->limit = $pagination_limit;
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $this->tbUrl->generate('storiesModal/index', 'page={page}&' . $request_url);

        $this->data = array_merge($this->data, $data);
        $this->data['pagination'] = $pagination->render();
        $this->data['filter_request_url'] = $this->tbUrl->generate('storiesModal/index', $request_url);

        return $this->fetchTemplate($with_filter ? 'stories_modal' : 'stories_modal_listing', $this->data);
    }
}