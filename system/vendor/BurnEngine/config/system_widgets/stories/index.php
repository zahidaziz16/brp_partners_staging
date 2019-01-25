<?php

return array(
    'label'        => 'Articles Listing',
    'route'        => 'stories/index',
    'display'      => true,
    'ssl'          => false,
    'merge_global' => true,
    'widgets'      => array(
        0 => array(
            'label' => 'Page content',
            'slot'  => 'page_content',
            'areas' => 'content',
            'class'  => 'Theme_StoriesListPageContentSystemWidget',
            'locked' => true
        )
    )
);