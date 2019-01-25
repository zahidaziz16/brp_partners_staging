tbApp.initTags = function($menuContainer, options) {

    var is_dirty = false;

    options = $.extend({
        width:          800,
        margin_left:    -400,
        sticky_sidebar: true
    }, options || {});

    var bindModal = function($menuItem) {

        $menuItem.find(".tbEditMenuItem").bind("click", function() {
            var $output = $(Mustache.render($("#common_modal_dialog_template").text(), {
                width:       options.width,
                margin_left: options.margin_left
            })).appendTo($("body"));

            var $settingsWindow = $output.find(".sm_window").first();

            var $contents = $(Mustache.render($("#stories_tags_template").text(), {
                tags: tbHelper.jsonToArray($sReg.get("/tb/tags"))
            })).appendTo($settingsWindow.find(".sm_content"));

            window.scrollTo(0, 0);

            $settingsWindow.find(".tbUpdateSettings").bind("click", function() {

                is_dirty = true;

                $menuItem.data("settings", form2js($settingsWindow.find("form")[0], ".", true, function(node) {
                    var $node = $(node);

                    if ($node.is(":checkbox")) {
                        if ($node.is('[name$="[]"]')) {
                            return $node.is(":checked") ? $node.val() : false;
                        }

                        return {
                            name:  $node.attr("name"),
                            value: $node.is(":checked") ? 1 : 0
                        };
                    }

                    return false;
                }, false));

                $settingsWindow.fadeOut(300, function() {
                    $settingsWindow.parent("div").remove();
                });

                return false;
            });

            $settingsWindow.find("a.sm_closeWindowButton").add($output.find(".sm_overlayBG")).bind("click", function() {
                $settingsWindow.fadeOut(300, function() {
                    $settingsWindow.parent("div").remove();
                });
            });

            if ($menuItem.data("settings")) {
                js2form($settingsWindow.find("form")[0], $menuItem.data("settings"));
            }

            $settingsWindow.show();
            beautifyForm($settingsWindow);
            $settingsWindow.find(".colorSelector").each(function() {
                assignColorPicker($(this));
            });

            if (typeof $menuItem.data("settings").accent_bg != "undefined") {
                $settingsWindow.find('input[name="accent_bg"]')
                    .prev(".colorSelector")
                    .find("div").css("background-color", $menuItem.data("settings").accent_bg);
            }

            if (typeof $menuItem.data("settings").accent_color != "undefined") {
                $settingsWindow.find('input[name="accent_color"]')
                    .prev(".colorSelector")
                    .find("div").css("background-color", $menuItem.data("settings").accent_color);
            }

            return false;
        });
    };

    $menuContainer.find(".tb_menu_listing ol.tbSortable").nestedSortable({
        items: "li:not(.ui-state-disabled)",
        handle: "h3",
        toleranceElement: '> div',
        maxLevels: 2
    }).end()
    .find("input.tbItemFilter").each(function() {
        $(this).fastLiveFilter($(this).parents(".tbMenuItemsTab").first().find(".tbItemsList"));
    }).end()
    .find(".tbAddMenuItem").bind("click", function() {

        is_dirty = true;

        var $section = $(this).parents(".tbLanguagePanel").first();
        var title = $(this).parent().text();

        var menuItem = Mustache.render($("#store_menu_template").text(), {
            id:    tbHelper.generateUniqueId(10),
            title: title
        });

        var item_id = $(this).attr("item_id").split("_")[1];

        var $menuItem = $(menuItem).appendTo($section.find(".tb_menu_listing ol").first()).data({
            id: item_id
        });

        var tpl = Mustache.render($("#stories_tags_template").text());
        var default_settings = form2js($(tpl).find("form")[0], ".", false, null, false);

        $menuItem.data("settings", default_settings);
        bindModal($menuItem);
    }).end()
    .find(".tbLanguagePanel").each(function() {
        var $tabContents = $(this);
        var menuData = JSON.parse($(this).find("textarea.tbMenuData").first().text());

        var buildMenu = function(menuData, parent) {
            $.each(menuData, function(index, value) {
                var title;

                title = $tabContents.find('[item_id="tag_' + value.data.id + '"]').parent("li").text();

                var menuItem = Mustache.render($("#store_menu_template").text(), {
                    id:         tbHelper.generateUniqueId(10),
                    title:      title
                });

                var $toAppend;

                if (typeof parent != "undefined" ) {
                    if (!parent.find("> ol").length) {
                        parent.append("<ol></ol>");
                    }
                    $toAppend = parent.find("> ol");
                } else {
                    $toAppend = $tabContents.find(".tb_menu_listing ol").first();
                }

                var $menuItem = $(menuItem).appendTo($toAppend).data({
                    id:   value.data.id
                });

                bindModal($menuItem);

                if (typeof value.data.settings != "undefined") {
                    $menuItem.data("settings", value.data.settings);
                }

                if (typeof value.children != "undefined") {
                    buildMenu(value.children, $menuItem);
                }
            });
        };

        buildMenu(menuData);
    }).end()
    .on("click", ".tbRemoveMenuItem", function() {
        $(this).parents("li").first().remove();
        is_dirty = true;
    });

    if (options.sticky_sidebar) {
        $menuContainer.find(".tbMenuTabs").parent().stickySidebar({
            padding: 30
        });
    }

    var updateMenuData = function(tree) {
        $.each(tree, function(index, value) {
            if (typeof value.children != "undefined") {
                updateMenuData(value.children);
            }
            value.data = $("#menu_item_" + value.id).data();
            delete value.id;
            delete value.data.nestedSortableItem;
        });
    };

    var prepareMenuForSave = function() {
        $menuContainer.find(".tb_menu_listing ol.tbSortable").each(function() {
            var $section = $(this).parents(".tbLanguagePanel").first();
            var tree = $(this).nestedSortable("toHierarchy", {startDepthCount: 0});

            updateMenuData(tree);
            $section.find("textarea.tbMenuData").first().text(JSON.stringify(tree));
            $section.find("input[name$='[is_dirty]']").val(is_dirty ? 1 : 0);
        });
    };

    $(tbApp).on("tbCp:afterSave", function(event, eventDispatcher) {
        is_dirty = false;
    });

    return {
        prepareForSave: prepareMenuForSave
    }
};