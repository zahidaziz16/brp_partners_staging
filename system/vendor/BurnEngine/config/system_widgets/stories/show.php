<?php

return array(
    'label'        => 'Article Page',
    'route'        => 'stories/show',
    'display'      => true,
    'ssl'          => false,
    'merge_global' => true,
    'widgets'      => array(
        0 => array(
            'label' => 'Page content',
            'slot'  => 'page_content',
            'areas' => 'content',
            'class'  => 'Theme_StoriesInfoPageContentSystemWidget',
            'locked' => true
        )
    )
);