<?php

class Theme_Catalog_LayoutBuilderPlugin extends TB_ExtensionPlugin
{
    protected $init_order              = 5000;
    /**
     * @var TB_WidgetsArea[]
     */
    protected $areas                   = array();
    protected $area_contents           = array();
    protected $area_settings_cache_key = '';
    protected $area_contents_cache_key = '';
    protected $area_cache_expire;

    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        /*
         * ToDo LazyLoading to determine areas and layout builder blocks. Process on demand.
        if (TB_RequestHelper::isAjaxRequest()) {
            return;
        }
        */

        if ($this->getThemeData()->skip_layout) {
            return;
        }

        $this->bootstrap('common');
        $this->bootstrap('fonts');
        $this->bootstrap('style');
        $this->bootstrap('maintenance');

        $themeData->addCallable(array($this, 'hasArea'));
        $themeData->addCallable(array($this, 'getArea'));
        $themeData->addCallable(array($this, 'renderArea'));
        $themeData->addCallable(array($this, 'getAreaSettings'));
        $themeData->addCallable(array($this, 'areaExists'));
        $themeData->addCallable(array($this, 'getContentCssClasses'));

        $this->engine->getWidgetManager()->loadWidgetClasses();
        $this->area_cache_expire = $this->engine->getConfig('default_cache');

        /*
        if ($themeData->route != 'error/not_found') {
            $content_cache_key = substr($this->context->getRequestUrl(), strlen($this->context->getBaseHttpsIf()));
        } else {
            $content_cache_key = 'error/not_found';
        }

        if (empty($content_cache_key)) {
            $content_cache_key = 'home';
        } else {
            $content_cache_key = preg_replace('/^index.php\?route=/s', '', $content_cache_key);
            $content_cache_key = strtolower(trim(preg_replace('/[^A-Za-z0-9-_\.]+/', '-', $content_cache_key)));
        }
        $this->area_contents_cache_key = 'area_html.' . $content_cache_key;
        */

        $request_area_keys = array();
        foreach ($themeData->request_areas as $area_name => $area_id) {
            $request_area_keys[] = $area_name . '_' . $area_id;
        }
        $cache_key = str_replace('/', '_', implode('.', $request_area_keys)) . '.' . $this->context->getStoreId() . '.' . $this->language_code;

        if ($themeData->information_id) {
            $additional_cache_key =  '.information-' . $themeData->information_id;
        } else
        if ($themeData->category_id) {
            $additional_cache_key =  '.category-' . $themeData->category_id;
        } else
        if ($themeData->route == 'product/product' && isset($this->engine->getOcRequest()->get['product_id']) && is_numeric($this->engine->getOcRequest()->get['product_id'])) {
            $additional_cache_key =  '.product-' . (int) $this->engine->getOcRequest()->get['product_id'];
        } else {
            $additional_cache_key = '.' . strtolower(trim(preg_replace('/[^A-Za-z0-9-_\.]+/', '-', $this->getThemeData()->route)));
            /*
            $additional_cache_key = substr($this->context->getRequestUrl(), strlen($this->context->getBaseHttpsIf()));
            $additional_cache_key = str_replace('&', '-', html_entity_decode($additional_cache_key, ENT_QUOTES, 'UTF-8'));
            $additional_cache_key = trim(preg_replace('/^index.php\?route=/s', '', $additional_cache_key));
            if (!empty($additional_cache_key)) {
                $additional_cache_key = '.' . strtolower(trim(preg_replace('/[^A-Za-z0-9-_\.]+/', '-', $additional_cache_key)));
            }
            */
        }

        if (TB_RequestHelper::isRequestHTTPS()) {
            $additional_cache_key .= '.ssl';
        }
        $additional_cache_key .= '.' . $themeData->currency_code;

        $this->area_contents_cache_key = 'area_contents.' . $cache_key . $additional_cache_key;

        $cached_contents = $this->engine->getCacheVar($this->area_contents_cache_key);

        $area_settings_keys = array();
        foreach ($themeData->request_areas_settings as $area_name => $area_id) {
            $area_settings_keys[] = $area_name . '_' . $area_id;
        }
        //$settings_cache_key = str_replace('/', '_', implode('.', $area_settings_keys)) . '.' . $this->context->getStoreId() . '.' . $this->language_code;
        $this->area_settings_cache_key = 'area_settings.' . $cache_key;

        // The settings need to be rebuilt if the css is about to be regenerated
        if (false === $this->extension->getStylesCacheKey() || !$themeData['system']['cache_enabled'] || !$themeData['system']['cache_settings']) {
            $this->buildAreaSettings($request_area_keys, $area_settings_keys);
        } else {
            $this->areas = $this->engine->getCacheVar($this->area_settings_cache_key);

            if (null === $this->areas) {
                $this->buildAreaSettings($request_area_keys, $area_settings_keys);
            } else {
                $this->initAreas($themeData);
                if (!$cached_contents) {
                    foreach ($this->areas as $area) {
                        $area->configureWidgets();
                    }
                }
            }
        }

        $build_cache = false;

        if ($cached_contents) {

            foreach ($cached_contents as $area_name => $contents) {
                /** @var TB_WidgetsArea $area */
                $area = $this->areas[$area_name];

                $area->addJsContents($contents['js']);

                foreach ($contents['attributes'] as $key => $value) {
                    $area->addAttribute($key, $value);
                }

                foreach ($contents['widgets'] as $id => $attributes) {
                    $widget = $area->getWidget($id);
                    $widget->mergeAttributes($attributes);

                    if (!$widget->getAttribute('cacheContent')) {
                        if (!$widget->getAttribute('configured')) {
                            $area->configureWidgetById($id);
                        }
                    } else
                    if ($widget->getAttribute('cacheExpire') < time()) {
                        $widget->addAttribute('cacheExpire', $widget->getAttribute('cacheTTL') + time());
                        $widget->removeAttribute('content');
                        $widget->removeAttribute('asyncCache');
                        $area->configureWidgetById($id);
                        $build_cache = true;
                    } else {
                        $widget->setHasContent(true);
                    }
                }

                $this->area_contents[$area_name] = $contents;
            }
        } else
        if (!empty($this->areas)) {
            $build_cache = true;
        }

        if ($build_cache && $themeData['system']['cache_enabled'] && $themeData['system']['cache_content']) {
            $this->eventDispatcher->connect('view:output', array($this, 'buildContentCache'));
        }
        $this->eventDispatcher->connect('core:generateJs', array($this, 'buildAreasJs'));

        $system_slots = array();
        foreach ($this->areas as $area) {
            $system_slots = array_merge($system_slots, $area->getSystemSlots());
        }

        $this->getThemeData()->areas_system_slots = $system_slots;
    }

    public function buildAreaSettings(array $area_keys, array $area_settings_keys)
    {
        $this->areas = array();
        $themeData = $this->getThemeData();
        $widgetManager = $this->engine->getWidgetManager();

        $keys = $widgetManager->loadAreasData($area_keys, $area_settings_keys);

        /** @var Theme_Catalog_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');

        foreach ($themeData->request_areas as $area_name => $area_id) {
            if (!isset($keys['area'][$area_name])) {
                continue;
            }

            if ($keys['area'][$area_name] != $area_id) {
                throw new Exception('Area detection mismatch');
            }

            $area_data = $widgetManager->getAreaBuilder($area_name . '_' . $area_id);

            if (!isset($keys['area_settings'][$area_name])) {
                $area_data['settings'] = array();
            } else {
                $area_data['settings'] = $widgetManager->getAreaStyle($area_name . '_' . $keys['area_settings'][$area_name]);
            }

            $area_data['settings']['skip_font'] = true;
            $layoutBuilderModel->filterAreaSettings($area_data['settings'], 'area_' . $area_name);
            unset($area_data['settings']['skip_font']);

            $area = new TB_WidgetsArea($area_name, $widgetManager);
            $area->createFromArray($area_data);

            if (!count($area->getRows())) {
                continue;
            }

            $area->configureWidgets();

            $area_settings = $area->getSettings();

            if ($area_name == 'content') {
                $area->addData('row_classes', $this->buildRowClasses($area_settings));
                if ($themeData->route == 'product/product') {
                    if (!isset($area_settings['layout']['extra_class'])) {
                        $area_settings['layout']['extra_class'] = '';
                    }
                    $area_settings['layout']['extra_class'] .= ' product-info';
                }
            }

            $area->addData('css_classes', $this->buildLayoutClasses($area_settings, $area_name));


            $this->areas[$area_name] = $area;
        }

        $this->initAreas($themeData);
        if ($themeData['system']['cache_styles'] && $themeData['system']['cache_settings'] && !empty($this->areas)) {
            $this->eventDispatcher->connect('view:output', array($this, 'buildSettingsCache'));
        }
    }

    protected function initAreas(TB_ViewDataBag $themeData)
    {

        $system_settings = $themeData->system;

        foreach ($this->areas as $area) {
            $area->initWidgets($themeData);
            $area_name = $area->getName();

            $css_classes  = 'tb_area_' . $area_name;
            $css_classes .= $area->getData('css_classes');
            $css_classes .= $area_name == 'content' && !empty($system_settings['critical_css']) ? ' tb_preload tb_loading' : '';
            $css_classes .= $system_settings['bg_lazyload'] ? ' lazyload' : '';
            $attributes   = ' id="' . $area_name . '"';
            $attributes  .= ' class="' . $css_classes . '"';
            $attributes  .= $system_settings['bg_lazyload'] ? ' data-expand="' . $system_settings['bg_lazyload_expand'] . '"' : '';

            $themeData->{$area_name . '_section_attributes'} = $attributes;

            if ($area_name == 'content') {
                $themeData->{$area_name . '_row_css_classes'} = $area->getData('row_classes');
            }

            $themeData->{$area_name . '_layout_settings'} = $area->getSetting('layout', array());

            $cache_widgets = $themeData['system']['cache_widgets'];
            $expire = 0;

            foreach ($area->getWidgets() as $widget) {
                $cache_key = get_class($widget);

                if ($cache_key == 'Theme_OpenCartWidget') {
                    $cache_key = TB_Utils::underscore($widget->getName());
                }

                if (isset($cache_widgets[$cache_key]) && $cache_widgets[$cache_key]['enabled']) {
                    if ($cache_widgets[$cache_key]['ttl'] > $expire) {
                        $expire = (int) $cache_widgets[$cache_key]['ttl'];
                    }
                } else
                if (get_class($widget) == 'Theme_OpenCartWidget') {
                    // OpenCart widget needs pre-rendering because of the addScripts() and addStyles() calls. Its layout is 'TB_Widgets' and the widget does not get called from open cart
                    $widget->render();
                }
            }

            $expire = abs($expire * 60);

            if ($expire > $this->area_cache_expire) {
                $this->area_cache_expire = $expire;
            }
        }

        $this->eventDispatcher->notify(new sfEvent($this, 'layoutBuilder:initAreas'));
    }

    public function buildContentCache()
    {
        $cache = array();

        foreach ($this->area_contents as $area_name => $contents) {

            $area = $this->areas[$area_name];

            $contents['html'] = preg_replace('/\n\s*\n/', "\n", $contents['html']);
            $contents['js'] = trim($area->getJsContents());
            $contents['attributes'] = array(
                'register_sticky' => $area->getAttribute('register_sticky')
            );

            $this->eventDispatcher->notify(new sfEvent($area, 'core:buildContentCache'));

            foreach ($area->getWidgets() as $widget) {
                // $contents['widgets'] hold the widgets that are to be rendered as placeholders
                if (isset($contents['widgets'][$widget->getId()]) && $widget->getAttribute('renderPlaceHolder')) {
                    if (!$widget->getAttribute('cacheContent')) {
                        $widget->removeAttribute('content');
                        //$widget->removeAttribute('js');
                        $widget->removeAttribute('configured');
                    } else {
                        if (!$widget->getAttribute('asyncCache') && method_exists($widget, 'onAreaContentOutput')) {
                            $widget_html = $widget->getAttribute('content');
                            $widget->onAreaContentOutput($widget_html);
                            $widget->addAttribute('content', $widget_html);
                        }
                        /*
                        if ($js = $widget->getAttribute('js')) {
                            $widget->addAttribute('js', preg_replace('/\n\s*\n/', "\n", $js));
                        }
                        */
                        $widget->addAttribute('asyncCache', 1);
                    }

                    $contents['widgets'][$widget->getId()] = $widget->getAttributes();
                } else
                // Fully cached widgets
                if ($widget->getAttributes()) {
                    if (method_exists($widget, 'onAreaContentOutput')) {
                        $widget->onAreaContentOutput($contents['html']);
                    }
                    /*
                    if ($js = $widget->getAttribute('js')) {
                        $contents['js'] .= preg_replace('/\n\s*\n/', "\n", $js);
                    }
                    */
                }
            }

            $cache[$area_name] = $contents;
        }

        $this->engine->setCacheVar($this->area_contents_cache_key, $cache, null, true);
    }

    public function buildSettingsCache()
    {
        foreach ($this->areas as $area) {
            foreach ($area->getWidgets() as $widget) {
                if ($widget instanceof Theme_SystemWidget && $widget->getAttribute('defaultSlotPrefix')) {
                    $widget->setSlotPrefix($widget->getAttribute('defaultSlotPrefix'));
                }
            }
        }
        $this->engine->setCacheVar($this->area_settings_cache_key, $this->areas);
    }

    public function buildAreasJs()
    {
        foreach ($this->areas as $area) {
            $this->engine->getThemeExtension()->addJsContents($area->getJsContents());
            if ($area->getAttribute('register_sticky')) {
                $this->getThemeData()->registerJavascriptResource('javascript/sticky-kit.min.js');
            }
        }
    }

    public function hasArea($area_name, $slot_prefix = null)
    {
        if (!isset($this->areas[$area_name])) {
            return false;
        }

        $area = $this->areas[$area_name];

        if (!$area->getAttribute('system_widgets_booted')) {
            if (null == $slot_prefix) {
                $slot_prefix = $this->getThemeData()->route;
            }

            foreach ($area->getWidgets() as $widget) {
                if ($widget instanceof Theme_SystemWidget) {
                    // Some system widgets should not depend on the template slot prefix because they can be displayed on different layouts.
                    // For example the 'breadcrumbs' system widget can be set in the global intro area
                    // We set a temporal slot prefix depending on the current route and defaultSlotPrefix attribute, which will revert the slot
                    // prefix to its default value in 'buildSettingsCache' method
                    $widget->addAttribute('defaultSlotPrefix', $widget->getSlotPrefix());
                    $widget->setSlotPrefix($slot_prefix);
                }

                if ($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) {
                    foreach ($widget->getSubWidgets() as $subWidget) {
                        if ($subWidget instanceof Theme_SystemWidget) {
                            $widget->addAttribute('defaultSlotPrefix', $subWidget->getSlotPrefix());
                            $subWidget->setSlotPrefix($slot_prefix);
                        }
                    }
                }
            }

            $area->addAttribute('system_widgets_booted', 1);
        }

        if (!isset($this->area_contents[$area_name])) {
            $this->buildArea($area, $slot_prefix);
        }

        return !empty($this->area_contents[$area_name]['html']);
    }

    /**
     * @param $area_name
     * @param null $slot_prefix
     * @return null|TB_WidgetsArea
     */
    public function getArea($area_name, $slot_prefix = null)
    {
        if (!$this->hasArea($area_name, $slot_prefix)) {
            return null;
        }

        return $this->areas[$area_name];
    }

    public function areaExists($area_name)
    {
        return isset($this->areas[$area_name]) ? 1 : 0;
    }

    public function getAreaSettings($area_name, $key = null)
    {
        if (!isset($this->areas[$area_name])) {
            return null;
        }

        return $key !== null ? $this->areas[$area_name]->getSetting($key) : $this->areas[$area_name]->getSettings();
    }

    public function renderArea($area_name, $slot_prefix = null)
    {
        if (!$this->hasArea($area_name, $slot_prefix)) {
            return '';
        }

        $area = $this->areas[$area_name];
        $contents = $this->area_contents[$area_name];

        foreach ($contents['widgets'] as $id => $attributes) {
            $widget = $area->getWidget($id);
            if (null === $widget) {
                $this->engine->fbLog('Invalid widget somehow: ' . $widget->getId() . ' - ' . $widget->getName());

                continue;
            }

            $widget_html = $widget->getAttribute('content');

            if (null === $widget_html && !$widget->isRendered()) {
                // The widget is rendered as placeholder in the cache and its cache has expired (or turned off)
                $widget_html = $widget->render();
                $expired_widgets[] = $widget->getId();
            }

            $contents['html'] = str_replace('{{' . $widget->getId() . '}}', $widget_html, $contents['html']);
        }

        foreach ($area->getWidgets() as $widget) {
            if ($js = $widget->getAttribute('js')) {
                $area->addJsContents($js);
            }

            // If the widget is fully cached, getAttributes will return empty array
            // The following condition is true before building the cache or if the widget is rendered as placeholder but not cached (widgets with cache off and system widgets)
            // The additional asyncCache attribute is used instead of cacheContent, because before building the cache every widget has cacheContent 1 and asyncCache 0, and after building the cache the widgets with different TTL have asyncCache 1
            if ($widget->getAttributes() && !$widget->getAttribute('asyncCache')) {
                if (method_exists($widget, 'onAreaContentOutput')) {
                    $widget->onAreaContentOutput($contents['html']);
                }
            }
            if ($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) {
                $contents['html'] = str_replace('{{current_url}}', $this->getThemeData()->current_url, $contents['html']);
            }
        }
        // This is the html with the placeholders rendered
        //$this->area_contents[$area_name]['html'] = $contents['html'];

        $event = new TB_ViewSlotEvent($this, 'area_' . $area_name . ':output');
        $event->setContent($contents['html']);

        $this->eventDispatcher->notify($event);

        return $event->getAllContent();
    }

    public function buildArea(TB_WidgetsArea $area)
    {
        $system_settings = $this->getThemeData()->system;

        $result = array(
            'html'    => '',
            'widgets' => array()
        );

        $register_sticky = false;

        /** @var Theme_Catalog_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');

        foreach ($area->getRows() as $row) {

            $layoutBuilderModel->filterRowSettings($row['settings']);

            /* @var $row_widgets TB_Widget[] */
            $row_widgets = array();

            foreach ($row['columns'] as &$column) {

                if (!isset($column['settings'])) {
                    $column['settings'] = array();
                }

                $column['settings']['grid_proportion'] = $column['grid_proportion'];

                $layoutBuilderModel->filterColumnSettings($column['settings'], $row['settings']);

                if (isset($column['widgets'])) {
                    foreach ($column['widgets'] as $widget) {
                        if ($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) {
                            foreach ($widget->getSubWidgets() as $subWidget) {
                                $row_widgets[] = $subWidget;
                            }
                        }
                        $row_widgets[] = $widget;
                    }
                }

                // Sticky column
                if ($row['settings']['layout']['sticky_columns'] == 'custom' && $column['settings']['layout']['is_sticky']) {
                    $register_sticky = true;
                    $area->addJsContents('stickyColumn("' . '.col_' . $column['id'] . '",' . (int) $column['settings']['layout']['sticky_offset'] . ');');
                }
            }

            $row['wrap_classes']     = 'row_' . $row['id'];
            $row['wrap_classes']    .= $this->buildLayoutClasses($row['settings']);
            $row['wrap_classes']    .= !empty($row['settings']['preset_id']) ? ' pr_' . $row['settings']['preset_id'] : '';
            $row['wrap_classes']    .= $system_settings['bg_lazyload'] ? ' lazyload' : '';
            $row['wrap_attributes']  = ' class="' . $row['wrap_classes'] . '"';
            $row['wrap_attributes'] .= $system_settings['bg_lazyload'] ? ' data-expand="' . $system_settings['bg_lazyload_expand'] . '"' : '';

            $row['row_classes']    = $this->buildRowClasses($row['settings']);
            $row['row_attributes'] = ' class="' . $row['row_classes'] . '"';

            $row['area_name']   = $area->getName();

            // Sticky row
            if ($row['settings']['layout']['sticky_columns'] == 'all') {
                $selectors = array();
                foreach ($row['columns'] as $row_column) {
                    $selectors[] = '.col_' . $row_column['id'];
                }

                $register_sticky = true;
                $area->addJsContents('stickyColumn("' . implode(',', $selectors) . '",' . (int) $row['settings']['layout']['sticky_offset'] . ');');
            }

            if ($system_settings['cache_enabled'] && $system_settings['cache_content']) {
                foreach ($row_widgets as $widget) {

                    if ($widget instanceof Theme_SystemWidget || !$widget->allowAddToAreaContentCache()) {
                        $widget->addAttribute('renderPlaceHolder', 1);
                        $widget->addAttribute('cacheContent', 0);

                        continue;
                    }

                    $cache_key = get_class($widget);
                    if ($cache_key == 'Theme_OpenCartWidget') {
                        $cache_key = TB_Utils::underscore($widget->getName());

                        if (!isset($system_settings['cache_widgets'][$cache_key])) {
                            $this->eventDispatcher->notify(new sfEvent($widget, 'core:ocWidgetCacheConfig'));
                        }
                    }

                    if (isset($system_settings['cache_widgets'][$cache_key])) {
                        if (!$system_settings['cache_widgets'][$cache_key]['enabled']) {
                            $widget->addAttribute('renderPlaceHolder', 1);
                            $widget->addAttribute('cacheContent', 0);
                        } else {
                            if (($system_settings['cache_widgets'][$cache_key]['ttl'] * 60) < $this->area_cache_expire) {
                                $widget->addAttribute('renderPlaceHolder', 1);
                                $ttl = $system_settings['cache_widgets'][$cache_key]['ttl'] * 60;
                                $widget->addAttribute('cacheTTL',  $ttl);
                                $widget->addAttribute('cacheExpire', $ttl + time());
                            } else {
                                $widget->addAttribute('renderPlaceHolder', 0);
                            }
                            $widget->addAttribute('cacheContent', 1);
                        }
                    }
                }
            }

            $row_html = $this->renderRow($row, $area->getName());

            $has_content = false;
            foreach ($row_widgets as $widget) {
                if ($widget->hasContent()) {
                    $has_content = true;

                    if (!$system_settings['cache_enabled'] || !$system_settings['cache_content']) {
                        break;
                    }

                    if ($widget->getAttribute('renderPlaceHolder')) {
                        $result['widgets'][$widget->getId()] = $widget->getAttributes();
                    }
                }
            }

            if ($has_content) {
                $result['html'] .= $row_html;
            }

            // Sticky area
            if ($has_content && ($area->getName() == 'column_right' || $area->getName() == 'column_left')) {
                $content_area_settings = $this->getAreaSettings('content', 'layout');

                $column_var = $area->getName() == 'column_right' ? 'right' : 'left';
                if ($content_area_settings[$column_var . '_column_is_sticky']) {
                    $register_sticky = true;
                    $area->addJsContents('stickyColumn("' . '#' . $column_var . '_col",' . (int) $content_area_settings[$column_var . '_column_sticky_offset'] . ');');
                }
            }

            if ($register_sticky) {
                $area->addAttribute('register_sticky', true);
            }

        }

        $this->area_contents[$area->getName()] = $result;
    }

    protected function renderRow($row, $area_name = '')
    {
        $columns_content = array();
        foreach ($row['columns'] as $key => $column) {
            $columns_content[$key] = '';
            if (!isset($column['widgets']) || empty($column['widgets'])) {
                continue;
            }
            /** @var TB_Widget $widget */
            foreach ($column['widgets'] as $widget) {
                $widget_content = $widget->render();
                if ($widget->hasContent()) {
                    if ($widget->getAttribute('renderPlaceHolder')) {
                        $columns_content[$key] .= '{{' . $widget->getId() . '}}';
                    } else {
                        $columns_content[$key] .= $widget_content;
                    }
                }
            }
        }

        $last_key = null;
        foreach ($row['columns'] as $key => $column) {
            $contents = trim($columns_content[$key]);
            $row['columns'][$key]['changed_grid_proportion'] = false;

            if (!empty($contents) || !$row['settings']['layout']['merge_columns']) {
                $row['columns'][$key]['html_contents'] = $contents;
                $last_key = $key;
            } else {
                $near_key = null;

                if (null !== $last_key) {
                    $near_key = $last_key;
                } else
                if (isset($row['columns'][$key-1])) {
                    $near_key = $key-1;
                } else
                if (isset($row['columns'][$key+1])) {
                    $near_key = $key+1;
                }

                if (null !== $near_key) {
                    if (!in_array($row['columns'][$near_key]['grid_proportion'], array('auto', 'fill'))) {
                        if ($column['grid_proportion'] == 'fill' || (!isset($row['columns'][$key+1]) && $column['grid_proportion'] == 'auto')) {
                            $row['columns'][$near_key]['grid_proportion'] = 'fill';
                            $row['columns'][$near_key]['changed_grid_proportion'] = true;
                        } else
                        if ($column['grid_proportion'] != 'auto') {
                            list($num, $denum) = explode('_', $column['grid_proportion']);
                            list($near_num, $near_denum) = explode('_', $row['columns'][$near_key]['grid_proportion']);

                            $fraction = new MathFraction($num, $denum);
                            $near_proportion = $fraction->add(new MathFraction($near_num, $near_denum));

                            $row['columns'][$near_key]['grid_proportion'] = str_replace('/', '_', $near_proportion);
                            $row['columns'][$near_key]['changed_grid_proportion'] = true;
                        }
                    }
                    unset($row['columns'][$key]);
                } else {
                    $row['columns'][$key]['html_contents'] = '';
                }
            }
        }

        foreach ($row['columns'] as &$column) {
            if ($column['changed_grid_proportion']) {
                foreach ($column['widgets'] as $widget) {
                    $widget->addAttribute('grid_proportion', $column['grid_proportion']);
                }
            }

            $column['settings']['grid_proportion'] = $column['grid_proportion'];
            $column['column_classes']  = 'col_' . $column['id'];
            $column['column_classes'] .= ' col';
            $column['column_classes'] .= $this->buildColumnClasses($column['settings'], $row['settings']);
            $column['column_attributes'] = ' class="' . $column['column_classes'] . '"';
        }

        return $this->engine->getResourceLoader()->fetchExtensionTemplate($this->extension, $this->context->getEngineAreaTemplateDir() . '/builder_row.tpl', $row, true);
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        TB_ColorSchemer::getInstance()->setFilteredColors($this->getThemeData()->colors, 'theme');
        /** @var Theme_Catalog_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');

        foreach ($this->areas as $area) {

            $area_name = $area->getName();
            $area_settings = $area->getSettings();

            $filter_area_name = 'area_' . $area_name;
            if ($area_name == 'column_left' || $area_name == 'column_right') {
                $filter_area_name = 'area_content';
            }

            $area_default_colors = AreaItemData::getAreaColors($filter_area_name);

            if (empty($area_settings)) {
                // The area has no default settings in global.php
                $layoutBuilderModel->filterSettings($area_settings, 'area_' . $area_name, $area_default_colors);

                // initAreas() is executed before this method. If there is no default record for the area, {$area_name . '_layout_settings'} will be empty
                $this->getThemeData()->{$area_name . '_layout_settings'} = $area_settings['layout'];

                if (isset($area_settings['font'][$this->language_code])) {
                    $layoutBuilderModel->cleanFontDataBeforePersist($area_settings['font']);
                }
            }

            // Background inheritance
            if (!empty($area_settings['background']['solid_color_inherit_key'])) {
                $area_settings['background']['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->getThemeData()->colors, $area_settings['background']['solid_color_inherit_key']);
            }

            if (isset($area_settings['colors'])) {
                // Need to filter them because of force_print value. If the main colors are saved without loading the area settings, the area settings may contain wrong inherited color values with force_print setting. They need to be resolved from the main colors.
                TB_ColorSchemer::getInstance()->filterAreaColors($area_settings['colors'], $area_default_colors, $filter_area_name);
            }

            $layoutBuilderModel->cleanSettingsColorsBeforePersist($area_settings['colors']);

            $area_css_id = strtolower(str_replace(' ', '_', $area_name));

            $this->addStyleColors($area_settings, $area_css_id, $styleBuilder);

            $styleBuilder->buildEffectsCss($area_settings, '#' . $area_css_id);
            if (isset($area_settings['font'][$this->language_code])) {
                $styleBuilder->addFonts($area_settings['font'][$this->language_code], $area_css_id);
            }

            foreach ($area->getRows() as $row) {
                // Background inheritance
                if (!empty($row['settings']['background']['solid_color_inherit_key'])) {
                    $row['settings']['background']['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->getThemeData()->colors, $row['settings']['background']['solid_color_inherit_key']);
                }

                $styleBuilder->buildEffectsCss($row['settings'], '.row_' . $row['id']);

                if (isset($row['settings']['font'][$this->language_code])) {
                    $styleBuilder->addFonts($row['settings']['font'][$this->language_code], '.row_' . $row['id']);
                }

                if (!isset($row['settings']['colors'])) {
                    $row['settings']['colors'] = array();
                }

                if (isset($row['settings']['colors'])) {
                    // Filter to resolve force_print values.
                    TB_ColorSchemer::getInstance()->filterRowColors($row['settings']['colors'], AreaItemData::getRowColors());
                    $layoutBuilderModel->cleanSettingsColorsBeforePersist($row['settings']['colors']);
                }

                $this->addStyleColors($row['settings'], '.row_' . $row['id'], $styleBuilder);

                foreach ($row['columns'] as $column) {

                    if (!isset($column['settings'])) {
                        $column['settings'] = array();
                    }

                    // ToDo compatibility hack to remove (Pavilion  < 1.3 does not have column id)
                    if (!isset($column['id'])) {
                        $column['id'] = TB_Utils::genRandomString();
                    }

                    if (isset($column['widgets'])) {

                        if (!isset($column['settings']['colors'])) {
                            $column['settings']['colors'] = array();
                        }

                        // Background inheritance
                        if (!empty($column['settings']['background']['solid_color_inherit_key'])) {
                            $column['settings']['background']['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->getThemeData()->colors, $column['settings']['background']['solid_color_inherit_key']);
                        }

                        $styleBuilder->buildEffectsCss($column['settings'], '.col_' . $column['id']);

                        if (isset($row['settings']['colors'])) {
                            TB_ColorSchemer::getInstance()->filterColumnColors($column['settings']['colors'], AreaItemData::getColumnColors());
                            $layoutBuilderModel->cleanSettingsColorsBeforePersist($column['settings']['colors']);
                        }

                        $this->addStyleColors($column['settings'], '.col_' . $column['id'], $styleBuilder);

                        foreach ($column['widgets'] as $widget) {
                            $this->addWidgetAssets($widget, $styleBuilder);
                        }
                    } else {
                        $styleBuilder->buildEffectsCss($column['settings'], '.col_' . $column['id']);
                    }

                    if (!empty($column['settings']['layout']['height']) && $column['settings']['layout']['height'] != 'auto') {
                        $column_css = '.col_' . $column['id'] . ' {' .
                                      '  min-height: ' . $column['settings']['layout']['height'] . ';' .
                                      '}' .
                                      (!isset($column['widgets']) ?
                                      '.col_' . $column['id'] . ':empty {' .
                                      '  display: block !important;' .
                                      '}' : '');

                        $styleBuilder->addCss($column_css);
                    }
                }
            }
        }
    }

    protected function addWidgetAssets(TB_Widget $widget, TB_StyleBuilder $styleBuilder)
    {
        foreach (array('box', 'title') as $style_type) {
            if (!($styles = $widget->getSettings($style_type . '_styles')) || ($style_type == 'title' && !$widget->hasTitleStyles())) {
                continue;
            }

            $css_selector = $widget->getDomId();

            // Filter to resolve force_print values.
            if (!$widget->getSettings('preset_id') || !empty($styles['colors'])) {
                /** @var Theme_Catalog_LayoutBuilderModel $layoutBuilderModel */
                $layoutBuilderModel = $this->getModel('layoutBuilder');

                TB_ColorSchemer::getInstance()->filterWidgetColors($styles['colors'], $style_type == 'box'  ? $widget->getDefaultBoxColors() : $widget->getDefaultTitleColors(), $widget->getId());

                $layoutBuilderModel->cleanSettingsColorsBeforePersist($styles['colors']);
                $widget->setSettings($styles, $style_type . '_styles');

                $this->addStyleColors($styles, '#' . $css_selector, $styleBuilder);
            }

            if (isset($styles['font'][$this->language_code])) {
                $styleBuilder->addFonts($styles['font'][$this->language_code], '#' . $css_selector);
            }

            if ($style_type == 'title') {
                if ($widget instanceof Theme_OpenCartWidget) {
                    $css_selector .= ' .panel-heading, #' . $css_selector . ' .box-heading';
                    $styleBuilder->buildBoxModelCss($styles['layout'], '#' . $css_selector);
                } else {
                    $css_selector .= ' .panel-heading';
                }
            }

            $styleBuilder->buildEffectsCss($styles, '#' . $css_selector);
        }

        if (method_exists($widget, 'buildStyles')) {
            $widget->buildStyles($styleBuilder);
        }

        if ($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) {
            foreach ($widget->getSubWidgets() as $widget) {
                $this->addWidgetAssets($widget, $styleBuilder);
            }
        }
    }

    protected function addStyleColors(array $settings, $id, TB_StyleBuilder $styleBuilder)
    {
        if (!isset($settings['colors']) || !is_array($settings['colors'])) {
            return;
        }

        $bg_color = isset($settings['background']['solid_color']) ? $settings['background']['solid_color'] : null;

        foreach ($settings['colors'] as $group_values) {
            foreach ($group_values as $color_rule) {
                // The color rule can be string because of colors filtering in self::buildStyles()
                if (is_array($color_rule)) {
                    $styleBuilder->addScopedColorRule($id, $color_rule, $bg_color);
                }
            }
        }
    }

    public function buildLayoutClasses(array $settings, $area_name = false)
    {
        if (!isset($settings['layout'])) {
            return '';
        }

        $layout   = $settings['layout'];
        $lang_dir = $this->getThemeData()->language_direction;

        $margin_left   = $lang_dir == 'rtl' && !empty($layout['margin_rtl_mode'])  ? $layout['margin_right']  : $layout['margin_left'];
        $margin_right  = $lang_dir == 'rtl' && !empty($layout['margin_rtl_mode'])  ? $layout['margin_left']   : $layout['margin_right'];
        $padding_left  = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_right'] : $layout['padding_left'];
        $padding_right = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_left']  : $layout['padding_right'];

        $classes  = '';

        if ($area_name && $area_name != 'content') {
            $classes .= $layout['type'] == 'fixed' ? ' container' : '';
            $classes .= $layout['type'] == 'full' ? ' container-fluid' : '';
            $classes .= $layout['type'] == 'full_fixed' ? ' container-fluid tb_content_fixed' : '';
        } else {
            $classes .= ' row-wrap';
            $classes .= $layout['type'] == 'fixed' ? ' tb_width_fixed' : '';
            $classes .= $layout['type'] == 'full_fixed' ? ' tb_content_fixed' : '';
        }

        if (empty($settings['preset_id'])) {
            $classes .= $layout['margin_top'] != 0 ? ' tb_mt_' . $layout['margin_top'] : '';
            $classes .= $margin_right != 0 ? ' tb_mr_' . $margin_right : '';
            $classes .= $layout['margin_bottom'] != 0 ? ' tb_mb_' . $layout['margin_bottom'] : '';
            $classes .= $margin_left != 0 ? ' tb_ml_' . $margin_left : '';

            if (empty($layout['separate_columns'])) {
                $classes .= $layout['padding_top'] != 0 ? ' tb_pt_' . $layout['padding_top'] : '';
                $classes .= $padding_right != 0 ? ' tb_pr_' . $padding_right : '';
                $classes .= $layout['padding_bottom'] != 0 ? ' tb_pb_' . $layout['padding_bottom'] : '';
                $classes .= $padding_left != 0 ? ' tb_pl_' . $padding_left : '';
            }
        }

        if (!empty($layout['extra_class'])) {
            $classes .= ' ' . trim($layout['extra_class']);
        }

        return $classes;
    }

    public function buildRowClasses(array $settings)
    {
        if (!isset($settings['layout'])) {
            return '';
        }

        $layout = $settings['layout'];

        $classes = 'row';

        if (empty($layout['separate_columns'])) {
            if(isset($layout['columns_gutter']) && strlen($layout['columns_gutter']) && (int) $layout['columns_gutter'] >= 0) {
                $classes .= $layout['columns_gutter'] > 30 ?
                    ' tb_gut_xs_30 tb_gut_sm_30 tb_gut_md_' . $layout['columns_gutter'] . ' tb_gut_lg_' . $layout['columns_gutter'] :
                    ' tb_gut_xs_'  . $layout['columns_gutter'] . ' tb_gut_sm_'  . $layout['columns_gutter'] . ' tb_gut_md_' . $layout['columns_gutter'] . ' tb_gut_lg_' . $layout['columns_gutter'];
            }
            else {
                $classes .= ' tb_gut_xs_30 tb_gut_sm_30 tb_gut_md_30 tb_gut_lg_30';
            }
        } else {
            $classes .= ' tb_separate_columns';
            $classes .= ' tb_ip_'  . $layout['inner_padding'];
        }

        if (empty($layout['columns_rtl_mode'])) {
            $classes .= ' tb_no_rtl_columns';
        }

        return $classes;

    }

    public function buildColumnClasses(array $column_settings, array $row_settings)
    {
        if (!isset($column_settings['layout']) || !isset($row_settings['layout'])) {
            return '';
        }

        $layout     = $column_settings['layout'];
        $row_layout = $row_settings['layout'];
        $lang_dir = $this->getThemeData()->language_direction;

        $padding_left  = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_right'] : $layout['padding_left'];
        $padding_right = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_left']  : $layout['padding_right'];

        $proportion_xs = explode('_', $column_settings['viewport']['xs']['layout']['grid_proportion']);
        $proportion_sm = explode('_', $column_settings['viewport']['sm']['layout']['grid_proportion']);
        $proportion_md = explode('_', $column_settings['viewport']['md']['layout']['grid_proportion']);
        $proportion_lg = explode('_', $column_settings['grid_proportion']);

        $order_xs = $column_settings['viewport']['xs']['layout']['column_order'];
        $order_sm = $column_settings['viewport']['sm']['layout']['column_order'];
        $order_md = $column_settings['viewport']['md']['layout']['column_order'];

        $grid_xs = !in_array($proportion_xs[0], array('auto', 'fill', 'none')) ? 12 * $proportion_xs[0] / $proportion_xs[1] : (string) $proportion_xs[0];
        $grid_sm = !in_array($proportion_sm[0], array('auto', 'fill', 'none')) ? 12 * $proportion_sm[0] / $proportion_sm[1] : (string) $proportion_sm[0];
        $grid_md = !in_array($proportion_md[0], array('auto', 'fill', 'none')) ? 12 * $proportion_md[0] / $proportion_md[1] : (string) $proportion_md[0];
        $grid_lg = !in_array($proportion_lg[0], array('auto', 'fill', 'none')) ? 12 * $proportion_lg[0] / $proportion_lg[1] : (string) $proportion_lg[0];

        $classes  = '';
        $classes .= is_int($grid_xs) ? ' col-xs-' . $grid_xs : ' col-xs-' . (is_string($grid_xs) ? $grid_xs : $proportion_xs[0] . '-' . $proportion_xs[1]);
        $classes .= is_int($grid_sm) ? ' col-sm-' . $grid_sm : ' col-sm-' . (is_string($grid_sm) ? $grid_sm : $proportion_sm[0] . '-' . $proportion_sm[1]);
        $classes .= is_int($grid_md) ? ' col-md-' . $grid_md : ' col-md-' . (is_string($grid_md) ? $grid_md : $proportion_md[0] . '-' . $proportion_md[1]);
        $classes .= is_int($grid_lg) ? ' col-lg-' . $grid_lg : ' col-lg-' . (is_string($grid_lg) ? $grid_lg : $proportion_lg[0] . '-' . $proportion_lg[1]);
        $classes .= $layout['align'] != 'start' ? ' col-align-'  . $layout['align'] : '';
        $classes .= ' col-valign-' . $layout['valign'];
        $classes .= $order_xs != 'default' ?  ' pos-xs-' . $order_xs : '';
        $classes .= $order_sm != 'default' ?  ' pos-sm-' . $order_sm : '';
        $classes .= $order_md != 'default' ?  ' pos-md-' . $order_md : '';

        if (!$row_layout['separate_columns'] || !$layout['inherit_padding']) {
            $classes .= ' tb_pt_' . $layout['padding_top']   ;
            $classes .= ' tb_pr_' . $padding_right           ;
            $classes .= ' tb_pb_' . $layout['padding_bottom'];
            $classes .= ' tb_pl_' . $padding_left            ;
        }

        $classes .= !empty($layout['extra_class']) ? ' ' . $layout['extra_class'] : '';

        return $classes;
    }

}