<?php

class Theme_Admin_PaymentImagesPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'payment_images';
    }

    public function filterSettings(array &$payment_images)
    {
        $default_settings = array(
            'rows' => array()
        );
        $image_settings = array(
            'type'        => '',
            'file'        => '',
            'link_url'    => '',
            'lint_target' => ''
        );

        $payment_images = TB_FormHelper::initFlatVarsSimple($default_settings, $payment_images);
    }

    public function saveAlways()
    {
        return false;
    }

    public function saveData($post_data)
    {
        if (!isset($post_data[$this->getConfigKey()])) {
            return false;
        }

        if (!isset($post_data[$this->getConfigKey()]['rows']) || empty($post_data[$this->getConfigKey()]['rows'])) {
            $post_data[$this->getConfigKey()]['rows'] = array();
        }

        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }

    public function setDataForView(&$payment_images, TB_ViewDataBag $themeData)
    {
        foreach ($payment_images['rows'] as $key => &$values) {
            if ($values['type'] == 'image') {
                if ($values['file'] && file_exists(DIR_IMAGE . $values['file'])) {
                    $values['preview'] = $this->getOcModel('tool/image')->resize($values['file'], 50, 50);
                } else {
                    unset($payment_images['rows'][$key]);
                }
            } else {
                $values['preview'] = $this->getThemeModel()->getNoImage();
            }
        }
    }
}