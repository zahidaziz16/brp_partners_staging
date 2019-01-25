<?php

require_once dirname(__FILE__) . '/../../model/layoutBuilderModel.php';

class Theme_Catalog_LayoutBuilderModel extends Theme_LayoutBuilderModel
{
    public function filterAreaSettings(array &$area_settings, $area_name)
    {
        if ($area_name == 'area_column_left' || $area_name == 'area_column_right') {
            $area_name = 'area_content';
        }

        $this->filterSettings($area_settings, $area_name);
    }

    public function filterRowSettings(array &$row_settings)
    {
        $this->filterSettings($row_settings, 'widgets_row');
    }

    public function filterColumnSettings(array &$column_settings, $row_settings)
    {
        $this->filterSettings($column_settings, 'row_column', null, $row_settings);
    }
}