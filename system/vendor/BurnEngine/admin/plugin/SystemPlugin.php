<?php

//require_once TB_THEME_ROOT . '/library/ClassCacheGenerator.php';

class Theme_Admin_SystemPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'system';
    }

    public function filterSettings(array &$system_settings)
    {
        $default_vars = array(
            'critical_css'       => '',
            'image_lazyload'     => 1,
            'js_lazyload'        => 0,
            'js_lazyload_expand' => 100,
            'bg_lazyload'        => 0,
            'bg_lazyload_expand' => 100,
            'combine_js'         => 1,
            'minify_js'          => 0,
            'minify_css'         => 1,
            'minify_html'        => 0,
            'cache_enabled'      => 1,
            'cache_js'           => 1,
            'cache_styles'       => 1,
            'cache_settings'     => 1,
            'cache_menu'         => 1,
            'cache_classes'      => 1,
            'cache_content'      => 1,
            'optimize_js_load'   => 1,
            'defer_js_load'      => 1,
            'optimize_exclude'   => '',
            'cache_widgets'      => array(
                'Theme_LatestProductsWidget' => array(
                    'enabled' => 1,
                    'ttl'     => 60
                ),
                'Theme_LatestReviewsWidget' => array(
                    'enabled' => 1,
                    'ttl'     => 60
                )
            ),
            'compatibility_colorbox' => 0,
            'tb_optimizations_mod'   => 0,
            'pages' => array()
        );

        if ($this->engine->gteOc2()) {
            $default_vars['compatibility_moment_js'] = 1;
        }

        if (defined('TB_OPTIMIZATIONS_MOD') && defined('TB_OPTIMIZATIONS_COMPATIBILITY') && defined('TB_OPTIMIZATIONS_DATABASE')) {
            $system_settings['tb_optimizations_mod'] = 1;

            foreach (unserialize(TB_OPTIMIZATIONS_COMPATIBILITY) as $key => $value) {
                $default_vars['compatibility_' . $key] = $value['default'];
            }

            $optimization_database = unserialize(TB_OPTIMIZATIONS_DATABASE);

            $default_vars['cache_db'] = $optimization_database['cache_db'];

            foreach ($optimization_database['cache_items'] as $key => $value) {
                $default_vars['cache_' . $key] = $value['default'];
                $default_vars['cache_' . $key . '_ttl'] = $value['ttl'];
            }
        }

        $system_settings = TB_FormHelper::initFlatVarsSimple($default_vars, $system_settings);

        $cache_widgets_defaults = array();
        foreach ($this->getCacheWidgetNames() as $class => $name) {
            $cache_widgets_defaults[$class]['enabled'] = 1;
            $cache_widgets_defaults[$class]['ttl'] = 240;
        }

        $system_settings['cache_widgets'] = array_replace($cache_widgets_defaults, $system_settings['cache_widgets']);
    }

    protected function getCacheWidgetNames()
    {
        static $classes;

        if (null !== $classes) {
            return $classes;
        }

        $exclude = array(
            'Theme_GroupWidget',
            'Theme_BlockGroupWidget',
            'Theme_TextWidget',
            'Theme_SeparatorWidget',
            'Theme_BannerWidget',
            'Theme_CallToActionWidget',
            'Theme_GalleryWidget',
            'Theme_IconListWidget',
            'Theme_GoogleMapsWidget',
            'Theme_FireSliderWidget',
            'Theme_MenuWidget',
            'Theme_OpenCartWidget',
            'Theme_RecentlyViewedProductsWidget',
            'Theme_AlsoBoughtProductsWidget'
        );

        $classes = array();

        foreach ($this->engine->getWidgetManager()->getWidgetsInfo() as $info) {
            if (!in_array($info['class'], $exclude) && !$info['system'] && $info['cache']) {
                $classes[$info['class']] = $info['name'];
            }
        }

        $oc_widgets = $this->engine->getWidgetManager()->caveOpenCartWidgets();

        /** @var Theme_OpenCartWidget $widget */
        foreach ($oc_widgets as $widget) {
            $event = new sfEvent($this, 'core:canCacheOcModule', array('module_settings' => $widget->getOcModuleSettings()));
            if ($this->engine->getEventDispatcher()->notifyUntil($event)->isProcessed()) {
                continue;
            }

            $classes[TB_Utils::underscore($widget->getName())] = $widget->getName();
        }

        return $classes;
    }

    public function setDataForView(&$system_settings, TB_ViewDataBag $themeData)
    {
        $themeData->cache_widget_names = $this->getCacheWidgetNames();
        if (defined('TB_OPTIMIZATIONS_MOD') && defined('TB_OPTIMIZATIONS_COMPATIBILITY') && defined('TB_OPTIMIZATIONS_DATABASE')) {
            $themeData->optimizations_compatibility = unserialize(TB_OPTIMIZATIONS_COMPATIBILITY);
            $themeData->optimizations_database = unserialize(TB_OPTIMIZATIONS_DATABASE);
        } else {
            $system_settings['tb_optimizations_mod'] = 0;
        }

        $themeData->mysql_version = $this->engine->getOcDb()->query('SELECT VERSION() as mysql_version')->row['mysql_version'];

        $memory_limit = ini_get('memory_limit');
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'M') {
                $memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
            }
        }

        $themeData->memory_limit = round($memory_limit / 1024 / 1024);
    }

    public function saveData($post_data, &$theme_settings)
    {
        $data = $post_data[$this->getConfigKey()];

        if (!$data['cache_styles']) {
            $data['cache_settings'] = 0;
        }

        /*
        if ($data['cache_classes'] && $data['cache_enabled']) {
            TB_ClassCacheGenerator::buildCache($this->context->getStoreId(), $this->context->isDebug());
        } else {
            TB_ClassCacheGenerator::deleteCache($this->context->getStoreId());
        }
        */

        if (isset($theme_settings[$this->getConfigKey()])) {
            $system_settings = $theme_settings[$this->getConfigKey()];

            if (!$data['cache_enabled'] && ($system_settings['cache_enabled'] != $data['cache_enabled'])) {
                $this->engine->wipeAllCache();
            } else
            if ($system_settings['cache_content'] != $data['cache_content']) {
                $this->engine->wipeVarsCache('*area_contents*');
            }
        }

        if (isset($theme_settings['system']['pages'])) {
            foreach ($theme_settings['system']['pages'] as $hash => $page) {
                $remove = true;
                if (isset($data['pages']) && !empty($data['pages'])) {
                    foreach ($data['pages'] as $data_hash => $data_page) {
                        if ($data_page['route'] == $page['route']) {
                            $remove = false;
                            break;
                        }
                    }
                }
                if (true === $remove) {
                    unset($theme_settings['system_routes'][$page['route']]);
                    unset($theme_settings['system']['pages'][$hash]);
                }
            }
        }

        if (isset($data['pages']) && !empty($data['pages'])) {
            foreach ($data['pages'] as $data_page) {
                $theme_settings['system_routes'][$data_page['route']] = 1;
            }
        }

        $theme_settings['install_info']['mysql_version'] = $this->getThemeData()->mysql_version;

        return array(
            $this->getConfigKey() => $data
        );
    }
}