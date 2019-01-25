<?php

require_once 'SystemWidget.php';

class Theme_StoriesListPageContentSystemWidget extends Theme_SystemWidget
{

    public function onFilter(array &$settings)
    {
        $default_restrictions = array(
            0 => array(
                'max_width'     => 1900,
                'items_per_row' => 4,
                'items_spacing' => 30
            ),
            1 => array(
                'max_width'     => 1500,
                'items_per_row' => 3,
                'items_spacing' => 30
            ),
            2 => array(
                'max_width'     => 1100,
                'items_per_row' => 2,
                'items_spacing' => 30
            ),
            3 => array(
                'max_width'     => 700,
                'items_per_row' => 1,
                'items_spacing' => 30
            ),
        );

        $settings = array_replace($settings, $this->initLangVars(array(
            //'is_active'        => 1,
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'view_mode'          => 'list',
            'restrictions'       => $default_restrictions,
        ), $settings));

        foreach ($settings['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($settings['restrictions'][$i]);
            }
        }

        if (empty($settings['restrictions'])) {
            $settings['restrictions'] = $default_restrictions;
        }

        parent::onFilter($settings);
    }

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));

        $listing_classes    = '';
        $listing_classes   .= $this->settings['view_mode'] == 'grid' ? ' tb_grid_view' : ' tb_list_view tb_style_bordered';
        $restrictions_json  = array();

        if ($this->settings['view_mode'] == 'grid') {
            foreach ($this->settings['restrictions'] as $restriction) {
                $restrictions_json[$restriction['max_width']] = array(
                    'items_per_row' => $restriction['items_per_row'],
                    'items_spacing' => $restriction['items_spacing']
                );
            }
            krsort($restrictions_json);
        }

        $this->themeData['system.' . $this->getSlotName()] = array(
            'listing_classes'   => $listing_classes,
            'restrictions_json' => json_encode($restrictions_json)
        );
    }

    public function assignAssets()
    {
        $main_settings = $this->engine->getOcConfig()->get('stories_settings');

        if ($main_settings['comments'] == 'disqus') {
            $this->engine->getThemeExtension()->addJsContents('<script type="text/javascript">
            var disqus_shortname = \'' . $main_settings['disqus_shortname'] . '\';
            (function () {
            var s = document.createElement(\'script\'); s.async = true;
            s.type = \'text/javascript\';
            s.src = \'//\' + disqus_shortname + \'.disqus.com/count.js\';
            (document.getElementsByTagName(\'HEAD\')[0] || document.getElementsByTagName(\'BODY\')[0]).appendChild(s);
            }());
            </script>', 'disqus_show_comments');
        }
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'article_title' => array(
                'section_name'      => 'Article Title',
                'elements'          => '.tb_article h2',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 26,
                'line-height'       => 30,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
        );
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'body' => array(
                '_label' => 'Articles',
                'links' => array(
                    'label'       => 'Article title',
                    'elements'    => 'h2 a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'links_hover' => array(
                    'label'       => 'Article title (hover)',
                    'elements'    => 'h2 a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'text' => array(
                    'label'       => 'Summary',
                    'elements'    => '.tb_description',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'meta_text' => array(
                    'label'       => 'Meta text',
                    'elements'    => '.tb_meta',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'meta_links' => array(
                    'label'       => 'Meta links',
                    'elements'    => '.tb_meta a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'meta_links_hover' => array(
                    'label'       => 'Meta links (hover)',
                    'elements'    => '.tb_meta a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                ),
                'meta_icons' => array(
                    'label'       => 'Meta icons',
                    'elements'    => '.tb_meta .fa',
                    'property'    => 'color',
                    'color'       => '#cccccc',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'read_more' => array(
                    'label'       => 'Read more',
                    'elements'    => '.tb_main_color',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'read_more_hover' => array(
                    'label'       => 'Read more',
                    'elements'    => '.tb_main_color:hover',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                )
            ),
            'pagination' => array(
                '_label' => 'Pagination',
                'links_text' => array(
                    'label'       => 'Links text',
                    'elements'    => '
                        .pagination a:not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text'
                ),
                'links_bg' => array(
                    'label'       => 'Links bg',
                    'elements'    => '
                        .pagination a:not(:hover)
                    ',
                    'property'    => 'background-color',
                    'color'       => '#eeeeee',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg'
                ),
                'links_text_hover' => array(
                    'label'       => 'Links text (hover)',
                    'elements'    => '
                        .pagination a:hover
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text_hover'
                ),
                'links_bg_hover' => array(
                    'label'       => 'Links bg (hover)',
                    'elements'    => '
                        .pagination a:hover
                    ',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg_hover'
                ),
                'links_text_active' => array(
                    'label'       => 'Links text (active)',
                    'elements'    => '
                        .pagination b,
                        .pagination span
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_text_active'
                ),
                'links_bg_active' => array(
                    'label'       => 'Links bg (active)',
                    'elements'    => '
                        .pagination b,
                        .pagination span
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.links_bg_active'
                ),
                'results' => array(
                    'label'       => 'Results text',
                    'elements'    => '.pagination .results',
                    'property'    => 'color',
                    'color'       => '#999999',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:pagination.results'
                ),
                'border' => array(
                    'label'       => 'Border',
                    'elements'    => '
                        .pagination
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.column_border'
                )
            )
        );

        return $default_colors;
    }

    public function hasTitleStyles()
    {
        return false;
    }
}