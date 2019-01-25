<?php

class TB_DummyController extends Controller
{
    public function getChildController($child, $args = array())
    {
        return parent::getChild($child, $args);
    }
}