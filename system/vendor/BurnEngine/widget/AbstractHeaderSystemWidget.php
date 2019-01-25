<?php

require_once 'SystemWidget.php';

abstract class AbstractHeaderSystemWidget extends Theme_SystemWidget
{
    protected function modifyDefaultCommonVars(&$default_vars)
    {
        $default_vars['layout']['display'] = 'block';
    }
}