<?php

return array(
    'label'   => 'Return request form',
    'route'   => $gteOc2 ? 'account/return/add' : 'account/return/insert',
    'display' => true,
    'ssl'     => true,
    'merge_global' => true
);