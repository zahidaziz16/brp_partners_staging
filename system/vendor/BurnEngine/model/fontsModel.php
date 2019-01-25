<?php

require_once $this->context->getEngineDir() . '/library/WebfontsParser.php';

class Theme_FontsModel extends TB_ExtensionModel
{
    public function getGoogleFontsList()
    {
        static $result = array();

        if (!empty($result)) {
            return $result;
        }

        $fonts_file = $this->context->getConfigDir() . '/data/webfonts.json';
        $result = TB_WebfontsParser::parseJson($fonts_file);

        return $result;
    }

    public function getBuiltFontsList()
    {
        return array(
            'Arial',
            'Tahoma',
            'Verdana',
            'Trebuchet MS',
            'Lucida Sans Unicode',
            'Georgia',
            'Times New Roman'
        );
    }
}