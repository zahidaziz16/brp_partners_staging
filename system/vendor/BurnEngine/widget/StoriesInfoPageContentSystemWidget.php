<?php

require_once 'SystemWidget.php';

class Theme_StoriesInfoPageContentSystemWidget extends Theme_SystemWidget
{

    public function initFrontend()
    {
        $this->engine->getEventDispatcher()->connect('core:assignAssets', array($this, 'assignAssets'));
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

        if ($this->themeData->route == 'stories/show') {
            $story = $this->engine->getOcModel('stories/index')->getOne($this->engine->getOcRequest()->get['story_id']);

            if ($story) {
                $thumb = '';
                if ($story['image']) {
                    $thumb = $this->engine->getContext()->getImageUrl() . $this->getThemeModel()->resizeImageAdvanced($story['image'], 280, 150, 'crop');
                }
                $description = $story['teaser'] ? $story['teaser'] : substr(htmlspecialchars(strip_tags(html_entity_decode($story['description'], ENT_COMPAT, 'UTF-8'))), 0, 300);;

                // Facebook Open Graph
                $fb_meta  = '<meta property="og:type" content="article" />' . "\n";
                $fb_meta .= '<meta property="og:url" content="' . $this->engine->getOcUrl()->link('stories/show', 'story_id=' . $story['story_id']) . '" />' . "\n";
                $fb_meta .= '<meta property="og:title" content="' . $story['title'] . '" />' . "\n";
                $fb_meta .= '<meta property="og:description" content="' . $description . '" />' . "\n";
                if ($thumb) {
                    $fb_meta .= '<meta property="og:image" content="' . TB_Utils::escapeHtmlImage($thumb) . '" />' . "\n";
                }

                // Twitter Cards
                $twitter_meta = '<meta name="twitter:card" content="summary_large_image" />' . "\n";
                $twitter_meta .= '<meta name="twitter:site" content="@' . $this->themeData['twitter']['username'] . '" />' . "\n";
                $twitter_meta .= '<meta name="twitter:creator" content="@' . $this->themeData['twitter']['username'] . '" />' . "\n";
                $twitter_meta .= '<meta name="twitter:title" content="' . $story['title'] . '" />' . "\n";
                $twitter_meta .= '<meta name="twitter:description" content="' . $description . '" />' . "\n";
                if ($thumb) {
                    $twitter_meta .= '<meta name="twitter:image" content="' . $thumb . '" />' . "\n";
                }

                $this->themeData->fbMeta = $fb_meta;
                $this->themeData->twitterMeta = $twitter_meta;
            }
        }
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Body',
                'elements'          => '',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'h1' => array(
                'section_name'      => 'H1',
                'elements'          => 'h1',
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
            'h2' => array(
                'section_name'      => 'H2',
                'elements'          => 'h2',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 16,
                'line-height'       => 20,
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
            'h3' => array(
                'section_name'      => 'H3',
                'elements'          => 'h3',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 15,
                'line-height'       => 20,
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
            'h4' => array(
                'section_name'      => 'H4',
                'elements'          => 'h4',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 14,
                'line-height'       => 20,
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
            )
        );
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'body' => array(
                '_label' => 'Article',
                'text' => array(
                    'label'       => 'Body',
                    'elements'    => '.tb_text_wrap',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'text_links' => array(
                    'label'       => 'Links',
                    'elements'    => '
                        .tb_text_wrap a:not(.btn):not(:hover)
                    ',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '
                        .tb_text_wrap a:not(.btn):hover
                    ',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links_hover'
                ),
                'titles' => array(
                    'label'       => 'Titles',
                    'elements'    => '
                        h1,
                        h2,
                        h3,
                        h4,
                        h5,
                        h6,
                        .h1,
                        .h2,
                        .h3,
                        .h4,
                        .h5,
                        .h6,
                        legend
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
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