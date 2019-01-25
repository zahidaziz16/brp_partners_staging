<?php

class Theme_Admin_BackgroundPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'background';
    }

    public function filterSettings(array &$background_settings)
    {
        $default_settings = array(
            'global'   => array(),
            'page'     => array(),
            'category' => array(),
            'options'  => array(
                'category_inherit' => 'global',
                'page_inherit'     => 'global'
            )
        );

        $background_settings = TB_FormHelper::initFlatVarsSimple($default_settings, $background_settings);
        $background_settings['options'] = TB_FormHelper::initFlatVarsSimple($default_settings['options'], $background_settings['options']);

        $this->extension->getThemeData()->addJavascriptVar('tb/background/options', $background_settings['options']);

        $default_vars = $this->getDefaultVars();

        $background_settings['default'] = $default_vars;
        $background_settings['global'] = TB_FormHelper::initFlatVarsSimple($default_vars, $background_settings['global']);
        if (empty($background_settings['global']['type'])) {
            $background_settings['global']['type'] = 'none';
        }

        foreach ($background_settings['page'] as &$item) {
            $item = TB_FormHelper::initFlatVarsSimple($default_vars, $item);
        }
    }

    protected function getDefaultVars()
    {
        return array(
            'type'              => '',
            'image'             => '',
            'position'          => 'center',
            'position_x'        => 0,
            'position_y'        => 0,
            'position_x_metric' => 'px',
            'position_y_metric' => 'px',
            'repeat'            => 'no-repeat',
            'attachment'        => 'fixed',
            'size'              => 'auto',
            'size_x'            => 100,
            'size_y'            => 100,
            'size_x_metric'     => 'px',
            'size_y_metric'     => 'px',
        );
    }

    public function saveData($post_data, &$theme_settings)
    {
        if (empty($post_data[$this->getConfigKey()]['category'])) {
            unset($theme_settings['background']['category']);
        }

        if (empty($post_data[$this->getConfigKey()]['page'])) {
            unset($theme_settings['background']['page']);
        }

        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }

    public function setDataForView(&$background_settings, TB_ViewDataBag $themeData)
    {
        $background_settings['default'] = $this->getDefaultVars();

        $this->setPreviewImage($background_settings['default']);
        $this->setPreviewImage($background_settings['global']);

        foreach ($background_settings['page'] as &$item) {
            $this->setPreviewImage($item);
        }

        $categories_flat = $this->extension->getModel('category')->getCategoriesFlatTree();
        foreach ($background_settings['category'] as $category_id => &$item) {

            if ($category_id != 0 && !isset($categories_flat[$category_id])) {
                unset($background_settings['category'][$category_id]);
                continue;
            }

            $item = TB_FormHelper::initFlatVarsSimple($this->getDefaultVars(), $item);

            if ($category_id == 0) {
                $item['category_full_name'] = 'All categories';
                $item['category_name'] = 'All categories';
                $item['category_parent_name'] = '';

                $this->setPreviewImage($item);

                continue;
            }

            $name_stack = array();
            $current_id = $category_id;

            do {
                $name_stack[] = $categories_flat[$current_id]['name'];
                $current_id = $categories_flat[$current_id]['parent_id'];
            } while ($current_id != 0);

            $item['category_full_name'] = join(' > ', array_reverse($name_stack));
            $item['category_name'] = $categories_flat[$category_id]['name'];
            $parent_id = $categories_flat[$category_id]['parent_id'];

            $item['category_parent_name'] = '';
            if ($parent_id != 0) {
                $item['category_parent_name'] = $categories_flat[$parent_id]['name'];
            }

            $this->setPreviewImage($item);
        }
    }

    protected function setPreviewImage(&$row)
    {
        if (is_file(DIR_IMAGE . $row['image'])) {
            $row['preview'] = $this->getOcModel('tool/image')->resize($row['image'], 100, 100);
        } else {
            $row['preview'] = $this->getThemeModel()->getNoImage();
        }
    }
}