(function ($, tbApp) {

function reArrangeRows($container) {

    var $rows = $container.find("> div.s_sortable_row");

    $rows.each(function() {
        var row_index = $rows.index(this);
        $(this).find("span.row_order").first().text(parseInt(row_index) + 1);
    });
}

function updatePreviewBox(section_name) {

    var css_string = calculateShadow(section_name) + calculateBorder(section_name) + calculateGradient(section_name);

    $("#" + section_name + "_style_preview")
        .attr('style', css_string)
        .parent().attr('style', calculateBorder(section_name));
}

function calculateShadow(section_name) {

    var css_string = "";

    $("#style_settings_" + section_name + "_box_shadow").find("div.tb_box_shadow_row").each(function() {

        var $row = $(this);

        var angle    = parseInt($row.find('input[name$="[angle]"]').val());
        var distance = parseInt($row.find('input[name$="[distance]"]').val());
        var blur     = parseInt($row.find('input[name$="[blur]"]').val());
        var spread   = parseInt($row.find('input[name$="[spread]"]').val());
        var opacity  = parseInt($row.find('input[name$="[opacity]"]').val());
        var color    = hexToRgb($row.find('input[name$="[color]"]').val());
        var inner    = $row.find('input[name$="[inner]"]').is(":checked");

        var radiants = (Math.PI / 180) * angle;
        var tempX = Math.round(-distance * Math.cos(radiants));
        var tempY = Math.round(distance * Math.sin(radiants));

        if(opacity != 0) {
            opacity = opacity/100;
        }

        if(css_string != "") {
            css_string += ", ";
        }

        var shadow_tpl = "{{x}}px {{y}}px {{blur}}px {{spread}}px rgba({{color_r}},{{color_g}},{{color_b}},{{opacity}}){{#inner}} inset{{/inner}}";
        css_string += Mustache.render(shadow_tpl, {
            x:       tempX,
            y:       tempY,
            blur:    blur,
            spread:  spread,
            color_r: color[0],
            color_g: color[1],
            color_b: color[2],
            opacity: opacity,
            inner:   inner
        });

        $row.find('input[name$="[size_x]"]').val(tempX);
        $row.find('input[name$="[size_y]"]').val(tempY);
    });

    if (css_string != "") {
        css_string = "box-shadow: " + css_string + ";";
    }

    return css_string;
}

function initBoxShadowsRow($row, section_name) {

    var updatePreview = function() {
        updatePreviewBox(section_name);
    };

    $row.find("a.tbRemoveRow").bind("click", function() {
        if (confirm("Are you sure?")) {
            var $parent = $row.parent("div.s_sortable_holder");
            $row.fadeTo(150, 0, function() {
                $(this).slideUp({
                    duration: 500,
                    easing: "easeOutCubic",
                    complete: function() {
                        $(this).remove();
                        reArrangeRows($parent);
                        updatePreview();
                        $.fn.stickySidebar("reset");
                    }
                });
            });
        }
        return false;
    });

    $row.find("div.colorSelector").each(function() {
        assignColorPicker($(this));
    });

    $row.find("div.tb_knob").each(function() {
        var knob = createKnob($(this), $row, updatePreview);

        $(this).data("knob", knob);
        knob.get();
    });

    $row.find("div.tb_slider > div").each(function() {
        $(this).slider({
            slide: function( event, ui ) {
                $(this).parent("div.tb_slider").next().find("input").val(ui.value);
                updatePreview();
            },
            value: $(this).parent("div.tb_slider").parent().find("input").val()
        });
    });

    $row.find("input.s_spinner").spinner({
        step: 1,
        mouseWheel: true
    }).bind("change spin spinstop", function() {
            $(this).parent().prev("div.tb_slider").slider({
                value: $(this).val()
            });
            var $knob = $(this).closest(".tb_knob");
            if ($knob.length) {
                $knob.data("knob").get();
            }

            updatePreview();
        });

    $row.find('input[name$="[inner]"]').bind("change", updatePreview);
    $row.find('input[name$="[color]"]').bind("changeColor", updatePreview);

}

function initBoxShadow(section_name, input_property) {

    // check if 'new' is omitted.
    var that = (this === window) ? {} : this;

    that.$container = $("#style_settings_" + section_name + "_box_shadow");

    that.$container.find("a.tbAddRow").first().bind("click", function() {

        var output = Mustache.render($("#style_section_shadow_row_template").text(), {
            input_property: input_property,
            section_name:   section_name,
            row_num:        tbHelper.generateUniqueId(3),
            row_order:      that.$container.find("div.tb_box_shadow_row").length + 1
        });

        var $row = $(output).appendTo($(this).prev("div.s_sortable_holder"));

        initBoxShadowsRow($row, section_name);
        beautifyForm($row);

        return false;
    });

    that.$container.find("div.tb_box_shadow_row").each(function() {
        initBoxShadowsRow($(this), section_name);
    });

    that.$container.find(" > div.s_sortable_holder").sortable({
        handle: "h3.s_drag_area",
        tolerance: "pointer",
        stop: function(event, ui) {
            updatePreviewBox(section_name);
            reArrangeRows(ui.item.parent("div.s_sortable_holder"));
        }
    });
}

function calculateGradient(section_name) {

    var $container = $("#style_settings_" + section_name + "_background");

    if (!$container.length) {
        return "";
    }

    var css_string = "";
    var css_string_w3c = "";
    var css_string_vendor = "";

    $container.find('div.tbTabContent[background_type="gradient"]').each(function() {
        var string_start = css_string_w3c != "" ? ", " : "";

        switch($(this).find("select.tbGradientType").val()) {
            case "linear":
                var angle     = Number($(this).find('input[name$="[angle]"]').val());
                var new_angle = Math.abs(angle - 450) % 360;

                css_string_w3c += string_start + "linear-gradient(" + new_angle + "deg";;
                css_string_vendor += string_start + "{{prefix}}" + "linear-gradient(" + angle + "deg";;
                break;
            case "radial":
                css_string_w3c += string_start + "radial-gradient(ellipse at center";
                css_string_vendor +=  string_start + "{{prefix}}radial-gradient(center, ellipse cover";
                break;
        }

        $(this).find("div.tbBgColorRow").each(function() {
            var color      = hexToRgb($(this).find('input[name$="[color]"]').val());
            var opacity    = parseInt($(this).find('input[name$="[opacity]"]').val());
            var has_offset = !$(this).find('input[name$="[offset_auto]"]').is(":checked");
            var offset     = "";

            if(opacity != 0) {
                opacity = opacity/100;
            }

            if (has_offset) {
                offset = " " + parseInt($(this).find('input[name$="[offset]"]').val()) + "%";
            }

            var color_stop = ", rgba(" + color[0] + "," + color[1] + "," + color[2] + "," + opacity + ")" + offset;

            css_string_w3c += color_stop;
            css_string_vendor += color_stop;

        });

        css_string_w3c += ")";
        css_string_vendor += ")";
    });

    if (css_string_w3c != "") {
        $.each(["-moz-", "-webkit-", "-ms-"], function(index, value) {
            css_string += "background-image: " + css_string_vendor.replace(/{{prefix}}/g, value) + ";\n";
        });
        css_string += "background-image: " + css_string_w3c + ";\n";
    }
    
    if ($container.find('input[name$="[background][solid_color]"]').first().val()) {
        var solid_color = hexToRgb($container.find('input[name$="[background][solid_color]"]').first().val());
        var solid_color_opacity = $container.find('input[name$="[background][solid_color_opacity]"]').first().val() / 100;
        if (null !== solid_color) {
            css_string += "background-color: rgba(" + solid_color[0] + "," + solid_color[1] + "," + solid_color[2] + "," + solid_color_opacity + ");" + "\n";
        }
    }

    return css_string;
}


function checkBgColorRowsRemove($tab_content) {
    if ($tab_content.find("div.tbBgColorRow").length <= 2) {
        $tab_content.find("a.tbRemoveColorRow").hide();
    } else {
        $tab_content.find("a.tbRemoveColorRow").show();
    }
}

function initBgColorGradient($tab_content, section_name, input_property, bg_row_key) {

    $tab_content.on("click", "a.tbAddColorRow", function() {

        var row_order = $tab_content.find("div.tbBgColorRow").length;
        var options = {
            input_property: input_property,
            section_name:   section_name,
            bg_row_key:     bg_row_key,
            row_key:        tbHelper.generateUniqueId(3),
            row_order:      row_order + 1,
            color:          '#ffffff'
        };

        var tpl = $("#style_section_background_gradient_color_template").text();

        var $row = $(Mustache.render(tpl, options)).appendTo($(this).prev("div.s_sortable_holder"));
        initBgColorRow($row, section_name);

        if (row_order == 0) {
            options.row_order = 2;
            options.color     = '#000000';
            options.row_key   = tbHelper.generateUniqueId(3);

            $row = $(Mustache.render(tpl, options)).appendTo($(this).prev("div.s_sortable_holder"));
            initBgColorRow($row, section_name);
        }

        checkBgColorRowsRemove($tab_content);
        beautifyForm($row);
        updatePreviewBox(section_name);

    });

    $tab_content.find("div.tbBgColorRow").each(function() {
        initBgColorRow($(this), section_name);
    });

    var knob = createKnob($tab_content.find("div.tb_knob"), null, function() {
        updatePreviewBox(section_name);
    });
    $tab_content.find("div.tb_knob").data("knob", knob);
    knob.get();

    $tab_content.find(".s_spinner").first().spinner().bind("spin", function() {
        $(this).closest(".tb_knob").data("knob").update();
    });

    $tab_content.find("div.s_sortable_holder").sortable({
        handle: "h3.s_drag_area",
        tolerance: "pointer",
        stop: function(event, ui) {
            updatePreviewBox(section_name);
            reArrangeRows(ui.item.parent("div.s_sortable_holder"));
        }
    });

    $tab_content.find("select.tbGradientType").bind("change", function() {
        if ($(this).val() == "linear") {
            $tab_content.find("div.tbLinearGroup").show();
        } else
        if ($(this).val() == "radial") {
            $tab_content.find("div.tbLinearGroup").hide();
        }
        updatePreviewBox(section_name);
    });

    $tab_content.find('select[name$="[size]"]').bind("change", function() {
        $tab_content.find('input[name$="[size_x]"]').closest("div").toggle($(this).val() == "custom");
        $tab_content.find('input[name$="[size_y]"]').closest("div").toggle($(this).val() == "custom");
    });

    $tab_content.find('select[name$="[position]"]').bind("change", function() {
        $tab_content.find('input[name$="[position_x]"]').closest("div").toggle($(this).val() == "custom");
        $tab_content.find('input[name$="[position_y]"]').closest("div").toggle($(this).val() == "custom");
    });

    checkBgColorRowsRemove($tab_content);
    beautifyForm($tab_content);
}

function initBgColorRow($row, section_name) {

    $row.find("div.tb_slider > div").each(function() {
        $(this).slider({
            slide: function(event, ui) {
                $(this).parent("div.tb_slider").next().find("input").val(ui.value);
                updatePreviewBox(section_name);
            },
            value: $(this).closest("div.tb_slider").parent().find("input").val()
        });
    });

    $row.find(".s_spinner").spinner({
        step: 1,
        mouseWheel: true
    }).bind("change spin spinstop", function() {
            $(this).parent().prev("div.tb_slider").slider({
                value: $(this).val()
            });
            updatePreviewBox(section_name);
        });

    $row.find(".colorSelector").each(function() {
        assignColorPicker($(this));
    });

    $row.find('input[name$="[color]"]').bind("changeColor", function() {
        updatePreviewBox(section_name);
    });

    $row.find("div.tbBgColorRowOffset").each(function() {
        var $el = $(this);
        $el.find(':checkbox[name$="[offset_auto]"]').bind("click", function() {
            $el.find("> div").first().toggleClass("tb_disabled", $(this).is(":checked"));
            updatePreviewBox(section_name);
        });
    });
}

function initBgImageRow($row, section_name) {
    $row.find('select[name$="[size]"]').bind("change", function() {
        $row.find('input[name$="[size_x]"]').closest("div").toggle($(this).val() == "custom");
        $row.find('input[name$="[size_y]"]').closest("div").toggle($(this).val() == "custom");
    });

    $row.find('select[name$="[position]"]').bind("change", function() {
        $row.find('input[name$="[position_x]"]').closest("div").toggle($(this).val() == "custom");
        $row.find('input[name$="[position_y]"]').closest("div").toggle($(this).val() == "custom");
    });
}

function initBackground(section_name, input_property) {

    var $container = $("#style_settings_" + section_name + "_background");

    if (!$container.length) {
        return;
    }

    var $tabs_holder = $container.find("div.tb_tabs").first();
    var tabs = $tabs_holder.tabs();

    tabs.find(".ui-tabs-nav").sortable({
        axis: "y",
        tolerance: "pointer",
        stop: function() {
            $tabs_holder.find("div.tb_tabs_nav > ul > li > a").each(function() {
                $($(this).attr("href")).appendTo($tabs_holder);
            });
            updatePreviewBox(section_name);
            tabs.tabs("refresh");
        }
    });

    var addNewBackgroundTab = function(content, background_type) {
        var num_tabs = $tabs_holder.find("div.tb_tabs_nav > ul > li").length + 1;
        var unique_id = tbHelper.generateUniqueId(3);

        $tabs_holder.find("div.tb_tabs_nav > ul").append(
            "<li><a href='#" + section_name + "_row_" + unique_id + "'>#" + num_tabs + "</a></li>"
        );

        if (background_type == 'gradient') {
            $tabs_holder.append(
                "<div id='" + section_name + "_row_" + unique_id + "' class='tb_gradient_listing tbTabContent' background_type='gradient'>" + content + "</div>"
            );
        } else
        if (background_type == 'image') {
            $tabs_holder.append(
                "<div id='" + section_name + "_row_" + unique_id + "' class='tb_image_listing tb_list_view tbTabContent' background_type='image'>" + content + "</div>"
            );
        }

        tabs.tabs("refresh");
        tabs.tabs("option", "active", num_tabs-1);

        return $tabs_holder.find(".tbTabContent").last();
    };

    $container.find("a.tbAddGradient").bind("click", function() {

        var bg_row_key = tbHelper.generateUniqueId(3);
        var options = {
            input_property: input_property,
            section_name:   section_name,
            bg_row_key:     bg_row_key,
            bg_container:   !tbHelper.str_begins_with('widget_data', input_property)
        };
        var output = Mustache.render($("#style_section_background_gradient_template").text(), options);
        var $newTab = addNewBackgroundTab(output, "gradient");

        initBgColorGradient($newTab, section_name, input_property, bg_row_key);
        $newTab.find("a.tbAddColorRow").trigger("click");

        $container.find("fieldset.tbGradientListing").show();

        return false;
    });

    $container.find("a.tbAddImage").bind("click", function() {
        var options = {
            input_property: input_property,
            section_name:   section_name,
            bg_row_key:     tbHelper.generateUniqueId(3),
            bg_container:   !tbHelper.str_begins_with('widget_data', input_property)
        };
        var output = Mustache.render($("#style_section_background_image_template").text(), options);
        var $newTab = addNewBackgroundTab(output, "image");

        initBgImageRow($newTab, section_name);
        beautifyForm($newTab);
        $container.find("fieldset.tbGradientListing").show();

        return false;
    });

    $tabs_holder.find('.tbTabContent').each(function(index) {
        if ($(this).is('[background_type="gradient"]')) {
            initBgColorGradient($(this), section_name, input_property, $(this).find('input[data-bg-row-key]').first().data('bg-row-key'));
        } else {
            initBgImageRow($(this), section_name);
        }
    });

    $container.on("click", "a.tbRemoveColorRow", function() {
        if (confirm('Are you sure?')) {
            var $parent = $(this).parents("div.s_sortable_holder").first();

            if ($parent.find("div.tbBgColorRow").length > 2) {
                $(this).parents('div.tbBgColorRow').first().fadeTo(150, 0, function() {
                    $(this).slideUp({
                        duration: 500,
                        easing: "easeOutCubic",
                        complete: function() {
                            $(this).remove();
                            reArrangeRows($parent);
                            checkBgColorRowsRemove($parent);
                            updatePreviewBox(section_name);
                            $.fn.stickySidebar("reset");
                        }
                    });
                });
            }
        }

        return false;
    });

    $container.on("click", "a.tbRemoveBackgroundRow", function() {
        if (confirm('Are you sure?')) {

            var num_tabs = $tabs_holder.find("div.tb_tabs_nav > ul > li").length + 1;
            var $panel = $(this).closest(".tbTabContent");

            $("#" + $panel.attr("aria-labelledby")).closest("li").remove();
            $panel.remove();

            $tabs_holder.tabs("refresh");
            $tabs_holder.tabs("option", "active", num_tabs-1);

            $.fn.stickySidebar("reset");
            updatePreviewBox(section_name);
            $tabs_holder.find("div.tb_tabs_nav > ul a").each(function(index) {
                $(this).text("#" + (index + 1));
            });


            if ($tabs_holder.find("div.tb_tabs_nav > ul a").length == 0) {
                $container.find("fieldset.tbGradientListing").hide();
            }
        }

        return false;
    });

    $container.on("change", 'input[id^="bg_image_style"]', function() {
        $(this).parents("div.tbBgImageRow").first().find(".tbFilename").text(tbHelper.basename($(this).val()));
    });

    $container.find('input[name$="[background][solid_color]"]').first().bind("changeColor", function() {
        updatePreviewBox(section_name);
    });
    $container.find('input[name$="[background][solid_color_opacity]"]').first().bind("change", function() {
        updatePreviewBox(section_name);
    });

    $container.on("click", ".tbInheritMenuButton", function() {

        var $color_el = $container.find("input[name$='[solid_color]']");
        var menu = JSON.parse($("#colors_inherit_menu").val());

        var $button = $(this);

        var findMenu = function(inherit_key) {
            var result = null;

            $.each(menu, function(index, val) {
                if (val.inherit_key == inherit_key) {
                    result = val;
                    return false;
                }
            });

            return result;
        };

        var keyToId = function(key) {
            if (-1 == key.indexOf(":")) {
                key = "theme_" + key;
            }

            return 'color_item_' + key.replace(/[\.:]/g, "_");
        };

        var $inherit_key_el = $container.find("input[name$='[solid_color_inherit_key]']");

        $button.addClass("tb_opened");

        for(var i = menu.length -1; i >= 0 ; i--){
            menu[i].color     = $("#" + keyToId(menu[i].inherit_key)).find('input[name$="[color]"]').val();
            menu[i].label     = "Theme Colors  <span>&#9654;</span> " + menu[i].label;
            menu[i].selected  = menu[i].inherit_key == $inherit_key_el.val();
            menu[i].parent_id = keyToId(menu[i].inherit_key);
        }

        var tpl = Mustache.render($("#colors_inherit_menu_template").text(), {
            inherit_menu  : menu,
            current_label : findMenu($inherit_key_el.val()).label
        });
        var $dialog = $(tpl).appendTo($button).show();

        $dialog.on("click", ".tbChooseParent", function() {
            var menu_item = findMenu($(this).data("inherit_key"));

            $inherit_key_el.val(menu_item.inherit_key);
            $color_el.val(menu_item.color);
            $color_el.trigger("updateColor");
            $color_el.closest(".tbColorItem").attr("parent_id", menu_item.parent_id);

            updatePreviewBox(section_name);

            tbApp.removeDomInstance("background_color_inherit_menu");

            return false;
        });

        $dialog.find(".tbChooseParent").hover(
            function() {
                $dialog.find(".tbCurrentRule").html(findMenu($(this).data("inherit_key")).label);
            }, function() {
                $dialog.find(".tbCurrentRule").html(findMenu($dialog.find(".tb_selected").data("inherit_key")).label);
            }
        );

        tbApp.removeDomInstance("background_color_inherit_menu");

        $dialog.data("closeTimeout", setTimeout(function() {
            tbApp.removeDomInstance("background_color_inherit_menu");
        }, 3500));

        $dialog.on("mouseenter", function() {
            clearTimeout($(this).data("closeTimeout"));
        });

        $dialog.on("mouseleave", function() {
            $(this).data("closeTimeout", setTimeout(function() {
                tbApp.removeDomInstance("background_color_inherit_menu");
            }, 1000));
        });

        tbApp.registerDomInstance("background_color_inherit_menu", $dialog, function() {
            clearTimeout($(this).data("closeTimeout"));
            $button.removeClass("tb_opened");
        });

        return false;
    });

    $container.find(".tbBackgroundInherit").bind("change", function() {

        $container.find(".tbColorItem").toggleClass("tb_disabled tb_inherit", $(this).prop("checked"));

        var keyToId = function(key) {
            if (-1 == key.indexOf(":")) {
                key = "theme_" + key;
            }

            return 'color_item_' + key.replace(/[\.:]/g, "_");
        };

        var $color_el = $container.find("input[name$='[solid_color]']");
        var menu = JSON.parse($("#colors_inherit_menu").val());

        for(var i = menu.length -1; i >= 0 ; i--){
            menu[i].color     = $("#" + keyToId(menu[i].inherit_key)).find('input[name$="[color]"]').val();
        }

        if ($(this).prop("checked")) {
            $('<input type="hidden" name="' + $color_el.attr("name").replace(/(.*)\[(.*)\]/, '$1[solid_color_inherit_key]') + '" value="' + menu[0].inherit_key + '" />').insertAfter($color_el);
            $color_el.data("original_color", $color_el.val());
            $color_el.val(menu[0].color);
        } else {
            $color_el.val($color_el.data("original_color") || "");
            $container.find("input[name$='[solid_color_inherit_key]']").remove();
        }

        $color_el.trigger("updateColor");
        updatePreviewBox(section_name);
    });

    $container.on("click", function() {
        tbApp.removeDomInstance("background_color_inherit_menu");
    });

    assignColorPicker($container.find(".colorSelector").first(), true);
}

function calculateBorder(section_name) {

    var $container = $("#style_settings_" + section_name + "_border");

    if (!$container.length) {
        return "";
    }

    var css_string = "";

    $.each(["top", "right", "bottom", "left"], function(index, value) {

        var width   = parseInt($container.find('input[name$="[border][' + value + '][width]"]').val());
        var style   = $container.find('select[name$="[border][' + value + '][style]"]').val();
        var opacity = parseInt($container.find('input[name$="[border][' + value + '][opacity]"]').val());
        var color   = hexToRgb($container.find('input[name$="[border][' + value + '][color]"]').val());

        if (null == color) {
            return true;
        }

        if(opacity != 0) {
            opacity = opacity/100;
        }

        css_string += "border-" + value + ": " + width + "px " + style + " rgba(" + color[0] + "," + color[1] + "," + color[2] + "," + opacity + ");\n";
    });

    var radius_top_left     = parseInt($container.find('input[name$="[border_radius][top_left]"]').val());
    var radius_top_right    = parseInt($container.find('input[name$="[border_radius][top_right]"]').val());
    var radius_bottom_right = parseInt($container.find('input[name$="[border_radius][bottom_right]"]').val());
    var radius_bottom_left  = parseInt($container.find('input[name$="[border_radius][bottom_left]"]').val());

    css_string +=  "border-radius:" + radius_top_left  + "px " + radius_top_right + "px " + radius_bottom_right + "px " + radius_bottom_left + "px;\n";

    return css_string;
}

function initBorder(section_name) {

    if (!(this instanceof initBorder)) return new initBorder(section_name);

    var $container = $("#style_settings_" + section_name + "_border");

    if (!$container.length) {
        return "";
    }

    $container.find("div.tb_slider > div").each(function() {
        $(this).slider({
            slide: function(event, ui) {
                $(this).parent("div.tb_slider").next().find("input").val(ui.value);
                updatePreviewBox(section_name);
            },
            value: $(this).parent("div.tb_slider").parent().find("input").val()
        });
    });
    
    $container.find(".s_spinner").spinner({
        step: 1,
        mouseWheel: true
    }).bind("spin spinstop", function(event) {
        $(this).parent().prev("div.tb_slider").find("> div").slider("value", $(this).val());
        $(this).trigger("change");
        updatePreviewBox(section_name);
    });

    $container.find(".colorSelector").each(function() {
        assignColorPicker($(this));
    });

    $container.find("a.tbBorderLock").first().bind("click", function() {
        $(this).toggleClass("active");

        if ($(this).hasClass("active")) {

            var $first_row = $container.find("div.tbBorderStylesRow").first();
            var $siblings  = $first_row.siblings("div.tbBorderStylesRow");
            var $sliders   = $siblings.find("div.tb_slider > div");

            var width   = $first_row.find('input[name$="[width]"]').val();
            var style   = $first_row.find('select[name$="[style]"]').val();
            var color   = $first_row.find('input[name$="[color]"]').val();
            var opacity = $first_row.find('input[name$="[opacity]"]').val();

            $siblings.each(function() {
                $(this).addClass("tb_disabled");
                $(this).find('input[name$="[width]"]').val(width);
                $(this).find('select[name$="[style]"]').val(style);
                $(this).find('input[name$="[color]"]').val(color);
                $(this).find('input[name$="[opacity]"]').val(opacity);
            });

            $container.find('input[name$="[color]"]').trigger("updateColor");
            $container.find('input[name$="[opacity]"]').trigger("change");

            $first_row.find('select[name$="[style]"]').bind("change.locked", function(e) {
                $siblings.find('select[name$="[style]"]').val($(this).val());
                updatePreviewBox(section_name);
            });

            $first_row.find("input").bind("change.locked changeColor.locked", function(e) {

                var attr_name = $(this).attr("name").match(/.*(\[.*\])/)[1];
                var input_value = $(this).val();

                if (attr_name == "[color]" && e.type == "changeColor") {
                    $siblings.find('input[name$="[color]"]').val(input_value).trigger("updateColor");
                    updatePreviewBox(section_name);
                } else
                if (attr_name != "[color]") {
                    $siblings.find('input[name$="'+ attr_name + '"]').val(input_value);

                    if (attr_name == "[opacity]") {
                        $sliders.each(function() {
                            $(this).slider("option", "value", input_value);
                        });
                    }
                    updatePreviewBox(section_name);
                }
            });

            $first_row.find("div.tb_slider > div").bind("slide.locked", function(event, ui) {
                $sliders.each(function() {
                    $(this).slider("value", ui.value);
                    $(this).parent("div.tb_slider").parent().find("input").val(ui.value);
                });
            });

            updatePreviewBox(section_name);

        } else {
            $container.find("div.tbBorderStylesRow").removeClass("tb_disabled");
            $container.find("div.tbBorderStylesRow").first().find("input").unbind(".locked");
            $container.find("div.tb_slider > div").first().unbind("slide.locked slidechange.locked");
            $container.find("select").first().unbind("change.locked");
        }
    });

    $container.find("a.tbRadiusLock").first().bind("click", function() {

        var $first_row = $(this).parents("fieldset").first().find("div.tbBorderRadiusRow").first();
        var $siblings  = $first_row.siblings("div.tbBorderRadiusRow");

        $(this).toggleClass("active");

        if ($(this).hasClass("active")) {
            $siblings.addClass("tb_disabled");
            $first_row.find("input").bind("change.locked", function() {
                $siblings.find("input").val($(this).val());
            }).trigger("change");

            updatePreviewBox(section_name);
        } else {
            $siblings.removeClass("tb_disabled");
            $first_row.find("input").unbind("change.locked");
        }
    });

    var updatePreview = function(e) {
        updatePreviewBox(section_name);
    };

    $container.find('input[name$="[color]"]').bind("changeColor", updatePreview);
    $container.find('select[name$="[style]"]').bind("change", updatePreview);
    $container.find('select[name*="[border_radius]"]').bind("change", updatePreview);
}

function initLayout(section_name) {
    if (section_name == "area_content") {

        var $container = $("#style_settings_" + section_name + "_layout");

        $container.find(':checkbox[name$="[layout][separate_columns]"]').bind("change", function() {
            var is_checked = $(this).is(":checked");

            if (is_checked) {
                $container.find(".tbPaddingWrap input").val(0).toggleClass('tb_disabled', is_checked);
                $container.find('.tbPaddingWrap').addClass('tb_disabled');
                $container.find('.tbSidebarsWrap > .tbNormalColumns').hide();
                $container.find('.tbSidebarsWrap > .tbSeparateColumns').show();
            } else {
                $container.find('.tbPaddingWrap').removeClass('tb_disabled');
                $container.find('.tbSidebarsWrap > .tbNormalColumns').show();
                $container.find('.tbSidebarsWrap > .tbSeparateColumns').hide();
            }
        }).trigger("change");
    }
}

var comboBoxConstructor = function($panel, widget_area) {

    return {
        token:       Math.floor((Math.random()*99)+1),
        panel:       $panel,
        widget_area: widget_area,
        contents:    {},
        comboBox:    {},
        pageType:    "",
        currentItem: {},

        create: function() {
            return this;
        },

        afterCpSave: function() {
            this.reloadModified();
            this.panel.find(".tbRecordInfoMessage1").hide();
            if (this.panel.find(".tbRecordInfoMessage2").length) {
                this.panel.find(".tbRecordInfoMessage2").show();
            }
        },

        init: function() {

            var self = this;

            self.contents = this.panel.find(".tbComboBoxRow");
            self.contents.find(".tbSystemMenu").find("li.tb_multiple > ul:not(:has(*))").parent("li").remove();
            self.comboBox = self.contents.find("select.tbComboBox").tbComboBox({
                $contents: self.contents,
                select:    $.proxy(self, "_onMenuItemSelect"),
                remove:    $.proxy(self, "_onMenuItemRemove")
            }).data("uiTbComboBox");

            return self;
        },

        loadSettingsData: function(type, record_id) {

            var self = this;
            var chosen = self.comboBox.parent.exportValue();
            var pageType = self.pageType != "" ? self.pageType + ":" : "";

            self.panel.block().css('position', '');

            $.get($sReg.get('/tb/url/style/renderSection') + "&section=" + self.widget_area + "&area_type=" + type + "&area_id=" + record_id, function(data) {
                self.panel.empty().append(data).unblock();

                tbApp.initStyleArea(self.widget_area);
                self.comboBox.parent.importValue(chosen);
                self.contents.find(".tbPageType").text(pageType);

                var settings_label;

                if (typeof chosen.custom != "undefined") {
                    settings_label = pageType + " " + chosen.custom;
                } else {
                    settings_label = self.comboBox.element.find('option[value="' + chosen.value + '"]').text();
                }

                self.panel.find(".tbRecordInfoMessage1, .tbRecordInfoMessage2").find(".tbPageDescription").text(settings_label);
            });
        },

        reloadModified: function() {

            var self = this;
            var url = $sReg.get('/tb/url/layoutBuilder/modifiedMenu') + "&area_name=" + self.widget_area + "&record_type=style";

            $.get(url, function(modified_menu) {
                self.comboBox.modifiedMenu = undefined;
                self.contents.find(".tbModifiedMenu").remove();
                self.contents.append(modified_menu);
            });
        },

        getPageType: function() {
            return this.contents.find(".tbPageType").text();
        },

        getLabel: function() {
            return this.comboBox.label();
        },

        setMenuItem: function(uiItem) {

            if ($(uiItem.element).is("option")) {
                this.pageType = "";
            } else {
                switch (uiItem.key) {
                    case "category":
                        this.pageType = "Category";
                        break;
                    case "page":
                        this.pageType = "Information page";
                        break;
                    case "layout":
                        this.pageType = "Layout";
                        break;
                    case "system":
                        this.pageType = "System page";
                        break;
                }
            }

            this.currentItem = uiItem;
            this.contents.find('input[name="area_id"]').val(uiItem.optionValue);
        },

        _onMenuItemSelect: function(event, ui) {

            this.setMenuItem(ui.item);
            this.loadSettingsData(ui.item.key, ui.item.optionValue);
            ui.context.blur();
        },

        _onMenuItemRemove: function(event, ui) {

            var item = ui.item;

            if (!confirm('Delete "' + item.label + '" configuraiton. Are you sure ?')) {
                return false;
            }

            var self = this;
            var params = {
                area_name         : self.widget_area,
                area_type         : item.key,
                area_id           : item.optionValue,
                current_area_type : self.currentItem.key,
                current_area_id   : self.currentItem.optionValue,
                record_type       : "style"
            };

            $.getJSON($sReg.get('/tb/url/layoutBuilder/removeSettings') + "&" + $.param(params), function(response) {
                if (item.optionValue == ui.context.value() || response.reload == 1) {
                    self.loadSettingsData(self.currentItem.key, self.currentItem.optionValue);
                } else {
                    self.reloadModified();
                    ui.context.blur();
                }
            });

            return false;
        }
    }
};

tbApp.updatePreviewBox = function(section_name) {
    return updatePreviewBox(section_name);
};

tbApp.initBackground = function (section_name, input_property) {
    return initBackground (section_name, input_property);
};

tbApp.initBoxShadow = function(section_name, input_property) {
    return initBoxShadow(section_name, input_property);
};

tbApp.initBorder = function(section_name) {
    return initBorder(section_name);
};

tbApp.initLayout = function(section_name) {
    return initLayout(section_name);
};

tbApp.displayColorInheritMenu = function($button, origin) {

    if ($button.find("> div").length) {
        return;
    }

    var $row = $button.closest(".tbColorItem");
    var $inherit_key_el = $row.find("input[name$='[inherit_key]']");

    var keyToId = function(key) {
        var context_index = key.indexOf(":");

        if (-1 == context_index) {
            key = "theme_" + key;
        } else {
            var context = key.substring(0, context_index);

            if (context == 'area' && /area\[(.*)\]/.test($inherit_key_el.attr("name"))) {
                key = 'area_' + $inherit_key_el.attr("name").match(/area\[(.*?)\]/)[1] + key.substring(context_index);
            }
        }

        return 'color_item_' + key.replace(/[\.:]/g, "_");
    };

    var menu = JSON.parse($("#colors_inherit_menu").val());

    var findMenu = function(inherit_key) {
        var result = null;

        $.each(menu, function(index, val) {
            if (val.inherit_key == inherit_key) {
                result = val;
                return false;
            }
        });

        return result;
    };

    $button.addClass("tb_opened");

    var original_inherit_key = $inherit_key_el.data("original_inherit_key") ? $inherit_key_el.data("original_inherit_key") : $inherit_key_el.val();

    for(var i = menu.length -1; i >= 0 ; i--){
        if (keyToId(menu[i].inherit_key) == keyToId(original_inherit_key)) {
            menu.splice(i, 1);

            continue;
        }

        menu[i].selected  = menu[i].inherit_key == $inherit_key_el.val();
        menu[i].color     = $("#" + keyToId(menu[i].inherit_key)).find('input[name$="[color]"]').val();
        menu[i].parent_id = keyToId(menu[i].inherit_key);
        if (origin != 'theme') {
            menu[i].label = "Theme Colors  <span>&#9654;</span> " + menu[i].label;
        }
    }

    var $original_parent = $("#" + keyToId(original_inherit_key)),
        original_parent_id,
        original_label,
        original_color;

    if ($original_parent.length || !$row.hasAttr("parent_id")) {
        original_parent_id = keyToId(original_inherit_key);
    } else {
        original_parent_id = $row.attr("parent_id");
    }

    if (original_inherit_key == $inherit_key_el.val()) {
        original_label = $row.find(".tbColorToggleInherit").attr("title").replace("Inherits from ", "").replace(/->/g, "<span>&#9654;</span>");
        original_color = $row.find('input[name$="[color]"]').val();
    } else {
        if ($original_parent.length) {
            var begin = '';

            if(typeof original_inherit_key.split(":")[1] != "undefined" && original_inherit_key.split(":")[0] != $inherit_key_el.data("context")) {
                begin = tbHelper.ucfirst(original_inherit_key.split(":")[0]) + " Colors <span>&#9654;</span> ";
            }

            original_label = begin + $original_parent.closest(".tb_color_row").find("> legend").first().text() + " <span>&#9654;</span> " + $original_parent.find("> label").first().text();
            original_color = $original_parent.find('input[name$="[color]"]').val();
        } else {
            var key_parts = original_inherit_key.split(":");
            var $input_color = $row.find('input[name$="[color]"]');

            original_label = tbHelper.ucfirst(key_parts[0]) + " Colors <span>&#9654;</span> " + tbHelper.ucfirst(key_parts[1].split(".")[0]) + " <span>&#9654;</span> " + tbHelper.ucfirst(key_parts[1].split(".")[1]).replace(/_/g, " ");
            if ($input_color.data("original_color")) {
                original_color = $input_color.data("original_color");
            } else {
                original_color = $row.attr("parent_color");
            }
        }
    }

    menu.unshift({
        label       : original_label,
        color       : original_color,
        inherit_key : original_inherit_key,
        selected    : original_inherit_key == $inherit_key_el.val(),
        parent_id   : original_parent_id
    });

    var tpl = Mustache.render($("#colors_inherit_menu_template").text(), {
        inherit_menu  : menu,
        current_label : findMenu($inherit_key_el.val()).label
    });
    var $dialog = $(tpl).appendTo($button).show();

    $dialog.find(".tbChooseParent").hover(
        function() {
            $dialog.find(".tbCurrentRule").html(findMenu($(this).data("inherit_key")).label);
        }, function() {
            $dialog.find(".tbCurrentRule").html(findMenu($dialog.find(".tb_selected").data("inherit_key")).label);
        }
    );

    $dialog.on("click", ".tbChooseParent", function() {

        $dialog.hide();

        var $inherit_el = $row.find("input[name$='[inherit]']");
        var $force_print_el = $row.find("input[name$='[force_print]']");
        var item = findMenu($(this).data("inherit_key"));

        if ($inherit_key_el.data("original_inherit_key") == item.inherit_key) {
            $inherit_key_el.removeAttr("data-original_inherit_key"); // to see the changes in firebug
            $inherit_key_el.removeData("original_inherit_key");
            $inherit_el.val(1);
            $force_print_el.val($force_print_el.data("original_force_print"));
        } else {
            if (Number($inherit_el.val()) != 2) {
                $inherit_key_el.attr("data-original_inherit_key", $inherit_key_el.val()); // to see the changes in firebug
                $inherit_key_el.data("original_inherit_key", $inherit_key_el.val());
                $force_print_el.data("original_force_print", $force_print_el.val());
            }

            $inherit_el.val(2);
            $force_print_el.val(1);
        }

        $inherit_key_el.val(item.inherit_key);

        $row.attr("parent_id", item.parent_id);
        setTimeout(function() {
            $("#" + $row.attr("parent_id")).find('input[name$="[color]"]').trigger("changeColor");
        }, 50);

        $row.find(".tbColorToggleInherit").attr("title", "Inherits from " + item.label.replace(/<span>&#9654;<\/span>/g, "->").replace(/<span>▶<\/span>/g, "->"));
        $row.find(".colorSelector >").css("background-color", item.color);
        $row.find('input[name$="[color]"]').val(item.color);

        tbApp.removeDomInstance("colors_inherit_menu");

        return false;
    });

    tbApp.removeDomInstance("colors_inherit_menu");

    $dialog.data("closeTimeout", setTimeout(function() {
        tbApp.removeDomInstance("colors_inherit_menu");
    }, 3500));

    $dialog.on("mouseenter", function() {
        clearTimeout($(this).data("closeTimeout"));
    });

    $dialog.on("mouseleave", function() {
        $(this).data("closeTimeout", setTimeout(function() {
            tbApp.removeDomInstance("colors_inherit_menu");
        }, 1000));
    });

    tbApp.registerDomInstance("colors_inherit_menu", $dialog, function() {
        clearTimeout($(this).data("closeTimeout"));
        $button.removeClass("tb_opened");
    });

    return false;
};

tbApp.initColors = function(section_name) {
    $("#style_settings_" + section_name + "_colors")
        .find("div.colorSelector").each(function() {
            assignColorPicker($(this), $(this).hasClass("tbBackgroundColor"));
        }).end()
        .find(".tbColorItem").each(function() {
            var $row = $(this);

            if ($row.hasClass("tbHasChildren")) {
                $(this).find('input[name$="[color]"]').bind("changeColor", function() {
                    $("#style_settings_" + section_name + "_colors")
                        .find('.tb_inherit[parent_id="' + $row.attr("id") + '"]')
                        .find('input[name$="color]"]').val($(this).val()).triggerAll("updateColor changeColor");
                });
            }

            if ($row.hasAttr("parent_id")) {
                $("#" + $row.attr("parent_id")).find('input[name$="color]"]').trigger("changeColor");
            }

        }).end()
        .on("click", ".tbColorToggleInherit", function() {
            var $row = $(this).parents(".tbColorItem").first();

            if ($row.hasClass("tb_inherit")) {
                $row.removeClass("tb_inherit tb_disabled")
                    .addClass("tb_no_inherit")
                    .find('input[name$="[inherit]"]').first().val(0);
            } else
            if ($row.hasClass("tb_no_inherit")) {
                $row.removeClass("tb_no_inherit")
                    .addClass("tb_inherit tb_disabled")
                    .find('input[name$="[inherit]"]').first().val(1);

                var $colorInput = $row.find('input[name$="[color]"]');
                var new_color;

                if ($row.hasAttr("parent_color")) {
                    new_color = $row.attr("parent_color");
                } else {
                    new_color = $("#" + $row.attr("parent_id")).find('input[name$="[color]"]').val();
                }

                $colorInput.val(new_color).trigger("updateColor");

                if ($row.hasClass('tbHasChildren')) {
                    $row.closest("fieldset").parent()
                        .find('.tb_inherit[parent_id="' + $row.attr("id") + '"]')
                        .find('input[name$="[color]"]').val(new_color).trigger("updateColor");
                }
            }
        })
        .on("click", ".tbInheritMenuButton", function() {
            return tbApp.displayColorInheritMenu($(this), "builder");
        })
        .closest("div.tb_subpanel").on("click", function() {
            tbApp.removeDomInstance("colors_inherit_menu");
        });
};

tbApp.initStyleSection = function(section_id, section_name, input_property) {
    switch (section_id) {
        case "layout":
            tbApp.initLayout(section_name);
            break;
        case "box_shadow":
            tbApp.initBoxShadow(section_name, input_property);
            break;
        case "background":
            tbApp.initBackground(section_name, input_property);
            break;
        case "border":
            tbApp.initBorder(section_name);
            break;
        case "colors":
            tbApp.initColors(section_name);
            break;
        case "typography":
            tbApp.initFontItems($("#style_settings_" + section_name + "_typography"));
            break;

    }
};

tbApp.initStyleArea = function(area_name) {

    var initialized_sections = [];
    var $panel = $("#style_settings_" + area_name);
    var $recordInfoMessage1 = $panel.find(".tbRecordInfoMessage1");
    var $recordInfoMessage2 = $panel.find(".tbRecordInfoMessage2");

    var area_id = "area_" + area_name;
    var area_prefix = "area";

    if ($panel.data("area_id")) {
        area_id = $panel.data("area_id");
    }

    if ($panel.data("area_prefix")) {
        area_prefix = $panel.data("area_prefix")
    }

    var initTab = function($tab, $panel) {

        var section = $tab.find("a").data("section");

        if (!section || -1 != initialized_sections.indexOf(section)) {
            return;
        }

        tbApp.initStyleSection(section, area_id, area_prefix + "[" + area_name + "]");
        beautifyForm($panel);
        initialized_sections.push(section);
        $panel.find("> h2").prepend(area_name + " ");

        if ($recordInfoMessage1.length && !$panel.find(".tbRecordInfoMessage1").length) {
            $panel.prepend($recordInfoMessage1.clone());
        }

        if ($recordInfoMessage2.length && !$panel.find(".tbRecordInfoMessage2").length) {
            $panel.prepend($recordInfoMessage2.clone());
        }
    };

    $panel.find("> .tb_tabs").tabs({
        activate: function(event, ui) {
            initTab(ui.newTab, ui.newPanel);
            tbApp.cookie.set("tbStyle" + area_id + "Tabs", ui.newTab.index());
        },
        active: tbApp.cookie.get("tbStyle" + area_id + "Tabs", 0),
        create: function(event, ui) {
            initTab(ui.tab, ui.panel);
            $panel.find("> .tb_tabs > .tb_tabs_nav").first().stickySidebar({
                padding: 30
            });
        },
        load: function(event, ui) {
            initTab(ui.tab, ui.panel);
            $(ui.panel).removeClass("tb_loading");
        }
    });

    tbApp.stylesComboBoxFactory($panel, area_name).init();
    tbApp.updatePreviewBox(area_id);

    $panel.find(".tbSaveAreaSettings").bind("click", function() {
        $panel.block({ message: '<h1>Saving settings</h1>' }).css('position', '');
        tbHelper.createCallbackRegister($(tbApp)).collectEvent('tbCp:beforeSave', function() {
            $("#tb_cp_form").ajaxSubmit({
                dataType: "json",
                beforeSerialize: function($form) {
                    $form.find(':input').not('.tbAreaSettingsKey, [name^="' + area_prefix + '[' + area_name + ']"]').attr("disabled", "disabled");
                },
                success: function(response, statusText, xhr, $form) {
                    $(tbApp).trigger("tbCp:afterSave", [response, $form]);
                    $panel.unblock();
                    $form.find(':input').not('[name^="' + area_prefix + '[' + area_name + ']"]').removeAttr("disabled");
                    tbApp.stylesComboBoxFactory($panel, area_name).afterCpSave();
                }
            });
        });
    });

    $panel.find(".tbAreaLoadPresetDialog").bind("click", function() {
        var $menu = $(this).closest(".tbAreaActions").find(".tbPresetMenu");

        if ($menu.is(":visible")) {
            clearTimeout($(this).data("closeTimeout"));
            $menu.hide();

            return;
        }

        $menu.show();

        $menu.data("closeTimeout", setTimeout(function() {
            $menu.hide();
        }, 3500));

        $menu.on("mouseenter", function() {
            clearTimeout($(this).data("closeTimeout"));
        });

        $menu.on("mouseleave", function() {
            clearTimeout($(this).data("closeTimeout"));
            $(this).data("closeTimeout", setTimeout(function() {
                $menu.hide();
            }, 1000));
        });
    });

    $panel.find(".tbAreaLoadPreset").bind("click", function() {
        var area_type = tbApp.stylesComboBoxFactory($panel, area_name).contents.find("input[name='area_type']").val();
        var area_id   = tbApp.stylesComboBoxFactory($panel, area_name).contents.find("input[name='area_id']").val();

        $panel.block().css('position', '');

        $.getJSON($sReg.get('/tb/url/style/loadAreaPreset'), {
            preset_id: $(this).closest(".tbAreaActions").find("select").val(),
            area_name: area_name,
            area_type: area_type,
            area_id:   area_id
        }, function() {
            tbApp.stylesComboBoxFactory($panel, area_name).loadSettingsData(area_type, area_id);
        });

        return false;
    });

    $(tbApp).off("." + area_id);

    $(tbApp).on("tbCp:beforeSerialize." + area_id, function(e, $form) {
        $form.find(':input[name^="' + area_prefix + '[' + area_name + ']"]').attr("disabled", "disabled");
    });

    $(tbApp).on("tbCp:afterSave." + area_id, function(e, data, $form) {
        $form.find(':input[name^="' + area_prefix + '[' + area_name + ']"]').removeAttr("disabled");
    });
};

tbApp.stylesComboBoxFactoryInstances = [];
tbApp.stylesComboBoxFactory = function($panel, widget_area) {

    if (typeof tbApp.stylesComboBoxFactoryInstances[widget_area] == "undefined") {
        tbApp.stylesComboBoxFactoryInstances[widget_area] = comboBoxConstructor($panel, widget_area).create();
    }

    return tbApp.stylesComboBoxFactoryInstances[widget_area];
};

})(jQuery, tbApp);