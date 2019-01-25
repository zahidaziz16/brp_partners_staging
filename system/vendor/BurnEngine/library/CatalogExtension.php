<?php

require_once TB_THEME_ROOT . '/library/PriceFormatter.php';

abstract class TB_CatalogExtension extends TB_Extension
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Url
     */
    protected $url;

    public function init()
    {
        $this->request = $this->engine->getOcRequest();
        $this->url     = $this->engine->getOcRegistry()->get('url');

        parent::init();
    }

    public function registerCatalogRoute($route)
    {
        $routeBag = $this->engine->getRoutesBag();
        if (!isset($routeBag[$this->getName()] )) {
            $routeBag[$this->getName()]  = array();
            $extensions_routes = array();
        } else {
            $extensions_routes = $routeBag[$this->getName()];
        }
        $extensions_routes[$route['name']] = $route;
        $routeBag[$this->getName()] = $extensions_routes;
    }


    public function getRouteByName($name)
    {
        $routesBag = $this->engine->getRoutesBag();

        return $routesBag[$this->getName()][$name]['route'];
    }

    public function getExtensionLink($route, $args = '', $connection = 'NONSSL')
    {
        return $this->url->link($this->getName() . '/' . $route, $args, $connection);
    }
}