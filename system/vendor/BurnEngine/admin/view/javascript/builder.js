(function($, tbApp) {

$.fn.proportionPanels = function($container) {

    if (!this.length || typeof $container == 'undefined') return;

    var $wrapper = this;
    var position, aX, $prev_el, $next_el,
        $current_el =  null,
        delay       = 20,
        shift       = Math.round($wrapper.width() * (1/12)),
        min_width   = 2,
        steps       = 0;

    $container.unbind(".proportionPanels");

    $container.bind("mousemove.proportionPanels", function(e) {

        if ($current_el == null) {
            return;
        }

        var delta = e.pageX - aX;

        var doMove = function(steps, direction) {

            var calculateProportion = function(proportion_arr, sequence) {
                var ratio = new Ratio(proportion_arr[0], proportion_arr[1]);

                if ((sequence == "next" && direction == "forward") || (sequence == "prev" && direction == "backward")) {
                    return ratio.subtract(1,12).simplify().toString().replace("/", "_");
                } else
                if ((sequence == "next" && direction == "backward") || (sequence == "prev" && direction == "forward")) {
                    return ratio.add(1,12).simplify().toString().replace("/", "_");
                }
            };

            var next_grid_proportion = calculateProportion($next_el.attr("grid_proportion").split("_"), 'next');
            var prev_grid_proportion = calculateProportion($prev_el.attr("grid_proportion").split("_"), 'prev');

            $next_el.attr("grid_proportion", next_grid_proportion);
            $prev_el.attr("grid_proportion", prev_grid_proportion);

            $next_el.alterClass("tb_grid_*", 'tb_grid_'+ next_grid_proportion);
            $prev_el.alterClass("tb_grid_*", 'tb_grid_' + prev_grid_proportion);

            $next_el.width($next_el.initWidth - shift * steps);
            $prev_el.width($prev_el.initWidth + shift * steps);

            var $cols = $container.find("div.s_builder_col");

            $container.find("div.s_builder_cols_grid_helper > div").eq($cols.index($next_el))
                .alterClass("tb_grid_*", 'tb_grid_'+ next_grid_proportion)
                .find("span").text(next_grid_proportion.replace("_", "/"));
            $container.find("div.s_builder_cols_grid_helper > div").eq($cols.index($prev_el))
                .alterClass("tb_grid_*", 'tb_grid_'+ prev_grid_proportion)
                .find("span").text(prev_grid_proportion.replace("_", "/"));

            $current_el.css('left', position + shift * steps);
        };

        $wrapper.css('cursor', 'e-resize');

        if (delta > delay + (shift * steps) && ($next_el.width() > min_width * shift)) {
            doMove(++steps, 'forward');
        } else
        if (delta < delay + (shift * (steps-1)) && ($prev_el.width() > min_width * shift)) {
            doMove(--steps, 'backward');
        }

    }).bind("mouseleave.proportionPanels mouseup.proportionPanels", function() {
        if ($current_el == null) {
            return;
        }

        $container.find(".s_builder_cols_grid_helper").fadeOut();
        $wrapper.css('cursor', 'default');
        $current_el.removeClass("tb_dragging");
        $current_el = null;
        steps = 0;
    });

    this.find("div.tb_drag_handle").remove();
    this.css("position", "relative");

    if (this.find("> div.s_builder_col").length == 6) {
        return;
    }

    this.find("> div.s_builder_col").each(function() {
        if (!$(this).next("div").is(".tb_drag_handle") && !$(this).is(":last-child")) {
            $('<div class="tb_drag_handle" style="position: absolute;"><div class="tb_drag_area"></div></div>')
                .insertAfter(this)
                .css("left", $(this).position().left + $(this).outerWidth(true))
                .bind("mousedown", function(e) {
                    $current_el = $(this);

                    $current_el.addClass("tb_dragging");

                    $prev_el = $current_el.prev("div");
                    $prev_el.initWidth = $prev_el.outerWidth();

                    $next_el = $current_el.next("div");
                    $next_el.initWidth = $next_el.outerWidth();

                    position = $current_el.position().left;
                    aX = e.pageX;

                    $container.find(".s_builder_cols_grid_helper").fadeIn();
                });
        }
    });
};

function checkLockedWidgets() {
    var currentTab = $("#tb_cp_panel_layout_builder").find("> .tb_tabs > div:visible");

    $("#tb_cp_layout_builder_widgets_panel").find("div.s_builder_row_widgets_listing div.tbWidget.tbWidgetLocked").each(function() {
        if ($(this).is(".tbWidgetLocked")) {
            var lockedWidget = currentTab.find('div[data-slot_name="' + $(this).data("slot_name") + '"]');

            if (lockedWidget.length) {
                lockedWidget.addClass("tbWidgetLocked").find("a.tbWidgetDuplicate").hide();
                $(this).draggable("disable");
            } else {
                $(this).draggable("enable");
            }
        }
    });
}

function initWidgetsPanel() {

    var options = {
        helper: "clone",
        connectToSortable: $("#tb_cp_panel_layout_builder").find("div.s_builder_col"),
        handle: "h3"
    };

    $("#tb_cp_layout_builder_widgets_panel").find("div.tbWidget").draggable(options);
    checkLockedWidgets();
}

function initWidget($widget) {

    $widget.find("> div.s_widget_actions a.s_button_remove_widget").bind("click", function() {
        if (confirm('Are you sure?')) {
            $widget.fadeOut(400, function() {
                $widget.remove();
                checkLockedWidgets();
            });
        }

        return false;
    });

    var getAreaSettings = function($editElement) {
        var settings = "";
        var query    = $.jurlp($editElement.attr("href")).query();
        var $inputs  = $("#style_settings_" + query.area_name).find(":input");

        if ($inputs.length && query.area_type == $inputs.filter('input[name="area_type"]').val() && query.area_id == $inputs.filter('input[name="area_id"]').val()) {
            settings = JSON.stringify($inputs.serializeJSON());
        }

        return settings;
    };

    $widget.find("> div.s_widget_actions .tbEditWidget").sModal({
        width:  800,
        height: 620,
        fixed:  false,
        requestMethod: "POST",
        requestData: function() {

            var $tbEditWidget = $widget.find("> div.s_widget_actions .tbEditWidget");
            var preset_id = $tbEditWidget.data("load_preset");
            var apply_preset = $tbEditWidget.data("apply_preset");

            if (preset_id !== undefined) {
                $tbEditWidget.removeData("load_preset");
            }

            if (apply_preset !== undefined) {
                $tbEditWidget.removeData("apply_preset");
            }

            return {
                preset_id       : preset_id !== undefined ? preset_id : "",
                apply_preset    : apply_preset !== undefined ? apply_preset : false,
                settings        : $widget.find("> textarea.widget_settings").text(),
                row_settings    : $widget.parents(".tbBuilderRow").first().find(".tbBuilderRowSettings").text(),
                column_id       : $widget.parents(".tbBuilderColumn").data("column_id"),
                column_settings : $widget.parents(".tbBuilderRow").first().find(".tbBuilderRowColumnSettings").text(),
                area_settings   : getAreaSettings($(this)),
                theme_colors    : JSON.stringify($("#colors_form_rows").find(':input[name^="colors"]').serializeJSON())
            };
        },
        onClick: function() {
            tbApp.removeDomInstance("widget_duplicate_submenu");
        },
        onShow: function(obj) {

            var loadPreset = function(apply_preset) {

                if (apply_preset === undefined) {
                    apply_preset = 0;
                }

                var preset_id = obj.find("select[name='preset_id']").val();

                if (String(preset_id) == "0") {
                    return false;
                }

                var $tbEditWidget = $widget.find("> div.s_widget_actions .tbEditWidget");

                $tbEditWidget.data("load_preset", preset_id);
                $tbEditWidget.data("apply_preset", apply_preset ? 1 : 0);
                obj.close(0);
                $tbEditWidget.trigger("click");

                return true;
            };

            obj.find(".tbLoadPreset").bind("click", function() {
                loadPreset();

                return false;
            });

            obj.find(".tbApplyPreset").bind("click", function() {

                if ($(this).hasClass("tbApplyClear")) {

                    $(this)
                        .parent().find("input[name='widget_data[preset_id]']").val("").end()
                        .find("select[name='preset_id']").val(0);

                    obj.find(".tb_color_row.tb_disabled").each(function() {
                        if ($(this).find(".tbColorToggleInherit").length) {
                          $(this).find("input[name$='[inherit]']").val(1);
                        }
                    });

                    obj.find(".tbWidgetUpdate").trigger("click");

                    return false;
                }

                if (!loadPreset(true)) {
                    return false;
                }

                return false;
            });

            if (obj.find("input[name='widget_data[preset_id]']").val()) {
                $("#style_settings_widget_box_box_shadow, #style_settings_widget_box_background, #style_settings_widget_box_border").addClass("tb_disabled");
                $("#style_settings_widget_title_box_shadow, #style_settings_widget_title_background, #style_settings_widget_title_border, #style_settings_widget_title_colors").addClass("tb_disabled");
                obj.find("#style_settings_widget_title_typography div[id^='style_settings_widget_title_typography_language']").addClass("tb_disabled");
                obj.find(".tbMarginFieldset, .tbPaddingFieldset").addClass("tb_disabled");

                $.each(JSON.parse(obj.find(".tbPresetBoxColorKeys").text()), function(index, value) {
                    $("#widget_box_colors_group_" + value).addClass("tb_disabled");
                });

                $.each(JSON.parse(obj.find(".tbPresetBoxFontKeys").text()), function(index, value) {
                    obj.find("fieldset[id*='widget_box_typography_group_" + value + "']").addClass("tb_disabled");
                });

                obj.find("#widget_box_styles_holder, #widget_title_styles_holder").prepend(Mustache.render($("#preset_name_info_template").text(), {
                    preset_name: obj.find("select[name='preset_id'] option:selected").text()
                }));

                obj.find(".tbLoadPreset").hide();
                obj.find(".tbApplyPreset").addClass("tbApplyClear").text("Clear");
                obj.find("select[name='preset_id']").parent().addClass("tb_disabled");
            }

            obj.find(".tbWidgetUpdate").bind("click", function() {

                obj.find("div.sm_content").first().block({ message: '<h1>Updating block settings</h1>' });

                for (var instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }

                var $form = $(this).closest("form");

                $(tbApp).trigger("tbWidget:onUpdate", [$widget, $form]);

                var query = $.jurlp($widget.find("> div.s_widget_actions .tbEditWidget").attr("href")).query();
                var data = {
                    area_name:     $widget.closest(".tbRowsContainer").attr("widget_area"),
                    area_type:     query.area_type,
                    area_id:       query.area_id,
                    row_settings:  $widget.parents(".tbBuilderRow").first().find(".tbBuilderRowSettings").text(),
                    area_settings: getAreaSettings($widget.find("> div.s_widget_actions .tbEditWidget")),
                    theme_colors:  JSON.stringify($("#colors_form_rows").find(':input[name^="colors"]').serializeJSON()),
                    widget_data:   JSON.stringify($form.find(":input").serializeJSON().widget_data)
                };

                $.post($form.attr("action"), $.param(data), function(response) {
                    $widget
                        .find("> h3").html(response.title).end()
                        .find("> textarea.widget_settings").text(response.data).end()
                        .find("> input.tbWidgetDirty").val(1);

                    setTimeout(function() {
                        obj.find("div.sm_content").first().unblock();
                        obj.close();
                    },500);
                }, "json");

                return false;
            });

            var initWidgetMainTabs = function($tab, $panel) {

                if ($tab.hasAttr("add_modal_class")) {
                    obj.getContents().addClass("sm_wide");
                } else {
                    obj.getContents().removeClass("sm_wide");
                }

                if ($tab.data("initialized")) {
                    return;
                }

                $tab.data("initialized", true);

                if (!$panel.hasClass("tbWidgetCommonOptions") && !$panel.hasClass("tbNoBeautify")) {
                    beautifyForm($panel);
                    return;
                }

                var update_preview_box = false;
                var initCommonOptionsSection = function($ctab, $cpanel) {
                    if ($ctab.data("initialized")) {
                        return;
                    }

                    beautifyForm($cpanel);

                    tbApp.initStyleSection($ctab.data("section"), "widget_" + $ctab.closest("ul").data("style_id"), $ctab.closest("ul").data("input_property"));
                    $ctab.data("initialized", true);

                    if (!update_preview_box) {
                        tbApp.updatePreviewBox("widget_" + $ctab.closest("ul").data("style_id"));
                    }
                    update_preview_box = true;
                };

                $panel.find("div.tbWidgetCommonOptionsTabs").first().tabs({
                    activate: function(event, ui) {
                        initCommonOptionsSection(ui.newTab.find("a"), ui.newPanel);
                    },
                    create: function(event, ui) {
                        initCommonOptionsSection(ui.tab.find("a"), ui.panel);
                    }
                });
            };

            obj.find("div.tbWidgetMainTabs").first().tabs({
                activate: function(event, ui) {
                    initWidgetMainTabs(ui.newTab.find("a"), ui.newPanel);
                },
                create: function(event, ui) {
                    initWidgetMainTabs(ui.tab.find("a"), ui.panel);
                }
            });

            obj.getContents().filter(".sm_window").trigger("widgetEditContentsShow", [$widget.attr("id")]);
        },
        onClose: function(obj) {
            $(tbApp).trigger("tbWidget:closeForm", [$widget]);
            obj.find("div.colorSelector").each(function() {
                $("#" + $(this).data("colorpickerId")).remove();
            });
            obj.find("div.s_widget_options_holder").first().trigger("closeWindow", [obj]);
        }
    });

    $widget.find("> div.s_widget_actions a.tbWidgetDuplicate").bind("click", function() {

        if ($widget.find(".tbWidgetDuplicateOptions").length) {
            return false;
        }

        tbApp.removeDomInstance("widget_duplicate_submenu");

        var output = Mustache.render($("#widget_duplicate_submenu_template").text());
        var $row = $(output).appendTo($widget.find(".s_widget_actions").css( "display", "block")).show();

        $row.data("closeTimeout", setTimeout(function() {
            tbApp.removeDomInstance("widget_duplicate_submenu");
        }, 3500));

        $row.on("mouseenter", function() {
           clearTimeout($(this).data("closeTimeout"));
        });

        $row.on("mouseleave", function() {
            $(this).data("closeTimeout", setTimeout(function() {
                tbApp.removeDomInstance("widget_duplicate_submenu");
            }, 1000));
        });

        if ($widget.is(".tbGroupWidget")) {
            $row.find(".tbFavourites").remove();
        }

        tbApp.registerDomInstance("widget_duplicate_submenu", $row, function() {
            clearTimeout($(this).data("closeTimeout"));
            $(this).closest(".tbWidget").find(".s_widget_actions").css( "display", "");
        });

        var cloneWidget = function($widget) {
            var $newWidget = $($widget.outerHTML());

            if ($widget.is(".tbGroupWidget")) {
                var subwidget_map = JSON.parse($widget.find(".tbGroupWidgetSubwidgetMap").val());
                var section_keys  = JSON.parse($widget.find(".tbGroupWidgetSectionKeys").val());
                var titles_map    = JSON.parse($widget.find(".tbGroupWidgetSectionTitlesMap").val());

                var new_subwidget_map = {},
                    new_section_keys  = [],
                    new_titles_map    = {},
                    old_new_map       = {};

                $.each(section_keys, function(index, value) {
                    var new_id = tbHelper.generateUniqueId();

                    old_new_map[value] = new_id;
                    new_section_keys.push(new_id);
                    new_titles_map[new_id] = titles_map[value];
                    new_subwidget_map[new_id] = [];
                });

                var findSection = function(widget_id) {
                    var result = null;

                    $.each(subwidget_map, function(section_id, widgets) {
                        if (null !== result) {
                            return false;
                        }

                        $.each(widgets, function(index, id) {
                            if (id == widget_id) {
                                result = section_id;

                                return false;
                            }
                        });
                    });

                    return result;
                };

                $newWidget.find(".tbWidget").add($newWidget).each(function() {

                    var new_id = tbHelper.generateUniqueId(8);
                    var new_attr_id = $(this).attr("id").replace(/(.*)_(.*)/, "$1_" + new_id);

                    if (this != $newWidget[0]) {
                        var section_id = findSection($(this).attr("id"));

                        new_subwidget_map[old_new_map[section_id]].push(new_attr_id);
                    }

                    $(this).attr("id", new_attr_id);
                });

                $newWidget.find(".tbGroupWidgetSubwidgetMap").text(JSON.stringify(new_subwidget_map));
                $newWidget.find(".tbGroupWidgetSectionKeys").text(JSON.stringify(new_section_keys));
                $newWidget.find(".tbGroupWidgetSectionTitlesMap").text(JSON.stringify(new_titles_map));
            } else {
                $newWidget.find(".tbWidget").add($newWidget).each(function() {
                    var new_id = $(this).attr("id").replace(/(.*)_(.*)/, "$1_" + tbHelper.generateUniqueId(8));

                    $(this).attr("id", new_id);
                });
            }

            return $newWidget;
        };

        var initNewWidget = function($widget) {
            initWidget($widget);

            $widget.find("div.s_widget").each(function() {
                initWidget($(this));
            });
        };

        $row
            .on("click", ".tbDuplicate", function() {
                $(this).closest(".tbWidgetDuplicateOptions").remove();
                $widget.find(".s_widget_actions").css( "display", "");
                initNewWidget(cloneWidget($widget).insertAfter($widget));
            })
            .on("click", ".tbFavourites", function() {

                $(this).closest(".tbWidgetDuplicateOptions").remove();
                $widget.find(".s_widget_actions").css( "display", "");

                var $newWidget = cloneWidget($widget);
                var new_id = $newWidget.attr("id").replace(/(.*)_(.*)/, "$1_" + tbHelper.generateUniqueId(8));

                $newWidget.attr("id_prefix", new_id.replace(/(.*)_(.*)/, "$1")).attr("id", new_id);
                $newWidget.find("div.s_widget").each(function() {
                    var new_id = $(this).attr("id").replace(/(.*)_(.*)/, "$1_" + tbHelper.generateUniqueId(8));

                    $(this).attr("id_prefix", new_id.replace(/(.*)_(.*)/, "$1")).attr("id", new_id);
                });

                $.post($sReg.get('/tb/url/widget/saveToFavourites'), {
                    widget_id:   new_id,
                    widget_data: $widget.find(".widget_settings").text()
                });

                $('#widgets_favourites_tab').addClass("tb_widget_added").closest(".tb_tabs").tabs("option", "active", -1);
                setTimeout(function() {
                    $("#widgets_favourites_tab").removeClass("tb_widget_added");
                }, 500);

                $newWidget.appendTo("#layout_builder_widgets_favourites .tbFavouritesWidgetsList");
                initWidgetsPanel();
            });

        return false;
    });

    if ($widget.is(".tbGroupWidget.tbBlockGroupWidget")) {
        initGroupWidget($widget, 'block_group');
    } else
    if ($widget.is(".tbGroupWidget")) {
        initGroupWidget($widget, 'group');
    }
}

function initGroupWidget($widget, type) {
    $widget.find(".tbGroupWidgetOpen").bind("click", function() {

        var disableRow = function() {
            $widget.hide();
            $("#tb_cp_layout_builder_widgets_panel").find("div.s_builder_row_widgets_listing  div.tbGroupWidget").draggable("disable");
        };

        var enableRow = function() {
            $widget.show();
            $("#tb_cp_layout_builder_widgets_panel").find("div.s_builder_row_widgets_listing  div.tbGroupWidget").draggable("enable");
            initWidgetsPanel();
        };

        var $group_widget = $($("#" + type + "_widget_template").text()).insertAfter($widget);

        var initSortables = function() {
            var $container = $widget.parents(".s_builder_wrap").first();

            $container.find("div.s_builder_col").sortable({
                forcePlaceholderSize: true,
                connectWith: $container.find("div.s_widget_subwidgets, div.s_builder_col")
            });

            $group_widget.find("div.s_widget_subwidgets").sortable({
                forcePlaceholderSize: true,
                connectWith: $container.find("div.s_widget_subwidgets, div.s_builder_col"),
                stop: function(event, ui) {
                    if (ui.item.is("[id_prefix]")) {
                        ui.item.attr("id", ui.item.attr("id_prefix") + "_" + tbHelper.generateUniqueId(8));
                        ui.item.removeAttr("id_prefix");
                        initWidget(ui.item);
                        ui.item.attr("new_subwidget", 1);
                        checkLockedWidgets();
                    }

                    var $panel = $container.parent();

                    ui.item.find("a.tbEditWidget").jurlp("query", {
                        area_name: $panel.find('input[name="area_name"]').val(),
                        area_type: $panel.find('input[name="area_type"]').val(),
                        area_id:   $panel.find('input[name="area_id"]').val()
                    });
                }
            });

            $("#tb_cp_layout_builder_widgets_panel").find("div.s_builder_row_widgets_listing  div.s_widget").draggable({
                connectToSortable: $container.find("div.s_widget_subwidgets, div.s_builder_col")
            });
        };

        var section_keys = JSON.parse($widget.find(".tbGroupWidgetSectionKeys").text());

        var addGroup = function(section_hash) {

            var row_title = $group_widget.find(".tbGroupWidgetSections > div").length + 1;

            if (section_hash === undefined) {
                section_hash = tbHelper.generateUniqueId();
            }

            var titles_map = JSON.parse($widget.find(".tbGroupWidgetSectionTitlesMap").text());

            if (titles_map[section_hash] !== undefined) {
                row_title = titles_map[section_hash];
            }

            var options = {
                row_title:    row_title,
                section_hash: section_hash
            };
            var output = Mustache.render($("#group_widget_section_template").text(), options);
            var $group = $(output).appendTo($group_widget.find(".tbGroupWidgetSections"));

            initSortables();
            section_keys.push(options.section_hash);

            return $group;
        };

        $group_widget.find(".tbGroupWidgetSections").sortable({
            forcePlaceholderSize: true,
            handle: ".s_drag_area",
            tolerance: "pointer"
        });

        $group_widget.find(".tbGroupWidgetAddGroup").bind("click", function() {
            addGroup();
        });

        $group_widget.on("click", ".tbGroupWidgetRemoveGroup", function() {
            if (confirm("Are you sure?")) {
                $(this).closest(".tbGroupWidgetSection").remove();

                var $rows = $group_widget.find(".tbGroupWidgetSections > .tbGroupWidgetSection");

                $rows.each(function() {
                    var $rowTitle = $(this).find(".tbRowTitle").first();

                    if (tbHelper.is_numeric($rowTitle.text().trim())) {
                        $rowTitle.text(parseInt($rows.index(this)) + 1);
                    }
                });
            }

            return false;
        });

        var subwidget_map = JSON.parse($widget.find(".tbGroupWidgetSubwidgetMap").text());
        var $subwidgets = $widget.find("div.s_widget_subwidgets > .tbWidget");

        if (section_keys.length) {
            $.each(section_keys, function(section_index, section_key) {

                var $group = addGroup(section_key);

                if (subwidget_map[section_key] !== undefined) {
                    $.each(subwidget_map[section_key], function(index, value) {
                        $subwidgets.each(function() {
                            if ($(this).attr("id") == value) {
                                $group.find("div.s_widget_subwidgets").append($(this));
                            }
                        });
                    });
                }
            });
        } else {
            addGroup();
        }

        disableRow();

        $group_widget.find(".tbGroupWidgetUpdate").bind("click", function() {
            subwidget_map = {};
            section_keys = [];

            $group_widget.find(".tbGroupWidgetSection").each(function() {
                var section_hash = $(this).find("input[name='section_hash']").val();

                section_keys.push(section_hash);

                if (subwidget_map[section_hash] === undefined) {
                    subwidget_map[section_hash] = [];
                }

                $(this).find(".tbWidget").each(function() {
                    $(this).removeAttr("new_subwidget");
                    subwidget_map[section_hash].push($(this).attr("id"));
                    $widget.find("div.s_widget_subwidgets").append($(this));
                });
            });

            var titles_map = JSON.parse($widget.find(".tbGroupWidgetSectionTitlesMap").text());

            $.each(titles_map, function(index) {
                if (-1 == section_keys.indexOf(index)) {
                    delete titles_map[index];
                }
            });

            $group_widget.remove();

            $widget.find(".tbGroupWidgetSubwidgetMap").text(JSON.stringify(subwidget_map));
            $widget.find(".tbGroupWidgetSectionKeys").text(JSON.stringify(section_keys));
            $widget.find(".tbGroupWidgetSectionTitlesMap").text(JSON.stringify(titles_map));

            enableRow();

            return false;
        });
    });
}

function changeGridProportion($row, columns_num) {

    $row.find("div.s_builder_cols_wrap > div.s_builder_col").alterClass("tb_grid_*");

    var $cols_wrap = $row.find("div.s_builder_cols_wrap").first();
    var proportions = [];

    if (columns_num != 5) {
        for (var i = 1; i <= columns_num; i++) {
            proportions.push("1_" + columns_num);
        }
    } else {
        proportions = ["1_3", "1_6","1_6","1_6","1_6"];
    }

    $.each(proportions, function(index, value) {
        $cols_wrap.find(" > div.s_builder_col").eq(index)
            .addClass("tb_grid_" + value)
            .attr("grid_proportion", value);
        $row.find("div.s_builder_cols_grid_helper > div").eq(index).alterClass("tb_grid_*", "tb_grid_" + value).find("span").text(value.replace("_", "/"));
    });

    if (columns_num > 1 && columns_num < 6) {
        $row.find("div.s_builder_cols_wrap").first().proportionPanels($row);
    } else {
        $row.find("div.tb_drag_handle").remove();
    }
}

function initRow($row, $container) {

    $row.find("a.tbBuilderRowRemove").bind("click", function() {
        if (confirm('Are you sure?')) {
            $row.fadeOut(400, function() {
                var $parent_container = $row.parent("div.builder_container");
                $row.remove();
                reArrangeRows($parent_container);
            });
        }

        return false;
    });

    var distributeAutoProportions = function() {

        var proportions = [];
        var words = 0;
        var sum = 0;

        $row.find(".s_builder_cols_grid_helper > div > span").each(function() {
            proportions.push($(this).text());
        });

        $.each(proportions, function(index, value) {
            if (value == 'auto' || value == 'fill') {
                words++;

                return true;
            }

            var splited = value.split('/');

            sum += Number(splited[0]) / Number(splited[1]);
        });

        if (sum == 0.9999999999999999) {
            sum = 1;
        }

        if (0 == words) {
            return;
        }

        var space_per_col = (1 - sum)/words * 100;
        var $cols_wrap = $row.find("div.s_builder_cols_wrap").first();

        $.each(proportions, function(index, value) {
            if (value == "auto" || value == "fill") {
                $cols_wrap.find(" > div.s_builder_col").eq(index).css("width", space_per_col + "%");
            }
        });
    };
    distributeAutoProportions();

    $row.find("a.tbBuilderRowCustomProportions").bind("click", function() {
        var default_proportions = [];

        $row.find(".s_builder_cols_grid_helper > div > span").each(function() {
            default_proportions.push($(this).text());
        });

        var proportions = prompt("Custom row proportions", default_proportions.join(" + "));

        if (null !== proportions) {

            proportions = proportions.split("+").map(function (str) {
                return str.trim();
            });

            var error = null,
                invalid_fractions = null,
                sum = 0,
                is_12_grid = true;

            if (proportions.length == 1 && proportions[0] == 1) {
                sum = 1;
            } else {
                var valid_fractions = ["1/2", "1/3", "2/3", "1/4", "3/4", "1/5", "2/5", "3/5", "4/5", "1/6", "5/6", "1/8", "3/8", "5/8", "7/8", "1/12", "5/12", "7/12", "11/12"];
                var has_words = 0;


                $.each(proportions, function(index, value) {

                    if (value == 'auto' || value == 'fill') {
                        has_words++;
                        is_12_grid = false;

                        return true;
                    }

                    var splited = value.split('/');

                    if (splited.length != 2 || parseInt(splited[0]) != Math.abs(Number(splited[0])) || parseInt(splited[1]) != Math.abs(Number(splited[1])) || splited[0] == 0 || splited[1] == 0) {
                        error = 'Wrong proportion format detected. Each proportion must be defined like x/y, where x and y must be positive integers.';

                        return false;
                    }

                    if (-1 == valid_fractions.indexOf(value)) {
                        error = "Invalid fractions detected:\n";
                        if (null === invalid_fractions) {
                            invalid_fractions = [];
                        }
                        invalid_fractions.push(value);
                    }

                    var numerator   = Number(splited[0]);
                    var denominator = Number(splited[1]);

                    sum += numerator / denominator;

                    if (12 % denominator != 0) {
                        is_12_grid = false;
                    }
                });

                if (sum == 0.9999999999999999) {
                    sum = 1;
                }
            }

            if (null === error && (sum != 1 && !has_words || has_words && sum >= 1)) {
                error = 'The sum of the proportions must be equal to 1';
            }

            if (null !== error) {
              if (null !== invalid_fractions) {
                  error += invalid_fractions.join("  ") + "\n Please, refer to the documentation to review which fractions you can use";
              }
              alert(error);

              return false;
            }

            var $col_input = $row.find("div.tb_columns_num input");
            var cols_num = Number($col_input.val());

            if (proportions.length != cols_num) {
                if (proportions.length > cols_num) {
                    for (var i = 0; i < proportions.length - cols_num; i++) {
                        changeColumnsNum($col_input[0], "++");
                    }
                } else {
                    for (var i = 0; i < cols_num - proportions.length; i++) {
                        changeColumnsNum($col_input[0], "--");
                    }
                }
            }

            if (proportions.length == 1 && proportions[0] == 1) {
                return;
            }

            if (has_words) {
                var space_per_col = (1 - sum)/has_words * 100;
            }

            var $cols_wrap = $row.find("div.s_builder_cols_wrap").first();

            $.each(proportions, function(index, value) {
                var $col =  $cols_wrap.find(" > div.s_builder_col").eq(index);

                $col.alterClass("tb_grid_*")
                    .addClass("tb_grid_" + value.replace("/", "_"))
                    .attr("grid_proportion", value.replace("/", "_"));

                if (has_words && (value == "auto" || value == "fill")) {
                    $col.css("width", space_per_col + "%");
                }

                $row.find("div.s_builder_cols_grid_helper > div").eq(index).alterClass("tb_grid_*", "tb_grid_" + value.replace("/", "_")).find("span").text(value);
            });

            if (is_12_grid && cols_num < 6) {
                $row.find("div.s_builder_cols_wrap").first().proportionPanels($row);
            } else {
                $row.find("div.tb_drag_handle").remove();
            }
        }

        return false;
    });

    $row.find("a.tbBuilderRowDuplicate").bind("click", function() {
        if (!confirm('Are you sure you want to duplicate the whole row?')) {
            return false;
        }

        var newRow = $($row.outerHTML());
        var new_id = tbHelper.generateUniqueId(6);

        newRow.attr("id", "row_" + new_id).attr("idstr", new_id);
        newRow.find("div.s_widget").each(function() {
            if ($(this).is(".tbWidgetLocked")) {
                $(this).remove();
            } else {
                $(this).attr("id", $(this).attr("id").replace(/(.*)_(.*)/, "$1_" + tbHelper.generateUniqueId(8)));
            }
        });
        newRow.find('input[id^="columns_num_"]').attr("id", "columns_num_" + new_id);
        newRow.find("a.tbBuilderRowEdit").jurlp("query", {row_id: new_id});
        newRow.find('.tbBuilderColumn').each(function() {
            $(this).data("column_id", tbHelper.generateUniqueId(5));
        });
        /*
        newRow.find('div[id^="builder_col_"]').each(function() {
            $(this).attr("id", $(this).attr("id").replace(/builder_col_(.*)_(.*)/, "builder_col_$1_" + new_id + "_$2"));
        });
        */

        newRow.insertAfter($row);
        initRow(newRow, $container);
        initWidgetsPanel();
        reArrangeRows($row.parent("div.builder_container"));

        return false;
    });

    $row.find("> div.s_builder_cols div.s_widget").each(function() {
        initWidget($(this));
    });

    var initSortables = function() {

        $container.find("div.s_builder_col").sortable({
            forcePlaceholderSize: true,
            handle: "h3",
            cancel: ".ui-state-disabled",
            connectWith: $container.find("div.s_builder_col"),
            stop: function(event, ui) {
                if (ui.item.is("[id_prefix]")) {
                    ui.item.attr("id", ui.item.attr("id_prefix") + "_" + tbHelper.generateUniqueId(8));
                    ui.item.removeAttr("id_prefix").css('width', '');
                    initWidget(ui.item);
                    checkLockedWidgets();
                }

                var $panel = $container.closest(".tbBuilderPanel");

                ui.item.find("a.tbEditWidget").jurlp("query", {
                    area_name: $panel.find('input[name="area_name"]').val(),
                    area_type: $panel.find('input[name="area_type"]').val(),
                    area_id:   $panel.find('input[name="area_id"]').val()
                });
            }
        }).disableSelection();

        $row.find("div.s_sortable_holder").first().sortable({
            forcePlaceholderSize: true,
            handle: "span.s_drag_area",
            axis: "y"
        });

    };
    initSortables();

    var changeColumnsNum = function(el, operation) {
        if (operation == '++' && el.value < 6) {
            el.value++;
        } else
        if (operation == '--' && el.value > 1) {
            el.value--;
        } else {
            return;
        }
        $(el).attr("value", el.value);

        var cnt = el.value;
        var div_cnt = $row.find("div.s_builder_cols_wrap > div.s_builder_col").length;
        var alter_cnt = Math.abs(parseInt(cnt) - parseInt(div_cnt));

        if (cnt < div_cnt) {
            var $emptyCols = $row.find("div.s_builder_cols_wrap > div.s_builder_col:empty:lt(" + alter_cnt + ")");

            $row.find("div.s_builder_cols_grid_helper > div:lt(" + alter_cnt + ")").remove();

            alter_cnt -= $emptyCols.length;
            $emptyCols.remove();
        }

        for (var i = 0; i < alter_cnt; i++) {
            if (cnt > div_cnt) {
                $row.find("div.s_builder_cols_wrap").append('<div class="s_builder_col tbBuilderColumn" data-column_id="' + tbHelper.generateUniqueId(5) + '"></div>');
                $row.find("div.s_builder_cols_grid_helper").append('<div class="tb_grid_1_3"><span>1/3</span></div>');
            } else {
                $row.find("div.s_builder_cols_wrap > div.s_builder_col").eq(div_cnt-i-1).remove();
                $row.find("div.s_builder_cols_grid_helper > div").eq(div_cnt-i-1).remove();
            }
        }

        changeGridProportion($row, cnt);
        if (operation == '++' && el.value <= 6) {
            initWidgetsPanel();
            initSortables();
            $container.find("div.group_widget_container_interface").each(function() {
                $(this).find("a.button_update").trigger("click");
            });
        }
    };

    $row.find("div.tb_columns_num").first().find("a.tb_button_increase").bind("click", function() {
        changeColumnsNum($(this).next("input")[0], "++");
    }).end().find("a.tb_button_decrease").bind("click", function() {
        changeColumnsNum($(this).prev("input")[0], "--");
    });

    var show_drag_handles = true;
    var proportions = [];
    var has_words = 0;
    var sum = 0;

    $row.find(".s_builder_col").each(function(index) {
        if (12 % Number($(this).attr("grid_proportion").split("_")[1]) != 0) {
            show_drag_handles = false;
        }
        proportions[index] = $(this).attr("grid_proportion");
        if ($(this).attr("grid_proportion") == "auto" || $(this).attr("grid_proportion") == "fill") {
            has_words++;
        } else {
            var splited = $(this).attr("grid_proportion").split('/');

            sum += Number(splited[0]) / Number(splited[1]);
        }
    });

    if (show_drag_handles) {
        $row.find("div.s_builder_cols_wrap").first().proportionPanels($row);
    } else
    if (has_words) {
        var $cols_wrap = $row.find("div.s_builder_cols_wrap").first();
        var space_per_col = (1 - sum)/has_words * 100;

        $.each(proportions, function(index, value) {
            if (value == "auto" || value == "fill") {
                $cols_wrap.find(" > div.s_builder_col").eq(index).css("width", space_per_col + "%");
            }
        });
    }

    var getAreaSettings = function($editElement) {
        var area_settings = "";
        var $inputs = $("#style_settings_" + $container.attr("widget_area")).find(":input");

        var query   = $.jurlp($editElement.attr("href")).query();

        if ($inputs.length && query.area_type == $inputs.filter('input[name="area_type"]').val() && query.area_id == $inputs.filter('input[name="area_id"]').val()) {
            area_settings = JSON.stringify($inputs.serializeJSON());
        }

        return area_settings;
    };

    $row.find(".tbBuilderRowEdit").first().sModal({
        width:  800,
        height: 620,
        fixed:  false,
        requestMethod: "POST",
        requestData: function() {

            var $tbEditRow = $row.find(".tbBuilderRowEdit");
            var preset_id = $tbEditRow.data("load_preset");
            var apply_preset = $tbEditRow.data("apply_preset");

            if (preset_id !== undefined) {
                $tbEditRow.removeData("load_preset");
            }

            if (apply_preset !== undefined) {
                $tbEditRow.removeData("apply_preset");
            }

            var $columns = $(this).closest(".tbBuilderRow").find(".tbBuilderColumn");

            var column_ids = $columns.map(function() {
              return $(this).data("column_id");
            }).get();

            var grid_proportions = {};

            $columns.each(function() {
                grid_proportions[$(this).data("column_id")] = $(this).attr("grid_proportion");
            });

            return {
                preset_id        : preset_id !== undefined ? preset_id : "",
                apply_preset     : apply_preset !== undefined ? apply_preset : false,
                column_ids       : column_ids,
                grid_proportions : grid_proportions,
                settings         : $row.find("textarea.tbBuilderRowSettings").text(),
                column_settings  : $row.find("textarea.tbBuilderRowColumnSettings").text(),
                area_settings    : getAreaSettings($(this)),
                theme_colors     : JSON.stringify($("#colors_form_rows").find(':input[name^="colors"]').serializeJSON())
            };
        },
        onClick: function() {
            tbApp.removeDomInstance("widget_duplicate_submenu");
        },
        onShow: function(obj) {

            var loadPreset = function(apply_preset) {

                if (apply_preset === undefined) {
                    apply_preset = 0;
                }

                var preset_id = obj.find("select[name='preset_id']").val();

                if (String(preset_id) == "0") {
                    return false;
                }

                var $tbEditRow = $row.find(".tbBuilderRowEdit");

                $tbEditRow.data("load_preset", preset_id);
                $tbEditRow.data("apply_preset", apply_preset ? 1 : 0);
                obj.close(0);
                $tbEditRow.trigger("click");

                return true;
            };

            obj.find("div.sm_content").first().on("click", function() {
                tbApp.removeDomInstance("colors_inherit_menu");
            });

            obj.find(".tbLoadPreset").bind("click", function() {
                loadPreset();

                return false;
            });

            obj.find(".tbApplyPreset").bind("click", function() {

                if ($(this).hasClass("tbApplyClear")) {

                    $(this)
                        .parent().find("input[name='widgets_row[preset_id]']").val("").end()
                        .find("select[name='preset_id']").val(0);

                    obj.find(".tb_color_row.tb_disabled").each(function() {
                        if ($(this).find(".tbColorToggleInherit").length) {
                            $(this).find("input[name$='[inherit]']").val(1);
                        }
                    });

                    obj.find("a.update_settings").trigger("click");

                    return false;
                }

                if (!loadPreset(true)) {
                    return false;
                }

                return false;
            });

            if (obj.find("input[name='widgets_row[preset_id]']").val()) {
                $("#style_settings_widgets_row_box_shadow, #style_settings_widgets_row_background, #style_settings_widgets_row_border").addClass("tb_disabled");

                obj.find(".tbMarginFieldset, .tbPaddingFieldset").addClass("tb_disabled");

                $.each(JSON.parse(obj.find(".tbPresetBoxColorKeys").text()), function(index, value) {
                    $("#row_colors_group_" + value).addClass("tb_disabled");
                });

                $.each(JSON.parse(obj.find(".tbPresetBoxFontKeys").text()), function(index, value) {
                    obj.find("fieldset[id*='row_typography_group_" + value + "']").addClass("tb_disabled");
                });

                obj.find("#row_settings_holder").prepend(Mustache.render($("#preset_name_info_template").text(), {
                    preset_name: obj.find("select[name='preset_id'] option:selected").text()
                }));

                obj.find(".tbLoadPreset").hide();
                obj.find(".tbApplyPreset").addClass("tbApplyClear").text("Clear");
                obj.find("select[name='preset_id']").parent().addClass("tb_disabled");
            }

            var initCommonOptionsSection = function($tab, $panel, section_id, input_property) {

                if ($tab.data("initialized")) {
                    return;
                }

                beautifyForm($panel);

                if ($tab.attr("section") == "layout") {
                    if (section_id == 'widgets_row') {
                        $panel.find('input[name$="[layout][inner_padding]"]').bind("spinstop", function() {
                            if ($panel.find(':checkbox[name$="[layout][separate_columns]"]').is(":checked")) {
                                obj.find(".tbRowColumnPadding :checked").closest("fieldset").find("input[name*='[padding_']").val($(this).val());
                            }
                        });

                        $panel.find(':checkbox[name$="[layout][separate_columns]"]').bind("change", function() {
                            var is_checked = $(this).is(":checked");
                            var inner_padding = obj.find("input[name='widgets_row[layout][inner_padding]']").val();

                            $(this).closest("fieldset").find(".tbNormalColumns").toggle(!is_checked);
                            $(this).closest("fieldset").find(".tbSeparateColumns").toggle(is_checked);

                            if (is_checked) {
                                if ($(this).data("has_changed")) {
                                    $panel.find(".tbPaddingWrap input").val(0);
                                    obj.find(".tbRowColumnPadding").show().find(':checkbox[name$="[layout][inherit_padding]"]').prop("checked", true).trigger("change");
                                }
                                $panel.find(".tbPaddingWrap").addClass('tb_disabled');
                            } else {
                                $panel.find(".tbPaddingWrap").removeClass('tb_disabled');
                                obj.find(".tbRowColumnPadding").hide().find(':checkbox[name$="[layout][inherit_padding]"]').prop("checked", false).trigger("change");
                            }

                            if ($(this).data("has_changed")) {
                                obj.find(".tbRowColumnPadding").closest("fieldset").each(function() {
                                    $(this).find("input[name*='[padding_']").val(is_checked ? inner_padding : 0);
                                });
                            }

                            obj.find('.tbRowMainTabs  > div[data-section_id^="row_column_"] .tbWidgetsRowMainTabs').each(function() {
                                $(this).find("a[section='border']").each(function() {
                                    $(this).parent("li").addBack($($(this).attr("href"))).toggle(!is_checked);
                                });

                                if (is_checked && $(this).data("uiTabs")) {
                                    $(this).tabs("option", "active", 0);
                                }
                            });

                            $(this).data("has_changed", true);
                        }).trigger("change");
                    }

                    if (tbHelper.str_begins_with("row_column", section_id)) {
                        $panel.find(':checkbox[name$="[layout][inherit_padding]"]').bind("change", function() {
                            $(this).closest("fieldset").find(".tbPaddingWrap").toggleClass("tb_disabled", $(this).is(":checked"));
                            if ($(this).is(":checked")) {
                                $(this).closest("fieldset").find("input[name*='[padding_']").val(obj.find("input[name='widgets_row[layout][inner_padding]']").val());
                            }
                        }).trigger("change");

                        $panel.find(':checkbox[name$="[layout][is_sticky]"]').bind("change", function() {
                            $(this).closest(".tbColumnSticky").find('input[name$="[sticky_offset]"]').closest("div").toggle($(this).prop("checked"));
                        }).trigger("change");
                    }
                }

                tbApp.initStyleSection($tab.attr("section"), section_id, input_property);
                $tab.data("initialized", true);
            };

            obj.find("a.update_settings").bind("click", function() {
                obj.find("div.sm_content").first().block({ message: '<h1>Updating row settings</h1>' });

                var query = $.jurlp($row.find("a.tbBuilderRowEdit").first().attr("href")).query();
                var $form = $(this).closest("form");
                var section = $.jurlp($form.attr("action")).query().section;
                var data = {
                    area_name:     $container.attr("widget_area"),
                    area_id:       query.area_id,
                    area_type:     query.area_type,
                    area_settings: getAreaSettings($row.find("a.tbBuilderRowEdit").first()),
                    theme_colors:  JSON.stringify($("#colors_form_rows").find(':input[name^="colors"]').serializeJSON()),
                    settings:      JSON.stringify($form.find(":input").serializeJSON()[section])
                };

                $.post($form.attr("action"), $.param(data), function(response) {
                    $row.find("textarea.tbBuilderRowSettings").text(response.row_settings);
                    $row.find("textarea.tbBuilderRowColumnSettings").text(response.column_settings);
                    setTimeout(function() {
                        obj.find("div.sm_content").first().unblock();
                        obj.close();
                    },500);
                }, "json");

                return false;
            });

            var initMainTab = function($mainTab, $mainPanel) {

                var updateColumnColors = function() {
                    if (tbHelper.str_begins_with("widgets_row[columns]", $mainPanel.data("input_property"))) {
                        $mainPanel.find("div[id$='_colors']").find("div[parent_id].tb_inherit").each(function() {
                            var new_color = $("#" + $(this).attr("parent_id")).find('input[name$="[color]"]').val();

                            $(this).find('input[name$="[color]"]').val(new_color).trigger("updateColor");

                            if ($(this).hasClass('tbHasChildren')) {
                                $(this).closest("fieldset").parent()
                                    .find('.tb_inherit[parent_id="' + $(this).attr("id") + '"]')
                                    .find('input[name$="[color]"]').val(new_color).trigger("updateColor");
                            }
                        });
                    }
                };
                updateColumnColors();

                if (tbHelper.str_begins_with("widgets_row[columns]", $mainPanel.data("input_property"))) {
                    $mainPanel.find(".tbColumnSticky").toggle(obj.find('select[name$="[layout][sticky_columns]"]').val() == "custom");
                }

                if ($mainTab.data("initialized")) {
                    return;
                }

                if ($mainPanel.is("#row_advanced_settings_holder")) {
                    $mainPanel.find('select[name$="[layout][sticky_columns]"]').bind("change", function() {
                        $(this).closest("fieldset").find("input[name$='[sticky_offset]']").parent("div").toggle($(this).val() == 'all');
                    }).trigger("change");

                    $mainPanel.find('.tbExtraClassTabs').tabs();
                }

                $mainPanel.find("div.tbWidgetsRowMainTabs").first().tabs({
                    activate: function(event, ui) {
                        initCommonOptionsSection(ui.newTab.find("a"), ui.newPanel, $mainPanel.data("section_id"), $mainPanel.data("input_property"));
                        if (ui.newTab.find("a").attr("section") == "colors") {
                            updateColumnColors();
                        }
                    },
                    create: function(event, ui) {
                        initCommonOptionsSection(ui.tab.find("a"), ui.panel, $mainPanel.data("section_id"), $mainPanel.data("input_property"));
                    }
                });

                tbApp.updatePreviewBox($mainPanel.data("section_id"));
                $mainTab.data("initialized", true);

            };

            obj.find(".tbRowMainTabs").tabs({
                activate: function(event, ui) {
                    initMainTab(ui.newTab.find("a"), ui.newPanel);
                },
                create: function(event, ui) {
                    initMainTab(ui.tab.find("a"), ui.panel);
                }
            });

            var $panel = $container.parent();
            var comboBox = tbApp.builderComboBoxFactory($panel, $panel.find(".tbRowsContainer").attr("widget_area"));
            var modalLabel = $panel.find(".tbAreaName").text() + " Area > ";
            var pageType = comboBox.getPageType();

            if (pageType != "") {
                modalLabel += pageType + " ";
            }

            modalLabel += comboBox.getLabel() + " > Row " + $row.find(".row_order").text();
            obj.find(".sm_title span").text(modalLabel);
        },
        onClose: function(obj) {
            obj.find("div.colorSelector").each(function() {
                $("#" + $(this).data("colorpickerId")).remove();
            });
            obj.find("div.s_widget_options_holder").first().trigger("closeWindow", [obj]);
        }
    });
}

function reArrangeRows($container) {

    var $rows = $container.find("> .tbBuilderRow");

    $rows.each(function() {
        var row_index = $rows.index(this);
        $(this).find("span.row_order").first().text(parseInt(row_index) + 1);
    });
}

function removeNonMatchingWidgets($panel) {
    var removed = false;

    $panel.find(".tbBuilderRow").each(function() {
        var $row = $(this);

        $row.find(".s_widget").each(function() {
            var class_name = $(this).attr("id").slice(0, $(this).attr("id").lastIndexOf("_"));

            if (class_name.match(/Theme_.*?SystemWidget/) && !$("#layout_builder_widgets_blocks").find(".tbSystemWidgetsList .s_widget[data-slot_name='" + $(this).data("slot_name") + "']").length) {
                removed = true;
                $(this).remove();
            }
        });
        if (removed && !$row.find(".s_widget").length) {
            $row.remove();
            reArrangeRows($panel.find(".tbRowsContainer"));
        }
    });
    if (removed) {
        $panel.find(".tbRowsContainer").before('<div class="s_server_msg s_msg_blue tbRecordInfoMessage2"><p class="s_icon_16 s_info_16">Some non-compatible system blocks have been removed.</p></div>');
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
        currentItem: null,

        create: function() {
            return this;
        },

        afterBuilderTabSave: function(data) {
            if (typeof this.currentItem.key == "undefined" || this.comboBox.getContentsElement().find(".tbModifiedMenu [" + this.currentItem.key + "_id='" + this.currentItem.optionValue + "']").length == 0) {
                this.reloadModified();
            }
            this.panel.find(".tbRecordInfoMessage1").hide();

            if (typeof data.override_msg != "undefined" && data.override_msg != "") {
                this.panel.find(".tbRecordInfoMessage2").show().find(".tbOverrideMsg").text(data.override_msg);
            }
        },

        init: function() {

            var self = this;

            self.contents = this.panel.find(".tbComboBoxRow");
            var tmp = self.contents.find("select.tbComboBox").tbComboBox({
                $contents: self.contents,
                select:    $.proxy(self, "_onMenuItemSelect"),
                remove:    $.proxy(self, "_onMenuItemRemove")
            });

            self.comboBox = tmp.data("uiTbComboBox");
            self.checkWidgetsMatch();

            if (self.currentItem === null) {
                var area_id = self.comboBox.getContentsElement().find("input[name='area_id']").val();
                var $option = self.comboBox.getContentsElement().find("option[value='" + area_id + "']");

                self.currentItem = {
                    option:      $option[0],
                    key:         self.comboBox.getContentsElement().find("input[name='area_type']").val(),
                    label:       $option.text(),
                    optionValue: area_id,
                    remove:      false,
                    value:       $option.text()
                };
            }

            return self;
        },

        loadSettingsData: function(type, record_id) {

            var self = this;
            var chosen = self.comboBox.parent.exportValue();
            var pageType = self.pageType != "" ? self.pageType + ":" : "";

            self.panel.block();

            return $.get($sReg.get('/tb/url/layoutBuilder/areaBuilder') + "&area_name=" + self.widget_area + "&area_type=" + type + "&area_id=" + record_id, function(data) {
                self.panel.empty().append(data).unblock();
                tbApp.initBuilderTab(self.panel);
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
            var url = $sReg.get('/tb/url/layoutBuilder/modifiedMenu') + "&area_name=" + self.widget_area + "&record_type=builder";

            $.get(url, function(modified_menu) {
                self.comboBox.modifiedMenu = undefined;
                self.contents.find(".tbModifiedMenu").remove();
                self.contents.append(modified_menu);
            });
        },

        reloadSystemBlocks: function(area_type, area_id) {

            $("#layout_builder_widgets_blocks").block();

            if (typeof area_type == "undefined") {
                area_type = this.comboBox.parent.value();
            }

            if (typeof area_id == "undefined") {
                area_id = this.comboBox.parent.value();
            }

            return $.get($sReg.get('/tb/url/layoutBuilder/systemBlocks') + "&area_name=" + this.widget_area + "&area_type=" + area_type + "&area_id=" + area_id, function(data) {
                if (data.trim() == "") {
                    data = "<p>There are no system blocks for the current area.</p>";
                }
                $("#layout_builder_widgets_blocks").find(".tbSystemWidgetsList").html("<div>" + data + "</div>");
                initWidgetsPanel();
                $("#layout_builder_widgets_blocks").unblock();
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

            return this.reloadSystemBlocks(uiItem.key, uiItem.optionValue);
        },

        checkWidgetsMatch: function() {
            $panel.find(".tbWidget").each(function() {
                var class_name = $(this).attr("id").slice(0, $(this).attr("id").lastIndexOf("_"));

                if (class_name.match(/Theme_.*?SystemWidget/)) {
                    return true;
                }

                if ($(this).is("[id^='Theme_OpenCartWidget']")) {
                    var name = $(this).find("> h3").first().text();
                    var is_missing = true;

                    $("#layout_builder_widgets_modules .tbWidget > h3").each(function() {
                        if ($(this).text() == name) {
                            is_missing = false;
                            return false;
                        }
                    });
                    if (is_missing) {
                        $(this).addClass("tb_block_missing")
                            .find("> h3").prepend("*missing* ").end()
                            .find(".tbEditWidget, .tbWidgetDuplicate").remove();
                    }

                    return true;
                }

                if (!$("#layout_builder_widgets_custom").find("[id_prefix='" + class_name + "']").length) {
                    $(this).addClass("tb_block_missing")
                        .find("> h3").prepend("*missing* ").end()
                        .find(".tbEditWidget, .tbWidgetDuplicate").remove();
                }
            });
        },

        _onMenuItemSelect: function(event, ui) {
            $.when(this.setMenuItem(ui.item), this.loadSettingsData(ui.item.key, ui.item.optionValue)).done(function() {
                removeNonMatchingWidgets($panel);
            });
            ui.context.blur();
        },

        _onMenuItemRemove: function(event, ui) {

            var item = ui.item;
            var self = this;

            if (!confirm('Delete "' + item.label + '" configuraiton. Are you sure ?')) {
                return false;
            }

            var current_key = typeof self.currentItem.key != "undefined" ? self.currentItem.key : item.key;
            var current_value = typeof self.currentItem.optionValue != "undefined" ? self.currentItem.optionValue : item.optionValue;
            var params = {
                area_name         : self.widget_area,
                area_type         : item.key,
                area_id           : item.optionValue,
                current_area_type : current_key,
                current_area_id   : current_value,
                record_type       : "builder"
            };

            $.getJSON($sReg.get('/tb/url/layoutBuilder/removeSettings') + "&" + $.param(params), function(response) {
                if (item.optionValue == ui.context.value() || response.reload == 1) {
                    self.loadSettingsData(current_key, current_value);
                } else {
                    self.reloadModified();
                    ui.context.blur();
                }
            });

            return false;
        }
    }
};

tbApp.builderComboBoxFactoryInstances = [];
tbApp.builderComboBoxFactory = function($panel, widget_area) {

    if (typeof tbApp.builderComboBoxFactoryInstances[widget_area] == "undefined") {
        tbApp.builderComboBoxFactoryInstances[widget_area] = comboBoxConstructor($panel, widget_area).create();
    }

    return tbApp.builderComboBoxFactoryInstances[widget_area];
};

tbApp.builderCollectRowsData = function($panel) {

    var $container = $panel.find("div.builder_container").first();
    var rows = $container.sortable("toArray");
    var data = {};

    data.rows      = [];
    data.area_name = $container.attr("widget_area");
    data.area_type = $panel.find(':input[name="area_type"]').val();
    data.area_id   = $panel.find(':input[name="area_id"]').val();

    $.each(rows, function(index, value) {
        var $row = $("#" + value);
        var columns_data = {};

        $row.find("div.s_builder_col").each(function(iindex) {
            var columns = $(this).sortable("toArray");

            columns_data[iindex] = {};
            columns_data[iindex].id = $(this).data("column_id");
            columns_data[iindex].widgets = [];
            columns_data[iindex].grid_proportion = $(this).attr("grid_proportion");

            $.each(columns, function(iiindex, vvalue) {

                if (vvalue == "") {
                    // This happens if a group widget is opened
                    return true;
                }

                var $widget = $("#" + vvalue);
                if (!$widget.find("> input.tbWidgetDirty").length) {
                    alert("There is no dirty data indicator field for the widget " + vvalue);
                }
                var widget_data = {
                    id: vvalue,
                    settings: $widget.find("> textarea.widget_settings").text(),
                    is_dirty: $widget.find("> input.tbWidgetDirty").val()
                };

                if ($widget.is(".tbGroupWidget")) {
                    var subwidgets = [];

                    $widget.find("div.s_widget_subwidgets > div.s_widget").each(function() {
                        subwidgets.push({
                            id:       $(this).attr("id"),
                            settings: $(this).find("> textarea.widget_settings").val(),
                            is_dirty: $(this).find("> input.tbWidgetDirty").val()
                        });
                    });

                    if (subwidgets.length) {
                        widget_data.subwidgets = subwidgets;
                        widget_data.subwidget_map = JSON.parse($widget.find(".tbGroupWidgetSubwidgetMap").text());
                        widget_data.section_keys = JSON.parse($widget.find(".tbGroupWidgetSectionKeys").text());
                    }
                }

                columns_data[iindex].widgets.push(widget_data);
            });
        });

        data.rows.push({
            id:              $row.attr("idstr"),
            columns:         columns_data,
            columns_number:  $("#columns_num_" + $row.attr("idstr")).val(),
            settings:        $row.find("textarea.tbBuilderRowSettings").first().text(),
            column_settings: $row.find("textarea.tbBuilderRowColumnSettings").first().text()
        });
    });

    return data;
};

tbApp.initBuilderTab = function($panel) {

    var $container = $panel.find("div.builder_container").first();

    initWidgetsPanel();

    $container.sortable({
        forcePlaceholderSize: true,
        handle: "div.s_builder_row_header > h3",
        stop: function() {
            reArrangeRows($container);
        }
    });

    $container.find("> .tbBuilderRow").each(function() {
        initRow($(this), $container);
    });

    $panel.find(".tbCopyArea").bind("click", function() {
        if (confirm("Are you sure?")) {
            $.getJSON($(this).attr("href"), { new_store_id: $(this).siblings("select").val() });
        }

        return false;
    });

    $panel.find(".tbDeleteArea").bind("click", function() {
        if (confirm("Are you sure?")) {
            var remove_url = $(this).attr("href");
            var comboBox = tbApp.builderComboBoxFactoryInstances[$.jurlp(remove_url).query().area_name];

            $.getJSON(remove_url, function() {
                comboBox.loadSettingsData(comboBox.currentItem.key, comboBox.currentItem.optionValue);
            });
        }

        return false;
    });

    $panel.find(".tbNewWidgetsRow").bind("click", function() {
        var area_name = $panel.find(".tbRowsContainer").attr("widget_area");
        var area_type = $panel.find('input[name="area_type"]').val();
        var area_id   = $panel.find('input[name="area_id"]').val();

        var area_settings = "";
        var $inputs  = $("#style_settings_" + area_name).find(":input");

        if ($inputs.length && area_type == $inputs.filter('input[name="area_type"]').val() && area_id == $inputs.filter('input[name="area_id"]').val()) {
            area_settings = JSON.stringify($inputs.serializeJSON());
        }

        var $add_button = $(this);

        $add_button.after('<span class="tb_loading_inline"></span>');

        $.post($sReg.get('/tb/url/layoutBuilder/getNewWidgetRow') + '&area_name=' + area_name + '&area_type=' + area_type + '&area_id=' + area_id, {
                area_settings: area_settings,
                theme_colors:  JSON.stringify($("#colors_form_rows").find(':input[name^="colors"]').serializeJSON())
            }, function(data) {
                var $row = $(data);

                $row.appendTo($container);
                reArrangeRows($container);
                initRow($row, $container);
                initWidgetsPanel();
                beautifyForm($row);
                $add_button.next('.tb_loading_inline').remove();
            }
        );

        return false;

    });

    $panel.find(".tbAreaSettings").bind("click", function() {

        var area_name = $panel.find(".tbRowsContainer").attr("widget_area"),

            $modal = $(Mustache.render($("#common_modal_dialog_template").text(), {
                width       : 800,
                margin_left : -400
            })).appendTo($("body")),

            $promptWindow = $modal.find(".sm_window").first(),

            all_templates = JSON.parse($panel.find(".tbAreaTemplates").val()),

            default_templates = all_templates.filter(function(item) {
                return item.is_theme;
            }),

            custom_templates = all_templates.filter(function(item) {
                return !item.is_theme;
            }),

            $modal_contents = Mustache.render($("#area_settings_template").text(), {
                export_name       : "Export " + tbHelper.generateUniqueId(),
                area_name         : area_name,
                default_templates : default_templates,
                custom_templates  : custom_templates,
                has_templates     : default_templates.length || custom_templates.length
            });

        $promptWindow
            .find(".sm_content").append($modal_contents).end()
            .find(".tbAreaSettingsTabs").tabs().end()
            .find(".tbTemplatesTabs").tabs();

        $promptWindow.on("click", ".tbRemoveTemplate", function() {

            if (!confirm("Are you sure?")) {
                return false;
            }

            var $templateRow = $(this).closest(".tbTemplateRow");
            var template_id = $templateRow.find("[name='area_template']").val();

            if (!template_id) {
                return false;
            }

            $promptWindow.find('> .sm_window_wrap').block();

            $.post($sReg.get('/tb/url/layoutBuilder/removeAreaTemplate'), {
                area_name   : area_name,
                template_id : template_id,
                is_theme    : $templateRow.find("[name='area_template']").data("is_theme")
            } , function(data) {

                $("#tb_cp_panel_layout_builder").removeClass('tb_noloading');
                $promptWindow.find('> .sm_window_wrap').unblock();

                if (data.success) {
                    $templateRow.remove();

                    var all_templates = JSON.parse($panel.find(".tbAreaTemplates").val()).filter(function(item) {
                        return item.id != template_id;
                    });

                    $panel.find(".tbAreaTemplates").val(JSON.stringify(all_templates));

                }
            }, "json");

            return false;
        });

        $promptWindow.find(".tbLoadTemplate").bind("click", function() {

            var template_id = $promptWindow.find("[name='area_template']:checked").val();

            if (!template_id) {
                return false;
            }

            $promptWindow.find('> .sm_window_wrap').block({ message: '<h1>Loading</h1>' });

            $.getJSON($sReg.get('/tb/url/layoutBuilder/loadAreaTemplate'), {
                area_name   : area_name,
                area_type   : $panel.find('input[name="area_type"]').val(),
                area_id     : $panel.find('input[name="area_id"]').val(),
                template_id : template_id,
                is_theme    : $promptWindow.find("[name='area_template']:checked").data("is_theme")
            } , function(data) {

                if (data.success && data.rows_html) {
                    $panel
                        .find(".tbRowsContainer").empty().append(data.rows_html)
                        .find("> .tbBuilderRow").each(function() {
                            initRow($(this), $panel.find("div.builder_container").first());
                            initWidgetsPanel();
                        });
                    $panel.find(".tbRecordInfoMessage1, .tbRecordInfoMessage2").remove();
                    removeNonMatchingWidgets($panel);

                    $("#tb_cp_panel_layout_builder").removeClass('tb_noloading');
                    $promptWindow.find('> .sm_window_wrap').unblock();
                    $modal.find(".sm_overlayBG").trigger("click");
                } else
                if (!data.success) {
                    $promptWindow.find('> .sm_window_wrap').unblock();
                    alert(data.message);
                }
            });

            return false;
        });

        $promptWindow.show().find("a.sm_closeWindowButton").add($modal.find(".sm_overlayBG")).bind("click", function() {
            $promptWindow.fadeOut(300, function() {
                $promptWindow.parent("div").remove();
            });
        });

        $promptWindow.find(".tbSaveExport").bind("click", function() {
            var export_name = $promptWindow.find("[name='export_name']").val();

            if (!export_name.length) {
                return false;
            }

            $promptWindow.find('> .sm_window_wrap').block({ message: '<h1>Exporting</h1>' });

            $(tbApp).on("tbCp:builderAfterSave.saveAreaTemplate", function(event, result, config, data) {

                $.post($sReg.get('/tb/url/layoutBuilder/saveAreaTemplate'), {
                    area_name   : data.area_name,
                    area_type   : data.area_type,
                    area_id     : data.area_id,
                    export_name : export_name,
                    export_image: $promptWindow.find("[name='export_image']").val(),
                    is_theme    : $promptWindow.find("[name='is_theme']").is(":checked") ? 1 : 0
                } , function(data) {

                    $("#tb_cp_panel_layout_builder").removeClass('tb_noloading');
                    $promptWindow.find('> .sm_window_wrap').unblock();
                    $modal.find(".sm_overlayBG").trigger("click");

                    if (data.success && data.area_templates) {
                        $panel.find(".tbAreaTemplates").val(JSON.stringify(data.area_templates));
                    }
                }, "json");
                $(tbApp).off("tbCp:builderAfterSave.saveAreaTemplate");
            });

            $("#tb_cp_panel_layout_builder").addClass('tb_noloading').find(".tbSaveAreaSettings").trigger("click");

            return false;
        });

        $promptWindow.find(".tbAreaSerializeExport").bind("click", function() {
            $promptWindow.find('> .sm_window_wrap').block({ message: '<h1>Exporting</h1>' });

            var postData = {
                area_name   : area_name,
                area_type   : $panel.find('input[name="area_type"]').val(),
                area_id     : $panel.find('input[name="area_id"]').val()
            };

            $.post($sReg.get('/tb/url/layoutBuilder/serializeArea'), postData, function(data) {
                if (data.success) {
                    $("#area_settings_serialize textarea").val(btoa(encodeURIComponent(JSON.stringify(data.area))));
                }
                $promptWindow.find('> .sm_window_wrap').unblock();
            }, "json");

            return false;
        });

        $promptWindow.find(".tbAreaLoadExport").bind("click", function() {
            $promptWindow.find('> .sm_window_wrap').block({ message: '<h1>Exporting</h1>' });

            var postData = {
                area_name   : area_name,
                area_type   : $panel.find('input[name="area_type"]').val(),
                area_id     : $panel.find('input[name="area_id"]').val(),
                area_data   : decodeURIComponent(atob($("#area_settings_serialize textarea").val()))
            };

            $.post($sReg.get('/tb/url/layoutBuilder/loadAreaExport'), postData, function() {
                var comboBox = tbApp.builderComboBoxFactoryInstances[area_name];

                $promptWindow.find('> .sm_window_wrap').unblock();
                comboBox.loadSettingsData(comboBox.currentItem.key, comboBox.currentItem.optionValue);
                $modal.find(".sm_overlayBG").trigger("click");
            }, "json");

            return false;
        });

        return false;

    });

    var comboBox = tbApp.builderComboBoxFactory($panel, $container.attr("widget_area")).init().comboBox;

    var inherit_key = $panel.find("input[name='inherit_key']").val();

    if (inherit_key != "") {
        $panel.find(".tbRecordInfoMessage1 .tbParentArea")
            .wrap("<a class='tbChangeContent' href='javascript:;'></a>").end()
            .find("a.tbChangeContent").bind("click", function() {

                var inherit_area_type, inherit_area_id, label;

                if (inherit_key.indexOf("category_level_") == 0) {
                    inherit_area_type = 'category';
                    inherit_area_id = inherit_key.substring(9);
                    label = "Level " + inherit_area_id.substring(6) + " categories";
                } else
                if (inherit_key.indexOf("category_global") == 0) {
                    inherit_area_type = 'category';
                    inherit_area_id = 'category_global';
                    label = "All categories";
                } else
                if (inherit_key.indexOf("layout_") == 0) {
                    inherit_area_type = 'layout';
                    inherit_area_id = inherit_key.substring(7);
                    label = $panel.find(".tbLayoutMenu a[layout_id='" + inherit_area_id + "']").text();
                } else
                if (inherit_key.indexOf("global") == 0) {
                    inherit_area_type = 'global';
                    inherit_area_id = 'global';
                    label = "GLOBAL";
                }

                comboBox.parent.value(label, true, inherit_area_id);
                comboBox.parent._trigger("select", null, {
                    item: {
                        element:     null,
                        key:         inherit_area_type,
                        label:       label,
                        optionValue: inherit_area_id,
                        value:       label
                    },
                    context: comboBox
                });
            });
    }
};

tbApp.initBuilderSection = function($tab, $panel) {

    var section = $tab.attr("aria-controls");

    if (!section || $tab.data("initialized") || !/[\S]/.test($("#" + section).html())) {
        return;
    }

    $tab.data("initialized", true);
    tbApp.initBuilderTab($panel);

    $(tbApp).on("tbCp:saveBuilderTab", function(event, config) {

        if (section != config.section) {
            return;
        }

        $(tbApp).trigger("tbCp:builderBeforeSave", [config]);

        $panel
            .block({ message: '<h1>Saving settings</h1>' })
            .find("div.group_widget_container_interface .tbGroupWidgetUpdate")
            .trigger("click");

        var data = tbApp.builderCollectRowsData($panel);

        $.post($sReg.get('/tb/url/layoutBuilder/saveRows'), data, function(result) {
            $panel.unblock();
            tbApp.builderComboBoxFactory($panel, $panel.find("div.builder_container").first().attr("widget_area")).afterBuilderTabSave(result);
            $(tbApp).trigger("tbCp:builderAfterSave", [result, config, data, $panel]);
        }, "json");
    });

};

})(jQuery, tbApp);