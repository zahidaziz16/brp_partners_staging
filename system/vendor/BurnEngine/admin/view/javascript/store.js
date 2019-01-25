(function($, tbApp) {

tbApp.initRestrictionRows = function ($container, input_property) {
    var restriction_rows_num = $container.find(".tbItemsRestrictionRow").length;

    $container.find(".tbAddItemsRestrictionRow").bind("click", function() {
        var output = Mustache.render($("#store_items_restriction_template").text(), {
            input_property: input_property,
            row_num:        restriction_rows_num++
        });

        $container.find(".tbItemsRestrictionsWrapper").append($(output)).find(".s_spinner").each(function() {
            $(this).spinner({
                mouseWheel: true
            });
        });

        return false;
    });

    $container.on("click", ".tbRemoveItemsRestrictionRow", function() {
        if ($(this).closest(".tbItemsRestrictionRow").siblings().length > 0) {
            $(this).closest(".tbItemsRestrictionRow").remove();
        }
    });
};

tbApp.storeInitProductListing = function($container, input_property) {

    var disableListingLayoutElements = function(list_type, template_name, $panel) {
        var disabled_elements = $panel.find("textarea.product_listing_layout_" + list_type + "_" + template_name).val();

        if (!disabled_elements) {
            return;
        }

        disabled_elements = JSON.parse(disabled_elements);

        if (!disabled_elements) {
            return;
        }

        $.each(disabled_elements, function(name, value) {
            var $el = $panel.find("[name$='[" + list_type + "][" + name + "]']:not([value='0'])");

            switch ($el.attr("type")) {
                case "checkbox":
                case "radio":
                    $el.prop("checked", Number(value) > 0);
                    break;
                default: $el.val(value);
            }

            var closest = $el.closest(".tb_product_elements").is("table") ? "td" : ".s_row_2";

            $el.closest(closest).addClass("tb_disabled tbDisabledLayoutProperty");
        });
    };

    ["grid", "list"].forEach(function (list_type) {

        $container.find("select[name$='[" + list_type + "][listing_layout]']").bind("change", function() {
            var template_name = $(this).val();
            var $panel = $(this).closest(".tbProductsSettings" + tbHelper.ucfirst(list_type));

            $panel.find(".tbDisabledLayoutProperty").removeClass("tb_disabled");

            if (template_name != "plain") {
                disableListingLayoutElements(list_type, template_name, $panel);
            }
        }).trigger("change");
    });

    var widgetIconListReplace = function($newIcon, $activeRow) {
        $activeRow.find(".tbIcon").removeClass("s_icon_holder s_h_26").empty().append($newIcon).end()
            .find('input[name*="button_icon"]:hidden').val($newIcon.attr("glyph_value")).end()
            .find(".tbChooseIcon").removeClass("s_plus_10").addClass("s_delete_10 tbRemoveIcon").text("Remove");
    };

    $container.on("click", ".tbChooseIcon", function() {
        if ($(this).hasClass("tbRemoveIcon")) {
            $(this).removeClass("tbRemoveIcon s_delete_10").addClass("s_plus_10").text("Choose")
                .parents(".tbIconRow").first()
                .find('input[name*="button_icon"]:hidden').val("").end()
                .find(".tbIcon").addClass("s_icon_holder s_h_26").empty();
        } else {
            tbApp.openIconManager(widgetIconListReplace, $(this).parents(".tbIconRow").first());
        }
    });

    $container.find(".tbProductWishlistButtonOptionsStyle, .tbProductCompareButtonOptionsStyle, .tbProductCartButtonOptionsStyle, .tbProductQuickviewButtonOptionsStyle").each(function() {
        var $row    = $(this);
        var $select = $(this).find('select');

        $select.bind("change", function() {
            $row.next().toggleClass('tb_disabled', $select.val() == 'plain' || $select.val() == 'icon_plain');
        }).trigger("change");
    });

    $container.find(".tbProductCartButtonOptionsPosition, .tbProductCompareButtonOptionsPosition, .tbProductWishlistButtonOptionsPosition, .tbProductQuickviewButtonOptionsPosition").each(function() {
        var $row    = $(this);
        var $select = $(this).find('select');

        $select.bind("change", function() {
            $row.closest('.tb_wrap').find("> :last-child").toggleClass('tb_disabled', $select.val() == '1' || $select.val() == '2');
        }).trigger("change");
    });

    tbApp.initRestrictionRows($container, input_property);
};

tbApp.storeInitSubcategories = function($container, input_property) {

    tbApp.initRestrictionRows($container, input_property);
};

})(jQuery, tbApp);