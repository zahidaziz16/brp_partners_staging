<?php
// Heading
$_['heading_title'] = '<strong id="tb_module_hide_me" style="text-shadow: 0 -1px 4px white, 0 -2px 10px yellow, 0 -10px 20px #ff8000, 0 -18px 40px red;">BurnEngine</strong>';

if (version_compare(VERSION, '2.2.0.0', '>=')) {
    $_['heading_title'] .= '
    <script>$(document).ready(function() {
        $("#tb_module_hide_me").closest("tr").remove();
    });
    </script>';
    $old_module_controller = realpath(realpath(dirname(__FILE__)) . '/../../../controller/module') . '/BurnEngine.php';
    if (is_file($old_module_controller)) {
        @unlink($old_module_controller);
    }
}