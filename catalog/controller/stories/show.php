<?php

require_once dirname(__FILE__) . '/lib/controller.php';

class ControllerStoriesShow extends ControllerStoriesCatalog
{
    public function index()
    {
        $this->language->load('module/stories');
        $this->load->language('common/header');

        $settings = $this->config->get('stories_settings');
        $lang_id  = $this->config->get('config_language_id');

        $page_title = !empty($settings['lang'][$lang_id]['page_title']) ? $settings['lang'][$lang_id]['page_title'] : $this->language->get('text_articles');

        $story_id = 0;
        if (isset($this->request->get['story_id']) && !empty($this->request->get['story_id'])) {
            $story_id = (int) $this->request->get['story_id'];
        }

        $story = $this->getModel()->getOne($story_id);

        if (!$story) {
            $this->notFound($this->url->link('stories/show', 'story_id=' . $story_id));

            return;
        }

        $story['url'] = $this->url->link('stories/show', 'story_id=' . $story_id);

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
            'text'      => $story['title'],
            'href'      => $story['url'],
            'separator' => $this->language->get('text_separator')
        );

        $meta_title       = $story['meta_title'] ? $story['meta_title'] : $story['title'];
        $meta_description = $story['meta_description'] ? $story['meta_description'] : '';

        $this->document->setTitle($meta_title);
        $this->document->setDescription($meta_description);
        $this->document->addLink($this->url->link('stories/show', 'story_id=' . $story_id), 'canonical');

        $settings = $this->config->get('stories_settings');

        $image = '';

        if ($story['image']) {
            $this->load->model('tool/image');
            $image = $this->getModel()->resizeImageAdvanced($story['image'], $settings['image_description_width'], $settings['image_description_height'], 'crop');
        }

        $tags = array();
        if (trim($story['tags'])) {
            foreach (explode('!*!', $story['tags']) as $tag) {
                list($name, $tag_id) = explode('<=>', $tag);
                $tags[] = array(
                    'name' => $name,
                    'url'   => $this->url->link('stories/tag', 'story_tag_id=' . $tag_id)
                );
            }
        }

        if (!isset($_SESSION['story_viewed'])) {
            $this->getModel()->incrementViews($story_id);
            $_SESSION['story_viewed'] = true;
        }

        $this->data['image']         = $image;
        $this->data['image_width']   = $settings['image_description_width'];
        $this->data['image_height']  = $settings['image_description_height'];
        $this->data['date_added']    = date($this->language->get('date_format_short'), strtotime($story['date_added']));
        $this->data['tags']          = $tags;
        $this->data['heading_title'] = $story['title'];
        $this->data['story_url']     = $story['url'];
        $this->data['description']   = html_entity_decode($story['description'], ENT_COMPAT, 'UTF-8');
        $this->data['settings']      = $settings;
        $this->data['settings']['comments']           = $settings['comments'];
        $this->data['settings']['disqus_shortname']   = $settings['disqus_shortname'];
        $this->data['settings']['social_share']       = html_entity_decode($settings['social_share'], ENT_COMPAT, 'UTF-8');

        $this->renderOutput('stories/show.tpl');
    }
}
