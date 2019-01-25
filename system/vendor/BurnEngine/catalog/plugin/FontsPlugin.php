<?php

class Theme_Catalog_FontsPlugin extends TB_ExtensionPlugin
{
    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        $this->bootstrap('common');

        $font = $this->getSetting('font');
        $fonts = array();
        if (isset($font[$this->language_code])) {
            $fonts = $font[$this->language_code];
        } else
        if (isset($font)) {
            $fonts = reset($font);
        }

        $themeData->fonts = $fonts;
        $themeData->addCallable(array($this, 'calculateLineHeight'), 'calculateLineHeight');
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder, TB_ViewDataBag $themeData)
    {
        $styleBuilder->addFonts($themeData->fonts);
    }

    public function calculateLineHeight($font_size, $baseline_height)
    {
        $font = $this->getThemeData()->font;
        
        if (isset($font['body'])) {
          $baseline_height = $font['body']['line-height'];
        }
        // $baseline_height + ceil(($font_size - $baseline_height) / ($baseline_height / 2)) * ($baseline_height/2) - alternate method for calculation
        return $baseline_height + ceil((2 * ($font_size + 2) / $baseline_height) - 2) * ($baseline_height / 2);
    }
}