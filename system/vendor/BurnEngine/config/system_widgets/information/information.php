<?php

return array(
    'label'        => 'Information page',
    'route'        => 'information/information',
    'display'      => true,
    'ssl'          => false,
    'merge_global' => true,
    // Whether or not to load the settings/blocks for the GLOBAL scope record if it is present. The builder content area has no GLOBAL scope, but the style content wrapper does.
    // This is mainly useful when having default settings for the respective scope. You may want to apply the GLOBAL scope settings/blocks if present even when there are default ones,
    // when there is no record for the scope. If the respect_global_record key is not set, it is stored as 0 when exporting theme defaults.
    'respect_global_record' => 1
);