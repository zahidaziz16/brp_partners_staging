<?php
// Heading
$_['heading_title'] = '<strong class="tb_hide_me" style="text-shadow: 0 -1px 4px white, 0 -2px 10px yellow, 0 -10px 20px #ff8000, 0 -18px 40px red;">BurnEngine</strong>';

$old_theme_controller = realpath(realpath(dirname(__FILE__)) . '/../../../../controller/theme') . '/BurnEngine.php';
if (version_compare(VERSION, '2.3.0.0', '>=') && is_file($old_theme_controller)) {
    $_['heading_title'] .= '
    <script>$(document).ready(function() {
        var i = 0;
        $("#extension").find(".tb_hide_me").closest("tr").each(function() {
            if (i == 1) {
                $(this).next("tr").find("a.btn[href*=\'BurnEngine\']").end().remove();
                $(this).remove();
                var $opt = $("#content").find("select[name=\'type\'] :selected");
                var num = $("#extension").find("table > tbody td[colspan=\'2\']").length;
                $opt.text($opt.text().replace(/\.*\((\d).*/, "(" + num + ")"));
            }
            i++;
        });
    });
    </script>';
}

if (version_compare(VERSION, '2.3.0.0', '>=')) {
    $_['heading_title'] .= '
    <script>$(document).ready(function() {
        if ($(document).data("ajax_event_bound")) {
            return;
        }
        
        $(document).ajaxSuccess(function( event, xhr, settings ) {
            var qs = (function(a) {
                if (a == "") return {};
                var b = {};
                for (var i = 0; i < a.length; ++i)
                {
                    var p=a[i].split(\'=\', 2);
                    if (p.length == 1)
                        b[p[0]] = "";
                    else
                        b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
                }
                return b;
            })(settings.url.split("?")[1].split(\'&\'));
            if (qs["route"] == "extension/extension/theme/install" && qs["extension"] == "BurnEngine") {
                window.location = settings.url.split("?")[0] + "?route=extension/theme/BurnEngine&store_id=0&token=" + qs["token"];
            }
            $(document).data("ajax_event_bound", true);
        });
    });
    </script>';
}
