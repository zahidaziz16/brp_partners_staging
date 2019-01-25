/* -----------------------------------------------------------------
   S Y S T E M
----------------------------------------------------------------- */

function getURLVar(key) {
    var value = [];
    var query = String(document.location).split('?');
    if (query[1]) {
        var part = query[1].split('&');
        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');
            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }
        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
}

// Cart add remove functions

var cart = {
    'add': function(product_id, quantity) {
        var $product = $('.product-id_' + product_id).parent();

        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: 'product_id=' + product_id + '&quantity=' + (quantity !== undefined && Number(quantity) !== 0 ? quantity : 1),
            dataType: 'json',
            beforeSend: function() {
                $product
                    .find('.wait').remove().end()
                    .removeClass('tb_product_loaded tb_add_to_cart_loaded')
                    .addClass('tb_product_loading tb_add_to_cart_loading')
                    .find('.tb_button_add_to_cart').append('<span class="wait"></span>');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                if (json['redirect']) {
                    location = json['redirect'];
                } else
                if (json['error']) {
                    for (var first in json.error) break;
                    displayNotice('product', 'failure', product_id, json.error[first]);
                } else
                if (json['success']) {
                    setTimeout(function () {
                        $product
                            .removeClass('tb_product_loading tb_add_to_cart_loading')
                            .find('.tb_button_add_to_cart .wait').remove().end()
                            .addClass('tb_product_loaded tb_add_to_cart_loaded');

                        setTimeout(function () {
                            $product
                                .removeClass('tb_product_loaded tb_add_to_cart_loaded')
                        }, 5000);
                    }, 500);
                    $.get('index.php?route=common/cart/info', function(result) {
                        var $container = $(tbRootWindow.document).find('.tb_wt_header_cart_menu_system');

                        $container.find('.heading').replaceWith($(result).find('.heading').clone());
                        $container.find('.content').replaceWith($(result).find('.content').clone());

                        tbApp.triggerResizeCallbacks();
                    });
                    displayNotice('product', 'success', product_id, json['success']);
                }
            }
        });
    },
    'update': function(key, quantity) {
        $.ajax({
              url: 'index.php?route=checkout/cart/edit',
              type: 'post',
              data: 'key=' + key + '&quantity=' + (quantity !== undefined && Number(quantity) !== 0 ? quantity : 1),
              dataType: 'json',
              beforeSend: function() {
                    $('#cart > button').button('loading');
              },
              success: function(json) {
                    $('#cart > button').button('reset');
                    if (tbApp['/tb/route'] === 'checkout/cart' || tbApp['/tb/route'] === 'checkout/checkout') {
                          location = 'index.php?route=checkout/cart';
                    } else {
                          $('.tb_wt_header_cart_menu_system').load('index.php?route=common/cart/info');
                    }
              }
        });
    },
    'remove': function(key) {
        $.ajax({
            url: 'index.php?route=checkout/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            success: function(json) {
                $('#cart > button').button('reset');
                if (tbApp['/tb/route'] === 'checkout/cart' || tbApp['/tb/route'] === 'checkout/checkout') {
                    location = tbApp['/tb/url/shopping_cart'];
                } else {
                    $.get('index.php?route=common/cart/info', function(result) {
                      var $items  = $(result).find('.content').clone(),
                          $title  = $(result).find('.heading').clone();

                      $('.tb_wt_header_cart_menu_system .heading').replaceWith($title);
                      $('.tb_wt_header_cart_menu_system .content').replaceWith($items);
                      tbApp.triggerResizeCallbacks();
                    });
                }
            }
        });
    }
};

var voucher = {
  'add': function() {

  },
  'remove': function(key) {
    $.ajax({
      url: 'index.php?route=checkout/cart/remove',
      type: 'post',
      data: 'key=' + key,
      dataType: 'json',
      beforeSend: function() {
        $('#cart > button').button('loading');
      },
      complete: function() {
        $('#cart > button').button('reset');
      },
      success: function(json) {
        $('#cart-total').html(json['total']);
        if (tbApp['/tb/route'] === 'checkout/cart' || tbApp['/tb/route'] === 'checkout/checkout') {
          location = 'index.php?route=checkout/cart';
        } else {
          $('.tb_wt_header_cart_menu_system').load('index.php?route=common/cart/info');
        }
      }
    });
  }
};

var wishlist = {
  'add': function(product_id) {
    var $product = $('.product-id_' + product_id).parent();

    $.ajax({
      url: 'index.php?route=account/wishlist/add',
      type: 'post',
      data: 'product_id=' + product_id,
      dataType: 'json',
      beforeSend: function() {
          $product
              .find('.wait').remove().end()
              .removeClass('tb_product_loaded tb_wishlist_loaded')
              .addClass('tb_product_loading tb_wishlist_loading')
              .find('.tb_button_wishlist').append('<span class="wait"></span>');
      },
      success: function(json) {
        $('.alert').remove();

        if (json['success']) {
            displayNotice('wishlist', 'success', product_id, json['success']);
        }
        if (json['info']) {
            displayNotice('wishlist', 'failure', product_id, json['info']);
        }

        setTimeout(function () {
            $product
                .removeClass('tb_product_loading tb_wishlist_loading')
                .find('.tb_button_wishlist .wait').remove().end()
                .addClass('tb_product_loaded tb_wishlist_loaded');

            setTimeout(function () {
                $product
                    .removeClass('tb_product_loaded tb_wishlist_loaded')
            }, 5000);
        }, 500);

        $(tbRootWindow.document).find('.wishlist-total').html(json['total']);

        Array.prototype.forEach.call(tbRootWindow.document.querySelectorAll('a.wishlist_total, li.wishlist_total > a > .tb_text'), function(el) {
            var number = json['total'].replace(/[^0-9]/g, '');

            $(el).find('.tb_items').remove();
            $(el).append('<span class="tb_items">' + number + '</span>');
        });
      }
    });
  },
  'remove': function() {

  }
};

var compare = {
    'add': function(product_id) {
        $.ajax({
            url: 'index.php?route=product/compare/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function(json) {
                $('.alert').remove();

                if (json['success']) {
                    displayNotice('compare', 'success', product_id, json['success']);

                    $(tbRootWindow.document).find('#compare-total').html(json['total']);

                    Array.prototype.forEach.call(tbRootWindow.document.querySelectorAll('a.tb_compare_total, li.tb_compare_total > a > .tb_text'), function(el) {
                        var number = json['total'].replace(/[^0-9]/g, '');

                        $(el).find('.tb_items').remove();
                        $(el).append('<span class="tb_items">' + number + '</span>');
                    });
                }
            }
        });
    },
    'remove': function() {
    }
};

// Search

function moduleSearch($element) {
    var filter_name = $element.parent().find('input[name=search]').val();

    if (filter_name) {
        var operator = tbApp['/tb/url/search'].indexOf('?') !== -1 ? '&' : '?';

        location = tbApp['/tb/url/search'] + operator + 'search=' + encodeURIComponent(filter_name);
    }
}

// Currency & Language

function changeLanguage($link) {
    $('input[name=\'code\']').attr('value', $link.attr('data-language-code'));

    if ($link.attr("href") == "javascript:;") {
        $link.closest('form').submit()
    }
}

function changeCurrency($link) {
    $('input[name=\'code\']').attr('value', $link.attr('data-currency-code'));
    $link.closest('form').submit()
}

// Autocomplete */

(function($) {
    $.fn.autocomplete = function(option) {
        return this.each(function() {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Focus
            $(this).on('focus', function() {
                this.request();
            });

            // Blur
            $(this).on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('keydown', function(event) {
                switch(event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function(event) {
                event.preventDefault();

                value = $(event.target).parent().attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            };

            // Show
            this.show = function() {
                var pos = $(this).position();

                $(this).siblings('ul.dropdown-menu').css({
                    display: 'block',
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });

                $(this).siblings('ul.dropdown-menu').show();
            };

            // Hide
            this.hide = function() {
                $(this).siblings('ul.dropdown-menu').hide();
            };

            // Request
            this.request = function() {
                clearTimeout(this.timer);

                this.timer = setTimeout(function(object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            };

            // Response
            this.response = function(json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['value']] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i]['category']) {
                            html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
                        }
                    }

                    // Get all the ones with a categories
                    var category = new Array();

                    for (i = 0; i < json.length; i++) {
                        if (json[i]['category']) {
                            if (!category[json[i]['category']]) {
                                category[json[i]['category']] = new Array();
                                category[json[i]['category']]['name'] = json[i]['category'];
                                category[json[i]['category']]['item'] = new Array();
                            }

                            category[json[i]['category']]['item'].push(json[i]);
                        }
                    }

                    for (i in category) {
                        html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

                        for (j = 0; j < category[i]['item'].length; j++) {
                            html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu').html(html);
            };

            $(this).after('<ul class="autocomplete-menu dropdown-menu"></ul>');
            $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

        });
    }
})(window.jQuery);

/* -----------------------------------------------------------------
   R E S I Z E   E V E N T S
----------------------------------------------------------------- */

tbApp.onScriptLoaded(function() {
    tbApp.windowWidth = tbWindowWidth;
    tbApp.lang_dir    = $('html').attr('dir');
    tbUtils.onSizeChange(function() {
        tbApp.windowWidth = window.innerWidth;
    }, false, false, 'windowWidth');
    includeSvgResource("catalog/view/theme/" + tbApp["/tb/basename"] + "/image/icons.svg", 2);
});

/* -----------------------------------------------------------------
   G L O B A L   V A R S
----------------------------------------------------------------- */

var tb_navigation_hovered = false;

/* -----------------------------------------------------------------
   N O T I F I C A T I O N S
----------------------------------------------------------------- */

var displayNotice = tbRootWindow.displayNotice !== undefined ? tbRootWindow.displayNotice : function(context, type, product_id, message) {

    var stack = Number(tbApp['/tb/msg_stack']);
    var thumb;

    if (!stack) {
        $.noty.closeAll();
    }

    var $product = $(".product-id_" + product_id).first().parent();

    if (!$product.length) {
        $product = $('.tb_wt_product_images_system');
        thumb = !$product.find('.image img').length ? $product.find('.thumbnail img').attr('src') : $product.find('.image img').attr('src');
    } else {
        thumb = $product.find(".image img").attr("src");
    }

    if (!thumb) {
        thumb = tbApp['/tb/no_image'];
    }

    var buttons_config = [{
        type: 'btn', text: tbApp._t('text_continue'), click: function() {
            $.noty.closeAll();
        }
    }];

    var modal = false;
    var w_width  = window.innerWidth;
    var w_height = window.innerHeight;

    if (w_width <= 768 && w_height <= 768) {
        modal = true;
    }

    var msg_tpl;

    if ($product.length && thumb) {
        msg_tpl = '\
            <h3>\
              <svg class="tb_icon tb_main_color_bg"><use xlink:href="{{url}}#{{icon}}" /></svg>\
              {{title}}\
            </h3>\
            <div class="noty_text_body">\
              <a class="thumbnail" href=""><img src="{{thumb_src}}"></a>\
              <p>{{contents}}</p>\
            </div>';
    } else {
        msg_tpl = '\
            <h3>\
              <svg class="tb_icon tb_main_color_bg"><use xlink:href="{{url}}#{{icon}}" /></svg>\
              {{title}}\
            </h3>\
            <p>{{contents}}</p>';
    }

    var showNotice = function(title) {
        var template_vars = {
            icon:      type == 'success' ? 'check' : 'close',
            title:     title,
            contents:  message,
            thumb_src: thumb,
            url:       window.location.href
        };

        noty({
            text: $.tim(msg_tpl, template_vars),
            layout: tbApp['/tb/msg_position'],
            closeOnSelfClick: false,
            modal: modal,
            buttons: modal ? buttons_config : false,
            closeButton: !modal,
            timeout: modal ? false : Number(tbApp['/tb/msg_timeout']),
            animateOpen: {opacity: 'toggle'},
            animateClose: {opacity: 'toggle'},
            close_speed: stack ? 500 : 0,
            onClose: function() {
                $(document).unbind('touchmove.noty');
            }
        });
    };

    if (type == 'failure') {
        showNotice(tbApp._t('text_failure'));
        return;
    }

    switch (context) {
        case 'product':
            buttons_config = [{
                type: 'btn', text: tbApp._t('text_continue_shopping'), click: function() {
                    $.noty.closeAll();
                }
            },{
                type: 'btn', text: tbApp._t('text_shopping_cart'), click: function() {
                    window.location = tbApp['/tb/url/shopping_cart'];
                }
            }];

            showNotice(tbApp._t('text_cart_updated'));
            break;
        case 'wishlist':
            buttons_config.push({
                type: 'btn', text: tbApp._t('text_wishlist'), click: function() {
                    window.location = tbApp['/tb/url/wishlist'];
                }
            });
            showNotice(tbApp._t('text_wishlist_updated'));
            break;
        case 'compare':
            buttons_config.push({
                type: 'btn', text: tbApp._t('text_product_comparison'), click: function() {
                    window.location = tbApp['/tb/url/compare'];
                }
            });
            showNotice(tbApp._t('text_compare_updated'));
            break;
    }
};

/* -----------------------------------------------------------------
   S T I C K Y   S I D E B A R
----------------------------------------------------------------- */

function stickyColumn(selectors, offset) {
    tbApp.onScriptLoaded(function() {
        selectors.split(",").forEach(function(selector) {
            var $selector = $(selector);

            $selector
                .wrapInner('<div class="col-sticky"></div>')
                .find('> .col-sticky')
                .stick_in_parent({
                    offset_top: parseInt($selector.css('paddingTop')) + Number(offset)
                });
        });
    });
}

/* -----------------------------------------------------------------
   S C R O L L   T O   T O P
----------------------------------------------------------------- */

function scroll_to_top(speed) {

    if (window.innerWidth <= 768 && window.innerHeight <= 768) {
        return;
    }

    if (speed === undefined) {
        speed = 800;
    }

    var $scroll_button = $('<a id="tbScrollToTop" class="btn btn-default tb_no_text" href="javascript:;"><i class="fa fa-angle-up"></i></a>').appendTo('body');

    $scroll_button.bind('click', function(){
        $('html, body').animate({ scrollTop: 0 }, speed);
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 150 && $scroll_button.css('opacity') < 100) {
            $scroll_button.css('opacity', 100);
        } else {
            $scroll_button.css('opacity', 0);
        }
    });
}

/* -----------------------------------------------------------------
   H E A D E R
----------------------------------------------------------------- */

function sticky_header(style, layout, padding) {

    if ($('#header').length == 0 || window.innerWidth <= 768 && window.innerHeight <= 768) {
        return;
    }

    if (style === undefined) {
        style = 'minimal';
    }

    if (layout === undefined) {
        layout = 'full';
    }

    if (padding === undefined) {
        padding = '10px 0';
    }

    var $cont    = $('#header'),
        $wrap    = $('#wrapper'),
        $temp;

    var height      = $cont.outerHeight(true),
        offset      = $cont.offset(),
        //height_mod  = parseInt($cont.next().css('margin-top')) + $cont.next().offset().top - height - offset.top,
        old_classes = $cont.attr('class'),
        classes     = layout != 'full_fixed' ? 'tb_sticky_width_' + layout : 'tb_sticky_width_full tb_content_fixed',
        scrolled    = false,
        animation_timeout;

    // Default style

    if (style == 'default') {
        $cont
            .addClass(classes + ' tbSticky');
        $('body')
            .css('padding-top', ($cont.hasClass('tb_header_overlay') ? 0 : height));
    }

    function clone_widget($widget) {

        tbApp.trigger('beforeCloneWidget', [$widget]);

        var $clonedWidget = $widget.clone(true)
            .css({'padding-left': 0, 'padding-right': 0})
            .removeClass('display-block')
            .addClass('display-inline-block')
            .find("script").remove().end();

        $temp.find('.col-md-12').append($clonedWidget);
    }

    tbApp.onWindowLoaded(function() {

        $(window).scroll(function () {
            if (window.pageYOffset > height + offset.top - 20 && tbApp.windowWidth > 768 && !tb_navigation_hovered) {

                // minimal style

                if ($wrap.find('> .tbSticky').length == 0) {

                    if (style == 'minimal' && $('#sticky_header').length == 0) {
                        $temp = $('<div id="sticky_header" class="tb_area_header ' + classes + ' tbSticky" style="padding: ' + padding + '"><div class="row tbStickyRow"><div class="col col-xs-12 col-md-12 col-valign-middle"></div></div></div>').prependTo($wrap);

                        $cont.find('.tbStickyShow:not([class*="tbStickyPosition"])').each(function () {
                            clone_widget($(this));
                        });

                        for (var i = 1; i <= 8; i++) {
                            $cont.find('.tbStickyPosition-' + i).each(function () {
                                clone_widget($(this));
                            });
                        }

                        $temp.find('.tb_hovered').removeClass('tb_hovered');

                        $temp.find('[id]:not(.tb_wt):not(#cart):not(#search):not(#site_logo)').each(function () {
                            $(this).attr('id', $(this).attr('id') + '_cloned');
                        });

                        $temp.find('[data-target]').each(function () {
                            $(this).data('target', $(this).data('target') + '_cloned');
                        });

                        dropdown_menu('#sticky_header .dropdown');

                    }
                }
                else {
                    $('#sticky_header').show();
                }

                // default style

                if (style == 'default' && $wrap.find('> .tbStickyScrolled').length == 0) {
                    clearTimeout(animation_timeout);

                    $cont
                        .removeClass('tbStickyRestored')
                        .addClass('tbElementsHidden')
                        .addClass('tbStickyScrolled')
                        .css('padding', padding);
                }
                
                scrolled = true;
            }
            else {
                if (style == 'minimal') {
                    $('#sticky_header')
                        .hide();
                    $cont
                        .css('height', '')
                        .css('padding', '');
                    $('body')
                        .css('padding-top', '');
                }
                if (style == 'default') {
                    $cont
                        .removeClass('tbStickyScrolled')
                        .css('padding', '');
                        
                    if (scrolled) {
                      $cont
                        .addClass('tbStickyRestored')
                    }

                    clearTimeout(animation_timeout);

                    animation_timeout = setTimeout(function() {
                        $cont
                            .removeClass('tbElementsHidden');
                    }, 500);
                }
            }
        });
        $(window).trigger('scroll');
    });
}

function responsive_header() {
    if (!$('#header').length) {
        return false;
    }

    var $temp;

    $('#header')
        .on('click', '.tbToggleButtons span', function () {
            if ($(this).hasClass('tbToggleCart')) {
                $temp = $('<div id="tb_mobile_car_menu" class="tbMobileMenu tbMobileCartMenu"><div class="row"><div class="col col-xs-12 col-sm-12 col-valign-top"></div></div></div>').appendTo('#wrapper');

                $('#header').find('.tb_wt_header_cart_menu_system').each(function () {
                    var $clonedMenu = $(this).clone(true).find("script").remove().end();

                    $temp.find('.col-sm-12').append($clonedMenu);
                    $('html').addClass('tbCartMenu');
                    element_query('#tb_mobile_car_menu', '500,300,0', '.cart-info');
                });
            } else {
                $temp = $('<div class="tbMobileMenu"><div class="row"><div class="col col-xs-12 col-sm-12 col-valign-top"></div></div></div>').appendTo('#wrapper');

                $('#header').find('.tbMobileMenuShow:not([class*="tbMobilePosition"])').each(function () {
                    var $clonedMenu = $(this).clone(true).find("script").remove().end();

                    $temp.find('.col-sm-12').append($clonedMenu);
                });
                for (var i = 1; i <= 8; i++) {
                    $('#header').find('.tbMobileMenuShow.tbMobilePosition-' + i).each(function () {
                        var $clonedMenu = $(this).clone(true).find("script").remove().end();

                        $temp.find('.col-sm-12').append($clonedMenu);
                    });
                }
                $temp.find('[id]:not(.tb_wt):not(#cart):not(#search):not(#site_logo)').each(function () {
                    $(this).attr('id', $(this).attr('id') + '_cloned');
                });
            }

            setTimeout(function(){
                $('.tbMobileMenuOverlay').addClass('tbActive');
                $('html').addClass('tbMobile');
                tbApp.triggerResizeCallbacks();
            }, 10);
        });

    $('#wrapper').append('' +
        '<div class="tbMobileMenuOverlay">' +
        '  <svg><use xlink:href="' + window.location.href + '#close" /></svg>' +
        '  <span class="tb_bg"></span>' +
        '</div>');

    $('#wrapper').on('click', '> .tbMobileMenuOverlay', function () {
        $('html').removeClass('tbMobile');
        $('.tbMobileMenuOverlay').removeClass('tbActive');
        setTimeout(function() {
            $('#wrapper').find('> .tbMobileMenu').remove();
        }, 500);
        tbApp.triggerResizeCallbacks();
    });

    if (window.innerWidth <= 768 && window.innerHeight > 768
        || window.innerWidth > 768 && window.innerHeight <= 768) {

        function responsive() {
            if (window.innerWidth > 768) {
                $('#wrapper').find('> .tbMobileMenu').remove();
                $('html').removeClass('tbMobile');
                $('.tbMobileMenuOverlay').removeClass('tbActive');
            }
        }

        tbUtils.onSizeChange(responsive, false, false, false);
    }
}

/* -----------------------------------------------------------------
   T A B S   /   A C C O R D I O N
----------------------------------------------------------------- */

function createGroup(container, type) {

    var initSubwidget = function($tab, $panel) {

        $panel.find("> div").each(function() {
            var id = $(this).attr("id");

            if (!$(this).data("initialized")) {

                if ("function" == typeof tbApp["init" + id]) {
                    tbApp["init" + id]();
                }

                $(this).data("initialized", true);
            }

            if ("function" == typeof tbApp["exec" + id]) {
                tbApp["exec" + id]();
            }
        });
    };

    var $container = $('#' + container);

    $container.find('.tab-content').addClass('tbAnimated');
    $container.find('li.active').addClass('tbActivated');

    if (type == 'tabs') {

        initSubwidget('tab', $($container.find('li.active > a[data-toggle="tab"]').attr('href')).find('> .panel-body'));

        // Init tabs
        $container.find('a[data-toggle="tab"]')
            .on('show.bs.tab', function(e) {
                var $tab      = $($(e.target).attr('href')),
                    $prev_tab = $($(e.relatedTarget).attr('href'));

                $tab.css('height', $prev_tab.height());

                if (!$(e.target).parent().hasClass("tbActivated")) {
                    $tab.addClass("tb_loading");
                    $tab.prepend('<span class="wait"></span>');
                }
            })
            .on('shown.bs.tab', function(e) {
                var $tab = $('#' + $(e.target).attr('href').substring(1));

                initSubwidget($(e.target), $tab.find('> .panel-body'));

                if (!$(e.target).parent().hasClass("tbActivated")) {
                    setTimeout(function () {
                        $(e.target).parent().addClass("tbActivated");
                        $tab.find("> .wait").remove();
                        $tab.removeClass("tb_loading");
                        $tab.find("> *").fadeOut(0).fadeIn(300);
                        var old_h = $tab.height();
                        $tab.css('height', '');

                        var new_h = $tab.height();
                        var diff = new_h - old_h;

                        $tab.css('height', old_h);
                        $tab.css('overflow', 'hidden');
                        $tab.animate({height: '+=' + diff}, 300);
                    }, 250);
                } else {
                    var old_h = $tab.height();
                    $tab.css('height', '');

                    var new_h = $tab.height();
                    var diff = new_h - old_h;

                    $tab.css('height', old_h);
                    $tab.animate({height: '+=' + diff}, 300);
                }
            });

    } else {

        // Init accordion
        var init_height = 50;

        $container.find(".panel-collapse")
            .on('show.bs.collapse', function(e) {
                var $panel = $(e.target).find('> .panel-body');

                init_height = $container.find('.in.tbActivated').height();

                if (!$(e.target).hasClass("tbActivated")) {
                    $panel.css('height', init_height);
                    $panel.addClass('tb_loading');
                    $panel.prepend('<span class="wait"></span>');
                }
            })
            .on('shown.bs.collapse', function(e) {
                var $panel = $(e.target).find('> .panel-body');

                initSubwidget('tab', $panel);

                if (!$(e.target).hasClass("tbActivated")) {
                    setTimeout(function () {
                        $(e.target).addClass("tbActivated");
                        $panel.find("> .wait").remove();
                        $panel.removeClass("tb_loading");
                        $panel.find(' > .panel-body > *').fadeOut(0).fadeIn(300);

                        var old_height = $panel.outerHeight();
                        $panel.css('height', '');

                        var new_height = $panel.outerHeight();

                        $panel.css('height', old_height);

                        $panel.animate({height: '+=' + (new_height - old_height)}, 300, function () {
                            $panel.css('overflow', '');
                        });
                    }, 250);
                }
            });
    }

}

/* -----------------------------------------------------------------
   C A R O U S E L
----------------------------------------------------------------- */

function createItemSlider(container, items_count, slide_step, speed, pagination, responsive_params, autoplay, loop) {

    if (autoplay === undefined) {
        autoplay = 0;
    }

    if (loop === undefined) {
        loop = false;
    }

    var $heading        = $(container).is('.has_slider:not(.no_title)') ? $(container).find('.panel-heading, .box-heading') : $(container).closest('.tb_wt_group').find('.nav-tabs'),
        $slides         = $(container + ' .tb_listing').children(),
        init            = false,
        side_nav        = $(container).is('.tb_side_nav') || !$heading.length ? 1 : 0,
        responsive_keys = [],
        listing_style   = '';

    listing_style += $(container + ' .tb_listing').is('.tb_style_bordered') ? ' tb_bordered' : '';
    listing_style += $(container + ' .tb_listing').is('.tb_compact_view')   ? ' tb_compact'  : '';

    for(var k in responsive_params) {
        responsive_keys.push(Number(k));
    }
    responsive_keys.sort(function(a, b){return a-b});

    function getRestrictions(c_width) {
        var result = {};

        if (responsive_keys.length) {
            $.each(responsive_keys, function(key, value) {
                result = responsive_params[value];
                if(c_width <= value) {
                    return false;
                }
            });
        }

        return result;
    }

    var swiperDeferred = $.Deferred();
    var SwiperObj = null;
    var current_slides_per_view;

    var buttons = function() {
        if (SwiperObj === null) {
            return;
        }

        if (SwiperObj.activeIndex < 1) {
            $(container)
                .find('.tb_prev').addClass('tb_disabled').end()
                .find('.tb_next').removeClass('tb_disabled');
        } else
        if (SwiperObj.activeIndex + current_slides_per_view == items_count) {
            $(container)
                .find('.tb_next').addClass('tb_disabled').end()
                .find('.tb_prev').removeClass('tb_disabled');
        } else {
            $(container).find('.tb_prev, .tb_next').removeClass('tb_disabled');
        }
    };

    function createSlider(slides_per_view, slide_step) {

        $slides.wrapAll('<div class="swiper-container"><div class="swiper-wrapper"></div></div>').wrap('<div class="swiper-slide"></div>');
        $(container)
            .addClass('has_slider')
            .find('.tb_listing')
            .addClass('tb_slider')
            .removeClass('tb_slider_load');

        if (pagination) {
            $(container)
                .find('.tb_listing')
                .after('<div class="tb_slider_pagination' + listing_style + '"></div>');
        }

        $(container)
            .find('.tb_listing')
            .after('<div class="tb_slider_controls">' +
                   '  <a class="tb_prev" href="javascript:;" title="' + tbApp._t('text_previous') + '">' +
                   '    <svg><use xlink:href="' + window.location.href + '#chevron_thin" /></svg>' +
                   '  </a>' +
                   '  <a class="tb_next" href="javascript:;" title="' + tbApp._t('text_next') + '">' +
                   '    <svg><use xlink:href="' + window.location.href + '#chevron_thin" /></svg>' +
                   '  </a>' +
                   '</div>');

        SwiperObj = new Swiper(container + ' .swiper-container', {
            setWrapperSize:         true,
            slidesPerView:          slides_per_view,
            slidesPerGroup:         slide_step,
            speed:                  speed,
            autoplay:               autoplay,
            loop:                   loop,
            loopAdditionalSlides:   slides_per_view,
            pagination:             pagination,
            paginationClickable:    true,
            slideActiveClass:       '',
            slideVisibleClass:      '',
            slideNextClass:         '',
            slidePrevClass:         '',
            // roundLengths:           true,
            bulletActiveClass:      'tb_active',
            onInit: function (swiper) {
                buttons();
                $(container).find('.swiper-container').addClass('tb_slider_init');
            },
            onSlideChangeEnd: function (swiper){
                buttons();
            },
            onTouchEnd: function (swiper){
                buttons();
            }
        });

        $(container).on('click', '.tb_prev, .tb_next', function() {
            $(container).addClass('tbSliderInteracted');
        });

        $(container).on('mouseover', function() {
            if (SwiperObj) {
                SwiperObj.stopAutoplay();
            }
        });
        $(container).on('mouseout', function() {
            if (SwiperObj && !$(this).is('.tbSliderInteracted')) {
                SwiperObj.startAutoplay();
            }
        });

        swiperDeferred.resolve(SwiperObj);
    }

    function destroySlider() {
        SwiperObj.destroy();
        SwiperObj = null;
        $(container).removeClass('has_slider')
            .find('.swiper-slide').unwrap().unwrap().end()
            .find('.swiper-slide > *').unwrap().end()
            .find('.tb_slider_controls, .tb_slider_pagination').remove();
        $(container).find('.tb_listing').removeClass('tb_slider');
    }

    function responsive() {

        var $width_container = side_nav ? $(container).find('.tb_listing') : $(container),
            restrictions = getRestrictions($width_container.width()),
            slides_per_view = Number(restrictions.items_per_row);

        if (items_count > slides_per_view && SwiperObj === null) {
            // create
            createSlider(slides_per_view, slide_step < slides_per_view ? slide_step : slides_per_view);
            $(container).find('.tb_slider_pagination').addClass('tb_size_' + slides_per_view);
            current_slides_per_view = slides_per_view;
            buttons();
        } else
        if (items_count <= slides_per_view && SwiperObj !== null) {
            // destroy
            destroySlider();
        } else
        if (current_slides_per_view != slides_per_view && SwiperObj !== null) {
            // reinit
            SwiperObj.params.slidesPerView = slides_per_view;
            SwiperObj.params.slidesPerGroup = slide_step < slides_per_view ? slide_step : slides_per_view;

            $(container).find('.tb_slider_pagination')
                .removeClass('tb_size_' + current_slides_per_view)
                .addClass('tb_size_' + slides_per_view);

            current_slides_per_view = slides_per_view;
            buttons();
        }

        var $nav  = $(container).find('.tb_slider_controls');

        if (!$nav.length) {
            return;
        }

        if (side_nav) {
            tbApp.onWindowLoaded(function() {
                var nav_height  = $nav.find('> a:first-child').height(),
                    mod_left    = parseInt($(container).css('padding-left')),
                    mod_right   = parseInt($(container).css('padding-right'));

                $nav.find('> a').css('margin-top', -($(container + ' .swiper-container').height()/2 + nav_height/2));
                $nav.find('> .tb_prev').css('margin-left', mod_left);
                $nav.find('> .tb_next').css('margin-right', mod_right);
                $nav.css('visibility', 'visible');
            });
        }
        else {
            var $container = $(container).is('.has_slider:not(.no_title)') ? $(container) : $(container).closest('.tb_wt_group'),
                mod_x      = $('html').attr('dir') == 'ltr' ? parseInt($heading.css('padding-right'))
                                               + parseInt($container.css('padding-right'))
                                               + parseInt($heading.css('margin-right'))
                                               : parseInt($heading.css('padding-left'))
                                               + parseInt($container.css('padding-left'))
                                               + parseInt($heading.css('margin-left')),
                mod_y      = ($heading.height() - $nav.outerHeight())/2 + parseInt($heading.css('padding-top')) + parseInt($container.css('padding-top'));

            mod_x += mod_x >= 15 ? -5 : 0;

            $nav.css('top', mod_y);
            $nav.css($('html').attr('dir') == 'ltr' ? 'right' : 'left', mod_x-4);
        }
    }

    $(container).on('click', '.tb_prev', function (e) {
        e.preventDefault();
        SwiperObj.slidePrev();
        buttons();
    });

    $(container).on('click', '.tb_next', function (e) {
        e.preventDefault();
        SwiperObj.slideNext();
        buttons();
    });

    return {
        refresh: function() {
            if(false !== responsive_params) {
                responsive();
                if (false === init) {
                    tbUtils.onSizeChange(function() {
                        if ($(container)[0].getBoundingClientRect().width > 0) {
                            responsive();
                            if ($(container).parent().parent().hasClass('tb_tabs_content')) {
                                $(container).parent().css('height', '');
                            }
                        }
                    }, false, false, 'createItemSlider_' + container);
                    init = true;
                }
            } else
            if (false === init) {
                buttons();
                init = true;
            }
        },
        swiperPromise: swiperDeferred.promise()
    }
}

/* -----------------------------------------------------------------
   L I G H T B O X
----------------------------------------------------------------- */

function lightbox_gallery(id, slider, index, gallery_items, gallery_options) {

    var pswpElement  = '';
        pswpElement += '<div id="lightbox_gallery_' + id + '" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">';
        pswpElement += '  <div class="pswp__bg"></div>';
        pswpElement += '  <div class="pswp__scroll-wrap">';
        pswpElement += '    <div class="pswp__container">';
        pswpElement += '      <div class="pswp__item"></div>';
        pswpElement += '      <div class="pswp__item"></div>';
        pswpElement += '      <div class="pswp__item"></div>';
        pswpElement += '    </div>';
        pswpElement += '    <div class="pswp__ui pswp__ui--hidden">';
        pswpElement += '      <div class="pswp__top-bar">';
        pswpElement += '        <div class="pswp__counter"></div>';
        pswpElement += '        <a class="pswp__button pswp__button--close" title="Close (Esc)"></a>';
        pswpElement += '        <a class="pswp__button pswp__button--share" title="Share"></a>';
        pswpElement += '        <a class="pswp__button pswp__button--fs" title="Toggle fullscreen"></a>';
        pswpElement += '        <a class="pswp__button pswp__button--zoom" title="Zoom in/out"></a>';
        pswpElement += '        <div class="pswp__preloader">';
        pswpElement += '          <div class="pswp__preloader__icn">';
        pswpElement += '            <div class="pswp__preloader__cut">';
        pswpElement += '              <div class="pswp__preloader__donut"></div>';
        pswpElement += '            </div>';
        pswpElement += '          </div>';
        pswpElement += '        </div>';
        pswpElement += '      </div>';
        pswpElement += '      <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">';
        pswpElement += '        <div class="pswp__share-tooltip"></div>';
        pswpElement += '      </div>';
        pswpElement += '      <a class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></a>';
        pswpElement += '      <a class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></a>';
        pswpElement += '      <div class="pswp__caption">';
        pswpElement += '        <div class="pswp__caption__center"></div>';
        pswpElement += '      </div>';
        pswpElement += '    </div>';
        pswpElement += '  </div>';
        pswpElement += '</div>';

    if (gallery_options === undefined) {
        gallery_options = {
            showHideOpacity:       true,
            // showAnimationDuration: false,
            // hideAnimationDuration: false,
            shareEl:               false,
            getThumbBoundsFn: function(index) {
                var thumbnail   = slider ? $('#' + id + ' .tb_slides')[0] : $('#' + id + ' .tb_gallery a')[index],
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect        = thumbnail.getBoundingClientRect(),
                    ratio       = $(thumbnail).data('ratio'),
                    x_mod       = 0,
                    y_mod       = 0;

                if (thumbnail.hasAttribute('data-ratio')) {
                    x_mod += ratio > 1 ? Math.round($(thumbnail).height() * ratio) - $(thumbnail).width()  : 0;
                    y_mod += ratio < 1 ? Math.round($(thumbnail).width()  / ratio) - $(thumbnail).height() : 0;
                }

                return {x:rect.left - x_mod/2, y:rect.top + pageYScroll  - y_mod/2, w:rect.width + x_mod};
            }
        };

    }

    gallery_options.index = slider ? slider.relative.activeSlide : index;

    var fullscreen_gallery = new PhotoSwipe (
        $(pswpElement).appendTo('body')[0],
        PhotoSwipeUI_Default,
        gallery_items,
        gallery_options
    );

    if (slider) {
        fullscreen_gallery.listen('afterChange', function () {
            slider.toCenter(fullscreen_gallery.getCurrentIndex(), true);
        });
    }

    fullscreen_gallery.listen('destroy', function() {
        $('#lightbox_gallery_' + id).remove();
    });

    fullscreen_gallery.init();

}

/* -----------------------------------------------------------------
   C O U N T E R
----------------------------------------------------------------- */

function create_countdown(container, server_date, timezone) {
    $(container).find('.tb_counter_time').each(function() {
        var finalDate = new Date($(this).data('special-price-end-date')),
            $counter  = $(this);

        $counter.countdown({
            until:  finalDate,
            serverSync: function() {
                return new Date(server_date);
            },
            timezone: timezone,
            layout: '{d<}<span class="tb_counter_days">{dn}</span>{d>}'   +
                    '<span class="tb_counter_hours">{hn}</span>'   +
                    '<span class="tb_counter_minutes">{mnn}</span>' +
                    '<span class="tb_counter_seconds">{snn}</span>'
        });
    });
}

/* -----------------------------------------------------------------
   P R O D U C T   H O V E R
----------------------------------------------------------------- */

function item_hover(container, active_elements, hover_elements, hover_style) {

    $(container).find('.tb_grid_view').addClass('tbHoverReady');

    // Append
    if(hover_style == 'append') {
        $(container + ' .tb_grid_view:not(".tb_slider")').find('> div').each(function() {
            var $item = $(this);
            var $hover_content = $item.clone();

            $hover_content.find(hover_elements).remove();
            $hover_content.find('div:not(:has(*)):not(.description)').remove();
            $hover_content.find('img')
                .attr('src', $hover_content.find('img').data('src'))
                .removeClass('lazyload')
                .removeAttr('data-aspectratio')
                .css('opacity', 100)
                .css('height', '');

            $item.find(active_elements).remove();
            $item.find('div:not(:has(*)):not(.description)').remove();

            $item.hover(
                function() {
                    $item.append('<div class="tb_item_hovered">' + $hover_content.html() + '</div>');
                }, function() {
                    $item.find('.tb_item_hovered').remove();
                }
            );
        });
    }

    // Flip && Overlay
    if(hover_style == 'flip' || hover_style == 'overlay') {
        $(container + ' .tb_grid_view').find('> div').each(function() {
            var $item = $(this);

            var $active_content = $item.clone();
            var $hover_content = $item.clone();

            $active_content.find(active_elements).remove();
            $active_content.find('div:not(:has(*)):not(.description)').remove();
            $active_content.find('div:not(:has(*)):not(.description)').remove();

            $hover_content.find(hover_elements).remove();
            $hover_content.find('div:not(:has(*)):not(.description)').remove();
            $hover_content.find('div:not(:has(*)):not(.description)').remove();

            $item.children().remove();

            $item.append('<div class="tb_' + hover_style + '"><div class="tb_item_info_active tb_front">' + $active_content.html() + '</div><div class="tb_item_info_hover tb_back">' + $hover_content.html() + '</div></div>');

            $item.find('div:not(:has(*)):not(.description)').remove();
        });
    }
}

/* -----------------------------------------------------------------
   T H U M B   H O V E R
 ----------------------------------------------------------------- */

function thumb_hover(container, hover_style) {

    if(hover_style != 'flip' && hover_style != 'overlay') {
        return;
    }
    // Flip & overlay
    $(container + ' .tb_products.tb_listing').find('> div').each(function() {

        if (!$(this).find(".image_hover").length) {
            return true;
        }
        $(this).find('.image_hover img').each(function() {
            $(this).attr('src', $(this).data('src')).css('opacity', 1);
        });
        $(this)
            .find('.image').addClass('tb_front').end()
            .find('.image_hover').addClass('tb_back').end()
            .find('.image, .image_hover').wrapAll('<div class="image tb_' + hover_style + '"></div>');
    });
}

/* -----------------------------------------------------------------
   C O O K I E   P O L I C Y
----------------------------------------------------------------- */

function cookie_policy(policy_text) {

    if ($.cookie('agreed_with_cookie_policy')) {
        return;
    }

    noty({
        text: '<h3>' + tbApp._t('text_cookie_policy_title') + '</h3><p>' + policy_text + '</p>',
        layout: tbApp['/tb/msg_position'],
        closeOnSelfClick: false,
        modal: false,
        buttons: [{
            type: 'btn',
            text: tbApp._t('text_cookie_policy_button'),
            click: function() {
                $.cookie('agreed_with_cookie_policy', '1', { expires: 30 });
                $.noty.closeAll();
            }
        }],
        closeButton: false,
        timeout: false,
        animateOpen: {opacity: 'toggle'},
        animateClose: {opacity: 'toggle'},
        close_speed: 500,
        onClose: function() {
            $(document).unbind('touchmove.noty');
        }
    });
}

/* -----------------------------------------------------------------
   D R O P D O W N   M E N U
----------------------------------------------------------------- */

function menu_position($dropdown, offset) {

    var menu_space  = $dropdown.outerWidth() + offset.left,
        margin_left = menu_space > tbApp.windowWidth ? tbApp.windowWidth - menu_space - 30 : 0;

    margin_left    += offset.left < 15 ? Math.abs(offset.left) + 15 : 0;

    if (margin_left == 0) {
        return;
    }

    if ($('html').is('[dir="ltr"]')) {
        $dropdown.css('margin-left', margin_left);
    } else {
        $dropdown.css('margin-right', -margin_left);
    }
}

function megamenu_position($nav, $menu, $dropdown, $relativeTo, orientation) {

    var menu_left,
        menu_top,
        menu_width,
        current_site_width = tbApp['/tb/maximum_width'];

    if (tbApp.windowWidth - 60 <= tbApp['/tb/maximum_width']) {
        current_site_width = tbApp.windowWidth - 60;
    }

    if ($menu.parent().hasClass('dropdown-menu')) {
        orientation = 'vertical';
    }

    if ($nav.data('relative_to') == 'content' && orientation == 'horizontal' && $relativeTo.width() < current_site_width) {
        menu_left  = $relativeTo.offset().left;
        menu_left -= $menu.offset().left; // -1 removed
        menu_left += parseInt($relativeTo.css('padding-left'));
        menu_top   = 0;
        menu_width = $relativeTo.width();
    } else {
        menu_left    = (tbApp.windowWidth - current_site_width) / 2 - parseInt($menu.offset().left); // -1 removed
        menu_left  += $nav.data('relative_to') == 'content' && $relativeTo.width() < current_site_width ? parseInt($relativeTo.css('padding-left')) : 0;
        menu_left  += $('html').attr('dir') == 'rtl' ? -7 : 0;
        menu_left   = orientation == 'horizontal' ? menu_left : 0;
        menu_top    = 0;
        //menu_top   += orientation == 'vertical' ? $nav.offset().top - $menu.offset().top : 0;
        menu_width  = current_site_width;
        menu_width += $nav.data('relative_to') == 'content' && $relativeTo.width() < current_site_width ? -parseInt($relativeTo.css('padding-left')) : 0;
        menu_width += $nav.data('relative_to') == 'content' && $relativeTo.width() < current_site_width ? -parseInt($relativeTo.css('padding-right')) : 0;
        menu_width += !orientation == 'vertical' && !$menu.parent().is('.dropdown-menu') ? -$nav.width() : 0;
        menu_width += $menu.parent().is('.dropdown-menu') ? -$menu.parent().innerWidth() : 0;
    }
    if ($nav.data('relative_to') == 'block' && orientation == 'vertical' && $menu.hasClass('tb_megamenu')) {
        if ($menu.offset().top - $nav.closest('div').offset().top < $dropdown.outerHeight()) {
            menu_top -= $menu.offset().top - $nav.closest('div').offset().top;
        }
    }

    $dropdown
        .css('margin-top', menu_top);

    if (!$menu.is('.tb_auto_width') || $menu.data('dropdown-width') > menu_width) {
        $dropdown
            .css('margin-left', menu_left)
            .css('width', menu_width);
    }

    if ($menu.is('.tb_tabbed_menu')) {
        var $link = $dropdown.find('> .tb_tabs > .nav-tabs > li.active'),
            tab   = '#' + $link.find('> a').data('target');

        $dropdown.find('> .tb_tabs > .nav-tabs').css('min-width', $menu.width());
    }
}

function dropdown_menu(selector, menu, direction, delay) {

    if (selector === undefined) {
        selector = '.dropdown-menu';
    }

    if (menu === undefined) {
        menu = '> li';
    }

    if (direction === undefined) {
        direction = 'right';
    }

    if (delay === undefined) {
        delay = 0;
    }

    $(selector).menuAim({
        triggerEvent:       'hover',
        rowSelector:        menu,
        submenuDirection:   direction,
        tolerance:          75,
        activationDelay:    delay,
        activateCallback: function(row) {

            var tb_sticky_parent;

            if ($(row).parent().is('.tb_grid_view') || $(row).parent().is('.tab-content')) {
                return;
            }

            $(row).addClass('tb_hovered');

            if ($(row).closest('#header').length) {
                tb_navigation_hovered = true;
            }
            if ($(row).closest('.tbSticky').length) {
                tb_sticky_parent = true;
            }

            var $nav         = $(row).closest('.nav'),
                $menu        = $(row),
                $dropdown    = $(row).find('.dropdown-menu').first(),
                $relativeTo  = $(row).closest('[class*="tb_area_"], .tbMobileMenu'),
                orientation  = $nav.length && $nav.hasClass('nav-horizontal') ? 'horizontal' : 'vertical';

            if (!$dropdown.length) {
                return;
            }

            $dropdown
                .css('margin-left',  '')
                .css('margin-right', '');

            // Regular menu

            var offset = $dropdown.offset();

            if (tb_sticky_parent && $menu.parent('.nav').length && ($dropdown.outerHeight() + offset.top - $(window).scrollTop > window.innerHeight)) {
                $dropdown.css('max-height', (window.innerHeight - 110));
                $dropdown.css('overflow-y', 'auto');
            }

            if (!$nav.length || !$menu.hasClass('tb_megamenu')) {
                menu_position($dropdown, offset);

                return;
            }

            // Megamenu

            megamenu_position($nav, $menu, $dropdown, $relativeTo, orientation);

        },
        deactivateCallback: function(row) {
            $(row).removeClass('tb_hovered')
                .find('.dropdown-menu').first().css('margin-left', '');

            tb_navigation_hovered = false;
        }
    });
}

/* -----------------------------------------------------------------
   T A B B E D   M E N U
----------------------------------------------------------------- */

function tabbed_menu(menu) {

    if (menu === undefined) {
        return false;
    }

    $('#' + menu).find('.nav-tabs').each(function() {
        var $menu      = $('#' + menu);


        $(this).menuAim({
            triggerEvent:       'hover',
            rowSelector:        '> li',
            submenuDirection:   'right',
            tolerance:          50,
            activationDelay:    0,
            activateCallback: function(row) {
                var $tab       = $('#' + $(row).find('> a').data('target')),
                    $container = $(row).closest('.tb_tabs');

                $container
                    .find('.active').removeClass('active').end()
                    .find('.tb_opened').removeClass('tb_opened');
                $(row).addClass('active');
                $tab.addClass('tb_opened');
                $menu.toggleClass('tb_first_tab_selected', $menu.find('.nav-tabs > li:first-child').is('.active'));
            }
        });
    });
}

/* -----------------------------------------------------------------
   Q U I C K V I E W
----------------------------------------------------------------- */

var tbShowQuickView,tbResizeQuickView;

function tbQuickView(product_id) {

    var $product = $('.product-id_' + product_id).parent(),
        $button  = $product.find('.tb_button_quickview a');

    if ($button.data('dialog')) {
        $('#' + $button.data('dialog')).modal('show');
    } else {

        var dialog_id = 'tbQuickviewModal-' + Math.floor(Math.random() * 100) + 1,
            html      = '';

        $product
            .find('.tb_button_quickview .wait').remove().end()
            .removeClass('tb_product_loaded tb_quickview_loaded')
            .addClass('tb_product_loading tb_quickview_loading')
            .find('.tb_button_quickview').addClass('tb_disabled')
            .find('a').prepend('<span class="wait"></span>');

        html += '<div id="' + dialog_id + '" class="modal modal--quickview fade">';
        html += '  <div class="modal-dialog">';
        html += '    <div class="modal-content">';
        html += '      <div class="modal-body">';
        html += '        <a href="javascript:;" class="close" data-dismiss="modal"></a>';
        html += '        <iframe scrolling="none"></iframe>';
        html += '      </div>';
        html += '    </div>';
        html += '  </div>';
        html += '</div>';

        $('body').append(html);

        var $iframe = $('#' + dialog_id).find('iframe');

        tbShowQuickView = function(height) {
            $iframe.height(height);
            $('#' + dialog_id).modal('show');
        };
        tbResizeQuickView = function(height) {
            $iframe.height(height);
        };

        $iframe
            .attr('src', 'index.php?route=product/product&tb_quickview=1&product_id=' + product_id)
            .on('load', function () {
                $product
                    .removeClass('tb_product_loading tb_quickview_loading')
                    .find('.tb_disabled').removeClass('tb_disabled')
                    .find('.wait').remove();
            });

        $button.data('dialog', dialog_id);
    }
}

/* -----------------------------------------------------------------
   B O O T
----------------------------------------------------------------- */

function bootApp() {

    var $body = $('body');

    /* ----------------------------------------
       Touch class
    ---------------------------------------- */

    if (tbUtils.is_touch) {
        tbUtils.addClass(document.querySelector('body'), 'is_touch');
    } else {
        tbUtils.addClass(document.querySelector('body'), 'no_touch');
    }

    /* ----------------------------------------
       System
    ---------------------------------------- */

    // Button load override

    var $uiButton = $.fn.button;

    $.fn.button = function() {
        if (arguments.length == 1 && typeof arguments[0] == 'string') {
          var $button = $(this);
          var position = $button.parent().hasClass('pull-right') ? 'before' : 'after';
          if (arguments[0] == 'loading') {
              $button.attr('disabled', true);
              if (position == 'before') {
                  $button.prev('.fa-spin').remove();
                  $button.before('<i class="fa fa-circle-o-notch fa-spin"></i>');
              } else {
                  $button.next('.fa-spin').remove();
                  $button.after('<i class="fa fa-circle-o-notch fa-spin"></i>');
              }
          }
          if (arguments[0] == 'reset') {
              $button.attr('disabled', false);
              if (position == 'before') {
                  setTimeout(function(){ $button.prev('.fa-spin').fadeOut(300, function() { $(this).remove(); }); }, 500);
              } else {
                  setTimeout(function(){ $button.next('.fa-spin').fadeOut(300, function() { $(this).remove(); }); }, 500);
              }
          }
        } else {
          $uiButton.apply(this, arguments);

        return this;
        }
    };

    // Form error highlight

    $('span.error, .text-danger').each(function() {
        if ($(this).parent().is('.help') || $(this).parent().is('.help-block')) {
            return;
        }
        $(this).closest('tr, .form-group').addClass('has-error');
    });

    // Close alert messages

    $body.on('click', '.alert-dismissible, .success, .attention, .warning, .information', function() {
        $(this).fadeTo(200, 0, function() {
            $(this).slideUp(300, function() {
                $(this).remove();
            });
        });
    });

    // Currency & Language

    $('#currency, #form-currency').on('click', 'a[data-currency-code]', function() {
        changeCurrency($(this));
    });

    $('#language, #form-language').on('click', 'a[data-language-code]', function() {
        changeLanguage($(this));
    });

    // Site search

    $('#search_button').bind('click', function() {
        moduleSearch($(this));
    });

    $('#filter_keyword').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            var $element = $(this);

            tbApp.once('filterKeywordEnter', function(context) {
                if (context.redirect) {
                    moduleSearch($element);
                }
            });

            tbApp.trigger('filterKeywordEnter', [{redirect: true}]);
        }
    });

    /*/ Tooltips

    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    $(document).ajaxStop(function() {
        $('[data-toggle="tooltip"]').tooltip({container: 'body'});
    });
    */

    // Modal dialog

    $body.on('click', '.agree, .colorbox, .fancybox, .tb_popup', function(e) {
        e.preventDefault();

        var $link = $(this);

        if ($link.data('dialog')) {
            $('#' + $link.data('dialog')).modal('show');
        } else {
            var dialog_id = 'tbModal-' + Math.floor(Math.random() * 100) + 1;

            html = '<div id="' + dialog_id + '" class="modal fade">';
            html += '  <div class="modal-dialog">';
            html += '    <div class="modal-content">';
            html += '      <div class="modal-header">';
            html += '        <h2 class="modal-title">' + $link.text() + '</h2>';
            html += '        <a href="javascript:;" class="close" data-dismiss="modal"></a>';
            html += '      </div>';
            html += '      <div class="modal-body"></div>';
            html += '    </div>';
            html += '  </div>';
            html += '</div>';

            $('body').append(html);

            $('#' + dialog_id)
                .modal('show')
                .on('shown.bs.modal', function (e) {
                    if ($link.data('dialog')) {
                        return;
                    }

                    var jqxhr = $.ajax({
                        url: $link.attr('href'),
                        type: 'get',
                        dataType: 'html',
                        beforeSend: function () {
                            $('#' + dialog_id).find('.modal-body')
                                .addClass('tb_loading tb_blocked')
                                .append('<span class="wait"></span>');
                        }
                    });

                    setTimeout(function() {
                        jqxhr.done(function(data) {
                            $('#' + dialog_id).find('.modal-body').html(data)
                                .removeClass('tb_loading tb_blocked');
                        });
                    }, 500);

                    $link.data('dialog', dialog_id);
                });
        }
    });

    /* ----------------------------------------
       Theme
     ---------------------------------------- */

    // Element queries

    element_query('.responsive');
    element_query('.cart-info', '500,300,0');
    element_query('.tb_listing_options', '1000,650,350,0');
    element_query('.tb_wt_product_options_system', '1000,300,0');
    element_query('.tb_system_search_box', '550,0');

    if(tbUtils.is_touch) {
        $body
            .on('click', function(e) {
                $('.dropdown').removeClass('tb_hovered');
                $('.tb_toggle').removeClass('tb_active');
            })
            .on('click', '.dropdown > a, .dropdown-toggle, .tb_wt_header_cart_menu_system .dropdown > h3', function(e) {

                var $menu     = $(this).parent(),
                    $nav      = $menu.closest('.nav'),
                    $dropdown = $menu.find('.dropdown-menu').first();

                if ($(this).siblings('.dropdown-menu,ul')[0].getBoundingClientRect().width < 1) {
                    $('.dropdown').removeClass('tb_hovered');
                    $(this).parents('.dropdown').addClass('tb_hovered');
                    $(this).parent().find('> .tb_toggle').addClass('tb_active');

                    if (!$dropdown) {
                        return;
                    }
                    if (!$nav.length || !$menu.hasClass('tb_megamenu')) {

                        $dropdown
                            .css('margin-left',  '')
                            .css('margin-right', '');

                        var offset = $dropdown.offset();

                        menu_position($dropdown, offset);
                    }
                    if ($menu.is('.tb_megamenu')) {
                        var $relativeTo = $menu.closest('[class*="tb_area_"], .tbMobileMenu'),
                            orientation = $nav.length && $nav.hasClass('nav-horizontal') ? 'horizontal' : 'vertical';

                        megamenu_position($nav, $menu, $dropdown, $relativeTo, orientation);
                    }
                }
                else {
                    $(this).parent().removeClass('tb_hovered');
                    $(this).parent().find('> .tb_toggle').removeClass('tb_active');
                    if ($(this).attr('href')) {
                        window.location = $(this).attr('href');
                    }
                }

                return false;
            })
            .on('click', 'li > [data-target]', function(e) {
                var $menu = $(this).parent(),
                    $nav  = $(this).closest('.nav'),
                    $tab  = $('#' + $(this).data('target')),
                    $tabs = $tab.closest('.tab-content');

                if (!$menu.is('.active')) {
                    $nav.find('.active').removeClass('active');
                    $tabs.find('.tb_opened').removeClass('tb_opened');
                    $menu.addClass('active');
                    $tab.addClass('tb_opened');
                }
                else {
                    if ($(this).attr('href') && $(this).attr('href') != '#' && $(this).attr('href') != 'javascript:;') {
                        window.location = $(this).attr('href');
                    }
                }

                return false;
            });
    }

    $body
        .on('click', '.dropdown > .tb_toggle', function() {
            if ($(this).siblings('.dropdown-menu,ul')[0].getBoundingClientRect().width < 1) {
                $(this).parent().addClass('tb_hovered').find('> .tb_toggle').addClass('tb_active');
            } else {
                $(this).parent().removeClass('tb_hovered').find('> .tb_toggle').removeClass('tb_active');
            }

            return false;
        })
        .on('click', '.tab-content > .tb_toggle', function() {
            if ($(this).next('.tab-pane')[0].getBoundingClientRect().width < 1) {
                $(this)
                    .addClass('tb_active')
                    .next('.tab-pane').addClass('active');
            } else {
                $(this)
                    .removeClass('tb_active')
                    .next('.tab-pane').removeClass('active');
            }

            return false;
        });

    // Dropdown menus
    dropdown_menu('.dropdown-menu'      , '> li', tbApp.lang_dir, 100);
    dropdown_menu('.tb_hidden_menu > ul', '> li', tbApp.lang_dir, 100);
    dropdown_menu('.nav-stacked'        , '> li', tbApp.lang_dir, 100);
    dropdown_menu('.nav:not(.nav-stacked):not(.nav-tabs)' , '> li', 'below', 100);

    // Responsive header
    responsive_header();

    $('#wrapper')
        .find('.tb_wt_cart > .nav').each(function() {
            var $nav = $(this),
                url  = window.location.href;

    $nav.find('> .heading a').one('mouseenter', function() {
        $nav
            .addClass('active')
            .load('index.php?route=common/cart/info .nav > *')
            .on('mouseleave', function() {
                $(this).removeClass('active');
            });
        });
    });

}

window.tbBoot = function() {
    $(window).on('load', function(){
        tbApp.trigger("tbWindowLoaded");
        $(document.body).trigger("sticky_kit:recalc");
    });

    $(document).ready(function() {
        tbApp.trigger("tbScriptLoadedInit");

        bootApp();

        tbApp.trigger("tbScriptLoaded");
    });
};

if (window.tbCriticalLoaded) {
    window.tbBoot();
}