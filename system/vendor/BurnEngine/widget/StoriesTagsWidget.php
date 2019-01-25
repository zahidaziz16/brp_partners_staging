<?php

class Theme_StoriesTagsWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Categories',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left',
            'menu'        => array(),
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'level_1_style' => 'list',
            'level_2_style' => 'list',
        ), $settings));
    }

    public function onEdit(array &$settings)
    {
        $settings['tags'] = array();
        foreach ($this->engine->getEnabledLanguages() as $language) {
            $settings['tags'][$language['code']] = $this->getOcModel('stories/tag')->getAll(array(
                'status'      => 1,
                'language_id' => $language['language_id']
            ));
        }
    }

    public function hasArea($name)
    {
        if (!$this->engine->getOcConfig('stories_settings')) {
            return false;
        }

        return parent::hasArea($name);
    }

    public function isActive()
    {
        if (!$this->engine->getOcConfig('stories_settings')) {
            return false;
        }

        return parent::isActive();
    }

    public function onTransformSettings(array &$settings)
    {
        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            if (isset($settings['lang'][$language_code]['menu']) && !empty($settings['lang'][$language_code]['menu'])) {
                $str = (string) html_entity_decode($settings['lang'][$language_code]['menu'], ENT_COMPAT, 'UTF-8');
                $settings['lang'][$language_code]['menu'] = json_decode($str, true);
            }
        }
    }

    public function render(array $view_data = array())
    {
        if (empty($this->settings['lang'][$this->language_code]['menu'])) {
            return '';
        }

        $tags_ids = array();
        foreach ($this->settings['lang'][$this->language_code]['menu'] as $key => $item) {
            $tags_ids[] = (int) $item['data']['id'];
            if (isset($item['children'])) {
                foreach ($item['children'] as $child) {
                    $tags_ids[] = (int) $child['data']['id'];
                }
            }
        }

        $tags = array();
        foreach ($this->getOcModel('stories/index')->getTag($tags_ids) as $tag_row) {
            $tags[$tag_row['tag_id']] = $tag_row;
        }

        $tag_tree = array();

        foreach ($this->settings['lang'][$this->language_code]['menu'] as $key => $item) {
            $tag_id = $item['data']['id'];

            if (!isset($tags[$tag_id])) {
                continue;
            }

            $tag = $tags[$tag_id];
            $tag_tree[$tag_id] = $this->setTagItem($tag, $item);

            if (isset($item['children']) && !empty($item['children'])) {
                $tag_tree[$tag_id]['children'] = array();
                foreach ($item['children'] as $child_item) {
                    $child_tag = $tags[$child_item['data']['id']];
                    $tag_tree[$tag_id]['children'][$child_item['data']['id']] = $this->setTagItem($child_tag, $child_item);
                }
            }
        }

        $view_data['tag_tree'] = $tag_tree;

        return parent::render($view_data);
    }

    protected function setTagItem($tag, $menu_item)
    {
        $result = array();

        if (empty($menu_item['data']['settings']['label'])) {
            $label = $tag['name'];
        } else {
            $label = $menu_item['data']['settings']['label'];
        }

        $result['label']     = $label;
        $result['url']       = $this->engine->getOcUrl()->link('stories/tag', 'story_tag_id=' . $tag['tag_id']);
        $result['url_title'] = $label;
        $result['css_class'] = isset($menu_item['data']['settings']['class']) && $menu_item['data']['settings']['class'] ? $menu_item['data']['settings']['class'] : '';

        if ($this->themeData->route == 'stories/tag' && isset($this->engine->getOcRequest()->get['story_tag_id']) && $this->engine->getOcRequest()->get['story_tag_id'] == $tag['tag_id']) {
            $result['css_class'] = trim($result['css_class'] . ' tb_active');
        }

        return $result;
    }

    protected function getBoxClasses()
    {
        $classes = parent::getBoxClasses();
        $classes .= ' tb_wt_categories';

        return $classes;
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'categories_level_1' => array(
                '_label' => 'Level 1',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '> ul > li > * > a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '> ul > li > * > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '> ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'categories_level_2' => array(
                '_label' => 'Level 2',
                'text' => array(
                    'label'       => 'Links',
                    'elements'    => '> ul > li > ul > li > * > a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'text_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '> ul > li > ul > li > * > a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'bullets' => array(
                    'label'       => 'Bullets',
                    'elements'    => '> ul > li > ul.tb_list_1 > li:before',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            )
        );

        return $default_colors;
    }

    public function getPresentationTitle()
    {
        $title = $this->getName();
        $lang_title = $this->getLangTitle();
        if (!empty($lang_title)) {
            $title = $lang_title . ' <span>(' . $title . ')</span>';
        }

        return $title;
    }

}