(function($, tbApp) {

    var $container = $("#tb_cp_panel_extensions > .tb_tabs").first();

    $container.tabs({
        activate: function(event, ui) {
            tbApp.cookie.set("tbExtensionSEOTabs", ui.newTab.index());
        },
        active: tbApp.cookie.get("tbExtensionSEOTabs", 0)
    });

    $container.find('.tbSeoStoreMetaTabs').tabs();

    $("#extension_seo_generator")
        .on("click", ".tbLanguagesChoice", function() {
            if (!$(this).find(":checked").length) {
                return false;
            }
        })
        .find("> .tb_tabs").tabs({
            activate: function(event, ui) {
                tbApp.cookie.set("tbExtensionSEOGeneratorTabs", ui.newTab.index());
            },
            active: tbApp.cookie.get("tbExtensionSEOGeneratorTabs", 0)
        });

    function initEditable($container) {
        $.fn.editable.defaults.mode = 'inline';
        $container.find(".editable").editable({
            type      : "text",
            emptytext : "",
            url       : $sReg.get("/tb/url/seo/default/editField"),
            params    : function(params) {
                params.language_id = $(this).data("language_id");
                params.item = $(this).data("item");

                return params;
            }
        });
    }

    $("#extension_seo_editor")
        .on("click", ".pagination a", function() {
            var $parent = $(this).closest(".tbLanguageTabs");
            var $container = $parent.hasClass('tb_tabs') ? $("#" + $parent.find("> ul > li").eq($parent.tabs("option", "active")).attr("aria-controls")) : $parent;

            $container.find('> .tb_data_holder').block();
            $.get($(this).attr("href"), function(data) {
                $container.find('> .tb_data_holder').unblock();
                $container.html(data);
                initEditable($container);
            });

            return false;
        })
        .on("click", "td", function() {
            var $empty_a = $(this).find("a.editable-empty");

            if ($empty_a.length && !$empty_a.next(".editable-inline").length) {
                $empty_a.editable("show");

                return false;
            }
        })
        .on("click", ".tbEditorSearch", function() {
            var search_string = $(this).closest(".tbSearchGroup").find("input").val().trim();

            if (!$(this).is(".tbEditorClearSearch")) {
                if (search_string.length < 3) {
                    return false;
                }
            } else {
                search_string = "";
            }

            var $parent = $(this).closest(".tbLanguageTabs");
            var $tab = $parent.find("> ul > li").eq($parent.tabs("option", "active"));
            var $container = $("#" + $tab.attr("aria-controls"));
            var url = $.jurlp($("#extension_seo_editor").find("li[aria-controls='" + $parent.attr("id") + "'] a").attr("href"));

            url.query({
                language_code : $tab.data("language_code"),
                action        : "editorPage",
                search_string : search_string
            });

            $container.block();
            $.get(url.url().toString(), function(data) {
                $container.unblock()
                    .html(data)
                    .find(".tbSearchGroup")
                    .find("input").val(search_string).end()
                    .find(".tbEditorClearSearch").toggle(search_string != "");
                initEditable($container);
            });

            return false;
        })
        .find("> .tb_tabs").tabs({
            activate: function(event, ui) {
                tbApp.cookie.set("tbExtensionSEOEditorTabs", ui.newTab.index());
            },
            active: tbApp.cookie.get("tbExtensionSEOEditorTabs", 0),
            beforeLoad: function(event, ui) {
                if (ui.tab.data("loaded")) {
                    event.preventDefault();
                } else {
                    ui.panel.block();
                }
            },
            load: function(event, ui) {
                ui.panel.find(".tbSeoEditorTabs").parent().tabs();
                initEditable(ui.panel);
                ui.tab.data("loaded", true);
                ui.panel.unblock();
            }
         });

    $container.find(".tbPreview").sModal({
        width       : 800,
        margin_left : -400,
        height      : 620,
        fixed       : false,
        dataType    : "json",
        linktag     : function() {
            var $row = $(this).closest("fieldset");

            return $sReg.get("/tb/url/seo/default/preview") + "&item=" + $row.data("item") + "&context=" + $row.data("context");
        },
        requestData: function() {
            var $row = $(this).closest("fieldset");

            return $row.find(":input[name^='seo_general[" + $row.data("item") + "][" + $row.data("context") + "]']").serializeArray();
        },
        onSetContents: function(data) {
            return generatePreview(data);
        },
        onShow: function(modal) {
            modal.getContents().find(".tb_tabs").tabs();
        }
    });

    $container.on("change", "#seo_multilingual_keywords", function() {
        if ($(this).prop("checked")) {
            $("#seo_language_prefix").prop("checked", true).trigger("change");
        }
    });

    $container.on("change", "#seo_language_prefix", function() {
        if (!$(this).prop("checked")) {
            $("#seo_default_language_prefix").prop("checked", false);
            $("#seo_multilingual_keywords").prop("checked", false);
        }

        $container.find(".tbLanguagePrefix").toggleClass("tb_disabled", !$(this).prop("checked"));
    });

    $container.on("change", "input[name='seo[default_language_prefix]']", function() {
        if ($(this).prop("checked")) {
            $("#seo_language_prefix").prop("checked", true);
        }
    });

    $container.on("click", ".tbGenerate", function() {

        if (!confirm("Are you sure?")) {
            return false;
        }

        var $row = $(this).closest("fieldset");

        $row.block();
        $.getJSON(
            $sReg.get("/tb/url/seo/default/generate") + "&item=" + $row.data("item") + "&context=" + $row.data("context"),
            $row.find(":input[name^='seo_general[" + $row.data("item") + "][" + $row.data("context") + "]']").serializeArray(),
            function(data) {

                $row.unblock();

                if (!data || !data.success) {
                    return;
                }
            });

        return false;
    });

    $container.on("click", ".tbClear", function() {
        if (!confirm("Are you sure you want to delete all the data for the current item ? This could affect your link structure and SEO positions.")) {
            return false;
        }

        var $row = $(this).closest("fieldset");

        $row.block();
        $.getJSON(
            $sReg.get("/tb/url/seo/default/clear") + "&item=" + $row.data("item") + "&context=" + $row.data("context"),
            $row.find(":input[name^='seo_general[" + $row.data("item") + "][" + $row.data("context") + "]']").serializeArray(),
            function() {
                $row.unblock();
            });

        return false;
    });

    $container.on("click", ".tbPatternItem", function() {
        var $input = $(this).closest("fieldset").find("input[name$='[pattern]']");

        $input.val($input.val() + $(this).text());
    });

    $container.on("click", ".tbSettingsButton", function() {
        $(this).closest("fieldset").find(".tbSettingsWrap").toggle();
    });

    $container.find(".tbSaveSeoUrlSettings").bind("click", function() {
        $container.block();
        $.post($sReg.get("/tb/url/seo/default/saveSettings"), $container.find(":input[name^='seo']").serializeArray(), function(data) {
            $container.unblock();
        }, "json");

        return false;
    });

    function generatePreview(data) {

        var output = "";

        output += '<h1 class="sm_title"><span>Preview</span></h1>';
        output += '<div class="tb_seo_preview tb_subpanel tb_cp">';

        if (data.preview_data == undefined || data.preview_data.length == 0) {
            output += '  <p class="tb_no_results">There are no items that satisfy the current conditions.</p>';
            output += '</div>';

            return output;
        }

        output += '<p class="s_hidden">Total affected items: ' + data.affected.total + '</p>';
        output += '  <div class="tb_tabs tb_fly_tabs tbLanguageTabs">';
        output += '    <h2>Meta title</h2>';
        output += '    <ul class="tb_tabs_nav clearfix">';
        $.each(data.preview_data, function(language_id, items) {
            output += '    <li class="s_language">';
            output += '      <a href="#preview_language_' + data.languages[language_id].code + '" title="' + data.languages[language_id].name + '">';
            output += '        <img class="inline" src="' + data.languages[language_id].url + data.languages[language_id].image + '" />';
            output += '        ' + data.languages[language_id].code;
            output += '      </a>';
            output += '    </li>';
        });
        output += '    </ul>';

        $.each(data.preview_data, function(language_id, items) {
            output += '  <div id="preview_language_' + data.languages[language_id].code + '">';
            output += '    <div class="s_server_msg s_msg_blue"><p class="s_icon_16 s_info_16">Affected items in ' + data.languages[language_id].name + ': ' + data.affected[language_id] + '</p></div>';
            output += '    <table class="s_table_1" cellpadding="0" cellspacing="0">';
            output += "      <thead>";
            output += "        <tr>";

            for (var first in items) break;
            for (var column in items[first]) {
                output += '        <th class="align_left">' + column + '</th>';
            }

            output += "        </tr>";
            output += "      </thead>";
            output += "      <tbody>";

            $.each(items, function(index, item) {
                output += "      <tr>";

                for (var column in item) {
                    output += '      <td class="align_left">' + item[column] + '</td>';
                }

                output += '      </tr>';
            });
            output += '      </tbody>';
            output += '    </table>';
            output += '  </div>';
        });

        output += '  </div>';
        output += '</div>';

        return output;
    }

})(jQuery, tbApp);
