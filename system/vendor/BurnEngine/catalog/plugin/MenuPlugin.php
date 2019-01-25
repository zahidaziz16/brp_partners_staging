<?php

class Theme_Catalog_MenuPlugin extends TB_ExtensionPlugin
{
    protected $widget_settings;
    protected $widget_id;

    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        $this->bootstrap('common');
        $this->bootstrap('style');

        $themeData->addCallable(array($this, 'getCategorySubMenu'));
        $themeData->addCallable(array($this, 'fetchMenuItems'));

        /*
        if ($themeData['system']['cache_menu']) {
            $cache_key = 'menu_html.' . $this->context->getStoreId() . '.' . (TB_RequestHelper::isRequestHTTPS() ? 'https.': '') . $this->language_code;
            $themeData->menuHTML = $this->engine->getCacheVar($cache_key, array($this, 'fetchMenu'));
        } else {
            $themeData->menuHTML = $this->fetchMenu();
        }
        */
    }

    protected function generateMenuCssClasses($menu_item, $type = '', $has_submenu = false, $return = false)
    {

        $settings         = $menu_item['data']['settings'];
        $menu_classes     = 'tb_link';
        $menu_classes    .= $return != 'labels'
                            && (
                               $has_submenu
                               && (
                                   $this->widget_settings['orientation'] == 'horizontal'
                                   ||
                                   $this->widget_settings['orientation'] == 'vertical'
                                   && (
                                       ($menu_item['level'] == 1 && $this->widget_settings['level_2'] == 'dropdown')
                                       || ($menu_item['level'] == 2 && $this->widget_settings['level_2'] == 'dropdown')
                                       || ($menu_item['level'] == 2 && $this->widget_settings['level_3'] == 'dropdown')
                                   )
                               )
                               ||
                               !empty($settings['is_megamenu'])
                               && (
                                   $type == 'all_categories'
                                   ||
                                   $type == 'category'
                               )
                            )
                            ? ' dropdown' : '';

        $menu_classes    .= !empty($settings['is_megamenu'])
                            || !empty($settings['tabbed_submenus'])
                            ? ' tb_megamenu' : '';
        $menu_classes    .= !empty($settings['is_megamenu'])
                            && !empty($settings['dropdown_width'])
                            ? ' tb_auto_width' : '';
        $menu_classes    .= $menu_item['level'] == 1
                            && $this->widget_settings['level_1_style'] == 'hidden'
                            ? ' tb_hidden_menu' : '';
        $menu_classes    .= !empty($settings['class']) ? ' ' . $settings['class'] : '';
        $label_classes    = '';
        $label_classes   .= $this->widget_settings['orientation'] == 'vertical'
                            && $menu_item['level'] == 1
                            && $this->widget_settings['level_1_style'] != 'list'
                            && $this->widget_settings['level_1_style'] != 'none'
                            ? ' ' . $this->widget_settings['level_1_style'] : '';
        $label_classes   .= $this->widget_settings['orientation'] == 'vertical'
                            && $menu_item['level'] == 2
                            && $this->widget_settings['level_2'] != 'dropdown'
                            && $this->widget_settings['level_2_style'] != 'list'
                            && $this->widget_settings['level_2_style'] != 'none'
                            ? ' ' . $this->widget_settings['level_2_style'] : '';
        $label_classes   .= $this->widget_settings['orientation'] == 'vertical'
                            && $menu_item['level'] == 3
                            && $this->widget_settings['level_2'] != 'dropdown'
                            && $this->widget_settings['level_3'] != 'dropdown'
                            && $this->widget_settings['level_3_style'] != 'list'
                            && $this->widget_settings['level_3_style'] != 'none'
                            ? ' ' . $this->widget_settings['level_3_style'] : '';
        $submenu_classes  = '';
        $submenu_classes .= empty($settings['tabbed_submenus'])
                            &&
                            $this->widget_settings['orientation'] == 'horizontal'
                            ||
                            $this->widget_settings['orientation'] == 'vertical'
                            && !(
                                $menu_item['level'] == 1
                                && $this->widget_settings['level_1_style'] == 'hidden'
                            )
                            && (
                                ($menu_item['level'] == 1 && $this->widget_settings['level_2'] == 'dropdown')
                                || ($menu_item['level'] == 2 && $this->widget_settings['level_3'] == 'dropdown')
                            )
                            ? ' dropdown-menu' : '';
        $submenu_classes .= $menu_item['level'] == 1 && $this->widget_settings['level_2_style'] == 'list'
                            || $menu_item['level'] == 2 && $this->widget_settings['level_3_style'] == 'list'
                            ? ' tb_list_1' : '';

        return array($menu_classes, $label_classes, $submenu_classes);
    }

    protected function generateMenuIcon($menu_item)
    {
        $settings  = $menu_item['data']['settings'];
        $menu_icon = '';

        if (!isset($settings['menu_icon_type'])) {
            return '';
        }

        if ($settings['menu_icon_type'] == 'symbol' && !empty($settings['menu_icon'])) {
            $menu_icon_styles  = '';
            $menu_icon_styles .= (int) $settings['menu_icon_size'] != 100 ? 'font-size: ' . $settings['menu_icon_size'] . '%;' : '';
            $menu_icon_styles .= !$settings['menu_icon_color_inherit'] ? 'color: ' . $settings['menu_icon_color'] . ';' : '';
            $menu_icon_styles .= isset($settings['menu_icon_spacing']) && strlen($settings['menu_icon_spacing']) ? 'margin: 0 ' . $settings['menu_icon_spacing'] . 'em;' : '';
            $menu_icon_styles  = $menu_icon_styles ? ' style="' . $menu_icon_styles . '"' : '';
            $menu_icon         = $settings['menu_icon'] ? '<span class="tb_icon"><i class="' . $settings['menu_icon'] . '"' . $menu_icon_styles . '></i></span>' : '';
        }
        if ($settings['menu_icon_type'] == 'image' && !empty($settings['menu_icon_image']) && is_file(DIR_IMAGE . $settings['menu_icon_image'])) {
            //$img_size = getimagesize(DIR_IMAGE . $settings['menu_icon_image']);
            $menu_icon_styles  = '';
            $menu_icon_styles .= !empty($settings['menu_icon_spacing']) ? 'margin: 0 ' . $settings['menu_icon_spacing'] . 'em;' : '';
            $menu_icon_styles  = $menu_icon_styles ? ' style="' . $menu_icon_styles . '"' : '';
            $menu_icon         = '<img class="tb_icon" src="' . $this->engine->getContext()->getImageUrl() . $settings['menu_icon_image'] . '" alt=""' . $menu_icon_styles . ' />';
        }

        return $menu_icon;
    }

    public function generateMenuAccentLabel($menu_item)
    {
        $settings             = $menu_item['data']['settings'];
        $accent_label         = '';
        $accent_label_styles  = '';

        if (empty($settings['accent_text'])) {
            return '';
        }

        $accent_label_styles .= 'color: ' . $settings['accent_color'] . ';';
        $accent_label_styles .= 'background-color: ' . $settings['accent_bg'] . ';';
        $accent_label_styles .= !empty($settings['accent_margin']) ? 'margin: ' . $settings['accent_margin'] . ';' : '';
        $accent_label        .= '<span class="tb_accent_label" style="' . $accent_label_styles . '">' . $settings['accent_text'] . '</span>';

        return $accent_label;
    }

    public function fetchMenu()
    {
        $store = $this->getSetting('store');

        if (!isset($store['menu']['tree'][$this->language_code]) || empty($store['menu']['tree'][$this->language_code])) {
            if ($this->engine->getConfig('menu_fallback_first_language')) {
                $menu_tree = reset($store['menu']['tree']);
            } else {
                return '';
            }
        } else {
            $menu_tree = $store['menu']['tree'][$this->language_code];
        }

        /** @var Theme_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');

        $menuHTML = $this->fetchMenuItems($menu_tree);
        $menu = $this->extension->fetchTemplate('menu/menu', array(
                'informations' => $defaultModel->getInformationPages(),
                'menu_items'   => $menuHTML
            ) + $this->engine->loadOcTranslation('common/footer')
        );

        return $menu;
    }

    public function setWidgetSettings($widget_settings, $widget_id)
    {
        $this->widget_settings = $widget_settings;
        $this->widget_id = $widget_id;
    }

    public function fetchMenuItems($tree, $return = false)
    {
        $html  = '';
        $order = 1;

        static $level = 0;

        $level++;

        foreach ($tree as $item) {

            $item['level'] = $level;

            switch ($item['data']['type']) {
                case 'page':
                    $html .= $this->fetchMenuPage($item);
                    break;
                case 'category':
                    if ($category = $this->getCategoryInfo($item)) {
                        $html .= $this->fetchMenuCategory($category, $item, $return, $order);
                    }
                    break;
                case 'categories';
                    $html .= $this->fetchMenuAllCategories($item);
                    break;
                case 'manufacturers';
                    $html .= $this->fetchMenuManufacturers($item);
                    break;
                case 'url';
                    $html .= $this->fetchMenuUrl($item);
                    break;
                case 'home';
                    $html .= $this->fetchMenuHome($item);
                    break;
                case 'html':
                    $html .= $this->fetchMenuHtml($item);
                    break;
                case 'system':
                    $html .= $this->fetchMenuSystem($item);
                    break;
                case 'title':
                    $html .= $this->fetchMenuTitle($item);
                    break;
                case 'separator':
                    $html .= $this->fetchMenuSeparator($item);
                    break;
            }

            $order++;
        }

        $level--;

        return $html;
    }

    public function getCategorySubMenu($category, array $menu_settings, $max_depth = null, $megamenu = false, $all_categories = false)
    {
        if (!empty($category['children']) && (null === $max_depth || $category['level'] < $max_depth)) {

            foreach ($category['children'] as &$child) {
                $menu_classes   = 'tb_menu_category_' . $child['category_id'];
                $menu_classes  .= ' tb_link';

                $menu_classes  .= !empty($child['children'])
                                  && (
                                     null === $max_depth
                                     || ($child['level']) < $max_depth
                                  )
                                  && (
                                     $menu_settings['is_megamenu']
                                     && $child['level'] > 2
                                     || !$menu_settings['is_megamenu']
                                     || $megamenu
                                  )
                                  && (
                                     $this->widget_settings['orientation'] == 'horizontal'
                                     ||
                                     $this->widget_settings['orientation'] == 'vertical'
                                     && (
                                         ($child['level'] == 1 && $this->widget_settings['level_2'] == 'dropdown')
                                         || ($child['level'] == 2 && $this->widget_settings['level_3'] == 'dropdown')
                                     )
                                  )
                                  ? ' dropdown' : '';
                $label_classes  = $menu_settings['is_megamenu']
                                  && $menu_settings['depth'] > 1
                                  && $child['level'] < 3
                                  && !$megamenu
                                  ? 'h4' : '';
                $label_classes .= !$menu_settings['is_megamenu']
                                  && $child['level'] == 2
                                  && $this->widget_settings['orientation'] == 'vertical'
                                  && $this->widget_settings['level_2_style'] != 'none'
                                  && $this->widget_settings['level_2_style'] != 'dropdown'
                                  ? $this->widget_settings['level_2_style'] : '';
                $label_classes .= !$menu_settings['is_megamenu']
                                  && $child['level'] == 3
                                  && $this->widget_settings['orientation'] == 'vertical'
                                  && $this->widget_settings['level_3_style'] != 'none'
                                  && $this->widget_settings['level_3_style'] != 'dropdown'
                                  ? $this->widget_settings['level_3_style'] : '';

                $child['menu_classes']  = !empty($menu_classes)  ? ' class="' . trim($menu_classes)  . '"' : '';
                $child['label_classes'] = !empty($label_classes) ? ' class="' . trim($label_classes) . '"' : '';

            }

            $submenu_classes  = $category['level'] == 1 ? $menu_settings['wrapper_classes'] : '';
            $submenu_classes .= !$menu_settings['is_megamenu']
                                && $this->widget_settings['level_2_style'] == 'list'
                                || $menu_settings['is_megamenu']
                                && ($megamenu
                                    || $category['level'] > 1)
                                ? ' tb_list_1' : '';
            $submenu_classes .= $this->widget_settings['orientation'] == 'horizontal'
                                && (
                                    $menu_settings['is_megamenu']
                                    && $category['level'] > 2
                                    || !$menu_settings['is_megamenu']
                                    || $megamenu
                                    && !$menu_settings['is_megamenu']
                                )
                                ||
                                $this->widget_settings['orientation'] == 'vertical'
                                && !$menu_settings['is_megamenu']
                                && (
                                    ($category['level'] == 1 && $this->widget_settings['level_2'] == 'dropdown')
                                    || ($category['level'] == 2 && $this->widget_settings['level_3'] == 'dropdown')
                                    || ($all_categories && $category['level'] == 1 && $this->widget_settings['level_3'] == 'dropdown')
                                )
                                ? ' dropdown-menu' : '';

            if ($megamenu) {
                $menu_settings['is_megamenu'] = false;
            }

            return trim($this->extension->fetchTemplate('menu/category_item', array(
                'widget_settings' => $this->widget_settings,
                'category'        => $category,
                'settings'        => $menu_settings,
                'max_depth'       => $max_depth,
                'submenu_classes' => !empty($submenu_classes) ? ' class="' . $submenu_classes . '"' : '',
                'toolImage'       => $this->getThemeModel()
            )));
        }

        return '';
    }

    protected function fetchMenuTitle($menu_item)
    {
        $item_settings = $menu_item['data']['settings'];

        return $this->extension->fetchTemplate('menu/title', array(
            'widget_settings' => $this->widget_settings,
            'settings'        => $item_settings,
        ));
    }

    protected function fetchMenuSeparator($menu_item)
    {
        $item_settings = $menu_item['data']['settings'];

        return $this->extension->fetchTemplate('menu/separator', array(
            'widget_settings' => $this->widget_settings,
            'settings'        => $item_settings,
        ));
    }

    protected function fetchMenuCategory($category, $menu_item, $return = false, $order)
    {
        $settings     = $menu_item['data']['settings'];
        $max_depth    = (int) $settings['depth'];

        if (empty($max_depth) || $max_depth < 0) {
            $max_depth = 99;
        }
        if (empty($settings['subcategory_direction'])) {
            $settings['subcategory_direction'] = 'row';
        }
        if (!empty($settings['category_custom_bg'])) {
            $settings['category_custom_bg'] = $this->engine->getContext()->getImageUrl() . $settings['category_custom_bg'];
        }
        if (!empty($settings['menu_banner'])) {
            $banner_file = $this->engine->getContext()->getImageDir() . '/' . $settings['menu_banner'];
            if (is_file($banner_file)) {
                $settings['menu_banner'] = $this->engine->getContext()->getImageUrl() . $settings['menu_banner'];
                list($settings['menu_banner_width'], $settings['menu_banner_height']) = getimagesize($banner_file);
            }
        }

        $settings['description']        = isset($settings['description']) ? trim($settings['description']) : '';
        $settings['category_custom_bg'] = isset($settings['category_custom_bg']) ? trim($settings['category_custom_bg']) : '';
        $settings['menu_banner']        = isset($settings['menu_banner']) ? trim($settings['menu_banner']) : '';
        $settings['wrapper_classes']  = '';
        $settings['wrapper_classes'] .= $settings['is_megamenu'] ? ($settings['subcategory_direction'] == 'row' && $settings['depth'] > 1 ? ' tb_listing tb_grid' : ' tb_multicolumn') : '';
        $settings['wrapper_classes'] .= $settings['is_megamenu'] && $settings['depth'] < 2 ? ' tb_list_1' : '';
        $settings['max_level_thumb'] = 0;
        if ($settings['category_thumb']) {
            $settings['max_level_thumb'] = $settings['subcategory_hover_thumb'] ? 2 : 1;
        }

        $subcategories = $return != 'labels' ? $this->getCategorySubMenu($category, $settings, $max_depth + 1) : false;

        list($menu_classes, $label_classes) = $this->generateMenuCssClasses($menu_item, 'category', !empty($subcategories), $return);

        $menu_classes .= ' tb_menu_category_' . $menu_item['data']['id'];
        $menu_classes .= $return == 'labels' && $order == 1  ? ' active'    : '';
        $menu_classes .= $return == 'menus'  && $order == 1  ? ' tb_opened' : '';

        $menu_attributes  = ' id="menu_category_' . $this->widget_id . '_' . $menu_item['data']['id'] . ($return == 'menus' ? '_tab' : '') . '"';
        $menu_attributes .= !empty($menu_classes) ? ' class="' . $menu_classes . '"' : '';
        $menu_attributes .= !empty($settings['dropdown_width']) ? ' data-dropdown-width="' . $settings['dropdown_width'] . '"' : '';

        $category_label = !empty($settings['label']) ? $settings['label'] : $category['name'];
        $manufacturers  =  array();

        if ($settings['manufacturers_type'] != 'none') {
            $category_ids = array_merge((array) $category['category_id'], $category['successor_ids']);

            /** @var Theme_Catalog_ManufacturerModel $manufacturerModel */
            $manufacturerModel = $this->getModel('manufacturer');

            $manufacturers = $manufacturerModel->getManufacturersByCategoryIds($category_ids);
            $manufacturers = $this->modifyMenuItemManufacturers($manufacturers, $settings);
            foreach ($manufacturers as &$manufacturer) {
                $manufacturer['href'] = $this->getThemeData()->link($this->extension->getRouteByName('category_manufacturer'), 'c_id=' . $category['category_id'] . '&man_id=' . $manufacturer['manufacturer_id']);
            }
        }

        $category_info = ($settings['show_main_thumbnail'] && $category['thumb'])
                         || $settings['category_custom_bg']
                         || $settings['description']
                         || $settings['show_title'];
        $submenu_width = 5 - ($category_info ? 1 : 0) - (!empty($manufacturers) && $settings['manufacturers_position'] == 'column' ? 1 : 0);

        $category_manufacturers = $this->extension->fetchTemplate('menu/category_manufacturers', array(
            'widget_settings' => $this->widget_settings,
            'manufacturers'   => $manufacturers,
            'settings'        => $settings,
            'title'           => $category['name'] . ' ' . $this->getThemeData()->text_menu_brands,
            'is_megamenu'     => (bool) $settings['is_megamenu']
        ));

        $menu_banner = $this->extension->fetchTemplate('menu/menu_banner', array(
            'widget_settings' => $this->widget_settings,
            'menu_banner'     => $settings['menu_banner'],
            'settings'        => $settings,
        ));

        // Dropdown menu
        $dropdown_classes = ' class="dropdown-menu"';
        $dropdown_styles  = !empty($settings['dropdown_width']) ? ' style="width: ' . $settings['dropdown_width'] . 'px;"' : '';

        return $this->extension->fetchTemplate('menu/category', array(
            'settings'               => $settings,
            'widget_settings'        => $this->widget_settings,
            'category'               => $category,
            'category_manufacturers' => $category_manufacturers,
            'category_info'          => $category_info,
            'menu_banner'            => $menu_banner,
            'menu_item_id'           => $this->widget_id . '_' . $menu_item['data']['id'] . ($return == 'menus' ? '_tab' : ''),
            'menu_attributes'        => $menu_attributes,
            'subcategories'          => $subcategories,
            'submenu_width'          => $submenu_width,
            'manufacturers'          => $manufacturers,
            'category_label'         => $category_label,
            'toolImage'              => $this->getThemeModel(),
            'url'                    => $this->engine->getOcUrl(),
            'menu_icon'              => $this->generateMenuIcon($menu_item),
            'accent_label'           => $this->generateMenuAccentLabel($menu_item),
            'label_classes'          => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'label_attributes'       => $return == 'labels' ? ' data-target="menu_category_' . $this->widget_id . '_' . $menu_item['data']['id'] . '_tab"' : '',
            'dropdown_attributes'    => $dropdown_classes . $dropdown_styles,
            'megamenu_title_class'   => $settings['depth'] > 1 ? ' class="h4"' : ''
        ) + $this->engine->loadOcTranslation('common/footer'));
    }

    protected function fetchMenuAllCategories($menu_item)
    {
        /** @var Theme_Catalog_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');

        $settings      = $menu_item['data']['settings'];
        $categories    = $categoryModel->getCategoriesTree();
        $manufacturers = array();

        $max_depth = (int) $settings['depth'];
        if (empty($max_depth) || $max_depth < 0) {
            $max_depth = 99;
        }

        if (!empty($settings['menu_banner'])) {
            $banner_file = $this->engine->getContext()->getImageDir() . '/' . $settings['menu_banner'];
            if (is_file($banner_file)) {
                $settings['menu_banner'] = $this->engine->getContext()->getImageUrl() . $settings['menu_banner'];
                list($settings['menu_banner_width'], $settings['menu_banner_height']) = getimagesize($banner_file);
            }
        }

        $settings['max_level_thumb'] = 0;
        $settings['wrapper_classes'] = '';
        $settings['menu_banner']     = isset($settings['menu_banner']) ? trim($settings['menu_banner']) : '';

        list($menu_classes, $label_classes) = $this->generateMenuCssClasses($menu_item, 'all_categories', !empty($categories));

        $menu_classes           .= ' tb_menu_all_categories';
        $wrapper_classes         = '';
        $wrapper_classes        .= $settings['is_megamenu'] ? (empty($settings['subcategory_direction']) || $settings['subcategory_direction'] == 'row' ? ' tb_listing tb_grid' : ' tb_multicolumn') : '';
        $wrapper_classes        .= !$settings['is_megamenu']
                                   && $this->widget_settings['level_2_style'] == 'list'
                                   ? ' tb_list_1' : '';
        $wrapper_classes        .= !$settings['is_megamenu']
                                   && ($this->widget_settings['orientation'] == 'horizontal'
                                       || $this->widget_settings['orientation'] == 'vertical'
                                       && $this->widget_settings['level_2'] == 'dropdown')
                                   ? ' dropdown-menu' : '';

        $level_1_menu_classes    = 'tb_menu_category_' . $menu_item['data']['id'];
        $level_1_menu_classes   .= !$settings['is_megamenu'] && $this->widget_settings['level_2_style'] == 'list' ? ' tb_link' : '';
        $level_1_label_classes   = $settings['is_megamenu'] ? ' h4' : '';

        if ($settings['manufacturers_type'] != 'none') {
            $manufacturers = $this->getMenuItemManufacturers($settings);
            foreach ($manufacturers as &$manufacturer) {
                $manufacturer['href'] = $this->engine->getOcUrl()->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']);
            }
        }

        foreach($categories as $key => &$category) {
            if (!$category['top']) {
                unset($categories[$key]);

                continue;
            }
            $category['submenu'] = $this->getCategorySubMenu($category, $settings, $max_depth, $settings['is_megamenu'], true);
        }

        $menu_banner = $this->extension->fetchTemplate('menu/menu_banner', array(
            'widget_settings' => $this->widget_settings,
            'menu_banner'     => $settings['menu_banner'],
            'settings'        => $settings,
        ));

        $category_manufacturers = $this->extension->fetchTemplate('menu/category_manufacturers', array(
            'widget_settings' => $this->widget_settings,
            'manufacturers'   => $manufacturers,
            'settings'        => $settings,
            'title'           => $settings['manufacturers_display'] == 'label' ? $this->getThemeData()->text_menu_brands : '',
            'is_megamenu'     => $settings['is_megamenu']
        ));

        return $this->extension->fetchTemplate('menu/all_categories', array(
            'settings'               => $settings,
            'widget_settings'        => $this->widget_settings,
            'categories'             => $categories,
            'category_manufacturers' => $category_manufacturers,
            'manufacturers'          => $manufacturers,
            'menu_banner'            => $menu_banner,
            'max_depth'              => $max_depth,
            'menu_item_id'           => $this->widget_id . '_' . $menu_item['data']['id'],
            'is_megamenu'            => $settings['is_megamenu'],
            'categories_width'       => 5 - (!empty($manufacturers) && $settings['manufacturers_position'] == 'column' ? 1 : 0),
            'toolImage'              => $this->getThemeModel(),
            'url'                    => $this->engine->getOcUrl(),
            'menu_classes'           => !empty($menu_classes)    ? ' class="' . trim($menu_classes)    . '"' : '',
            'menu_icon'              => $this->generateMenuIcon($menu_item),
            'accent_label'           => $this->generateMenuAccentLabel($menu_item),
            'label_classes'          => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'wrapper_classes'        => !empty($wrapper_classes) ? ' class="' . trim($wrapper_classes) . '"' : '',
            'level_1_menu_classes'   => $level_1_menu_classes,
            'level_1_label_classes'  => !empty($level_1_label_classes) ? ' class="' . trim($level_1_label_classes) . '"' : ''
        ) + $this->engine->loadOcTranslation('common/footer'));
    }

    protected function getCategoryInfo($menu_item)
    {
        /** @var Theme_Catalog_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');
        $category = $categoryModel->getTreeById($menu_item['data']['id']);
        if (false === $category) {
            return null;
        }

        $category['thumb'] = '';
        if ($menu_item['data']['settings']['show_main_thumbnail']) {
            $config_group = $this->engine->gteOc22() ? $this->engine->getConfigTheme() : 'config';

            $category['image'] ? $image = $category['image'] : $image = 'no_image.jpg';
            $category['thumb'] = (string) $this->getThemeModel()->resizeImage($image, $this->engine->getOcConfig()->get($config_group . '_image_category_width'), $this->engine->getOcConfig()->get($config_group . '_image_category_height'));
            $category['thumb_width']  = $this->engine->getOcConfig()->get($config_group . '_image_category_width');
            $category['thumb_height'] = $this->engine->getOcConfig()->get($config_group . '_image_category_height');
        }

        return $category;
    }

    protected function fetchMenuPage($menu_item)
    {
        /** @var Theme_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');
        $page = $defaultModel->getInformationPages($menu_item['data']['id']);

        if (false === $page) {
            return '';
        }

        $settings = $menu_item['data']['settings'];
        $url      = $this->engine->getOcUrl();

        list($menu_classes, $label_classes, $submenu_classes) = $this->generateMenuCssClasses($menu_item, 'page', !empty($menu_item['children']));

        $menu_classes .= ' tb_menu_page_' . $page['id'];
        if ($this->getThemeData()->information_id == $page['id']) {
            $menu_classes .= ' tb_selected';
        }

        return $this->extension->fetchTemplate('menu/page', array(
            'settings'          => $settings,
            'widget_settings'   => $this->widget_settings,
            'menu_item'         => $menu_item,
            'has_submenu'       => !empty($menu_item['children']),
            'information_page'  => $page,
            'url'               => $url->link('information/information', 'information_id=' . $page['id']),
            'target'            => '',
            'label'             => !empty($settings['label']) ? $settings['label'] : $page['title'],
            'menu_classes'      => !empty($menu_classes)    ? ' class="' . trim($menu_classes)    . '"' : '',
            'label_classes'     => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'submenu_classes'   => !empty($submenu_classes) ? ' class="' . trim($submenu_classes) . '"' : '',
            'menu_icon'         => $this->generateMenuIcon($menu_item),
            'accent_label'      => $this->generateMenuAccentLabel($menu_item)
        ));
    }

    protected function fetchMenuManufacturers($menu_item)
    {
        $settings = $menu_item['data']['settings'];

        $manufacturers = $this->getMenuItemManufacturers($menu_item['data']['settings']);
        $sorted_manufacturers = array();
        foreach ($manufacturers as $brand) {
            $first_letter = utf8_substr($brand['name'], 0, 1);
            if (!isset($sorted_manufacturers[$first_letter])) {
                $sorted_manufacturers[$first_letter] = array();
            }

            $sorted_manufacturers[$first_letter][] = $brand;
        }

        $max_columns = 2;
        $per_column = round((count($manufacturers) + count($sorted_manufacturers)) / $max_columns);
        $columns = array();
        $i = 0;
        $column_items_cnt = 0;
        $distribution_type = 2;

        foreach ($sorted_manufacturers as $letter => $items) {
            if ($distribution_type == 1 && isset($columns[$i]) && $i != $max_columns-1 && ($column_items_cnt + count($items)+1 > $per_column)) {
                $i++;
                $column_items_cnt = 0;
            }

            $columns[$i][$letter] = $items;
            $column_items_cnt += count($items)+1;

            if ($distribution_type == 2 && $i != $max_columns-1 && ($column_items_cnt >= $per_column)) {
                $i++;
                $column_items_cnt = 0;
            }
        }

        return $this->extension->fetchTemplate('menu/manufacturers', array(
            'widget_settings' => $this->widget_settings,
            'manufacturers'   => $columns,
            'settings'        => $settings,
            'is_megamenu'     => isset($settings['is_megamenu']) && $settings['is_megamenu'],
            'url'             => $this->engine->getOcUrl(),
            'menu_icon'         => $this->generateMenuIcon($menu_item),
            'accent_label'      => $this->generateMenuAccentLabel($menu_item)
        ));
    }

    protected function getMenuItemManufacturers($item_settings)
    {
        $options = array();

        if ($item_settings['manufacturers_type'] == 'custom' && isset($item_settings['manufacturers'])) {
            $options['manufacturer_ids'] = (array) $item_settings['manufacturers'];
        }

        /** @var Theme_Catalog_ManufacturerModel $manufacturerModel */
        $manufacturerModel = $this->getModel('manufacturer');
        $manufacturers = $manufacturerModel->getManufacturers($options);

        return $this->modifyMenuItemManufacturers($manufacturers, $item_settings);
    }

    protected function modifyMenuItemManufacturers($manufacturers, $item_settings)
    {
        foreach ($manufacturers as &$manufacturer) {
            if ($item_settings['manufacturers_display'] != 'label' && $manufacturer['image']) {
                $manufacturer['image'] = (string) $this->getThemeModel()->resizeImage($manufacturer['image'], $item_settings['image_size_x'], $item_settings['image_size_y']);
                $manufacturer['image_width']  = $item_settings['image_size_x'];
                $manufacturer['image_height'] = $item_settings['image_size_y'];
            } else {
                $manufacturer['image'] = false;
            }

            $manufacturer['display_name'] = ($item_settings['manufacturers_display'] != 'image');
        }

        return $manufacturers;
    }

    protected function fetchMenuUrl($menu_item)
    {
        $settings         = $menu_item['data']['settings'];
        $url              = isset($settings['url']) ? trim($settings['url']) : '';

        list($menu_classes, $label_classes, $submenu_classes) = $this->generateMenuCssClasses($menu_item, 'url', !empty($menu_item['children']));

        $label_classes   .= empty($settings['label']) ? ' tb_no_text' : '';
        $menu_classes    .= ' tb_menu_url_' . $menu_item['data']['id'];
        $menu_classes    .= !empty($settings['tabbed_submenus']) && empty($this->widget_settings['ignore_tabbed_submenu']) ? ' tb_tabbed_menu' : '';

        return $this->extension->fetchTemplate('menu/url', array(
            'settings'          => $settings,
            'widget_settings'   => $this->widget_settings,
            'menu_id'           => 'menu_url_' . $menu_item['data']['id'],
            'menu_item'         => $menu_item,
            'menu_classes'      => !empty($menu_classes)    ? ' class="' . trim($menu_classes)    . '"' : '',
            'url'               => !empty($url) ? $url : 'javascript:;',
            'target'            => ' target="' . $settings['target'] . '"',
            'label'             => !empty($settings['label']) ? $settings['label'] : '',
            'label_classes'     => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'has_menu'          => $this->widget_settings['orientation'] == 'vertical' && $menu_item['level'] == 1 && $this->widget_settings['level_1_style'] == 'hidden' ? false : true,
            'has_submenu'       => !empty($menu_item['children']),
            'submenu_classes'   => !empty($submenu_classes) ? ' class="' . trim($submenu_classes) . '"' : '',
            'menu_icon'         => $this->generateMenuIcon($menu_item),
            'accent_label'      => $this->generateMenuAccentLabel($menu_item)
        ));
    }

    protected function fetchMenuHome($menu_item)
    {
        //$menu_classes = isset($menu_item['data']['settings']['class']) ? trim($menu_item['data']['settings']['class']) : '';

        list($menu_classes, $label_classes, $submenu_classes) = $this->generateMenuCssClasses($menu_item, 'home', !empty($menu_item['children']));

        return $this->extension->fetchTemplate('menu/home', array(
            'widget_settings' => $this->widget_settings,
            'menu_classes'    => trim($menu_classes),
            'menu_item'       => $menu_item,
            'has_submenu'     => !empty($menu_item['children']),
            'home_url'        => $this->engine->getOcUrl()->link('common/home'),
            'display'         => $menu_item['data']['settings']['display'],
            'label_classes'   => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'submenu_classes' => !empty($submenu_classes) ? ' class="' . trim($submenu_classes) . '"' : ''
        ));
    }

    protected function fetchMenuHtml($menu_item)
    {
        $settings         = $menu_item['data']['settings'];
        $url              = isset($settings['url']) ? trim($settings['url']) : '';

        list($menu_classes, $label_classes, $submenu_classes) = $this->generateMenuCssClasses($menu_item, 'url', !empty($menu_item['children']));

        $label_classes   .= empty($settings['label']) ? ' tb_no_text' : '';
        $menu_classes    .= ' dropdown';
        $menu_classes    .= ' tb_menu_html_' . $menu_item['data']['id'];
        $menu_classes    .= $settings['dropdown_width_metric'] == '%' ? ' tb_full_menu' : '';

        return str_replace('&', '&amp;', $this->extension->fetchTemplate('menu/html', array (
            'settings'          => $settings,
            'widget_settings'   => $this->widget_settings,
            'menu_id'           => 'tb_menu_html_' . $menu_item['data']['id'],
            'menu_item'         => $menu_item,
            'menu_classes'      => !empty($menu_classes)    ? ' class="' . trim($menu_classes)    . '"' : '',
            'url'               => !empty($url) ? $url : 'javascript:;',
            'target'            => ' target="' . $settings['target'] . '"',
            'title'             => !empty($settings['url_title']) ? ' title="'  . $settings['url_title'] . '"' : '',
            'label'             => !empty($settings['label']) ? $settings['label'] : '',
            'label_classes'     => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'submenu_classes'   => !empty($submenu_classes) ? ' class="' . trim($submenu_classes) . '"' : '',
            'menu_icon'         => $this->generateMenuIcon($menu_item),
            'accent_label'      => $this->generateMenuAccentLabel($menu_item)
        )));
    }

    protected function fetchMenuSystem($menu_item)
    {
        $page = $this->getThemeModel()->getSystemMenuPagesLang($menu_item['data']['id']);

        if (false === $page) {
            return '';
        }

        $settings    = $menu_item['data']['settings'];
        $ssl         = isset($page['ssl']) && $page['ssl'] ? 'SSL' : '';

        list($menu_classes, $label_classes, $submenu_classes) = $this->generateMenuCssClasses($menu_item, 'html', !empty($menu_item['children']));

        $menu_classes .= ' tb_menu_system_' . $menu_item['data']['id'];
        if ($this->getThemeData()->route == $page['route']) {
            $menu_classes .=  ' tb_selected';
        }

        return $this->extension->fetchTemplate('menu/page', array(
            'settings'        => $settings,
            'widget_settings' => $this->widget_settings,
            'menu_item'       => $menu_item,
            'has_submenu'     => !empty($menu_item['children']),
            'url'             => $this->engine->getOcUrl()->link($page['route'], '', $ssl),
            'label'           => !empty($settings['label']) ? $settings['label'] : $page['title'],
            'target'          => '',
            'menu_classes'    => ' class="' . trim($menu_classes) . '"',
            'label_classes'   => !empty($label_classes)   ? ' class="' . trim($label_classes)   . '"' : '',
            'submenu_classes' => !empty($submenu_classes) ? ' class="' . trim($submenu_classes) . '"' : '',
            'menu_icon'       => $this->generateMenuIcon($menu_item),
            'accent_label'    => $this->generateMenuAccentLabel($menu_item)
        ));
    }
}