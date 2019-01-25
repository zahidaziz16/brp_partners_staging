<?php

class Newsletter_Catalog_NewsletterController extends TB_CatalogController
{
    public function subscribe()
    {
        if(empty($this->request->post['email']) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->sendJsonError($this->extension->translate('text_invalid_email'));
        }

        /** @var Newsletter_Catalog_DefaultModel $defaultModel */
        $defaultModel = $this->getModel('default');
        $settings = $defaultModel->getSettings();

        $name = '';
        if (!empty($this->request->post['name'])) {
            $name = trim(preg_replace('/\s\s+/', ' ', strip_tags((string) $this->request->post['name'])));
        }

        if ($settings['show_name'] && empty($name)) {
            return $this->sendJsonError($this->extension->translate('text_invalid_name'));
        }

        if (!$defaultModel->emailExists($this->request->post['email']) && ($settings['subscribe_customers'] || !$defaultModel->customerExists($this->request->post['email']))) {
            $defaultModel->subscribe(array(
                'email' => $this->request->post['email'],
                'name'  => $name
            ));
        }

        return $this->sendJsonSuccess($this->extension->translate('text_subscribed'));
    }
}