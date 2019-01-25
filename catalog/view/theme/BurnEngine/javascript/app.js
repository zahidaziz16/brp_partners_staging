/* EventEmitter v4.2.11 - git.io/ee | Unlicense - http://unlicense.org/ */
(function(){"use strict";function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var r=e.prototype,i=this,s=i.EventEmitter;r.getListeners=function(e){var t,n,r=this._getEvents();if(e instanceof RegExp){t={};for(n in r)r.hasOwnProperty(n)&&e.test(n)&&(t[n]=r[n])}else t=r[e]||(r[e]=[]);return t},r.flattenListeners=function(e){var t,n=[];for(t=0;t<e.length;t+=1)n.push(e[t].listener);return n},r.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},r.addListener=function(e,n){var r,i=this.getListenersAsObject(e),s="object"==typeof n;for(r in i)i.hasOwnProperty(r)&&-1===t(i[r],n)&&i[r].unshift(s?n:{listener:n,once:!1});return this},r.on=n("addListener"),r.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},r.once=n("addOnceListener"),r.defineEvent=function(e){return this.getListeners(e),this},r.defineEvents=function(e){for(var t=0;t<e.length;t+=1)this.defineEvent(e[t]);return this},r.removeListener=function(e,n){var r,i,s=this.getListenersAsObject(e);for(i in s)s.hasOwnProperty(i)&&(r=t(s[i],n),-1!==r&&s[i].splice(r,1));return this},r.off=n("removeListener"),r.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},r.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},r.manipulateListeners=function(e,t,n){var r,i,s=e?this.removeListener:this.addListener,o=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(r=n.length;r--;)s.call(this,t,n[r]);else for(r in t)t.hasOwnProperty(r)&&(i=t[r])&&("function"==typeof i?s.call(this,r,i):o.call(this,r,i));return this},r.removeEvent=function(e){var t,n=typeof e,r=this._getEvents();if("string"===n)delete r[e];else if(e instanceof RegExp)for(t in r)r.hasOwnProperty(t)&&e.test(t)&&delete r[t];else delete this._events;return this},r.removeAllListeners=n("removeEvent"),r.emitEvent=function(e,t){var n,r,i,s,o=this.getListenersAsObject(e);for(i in o)if(o.hasOwnProperty(i))for(r=o[i].length;r--;)n=o[i][r],n.once===!0&&this.removeListener(e,n.listener),s=n.listener.apply(this,t||[]),s===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},r.trigger=n("emitEvent"),r.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},r.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},r._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},r._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return i.EventEmitter=s,e},"function"==typeof define&&define.amd?define(function(){return e}):"object"==typeof module&&module.exports?module.exports=e:i.EventEmitter=e}).call(this);
/* utf8_decode - http://locutus.io/php/utf8_decode/ | Copyright (c) 2007-2016 Kevin van Zonneveld (http://kvz.io) and Contributors (http://locutus.io/authors) */
function utf8_decode(r){var o=[],e=0,h=0,t=0;for(r+="";e<r.length;){h=255&r.charCodeAt(e),t=0,h>191?h>223?h>239?(h=7&h,t=4):(h=15&h,t=3):(h=31&h,t=2):(h=127&h,t=1);for(var n=1;t>n;++n)h=h<<6|63&r.charCodeAt(n+e);4===t?(h-=65536,o.push(String.fromCharCode(55296|h>>10&1023)),o.push(String.fromCharCode(56320|1023&h))):o.push(String.fromCharCode(h)),e+=t}return o.join("")};
/* BurnEngine | (c) 2005, 2015 ThemeBurn */
(function(window) {
    var config;
    if (window.tbApp) {
        config = window.tbApp;
    }

    window.tbApp = {
        trace: Math.random(),
        sciprtLoadFunctions: [],
        windowLoadFunctions: [],
        windowLoaded: !1,
        scriptloaded: !1,
        onWindowLoaded: function(o) {
            "function" == typeof o && (this.windowLoadFunctions.push(o), this.windowLoaded ? o() : this.on("tbWindowLoaded", o))
        },
        onScriptLoaded: function(o, d) {
            "function" == typeof o && (this.sciprtLoadFunctions.push(o), this.scriptloaded ? o() : this.on(d === !0 ? "tbScriptLoadedInit" : "tbScriptLoaded", o))
        },
        events: new EventEmitter(),
        on: function(evt, listener) {
            return this.events.addListener(evt, listener);
        },
        once: function(evt, listener) {
            return this.events.addOnceListener(evt, listener);
        },
        off: function(evt, listener) {
            return this.events.removeListener(evt, listener);
        },
        trigger: function(evt, args) {
            return this.events.emitEvent (evt, args);
        },
        triggerWindowResize: function() {
            var event,
                eventName = window.hasOwnProperty("onorientationchange") ? "orientationchange" : "resize";

            if (document.createEvent) {
                event = document.createEvent("HTMLEvents");
                event.initEvent(eventName, true, true);
                event.eventName = eventName;
                window.dispatchEvent(event);
            } else {
                event = document.createEventObject();
                event.eventType = eventName;
                event.eventName = eventName;
                window.fireEvent("on" + event.eventType, event);
            }
        },
        triggerResizeCallbacks: function(namespace) {
            var callbacks = [];

            if (typeof namespace != "undefined") {
                if (tbUtils.resizeCallBacks[namespace] !== undefined){
                    callbacks = tbUtils.resizeCallBacks[namespace];
                }
            } else {
                for (var key in tbUtils.resizeCallBacks) {
                    callbacks.unshift.apply(callbacks, tbUtils.resizeCallBacks[key]);
                }
            }

            Array.prototype.forEach.call(callbacks, function(callBack) {
                callBack();
            });
        },
        _t: function(str) {
            var result = this['/lang/' + String(str)];

            if (undefined === result) {
                return '** not translated **';
            }

            return utf8_decode(result);
        }
    };

    tbApp.on("tbWindowLoaded", function() {
        tbApp.windowLoaded = true;
    });

    tbApp.on("tbScriptLoaded", function() {
        tbApp.scriptloaded = true;
        if (tbApp.executeInline !== undefined) {
            tbApp.executeInline.call(window, tbApp);
        }
    });

    if (config !== undefined) {
        for(var k in config) {
            tbApp[k] = config[k];
        }
    }
})(window);

