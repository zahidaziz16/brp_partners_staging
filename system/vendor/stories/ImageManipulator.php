<?php

/*
 * This file is part of the Pavilion theme package.
 *
 * (c) ThemeBurn <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ImageManipulator
{
    protected $image;
    protected $imageC = '';
    protected $imageInfo = array();
    protected $jpgQuality;

    /**
     * @param string $image (path to image)
     * @throws Exception
     */
    public function __construct($image)
    {
        if(!file_exists($image)){
            throw new Exception("File does not exist: " . $image);
        }

        $this->image = $image;

        $data = getimagesize($image);

        $this->imageInfo['width']  = $data[0];
        $this->imageInfo['height'] = $data[1];
        $this->imageInfo['type']   = $data[2];
        $this->imageInfo['mime']   = $data['mime'];
        $this->imageInfo['bits']   = $data['bits'];
    }

    /**
     * @param int $max_width
     * @param int $max_height
     * @param array $options:
     * method:
     *    fit = Fits image into width and height while keeping original aspect ratio. Expect your image not to use the full area.
     *    crop = Crops image to fill the area while keeping original aspect ratio. Expect your image to get, well, cropped.
     *    fill = Fits image into the area without taking care of any ratios. Expect your image to get deformed.
     *
     * cropAreaLeftRight
     *    l = left
     *    c = center
     *    r = right
     *    array( x-coordinate, width)
     *
     * ropAreaBottomTop
     *    t = top
     *    c = center
     *    b = bottom
     *   array( y-coordinate, height)
     *
     * pgQuality
     *
     *
     * @throws Exception
     */
    public function resize($max_width, $max_height, array $options = array())
    {
        $options = array_replace(array(
            'method'            => 'fit',
            'cropAreaLeftRight' => 'c',
            'cropAreaBottomTop' => 'c',
            'jpgQuality'        => 75
        ), $options);

        $this->jpgQuality = $options['jpgQuality'];

        $width  = $this->imageInfo['width'];
        $height = $this->imageInfo['height'];

        $newImage_width  = $max_width  != 'auto' && $max_width < $width ? $max_width  : $width;
        $newImage_height = $max_height != 'auto' && $max_height < $height ? $max_height : $height;

        $srcX = 0;
        $srcY = 0;

        if ($max_width == 'auto') {
            $max_width = $width;
        }

        if ($max_height == 'auto') {
            $max_height = $height;
        }

        $ratioOfMaxSizes = $max_width / $max_height;

        if ($options['method'] == 'fit') {

            if($ratioOfMaxSizes >= $this->getRatioWidthToHeight()) {
                $max_width = $max_height * $this->getRatioWidthToHeight();
            } else {
                $max_height = $max_width * $this->getRatioHeightToWidth();
            }

            $newImage_width = $max_width;
            $newImage_height = $max_height;


        } else

        if ($options['method'] == 'crop') {

            if ($ratioOfMaxSizes > $this->getRatioWidthToHeight()) {
                $max_height = $max_width * $this->getRatioHeightToWidth();
            } else {
                $max_width = $max_height * $this->getRatioWidthToHeight();
            }

            if (is_array($options['cropAreaLeftRight'])) {
                $srcX = $options['cropAreaLeftRight'][0];

                if ($ratioOfMaxSizes > $this->getRatioWidthToHeight()) {
                    $width = $options['cropAreaLeftRight'][1];
                } else {
                    $width = $options['cropAreaLeftRight'][1] * $this->getRatioWidthToHeight();
                }
            } elseif ($options['cropAreaLeftRight'] == 'r') {
                $srcX = $width - (($newImage_width / $max_width) * $width);
            } elseif ($options['cropAreaLeftRight'] == 'c') {
                $srcX = ($width/2) - ((($newImage_width / $max_width) * $width) / 2);
            }

            if (is_array($options['cropAreaBottomTop'])) {
                $srcY = $options['cropAreaBottomTop'][0];

                if ($ratioOfMaxSizes > $this->getRatioWidthToHeight()) {
                    $height = $options['cropAreaBottomTop'][1] * $this->getRatioHeightToWidth();
                } else {
                    $height = $options['cropAreaBottomTop'][1];
                }
            } elseif ($options['cropAreaBottomTop'] == 'b') {
                $srcY = $height - (($newImage_height / $max_height) * $height);
            } elseif ($options['cropAreaBottomTop'] == 'c') {
                $srcY = ($height/2) - ((($newImage_height / $max_height) * $height) / 2);
            }
        }

        list($image_create_func, ) = $this->getFunctionNames();

        $imageC = imagecreatetruecolor($newImage_width, $newImage_height);
        $newImage = $image_create_func($this->image);

        if($this->getMimeExtension() == 'png') {
            imagealphablending($imageC, false);
            imagesavealpha($imageC, true);
            $transparent = imagecolorallocatealpha($imageC, 255, 255, 255, 127);
            imagefilledrectangle($imageC, 0, 0, $newImage_width, $newImage_height, $transparent);
        }

        imagecopyresampled($imageC, $newImage, 0, 0, $srcX, $srcY, $max_width, $max_height, $width, $height);
        imagedestroy($newImage);

        $this->imageC = $imageC;
    }

    public function save($filename)
    {
        list(, $image_save_func) = $this->getFunctionNames();

        if($image_save_func == 'imagejpeg') {
            $result = $image_save_func($this->imageC, $filename, $this->jpgQuality);
        } else {
            $result = $image_save_func($this->imageC, $filename);
        }

        if(!$result) {
            throw new Exception('Cannot save file ' . $filename);
        }
    }

    protected function getFunctionNames()
    {
        switch ($this->getMimeExtension()) {
            case 'png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                break;

            case 'gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                break;

            default:
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
        }

        return array($image_create_func, $image_save_func);
    }

    public function getImageInfo()
    {
        return $this->imageInfo;
    }

    public function getExtension()
    {
        $extension = image_type_to_extension($this->imageInfo['type'], false);
        $extension = str_replace('jpeg', 'jpg', $extension);

        return $extension;
    }

    public function getMimeExtension()
    {
        return substr(strrchr($this->imageInfo['mime'], '/'), 1);
    }

    /**
     * Gets ratio width:height
     * @return float
     */
    public function getRatioWidthToHeight()
    {
        return $this->imageInfo['width'] / $this->imageInfo['height'];
    }

    /**
     * Gets ratio height:width
     * @return float
     */
    public function getRatioHeightToWidth()
    {
        return $this->imageInfo['height'] / $this->imageInfo['width'];
    }
}