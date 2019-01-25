<?php

if (!defined('T_ML_COMMENT')) {
    define('T_ML_COMMENT', T_COMMENT);
} else {
    define('T_DOC_COMMENT', T_ML_COMMENT);
}

class MemoryStreamWrapper
{
    const WRAPPER_NAME = 'var';

    private static $_content;
    private $_position;

    public static function prepare($content)
    {
        if (!in_array(self::WRAPPER_NAME, stream_get_wrappers())) {
            stream_wrapper_register(self::WRAPPER_NAME, get_class());
        }
        self::$_content = $content;
    }

    public function stream_open()
    {
        $this->_position = 0;

        return true;
    }

    public function stream_read($count)
    {
        $ret = substr(self::$_content, $this->_position, $count);
        $this->_position += strlen($ret);

        return $ret;
    }

    public function stream_stat()
    {
        return array();
    }

    public function stream_eof()
    {
        return $this->_position >= strlen(self::$_content);
    }
}

class TB_ClassCacheGenerator
{
    protected static $abstract = '';
    protected static $concrete = '';

    protected static function stripComments($source)
    {
        $tokens = token_get_all($source);
        $ret = '';
        foreach ($tokens as $token) {
            if (is_string($token)) {
                $ret .= $token;
            } else {
                list($id, $text) = $token;

                switch ($id) {
                    case T_COMMENT:
                    case T_ML_COMMENT:
                    case T_DOC_COMMENT:
                        break;
                    default:
                        $ret .= $text;
                        break;
                }
            }
        }

        $php_code =  trim(str_replace(array("<?\n","\n?>"),array('',''),$ret));
        MemoryStreamWrapper::prepare($php_code);

        $result = php_strip_whitespace(MemoryStreamWrapper::WRAPPER_NAME . '://');

        return $result;
    }

    protected static function generateCache(array $folders)
    {
        foreach ($folders as $folder) {
            $files = sfFinder::type('file')
                ->not_name('/^Admin.*$/')
                ->name('/^[a-zA-Z]+.php$/')
                ->sort_by_name()
                ->in(TB_THEME_ROOT . '/' . $folder);

            foreach ($files as $file) {
                self::parseFile($file);
            }
        }
    }

    protected static function parseFile($file)
    {
        $contents = file_get_contents($file);
        $contents = self::stripComments($contents);
        $contents = preg_replace('/\n\s*\n/', "\n", $contents);
        preg_match('/.*?((interface|abstract|class) .*)/s', $contents, $matches);

        if (!preg_match('/class.*?extends/', trim($contents))) {
            self::$abstract .= $matches[1] . "\n\n";
        } else {
            self::$concrete .= $matches[1] . "\n\n";
        }
    }

    public static function cacheExists()
    {
        return is_file(TB_THEME_ROOT . '/config/data/class_cache.php');
    }

    public static function buildCache($store_id, $rebuild = false)
    {
        if (!$rebuild && is_file(TB_THEME_ROOT . '/config/data/class_cache_' . $store_id . '.php')) {
            return;
        }

        self::generateCache(array('library', 'model', 'catalog/model', 'catalog/plugin', 'widget'));
        self::parseFile(TB_THEME_ROOT . '/catalog/ThemeCatalogExtension.php');

        file_put_contents(TB_THEME_ROOT . '/config/data/class_cache_' . $store_id . '.php', trim("<?php\n\n " . self::$abstract . self::$concrete));
    }

    public static function deleteCache($store_id = null)
    {
        if (null !== $store_id) {
            if (is_file(TB_THEME_ROOT . '/config/data/class_cache_' . $store_id . '.php')) {
                unlink(TB_THEME_ROOT . '/config/data/class_cache_' . $store_id . '.php');
            }
        } else {
            $files = sfFinder::type('file')
                ->name('/^class_cache_[0-9]+.php$/')
                ->in(TB_THEME_ROOT . '/config/data');

            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}