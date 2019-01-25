<?php

require_once tb_modification(dirname(__FILE__) . '/dbHelper.php');
require_once tb_modification(DIR_SYSTEM . 'vendor/stories/ImageManipulator.php');
if (!class_exists('URLify')) {
    require_once dirname(__FILE__) . '/URLify.php';
}

class ModelStoriesModel extends Model
{
    /**
     * @var DbHelper
     */
    protected $dbHelper;

    protected $group_field_name = 'group';

    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
        $this->dbHelper = new DbHelper($registry->get('db'), DB_PREFIX);
        if (version_compare(VERSION, '2.0.0.0') > 0) {
            $this->group_field_name = 'code';
        }
    }

    /**
     * @param string $name
     * @return ModelStoriesIndex|ModelStoriesTag|Model
     */
    protected function getModel($name = 'index')
    {
        if (false === strpos($name, '/')) {
            $name = 'stories/' . $name;
        }

        $model_full_name = 'model_' . str_replace('/', '_', $name);

        if (!$this->registry->has($model_full_name)) {
            $this->load->model($name);
        }

        return $this->registry->get($model_full_name);
    }

    public function getSettings($store_id = 0, $key = null)
    {
        static $settings = array();

        if (isset($settings[$store_id])) {
            return $settings[$store_id];
        }

        if ($store_id == 0) {
            $settings[$store_id] = $this->config->get('stories_settings');
        } else {
            $settings[$store_id] = array();
            if ($row = $this->dbHelper->getRecord('setting', array('store_id' => $store_id, 'key' => 'stories_settings'))) {
                $settings[$store_id] = $this->decodeValue($row['value']);
            }
        }

        return null === $key ? $settings[$store_id] : $settings[$store_id][$key];
    }

    public function editSetting($key, $value, $store_id = 0)
    {
        $this->dbHelper->delete('setting', array(
            'store_id'              => $store_id,
            $this->group_field_name => 'stories',
            'key'                   => $key
        ));

        if ($value) {
            $this->dbHelper->insert('setting', array(
                'store_id'              => $store_id,
                $this->group_field_name => 'stories',
                'key'                   => $key,
                'value'                 => $this->encodeValue($value),
                'serialized'            => is_array($value) ? 1 : 0
            ));
        }
    }

    protected function encodeValue($value)
    {
        if (is_scalar($value)) {
            return($value);
        }

        return !version_compare(VERSION, '2.1.0.0', '>=') ? serialize($value) : json_encode($value);
    }

    protected function decodeValue($value)
    {
        return !version_compare(VERSION, '2.1.0.0', '>=') ? unserialize($value) : json_decode($value, true);
    }

    public function getDefaultLanguage($key = null)
    {
        $code = $this->dbHelper->getValue('setting', 'value', array('key' => 'config_language'));
        $language = $this->dbHelper->getRecord('language', array('code' => $code));

        return null === $key ? $language : $language[$key];
    }

    public function resizeImageAdvanced($filename, $width, $height, $method = 'fit')
    {
        if (!is_file(DIR_IMAGE . $filename)) {
            return '';
        }

        $info = pathinfo($filename);
        $extension = $info['extension'];

        $old_image = $filename;

        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '-' . $method .'.' . $extension;

        if (!is_file(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!file_exists(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig > $width || $height_orig > $height) {
                $image = new TB_ImageManipulator(DIR_IMAGE . $old_image);
                $image->resize($width, $height, array('method' => $method));
                $image->save(DIR_IMAGE . $new_image);
            } else {
                $new_image = $old_image;
            }
        }

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            return $this->config->get('config_ssl') . 'image/' . $new_image;
        } else {
            return $this->config->get('config_url') . 'image/' . $new_image;
        }
    }

    public function getFoundRows()
    {
        return $this->dbHelper->getFoundRows();
    }
}