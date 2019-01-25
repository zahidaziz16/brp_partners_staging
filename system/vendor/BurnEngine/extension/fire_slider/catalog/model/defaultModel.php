<?php

class FireSlider_Catalog_DefaultModel extends TB_ExtensionModel
{
    public function generateSliderMarkup($slider, $uid)
    {
        $this->filterSlider($slider);

        foreach ($slider['slides'] as &$slide) {
            foreach ($slide['layers'] as &$layer) {
                if (!empty($layer['image_src'])  && file_exists(DIR_IMAGE . $layer['image_src'])) {
                    if (!isset($layer['image_width']) || empty($layer['image_width']) || !isset($layer['image_height']) || empty($layer['image_height'])) {
                        $dimensions = getimagesize(DIR_IMAGE . $layer['image_src']);
                        if (!isset($layer['image_width']) || empty($layer['image_width'])) {
                            $layer['image_width'] = $dimensions[0];
                        }
                        if (!isset($layer['image_height']) || empty($layer['image_height'])) {
                            $layer['image_height'] = $dimensions[1];
                        }
                    }
                    $layer['image_src'] = $this->context->getImageUrl() . $layer['image_src'];
                } else {
                    $layer['image_src'] = '';
                }
            }
        }

        return $this->extension->fetchTemplate('slider', array('slider' => $slider, 'uid' => $uid));
    }

    public function generateSliderStyles($slider, $uid)
    {
        $this->filterSlider($slider);

        return $this->extension->fetchTemplate('styles', array('slider' => $slider, 'uid' => $uid));
    }

    protected function filterSlider(&$slider)
    {
        if (!isset($slider['layout'])) {
            var_dump($slider);
            throw new Exception('Non-existing slider layout');
        }
        $slider['css_class']  = '';
        $slider['css_class'] .= $slider['layout'];


        $slider['frame_attributes']  = '';

        if ($slider['layout'] == 'fixed') {
            $slider['frame_attributes'] .= 'data-mightyslider="';
            $slider['frame_attributes'] .= $slider['width'] != 0 ? 'width: ' . $slider['width'] . ', ' : '';
            $slider['frame_attributes'] .= $slider['height'] != 0 ? 'height: ' . $slider['height'] . ', ' : '';
            $slider['frame_attributes']  = ' ' . trim($slider['frame_attributes'], ', ') . '"';
        }

        if ($slider['layout'] == 'fullwidth' && $slider['autoscale']) {
            $slider['frame_attributes'] .= 'data-mightyslider="';
            $slider['frame_attributes'] .= $slider['responsive_width'] != 0 ? 'width: ' . $slider['responsive_width'] . ', ' : '';
            $slider['frame_attributes'] .= $slider['height'] != 0 ? 'height: ' . $slider['height'] . ', ' : '';
            $slider['frame_attributes']  = ' ' . trim($slider['frame_attributes'], ', ') . '"';
        }

        if ($slider['layout'] == 'fullscreen' && $slider['autoscale']) {
            $slider['frame_attributes'] .= 'data-mightyslider="';
            $slider['frame_attributes'] .= $slider['responsive_width'] != 0 ? 'width: ' . $slider['responsive_width'] . ', ' : '';
            $slider['frame_attributes'] .= $slider['responsive_height'] != 0 ? 'height: ' . $slider['responsive_height'] . ', ' : '';
            $slider['frame_attributes']  = ' ' . trim($slider['frame_attributes'], ', ') . '"';
        }

        $slide_num = 1;

        foreach ($slider['slides'] as &$slide) {

            $slide['cover'] = html_entity_decode($slide['cover'], ENT_COMPAT, 'UTF-8');
            if (!empty($slide['cover'])  && file_exists(DIR_IMAGE . $slide['cover'])) {
                $slide['cover'] = $this->context->getImageUrl() . $slide['cover'];
            } else {
                $slide['cover'] = '';
            }

            $slide['attributes']  = '';
            $slide['attributes'] .= 'id="{{uid}}_slide_' . $slide_num . '"';
            $slide['attributes'] .= ' class="ms_slide" data-mightyslider="';
            $slide['attributes'] .= $slide['cover'] || $slide['type'] == 'video' || $slide['type'] == 'iframe' ? 'type: \'' . $slide['type'] . '\', ' : '';
            $slide['attributes'] .= $slide['cover'] ? 'cover: \'' . TB_Utils::escapeHtmlImage($slide['cover']) . '\', ' : '';
            $slide['attributes'] .= $slide['viewport'] ? 'viewport: \'' . $slide['viewport'] . '\', ' : '';
            $slide['attributes'] .= $slide['type'] == 'video' ? 'video: \'' . $slide['video'] . '\', ' : '';
            $slide['attributes'] .= $slide['type'] == 'iframe' ? 'source: \'' . $slide['source'] . '\', ' : '';
            $slide['attributes'] .= 'id: \'{{uid}}_slide_' . $slide_num . '\'';
            $slide['attributes'] .= '"';

            $layer_num   = 1;
            $layer_count = count($slide['layers']);

            foreach($slide['layers'] as &$layer) {

                $layer['attributes']       = '';
                $layer['attributes']      .= ' id="{{uid}}_slide_' . $slide_num . '_layer_' . $layer_num . '"';
                $layer['attributes']      .= $layer['class'] ? ' class="mSCaption ' . $layer['class'] . '"' : ' class="mSCaption"';
                $layer_options    = '';
                // $layer_options   .= $layer['loop'] ? 'loop: ' . $layer['loop'] . ', ' : '';
                // $layer_options   .= $layer['no_delay'] ? 'dontDelayOnRepeat: ' . $layer['no_delay'] . ', ' : '';
                // $layer_options   .= $layer['start_keyframe'] ? 'startAtOnRepeat: ' . $layer['start_keyframe'] . ', ' : '';
                $layer['attributes']      .= $layer_options ? ' data-mightyslider="' . trim($layer_options, ', ') . '"' : '';

                $layer_keyframes  = '';
                foreach ($layer['keyframes'] as $keyframe) {
                    $layer_keyframes .= '{';
                    $layer_keyframes .= $keyframe['delay'] ? 'delay: ' . $keyframe['delay'] . ', ' : '';
                    $layer_keyframes .= $keyframe['speed'] ? 'speed: ' . $keyframe['speed'] . ', ' : '';
                    $layer_keyframes .= $keyframe['easing'] ? 'easing: \'' . $keyframe['easing'] . '\', ' : '';

                    $style = $keyframe['style'];
                    $layer_keyframes_style  = '';
                    $layer_keyframes_style .= strlen($style['top'])     ? 'top: ' . $style['top'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['right'])   ? 'right: ' . $style['right'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['bottom'])  ? 'bottom: ' . $style['bottom'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['left'])    ? 'left: ' . $style['left'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['opacity']) ? 'opacity: ' . $style['opacity'] . ', ' : '';

                    $layer_keyframes_style_transform  = '';
                    // $layer_keyframes_style_transform .= strlen($style['x']) ? 'translateX(' . $style['x'] . ') ' : '';
                    // $layer_keyframes_style_transform .= strlen($style['y']) ? 'translateY(' . $style['y'] . ') ' : '';
                    $layer_keyframes_style_transform .= strlen($style['scale']) ? 'scale(' . $style['scale'] . ') ' : '';
                    $layer_keyframes_style_transform .= strlen($style['rotate']) ? 'rotate(' . $style['rotate'] . 'deg) ' : '';
                    // $layer_keyframes_style_transform .= strlen($style['rotate_x']) ? 'rotateX(' . $style['rotate_x'] . 'deg) ' : '';
                    // $layer_keyframes_style_transform .= strlen($style['rotate_y']) ? 'rotateY(' . $style['rotate_y'] . 'deg) ' : '';
                    $layer_keyframes_style .= $layer_keyframes_style_transform ? 'transform: \'' . trim($layer_keyframes_style_transform) . '\', ' : '';
                    $layer_keyframes_style  = trim($layer_keyframes_style, ', ');
                    $layer_keyframes .= $layer_keyframes_style ? 'style: {' . $layer_keyframes_style . '}' : '';
                    $layer_keyframes  = trim($layer_keyframes, ', ');
                    $layer_keyframes .= '}, ';
                }

                /*
                if ($layer['dup_initial_pos'] && $layer['loop'] && !empty($layer['keyframes'])) {
                    $layer_keyframes .= '{';
                    $style = $layer['style'];
                    $layer_keyframes_style  = '';
                    $layer_keyframes_style .= strlen($style['top']) ? 'top: ' . $style['top'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['right']) ? 'right: ' . $style['right'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['bottom']) ? 'bottom: ' . $style['bottom'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['left']) ? 'left: ' . $style['left'] . ', ' : '';
                    $layer_keyframes_style .= strlen($style['opacity']) ? 'opacity: ' . $style['opacity'] . ', ' : '';
                    $layer_keyframes_style_transform  = '';
                    $layer_keyframes_style_transform .= strlen($style['x']) ? 'translateX(' . $style['x'] . ') ' : '';
                    $layer_keyframes_style_transform .= strlen($style['y']) ? 'translateY(' . $style['y'] . ') ' : '';
                    $layer_keyframes_style_transform .= strlen($style['scale']) ? 'scale(' . $style['scale'] . ') ' : '';
                    $layer_keyframes_style_transform .= strlen($style['rotate']) ? 'rotate(' . $style['rotate'] . 'deg) ' : '';
                    $layer_keyframes_style_transform .= strlen($style['rotate_x']) ? 'rotateX(' . $style['rotate_x'] . 'deg) ' : '';
                    $layer_keyframes_style_transform .= strlen($style['rotate_y']) ? 'rotateY(' . $style['rotate_y'] . 'deg) ' : '';
                    $layer_keyframes_style .= $layer_keyframes_style_transform ? 'transform: \'' . trim($layer_keyframes_style_transform) . '\', ' : '';
                    $layer_keyframes_style  = trim($ms_layer_keyframes_style, ', ');
                    $layer_keyframes .= $layer_keyframes_style ? 'style: {' . $layer_keyframes_style . '}' : '';
                    $layer_keyframes  = trim($layer_keyframes, ', ');
                    $layer_keyframes .= '}, ';
                }
                */
                $layer_keyframes  = trim($layer_keyframes, ', ');

                $layer['attributes'] .= $layer_keyframes ? ' data-msanimation="' . $layer_keyframes . '"' : '';
                $layer['attributes'] = trim($layer['attributes']);

                $layer['style']['top']    = $layer['keyframes'][0]['style']['top'];
                $layer['style']['bottom'] = $layer['keyframes'][0]['style']['bottom'];
                $layer['style']['left']   = $layer['keyframes'][0]['style']['left'];
                $layer['style']['right']  = $layer['keyframes'][0]['style']['right'];

                if ($layer['keyframes'][0]['animation'] == 'top') {
                    if (strlen($layer['keyframes'][0]['style']['top'])) {
                        $layer['style']['top']    -= $layer['style']['position_modify'];
                    } else
                    if (strlen($layer['keyframes'][0]['style']['bottom'])) {
                        $layer['style']['bottom'] += $layer['style']['position_modify'];
                    }
                }

                if ($layer['keyframes'][0]['animation'] == 'right') {
                    if (strlen($layer['keyframes'][0]['style']['right'])) {
                        $layer['style']['right'] -= $layer['style']['position_modify'];
                    } else
                    if (strlen($layer['keyframes'][0]['style']['left'])) {
                        $layer['style']['left']  += $layer['style']['position_modify'];
                    }
                }

                if ($layer['keyframes'][0]['animation'] == 'bottom') {
                    if (strlen($layer['keyframes'][0]['style']['bottom'])) {
                        $layer['style']['bottom'] -= $layer['style']['position_modify'];
                    } else
                    if (strlen($layer['keyframes'][0]['style']['top'])) {
                        $layer['style']['top']    += $layer['style']['position_modify'];
                    }
                }

                if ($layer['keyframes'][0]['animation'] == 'left') {
                    if (strlen($layer['keyframes'][0]['style']['left'])) {
                        $layer['style']['left']  -= $layer['style']['position_modify'];
                    } else
                    if (strlen($layer['keyframes'][0]['style']['right'])) {
                        $layer['style']['right'] += $layer['style']['position_modify'];
                    }
                }

                $layer['css_styles']  = '';
                $layer['css_styles'] .= 'z-index: ' . ($layer_count - $layer_num + 1) . ";\n";
                $layer['css_styles'] .= strlen($layer['style']['top'])    ? ' top: '    . $layer['style']['top'] . "px;\n" : ' top: auto !important;';
                $layer['css_styles'] .= strlen($layer['style']['right'])  ? ' right: '  . $layer['style']['right'] . "px;\n" : ' right: auto !important;';
                $layer['css_styles'] .= strlen($layer['style']['bottom']) ? ' bottom: ' . $layer['style']['bottom'] . "px;\n" : ' bottom: auto !important;';
                $layer['css_styles'] .= strlen($layer['style']['left'])   ? ' left: '   . $layer['style']['left'] . "px;\n" : ' left: auto !important;';
                $layer['css_styles'] .= strlen($layer['html_width']) && $layer['html_width'] != 0 ? ' width: ' . $layer['html_width'] . $layer['html_width_metric'] . ($layer['html_width'] == 100 && $layer['html_width_metric'] == '%' ? '!important;' : '') . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['padding_top']) && $layer['style']['padding_top'] != 0 ? ' padding-top: ' . $layer['style']['padding_top'] . "px;\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['padding_right']) && $layer['style']['padding_right'] != 0 ? ' padding-right: ' . $layer['style']['padding_right'] . "px;\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['padding_bottom']) && $layer['style']['padding_bottom'] != 0 ? ' padding-bottom: ' . $layer['style']['padding_bottom'] . "px;\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['padding_left']) && $layer['style']['padding_left'] != 0 ? ' padding-left: ' . $layer['style']['padding_left'] . "px;\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['font_size']) ? ' font-size: ' . $layer['style']['font_size'] . "px;\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['line_height']) && $layer['style']['line_height'] != 0 ? ' line-height: ' . $layer['style']['line_height'] . "px;\n" : '';
                // $layer['css_styles'] .= ' text-align: ' . $layer['style']['text_align'] . ";\n";
                $layer['css_styles'] .= ' text-align: ' . $layer['style']['text_align'] . ";\n";
                $layer['css_styles'] .= $layer['style']['text_transform'] != 'none' ? ' text-transform: ' . $layer['style']['text_transform'] . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['opacity']) ? ' opacity: ' . $layer['style']['opacity'] . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['color']) ? 'color: ' . $layer['style']['color'] . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['bg_color']) ? 'background-color: ' . $layer['style']['bg_color'] . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['style']['border_radius']) ? 'border-radius: ' . $layer['style']['border_radius'] . "px;\n" : '';

                $layer['css_styles'] .= strlen($layer['transform_origin']) ? ' transform-origin: ' . $layer['transform_origin'] . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['transform_origin']) ? ' -ms-transform-origin: ' . $layer['transform_origin'] . ";\n" : '';
                $layer['css_styles'] .= strlen($layer['transform_origin']) ? ' -webkit-transform-origin: ' . $layer['transform_origin'] . ";\n" : '';

                $layer_styles_transform  = '';
                $layer_styles_transform .= strlen($layer['style']['scale']) ? 'scale(' . $layer['style']['scale'] . ') ' : '';;
                $layer_styles_transform .= strlen($layer['style']['rotate']) ? 'rotate(' . $layer['style']['rotate'] . 'deg) ' : '';;
                //$layer_styles_transform .= strlen($layer['style']['rotate_x']) ? 'rotateX(' . $layer['style']['rotate_x'] . 'deg) ' : '';;
                //$layer_styles_transform .= strlen($layer['style']['rotate_y']) ? 'rotateY(' . $layer['style']['rotate_y'] . 'deg) ' : '';;

                $layer['css_styles'] .= $layer_styles_transform ? '  transform: ' . trim($layer_styles_transform) . ";\n" : '';
                $layer['css_styles'] .= $layer_styles_transform ? '  -ms-transform: ' . trim($layer_styles_transform) . ";\n" : '';
                $layer['css_styles'] .= $layer_styles_transform ? '  -webkit-transform: ' . trim($layer_styles_transform) . ";\n" : '';

                $layer_num++;
            }

            $slide_num++;
        }
    }
}