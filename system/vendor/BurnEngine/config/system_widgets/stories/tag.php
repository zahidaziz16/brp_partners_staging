<?php

return array(
    'label'        => 'Articles by Tag',
    'route'        => 'stories/tag',
    'display'      => true,
    'ssl'          => false,
    'merge_global' => true,
    'widgets'      => array(
        0 => array(
            'label' => 'Tag description',
            'slot'  => 'tag_description',
            'areas' => 'intro, content, column_left, column_right',
            'locked' => false
        ),
        1 => array(
            'label' => 'Page content',
            'slot'  => 'page_content',
            'areas' => 'content',
            'class'  => 'Theme_StoriesListPageContentSystemWidget',
            'locked' => true
        )
    )
);