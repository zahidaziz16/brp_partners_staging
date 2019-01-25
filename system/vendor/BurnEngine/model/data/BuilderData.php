<?php

class BuilderData
{
    public static function getBackgroundImageRow()
    {
        return array(
            'background_type'     => 'image',
            'image'               => '',
            'position'            => 'center',
            'position_x_metric'   => 'px',
            'position_y_metric'   => 'px',
            'position_x'          => 0,
            'position_y'          => 0,
            'repeat'              => 'no-repeat',
            'size'                => 'auto',
            'size_x_metric'       => 'px',
            'size_y_metric'       => 'px',
            'size_x'              => 1,
            'size_y'              => 1,
            'attachment'          => 'scroll'
        );
    }

    public static function getBackgroundGradientRow()
    {
        return array(
            'background_type'     => 'gradient',
            'type'                => 'linear',
            'angle'               => 0,
            'position'            => 'center',
            'position_x_metric'   => 'px',
            'position_y_metric'   => 'px',
            'position_x'          => 0,
            'position_y'          => 0,
            'repeat'              => 'no-repeat',
            'size'                => 'auto',
            'size_x_metric'       => 'px',
            'size_y_metric'       => 'px',
            'size_x'              => 0,
            'size_y'              => 0,
            'attachment'          => 'scroll',
            'colors'              => array()
        );
    }

    public static function getBoxShadowRow()
    {
        return array(
            'size_x'   => 0,
            'size_y'   => 0,
            'angle'    => 0,
            'distance' => 0,
            'inner'    => 0,
            'blur'     => 0,
            'spread'   => 0,
            'color'    => '#000000',
            'opacity'  => 1
        );
    }

    public static function getBackgroundColorRow()
    {
        return array(
            'color'       => '#000000',
            'offset_auto' => 1,
            'offset'      => 100,
            'opacity'     => 100
        );
    }

    public static function getBorderRow()
    {
        return array(
            'width'   => 0,
            'style'   => 'solid',
            'color'   => '#000000',
            'opacity' => 100
        );
    }
}