<?php

class TB_Get
{
    /**
     * @var array|TB_Get[]
     */
    protected static $instances = array();

    /**
     * @var TB_Extension
     */
    protected $extension;

    protected function __construct(TB_Extension $extension)
    {
        $this->extension = $extension;
    }

    public static function getInstance(TB_Extension $extension)
    {
        if (!isset(self::$instances[$extension->getName()])) {
            self::$instances[$extension->getName()] = new self($extension);
        }

        return self::$instances[$extension->getName()];
    }

    public function __get($name)
    {
        if (substr($name, -10) == 'Controller') {
            return $this->getController(substr($name, 0, -10));
        }
    }

    /**
     * @param $name
     *
     * @return TB_Controller
     */
    protected function getController($name)
    {
        return $this->extension->getController($name);
    }
}