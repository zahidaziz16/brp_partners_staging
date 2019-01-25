<?php

return array(
    'name'                            => 'BurnEngine',
    'description'                     => 'OpenCart themes engine',
    'fallback_language'               => 'en-gb',
    'seo_action'                      => 'startup/seo_url',
    'admin_folder'                    => 'admin',
    'admin_redirect_https'            => true,
    'default_cache'                   => 14400,
    'image_serve_domain'              => '',
    'css_images_url'                  => 'auto_full',
    'color_inheritance_error_type'    => 'info', // info, exception, false
    'widgets_fallback_first_language' => true,
    'menu_fallback_first_language'    => true,
    'show_system_settings'            => true,
    'catalog_external_js'             => true,
    'catalog_google_fonts'            => true,
    'catalog_google_fonts_js'         => true,
    'catalog_merge_google_fonts'      => true,
    'admin_ajax'                      => true,
    'admin_external_js'               => true,
    'admin_google_fonts'              => true,
    'admin_beautify'                  => true,
    'admin_save_purge_cache'          => true,
    'admin_show_copy_area'            => false,
    'debug'                           => true,
    'firephp'                         => true
);