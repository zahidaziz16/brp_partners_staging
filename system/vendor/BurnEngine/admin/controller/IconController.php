<?php

class Theme_Admin_IconController extends TB_AdminController
{
    public function getList()
    {
        $this->data['replace_callback'] = $this->request->get['replace_callback'];
        $this->data['close_callback'] = $this->request->get['close_callback'];

        $this->renderTemplate('icon_list');
    }
}