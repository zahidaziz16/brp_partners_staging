<?php

class TB_CatalogModuleAction
{
    protected $file;
   	protected $class;
   	protected $method;
   	protected $args = array();

   	public function __construct(TB_CatalogExtension $extension, $args)
    {
        $this->file   = $extension->getAreaDir() . '/controller/ModuleController.php';
        $this->class  = TB_Utils::camelize($extension->getName()) . '_Catalog_ModuleController';
        $this->method = 'index';
        $this->args   = $args;
   	}

   	public function getFile()
    {
   		return $this->file;
   	}

   	public function getClass()
    {
   		return $this->class;
   	}

   	public function getMethod()
    {
   		return $this->method;
   	}

   	public function getArgs()
    {
        return $this->args;
    }
}