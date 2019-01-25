<?php

return array(
    'label'        => 'Paypal Express Confirm',
    'route'        => (version_compare(VERSION, '2.3.0.0', '>=') ? 'extension/' : '') . 'payment/pp_express/expressConfirm',
    'display'      => true,
    'ssl'          => false,
    'merge_global' => true,
);