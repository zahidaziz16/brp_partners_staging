<?php
// Heading
$_['heading_title']       = 'Blog (Stories)<span class="stories_hide_me"></span>';

// Text
$_['text_module']         = 'Modules';
$_['text_success']        = 'Success: You have modified module stories!';
$_['text_content_top']    = 'Content Top';
$_['text_content_bottom'] = 'Content Bottom';
$_['text_column_left']    = 'Column Left';
$_['text_column_right']   = 'Column Right';

// Entry
$_['entry_layout']        = 'Layout:';
$_['entry_position']      = 'Position:';
$_['entry_status']        = 'Status:';
$_['entry_sort_order']    = 'Sort Order:';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify module stories!';

$old_module_controller = realpath(realpath(dirname(__FILE__)) . '/../../../controller/module') . '/stories.php';
if (version_compare(VERSION, '2.3.0.0', '>=') && is_file($old_module_controller)) {
    $_['heading_title'] .= '
    <script>$(document).ready(function() {
        var i = 0;
        $("#extension").find(".stories_hide_me").closest("tr").each(function() {
            if (i == 1) {
                $(this).remove();
            }
            i++;
        });
    });
    </script>';
    @unlink($old_module_controller);
}