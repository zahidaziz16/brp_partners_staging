<?php

require_once dirname(__FILE__) . '/lib/controller.php';

class ControllerStoriesTag extends ControllerStoriesCatalog
{
    public function index()
    {
        $this->language->load('module/stories');

        $settings = $this->config->get('stories_settings');
        $lang_id  = $this->config->get('config_language_id');

        $tag_id = 0;
        if (isset($this->request->get['story_tag_id']) && !empty($this->request->get['story_tag_id'])) {
            $tag_id = (int) $this->request->get['story_tag_id'];
        }

        $tag = $this->getModel()->getTag($tag_id);

        if (!$tag) {
            $this->notFound($this->url->link('stories/tag', 'tag_id=' . $tag_id));

            return;
        }

        $page_title        = !empty($settings['lang'][$lang_id]['page_title']) ? $settings['lang'][$lang_id]['page_title'] : $this->language->get('text_articles');
        $meta_title        = $tag['meta_title'] ? $tag['meta_title'] : $tag['name'];
        $meta_description  = $tag['meta_description'] ? $tag['meta_description'] : substr(html_entity_decode($tag['description'], ENT_COMPAT, 'UTF-8'), 0, 80);

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $page_title,
            'href'      => $this->url->link('stories/index'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $tag['name'],
            'href'      => $this->url->link('stories/tag', 'story_tag_id=' . $tag_id),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->setTitle($meta_title);
        $this->document->setDescription($meta_description);
        $this->document->addLink($this->url->link('stories/tag', 'story_tag_id=' . $tag_id), 'canonical');

        $this->data['heading_title'] = $tag['name'];
        $this->data['description']   = html_entity_decode($tag['description'], ENT_COMPAT, 'UTF-8');

        $settings = $this->config->get('stories_settings');

        $url = '';

        $page = 1;
        if (isset($this->request->get['page']) && !empty($this->request->get['page']) && is_numeric($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        }

        $limit = $settings['stories_per_page'];

        $options = array(
            'page'                => $page,
            'limit'               => $limit,
            'start'               => $limit * ($page - 1),
            'filter_tag_ids'      => array($tag_id),
            'filter_sticky_first' => true
        );

        $stories = $this->getModel()->getStories($options);
        $total = $this->getModel()->getFoundRows();

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page  = $page;
        $pagination->limit = $limit;
        $pagination->text  = $this->language->get('text_pagination');
        $pagination->url   = $this->url->link('stories/tag', $url . '&story_tag_id=' . $tag_id . '&page={page}');

        $this->data['stories'] = array();

        foreach ($stories as $story) {

            if (!empty($story['teaser'])) {
                $description = strip_tags(html_entity_decode($story['teaser'], ENT_COMPAT, 'UTF-8'));
            } else {
                $description = strip_tags(html_entity_decode($story['description'], ENT_COMPAT, 'UTF-8'));
                $description = strlen($description) > $settings['text_limit'] ? substr($description, 0, $settings['text_limit']) . '...' : $description;
            }

            $thumb = '';
            if ($story['image']) {
                $thumb = $this->getModel()->resizeImageAdvanced($story['image'], $settings['image_list_width'], $settings['image_list_height'], 'crop');
            }

            $tags = array();
            if (trim($story['tags'])) {
                foreach (explode('!*!', $story['tags']) as $tag) {
                    list($name, $tag_id) = explode('<=>', $tag);
                    $tags[] = array(
                        'name' => $name,
                        'url'  => $this->url->link('stories/tag', 'story_tag_id=' . $tag_id)
                    );
                }
            }

            $this->data['stories'][] = array (
                'title'        => $story['title'],
                'description'  => $description,
                'tags'         => $tags,
                'thumb'        => $thumb,
                'thumb_width'  => $settings['image_list_width'],
                'thumb_height' => $settings['image_list_height'],
                'url'          => $this->url->link('stories/show', 'story_id=' . $story['story_id']),
                'date_added'   => date($this->language->get('date_format_short'), strtotime($story['date_added']))
            );
        }

        if ($this->gteOc2()) {
            $this->data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));
        }

        $this->data['pagination']       = $pagination->render();
        $this->data['settings']         = $settings;
        $this->data['text_read_more']   = $this->language->get('text_read_more');
        $this->data['text_no_articles'] = $this->language->get('text_no_articles');

        $this->renderOutput('stories/tag.tpl');
    }
}