<?php

class Theme_Admin_ProductPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'product';
    }

    public function filterSettings(array &$product_settings)
    {
        $default_vars = array(
            'designs' => array(
                'layout' => array(),
                'option' => array()
            ),
            'image' => array(
                'thumb_width'       => 220,
                'thumb_height'      => 220,
                'popup_width'       => 500,
                'popup_height'      => 500,
                'additional_width'  => 70,
                'additional_height' => 70,
                'compare_width'     => 90,
                'compare_height'    => 90,
                'wishlist_width'    => 50,
                'wishlist_height'   => 50,
                'cart_width'        => 50,
                'cart_height'       => 50,
                'location_width'    => 250,
                'location_height'   => 50,
                'related_width'     => 80,
                'related_height'    => 80
            )
        );

        foreach ($this->getProductOptions() as $option_id => $option) {
            if (!isset($default_vars['designs']['option'][$option_id])) {
                $default_vars['designs']['option'][$option_id] = array(
                    'style_id' => 'style_1'
                );
                if ($option['type'] == 'image') {
                    $default_vars['designs']['option'][$option_id]['image_width']  = 23;
                    $default_vars['designs']['option'][$option_id]['image_height'] = 23;
                }
            }
        }

        $product_settings = TB_FormHelper::initFlatVarsSimple($default_vars, $product_settings);

        $product_settings['designs'] = TB_FormHelper::initFlatVarsSimple($default_vars['designs'], $product_settings['designs']);
        $product_settings['image']   = TB_FormHelper::initFlatVarsSimple($default_vars['image'], $product_settings['image']);
    }

    public function setDataForView(&$plugin_settings, TB_ViewDataBag $themeData)
    {
        $themeData->product_options = $this->getProductOptions();
        $themeData->product_option_styles = $this->getThemeModel()->getProductOptionStyles();

        foreach ($themeData->product_options as $option_id => $option) {
            if (!isset($plugin_settings['designs']['option'][$option_id])) {
                $plugin_settings['designs']['option'][$option_id]['style_id'] = 'style_1';
                if ($option['type'] == 'image' || $option['has_images']) {
                    $plugin_settings['designs']['option'][$option_id]['image_width']  = 22;
                    $plugin_settings['designs']['option'][$option_id]['image_height'] = 22;
                }
            }
        }
    }

    protected function getProductOptions()
    {
        return $this->getThemeModel()->getProductOptions((array('checkbox', 'radio', 'image')));
    }

    public function saveData($post_data)
    {
        if ($this->engine->gteOc22() && isset($post_data[$this->getConfigKey()])) {
            $config_images = array();

            foreach ($post_data[$this->getConfigKey()]['image'] as $key => $value) {
                $config_images[TB_Engine::getName() . '_image_' . $key] = $value;
            }

            $this->engine->getDbSettingsHelper('setting')->persistGroup(TB_Engine::getName(), $config_images, $this->context->getStoreId());
        }

        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }
}