<?php

interface TB_Url
{
    public function link($route, $args = '', $connection = 'NONSSL');
    public function addRewrite($rewrite);
}