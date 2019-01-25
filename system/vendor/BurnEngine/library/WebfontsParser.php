<?php

class TB_WebfontsParser
{
    public static function parseJson($fonts_file)
    {
        if (!file_exists($fonts_file)) {
            throw new Exception('Could not find webfonts.json');
        }

        $fonts = json_decode(file_get_contents($fonts_file));
        $fonts = (array) $fonts->items;

        $result = array();
        foreach ($fonts as $font) {
            unset($font->kind);

            $variants = array();
            foreach ($font->variants as $variant) {
                $variants[$variant]['code'] = $variant;
                if ($variant == 'regular') {
                    $css_weight = 'normal';
                    $css_style = 'default';
                } else
                if ($variant  == 'bold') {
                    $css_weight = 'bold';
                    $css_style = 'default';
                } else
                if ($variant == 'italic') {
                    $css_weight = 'normal';
                    $css_style = 'italic';
                } else
                if ($variant == 'bolditalic') {
                    $css_weight = 'bold';
                    $css_style = 'italic';
                } else
                if (is_numeric($variant)) {
                    $css_weight = $variant;
                    $css_style = 'default';
                } else {
                    $matches = preg_split('#(?<=\d)(?=[a-z])#i', $variant);
                    $css_weight = $matches[0];
                    $css_style = $matches[1];
                }

                $variants[$variant]['css_weight'] = $css_weight;
                $variants[$variant]['css_style'] = $css_style;
            }
            $font->variants = $variants;

            $result[$font->family] = $font;
        }

        return $result;
    }
}