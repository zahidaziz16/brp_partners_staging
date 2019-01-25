<?php

class Theme_Admin_LayoutBuilderController extends TB_AdminController
{
    /**
     * @var TB_WidgetManager
     */
    protected $widgetManager;

    /**
     * @var Theme_Admin_LayoutBuilderModel
     */
    protected $layoutBuilderModel;

    protected $section_map = array(
        'intro' => array(
            'ajax'     => true,
            'group'    => 'builder_section',
            'template' => 'theme_builder_area'
        ),
        'content' => array(
            'ajax'     => true,
            'group'    => 'builder_section',
            'template' => 'theme_builder_area'
        ),
        'header' => array(
            'ajax'     => true,
            'group'    => 'builder_section',
            'template' => 'theme_builder_area'
        ),
        'footer' => array(
            'ajax'     => true,
            'group'    => 'builder_section',
            'template' => 'theme_builder_area'
        ),
        'column_left' => array(
            'ajax'     => true,
            'group'    => 'builder_section',
            'template' => 'theme_builder_area'
        ),
        'column_right' => array(
            'ajax'     => true,
            'group'    => 'builder_section',
            'template' => 'theme_builder_area'
        )
    );

    public function init()
    {
        $this->widgetManager = $this->engine->getWidgetManager();
        $this->layoutBuilderModel = $this->getModel('layoutBuilder');
    }

    public function index()
    {
        $area_name = 'content';
        $tabsVal = TB_Utils::getTbAppValue('tbLayoutBuilderTabs');
        if (isset($tabsVal['area_name']) && $this->isAreaValid($tabsVal['area_name'])) {
            $area_name = $tabsVal['area_name'];
        }

        if ($area_name == 'content') {
            $area_type = 'home';
            $area_id   = 'home';
        } else {
            $area_type = 'global';
            $area_id   = 'global';
        }

        $this->section_map[$area_name]['ajax'] = false;

        $favourite_widgets = array();
        foreach ((array) $this->engine->getSettingsModel('favourites', 0)->getScopeSettings('widgets') as $widget_id => $widget_settings) {
            $favourite_widgets[] = $this->widgetManager->createWidgetFromId($widget_id, $widget_settings);
        }

        array_filter($favourite_widgets);

        $this->data['widgets']             = $this->widgetManager->getWidgetsByArea('content');
        $this->data['favourite_widgets']   = $favourite_widgets;
        $this->data['system_widgets_html'] = $this->getSystemWidgetsHtml($area_name, $area_type, $area_id);
        $this->data['area_name']           = $area_name;
        $this->data['area_type']           = $area_type;
        $this->data['area_id']             = $area_id;

        $this->renderTemplate('theme_builder');
    }

    protected function renderBuilderSectionGroup($section)
    {
        $args = $this->getAreaArgs();

        if (null === $args) {
            if ($section == 'content') {
                $this->populateAreaBuilderData($section, 'home', 'home');
            } else {
                $this->populateAreaBuilderData($section, 'global', 'global');
            }
        } else {
            $this->populateAreaBuilderData($args[0], $args[1], $args[2]);
        }
    }

    public function areaBuilder()
    {
        $args = $this->getAreaArgs();
        if (null === $args) {
            $this->sendJsonError('Arguments error');
        } else {
            list ($area_name, $area_type, $area_id) = $args;
            $this->populateAreaBuilderData($area_name, $area_type, $area_id);

            $this->renderTemplate('theme_builder_area');
        }
    }

    protected function populateAreaBuilderData($area_name, $area_type, $area_id)
    {
        $widgets = $this->widgetManager->getWidgetsByArea($area_name);
        $area_widget_classes = array();

        foreach ($widgets as $widget) {
            $area_widget_classes[] = $widget->getClassName();
        }

        $this->data['area_widget_classes'] = json_encode($area_widget_classes);
        $this->data['area_templates']      = json_encode($this->getAreaTemplates($area_name));
        $this->data['area_name']           = $area_name;
        $this->data['area_type']           = $area_type;
        $this->data['area_id']             = $area_id;
        $this->data['can_add_rows']        = $this->validate();

        $slot_prefix = false;
        $inherit_msg = '';
        $override_msg = '';

        $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;
        $area = $this->widgetManager->getAreaBuilder($area_name . '_' . $area_key);
        $inherit_key = '';
        $can_delete = !empty($area);

        if (empty($area) && !($area_name == 'content' && $area_key == 'home')) {
            try {
                $params = $this->layoutBuilderModel->determineAreaParams($area_name, $area_type, $area_id, 'builder');
            } catch (Exception $e) {
                $this->sendJsonError($e->getMessage());
                return;
            }

            list($slot_prefix, $area_key) = $params;
            if (false !== $area_key) {
                $area = $this->widgetManager->getAreaBuilder($area_name . '_' . $area_key);
                $inherit_msg = $this->layoutBuilderModel->buildInheritInformationMessage($area_key, $area_type);
                $inherit_key = $area_key;
            } else {
                $inherit_msg = true;
            }
        } else {
            if (empty($area) && $area_name == 'content' && $area_key == 'home') {
                $inherit_msg = true;
            } else {
                $override_msg = $this->layoutBuilderModel->buildOverrideInformationMessage($area_name, $area_type, $area_id, 'builder');
            }
        }

        $this->engine->fbLog('area_type: ' . $area_type . ' | area_key:  ' . $area_key . ' | area_name: ' . $area_name . ' | area_id:    ' . $area_id . ' | slot_prefix: ' . $slot_prefix);

        list($area_settings, $rows_html) = $this->renderArea($area_name, $area, $slot_prefix);

        /** @var Theme_Admin_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');
        /** @var Theme_Admin_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');

        $this->data['area_settings_encoded'] = base64_encode(serialize($area_settings));
        $this->data['area_empty']            = empty($area);
        $this->data['can_delete']            = $can_delete && !empty($area);
        $this->data['rows_html']             = $rows_html;
        $this->data['inherit_msg']           = $inherit_msg;
        $this->data['inherit_key']           = $inherit_key;
        $this->data['override_msg']          = $override_msg;
        $this->data['category_levels']       = $categoryModel->getCategoryLevels();
        $this->data['store_has_categories']  = $categoryModel->storeHasCategories();
        $this->data['pages']                 = $defaultModel->getInformationPages();
        $this->data['layouts']               = $this->layoutBuilderModel->getLayoutsExcludingRoute(array('common/home', 'product/category'));
        $this->data['modified']              = $this->layoutBuilderModel->buildModifiedMenu($area_name, 'builder');
    }

    protected function renderArea($area_name, $area, $slot_prefix)
    {
        if (!empty($area)) {
            $widgets_config = $this->layoutBuilderModel->getAreaSystemSettings($area_name, $this->data['area_type'], $this->data['area_id']);
            $widget_system_slots = array_column($widgets_config['widgets'], 'slot');
            foreach($area['rows'] as $row_key => &$row) {
                foreach ($row['columns'] as $column_key => &$column) {
                    // ToDo compatability hack to remove (Pavilion  < 2.0 does not have column id)
                    if (!isset($column['id'])) {
                        $column['id'] = TB_Utils::genRandomString();
                    }
                    if (!isset($column['settings'])) {
                        $column['settings'] = array();
                    }
                    if (!empty($column['widgets'])) {
                        foreach ($column['widgets'] as $key => $value) {
                            $widget = $this->widgetManager->createWidgetFromId($value['id'], $value['settings']);

                            if (null === $widget) {
                                unset($column['widgets'][$key]);

                                continue;
                            }

                            if ($widget instanceof Theme_SystemWidget && !in_array($widget->getSlotName(), $widget_system_slots)) {
                                unset($column['widgets'][$key]);
                                continue;
                            }

                            if (false !== $slot_prefix && $widget instanceof Theme_SystemWidget) {
                                $widget->setSlotPrefix($slot_prefix);
                            }

                            if (($widget instanceof Theme_GroupWidget || $widget instanceof Theme_BlockGroupWidget) && isset($value['subwidgets'])) {
                                foreach ($value['subwidgets'] as $subwidget) {
                                    $instance = $this->widgetManager->createWidgetFromId($subwidget['id'], $subwidget['settings']);
                                    if (null !== $instance) {
                                        $widget->addSubWidget($instance);
                                    }
                                }

                                if (!empty($value['subwidget_map']) && !empty($value['section_keys'])) {
                                    $widget->setSubwidgetMap($value['subwidget_map']);
                                    $widget->setSectionsKeys($value['section_keys']);
                                }
                            }

                            $column['widgets'][$key] = $widget;
                        }
                        if (empty($column['widgets'])) {
                            unset($row['columns'][$column_key]);
                        }
                    }
                }

                if (empty($row['columns']) && $row['columns_number'] > 0) {
                    // Remove the row only if all of its system widgets were unset. This prevents removing empty saved rows.
                    unset($area['rows'][$row_key]);
                }
            }
        }

        $area_settings = isset($area['settings']) ? $area['settings'] : array();
        $this->layoutBuilderModel->filterAreaSettings($area_settings, 'area_' . $area_name);

        $rows_html = '';
        if (!empty($area['rows'])) {
            $i = 0;
            foreach ($area['rows'] as &$row) {

                $this->layoutBuilderModel->filterRowSettings($row['settings']);
                $this->layoutBuilderModel->cleanSettingsDataBeforePersist($row['settings']);

                $column_settings = array();

                foreach ($row['columns'] as &$row_column) {
                    $row_column['settings']['grid_proportion'] = $row_column['grid_proportion'];
                    $this->layoutBuilderModel->filterColumnSettings($row_column['settings'], $row['settings']);
                    $this->layoutBuilderModel->cleanSettingsDataBeforePersist($row_column['settings']);
                    $column_settings[$row_column['id']] = $row_column['settings'];

                    if (!empty($row_column['widgets'])) {
                        /** @var TB_Widget $widget */
                        foreach ($row_column['widgets'] as $key => $widget) {
                            $settings = $widget->getSettings();
                            $this->widgetManager->filterWidgetInstance($widget, $settings);
                            $widget->setSettings($settings);
                        }
                    }
                }

                $data = array(
                    'row'                     => $row,
                    'key'                     => ++$i,
                    'column_settings_encoded' => base64_encode(gzcompress(serialize($column_settings), 9)),
                    'row_settings_encoded'    => base64_encode(gzcompress(serialize($row['settings']), 9))
                );
                $rows_html .= $this->fetchTemplate('theme_builder_row', array_merge($this->data, $data));
            }
        }

        return array($area_settings, $rows_html);
    }

    public function modifiedMenu()
    {
        $area_name = null;
        if (isset($this->request->get['area_name'])) {
            $name = (string) $this->request->get['area_name'];
            if ($this->isAreaValid($name)) {
                $area_name = $name;
            }
        }

        $record_type = (string) $this->request->get['record_type'];

        if (null == $area_name || !in_array($record_type, array('style', 'builder'))) {
            return $this->sendJsonError('Not existing area');
        }

        $this->data['modified'] = $this->layoutBuilderModel->buildModifiedMenu($area_name, $record_type);

        $this->renderTemplate('theme_builder_modified');
    }

    public function removeSettings()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $args = $this->getAreaArgs();
        $record_type = (string) $this->request->get['record_type'];

        if (null === $args || !in_array($record_type, array('style', 'builder'))) {
            return $this->sendJsonError('Invalid arguments');
        }

        list ($area_name, $area_type, $area_id) = $args;
        $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;

        $current_area_id   = (string) $this->request->get['current_area_id'];
        $current_area_type = (string) $this->request->get['current_area_type'];

        $current_area_key = '';
        if (!empty($current_area_type)) {
            // Can be empty on the initial tab load, when the combobox has not been changed and there is no currentItem
            $current_area_key = strpos($current_area_id, $current_area_type) !== 0 ? $current_area_type . '_' . $current_area_id : $current_area_id;
        }

        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilder */
        $layoutBuilder = $this->layoutBuilderModel;
        $reload = 0;

        if (!empty($current_area_key) && $area_key != $current_area_key) {
            try {
                // Check current inherit key before removal
                $params = $layoutBuilder->determineAreaParams($area_name, $current_area_type, $current_area_id, $record_type);
                list(, $current_area_key) = $params;
            } catch (Exception $e) {
                return $this->sendJsonError($e->getMessage());
            }

            $this->widgetManager->removeWidgetArea($area_name . '_' . $area_key, $record_type);

            try {
                // Check current inherit key after removal
                $params = $layoutBuilder->determineAreaParams($area_name, $current_area_type, $current_area_id, $record_type);
                list(, $new_area_key) = $params;
            } catch (Exception $e) {
                return $this->sendJsonError($e->getMessage());
            }

            $reload = $current_area_key != $new_area_key;
        } else {
            $this->widgetManager->removeWidgetArea($area_name . '_' . $area_key, $record_type);
        }

        $this->engine->wipeAllCache('*' . $area_name . '_' . str_replace('/', '_', $area_key) . '*');
        $this->engine->wipeVarsCache('*area_keys.' . $this->context->getStoreId() . '*');

        return $this->sendJsonSuccess('Settings removed for key ' . $area_key, array('reload' => $reload ? 1 : 0));
    }

    public function systemBlocks()
    {
        $args = $this->getAreaArgs();
        if (null === $args) {
            $this->sendJsonError('Invalid arguments');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $this->setOutput($this->getSystemWidgetsHtml($area_name, $area_type, $area_id));
    }

    protected function getSystemWidgetsHtml($area_name, $area_type, $area_id)
    {
        $widgets_config = $this->layoutBuilderModel->getAreaSystemSettings($area_name, $area_type, $area_id);
        if (!empty($widgets_config)) {
            $widgets = $this->widgetManager->createSystemWidgets($widgets_config);
            $widgets_html = $this->fetchTemplate('theme_builder_system_widgets', array('widgets' => $widgets));
        } else {
            $widgets_html = '<p>There are no system blocks for the current area.</p>';
        }

        return $widgets_html;
    }

    public function copyArea()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $args = $this->getAreaArgs();
        if (null === $args || !isset($this->request->get['new_store_id'])) {
            return $this->sendJsonError('Arguments error');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $area_key = $area_name . '_' . (strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id);
        $area = $this->widgetManager->getAreaBuilder($area_key);

        if (empty($area)) {
            return $this->sendJsonError('Area not found');
        }

        $store_id = (int) $this->request->get['new_store_id'];
        $this->engine->getBuilderSettingsModel()->setAndPersistScopeSettings($area_key, $area, $store_id);

        return $this->sendJsonSuccess('The area has been copied');
    }

    public function saveAreaTemplate()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $args = $this->getAreaArgs();
        if (null === $args || empty($this->request->post['export_name'])) {
            return $this->sendJsonError('Arguments error');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $area_key = $area_name . '_' . (strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id);
        $area = $this->widgetManager->getAreaBuilder($area_key);

        if (empty($area)) {
            return $this->sendJsonError('Area not found');
        }

        /** @var Theme_Admin_ExportModel $exportModel */
        $exportModel = $this->getModel('export');

        foreach ($area['rows'] as &$area_row) {
            foreach ($area_row['columns'] as &$area_column) {
                if (!empty($area_column['widgets'])) {
                    foreach ($area_column['widgets'] as &$widget) {
                        $exportModel->replaceWidgetImages($widget);
                    }
                }
            }
        }

        $name = (string) $this->request->post['export_name'];
        $template_id = TB_Utils::slugify($name) . '-' . TB_Utils::genRandomString();

        $data = array(
            'id'       => $template_id,
            'name'     => $name,
            'image'    => html_entity_decode($this->getArrayKey('export_image', $this->request->post, ''), ENT_COMPAT, 'UTF-8'),
            'area_key' => $area_key,
            'area'     => $area
        );

        $template_key  = $area_name . '_' . $template_id;
        $group         = !empty($this->request->post['is_theme']) ? 'template_' . $this->engine->getThemeId() : 'template';
        $store_id      = !empty($this->request->post['is_theme']) ? $this->context->getStoreId() : 0;
        $settingsModel =  $this->engine->getSettingsModel($group, $store_id);

        $settingsModel->setAndPersistScopeSettings($template_key, $data);

        return $this->sendJsonSuccess('The area has been exported', array('area_templates' => $this->getAreaTemplates($area_name)));
    }

    public function serializeArea()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $args = $this->getAreaArgs();
        if (null === $args) {
            return $this->sendJsonError('Arguments error');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $area_key = $area_name . '_' . (strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id);
        $area = $this->widgetManager->getAreaBuilder($area_key);

        if (empty($area)) {
            return $this->sendJsonError('Area not found');
        }

        /** @var Theme_Admin_ExportModel $exportModel */
        $exportModel = $this->getModel('export');

        foreach ($area['rows'] as &$area_row) {
            foreach ($area_row['columns'] as &$area_column) {
                if (!empty($area_column['widgets'])) {
                    foreach ($area_column['widgets'] as &$widget) {
                        $exportModel->replaceWidgetImages($widget);
                    }
                }
            }
        }

        return $this->sendJsonSuccess('The area has been exported', array('area' => $area));
    }

    public function loadAreaExport()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $args = $this->getAreaArgs();
        if (null === $args || empty($this->request->post['area_data'])) {
            return $this->sendJsonError('Arguments error');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $area_key = $area_name . '_' . (strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id);
        $area = json_decode(html_entity_decode((string) $this->request->post['area_data'], ENT_COMPAT, 'UTF-8'), true);

        if (empty($area)) {
            return $this->sendJsonError('Area not found');
        }

        /** @var Theme_Admin_ImportModel $importModel */
        $importModel = $this->getModel('import');
        $importModel->modifyBuilderSettings($area, $this->engine->getEnabledLanguages());

        $this->engine->getBuilderSettingsModel()->setAndPersistScopeSettings($area_key, $area);

        return $this->sendJsonSuccess('The area has been imported');
    }

    protected function getAreaTemplates($area_name)
    {
        $area_templates = array();

        foreach ($this->engine->getDbSettingsHelper()->getKeysBeginWith($area_name . '_', 0, 'template_' . $this->engine->getThemeId()) as $key => $value) {
            unset($value['area']);
            if (!empty($value['image'])) {
                $value['image'] = str_replace(array('{{theme_url}}', '{{engine_url}}'), array($this->context->getThemeRootUrl(), $this->context->getEngineRootUrl()), $value['image']);
            }
            $value['is_theme'] = 1;
            $area_templates['1_' . $key] = $value;
        }

        if ($area_name == 'column_left' || $area_name == 'column_right') {
            $area_name = array('column_left_', 'column_right_');
        } else
            $area_name .= '_';

        foreach ($this->engine->getDbSettingsHelper()->getKeysBeginWith($area_name, 0, 'template') as $key => $value) {
            unset($value['area']);
            $value['is_theme'] = 0;
            $area_templates['0_' . $key] = $value;
        }

        krsort($area_templates);

        return array_values($area_templates);
    }

    public function removeAreaTemplate()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $area_name = null;
        if (isset($this->request->post['area_name'])) {
            $name = (string) $this->request->post['area_name'];
            if ($this->isAreaValid($name)) {
                $area_name = $name;
            }
        }

        if (!$area_name) {
            return $this->sendJsonError('Invalid area');
        }

        if (!$template_id = $this->getArrayKey('template_id', $this->request->post, null)) {
            return $this->sendJsonError('Invalid template');
        }

        $template_key  = $area_name . '_' . $template_id;
        $group         = !empty($this->request->post['is_theme']) ? 'template_' . $this->engine->getThemeId() : 'template';
        $store_id      = !empty($this->request->post['is_theme']) ? $this->context->getStoreId() : 0;
        $settingsModel =  $this->engine->getSettingsModel($group, $store_id);

        if (!$settingsModel->getScopeSettings($template_key)) {
            return $this->sendJsonError('Non existent template');
        }

        $settingsModel->deleteScopeSettings($template_key);

        return $this->sendJsonSuccess('Template has been removed');
    }

    public function loadAreaTemplate()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $args = $this->getAreaArgs();
        if (null === $args) {
            return $this->sendJsonError('Arguments error');
        }

        list ($area_name, $area_type, $area_id) = $args;
        $area_key = $area_name . '_' . (strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id);

        if (!$template_id = $this->getArrayKey('template_id', $this->request->get, null)) {
            return $this->sendJsonError('Invalid template');
        }

        $key   = $area_name . '_' . $template_id;
        $group = !empty($this->request->get['is_theme']) ? 'template_' . $this->engine->getThemeId() : 'template';

        if (!$template = $this->engine->getSettingsModel($group, 0)->getScopeSettings($key)) {
            return $this->sendJsonError('Non existent template: ' . $key);
        }

        $slot_prefix = false;
        if (!($area_name == 'content' && $area_key == 'home')) {
            try {
                $params = $this->layoutBuilderModel->determineAreaParams($area_name, $area_type, $area_id, 'builder');
            } catch (Exception $e) {
                return $this->sendJsonError($e->getMessage());
            }

            list($slot_prefix) = $params;
        }

        $this->data['area_name'] = $area_name;
        $this->data['area_type'] = $area_type;
        $this->data['area_id']   = $area_id;

        /** @var Theme_Admin_ImportModel $importModel */
        $importModel = $this->getModel('import');
        $importModel->modifyBuilderSettings($template['area'], $this->engine->getEnabledLanguages());

        list(, $rows_html) = $this->renderArea($area_name, $template['area'], $slot_prefix);

        return $this->sendJsonSuccess('Template has been loaded', array('rows_html' => $rows_html));
    }

    public function saveRows()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('Not a valid action');
        }

        $area_name = (string) $this->request->post['area_name'];
        if (!$this->isAreaValid($area_name)) {
            return $this->sendJsonError('Not a valid area');
        }

        $area_type = "page";
        if (isset($this->request->post['area_type'])) {
            $type = (string) $this->request->post['area_type'];
            if (in_array($type, array('global', 'home', 'page', 'category', 'layout', 'system', 'product', 'quickview'))) {
                $area_type = $type;
            }
        }

        $area_id = 0;
        if (isset($this->request->post['area_id'])) {
            $area_id = (string) $this->request->post['area_id'];
        }

        $rows = array();
        if (!empty($this->request->post['rows'])) {
            $rows = (array) $this->request->post['rows'];
        }

        $settings = '';
        if (!empty($this->request->post['settings'])) {
            $settings = unserialize(base64_decode((string) $this->request->post['settings']));
        }

        $area_key = $area_name . '_' . (strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id);

        $wipe_cache_widget_ids = array();
        foreach ($rows as &$row) {
            $row['settings'] = unserialize(gzuncompress(base64_decode($row['settings'])));
            $column_settings = array();
            if (!empty($row['column_settings'])) {
                $column_settings = unserialize(gzuncompress(base64_decode($row['column_settings'])));
            }
            unset($row['column_settings']);

            if (!isset($row['columns']) || empty($row['columns'])) {
                continue;
            }

            $column_ids = array();

            foreach ($row['columns'] as $row_column) {
                $column_ids[] = $row_column['id'];
            }

            if (!empty($column_ids)) {

                $current_ids = array_keys($column_settings);
                $not_assigned_ids = array_diff($current_ids, $column_ids);

                foreach (array_diff($column_ids, $current_ids) as $new_id) {
                    if ($old_id = array_shift($not_assigned_ids)) {
                        array_replace_key($column_settings, $old_id, $new_id);
                    } else {
                        $column_settings[$new_id] = array();
                    }
                }

                foreach ($not_assigned_ids as $old_id) {
                    unset($column_settings[$old_id]);
                }
            }

            foreach ($row['columns'] as &$column) {

                if (isset($column_settings[$column['id']])) {
                    $column['settings'] = $column_settings[$column['id']];
                }

                if (!isset($column['widgets']) || empty($column['widgets'])) {
                    continue;
                }

                foreach ($column['widgets'] as &$widget) {
                    if ($widget['is_dirty']) {
                        $wipe_cache_widget_ids[] = $widget['id'];
                    }
                    unset($widget['is_dirty']);
                    $widget['settings'] = unserialize(gzuncompress(base64_decode($widget['settings'])));
                    if (empty($widget['subwidgets'])) {
                        continue;
                    }

                    foreach ($widget['subwidgets'] as &$subwidget) {
                        if ($subwidget['is_dirty']) {
                            $wipe_cache_widget_ids[] = $subwidget['id'];
                        }
                        unset($subwidget['is_dirty']);
                        $subwidget['settings'] = unserialize(gzuncompress(base64_decode($subwidget['settings'])));
                    }
                }
            }
        }

        if (!empty($wipe_cache_widget_ids)) {
            $this->engine->wipeVarsCache('*' . '{' . implode(',', $wipe_cache_widget_ids) . '}*', true);
        }

        $this->widgetManager->persistWidgets(array('rows' => $rows, 'settings' => $settings), $area_key);
        $this->engine->wipeAllCache('*' . str_replace('/', '_', $area_key) . '*');
        $this->engine->wipeVarsCache('*area_keys.' . $this->context->getStoreId() . '*');

        $data = array(
            'override_msg' => $this->layoutBuilderModel->buildOverrideInformationMessage($area_name, $area_type, $area_id, 'builder')
        );

        if ($url = $this->getLivePreviewUrl()) {
            $data['livePreviewUrl'] = $url;
        }

        return $this->sendJsonSuccess('The data has been saved', $data);
    }

    public function getNewWidgetRow()
    {
        $args = $this->getAreaArgs();
        if (null === $args) {
            return $this->sendJsonError('Invalid arguments');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $area_settings = $this->getAreaSettings($area_name, $area_type, $area_id);

        $settings     = array();
        $theme_colors = array();
        $area_name    = (string) $this->request->get['area_name'];

        if (!empty($this->request->post['theme_colors'])) {
            $theme_colors = json_decode(html_entity_decode((string) $this->request->post['theme_colors'], ENT_COMPAT, 'UTF-8'), true);
            $theme_colors = $theme_colors['colors'];
        }

        $this->layoutBuilderModel->filterRowSettings($settings, 'area_' . $area_name, $area_settings, $theme_colors);
        $this->layoutBuilderModel->cleanSettingsDataBeforePersist($settings);

        $row = array(
            'id'             => TB_Utils::genRandomString(),
            'columns_number' => '1',
            'settings'       => $settings
        );

        $this->data['key']       = 0;
        $this->data['area_name'] = $area_name;
        $this->data['area_type'] = $area_type;
        $this->data['area_id']   = $area_id;
        $this->data['column_id'] = TB_Utils::genRandomString();
        $this->data['row']       = $row;
        $this->data['row_settings_encoded']    = base64_encode(gzcompress(serialize($row['settings']), 9));
        $this->data['column_settings_encoded'] = '';

        $this->renderTemplate('theme_builder_row');
    }

    public function createRowSettingsForm()
    {
        if (!isset($this->request->post['settings']) || !isset($this->request->get['row_id'])) {
            return $this->sendJsonError('Cannot create settings form. Missing parameters.');
        }

        $row_settings = unserialize(gzuncompress(base64_decode((string) $this->request->post['settings'])));
        if (!is_array($row_settings)) {
            return $this->sendJsonError('Cannot create settings form. Invalid settings data.');
        }

        $column_settings = array();
        if (!empty($this->request->post['column_settings'])) {
            $column_settings = unserialize(gzuncompress(base64_decode((string) $this->request->post['column_settings'])));
            if (!is_array($column_settings)) {
                return $this->sendJsonError('Cannot create settings form. Invalid settings data.');
            }
        }

        $column_ids = $this->request->post['column_ids'];

        $args = $this->getAreaArgs();
        if (null === $args) {
            return $this->sendJsonError('Invalid arguments');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $selected_preset_id = "";
        if (!empty($this->request->post['preset_id'])) {
            $selected_preset_id = (string) $this->request->post['preset_id'];
        }

        $apply_preset = false;
        if (isset($this->request->post['apply_preset'])) {
            $apply_preset = (bool) $this->request->post['apply_preset'];
        }

        $row_preset_id = "";
        if (!empty($row_settings['preset_id']) && empty($selected_preset_id)) {
            $row_preset_id = $row_settings['preset_id'];
            $selected_preset_id = $row_preset_id;
        } else
            if ($apply_preset && !empty($selected_preset_id)) {
                $row_preset_id = $selected_preset_id;
            }

        $current_preset = array();
        $preset_options = array();
        foreach (array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues()) as $preset) {
            $preset_options[$preset['id']] = $preset['name'];
            if ($preset['id'] == $selected_preset_id || $preset['id'] == $row_preset_id) {
                $current_preset = $preset;
            }
        }

        if (!empty($row_preset_id) && empty($current_preset)) {
            $row_preset_id = "";
        }

        $preset_box_color_keys = !empty($current_preset) && isset($current_preset['styles']['box']['colors']) ? array_keys($current_preset['styles']['box']['colors']) : array();
        $preset_box_font_keys  = !empty($current_preset) && isset($current_preset['styles']['box']['font']) ? array_keys(reset($current_preset['styles']['box']['font'])) : array();

        if (!empty($current_preset['styles']['box'])) {

            $style_settings = $current_preset['styles']['box'];

            if (!empty($style_settings['font'])) {
                foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                    if (!isset($style_settings['font'][$language_code])) {
                        $style_settings['font'][$language_code] = reset($style_settings['font']);
                    }
                }
            }

            $row_settings = array_replace_recursive($row_settings, $current_preset['styles']['box']);
            $colors = AreaItemData::getRowColors();
            $preset_color_keys = !empty($style_settings['colors']) ? array_keys(array_intersect_key($style_settings['colors'], $colors)) : array();

            if ($preset_color_keys) {
                $preset_box_color_keys = $preset_color_keys;

                foreach (array_keys($colors) as $color_group_key) {
                    if (!in_array($color_group_key, $preset_color_keys) && isset($row_settings['colors'][$color_group_key])) {
                        $colors[$color_group_key] = array_replace_recursive($colors[$color_group_key], $row_settings['colors'][$color_group_key]);
                    }
                }
            }

            foreach ($colors as $color_group_key => &$color_sections) {
                if (isset($style_settings['colors'][$color_group_key])) {
                    $color_sections = array_replace_recursive($color_sections, array_intersect_key($style_settings['colors'][$color_group_key], $color_sections));
                    foreach ($color_sections as $color_section_key => &$color_section_values) {
                        if ($color_section_key != "_label") {
                            $color_section_values['inherit'] = 0;
                        }
                    }
                }
            }

            $row_settings['colors'] = $colors;

            $default_fonts = AreaItemData::getFonts();
            $preset_font_keys = !empty($style_settings['font']) ? array_keys(array_intersect_key(reset($style_settings['font']), $default_fonts)) : array();

            if ($preset_font_keys) {
                $preset_box_font_keys = $preset_font_keys;

                foreach ($row_settings['font'] as &$settings_fonts) {
                    $settings_fonts = array_intersect_key($settings_fonts, $default_fonts);

                    foreach (array_keys($default_fonts) as $font_group_key) {
                        if (!in_array($font_group_key, $preset_font_keys) && isset($settings_fonts[$font_group_key])) {
                            $settings_fonts[$font_group_key] = array_replace_recursive($default_fonts[$font_group_key], $settings_fonts[$font_group_key]);
                        }
                    }
                }
            }
        }

        $area_settings = $this->getAreaSettings($area_name, $area_type, $area_id);
        $theme_colors  = array();

        if (!empty($this->request->post['theme_colors'])) {
            $theme_colors = json_decode(html_entity_decode((string) $this->request->post['theme_colors'], ENT_COMPAT, 'UTF-8'), true);
            $theme_colors = $theme_colors['colors'];
        }

        $this->layoutBuilderModel->filterRowSettings($row_settings, 'area_' . $area_name, $area_settings, $theme_colors);

        if (isset($row_settings['background']['rows']) && !empty($row_settings['background']['rows'])) {
            $this->initStyleBackgroundImages($row_settings['background']['rows']);
        }

        if (!empty($column_ids)) {

            $current_ids = array_keys($column_settings);
            $not_assigned_ids = array_diff($current_ids, $column_ids);

            foreach (array_diff($column_ids, $current_ids) as $new_id) {
                if ($old_id = array_shift($not_assigned_ids)) {
                    array_replace_key($column_settings, $old_id, $new_id);
                } else {
                    $column_settings[$new_id] = array();
                }
            }

            foreach ($not_assigned_ids as $old_id) {
                unset($column_settings[$old_id]);
            }
        }

        $i = 1;

        foreach ($column_settings as $column_id => &$column_settings_item) {

            $column_settings_item['grid_proportion'] = $this->request->post['grid_proportions'][$column_id];
            $this->layoutBuilderModel->filterColumnSettings($column_settings_item, $row_settings);

            $column_settings_item['order'] = $i;
            if (isset($column_settings_item['background']['rows']) && !empty($column_settings_item['background']['rows'])) {
                $this->initStyleBackgroundImages($column_settings_item['background']['rows']);
            }

            foreach ($column_settings_item['viewport'] as $size => $settings) {
                $column_settings_item['layout']['viewport'][$size] = $settings['layout'];
            }

            $data = array_merge($this->data, array(
                'settings'        => $column_settings_item,
                'section'         => 'row_column',
                'section_id'      => 'row_column_' . $column_id,
                'input_property'  => 'widgets_row[columns][' . $column_id . ']'
            ));

            $column_settings_item['html'] = $this->fetchTemplate('theme_builder_row_options_tab', $data);

            $i++;
        }

        $this->data['preset_options']        = $preset_options;
        $this->data['selected_preset_id']    = $selected_preset_id;
        $this->data['row_preset_id']         = $row_preset_id;
        $this->data['preset_box_color_keys'] = $preset_box_color_keys;
        $this->data['preset_box_font_keys']  = $preset_box_font_keys;
        $this->data['settings']              = $row_settings;
        $this->data['column_settings']       = $column_settings;
        $this->data['section']               = 'widgets_row';
        $this->data['section_id']            = 'widgets_row';
        $this->data['input_property']        = 'widgets_row';

        $this->renderTemplate('theme_builder_row_options');
    }

    protected function initStyleBackgroundImages(&$background_rows)
    {
        foreach ($background_rows as &$bg_row) {
            if ($bg_row['background_type'] == 'image') {
                if (!empty($bg_row['image'])  && file_exists(DIR_IMAGE . $bg_row['image'])) {
                    $bg_row['preview'] = $this->engine->getOcToolImage()->resize($bg_row['image'], 100, 100);
                } else {
                    $bg_row['image'] = '';
                    $bg_row['preview'] = $this->getThemeModel()->getNoImage();
                }
            }
        }
    }

    protected function getAreaSettings($area_name, $area_type, $area_id)
    {
        if (!empty($this->request->post['area_settings'])) {
            $area_settings = json_decode(html_entity_decode((string) $this->request->post['area_settings'], ENT_COMPAT, 'UTF-8'), true);
            $area_settings = $area_settings['area'][$area_name];
        } else {
            $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;
            $area_settings = $this->widgetManager->getAreaStyle($area_name . '_' . $area_key);

            if (empty($area_settings)) {
                try {
                    $params = $this->layoutBuilderModel->determineAreaParams($area_name, $area_type, $area_id, 'style');
                } catch (Exception $e) {
                    return $this->sendJsonError($e->getMessage());
                }

                list(, $area_key) = $params;
                $area_settings = $this->widgetManager->getAreaStyle($area_name . '_' . $area_key);
            }

            if (empty($area_settings)) {
                $area_settings = array();
            }
        }

        return $area_settings;
    }

    public function convertRowFormDataToSettings()
    {
        if (empty($this->request->post['settings'])) {
            return $this->sendJsonError('Empty row settings');
        }

        $settings     = json_decode(html_entity_decode((string) $this->request->post['settings'], ENT_COMPAT, 'UTF-8'), true);
        $theme_colors = array();

        if (!empty($this->request->post['theme_colors'])) {
            $theme_colors = json_decode(html_entity_decode((string) $this->request->post['theme_colors'], ENT_COMPAT, 'UTF-8'), true);
            $theme_colors = $theme_colors['colors'];
        }

        $args = $this->getAreaArgs();

        if (null === $args) {
            return $this->sendJsonError('Invalid arguments');
        }

        list ($area_name, $area_type, $area_id) = $args;
        $area_settings   = $this->getAreaSettings($area_name, $area_type, $area_id);

        $column_settings = array();
        if (isset($settings['columns'])) {
            $column_settings = $settings['columns'];
            unset($settings['columns']);
        }

        $this->layoutBuilderModel->filterRowSettings($settings, 'area_' . $area_name, $area_settings, $theme_colors);
        $this->layoutBuilderModel->cleanSettingsDataBeforePersist($settings);

        foreach ($column_settings as &$column) {
            $this->layoutBuilderModel->filterColumnSettings($column, $settings);
            $this->layoutBuilderModel->cleanSettingsDataBeforePersist($column);
        }

        $this->setOutput(json_encode(array(
            'row_settings'    => base64_encode(gzcompress(serialize($settings))),
            'column_settings' => base64_encode(gzcompress(serialize($column_settings)))
        )));
    }

    protected function getAreaArgs()
    {
        $area_name = null;
        if (isset($this->request->request['area_name'])) {
            $name = (string) $this->request->request['area_name'];
            if ($this->isAreaValid($name)) {
                $area_name = $name;
            }
        }

        $area_type = null;
        if (isset($this->request->request['area_type'])) {
            $type = (string) $this->request->request['area_type'];
            if (in_array($type, array('global', 'home', 'page', 'category', 'layout', 'system', 'product', 'quickview'))) {
                $area_type = $type;
            }
        }

        $area_id = null;
        if (isset($this->request->request['area_id'])) {
            $area_id = (string) $this->request->request['area_id'];
        }

        if ($area_name == null || $area_type == null || $area_id == null) {
            return null;
        }

        return array($area_name, $area_type, $area_id);
    }

    protected function isAreaValid($area_name)
    {
        return in_array((string) $area_name, array('header', 'footer', 'content', 'intro', 'column_left', 'column_right'));
    }
}