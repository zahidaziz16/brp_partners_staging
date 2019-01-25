<?php

class LiveSearch_Catalog_Extension extends TB_CatalogExtension
{
    public function configure()
    {
        $this->eventDispatcher->connect('core:beforeRouting', array($this, 'beforeRouting'));
        $this->eventDispatcher->connect('core:afterRouting',  array($this, 'afterRouting'));
        $this->eventDispatcher->connect('core:filterRoute',   array($this, 'filterRoute'));
        $this->eventDispatcher->connect('core:generateExternalCss',   array($this, 'generateExternalCss'));
    }

    public function beforeRouting()
    {
        $this->registerCatalogRoute(array(
            'name'       => 'live_search',
            'route'      => 'live_search',
            'controller' => 'live_search'
        ));
    }

    public function filterRoute(sfEvent $event, $route)
    {
        if ($route == 'live_search/search' || $route == 'live_search/seed') {
            $event['skipAfterRouteEvent'] = true;
        }

        return $route;
    }

    public function afterRouting()
    {
        /** @var LiveSearch_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');

        $settings = $defaultModel->getSettings();

        $this->themeData->addJavascriptVar('tb/live_search/show_image',        $settings['show_image']);
        $this->themeData->addJavascriptVar('tb/live_search/title_style',       $settings['title_style']);
        $this->themeData->addJavascriptVar('tb/live_search/max_results',       (int) $settings['max_results']);
        $this->themeData->addJavascriptVar('tb/live_search/min_length',        (int) $settings['min_length']);
        $this->themeData->addJavascriptVar('tb/live_search/highlight_results', (int) $settings['highlight_results']);
        $this->themeData->addJavascriptVar('tb/live_search/search_in_model',   (int) $settings['search_in']['model']);
        $this->themeData->addJavascriptVar('tb/live_search/show_model',        (int) $settings['show_model']);
        $this->themeData->addJavascriptVar('tb/url/live_search/search',        $this->engine->getOcUrl()->link('live_search/search', '', TB_RequestHelper::isRequestHTTPS()), false);
        $this->themeData->addJavascriptVar('tb/url/live_search/seed',          $this->engine->getOcUrl()->link('live_search/seed', '', TB_RequestHelper::isRequestHTTPS()), false);

        $this->themeData->registerJavascriptResource('javascript/typeahead.bundle.js', $this);
        $this->themeData->registerJavascriptResource('javascript/live_search.js', $this);

        $this->themeData->registerStylesheetResource('stylesheet/live_search.css', $this);
    }

    public function generateExternalCss(sfEvent $event)
    {
        /** @var LiveSearch_Catalog_DefaultModel $defaultModel */
        $defaultModel   = $this->getModel('default');
        $settings       = $defaultModel->getSettings();

        $css = '
        .tb_wt_header_search_system .twitter-typeahead .dropdown-menu {
            width: ' . $settings['dropdown_width'] . 'px;
        }
        ';

        /** @var TB_StyleBuilder $themeExtension */
        $styleBuilder = $event['styleBuilder'];
        $styleBuilder->addCss($css);
    }
}