<?php

class TB_FormHelper
{
    public static function initFlatVars(array $vars, $form_scope, $model_settings, $request_vars)
    {
        $result = array();
        foreach ($vars as $key => $value) {
            if (isset($request_vars[$form_scope][$key])) {
                $result[$key] = $request_vars[$form_scope][$key];
            } else {
                if (isset($model_settings[$form_scope][$key])) {
                    $result[$key] = $model_settings[$form_scope][$key];
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return array($form_scope => $result);
    }

    public static function initFlatVarsSimple(array $default_vars, array $primary_vars, array $secondary_vars = array())
    {
        $result = array();
        foreach ($default_vars as $key => $value) {
            if (isset($primary_vars[$key])) {
                $result[$key] = $primary_vars[$key];
            } else
            if ($secondary_vars && isset($secondary_vars[$key])) {
                $result[$key] = $secondary_vars[$key];
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function initLangVars(array $vars, $form_scope, $model_settings, $request_vars, $languages)
    {
        $result = array();
        foreach ($languages as $language) {
            $code = $language['code'];
            foreach ($vars as $key => $value) {
                if (isset($request_vars[$form_scope][$code][$key])) {
                    $result[$code][$key] = $request_vars[$form_scope][$code][$key];
                } else {
                    if (isset($model_settings[$form_scope][$code][$key])) {
                        $result[$code][$key] = $model_settings[$form_scope][$code][$key];
                    } else {
                        $result[$code][$key] = $value;
                    }
                }
            }
        }

        return array($form_scope => $result);
    }

    public static function initLangVarsSimple(array $default_vars, array $model_vars, array $languages, array $request_vars = array())
    {
        $result = array();
        foreach ($languages as $language) {
            $code = $language['code'];
            if (!isset($result[$code])) {
                $result[$code] = array();
            }
            foreach ($default_vars as $key => $value) {
                if ($request_vars && isset($request_vars[$code][$key])) {
                    $result[$code][$key] = $request_vars[$code][$key];
                } else
                if (isset($model_vars[$code][$key])) {
                    $result[$code][$key] = $model_vars[$code][$key];
                } else {
                    $result[$code][$key] = $value;
                }
            }
        }

        return $result;
    }

    public static function transformLangVar($data, $key, $model_settings, $languages)
    {
        $result = array();
        list($var_name, $default_value) = each($data);
        foreach ($languages as $language) {
            $code = $language['code'];
            if (isset($model_settings[$key][$var_name][$code])) {
                $result[$code] = $model_settings[$key][$var_name][$code];
            } else {
                $result[$code] = $default_value;
            }
        }

        return $result;
    }
}