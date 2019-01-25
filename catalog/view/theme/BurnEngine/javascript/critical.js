var tbUtils = {
    bind: function(el, ev, fn) {
        if(window.addEventListener) {
            el.addEventListener(ev, fn, false);
        } else if(window.attachEvent) {
            el.attachEvent('on' + ev, fn);
        } else {
            el['on' + ev] = fn;
        }

        return fn;
    },

    unbind: function(el, ev, fn) {
        if(window.removeEventListener){
            el.removeEventListener(ev, fn, false);
        } else if(window.detachEvent) {
            el.detachEvent('on' + ev, fn);
        } else {
            elem['on' + ev] = null;
        }
    },

    stop: function(ev) {
        var e = ev || window.event;

        e.cancelBubble = true;
        if (e.stopPropagation) {
            e.stopPropagation();
        }
    },

    resizeCallBacks: [],
    resizeContainers: [],

    onSizeChange: function(fn, namespace, unbind_namespace, container_id) {

        if (typeof container_id == "undefined" || !container_id) {
            container_id = String(Math.random());
        }

        if (typeof unbind_namespace != "undefined" && !unbind_namespace && this.resizeContainers.indexOf(container_id) >= 0) {
            return;
        }

        this.resizeContainers.push(container_id);

        if (typeof namespace == "undefined" || !namespace) {
            namespace = 'main';
        }

        if (typeof unbind_namespace == "undefined") {
            unbind_namespace = false;
        }

        if (this.resizeCallBacks[namespace] === undefined) {
            this.resizeCallBacks[namespace] = [];
        }

        var eventName = window.hasOwnProperty("onorientationchange") ? "orientationchange" : "resize";

        if (unbind_namespace) {
            var unbind = this.unbind;
            Array.prototype.forEach.call(this.resizeCallBacks[namespace], function(callBack) {
                unbind(window, eventName, callBack);
            });
            this.resizeCallBacks[namespace] = [];
        }

        this.resizeCallBacks[namespace].unshift(fn);

        if (window.hasOwnProperty("onorientationchange")) {
            return this.bind(window, "orientationchange", fn);
        }

        // orientation-change-polyfill
        var currentWidth = window.outerWidth;
        var orientationChanged = function(callBack) {
            var newWidth = window.outerWidth;

            if (newWidth !== currentWidth) {
                currentWidth = newWidth;
                callBack && callBack();
            }
        };

        return this.bind(window, "resize", function() {
            orientationChanged(fn);
        });
    },

    onWindowLoaded: function(win, fn) {

        var done = false, top = true,

            doc = win.document,
            root = doc.documentElement,
            modern = doc.addEventListener,

            add = modern ? 'addEventListener' : 'attachEvent',
            rem = modern ? 'removeEventListener' : 'detachEvent',
            pre = modern ? '' : 'on',

            init = function(e) {
                if (e.type == 'readystatechange' && doc.readyState != 'complete') return;
                (e.type == 'load' ? win : doc)[rem](pre + e.type, init, false);
                if (!done && (done = true)) fn.call(win, e.type || e);
            },

            poll = function() {
                try { root.doScroll('left'); } catch(e) { setTimeout(poll, 50); return; }
                init('poll');
            };

        if (doc.readyState == 'complete') fn.call(win, 'lazy');
        else {
            if (!modern && root.doScroll) {
                try { top = !win.frameElement; } catch(e) { }
                if (top) poll();
            }
            doc[add](pre + 'DOMContentLoaded', init, false);
            doc[add](pre + 'readystatechange', init, false);
            win[add](pre + 'load', init, false);
        }
    },

    removeClass: function(el, className) {
        if (!el) {
            return;
        }

        if (el.classList) {
            var classValues = className.trim().split(' ');

            for (var i = 0; i < classValues.length; i++) {
                if (el.classList.contains(classValues[i])) {
                    el.classList.remove(classValues[i]);
                }
            }
        } else {
            el.className = el.className.replace(new RegExp('(^|\\b)' + className.trim().split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        }
    },

    addClass: function(el, className) {
        if (!el) {
            return;
        }

        if (el.classList) {
            var classValues = className.split(' ');

            for (var i = 0; i < classValues.length; i++) {
                el.classList.add(classValues[i]);
            }
        } else {
            el.className += ' ' + className;
        }
    },

    hasClass: function(el, className) {
        if (!el) {
            return;
        }

        if (el.classList) {
            el.classList.contains(className);
        }
        else {
            new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
        }
    },

    globalEval: function(code) {
        var script = document.createElement("script");

        script.text = code;
        document.head.appendChild(script).parentNode.removeChild(script);
    }
};

function adjustItemSize(container, responsive_params, namespace) {

    var $container = typeof container == "string" ? document.querySelector(container) : container;

    if (!$container) {
        return;
    }

    var $el = $container.querySelector(".tb_grid_view");

    if (!$el) {
        return;
    }

    if (responsive_params === undefined) {
        responsive_params = {
            "1900": {"items_per_row": 8, "items_spacing": 30},
            "1600": {"items_per_row": 7, "items_spacing": 30},
            "1400": {"items_per_row": 6, "items_spacing": 30},
            "1200": {"items_per_row": 5, "items_spacing": 30},
            "1000": {"items_per_row": 4, "items_spacing": 30},
            "800" : {"items_per_row": 3, "items_spacing": 30},
            "600" : {"items_per_row": 2, "items_spacing": 30},
            "400" : {"items_per_row": 1, "items_spacing": 20}
        };
    }

    var responsive_keys = [],
        current_per_row = 0;

    for(var k in responsive_params) {
        responsive_keys.push(Number(k));
    }
    responsive_keys.sort(function(a, b){return a-b});

    function getRestrictions(c_width) {
        var result = {};

        for(var i = 0; i < responsive_keys.length; i++){
            result = responsive_params[responsive_keys[i]];
            if(c_width <= responsive_keys[i]) {
                break;
            }
        }

        return result;
    }

    var total_items = $el.childElementCount;

    function responsive() {

        var computed_style  = getComputedStyle($container),
            container_width = $container.querySelector('.tb_side_nav') ? $el.clientWidth : $container.clientWidth - (parseInt(computed_style.paddingRight) + parseInt(computed_style.paddingLeft)),
            restrictions    = getRestrictions(container_width);

        if (current_per_row == restrictions.items_per_row) {
            return;
        }

        tbUtils.removeClass($el, 'tb_size_1 tb_size_2 tb_size_3 tb_size_4 tb_size_5 tb_size_6 tb_size_7 tb_size_8 tb_multiline');
        tbUtils.removeClass($el, 'tb_gut_0 tb_gut_10 tb_gut_20 tb_gut_30 tb_gut_40 tb_gut_50');
        tbUtils.addClass($el, 'tb_size_' + restrictions.items_per_row + ' ' + 'tb_gut_'  + restrictions.items_spacing + (restrictions.items_per_row < total_items ? ' tb_multiline' : ''));

        current_per_row = restrictions.items_per_row;
    }

    //requestAnimationFrame(responsive);
    responsive();

    if (typeof container != 'string' || !container) {
        container = '';
    }

    tbUtils.onSizeChange(responsive, namespace, false, "adjustItemSize_" + container);

    // Add layout classes

    if (!$el.hasAttribute('data-nth_classes')) {
        var last_item_indexes = [];

        if (total_items > 1) {
            [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].forEach(function(key) {
                last_item_indexes[key] = (Math.ceil(total_items / key) - 1) * key;
            });
        }

        [].forEach.call($el.children, function(el, i) {
            [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].forEach(function(key) {
                if (((i)/key) % 1 === 0) {
                    tbUtils.addClass(el, 'clear' + key);
                }
            });

            i++;

            last_item_indexes.forEach(function(key, index) {
                if (i > key) {
                    tbUtils.addClass(el, 'tb_size_' + index + '_last');
                }
            });
        });

        $el.setAttribute('data-nth_classes', '1');
    }

}

function element_query(elements, sizes, child) {

    Array.prototype.forEach.call(document.querySelectorAll(elements), function(el) {

        if (sizes === undefined) {
            sizes = el.getAttribute('data-sizes');
        }

        if (!sizes) {
            sizes = [
                1260,
                1040,
                768,
                480,
                0
            ];
        }

        if (typeof sizes == "string") {
            sizes = sizes.split(",").sort(function(a, b) {
                return b - a;
            });
        }

        var width_detect = (function($element, sizes, child) {

            var max_w = sizes[0],
                min_w = sizes[sizes.length - 1];

            return function() {

                var $el = $element;

                if (child !== undefined) {
                    $el = document.querySelector('#' + $element.id + ' ' + child);
                }

                if (!$el) {
                    return;
                }

                var computedStyle = getComputedStyle($el),
                    width = $el.offsetWidth - parseInt(computedStyle.paddingRight) - parseInt(computedStyle.paddingLeft);

                for(var i = 0; i < sizes.length; i++){
                    if (i == 0) {
                        if (width > sizes[i]) {
                            tbUtils.removeClass($el, 'tb_max_w_' + max_w + ' tb_min_w_' + min_w);
                            max_w = 0;
                            min_w = sizes[i];
                            tbUtils.addClass($el, 'tb_min_w_' + min_w);

                            break;
                        }
                    } else {
                        if (width > sizes[i] && width <= sizes[i - 1]) {
                            tbUtils.removeClass($el, 'tb_max_w_' + max_w + ' tb_min_w_' + min_w);
                            max_w = sizes[i - 1];
                            min_w = sizes[i];
                            tbUtils.addClass($el, 'tb_max_w_' + max_w + ' tb_min_w_' + min_w);

                            break;
                        }
                    }
                }
            }

        })(el, sizes, child);

        var el_id = 'element_query_' + String(Math.random());
        if (el.id !== undefined) {
            if (el.id) {
                el_id = '#' + el.id;
            } else {
                el_id = el.nodeName + '_' + el.className.replace(" ", "_") + "_" + String(Math.random());
            }
        }

        width_detect();
        tbUtils.onSizeChange(width_detect, false, false, el_id);
    });
}

/*-global-vars-*/

tbUtils.is_touch = 'ontouchstart' in window || navigator.MaxTouchPoints || navigator.msMaxTouchPoints;

/*-critical-inline-scripts*/

window.tbCriticalLoaded = true;
if (window.tbBoot !== undefined) {
    window.tbBoot();
}