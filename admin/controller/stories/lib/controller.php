<?php

require_once DIR_SYSTEM . 'vendor/stories/controller.php';

class ControllerStoriesAdmin extends ControllerStories
{
    protected $error = array();
    protected $data = array();

    protected function setBreadcrumbs()
    {
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->link('stories/index'),
            'separator' => ' :: '
        );
    }

    protected function validatePermission($permission)
    {
        if (!$this->user->hasPermission('modify', $permission)) {
            $this->error['warning'] = $this->language->get('error_permission');

            return false;
        }

        return true;
    }

    protected function link($route, $args = '', $ssl = true)
    {
        return $this->url->link($route, $args . '&token=' . $this->getToken(), $ssl ? 'SSL' : 'NONSSL');
    }

    protected function getToken()
    {
        return $this->session->data['token'];
    }

    protected function isPost()
    {
        return $this->request->server['REQUEST_METHOD'] == 'POST';
    }

    protected function initFlatVarsSimple(array $default_vars, array $primary_vars, array $secondary_vars = array())
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

    protected function buildUrl($data, $allowed = array())
    {
        foreach($data as $key => $value) {
            if(null === $value || !strlen($value) || $allowed && !in_array($key, $allowed)) {
                unset($data[$key]);
            }
        }

        return http_build_query($data);
    }

    protected function renderOutput($template)
    {
        $this->data['gteOc2'] = $this->gteOc2();

        if ($this->gteOc2()) {
            $children = array(
                'header',
                'footer',
                'column_left'
            );
            foreach ($children as $name) {
                $this->data[$name] = $this->load->controller('common/' . $name);
            }
            $this->response->setOutput($this->load->view($template, $this->data));
        } else {
            $this->children = array(
                'common/header',
                'common/footer'
            );
            $this->template = $template;
            $this->data['column_left'] = '';
            $this->response->setOutput($this->render());
        }
    }
}