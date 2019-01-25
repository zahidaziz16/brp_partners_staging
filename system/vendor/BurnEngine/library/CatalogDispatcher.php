<?php

require_once TB_THEME_ROOT . '/library/Engine.php';
require_once TB_THEME_ROOT . '/library/CatalogController.php';
require_once TB_THEME_ROOT . '/library/CatalogExtension.php';
require_once TB_THEME_ROOT . '/library/CatalogModuleAction.php';

class TB_CatalogDispatcher
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var TB_Engine
     */
    protected $engine;

    public function __construct(TB_Engine $engine)
    {
        $this->engine   = $engine;
        $this->registry = $engine->getOcRegistry();
    }

    public function dispatch()
    {
        $request         = $this->registry->get('request');
        $themeData       = $this->engine->getThemeData();
        $eventDispatcher = $this->engine->getEventDispatcher();

        $event = new sfEvent($this->registry, 'core:initUrl', array(
            'themeData' => $themeData,
            'request'   => $request,
            'url'       => $this->registry->get('url')
        ));
        $eventDispatcher->notify($event);

        if ($event->isProcessed() && $event->getReturnValue() instanceof TB_Url) {
            $this->registry->set('url', $event->getReturnValue());
        }

        $ocUrl = $this->doRouting($eventDispatcher, $request, $themeData);
        $callable = $this->doExtensionsRoutesDispatching($themeData->route);

        if (false !== $ocUrl) {
            $event = new sfEvent($this->registry, 'core:afterDispatch', array(
                'themeData' => $themeData,
                'request'   => $request,
                'route'     => $themeData->route
            ));
            $eventDispatcher->notify($event);
        }

        if ($event->isProcessed()) {
            $this->registry->get('response')->setOutput($event->getReturnValue());
            $this->registry->get('response')->output();
            exit;
        }

        if (false !== $callable && false !== call_user_func($callable)) {
            $this->registry->get('response')->output();
            exit;
        }

        if (null !== $ocUrl) {
            $this->registry->set('url', $ocUrl);
        }
    }

    protected function doRouting(sfEventDispatcher $eventDispatcher, Request $request, $themeData)
    {
        $event = new sfEvent($this->registry, 'core:beforeRouting', array('themeData' => $themeData, 'request' => $request));
        $eventDispatcher->notify($event);

        $ocUrl = null;

        if ($event->isProcessed()) {
            $route = $event->getReturnValue();
        } else {
            if (!($this->registry->get('url') instanceof TB_Url)) {
                // prevent double rewrite after common/seo_url.php action
                $ocUrl = clone $this->registry->get('url');
            }

            if (isset($request->get['_route_'])) {
                $request->get['_route_'] = rtrim($request->get['_route_'], '/');
            }

            $seo_action = $this->engine->getConfig('seo_action');
            if ($seo_action == 'startup/seo_url' && !$this->engine->gteOc22()) {
                $seo_action = 'common/seo_url';
            }

            $this->engine->getThemeExtension()->loadController($seo_action);

            if (isset($request->get['route'])) {
                $route = $request->get['route'];
            } else {
                $route = 'common/home';
            }
        }

        $event = new sfEvent($this->registry, 'core:filterRoute', array('tbData' => $themeData, 'request' => $request));
        $route = $eventDispatcher->filter($event, $route)->getReturnValue();

        if (isset($request->get['_route_'])) {
            unset($request->get['_route_']);
        }

        $request->get['route'] = $route;
        $themeData->route = $route;

        if (empty($event['skipAfterRouteEvent'])) {
            $event = new sfEvent($this->registry, 'core:afterRouting', array('tbData' => $themeData, 'request' => $request, 'route' => $route));
            $eventDispatcher->notify($event);
        } else {
            return false;
        }

        return $ocUrl;
    }

    public function doExtensionsRoutesDispatching($request_route)
    {
        $extension_name = '';
        $controller_name = '';
        $registered_routes = $this->engine->getRoutesBag()->exportVars();
        $action = 'index';
        foreach ($registered_routes as $extension => $routes) {
            foreach ($routes as $route) {
                if (0 === stripos($request_route, $route['route'])) {
                    $extension_name = $extension;
                    $controller_name = $route['controller'];
                    $action_name = trim(substr($request_route, strlen($route['route'])), '/');

                    if (!empty($action_name)) {
                        $action = $action_name;
                    }

                    break;
                }
            }
        }

        if (empty($extension_name) || empty($controller_name)) {
            return false;
        }

        $controller = $this->engine->getExtension($extension_name)->getController($controller_name);
        if (!method_exists($controller, $action)) {
            return false;
        }

        return array($controller, $action);
    }
}