<?php

class TB_StyleBuilder
{
    /**
     * @var TB_Context
     */
    protected $context;

    /**
     * @var Theme_FontsModel
     */
    protected $fontsModel;

    /**
     * @var array
     */
    protected $css = array();

    /**
     * @var string
     */
    protected $external_css = '';

    /**
     * @var array
     */
    protected $fonts = array();

    /**
     * Fonts from presets to be included in the google fonts declaration includeFontResource. Used in StylePlugin
     *
     * @var array
     */
    protected $fonts_for_web = array();

    /**
     * @var array
     */
    protected $combined_fonts = array();

    protected $background_css = '';

    /**
     * @var array
     */
    protected $global_colors = array();

    /**
     * @var array
     */
    protected $scoped_colors = array();

    protected $baseline_height;

    public function __construct(TB_Context $context, Theme_FontsModel $fontsModel)
    {
        $this->context    = $context;
        $this->fontsModel = $fontsModel;
    }

    public function setBaseLineHeight($height)
    {
        $this->baseline_height = $height;
    }

    public function addGlobalColorRule(array $color_rule)
    {
        $this->processColorRule($color_rule);
    }

    public function addScopedColorRule($id, array $color_rule, $bg_solid_color = null)
    {
        $this->processColorRule($color_rule, $id, $bg_solid_color);
    }

    public function buildBoxModelCss($layout, $scope, $lang_dir = 'ltr', $return = false)
    {
        $css      = '';

        if (!empty($layout['margin_top']) && (empty($return) || $return == 'margin_y')) {
            $css .= $layout['margin_top']     != 0 ? 'margin-top:    '  . $layout['margin_top']     . "px;\n" : '';
        }
        if (!empty($layout['margin_bottom']) && (empty($return) || $return == 'margin_y')) {
            $css .= $layout['margin_bottom']  != 0 ? 'margin-bottom: '  . $layout['margin_bottom']  . "px;\n" : '';
        }
        if (!empty($layout['margin_left']) && (empty($return) || $return == 'margin_x')) {
            $margin_left   = $lang_dir == 'rtl' && !empty($layout['margin_rtl_mode'])  ? $layout['margin_right']  : $layout['margin_left'];
            $css .= $margin_left              != 0 ? 'margin-left: '    . $margin_left              . "px;\n" : '';
        }
        if (!empty($layout['margin_right']) && (empty($return) || $return == 'margin_x')) {
            $margin_right  = $lang_dir == 'rtl' && !empty($layout['margin_rtl_mode'])  ? $layout['margin_left']   : $layout['margin_right'];
            $css .= $margin_right             != 0 ? 'margin-right: '   . $margin_right             . "px;\n" : '';
        }
        if (!empty($layout['padding_top']) && (empty($return) || $return == 'padding_y')) {
            $css .= $layout['padding_top']    != 0 ? 'padding-top: '    . $layout['padding_top']    . "px;\n" : '';
        }
        if (!empty($layout['padding_bottom']) && (empty($return) || $return == 'padding_y')) {
            $css .= $layout['padding_bottom'] != 0 ? 'padding-bottom: ' . $layout['padding_bottom'] . "px;\n" : '';
        }
        if (!empty($layout['padding_left']) && (empty($return) || $return == 'padding_x')) {
            $padding_left  = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_right'] : $layout['padding_left'];
            $css .= $padding_left             != 0 ? 'padding-left: '   . $padding_left             . "px;\n" : '';
        }
        if (!empty($layout['padding_right']) && (empty($return) || $return == 'padding_x')) {
            $padding_right = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_left']  : $layout['padding_right'];
            $css .= $padding_right            != 0 ? 'padding-right: '  . $padding_right            . "px;\n" : '';
        }

        $this->addCss($css, $scope);
    }

    protected function processColorRule(array $color_rule, $id = null, $bg_solid_color = null)
    {
        if (empty($color_rule) || isset($color_rule['force_print']) && !$color_rule['force_print'] && $color_rule['inherit']) {
            return;
        }

        if (isset($color_rule['elements'])) {
            $color_rule['elements'] = html_entity_decode($color_rule['elements'], ENT_COMPAT, 'UTF-8');
        } else {
            $color_rule['elements'] = '';
        }

        $style_areas = array('header', 'intro', 'content', 'footer');

        if (null !== $id) {
            if (in_array($id, $style_areas)) {
                $scope = '.tb_area_' . $id;
            } else {
                $scope = $id;
            }

            if (!empty($color_rule['elements'])) {
                $scopes = explode(',', $color_rule['elements']);
                $scopes = array_map('trim', $scopes);
                $scope .= ' ' . implode(', ' . $scope . ' ', $scopes);
            }
        } else {
            $scope = $color_rule['elements'];
        }

        $color_properties = explode(',' , $color_rule['property']);
        $color_properties = array_map('trim', $color_properties);
        $color_rule['output'] = '';

        if ($color_rule['property'] != 'subtle') {

            // Normal colors
            foreach ($color_properties as $color_property) {
                $color_rule['output'] .= '  ' . $color_property . ': ' . (empty($color_rule['color']) ? 'transparent' : $color_rule['color']);
                $color_rule['output'] .= isset($color_rule['important']) && $color_rule['important'] ? ' !important' : '';
                $color_rule['output'] .= ";\n";
            }

            $color_rule['output'] = $scope . " {\n" . $color_rule['output'] . "}\n\n";

        } else {

            // Subtle colors
            foreach (explode(',' , $scope) as $scope) {
                $color_rule['output'] .= $scope . " .tb_bg_str_1, " . $scope . " .tb_bg_hover_str_1:hover { background-color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.05);}\n";
                $color_rule['output'] .= $scope . " .tb_bg_str_2, " . $scope . " .tb_bg_hover_str_2:hover { background-color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.1); }\n";
                $color_rule['output'] .= $scope . " .tb_bg_str_3, " . $scope . " .tb_bg_hover_str_3:hover { background-color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.15); }\n";
                $color_rule['output'] .= $scope . " .tb_bg_str_4, " . $scope . " .tb_bg_hover_str_4:hover { background-color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.2); }\n";
                $color_rule['output'] .= $scope . " .tb_bg_str_5, " . $scope . " .tb_bg_hover_str_5:hover { background-color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.3); }\n";
                $color_rule['output'] .= $scope . " .tb_text_str_1, " . $scope . " .tb_text_hover_str_1:hover { color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.2) !important; }\n";
                $color_rule['output'] .= $scope . " .tb_text_str_2, " . $scope . " .tb_text_hover_str_2:hover { color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.3) !important; }\n";
                $color_rule['output'] .= $scope . " .tb_text_str_3, " . $scope . " .tb_text_hover_str_3:hover { color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.4) !important; }\n";
                $color_rule['output'] .= $scope . " .tb_text_str_4, " . $scope . " .tb_text_hover_str_4:hover { color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.6) !important; }\n";
                $color_rule['output'] .= $scope . " .tb_text_str_5, " . $scope . " .tb_text_hover_str_5:hover { color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.8) !important; }\n";
                $color_rule['output'] .= $scope . " .border, " . $scope . " .border-color { border-color: " . 'rgba(' . TB_Utils::hex2rgb($color_rule['color'], true) . ", 0.15); }\n";
            }

        }

        if (null === $id) {
            $this->global_colors[] = $color_rule;
        } else {
            $this->scoped_colors[] = $color_rule;
        }
    }

    public function getGlobalColors()
    {
        return $this->global_colors;
    }

    public function getGlobalColorsString()
    {
        return $this->getColorsString($this->global_colors);
    }

    public function getScopedColors()
    {
        return $this->scoped_colors;
    }

    public function getScopedColorsString()
    {
        return $this->getColorsString($this->scoped_colors);
    }

    protected function getColorsString(array $rules)
    {
        $result = '';

        foreach ($rules as $color_rule) {
            $result .= $color_rule['output'];
        }

        return $result;
    }

    public function addFonts(array $fonts, $scope_id = '')
    {
        foreach ($fonts as $key => $font) {

            if ((empty($font['inherit_mask']) || $font['inherit_mask'] == 31) && (empty($font['type']) || $font['family'] == 'inherit')) {
                continue;
            }

            if (!empty($scope_id)) {
                $font['scope_id'] = $scope_id;
                $id = $key . '_' . $scope_id;
            } else {
                $id = TB_Utils::genRandomString();
            }

            $this->fonts[$id] = $font;
        }
    }

    public function addFontsForWeb(array $fonts)
    {
        foreach ($fonts as $key => $font) {
            if (empty($font['type']) || $font['type'] != 'google') {
                continue;
            }

            $this->fonts_for_web[TB_Utils::genRandomString()] = $font;
        }
    }

    public function getFonts()
    {
        $this->combineFonts();

        return $this->fonts;
    }

    public function getFontsString()
    {
        $result = '';

        foreach ($this->getFonts() as $font) {

            if (isset($font['elements'])) {
                $font['elements'] = html_entity_decode($font['elements'], ENT_COMPAT, 'UTF-8');
            } else {
                $font['elements'] = '';
            }

            $style_areas = array('header', 'intro', 'content', 'footer');

            if (!empty($font['scope_id'])) {
                if (in_array($font['scope_id'], $style_areas)) {
                    $scope = '.tb_area_' . $font['scope_id'];
                } else {
                    $scope = $font['scope_id'];
                }

                if (!empty($font['elements'])) {
                    $scopes = explode(',', $font['elements']);
                    $scopes = array_map('trim', $scopes);
                    $scope .= ' ' . implode(', ' . $scope . ' ', $scopes);
                }
            } else {
                $scope = $font['elements'];
            }

            $output = $scope . " {\n";

            if (!empty($font['family']) && $font['family'] != 'inherit') {
                $output .= "  font-family: '{$font['family']}';\n";
            }

            if (!isset($font['multiple_variants']) || !$font['multiple_variants']) {
                if (!empty($font['css_weight']) && $font['css_weight'] != 'default') {
                    $output .= "  font-weight: {$font['css_weight']};\n";
                }
                if (!empty($font['css_style']) && $font['css_style'] != 'default') {
                    $output .= "  font-style: {$font['css_style']};\n";
                }
            }

            if (!empty($font['size']) && (empty($font['inherit_mask']) || !($font['inherit_mask'] & 1))) {
                $output .= "  font-size: {$font['size']}px;\n";
            }

            if (!empty($font['has_line_height']))  {
                $no_inherit_mask   = !isset($font['inherit_mask']) || !strlen($font['inherit_mask']);
                $font_size_inherit = !$no_inherit_mask && $font['inherit_mask'] & 1;
                $has_font_size     = !empty($font['size']) && ($no_inherit_mask || !$font_size_inherit);

                if (!empty($font['line-height']) || $has_font_size) {
                    $line_height_inherit = !$no_inherit_mask && $font['inherit_mask'] & 2;

		            if ($no_inherit_mask || $line_height_inherit) {
			            $font['line-height'] = $this->calculateLineHeight($font['size']);
		            }

		            $output .= "  line-height: {$font['line-height']}px;\n";
	            }
            }

            if (isset($font['letter-spacing']) && strlen($font['letter-spacing']) && (empty($font['inherit_mask']) || !($font['inherit_mask'] & 4))) {
                $output .= "  letter-spacing: {$font['letter-spacing']}px;\n";
            }

            if (isset($font['word-spacing']) && strlen($font['word-spacing']) && (empty($font['inherit_mask']) || !($font['inherit_mask'] & 8))) {
                $output .= "  word-spacing: {$font['word-spacing']}px;\n";
            }

            if (!empty($font['transform']) && (empty($font['inherit_mask']) || !($font['inherit_mask'] & 16))) {
                $output .= "  text-transform: {$font['transform']};\n";
            }

            $output .= "}\n";

            $result .= $output;
        }

        return $result;
    }

    protected function calculateLineHeight($font_size)
    {
        return $this->baseline_height + ceil((2 * ($font_size + 2) / $this->baseline_height) - 2) * ($this->baseline_height / 2);
    }

    public function getCombinedFonts()
    {
        $this->combineFonts();

        return $this->combined_fonts;
    }

    public function getWebFonts()
    {
        $optimized = array();

        foreach ($this->combineFonts() as $family => $values) {
            if (!isset($optimized[$family])) {
                $optimized[$family] = $values;
            } else {
                $optimized[$family] = array_merge_recursive_distinct($optimized[$family], $values);
            }
        }

        $combine = $this->context->getEngineConfig('catalog_merge_google_fonts');

        $result = array();
        $families = array();
        $subsets = array();

        foreach ($optimized as $family => $values) {
            if ($combine) {
                $families[] = urlencode($family) . ':' . implode(',', $values['variants']);
                $subsets[] = trim(implode(',', $values['subsets']), ',');
            } else {
                $result[] = array(
                    'family' => urlencode($family) . ':' . implode(',', $values['variants']),
                    'subset' => trim(implode(',', $values['subsets']), ',')
                );
            }
        }

        if ($combine) {
            $result[] = array(
                'family' => implode('%7C', $families),
                'subset' => implode(',', array_unique($subsets))
            );
        }

        return $result;
    }

    protected function combineFonts()
    {
        $google_list = $this->fontsModel->getGoogleFontsList();
        $combined = array();

        foreach (array_merge($this->fonts, $this->fonts_for_web) as $section => $font) {

            if (empty($font['type']) || $font['type'] != 'google') {
                $css_weight = 'default';
                $css_style = 'default';

                if (!empty($font['type']) && $font['type'] == 'built' && isset($font['variant'])) {
                    $css_weight = 'normal';

                    if ($font['variant']  == 'bold') {
                        $css_weight = 'bold';
                    } else
                    if ($font['variant'] == 'italic') {
                        $css_weight = 'normal';
                        $css_style = 'italic';
                    } else
                    if ($font['variant'] == 'bolditalic') {
                        $css_weight = 'bold';
                        $css_style = 'italic';
                    }
                }

                $this->fonts[$section]['css_weight'] = $css_weight;
                $this->fonts[$section]['css_style'] = $css_style;

                if (empty($font['type']) || $font['type'] == 'inherit') {
                    unset($this->fonts[$section]['variant']);
                    unset($this->fonts[$section]['subsets']);
                }

            } else {

                $family = $font['family'];

                if (!isset($combined[$family]['variants'])) {
                    $combined[$family]['variants'] = array();
                    $combined[$family]['subsets'] = array();
                }

                $variants = isset($font['variant']) ? explode(',', $font['variant']) : '';
                $subsets = isset($font['subsets']) ? explode(',', $font['subsets']) : '';

                if (!isset($google_list[$family]->variants[$variants[0]])) {
                    $google_family_variant = reset($google_list[$family]->variants);
                    $this->fonts[$section]['variant'] = $google_family_variant['css_weight'];
                } else {
                    $google_family_variant = $google_list[$family]->variants[$variants[0]];
                }

                $this->fonts[$section]['css_weight'] = $google_family_variant['css_weight'];
                $this->fonts[$section]['css_style'] = $google_family_variant['css_style'];

                $variants = array_unique(array_merge($combined[$family]['variants'], $variants));
                $subsets = array_unique(array_merge($combined[$family]['subsets'], $subsets));

                $combined[$family]['variants'] = $variants;
                $combined[$family]['subsets'] = $subsets;
            }

            unset($this->fonts[$section]['show_built_styles']);
        }

        $this->combined_fonts = $combined;

        return $combined;
    }

    public function prependExternalCss($css)
    {
        $this->external_css .= $css;
    }

    public function getExternalCss()
    {
        return $this->external_css;
    }

    public function addCss($css, $scope = '')
    {
        if (empty($css)) {
            return;
        }

        if (empty($scope)) {
            $scope = 'not_scoped';
        }

        $this->css[$scope][] = $css;
    }

    public function getCss()
    {
        return $this->css;
    }

    public function getCssString()
    {
        $css = '';
        foreach ($this->css as $scope => $items) {
            $joined = implode("\n", $items);
            if ($scope != 'not_scoped') {
                $joined = $scope . ' {' . "\n" . '  ' . $joined . "\n}";
            }

            $css .= $joined . "\n";
        }

        return $css;
    }

    public function buildEffectsCss($settings, $scope)
    {
        $this->buildBackgroundCss($settings, $scope);
        $this->buildBorderCss($settings, $scope);
        $this->buildShadowCss($settings, $scope);
    }

    public function buildBorderCss($settings, $scope)
    {
        if (empty($settings['border']) && empty($settings['border_radius'])) {
            return;
        }

        $result      = '';

        if (isset($settings['border'])) {
            $border      = $settings['border'];
            $is_mirrored = $this->context->getLanguageDirection() == 'rtl' && !empty($settings['border']['rtl_mode']);

            foreach (array('top', 'right', 'bottom', 'left') as $side) {
                if (isset($border[$side]) && !empty($border[$side]['width'])) {
                    $css_side = $side;

                    if ($border[$side]['opacity'] == 100) {
                        $color = $border[$side]['color'];
                    } else {
                        $color = 'rgba(' . TB_Utils::hex2rgb($border[$side]['color'], true) . ', ' . ($border[$side]['opacity']/100) . ')';
                    }
                    if ($is_mirrored && $side == 'left') {
                        $css_side = 'right';
                    }
                    if ($is_mirrored && $side == 'right') {
                        $css_side = 'left';
                    }
                    $border_css = 'border-' . $css_side . ': ' . $border[$side]['width'] . 'px ' . $border[$side]['style'] . ' ' . $color . ';';
                    $result .= $border_css;
                }
            }
        }

        if (!empty($settings['border_radius'])) {
            $radius      = $settings['border_radius'];
            $is_mirrored = $this->context->getLanguageDirection() == 'rtl' && !empty($settings['border_radius']['rtl_mode']);

            $empty_positions = 0;
            foreach (array('top_left', 'top_right', 'bottom_left', 'bottom_right') as $side) {
                if (!isset($radius[$side])) {
                    $radius[$side] = 0;
                }
                if ($radius[$side] == 0) {
                    $empty_positions++;
                }
            }

            if ($empty_positions < 4) {
                $result .=  'border-radius: ' . ($is_mirrored ? $radius['top_right'] : $radius['top_left']) . 'px ' . ($is_mirrored ? $radius['top_left'] : $radius['top_right']) . 'px ' . ($is_mirrored ? $radius['bottom_left'] : $radius['bottom_right']) . 'px ' . ($is_mirrored ? $radius['bottom_right'] : $radius['bottom_left']) . 'px !important;' . "\n";
            }
        }

        $this->addCss($result, $scope);
    }

    public function buildShadowCss($settings, $scope)
    {
        if(!isset($settings['box_shadow']['rows']) || empty($settings['box_shadow']['rows'])) {
            return;
        }

        $box_shadow = '';

        foreach ($settings['box_shadow']['rows'] as $shadow) {
            $radiants = (pi()/180) * $shadow['angle'];
            $x = round(-$shadow['distance'] * cos($radiants));
            $y = round($shadow['distance'] * sin($radiants));
            $rbg = TB_Utils::hex2rgb($shadow['color']);
            $rbga = 'rgba(' . $rbg[0] . ', ' . $rbg[1] . ', ' . $rbg[2] . ', ' . ($shadow['opacity']/100) . ')';
            $shadow['inner'] ? $inset = ' inset' : $inset = '';
            $box_shadow .= $x . 'px ' . $y . 'px ' . $shadow['blur'] . 'px ' . $shadow['spread'] . 'px ' . $rbga . $inset . ', ';
        }

        $box_shadow = trim($box_shadow, ', ');
        if (!empty($box_shadow)) {
            $box_shadow = '-webkit-box-shadow: ' . $box_shadow . ';'. "\n" . '  box-shadow: ' . $box_shadow . ';';
        }

        $this->addCss($box_shadow, $scope);
    }

    protected function buildBackgroundImageCssRow($image_row, $legacy)
    {

        $bg_image  = 'url("' . $this->context->getImageUrl() . $image_row['image'] . '")';
        $bg_image .= ' ' . $image_row['repeat'];

        if ($image_row['position'] == 'custom') {
            $bg_image .= ' ' . $image_row['position_x'] . $image_row['position_x_metric'] . ' ' . $image_row['position_y'] . $image_row['position_y_metric'];
        } else {
            $bg_image .= ' ' . $image_row['position'];
        }

        if ($image_row['size'] != 'auto' && !$legacy) {
            if ($image_row['size'] == 'custom') {
                if ($image_row['size_x'] == 0) {
                    $size_x = 'auto';
                } else {
                    $size_x = $image_row['size_x'] . $image_row['size_x_metric'];
                }
                if ($image_row['size_y'] == 0) {
                    $size_y = 'auto';
                } else {
                    $size_y = $image_row['size_y'] . $image_row['size_y_metric'];
                }
                $bg_image .= ' / ' . $size_x . ' ' . $size_y;
            } else {
                $bg_image .= ' / ' . $image_row['size'];
            }
        }

        return $bg_image;
    }

    protected function buildBackgroundGradientCssRow($gradient_row, $vendor)
    {
        $color_stops = '';
        foreach ($gradient_row['colors'] as $gradient_colors) {
            $rbg = TB_Utils::hex2rgb($gradient_colors['color']);
            $offset = '';
            if (!$gradient_colors['offset_auto']) $offset = ' ' . $gradient_colors['offset'] . '%';
            $color_stops .= 'rgba(' . $rbg[0] . ', ' . $rbg[1] . ', ' . $rbg[2] . ', ' . ($gradient_colors['opacity']/100) . ')' . $offset . ', ';
        }
        $color_stops = trim($color_stops, ', ');

        if ($gradient_row['type'] == 'linear') {
            if ($vendor == 'w3c') {
                $gradient = 'linear-gradient(' . (abs($gradient_row['angle'] - 450) % 360) . 'deg, ' . $color_stops . ')';
            } else {
                $gradient = '-' . $vendor. '-linear-gradient(' . $gradient_row['angle'] . 'deg, ' . $color_stops . ')';
            }
        } else {
            if ($vendor == 'w3c') {
                $gradient = 'radial-gradient(ellipse at center, ' . $color_stops . ')';
            } else {
                $gradient = '-' . $vendor. '-radial-gradient(center, ellipse cover, ' . $color_stops . ')';
            }
        }

        $gradient .= ' ' . $gradient_row['repeat'];

        if ($gradient_row['position'] == 'custom') {
            $gradient .= ' ' . $gradient_row['position_x'] . $gradient_row['position_x_metric'] . ' ' . $gradient_row['position_y'] . $gradient_row['position_y_metric'];
        } else {
            $gradient .= ' ' . $gradient_row['position'];
        }

        return $gradient;
    }

    public function buildBackgroundCss($settings, $scope)
    {
        if (!isset($settings['background'])) {
            return;
        }

        $solid_color = trim($settings['background']['solid_color']);
        $solid_color_legacy = trim($settings['background']['solid_color']);

        if ($solid_color != '' && $settings['background']['solid_color_opacity'] < 100) {
            $solid_color = 'rgba(' . TB_Utils::hex2rgb($solid_color, true)    . ', ' . ($settings['background']['solid_color_opacity']/100) . ')';
        }

        if (empty($settings['background']['rows']) || !is_array($settings['background']['rows'])) {
            if ($solid_color != '' && $settings['background']['solid_color_opacity'] < 100) {
                $this->addCss('  background: ' . $solid_color_legacy . ";\n" . '  background: ' . $solid_color . ';', $scope);
            } else
            if ($solid_color != '') {
                $this->addCss('  background: ' . $solid_color . ';', $scope);
            }

            return;
        }

        $css_row                  = '';
        $css_content              = '';
        $has_row_gradients        = false;
        $has_content_gradients    = false;
        $has_container_property   = false;
        $row_images               = 0;
        $content_images           = 0;
        $css_row_first_image      = '';
        $css_content_first_image  = 0;
        $bg_rows                  = $settings['background']['rows'];

        foreach ($bg_rows as $bg_row) {
            if ($bg_row['background_type'] == 'image') {
                if (!isset($bg_row['container']) || $bg_row['container'] == 'row') {
                    if (empty($css_row_first_image)) {
                        $css_row_first_image = $this->buildBackgroundImageCssRow($bg_row, true);
                    }
                    $row_images++;
                } else
                if ($bg_row['container'] == 'content') {
                    if (empty($css_content_first_image)) {
                        $css_content_first_image = $this->buildBackgroundImageCssRow($bg_row, true);
                    }
                    $content_images++;
                }
            } else

            if ($bg_row['background_type'] == 'gradient') {
                if (!isset($bg_row['container']) || $bg_row['container'] == 'row') {
                    $has_row_gradients = true;
                } else
                if ($bg_row['container'] == 'content') {
                    $has_content_gradients = true;
                }
            }

            if (isset($bg_row['container'])){
                $has_container_property = true;
            }
        }

        // Legacy background
        if (!empty($css_row_first_image) || !empty($solid_color_legacy)) {
            $css_row .= '  background: ' . trim($css_row_first_image . ' ' . $solid_color_legacy) . ";\n";
        }

        if (!empty($css_content_first_image)) {
            $css_content .= 'background: ' . $css_content_first_image . ";\n";
        }

        $all_vendors = array('o', 'ms', 'moz', 'webkit', 'w3c');

        if ($has_container_property) {
            foreach (array('row', 'content') as $container) {
                if (!${"has_{$container}_gradients"} && ${"{$container}_images"} == 1) {
                    continue;
                }
                reset($all_vendors);
                $vendors = ${"has_{$container}_gradients"} ? $all_vendors: end($all_vendors);

                $result = $this->buildCssFromVendors((array) $vendors, $bg_rows, $solid_color, $container);
                if (!empty($result[0])) {
                    $css_row .= $result[0];
                }
                if (!empty($result[1])) {
                    $css_content .= $result[1];
                }
            }
        } else {
            $vendors = $has_row_gradients ? $all_vendors: end($all_vendors);
            $result = $this->buildCssFromVendors((array) $vendors, $bg_rows, $solid_color);
            if (!empty($result[0])) {
                $css_row .= $result[0];
            }
        }

        $css_row     .= '  background-size: ';
        $css_content .= '  background-size: ';

        foreach ($bg_rows as $bg_row) {
            if (!isset($bg_row['container']) || $bg_row['container'] == 'row') {
              if ($bg_row['size'] == 'custom') {
                  if ($bg_row['size_x'] == 0) {
                      $size_x = 'auto';
                  } else {
                      $size_x = $bg_row['size_x'] . $bg_row['size_x_metric'];
                  }
                  if ($bg_row['size_y'] == 0) {
                      $size_y = 'auto';
                  } else {
                      $size_y = $bg_row['size_y'] . $bg_row['size_y_metric'];
                  }
                  $css_row .= $size_x . ' ' . $size_y . ', ';
              } else {
                  $css_row .= $bg_row['size'] . ', ';
              }
            } else
            if ($bg_row['container'] == 'content') {
              if ($bg_row['size'] == 'custom') {
                  if ($bg_row['size_x'] == 0) {
                      $size_x = 'auto';
                  } else {
                      $size_x = $bg_row['size_x'] . $bg_row['size_x_metric'];
                  }
                  if ($bg_row['size_y'] == 0) {
                      $size_y = 'auto';
                  } else {
                      $size_y = $bg_row['size_y'] . $bg_row['size_y_metric'];
                  }
                  $css_content .= $size_x . ' ' . $size_y . ', ';
              } else {
                  $css_content .= $bg_row['size'] . ', ';
              }
            }
        }

        $css_row = rtrim($css_row, ', ') . ';';
        $css_content = rtrim($css_content, ', ') . ';';

        $css_row     .= '  background-attachment: ';
        $css_content .= '  background-attachment: ';

        foreach ($bg_rows as $bg_row) {
            if (!isset($bg_row['container']) || $bg_row['container'] == 'row') {
                $css_row .= $bg_row['attachment'] . ', ';
            } else
            if ($bg_row['container'] == 'content') {
                $css_content .= $bg_row['attachment'] . ', ';
            }
        }

        $css_row = rtrim($css_row, ', ') . ';';
        $css_content = rtrim($css_content, ', ') . ';';

        $this->addCss($css_row, $scope);
        $this->addCss($css_content, $scope . ' > .row');
    }

    protected function buildCssFromVendors(array $vendors, array $bg_rows, $solid_color, $container = null)
    {
        $css_row = '';
        $css_content = '';

        foreach ($vendors as $vendor) {
            $css_row_temp = '';
            $css_content_temp = '';

            foreach ($bg_rows as $bg_row) {
                $css_temp = '';

                if ($bg_row['background_type'] == 'image') {
                    $css_temp = $this->buildBackgroundImageCssRow($bg_row, false);
                } else
                if ($bg_row['background_type'] == 'gradient') {
                    $css_temp = $this->buildBackgroundGradientCssRow($bg_row, $vendor);
                }
                $css_temp .= ', ';

                if (null == $container || $container == 'row' && $bg_row['container'] == 'row') {
                    $css_row_temp .= $css_temp;
                } else
                if ($container == 'content' && $bg_row['container'] == 'content') {
                    $css_content_temp .= $css_temp;
                }
            }

            if (!empty($css_row_temp)) {
                $css_row .= '  background: ' . trim($css_row_temp, ', ') . ' ' . $solid_color. '; ' . "\n";
            }

            if (!empty($css_content_temp)) {
                $css_content .= '  background: ' . trim($css_content_temp, ', '). '; ' . "\n";
            }
        }

        return array($css_row, $css_content);
    }
}