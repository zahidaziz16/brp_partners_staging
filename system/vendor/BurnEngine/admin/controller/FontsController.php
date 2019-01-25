<?php

class Theme_Admin_FontsController extends TB_AdminController
{
    public function index()
    {
        $this->renderTemplate('theme_design_typography');
    }

    public function getFontData()
    {
        $name = (string) $this->request->get['font_name'];
        $list = $this->getModel('fonts')->getGoogleFontsList();

        $font = $list[$name];
        $this->setOutput(json_encode($font));
    }

    public function getFontsList()
    {
        $this->setOutput(json_encode(array(
            'built-in' => $this->getModel('fonts')->getBuiltFontsList(),
            'google'   => array_keys($this->getModel('fonts')->getGoogleFontsList())
        )));
    }
}