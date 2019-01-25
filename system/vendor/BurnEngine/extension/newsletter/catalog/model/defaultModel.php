<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class Newsletter_Catalog_DefaultModel extends Newsletter_DefaultModel
{
    public function emailExists($email)
    {
        return $this->dbHelper->getRecord('burnengine_newsletter', array('email' => $email)) !== false;
    }

    public function subscribe($data)
    {
        $this->dbHelper->addRecord('burnengine_newsletter', array(
            'email'      => $data['email'],
            'name'       => $data['name'],
            'created_at' => date("Y-m-d H:i:s")
        ));
    }

    public function customerExists($email)
    {
        return $this->dbHelper->getRecord('customer', array('email' => $email)) !== false;
    }
}