<?php

class TB_AdminUrl
{
    private $default_connection = 'SSL';
    private $default_args = '';
    private $default_extension;
    private $default_store_id;
    /** @var  Url */
    private $parentUrl;
    private $basename;

    public function __construct($registry)
    {
        $this->parentUrl = $registry->get('url');
    }

    public function link($route, $args = '', $connection = null)
    {
        if (empty($connection)) {
            $connection = $this->default_connection;
        }

        if (!empty($this->default_args)) {
            if (false !== strpos($args, '#')) {
                $args_parts = explode('#', $args);
                $args = $args_parts[0] . '&' . ltrim($this->default_args, '&') . '#' . $args_parts[1];
            } else {
                $args .= '&' . ltrim($this->default_args, '&');
            }
        }

        return $this->parentUrl->link($route, $args, $connection);
    }

    public function generate($controller_action, $args = '', array $params = array())
    {
        $parts = explode('/', $controller_action);
        $controller = $parts[0];
        $action = isset($parts[1]) ? $parts[1] : 'index';

        $route = (version_compare(VERSION, '2.2.0.0', '>=') ? 'theme/' : 'module/') . $this->basename;
        $route = (version_compare(VERSION, '2.3.0.0', '>=') ? 'extension/' : '') . $route;
        $args_arr = array(
            'controller=' . $controller,
            'action='     . $action
        );

        if (!empty($params['extension'])) {
            $args_arr[] = 'extension=' . $params['extension'];
        } else {
            if (null !== $this->default_extension) {
                $args_arr[] = 'extension=' . $this->default_extension;
            }
        }

        if (isset($params['store_id']) && strlen($params['store_id'])) {
            $args_arr[] = 'store_id=' . (int) $params['store_id'];
        } else {
            if (null !== $this->default_store_id) {
                $args_arr[] = 'store_id=' . $this->default_store_id;
            }
        }

        if (is_array($args)) {
            $args = http_build_query($args);
        }

        $args .= '&' . implode('&', $args_arr);

        if (!empty($params['hash'])) {
            $args .= '#' . ltrim($params['hash'], '#');
        }

        return $this->link($route, $args);
    }

    public function generateJs($controller_action, $args = '', $params = array())
    {
        return str_replace('&amp;', '&', $this->generate($controller_action, $args, $params));
    }

    public function setBasename($basename)
    {
        $this->basename = $basename;
    }

    public function setDefaultExtension($extension)
    {
        $this->default_extension = $extension;
    }

    public function setDefaultStoreId($store_id)
    {
        $this->default_store_id = (int) $store_id;
    }

    public function setDefaultConnection($connection)
    {
        $this->default_connection = $connection;
    }

    public function setDefaultArgs($args)
    {
        $this->default_args = $args;
    }
}
 
