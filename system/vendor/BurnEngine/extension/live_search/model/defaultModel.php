<?php
class LiveSearch_DefaultModel extends TB_ExtensionModel
{
    /**
     * @return TB_SettingsModel
     */
    public function getSettingsModel()
    {
        return $this->engine->getSettingsModel('live_search', $this->context->getStoreId());
    }

    public function getSettings()
    {
        $settings = $this->getSettingsModel()->getScopeSettings('settings');
        $settings = array_replace_recursive($this->getDefaultSettings(), (array) $settings);

        return $settings;
    }

    protected function getDefaultSettings()
    {
        return array(

            // General
            'show_image'   => 1,
            'show_model'   => 1,
            'show_price'   => 1,
            'max_results'  => 5,
            'search_in'    => array(
                'name'          => 1,
                'manufacturer'  => 1,
                'categories'    => 1,
                'model'         => 0,
                'description'   => 0,
                /*
                'tags'          => 0,
                'sku'           => 0,
                'upc'           => 0,
                'ean'           => 0,
                'jan'           => 0,
                'isbn'          => 0,
                'mpn'           => 0,
                */
            ),
            // Styling
            'highlight_results'  => 1,
            'min_length'         => 2,
            'image_width'        => 50,
            'image_height'       => 50,
            'dropdown_width'     => 250,
            'title_style'        => 'h3',
        );
    }
}