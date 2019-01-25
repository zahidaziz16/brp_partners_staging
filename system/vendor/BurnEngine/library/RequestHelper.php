<?php

class TB_RequestHelper
{
    public static function isAjaxRequest()
    {
        return (self::getHeader('X-Requested-With') && strtolower(self::getHeader('X-Requested-With')) == 'xmlhttprequest') ||
               (self::getHeader('x-requested-with') && strtolower(self::getHeader('x-requested-with')) == 'xmlhttprequest') ||
               (isset($_REQUEST['type']) && strtolower($_REQUEST['type']) == 'xmlhttprequest');
    }

    public static function getHeader($header)
    {
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (isset($_SERVER[$temp]) && !empty($_SERVER[$temp])) {
            return $_SERVER[$temp];
        }

        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) {
                return $headers[$header];
            }
        }

        return false;
    }

    public static function isRequestHTTPS()
    {
        $isSecure =
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) == 443);

        if ((!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }

        return $isSecure;
    }

    public static function redirect($url, $status = 302)
    {
        header('Status: ' . $status);
        header('Location: ' . str_replace('&amp;', '&', $url));

        exit();
    }

    public static function lteIe9()
    {
        static $result = null;

        if (null === $result && !empty($_SERVER['HTTP_USER_AGENT'])) {
            $result = preg_match('/(?i)msie [7-9]/',$_SERVER['HTTP_USER_AGENT']);
        }

        return $result;
    }
}