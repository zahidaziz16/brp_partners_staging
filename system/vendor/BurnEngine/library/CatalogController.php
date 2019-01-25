<?php

class TB_CatalogController extends TB_Controller
{
    protected function getRouteByName($name)
    {
        return $this->extension->getRouteByName($name);
    }

    protected function renderTemplate($template, $all_children = true)
    {
        $children = $all_children ? array('footer', 'header') : array();

        if (substr($template, -4) != '.tpl' && !$this->engine->gteOc22()) {
            $template .= '.tpl';
        }

        if (!$this->engine->gteOc22()) {
            $template = $this->engine->getConfigTheme() . '/template/' . $template;
        }

        if ($this->engine->gteOc2()) {
            foreach ($children as $name) {
                $this->data[$name] = $this->load->controller('common/' . $name);
            }
            $this->response->setOutput($this->load->view($template, $this->data));
        } else {
            foreach ($children as &$child) {
                $child = 'common/' . $child;
            }
            $this->children = $children;
            $this->template = $template;
            $this->response->setOutput($this->render());
        }
    }
}