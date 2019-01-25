(function ($, tbApp) {

var fontsMenu;

function buildFontsMenu(callback) {

    $.getJSON($sReg.get("/tb/url/fonts/getFontsList"), function(data) {

        var html = "<ul class='tb_scroll' style='display: none;'>\n";

        html += "<li class='tb_label tbOptGroup' font_type='built'><strong>Built-in fonts</strong></li>\n";

        $.each(data['built-in'], function(index, name) {
            html += "<li><a href='javascript:;' font_name='" + name + "'>" + name + "</a></li>\n";
        });

        html += "<li class='tb_label tbOptGroup' font_type='google'><strong>Google fonts</strong></li>\n";

        $.each(data['google'], function(index, name) {
            html += "<li><a href='javascript:;' font_name='" + name + "'>" + name + "</a></li>\n";
        });

        html += "</ul>\n";

        var fontsMenu = $(html).appendTo("body").addClass("ui-autocomplete").menu({
            input: $(),
            role: null
        }).data("uiMenu");

        fontsMenu.show = function(context) {
            fontsMenu.element
                .off("menuselect.combobox")
                .on("menuselect.combobox", $.proxy(context, "_menuSelectCallback"))
                .css("min-width", context.widget().outerWidth())
                .show()
                .position({
                    my: "left top",
                    at: "left bottom+4",
                    of: context.widget()
                });

            if (context.element.hasClass("tbCanInherit")) {
                fontsMenu.element.prepend('<li><a href="javascript:;" font_name="inherit">Inherit</a></li>');
                fontsMenu.refresh();
            }
        };

        fontsMenu.hide = function() {
            fontsMenu.element.find('a[font_name="inherit"]').parent().remove();
            fontsMenu.element.hide();
        };

        callback.call(null, fontsMenu);
    });
}

function selectFont(ui) {

    var div_parent = ui.context.element.closest("div.fontfamily");
    var div_style = div_parent.next("div.fontstyle");
    var font_type = ui.item.element.parent("li").prevAll(".tbOptGroup").attr("font_type");

    if (typeof font_type == "undefined") {
        // Inherit
        div_style.hide().find('input[name$="[variant]"]:hidden, input[name$="[subsets]"]:hidden').val("");
        div_parent.find('input[name$="[type]"]:hidden').val("");
        //div_parent.closest(".tbFontItem").find(".tbFontProperty.tb_no_inherit").find(".tbInheritLabel").trigger("click");

        return false;
    } else {
        //div_parent.closest(".tbFontItem").find(".tbFontProperty.tb_inherit").find(".tbInheritLabel").trigger("click");
    }

    var adjacent_el = div_style.find("select").multiselect();
    var variants_el = adjacent_el.find("optgroup.font_variants");

    div_parent.find('input[name$="[type]"]:hidden').val(font_type);

    if (font_type == "built" && !ui.context.element.hasClass("tbHasBuiltStyles")) {
        div_style.find('input[name$="[variant]"]:hidden').val("");
        div_style.find('input[name$="[subsets]"]:hidden').val("");
        div_style.hide();

        return false;
    }

    if (font_type == "built") {
        variants_el.empty();
        jQuery.each(['regular', 'bold', 'italic', 'bolditalic'], function(i, val) {
            var opt = $('<option />', {
                value: val,
                text: val
            });
            opt.appendTo(variants_el);
        });

        adjacent_el.find("option:first").attr("selected", "selected");
        adjacent_el.find("optgroup.font_subsets").empty().hide();

        div_style.find('input[name$="[variant]"]:hidden').val(adjacent_el.find(":selected").val());
        div_style.find('input[name$="[subsets]"]:hidden').val("");
        div_style.show();

        adjacent_el.multiselect('refresh');
        adjacent_el.multiselect("widget").find("li.ui-multiselect-optgroup-label").bind("click", function() {
            return false;
        });

        return false;
    }

    $('<span class="tb_loading_inline"></span>').insertAfter(ui.context.widget());
    $.getJSON($sReg.get("/tb/url/fonts/getFontData") + "&font_name=" + ui.item.optionValue, function(data) {

        variants_el.empty();

        jQuery.each(data.variants, function(i, val) {
            var opt = $('<option />', {
                value: val.code,
                text: val.code
            });
            opt.appendTo(variants_el);
        });

        if (variants_el.find("option[value*=regular]:first").is("option")) {
            variants_el.find("option[value*=regular]:first").attr("selected", "selected");
        } else {
            variants_el.find("option:first").attr("selected", "selected");
        }
        div_style.find('input[name$="[variant]"]:hidden').val(variants_el.find("option:selected").val());

        var subsets_el = adjacent_el.find("optgroup.font_subsets");

        subsets_el.empty().show();
        jQuery.each(data.subsets, function(i, val) {
            var opt = $('<option />', {
                value: val,
                text: val
            });
            opt.appendTo(subsets_el);
        });

        if (subsets_el.find("option[value=latin]:first").is("option")) {
            subsets_el.find("option[value=latin]:first").attr("selected", "selected");
        } else
        if (subsets_el.find("option[value*=latin]:first").is("option")) {
            subsets_el.find("option[value*=latin]:first").attr("selected", "selected");
        } else {
            subsets_el.find("option:first").attr("selected", "selected");
        }

        div_style.find('input[name$="[subsets]"]:hidden').val(subsets_el.find("option:selected").val());
        div_style.show();

        adjacent_el.multiselect('refresh');
        ui.context.uiInput.trigger("blur");
        ui.context.widget().next(".tb_loading_inline").remove();
    });


    return true;
}

function createFontsComboBox($element) {

    var comboBox = $element.combobox({

        select: function(event, ui) {
            if (false === selectFont(ui)) {
                ui.context.uiInput.trigger("blur");
            }
            ui.context.element.prev("input").val(ui.item.optionValue);
        },

        blur: function() {
            if (typeof fontsMenu != "undefined" && fontsMenu.element.is(":visible")) {
                fontsMenu.hide();
            }
        },

        buttonmousedown: function(event, ui) {
            ui._wasOpen = typeof fontsMenu != "undefined" && fontsMenu.element.is(":visible");
        },

        open: function(event, ui) {

            ui._menuSelectCallback = function(event, ui) {

                var $a = ui.item.find("> a").first();

                this._events["autocompleteselect input"].call(this, event, {
                    item: {
                        a: 'b',
                        label:       $a.text(),
                        value:       $a.text(),
                        optionValue: $a.attr("font_name"),
                        element:     $a
                    }
                });

                fontsMenu.hide();
            };

            ui.options.buildFontsMenu(ui.widget(), function(menu) {
               menu.show(ui);
            });
        },

        buildFontsMenu: function(uiWidget, callback) {
            if (typeof fontsMenu == "undefined") {
                $('<span class="tb_loading_inline"></span>').insertAfter(uiWidget);

                buildFontsMenu(function(menu) {
                    fontsMenu = menu;
                    uiWidget.next(".tb_loading_inline").remove();
                    callback.call(null, fontsMenu);
                })
            } else {
                callback.call(null, fontsMenu);
            }
        },

        search_data: function (term, matcher, callback) {

            var result = {};

            if (term.trim().length > 1) {

                if (typeof fontsMenu != "undefined" && fontsMenu.element.is(":visible")) {
                    fontsMenu.hide();
                }

                this.options.buildFontsMenu(this.widget(), function(menu) {
                    result = menu.element.find("li.ui-menu-item:not(.tb_multiple) > a").map(function() {
                        return matcher.test($(this).text()) ? {
                            label:       $(this).text(),
                            value:       $(this).text(),
                            optionValue: $(this).text(),
                            remove:      false,
                            element:     $(this)
                        } : null;
                    }).get();

                    callback.call(null, result);
                });
            } else {
                callback.call(null, result);
            }
        }
    });

    comboBox.combobox("customValue", $element.prev("input").val());

    return comboBox;
}

function initFontItems($container) {

    $container.find(".tb_multiselect").multiselect({
        header: false,
        noneSelectedText: 'Font options',
        selectedList: 2
    });

    $container.find("select.fontname").each(function() {
        createFontsComboBox($(this));
    });


    $container.find("select.tb_multiselect").each(function() {
        $select = $(this);

        $select.bind("multiselectclick", function(event, ui) {
            var el = $(this);
            var div_parent = el.parents("div.fontstyle:first");
            var selected_option = el.find('option[value="' + ui.value + '"]');
            var input_hidden;

            if (false == ui.checked && selected_option.siblings(":selected").length == 0) {
                return false;
            }

            if (selected_option.parent("optgroup").is(".font_variants")) {
                input_hidden = div_parent.find('input[name$="[variant]"]:hidden');

                if (true == ui.checked && el.is(':not(.multiple_variants)')) {
                    el.find("optgroup.font_variants > option").not(selected_option).removeAttr("selected");
                    selected_option.attr("selected", "selected");
                    input_hidden.val(selected_option.val());
                    el.multiselect('refresh');

                    return true;
                }
            }

            if (selected_option.parent("optgroup").is(".font_subsets")) {
                input_hidden = div_parent.find('input[name$="[subsets]"]:hidden');
            }

            var values = ui.value;
            if (input_hidden.val().length !== 0) {
                values = values + "," + input_hidden.val();
            }

            values = $.map(values.split(","), function(checkbox) {
                if (ui.value != checkbox || (ui.value == checkbox && ui.checked)) {
                    return checkbox;
                }
            }).join(",");

            input_hidden.val(values);
        });

        (function ($select) {
            $select.multiselect("widget").find("li.ui-multiselect-optgroup-label").bind("click", function() {
                if (!$select.hasClass("multiple_variants") && $(this).hasClass("font_variants")) {
                    return false;
                }
            });
        })($select);
    });

    if ($container.hasClass("tb_font_row_custom") || $container.hasClass("tbFontGroup")) {
        // Ugly hack for not applying the below events twice
        return;
    }

    $container.on("click", "label.tbInheritLabel", function() {

        var $row = $(this).closest(".tbFontProperty");
        var $label = $row.find("label");
        var $inherit_mask = $row.closest(".tbFontItem").find("input[name$='[inherit_mask]']");

        if ($row.hasClass("tb_inherit") === true) {
            $row
                .removeClass("tb_inherit tb_disabled")
                .addClass("tb_no_inherit")
                .find("input").val($row.find("label").data("value")).prop("disabled", false);
            $inherit_mask.val(Number($inherit_mask.val()) - Number($label.data("binary")));
        } else {
            $row
                .removeClass("tb_no_inherit")
                .addClass("tb_inherit tb_disabled")
                .find("input").prop("disabled", true);
            $inherit_mask.val(Number($inherit_mask.val()) + Number($label.data("binary")));
        }
    });
}

tbApp.initFontItems = function($container) {

    var initOnce = function($tab, $panel) {

        if ($tab.data("initialized")) {
            return;
        }

        $tab.data("initialized", true);
        initFontItems($panel);
        beautifyForm($panel);
    };

    var $tabs = $container.find(".tbLanguageTabs").first();

    if ($tabs.length) {
        $tabs.tabs({
            activate: function(event, ui) {
                initOnce(ui.newTab.find("a"), ui.newPanel);
            },
            create: function(event, ui) {
                initOnce(ui.tab.find("a"), ui.panel);
            }
        });
    } else {
        initOnce($container, $container);
    }
};

})(jQuery, tbApp);
