<?php

class ControllerStories extends Controller
{
    protected function getRequestUrl()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
            $elements = explode(',', $host);
            $host = trim(end($elements));
        } else
            if (!$host = $_SERVER['HTTP_HOST']) {
                if (!$host = $_SERVER['SERVER_NAME']) {
                    $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
                }
            }

        return "http://" . trim($host) . $_SERVER['REQUEST_URI'];
    }

    protected function mergeLanguage($language)
    {
        $this->data = array_merge(
            $this->data,
            $this->language->load($language)
        );
    }

    protected function getArrayKey($key, $source = array(), $default = '')
    {
        return isset($source[$key]) ? $source[$key] : $default;
    }

    /**
     * @param string $name
     * @return ModelStoriesIndex|ModelStoriesTag|ModelStoriesSystem|Model
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

    protected function redirect($url, $status = 302)
    {
        header('Status: ' . $status);
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
        exit();
    }

    protected function gteOc2()
    {
        return version_compare(VERSION, '2.0.0.0') > 0;
    }
}

if (!function_exists('tb_modification')) {
    function tb_modification($filename)
    {
        if (array_key_exists('vqmod', $GLOBALS)) {
            global $vqmod;

            if (is_callable(array($vqmod, 'modCheck'))) {
                if (!function_exists('modification')) {
                    return $vqmod->modCheck($filename);
                }

                return $vqmod->modCheck(modification($filename));
            }
        }

        if(class_exists('VQMod')) {
            if (!function_exists('modification')) {
                return VQMod::modCheck($filename);
            }

            return VQMod::modCheck(modification($filename));
        }

        return !function_exists('modification') ? $filename : modification($filename);
    }
}
