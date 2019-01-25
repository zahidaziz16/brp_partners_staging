<?php

require_once dirname(__FILE__) . '/lib/controller.php';

class ControllerStoriesTag extends ControllerStoriesAdmin
{
    public function index()
    {
        $this->mergeLanguage('stories/index');

        $this->document->setTitle($this->language->get('text_tags'));

        $this->setBreadcrumbs();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_tags'),
            'href'      => $this->link('stories/tag'),
            'separator' => ' :: '
        );

        $this->data['success'] = '';
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $items_per_page = $this->gteOc2() ? $this->config->get('config_limit_admin') : $this->config->get('config_admin_limit');

        $options = array();
        $options['limit'] = (int) $this->getArrayKey('limit', $this->request->get, $items_per_page);
        $options['page']  = (int) $this->getArrayKey('page', $this->request->get, 1);
        $options['sort']  = $this->getArrayKey('sort', $this->request->get, 'st.name');
        $options['order'] = $this->getArrayKey('order', $this->request->get, 'ASC');
        $options['start'] = ($options['page'] - 1) * $options['limit'];

        $tags = $this->getModel('tag')->getAll($options);
        foreach ($tags as &$tag) {
            $tag['date_added'] = date($this->language->get('date_format_short'), strtotime($tag['date_added']));
            $tag['edit_link'] = $this->link('stories/tag/edit', 'tag_id=' . $tag['tag_id']);
        }

        $this->data['tags'] = $tags;
        $this->data['tags_total'] = $this->getModel()->getFoundRows();

        $current_url = $this->link('stories/tag');
        $sorts_url = $current_url . '&page=' . $options['page'] . '&order=' . ($options['order'] == 'ASC' ? 'DESC' : 'ASC') . '&sort=';

        $this->data['url_sort_name']       = $sorts_url . 'st.name';
        $this->data['url_sort_status']     = $sorts_url . 'st.status';
        $this->data['url_sort_language']   = $sorts_url . 'st.language_id';
        $this->data['url_sort_date_added'] = $sorts_url . 'st.date_added';

        $pagination = new Pagination();
        $pagination->total = $this->data['tags_total'];
        $pagination->page  = $options['page'];
        $pagination->limit = $items_per_page;
        $pagination->text  = $this->data['text_pagination'];
        $pagination->url   = $current_url . '&page={page}';

        $this->data['pagination']    = $pagination->render();
        $this->data['sort']          = $options['sort'];
        $this->data['order']         = strtolower($options['order']);
        $this->data['action_insert'] = $this->link('stories/tag/edit');
        $this->data['action_delete'] = $this->link('stories/tag/delete');

        $this->renderOutput('stories/tag/index.tpl');
    }

    public function edit()
    {
        $this->mergeLanguage('stories/index');

        $action = isset($this->request->get['tag_id']) && !empty($this->request->get['tag_id']) ? 'update' : 'insert';

        if ($this->isPost() && isset($this->request->post['tag']) && $this->validate()) {
            if ($action == 'update') {
                $this->getModel('tag')->update((int) $this->request->get['tag_id'], $this->request->post['tag']);
            } else {
                $this->getModel('tag')->insert($this->request->post['tag']);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->link('stories/tag'));
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $this->setBreadcrumbs();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_tags'),
            'href'      => $this->link('stories/tag'),
            'separator' => ' :: '
        );

        if (isset($this->request->post['tag'])) {
            $tag = $this->request->post['tag'];
        } else
        if ($action == 'update') {
            $tag = $this->getModel('tag')->getOne((int) $this->request->get['tag_id']);
        } else {
            $tag = array(
                'language_id'      => $this->config->get('config_language_id'),
                'name'             => '',
                'description'      => '',
                'meta_title'       => '',
                'meta_description' => '',
                'status'           => 1,
                'keyword'          => ''
            );
        }

        if (empty($tag)) {
            $this->redirect($this->link('stories/tag'));
        }

        $this->data['tag']         = $tag;
        $this->data['error']       = $this->getArrayKey('warning', $this->error);
        $this->data['action']      = $action;
        $this->data['form_action'] = $this->link('stories/tag/edit', $action == 'update' ? 'tag_id=' . (int) $this->request->get['tag_id'] : '');
        $this->data['cancel']      = $this->link('stories/tag');
        $this->data['token']       = $this->getToken();
        $this->data['languages']   = $this->getModel('localisation/language')->getLanguages();

        $this->renderOutput('stories/tag/form.tpl');
    }

    public function delete()
    {
        if (isset($this->request->post['selected']) && $this->validate()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->getModel('tag')->deleteOne($id);
            }

            $this->language->load('stories/index');
            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->redirect($this->link('stories/tag'));
    }

    protected function validate()
    {
        return parent::validatePermission('stories/tag');
    }
}
