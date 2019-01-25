var tbHelper = {

    browserPrefix: "",

    getBrowserPrefix: function() {

        if (this.browserPrefix != "") {
            return this.browserPrefix;
        }

        var regex = /^(Moz|Webkit|Khtml|O|ms|Icab)(?=[A-Z])/;
        var tester = document.getElementsByTagName('script')[0];
        var prefix = "";

        for (var prop in tester.style) {
            if(regex.test(prop)) {
                prefix = prop.match(regex)[0];
                break;
            }
        }

        if('WebkitOpacity' in tester.style) prefix = 'Webkit';
        this.browserPrefix = prefix === "" ? "" : '-' + prefix.charAt(0).toLowerCase() + prefix.slice(1) +'-';

        return this.browserPrefix;
    },

    /**
     * @param   number {integer}
     * @param   multiple {integer}    multiple to round to
     * @return  rounded number {integer}
     * @example roundToMultiple(35, 15) returns 30
     */
    roundToMultiple: function(number, multiple){
        var value = number/multiple,
            integer = Math.floor(value),
            rest = value - integer;
        return rest > 0.5 ? (integer+1)*multiple : integer*multiple;
    },

    jsonToArray: function(json_values) {
        var arr = [];
        var helper = this;

        $.each(json_values, function(key, value) {
            arr.push(value);
        });

        return arr;
    },

    escapeHTML: function(str) {

        var escapeChars = {
            lt: '<',
            gt: '>',
            quot: '"',
            amp: '&',
            apos: "'"
        };
        var reversedEscapeChars = {};

        for(var key in escapeChars) reversedEscapeChars[escapeChars[key]] = key;
        reversedEscapeChars["'"] = '#39';

        if (str == null) return '';

        return String(str).replace(/[&<>"']/g, function(m){ return '&' + reversedEscapeChars[m] + ';'; });
    },

    unescapeHTML: function(str) {

        var escapeChars = {
            lt: '<',
            gt: '>',
            quot: '"',
            amp: '&',
            apos: "'"
        };

        if (str == null) return '';

        return String(str).replace(/\&([^;]+);/g, function(entity, entityCode){
            var match;
            if (entityCode in escapeChars) {
                return escapeChars[entityCode];
            } else if (match = entityCode.match(/^#x([\da-fA-F]+)$/)) {
                return String.fromCharCode(parseInt(match[1], 16));
            } else if (match = entityCode.match(/^#(\d+)$/)) {
                return String.fromCharCode(~~match[1]);
            } else {
                return entity;
            }
        });
    },

    str_rot13: function (str) {
        return (str + '')
            .replace(/[a-z]/gi, function (s) {
                return String.fromCharCode(s.charCodeAt(0) + (s.toLowerCase() < 'n' ? 13 : -13))
            })
    },

    collectInputParams: function (input_names, $container) {
        if (typeof $container == "undefined") {
            $container = $("#tb_cp_wrap");
        }

        var url = "";

        $.each(input_names, function(i, name) {
            var element = $container.find(':input[name="' + name + '"]');

            if (element.is(":checkbox")) {
                if (element.is(":checked")) {
                    url += "&" + name + "=1";
                }
            } else
            if (element.val()) {
                url += "&" + name + "=" + encodeURIComponent(element.val());
            }

        });

        if (url != "") {
            url = url.substr(1);
        }

        return url;
    },

    getQueryVar: function(query, variable) {

        var urlParams = {};
        var e,
            a = /\+/g, //Regex for replacing addition symbol with a space
            r = /([^&=]+)=?([^&]*)/g,
            d = function (s) { return decodeURIComponent(s.replace(a, " ")); };

        while (e = r.exec(query))
            urlParams[d(e[1])] = d(e[2]);

        if (variable in urlParams) {

            return urlParams[variable];
        }

        return null
    },

    getUrlVar: function(variable) {
        return this.getQueryVar(window.location.search.substring(1), variable);
    },

    generateUniqueId: function(length) {

        if (typeof length == "undefined") {
            length = 5;
        }

        var idstr = String.fromCharCode(Math.floor((Math.random()*25)+65));

        do {
            var ascicode = Math.floor((Math.random()*42)+48);

            if (ascicode < 58 || ascicode > 64) {
                var new_char = String.fromCharCode(ascicode);

                idstr += Math.floor(Math.random() * 2) + 1 == 2 ? new_char : new_char.toLowerCase();
            }
        } while (idstr.length < length);

        return (idstr);
    },

    generateGuid: function() {

        var S4 = function() {
            return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        };

        return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
    },
    basename: function(path, suffix) {
        var b = path.replace(/^.*[\/\\]/g, '');

        if (typeof(suffix) == 'string' && b.substr(b.length - suffix.length) == suffix) {
            b = b.substr(0, b.length - suffix.length);
        }

        return b;
    },
    str_repeat: function(input, multiplier) {
        var y = '';

        while (true) {
            if (multiplier & 1) {
                y += input;
            }
            multiplier >>= 1;
            if (multiplier) {
                input += input;
            }
            else {
                break;
            }
        }
        return y;
    },
    str_begins_with: function(needle, haystack) {
        return (haystack.substr(0, needle.length) == needle);
    },
    is_numeric: function (mixed_var) {
        return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
    },
    ucfirst: function (str) {
        str += '';

        return str.charAt(0).toUpperCase() + str.substr(1);
    },
    lcfirst: function(str) {
        str += '';

        return str.charAt(0).toLowerCase() + str.substr(1);

    },
    camelize: function(str) {
        return str.replace(/(?:^|[- _])(\w)/g, function (_, c) {
            return c ? c.toUpperCase () : '';
        });
    },
    dasherize: function(str) {
        return this.lcfirst(str.trim()).replace(/[_\s]+/g, '-').replace(/([A-Z])/g, '-$1').replace(/-+/g, '-').toLowerCase();
    },
    underscore: function(str) {
        return this.dasherize(str).split('-').join('_');
    },
    createObserver: function() {

        function Observer() {
            this.fns = [];
        }

        Observer.prototype = {
            subscribe : function(fn) {
                this.fns.push(fn);

            },
            unsubscribe : function(fn) {
                this.fns = this.fns.filter(
                    function(el) {
                        if ( el !== fn ) {
                            return el;
                        }
                    }
                );
            },
            fire : function(o, thisObj) {
                var scope = thisObj || window;

                this.fns.forEach(
                    function(el) {
                        el.call(scope, o);
                    }
                );
            }
        };

        return new Observer();
    },

    createEventDispatcher: function() {

        function EventDispatcher() {
            this.events = [];
        }

        EventDispatcher.prototype.addEventListener = function (key, func) {
            if (!this.events.hasOwnProperty(key)) {
                this.events[key] = [];
            }
            this.events[key].push(func);
        };

        EventDispatcher.prototype.removeEventListener = function (key, func) {
            if (this.events.hasOwnProperty(key)) {
                for (var i in this.events[key]) {
                    if (this.events[key][i] === func) {
                        this.events[key].splice(i, 1);
                    }
                }
            }
        };

        EventDispatcher.prototype.dispatchEvent = function (key, dataObj) {
            if (this.events.hasOwnProperty(key)) {
                dataObj = dataObj || {};
                dataObj.currentTarget = this;
                for (var i in this.events[key]) {
                    this.events[key][i](dataObj);
                }
            }
        };

        return new EventDispatcher();
    },

    createCallbackRegister: function(app) {
        return {
            app: app,
            callbacks: [],

            register: function(callback) {
                this.callbacks.push(callback);
            },

            getEvents: function() {
                return this.callbacks;
            },

            collectEvent: function(event_name, callback) {
                var deferred = [];

                app.trigger(event_name, [this]);

                $.each(this.getEvents(), function(key, value) {
                    deferred.push(value.call());
                });

                $.when.apply(null, deferred).done(function() {
                    callback.call(this);
                });

            }
        }
    }
};

function getCategoryFlatTree(callback) {

    var Tree = $.jStorage.get("categoryFlatTree");
    var TreeMethods = {

        getData: function() {
            return this.data;
        },

        getIndexedData: function() {
            return this.indexedData;
        },

        getCategory: function(category_id) {
            if (typeof this.indexedData[category_id] != "undefined") {
                return this.indexedData[category_id];
            } else {
                return null;
            }
        },

        getCategoryParent: function(category_id) {
            var category = this.getCategory(category_id);

            if (null === category) {
                return null;
            }

            if (category.parent_id == 0) {
                return null;
            } else {
                return this.getCategory(category.parent_id);
            }
        },

        getCategoryFullName: function(category_id) {

            var self = this;

            var getNamePathArray = function(category_id) {
                var category = self.getIndexedData()[category_id];

                if (category.parent_id == 0) {
                    return [category.name];
                } else {
                    return [category.name].concat(getNamePathArray(category.parent_id).reverse()).reverse();
                }
            };

            return getNamePathArray(category_id).join(" > ");
        },

        buildHTML: function(parent_id) {

            if (typeof parent_id == "undefined") {
                parent_id = 0;
            }

            var self = this;
            var html = "<ul>\n";

            if (parent_id == 0) {
                $.each(self.data, function(index, category) {
                    if (category.parent_id == 0) {
                        html += '<li' + (category.children_ids.length > 0 ? ' class="tb_multiple"' : "") + '><a href="javascript:;" category_id="' + category.category_id + '">' + category.name + '</a>';
                        if (category.children_ids.length > 0) {
                            html += self.buildHTML(category.category_id);
                        }
                        html += "</li>";
                    }
                });
            } else {
                $.each(this.getIndexedData()[parent_id].children_ids, function(index, child_id) {
                    var child = self.getIndexedData()[child_id];

                    html += '<li' + (child.children_ids.length > 0 ? ' class="tb_multiple"' : '') + '><a href="javascript:;" category_id="' + child.category_id + '">' + child.name + '</a>';
                    if (child.children_ids.length > 0) {
                        html += "\n" + self.buildHTML(child.category_id);
                    }
                    html += "</li>\n";
                });
            }

            html += "</ul>\n";

            return html;
        }
    };

    if (!Tree) {
        $.getJSON($sReg.get("/tb/url/getCategoryFlatTreeJSON"), function(data) {
            var indexedData = {};

            $.each(data, function(index, category) {
                indexedData[category.category_id] = category;
            });

            var Tree = {
                data: data,
                indexedData: indexedData
            };

            $.jStorage.set("categoryFlatTree", Tree);
            callback.call(null, $.extend(Tree, TreeMethods));
        });

        return null;
    } else {
        var result = $.extend(Tree, TreeMethods);

        if (typeof callback != "undefined") {
            callback.call(null, result);
        }

        return result;
    }
}

function getCategoryTreeOptions(callback) {
    getCategoryFlatTree(function(Tree) {
        var html = "";

        $.each(Tree.getData(), function(index, category) {
            html += '<option value="' + category.category_id + '" level="' + category.level + '">' + (category.level > 1 ? tbHelper.str_repeat('-', category.level) + tbHelper.str_repeat('-', parseInt(category.level)-1) : '') + " " + category.name + '</option>';
        });
        callback.call(null, html);
    });
}

function sticky_submit($cont) {
    if ($cont.find("> .tb_subpanel > .s_submit").length) {

        var $panel       = $cont.find("> .tb_subpanel > .s_submit");
        var panel_height = $panel.innerHeight();
        var panel_top    = $panel.offset().top;


        $panel.removeClass('tb_sticky');

        function position() {
            if ($(window).scrollTop() + $(window).height() < panel_top - 30 && $cont.is(':visible')) {
                if (!$panel.hasClass('tb_sticky')) {
                    $panel.addClass('tb_sticky');
                }
            } else {
                $panel.removeClass('tb_sticky');
            }
        }

        position();
        $(window).unbind('scroll.sticky_submit').bind('scroll.sticky_submit', function () {
            position()
        });
    }
}

tbApp.bootAdmin = function() {

    $(window).bind("scroll", function () {
        $("body").toggleClass("tb_scrolled", $(this).scrollTop() > 150);
    });

    var triggerIframeResize;

    $("#tbLivePreviewButton").bind("click", function() {
        $("html").addClass("tb_live_preview");

        iframe = document.createElement("iframe");

        $.getJSON($sReg.get("/tb/url/getLivePreviewToken"), function(data) {
            var url = $.jurlp($sReg.get("/tb/url/storeUrl"));

            url.query({
                forceHTTPS         : location.protocol === 'https:' ? 1 : 0,
                setLivePreviewMode : data.livePreviewToken
            });

            if (location.protocol === 'https:') {
                url.scheme("https://");
            }

            iframe.id = "tb_live_preview_iframe";
            iframe.src = url.url();

            iframe.onload = function() {
                triggerIframeResize = function() {
                    iframe.contentWindow.tbApp.triggerResizeCallbacks();

                    return true;
                };
                $("#tb_live_preview").unblock();
            }
        });

        /*
         iframe.onload = function() {

         $.getJSON(iframe.contentWindow.location.href + "&returnThemeLayoutBuilderData=1", function(data) {
         if ($("#menu_builder").data("loaded")) {
         var $tabs = $("#" + $("#menu_builder").attr("aria-controls")).find('> .tb_tabs');
         var $tab = $tabs.find('> .tb_tabs_nav li[aria-selected="true"]');
         var url = $tab.find("a").data("url");

         $tab.data("loaded", false);
         $tab.data("initialized", false);

         $tab.find("a").attr("href", url).jurlp("query", {
         area_name : $.jurlp(url).query().section,
         area_type : "layout",
         area_id   : "5"
         });

         var comboBox = tbApp.builderComboBoxFactoryInstances[$.jurlp(url).query().section].comboBox;

         comboBox.parent.value("Manufacturer", true, 5);
         comboBox.parent._trigger("select", null, {
         item: {
         element:     null,
         key:         "layout",
         label:       "Manufacturer",
         optionValue: 5,
         value:       "Manufacturer"
         },
         context: comboBox
         });

         $tabs.off("tabsload.livePreview").on("tabsload.livePreview", function() {
         $tabs.off("tabsload.livePreview");
         });
         //$tabs.tabs("load", 1);
         }
         });
         $("#tb_live_preview").unblock();
         };
         */

        $('<div id="tb_live_preview"></div>').appendTo("body").append(iframe).block();
        $('<div id="tb_screen_switch">' +
          '  <div class="s_buttons_group">' +
          '    <a id="tb_button_screen_desktop" class="s_button s_h_40 s_white fa-desktop" href="javascript:;"></a>' +
          '    <a id="tb_button_screen_laptop"  class="s_button s_h_40 s_white fa-laptop" href="javascript:;"></a>' +
          '    <a id="tb_button_screen_tablet"  class="s_button s_h_40 s_white fa-tablet" href="javascript:;"></a>' +
          '    <a id="tb_button_screen_mobile_landscape"  class="s_button s_h_40 s_white fa-mobile" href="javascript:;"></a>' +
          '    <a id="tb_button_screen_mobile"  class="s_button s_h_40 s_white fa-mobile" href="javascript:;"></a>' +
          '  </div>' +
          '</div>').appendTo("body");

        $(tbApp).on("tbCp:beforeSave.livePreview, tbCp:builderBeforeSave.livePreview", function() {
            $("#tb_live_preview").block();
        });

        $(tbApp).on("tbCp:afterSave.livePreview, tbCp:builderAfterSave.livePreview", function(e, response) {
            if (typeof response.livePreviewUrl != "undefined") {
                iframe.src = response.livePreviewUrl;
            }
        });
    });

    $("body").on("click", "li.ui-multiselect-optgroup-label", function() {
        return false;
    })
    .on('click', '#tb_button_screen_desktop', function() {
        if (triggerIframeResize === undefined) return false;

        $('#tb_live_preview').css('width', '');
        triggerIframeResize();
    })
    .on('click', '#tb_button_screen_laptop', function() {
        if (triggerIframeResize === undefined) return false;

        $('#tb_live_preview').css('width', 1024);
        triggerIframeResize();
    })
    .on('click', '#tb_button_screen_tablet', function() {
        if (triggerIframeResize === undefined) return false;

        $('#tb_live_preview').css('width', 768);
        triggerIframeResize();
    })
    .on('click', '#tb_button_screen_mobile_landscape', function() {
        if (triggerIframeResize === undefined) return false;

        $('#tb_live_preview').css('width', 480);
        triggerIframeResize();
    })
    .on('click', '#tb_button_screen_mobile', function() {
        if (triggerIframeResize === undefined) return false;

        $('#tb_live_preview').css('width', 320);
        triggerIframeResize();
    });

    $("#tbLivePreviewCloseButton").bind("click", function() {
        $(tbApp).off(".livePreview");
        $("#tb_live_preview").remove();
        $("#tb_screen_switch").remove();
        $("html").removeClass("tb_live_preview");

        var panel_id = $("#" + $("#menu_builder").attr("aria-controls"))
            .find('> .tb_tabs')
            .find('> .tb_tabs_nav li[aria-selected="true"]').attr("aria-controls");

        $("#" + panel_id).find(".tbBuilderRow").each(function() {
            $(this).find("div.s_builder_cols_wrap").first().proportionPanels($(this));
        });
    });

    var initTabs = function($selector, cookie_name) {

        var initialized = [];
        var active      = tbApp.cookie.get(cookie_name, 0);
        var fragments   = window.location.hash.split(",");


        if(window.location.hash) {
            var ids = [];

            $selector.find("ul").first().find("li").each(function() {
                if ($(this).attr("aria-controls")) {
                    ids.push($(this).attr("aria-controls"));
                } else {
                    ids.push($(this).find("> a").attr("href").substring(1));
                }
            });

            if (cookie_name == "tbMenuLevel1") {
                if (ids.indexOf(fragments[0].substring(1)) !== -1) {
                    active = ids.indexOf(fragments[0].substring(1));
                }
            } else
            if (fragments[1] !== undefined) {
                if (ids.indexOf(fragments[1]) !== -1) {
                    active = ids.indexOf(fragments[1]);
                }
            }
        }

        $selector.tabs({

            create: function(event, ui) {
                //sticky_submit(ui.panel);

                if (-1 == initialized.indexOf(ui.tab.attr("aria-controls"))) {

                    setTimeout(function() {
                        $(tbApp).trigger("tbCp:initTab-" + ui.tab.attr("aria-controls"));
                    }, 50);
                    initialized.push(ui.tab.attr("aria-controls"));
                }
            },

            activate: function (e, ui) {
                tbApp.cookie.set(cookie_name, ui.newTab.index());
                //sticky_submit(ui.newPanel);

                if (-1 == initialized.indexOf(ui.newTab.attr("aria-controls"))) {
                    $(tbApp).trigger("tbCp:initTab-" + ui.newTab.attr("aria-controls"));
                    initialized.push(ui.newTab.attr("aria-controls"));
                }

                if (cookie_name == "tbMenuLevel1") {
                    window.location.hash = ui.newPanel.attr('id');
                } else {
                    if (!fragments[0]) {
                        fragments[0] = 'tb_cp_panel_theme_settings';
                    }

                    window.location.hash = fragments[0] + "," + ui.newPanel.attr('id');
                }
            },

            active: active,

            beforeLoad: function( event, ui ) {

                if (ui.tab.data("loaded")) {
                    event.preventDefault();

                    return;
                }

                $("#loading_screen").fadeOut("normal");
                $("html").removeClass('blocked');
                ui.panel.addClass("tb_loading");
                ui.panel.block();

                ui.jqXHR.success(function() {
                    if ($sReg.get('/tb/Theme-Machine-Name') != ui.jqXHR.getResponseHeader("Theme-Machine-Name")) {
                        $("body").eq(0).empty();
                        if (ui.jqXHR.responseText.match(/<b>Fatal error<\/b>:/gi) || ui.jqXHR.responseText.match(/<b>Parse error<\/b>:/gi) || ui.jqXHR.responseText.match(/Stack trace:/g)) {
                            $("body").html(ui.jqXHR.responseText);
                        } else
                        if (ui.jqXHR.responseText.match(/<b>Warning<\/b>:/gi)) {
                            $("body").html(ui.jqXHR.responseText);
                        } else {
                            if (ui.jqXHR.responseText.match(/<b>Notice<\/b>:/gi)) {
                                console.log(ui.jqXHR.responseText);
                            }
                            //location.reload();
                        }
                    }
                    ui.tab.data("loaded", true);
                    ui.panel.unblock();
                });
            },

            load: function(event, ui) {
                $(ui.panel).removeClass("tb_loading");
            }
        });
    };

    initTabs($("#tb_cp_content_wrap"),         "tbMenuLevel1", true);
    initTabs($("#tb_cp_panel_theme_settings"), "tbSettingsMenu");

    var $tb_cp_wrap = $("#tb_cp_wrap");

    $tb_cp_wrap.find(".tb_multiselect").multiselect({
        header: false,
        noneSelectedText: '<?php echo $text_label_font_options; ?>',
        selectedList: 2
    });

    $(tbApp).on("tbCp:initTab-color_settings_tab", function() {
        if ($sReg.get("/tb/save_colors")) {
            $("#color_settings_tab .tbSaveColors").trigger("click");
            setTimeout(function() {
                $("#store_settings .tb_cp_form_submit").trigger("click");

                setTimeout(function() {
                    $("#loading_screen").fadeOut("normal");
                    $("html").removeClass('blocked');
                }, 300);
            }, 300);
        }
    });

    $("#tb_cp").on("click", "a.tb_cp_form_submit", function() {
        $("#tb_cp_wrap").block({ message: '<h1>Saving settings</h1>' });

        setTimeout(function() {
            tbHelper.createCallbackRegister($(tbApp)).collectEvent('tbCp:beforeSave', function() {
                var $form = $("#tb_cp_form");

                $(tbApp).trigger("tbCp:beforeSerialize", [$form]);

                var formJson = $form.serializeJSON();

                if (formJson.twitter !== undefined) {
                    $.each($sReg.get('/tb/language_codes'), function(key, value) {
                        if (formJson.twitter[value] !== undefined && formJson.twitter[value].code) {
                            formJson.twitter[value].code = tbHelper.str_rot13(btoa(formJson.twitter[value].code));
                        }
                    });
                }

                $.post($form.attr("action"), $.param({form_data: JSON.stringify(formJson)}), function(response) {
                    $(tbApp).trigger("tbCp:afterSave", [response, $form]);

                    $("#tb_cp_wrap").unblock();
                }, "json");
            });
        }, 50);


        return false;
    });

    $("#tb_cp").find("> div.s_server_msg a.s_close").bind("click", function() {
        $(this).parent("div.s_server_msg").hide();
    });

    $("#oc_store").bind("change", function() {
        window.location = $(this).val();
    });

    $("#tb_cp_panel_extensions").on("click", ".tbButtonBackToExtensions", function() {
        var tab = $("#menu_extensions");
        var tab_index = $("#tb_cp_panels_nav > ul > li").index(tab);

        tab.data("loaded", false);
        $("#tb_cp_content_wrap").tabs("load", tab_index);

        return false;
    });

    var theTimeout = setTimeout(function() {
        $("#tb_success_alert").hide("slow");
    }, 5000);

    $("div#yourdiv").mouseover(function() {
        clearTimeout(theTimeout);
    });
}

tbApp.widgetIconListClose = function() {
    $("#tb_resource_manager").dialog("close");
};

tbApp.openIconManager = function(replaceCallback, callbackArguments) {

    tbApp.widgetIconListReplace = function($iconElement) {
        var arguments = [$iconElement];

        if (typeof callbackArguments != "undefined") {
            if (!$.isArray(callbackArguments)) {
                arguments.push(callbackArguments);
            } else {
                arguments.concat(callbackArguments);
            }
        }

        return replaceCallback.apply(null, arguments);
    };

    $('<div id="tb_resource_manager"><iframe src=""></iframe></div>').prependTo($("#content")).dialog({
        title:     "Resource Manager",
        bgiframe:  false,
        width:     700,
        height:    500,
        resizable: false,
        modal:     true,
        open: function(event, ui) {
            $("#tb_resource_manager").find("iframe").attr("src", $sReg.get("/tb/url/icon/getList") + "&replace_callback=widgetIconListReplace&close_callback=widgetIconListClose");
            $(event.target).parents("div.ui-dialog:first").wrap('<div id="tb_resource_manager_wrapper" class="tb_resource_manager_wrapper"></div>');
        },
        close: function(event, ui) {
            $(this).dialog('destroy').remove();
            $("#tb_resource_manager_wrapper").remove();
        }
    });
};

tbApp.createMenuWidget = function($html, context) {

    var menu = $html.addClass("ui-autocomplete").menu({
        input: $(),
        role: null,
        items: '> :not(.tb_label)'
    }).data("uiMenu");

    if (typeof context != "undefined") {
        menu.element.on("menuselect.combobox", $.proxy(context, "_menuSelectCallback"));
    }

    menu.show = function(context, callback) {

        if (typeof context != "undefined") {
            menu.element
                .off("menuselect.combobox")
                .on("menuselect.combobox", $.proxy(context, "_menuSelectCallback"));
        }

        menu.element.show();

        if (typeof callback != "undefined") {
            callback.call(this, menu);
        }

        return menu.element;
    };

    return menu;
};

tbApp.createSharedMenuWidget = function($html) {

    var menu = tbApp.createMenuWidget($html.appendTo("body"));

    return {
        menu: menu,
        element: menu.element,
        show: function(context, $comboBoxWidget) {

            if (typeof $comboBoxWidget == "undefined") {
                $comboBoxWidget = context.widget();
            }

            menu.show(context, function() {
                menu.element.css("min-width", $comboBoxWidget.outerWidth())
                    .position({
                        my: "left top",
                        at: "left bottom+4",
                        of: $comboBoxWidget
                    });

                if (typeof context._menuShowCallback != "undefined") {
                    context._menuShowCallback.call(this, menu);
                }
            })
        },
        hide: function(context) {
            menu.element.hide();

            if (typeof context._menuHideCallback != "undefined") {
                context._menuHideCallback.call(this, menu);
            }
        }
    }
};

tbApp.createCategoryMenuWidget = function(categoryTree) {
    return tbApp.createSharedMenuWidget($(categoryTree.buildHTML()).addClass("tb_megamenu"));
};

tbApp.createSystemMenuWidget = function() {

    if (typeof tbApp.systemMenu == "undefined") {
        if ($("#tbSystemPagesMenu").length) {
            tbApp.systemMenu = tbApp.createSharedMenuWidget($("#tbSystemPagesMenu > ul").addClass("tb_megamenu"));
            tbApp.systemMenu.element.hide();
        } else {
            tbApp.systemMenu = null;
        }
    }

    return tbApp.systemMenu;
};

tbApp.createCategoryComboBox = function($element, options) {

    var comboBox = $element.combobox({

        select: function(event, ui) {
            if (typeof options["onSelect"] != "undefined" && $.isFunction(options["onSelect"])) {
                options["onSelect"].call(this, event, ui);
            }
        },

        blur: function(event, ui) {
            if (typeof tbApp.categoryMenu != "undefined" && tbApp.categoryMenu.element.is(":visible")) {
                tbApp.categoryMenu.hide(ui);
            }
        },

        buttonmousedown: function(event, ui) {
            ui._wasOpen = typeof tbApp.categoryMenu != "undefined" && tbApp.categoryMenu.element.is(":visible");
        },

        open: function(event, ui) {

            ui._menuSelectCallback = function(event, ui) {

                var $a = ui.item.find("> a").first();
                var identifier = function($element) {

                    var identifier = $.map($element.attr(), function(attr_value, attr_name) {

                        if (attr_name.substr(attr_name.lastIndexOf("_")+1) == "id") {
                            return attr_name;
                        }

                        return null;
                    })[0];

                    return {
                        key:   identifier.substr(0, identifier.length-3),
                        value: $element.attr(identifier)
                    }
                }($a);

                this._events["autocompleteselect input"].call(this, event, {
                    item: {
                        label:       $a.text(),
                        value:       $a.text(),
                        key:         identifier.key,
                        optionValue: identifier.value,
                        element:     $a
                    }
                });

                tbApp.categoryMenu.hide(this);
            };

            ui._menuShowCallback = function(menu) {

                var prependHtml = '<li><a href="javascript:;" category_id="0">All</a></li>';

                if (typeof options["prependHtml"] != "undefined") {
                    prependHtml += options["prependHtml"];
                }

                menu.element.prepend($(prependHtml).addClass("tbPrependMenu"));
                menu.refresh();
            };

            ui._menuHideCallback = function(menu) {
                menu.element.find("> li.tbPrependMenu").remove();
            };

            if (typeof tbApp.categoryMenu == "undefined") {
                $('<span class="tb_loading_inline"></span>').insertAfter(ui.widget());

                getCategoryFlatTree(function(categoryTree) {
                    tbApp.categoryMenu = tbApp.createCategoryMenuWidget(categoryTree);
                    ui.widget().next(".tb_loading_inline").remove();
                    tbApp.categoryMenu.show(ui, ui.widget());
                });
            } else {
                tbApp.categoryMenu.show(ui, ui.widget());
            }
        },

        search_data: function (term, matcher, callback) {

            var result = {};
            var self = this;

            if (term.trim().length > 1) {

                if (typeof tbApp.categoryMenu == "undefined") {
                    $('<span class="tb_loading_inline"></span>').insertAfter(self.widget());
                } else {
                    tbApp.categoryMenu.hide(self);
                }

                getCategoryFlatTree(function(categoryTree) {

                    result = $.map(categoryTree.getData(), function(category) {
                        if (matcher.test(category.name)) {
                            var category_name = categoryTree.getCategoryFullName(category.category_id).replace("&amp;", "&");

                            return {
                                label:       category_name,
                                value:       category.name.replace("&amp;", "&"),
                                key:         "category",
                                optionValue: category.category_id,
                                remove:      false,
                                element:     null
                            }
                        }

                        return null;

                    });

                    if (result.length) {
                        result = [{
                            label:       "Categories",
                            value:       "Categories",
                            optionValue: null,
                            element:     $('<li class="tb_label"></li>')
                        }].concat(result);
                    }

                    self.widget().next(".tb_loading_inline").remove();

                    callback.call(null, result);
                });
            } else {
                callback.call(null, result);
            }
        }
    });

    if (typeof options["customValue"] != "undefined") {
        comboBox.combobox("customValue", options["customValue"]);
    }

    return comboBox.data("uiCombobox");
};

tbApp.cookie = {

    container: {},

    _init: function() {
        if (typeof $.cookie("tbApp") != "undefined") {
            this.container = JSON.parse(atob($.cookie("tbApp")));
        }

        return this;
    },

    set: function(name, value) {
        this.container[name] = value;
        $.cookie("tbApp", btoa(JSON.stringify(this.container)), { expires: 7, path: $sReg.get('/tb/url/adminBase') });
    },

    get: function(name, default_value) {
        if (typeof this.container[name] != "undefined") {
            return this.container[name];
        }

        return default_value;
    },

    getObjProp: function(name, prop, default_value) {
        if (typeof this.container[name] != "undefined" && typeof this.container[name][prop] != "undefined") {
            return this.container[name][prop];
        }

        return default_value;
    }
}._init();

(function($) {

$.widget( "ui.tbComboBox", {

    widgetEventPrefix: "tbcombobox",
    categoryTree: null,
    parent: {},
    currentItem: {},

    options: {
        $contents: $()
    },

    _create: function() {

        this.$contents = this.options.$contents;
        this.parent = this.element.combobox({
            search_data: $.proxy(this, "searchData")
        }).data("uiCombobox");
        this._on( this._events );
    },

    _events: {

        "comboboxopen": function() {

            if (!this.$contents.find(".tbModifiedMenu").children().length) {
                this.parent.uiCombo.find("> ul.ui-autocomplete").find("li.tbModified").each(function() {
                    $(this).hide();
                    $(this).prev().hide();
                });
            }
        },

        "comboboxblur": function() {
            this._hideMenus();
        },

        "comboboxselect": function(event, ui) {

            var menu_changed = false;

            switch(ui.item.key) {
                case "choose_system":
                    this.showSystemMenu();
                    menu_changed = true;
                    break;
                case "choose_category":
                    this.showCategoryMenu();
                    menu_changed = true;
                    break;
                case "choose_page":
                    this.showPageMenu();
                    menu_changed = true;
                    break;
                case "choose_layout":
                    this.showLayoutMenu();
                    menu_changed = true;
                    break;
                case "modified":
                    this.showModifiedMenu();
                    menu_changed = true;
                    break;
            }

            if (menu_changed) {
                this._trigger( "changeMenu", event, {
                    item: ui.item,
                    context: this
                });

                return;
            }

            this.currentItem = ui.item;

            this._trigger( "select", event, {
                item: ui.item,
                context: this
            });
        },

        "comboboxremove": function(event, ui) {

            this.element.children('option[value="' + ui.optionValue + '"]').removeClass("ui-combobox-remove");

            this._trigger( "remove", event, {
                item: ui,
                context: this
            });
        }
    },

    _hideMenus: function() {
        if (typeof tbApp.categoryMenu != "undefined" && tbApp.categoryMenu.element.is(":visible")) {
            tbApp.categoryMenu.element.hide();
        }
        if (typeof this.modifiedMenu != "undefined" && this.modifiedMenu.element.is(":visible")) {
            this.modifiedMenu.element.hide();
        }
        if (typeof this.pageMenu != "undefined" && this.pageMenu.element.is(":visible")) {
            this.pageMenu.element.hide();
        }
        if (typeof this.layoutMenu != "undefined" && this.layoutMenu.element.is(":visible")) {
            this.layoutMenu.element.hide();
        }
        if (typeof tbApp.systemMenu != "undefined" && tbApp.systemMenu.element.is(":visible")) {
            tbApp.systemMenu.element.hide();
        }
    },

    _assignCategoryTree: function (callback) {
        var self = this;

        if (null === self.categoryTree) {
            getCategoryFlatTree(function(Tree) {
                self.categoryTree = Tree;
                callback.call(this, self.categoryTree);
            });
        } else {
            callback.call(this, self.categoryTree);
        }
    },

    _menuSelectCallback: function(event, ui) {

        var $a = ui.item.find("> a").first();

        if ($a.hasClass("ui-menu-noselect")) {
            return false;
        }

        var identifier = this._parseIdentifier($a);

        this.parent._events["autocompleteselect input"].call(this.parent, event, {
            item: {
                label:       $a.text(),
                value:       $a.text(),
                key:         identifier.key,
                optionValue: identifier.value,
                element:     $a
            }
        });

        $(event.delegateTarget).data("uiMenu").element.hide();
    },

    _buildMenu: function($html) {
        return tbApp.createMenuWidget($html.insertBefore($(this.widget()).find(".ui-menu").last()), this);
    },

    _buildPageMenu: function() {
        if (typeof this.pageMenu == "undefined") {
            if (this.$contents.find(".tbPageMenu").length) {
                this.pageMenu = this._buildMenu(this.$contents.find(".tbPageMenu"));
            } else {
                this.pageMenu = null;
            }
        }

        return this.pageMenu;
    },

    _buildLayoutMenu: function() {
        if (typeof this.layoutMenu == "undefined") {
            if (this.$contents.find(".tbLayoutMenu").length) {
                this.layoutMenu = this._buildMenu(this.$contents.find(".tbLayoutMenu"));
            } else {
                this.layoutMenu = null;
            }
        }

        return this.layoutMenu;
    },

    _parseIdentifier: function($element) {
        var identifier = $.map($element.attr(), function(attr_value, attr_name) {
            if (attr_name.substr(attr_name.lastIndexOf("_")+1) == "id") {
                return attr_name;
            }

            return null;
        })[0];

        return {
            key:   identifier.substr(0, identifier.length-3),
            value: $element.attr(identifier)
        }
    },

    _searchMenu: function(matcher, $menu_element, type) {
        return $menu_element.find("li.ui-menu-item:not(.tb_multiple) > a").map(function() {
            return matcher.test($(this).text()) ? {
                label:       $(this).text(),
                value:       $(this).text().replace("&amp;", "&"),
                key:         type,
                optionValue: $(this).attr(type + "_id"),
                remove:      false,
                element:     null
            } : null;
        }).get();
    },

    searchData: function(term, matcher, callback) {

        var result = {};
        var self = this;

        if (term.trim().length > 1) {

            self._hideMenus();

            if (null === self.categoryTree) {
                $('<span class="tb_loading_inline"></span>').insertAfter(self.widget());
            }

            this._assignCategoryTree(function(categoryTree) {

                result = $.map(categoryTree.getData(), function(category) {
                    if (matcher.test(category.name)) {
                        var category_name = categoryTree.getCategoryFullName(category.category_id).replace("&amp;", "&");

                        return {
                            label:       category_name,
                            value:       category.name.replace("&amp;", "&"),
                            key:         "category",
                            optionValue: category.category_id,
                            remove:      false,
                            element:     null
                        }
                    }

                    return null;

                });

                if (result.length) {
                    result = [{
                        label:       "Categories",
                        value:       "Categories",
                        optionValue: null,
                        element:     $('<li class="tb_label"></li>')
                    }].concat(result);
                }

                if (null !== self._buildPageMenu()) {

                    var matched_pages = self._searchMenu(matcher, self._buildPageMenu().element, 'page');

                    if (matched_pages.length) {
                        result = result.concat([{
                            label:       "Information Pages",
                            value:       "Information Pages",
                            optionValue: null,
                            element:     $('<li class="tb_label"></li>')
                        }]).concat(matched_pages);
                    }
                }

                if (null !== self._buildLayoutMenu()) {

                    var matched_layouts = self._searchMenu(matcher, self._buildLayoutMenu().element, 'layout');

                    if (matched_layouts.length) {
                        result = result.concat([{
                            label:       "Layouts",
                            value:       "Layouts",
                            optionValue: null,
                            element:     $('<li class="tb_label"></li>')
                        }]).concat(matched_layouts);
                    }
                }

                if (null !== tbApp.createSystemMenuWidget()) {

                    var matched_system = self._searchMenu(matcher, tbApp.createSystemMenuWidget().element, 'system');

                    if (matched_system.length) {
                        result = result.concat([{
                            label:       "System Pages",
                            value:       "System Pages",
                            optionValue: null,
                            element:     $('<li class="tb_label"></li>')
                        }]).concat(matched_system);
                    }
                }

                $(self.widget()).next(".tb_loading_inline").remove();

                callback.call(null, result);
            });
        } else {
            callback.call(null, result);
        }
    },

    showSystemMenu: function() {
        tbApp.createSystemMenuWidget().show(this);
    },

    showCategoryMenu: function() {

        var self = this;

        if (typeof tbApp.categoryMenu == "undefined") {
            if (null === self.categoryTree) {
                $('<span class="tb_loading_inline"></span>').insertAfter(self.widget());
            }

            self._assignCategoryTree(function(categoryTree) {
                tbApp.categoryMenu = tbApp.createCategoryMenuWidget(categoryTree);
                $(self.widget()).next(".tb_loading_inline").remove();
                tbApp.categoryMenu.show(self, self.widget());
            });
        } else {
            tbApp.categoryMenu.show(self, self.widget());
        }
    },

    showPageMenu: function() {
        this._buildPageMenu().element.show();
    },

    showLayoutMenu: function() {
        this._buildLayoutMenu().element.show();
    },

    showModifiedMenu: function() {

        var self = this;

        if (typeof self.modifiedMenu == "undefined") {
            self.modifiedMenu = self._buildMenu(self.$contents.find(".tbModifiedMenu"));
            self.modifiedMenu.element.show();

            self._on(self.modifiedMenu.element, {
                mousedown: function( event ) {
                    event.preventDefault();
                }
            });

            self.modifiedMenu.element.find("span").bind("click", function(event) {
                event.stopPropagation();

                var $a = $(this).prev("a");
                var identifier = self._parseIdentifier($a);

                self.parent._trigger( "remove", event, {
                    label:       $a.text(),
                    key:         identifier.key,
                    optionValue: identifier.value,
                    element:     $a[0]
                });

                self.blur();
            });
        } else {
            self.modifiedMenu.element.show();
        }
    },

    label: function() {
        return this.parent.label();
    },


    blur: function() {
        this.parent.uiInput.trigger("blur");
    },

    widget: function () {
        return this.parent.widget();
    },

    value: function(newVal) {
        if ( !arguments.length ) {
            return this.parent.value();
        }

        this.parent.value(newVal);
    },

    customValue: function ( newVal, optionValue ) {
        this.parent.value( newVal, true, optionValue );
    },

    getContentsElement: function() {
        return this.$contents;
    }

});

})(jQuery);

// Sticky Sidebar
(function($){

    var settings = {
            padding: 10
        },
        $window = $(window),
        stickyboxes = [],
        methods = {

            init:function(opts) {
                settings = $.extend(settings, opts);

                return this.each(function () {
                    var $this = $(this);

                    setPosition($this);
                    $this.data("stickySB").is_scrolled = false;
                    stickyboxes[stickyboxes.length] = $this;
                });
            },

            remove:function() {
                return this.each(function () {
                    var sticky = this;

                    $.each(stickyboxes, function (i, $sb) {
                        if($sb.get(0) === sticky) {
                            reset(null, $sb);
                            stickyboxes.splice(i, 1);

                            return false;
                        }
                    });
                });
            },

            destroy: function () {
                $.each(stickyboxes, function (i, $sb) {
                    reset(null, $sb);
                });
                stickyboxes=[];
                $window.unbind("scroll", moveIntoView);
                $window.unbind("resize", reset);

                return this;
            },

            reset: function() {
                $.each(stickyboxes, function (i, $sb) {
                    reset(null, $sb);
                });

                return this;
            }

        };

    var moveIntoView = function () {
        $.each(stickyboxes, function (i, $sb) {
            var data = $sb.data("stickySB");

            if (data && $sb.is(":visible")) {

                if (typeof(data.is_scrolled) === 'undefined' || !data.is_scrolled) {
                    setPosition($sb);
                    $sb.data("stickySB").is_scrolled = true;
                    data = $sb.data("stickySB");
                }

                var sidebarTop    = data.orig.offset.top;
                var windowTop     = $(window).scrollTop();
                var sidebarHeight = $sb.outerHeight();
                var parentTop     = $sb.parent().offset().top;
                var parentHeight  = $sb.parent().height();
                var parentScroll  = parentTop + parentHeight - windowTop;
                var sidebarOffset = 0;

                if (sidebarHeight + settings.padding < parentHeight && windowTop > (sidebarTop - settings.padding)) {
                    if(parentScroll - sidebarHeight - settings.padding < 0) {
                        sidebarOffset = parentScroll - sidebarHeight - settings.padding;
                    }
                    $sb
                        .css("width", data.orig.width)
                        .css("position", "fixed")
                        .css("top", 0 + settings.padding + sidebarOffset)
                        .css("left", data.orig.offset.left)
                        .css("margin-left", 0)
                        .css("-webkit-box-sizing", "content-box")
                        .css("-moz-box-sizing", "content-box")
                        .css("box-sizing", "content-box");
                } else {
                    $sb
                        .css("position", data.orig.position)
                        .css("top", data.orig.top)
                        .css("left", data.orig.left)
                        .css("width", "")
                        .css("margin-left", data.orig.marginLeft)
                        .css("-webkit-box-sizing", "")
                        .css("-moz-box-sizing", "")
                        .css("box-sizing", "");
                }
            }
        });
    };

    var setPosition = function ($sb) {
        if ($sb) {
            var data = {
                orig: { // cache for original css
                    top:        $sb.css("top"),
                    left:       $sb.css("left"),
                    position:   $sb.css("position"),
                    width:      $sb.width(),
                    outerWidth: $sb.outerWidth(),
                    marginTop:  $sb.css("marginTop"),
                    marginLeft: $sb.css("marginLeft"),
                    offset:     $sb.offset()
                }
            };
            $sb.data("stickySB", data);
        }
    };

    var reset = function (ev, $toReset) {

        var stickies = stickyboxes;

        if ($toReset) { // just resetting selected items
            stickies = [$toReset];
        }

        $.each(stickies, function(i, $sb) {
            var data = $sb.data("stickySB");
            if (data) {
                $sb.css({
                    position:   data.orig.position,
                    marginTop:  data.orig.marginTop,
                    marginLeft: data.orig.marginLeft,
                    left:       data.orig.left,
                    top:        data.orig.top
                });
                if (!$toReset) { // just resetting
                    setPosition($sb);
                    moveIntoView();
                }
            }
        });
    }

    $window.bind("scroll", moveIntoView);
    $window.bind("resize", reset);

    $.fn.stickySidebar = function(method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (!method || typeof method == "object") {
            return methods.init.apply(this, arguments);
        }
    }

})(jQuery);

function createKnob($el, $container, onTurn) {

    var knob = {

        init: function($el, $container, onTurn) {

            if (null == $container) {
                $container = $el.parent("div");
            }

            this.$el        = $el;
            this.$container = $container;
            this.onTurn     = onTurn;
            this.$dial      = $el.find(".tb_knob_dial");
            this.$input     = this.$el.find('input').first();
            this.height     = this.$dial.height();
            this.width      = this.$dial.width();
            this.$pointer   = this.$dial.find('.tb_knob_pointer');

            this.$dial.bind("mousedown", $.proxy(this, "start"));
            this.$input.bind("change", $.proxy(this, "get"));
            this.$container.bind('mouseleave mouseup', $.proxy(this, "stop"));
        },

        start: function(event) {

            var myOffset = this.$dial.offset();

            this.offset = {
                x: myOffset.left + (this.width/2),
                y: myOffset.top + (this.height/2)
            };

            this.turn(event);
            this.$container.bind('mousemove', $.proxy(this, "turn"));
        },

        get: function() {
            this.update(parseInt(this.$input.val()));
        },

        set: function(value) {
            this.$input.val(value);
            this.angle = value;
        },

        update: function(value) {
            if (typeof value == "undefined") {
                this.get();
                this.onTurn.call(this);
                return;
            }

            if (value > 180 || value < -179) {
                value = 180;
            }

            this.move(value);
            this.set(value);
        },

        move: function(degrees) {
            this.$pointer.css(tbHelper.getBrowserPrefix() + 'transform', 'rotate('+-degrees+'deg)');
        },

        turn: function(event) {
            event.preventDefault();

            var opposite = this.offset.y - event.pageY,
                adjacent = event.pageX - this.offset.x,
                radiants = Math.atan(opposite/adjacent),
                degrees  = Math.round(radiants*(180/Math.PI), 10);

            if (event.shiftKey) {
                degrees = tbHelper.roundToMultiple(degrees, 15);
            } else {
                degrees = tbHelper.roundToMultiple(degrees, 5);
            }

            if (adjacent < 0 && opposite >= 0) {
                degrees += 180;
            } else if (opposite < 0 && adjacent < 0) {
                degrees -= 180;
            }
            if (degrees === -180) {
                degrees = 180;
            }

            this.onTurn.call(this);
            this.update(degrees);
        },

        stop: function(event) {
            this.$container.unbind("mousemove");
        }
    }

    onTurn = $.isFunction(onTurn) ? onTurn : $.noop();
    knob.init($el, $container, onTurn);

    return knob;
}

function assignColorPicker($element, show_transparent, lazy) {

    if (typeof show_transparent == "undefined" || typeof show_transparent != "boolean") {
        show_transparent = false;
    }

    if (typeof lazy == "undefined" || typeof lazy != "boolean") {
        lazy = true;
    }

    var fix_dies = function(hex) {
        hex = hex.trim();

        if (hex.charAt(0) != "#") {
            hex = "#" + hex;
        }

        return hex;
    };

    var fix_hex_color = function(hex) {

        if (hex.charAt(0) == "#") {
            hex = hex.substring(1);
        }

        if (!/^#(?:[0-9a-fA-F]{3}){1,2}$/i.test(hex)) {
            var tmp_hex = "";
            var hex_length = hex.length > 3 ? 6 : 3;

            for ( var i = 0; i < hex_length; i++ ) {
                var cur_char = hex.charAt(i);
                if (!/^\d$/i.test(cur_char) && !/[a-fA-F]$/i.test(cur_char)) {
                    cur_char = tmp_hex.length > 0 ? tmp_hex.charAt(tmp_hex.length-1) : "f";
                }
                tmp_hex += cur_char;
            }
            hex = tmp_hex;
        }

        hex = hex.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i, function(m, r, g, b) {
            return r + r + g + g + b + b;
        });

        return "#" + hex;
    };

    var input_fix_dies = function($input) {
        var value = $input.val().trim();

        if (value != "" || !show_transparent) {
            value = fix_dies(value);
        }
        $input.val(value);

        return value;
    };

    var input_validate_hex_color = function($input) {
        var value = $input.val().trim();

        if (value != "" || !show_transparent) {
            value = fix_hex_color(value);
        }
        $input.val(value);

        return value;
    };

    var changeColorTimeout = null;

    var change_preview_color = function(hex) {
        hex = fix_dies(hex);
        if (/^#(?:[0-9a-fA-F]{3}){1,2}$/i.test(hex)) {
            $element.find("div").css("backgroundColor", hex);
            clearTimeout(changeColorTimeout);
            changeColorTimeout = setTimeout(function() {
                $element.next("input").trigger("changeColor");
            }, 200);
        }
    };

    $element.next("input").bind("updateColor", function() {
        $element.find("div").attr("style", "background-color: " + $(this).val());
        if (show_transparent) {
            if ($element.next("input").val() == "") {
                $element.addClass("colorpicker_no_color");
            } else {
                $element.removeClass("colorpicker_no_color");
            }
        }
    });

    $element.next("input").bind("keydown", function(e) {
        var $input = $(this);

        if ((e.shiftKey && /[0-9]$/i.test(String.fromCharCode(e.keyCode))) || !(e.ctrlKey && (e.keyCode == 86 || e.keyCode == 67)) && $.inArray(e.keyCode, [8,17,46]) < 0  && !/[0-9a-fA-F]$/i.test(String.fromCharCode(e.keyCode))) {
            e.preventDefault();
        }

        setTimeout(function() {
            $input.val($input.val().trim());

            if (show_transparent) {
                if ($input.val().length == 0) {
                    $element.addClass("colorpicker_no_color");
                    $element.next("input").trigger("changeColor");

                    return;
                } else {
                    $element.removeClass("colorpicker_no_color");
                }
            }
            input_fix_dies($input);
            change_preview_color($input.val());
        }, 300);
    });

    $element.next("input").bind("paste", function() {
        var $input = $(this);

        setTimeout(function() {
            input_validate_hex_color($input);
            change_preview_color($input.val());
        }, 300);
    });

    $element.next("input").bind("blur", function() {
        input_validate_hex_color($(this));
        change_preview_color($(this).val());
    });

    var bindColorpicker = function() {
        $element.ColorPicker({
            color: "#0000ff",
            onInit: function (colpkr) {
                if (show_transparent) {
                    $(colpkr).find(".colorpicker_transparent").bind("click", function() {
                        $element.next("input").val("");
                        $element.addClass("colorpicker_no_color");
                        $element.ColorPickerHide();
                        $element.next("input").trigger("changeColor");
                    });
                } else {
                    $(colpkr).find(".colorpicker_transparent").remove();
                }
            },
            onShow: function (colpkr) {
                $(colpkr).fadeIn(500);
                $(this).ColorPickerSetColor($element.next("input").val());

                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).fadeOut(400);
                if ($element.next("input").val().trim() != "" || !show_transparent)  {
                    $element.next("input").val("#" + $(colpkr).data('colorpicker').fields.eq(0).val());
                } else {
                    $element.next("input").val("");
                }

                $element.next("input").trigger("hideColorPicker");

                return false;
            },
            onChange: function (hsb, hex, rgb) {
                if (show_transparent) {
                    $element.removeClass("colorpicker_no_color");
                }
                $element.next("input").attr("value", "#" + hex);
                change_preview_color(hex);
            },
            onBeforeShow: function (colpkr) {
                $(this).ColorPickerSetColor(input_fix_dies($element.next("input")));
            }
        })
    };

    if (lazy) {
        $element.one("click", function() {
            bindColorpicker();
            $element.ColorPickerShow();
        });
    } else {
        bindColorpicker();
    }

    if (show_transparent && $element.next("input").val() == "") {
        $element.addClass("colorpicker_no_color");
    }
}

function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

    return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
}

function beautifyForm($selector) {
    $selector.find(".s_spinner").not(".tb_nostyle").each(function() {
        $(this).spinner();
    });
}

(function ($) {

/**
 * jQuery alterClass plugin
 *
 * Remove element classes with wildcard matching. Optionally add classes:
 * $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
 *
 * Copyright (c) 2011 Pete Boere (the-echoplex.net)
 * Free under terms of the MIT license: http://www.opensource.org/licenses/mit-license.php
 *
 */
$.fn.alterClass = function (removals, additions) {

    var self = this;

    if ( removals.indexOf( '*' ) === -1 ) {
        // Use native jQuery methods if there is no wildcard matching
        self.removeClass( removals );
        return !additions ? self : self.addClass( additions );
    }

    var patt = new RegExp( '\\s' +
        removals.
            replace( /\*/g, '[A-Za-z0-9-_]+' ).
            split( ' ' ).
            join( '\\s|\\s' ) +
        '\\s', 'g' );

    self.each( function ( i, it ) {
        var cn = ' ' + it.className + ' ';
        while ( patt.test( cn ) ) {
            cn = cn.replace( patt, ' ' );
        }
        it.className = $.trim( cn );
    });

    return !additions ? self : self.addClass( additions );
};

$.fn.extend({

    triggerAll: function (events, params) {
        var el = this, i, evts = events.split(' ');
        for (var i = 0; i < evts.length; i += 1) {
            el.trigger(evts[i], params);
        }
        return el;
    },

    tap: function (callback) {
        callback.apply(this);

        return this;
    },

    hasAttr: function(name) {
        return this.attr(name) !== undefined && this.attr(name) !== false;
    },

    outerHTML: function() {
        // IE, Chrome & Safari will comply with the non-standard outerHTML, all others (FF) will have a fall-back for cloning
        return (!this.length) ? this : (this[0].outerHTML || (
            function(el){
                var div = document.createElement('div');
                div.appendChild(el.cloneNode(true));

                var contents = div.innerHTML;
                div = null;

                return contents;
            }
        )(this[0]));
    }
});

(function(old) {
    $.fn.attr = function() {
        if(arguments.length === 0) {
            if(this.length === 0) {
                return null;
            }

            var obj = {};
            $.each(this[0].attributes, function() {
                if(this.specified) {
                    obj[this.name] = this.value;
                }
            });
            return obj;
        }

        return old.apply(this, arguments);
    };
})($.fn.attr);

})(jQuery);

(function(jQuery){

    var toString = Object.prototype.toString,
        hasOwnProp = Object.prototype.hasOwnProperty;

    jQuery.isObject = function( obj ) {
        if ( toString.call(obj) !== "[object Object]" )
            return false;

        //own properties are iterated firstly,
        //so to speed up, we can test last one if it is not own

        var key;
        for ( key in obj ) {}

        return !key || hasOwnProp.call( obj, key );
    }

})(jQuery);

/*
 * sModal - a jQuery plugin for a very simple modal box
 * @version 0.5.0
 * @requires jQuery v1.5.0 or later
 *
 * Copyright (c) 2013 Stoyan Kyosev (http://themeburn.com)
 */

(function($) {

    $.fn.sModal = function(options) {

        if (!this.length) {
            return this;
        }

        options = $.extend({
            width         : 450,
            height        : 345,
            fixed         : true,
            linktag       : "href",
            contents      : "",
            requestMethod : "GET",
            requestData   : {},
            dataType      : null,
            onClick       : function(){},
            onShow        : function(){},
            onClose       : function(){},
            onSetContents : function(){}
        }, options || {});

        this.each(function() {
            $(this).bind("click", function() {

                options.onClick.call(this);

                var context   = this;
                var $smWrap   = createWindow(options.width, options.height, options.fixed);
                var $smWindow = $smWrap.next('.sm_window');

                $('body').addClass('sModalInit');

                this.setLoading = function() {
                    $smWrap.find("> div.sm_content").eq(0).addClass("sm_ajaxLoading");
                };

                this.removeLoading = function() {
                    $smWrap.find("> div.sm_content").eq(0).removeClass("sm_ajaxLoading");
                };

                this.close = function(fadeout) {
                    if (fadeout === undefined) {
                        fadeout = "fast"
                    }

                    $.Deferred().done(function() {
                        options.onClose.call(this, context);
                    }).done(function() {
                        if (fadeout) {
                            $smWrap.fadeOut("fast", function() {
                                $smWrap.remove();
                            });
                        } else {
                            $smWrap.remove();
                        }

                    }).resolve();

                    $('body').removeClass('sModalInit');

                    $(window).unbind('scroll.smModal');
                };

                this.setContents = function(url, callback) {

                    var requestData;

                    if ($.isFunction(options.requestData)) {
                        requestData = options.requestData.call(this);
                    } else {
                        requestData = options.requestData;
                    }

                    context.setLoading();
                    $.ajax({
                        url      :  url,
                        type     : options.requestMethod,
                        data     : requestData,
                        dataType : options.dataType,
                        success  : function(data) {
                            var transformed_data = options.onSetContents.call(this, data);

                            if (transformed_data) {
                                data = transformed_data;
                            }

                            $smWrap.find("> .sm_window_wrap > div.sm_content").eq(0).empty().append(data);
                            context.removeLoading();
                            options.onShow.call(this, context);
                            if ($.isFunction(callback)) {
                                callback.call(this);
                            }
                        }
                    });
                };

                this.setContentsFromTemplate = function(contents) {
                    $smWrap.find("> .sm_window_wrap > div.sm_content").eq(0).empty().append(contents);
                    options.onShow.call(this, context);
                }

                this.find = function(selector) {
                    return $smWrap.find(selector);
                };

                this.getContents = function() {
                    return $smWrap;
                }

                $smWrap.find(".sm_closeWindowButton").add($smWrap.prev(".sm_overlayBG")).bind("click", function() {
                    context.close();

                    return false;
                });

                if (options.contents == "") {
                    var url = options.linktag;

                    if ($.isFunction(url)) {
                        url = options.linktag.call(this);
                    } else {
                        url = $(this).attr(options.linktag);
                    }

                    this.setContents(url);
                } else {
                    var contents = options.contents;

                    if ($.isFunction(contents)) {
                        contents = contents.call(this);
                    }

                    this.setContentsFromTemplate(contents);
                }

                // Position Window
                if (!options.fixed) {
                    $smWindow.css('top', $(window).scrollTop() + 30);

                    var w_scroll_init = $(window).scrollTop();

                    $(window).bind('scroll.smModal', function() {

                        var c_height  = $smWindow.height();
                        var c_scroll  = $smWindow.offset().top;
                        var w_height  = $(window).height();
                        var w_scroll  = $(window).scrollTop();

                        if (c_height + 60 <= w_height) {
                            $smWindow.css('top', w_scroll + 30);
                        } else
                        if (c_height > w_height) {
                            //Scrolling up
                            if (w_scroll_init > w_scroll) {
                                if (w_scroll < c_scroll + 30) {
                                    $smWindow.css('top', w_scroll + 30);
                                }
                            }
                            //Scrolling down
                            if (w_scroll_init < w_scroll) {
                                if (w_scroll + w_height >= c_scroll + c_height) {
                                    $smWindow.css('top', w_scroll + w_height - c_height - 30);
                                }
                            }
                        }

                        w_scroll_init = w_scroll;
                    });
                }

                return false;
            });
        });

        function createWindow(width, height, fixed) {

            var html = '';
            var margin_left = (width)/2;

            html += '<div class="sm_overlayBG"></div>';
            if(fixed) {
                html += '<div id="sm_window" class="sm_window sm_fixed" style="margin-left: -' + margin_left + 'px; width: ' + width + 'px; height: ' + height + 'px; margin-top: -' + (height)/2 + 'px; display: block;">';
            } else {
                html += '<div class="sm_window" style="margin-left: -' + margin_left + 'px; width: ' + width + 'px; display: block;">';
            }
            html += '  <a class="sm_closeWindowButton" href="#">close</a>';
            html += '  <div class="sm_window_wrap"><div class="sm_content"></div></div>';
            html += '</div>';

            return $(html).appendTo($("body"));
        }

        return this;
    };

})(jQuery);

(function () {

    var
        object = typeof exports != 'undefined' ? exports : window,
        chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',
        INVALID_CHARACTER_ERR = (function () {
            // fabricate a suitable error object
            try { document.createElement('$'); }
            catch (error) { return error; }}());

    // encoder
    // [https://gist.github.com/999166] by [https://github.com/nignag]
    object.btoa || (
        object.btoa = function (input) {
            for (
                // initialize result and counter
                var block, charCode, idx = 0, map = chars, output = '';
                // if the next input index does not exist:
                //   change the mapping table to "="
                //   check if d has no fractional digits
                input.charAt(idx | 0) || (map = '=', idx % 1);
                // "8 - idx % 1 * 8" generates the sequence 2, 4, 6, 8
                output += map.charAt(63 & block >> 8 - idx % 1 * 8)
                ) {
                charCode = input.charCodeAt(idx += 3/4);
                if (charCode > 0xFF) throw INVALID_CHARACTER_ERR;
                block = block << 8 | charCode;
            }
            return output;
        });

    // decoder
    // [https://gist.github.com/1020396] by [https://github.com/atk]
    object.atob || (
        object.atob = function (input) {
            input = input.replace(/=+$/, '')
            if (input.length % 4 == 1) throw INVALID_CHARACTER_ERR;
            for (
                // initialize result and counters
                var bc = 0, bs, buffer, idx = 0, output = '';
                // get next character
                buffer = input.charAt(idx++);
                // character found in table? initialize bit storage and add its ascii value;
                ~buffer && (bs = bc % 4 ? bs * 64 + buffer : buffer,
                    // and if not first of each 4 characters,
                    // convert the first 8 bits to one ascii character
                    bc++ % 4) ? output += String.fromCharCode(255 & bs >> (-2 * bc & 6)) : 0
                ) {
                // try to find character in table (0-63, not found => -1)
                buffer = chars.indexOf(buffer);
            }
            return output;
        });

}());

function utf8_to_b64(str) {
    return window.btoa(unescape(encodeURIComponent(str)));
}

function b64_to_utf8(str) {
    return decodeURIComponent(escape(window.atob(str)));
}