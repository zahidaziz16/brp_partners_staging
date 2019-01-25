/*! JSON v3.3.2 | http://bestiejs.github.io/json3 | Copyright 2012-2014, Kit Cambridge | http://kit.mit-license.org */
;(function(){var b=typeof define==="function"&&define.amd;var d={"function":true,object:true};var g=d[typeof exports]&&exports&&!exports.nodeType&&exports;var h=d[typeof window]&&window||this,a=g&&d[typeof module]&&module&&!module.nodeType&&typeof global=="object"&&global;if(a&&(a.global===a||a.window===a||a.self===a)){h=a;}function i(aa,U){aa||(aa=h.Object());U||(U=h.Object());var J=aa.Number||h.Number,Q=aa.String||h.String,w=aa.Object||h.Object,R=aa.Date||h.Date,S=aa.SyntaxError||h.SyntaxError,Z=aa.TypeError||h.TypeError,I=aa.Math||h.Math,X=aa.JSON||h.JSON;if(typeof X=="object"&&X){U.stringify=X.stringify;U.parse=X.parse;}var m=w.prototype,t=m.toString,q,l,K;var A=new R(-3509827334573292);try{A=A.getUTCFullYear()==-109252&&A.getUTCMonth()===0&&A.getUTCDate()===1&&A.getUTCHours()==10&&A.getUTCMinutes()==37&&A.getUTCSeconds()==6&&A.getUTCMilliseconds()==708;}catch(u){}function n(ab){if(n[ab]!==K){return n[ab];}var ac;if(ab=="bug-string-char-index"){ac="a"[0]!="a";}else{if(ab=="json"){ac=n("json-stringify")&&n("json-parse");}else{var aj,ag='{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}';if(ab=="json-stringify"){var ah=U.stringify,ai=typeof ah=="function"&&A;if(ai){(aj=function(){return 1;}).toJSON=aj;try{ai=ah(0)==="0"&&ah(new J())==="0"&&ah(new Q())=='""'&&ah(t)===K&&ah(K)===K&&ah()===K&&ah(aj)==="1"&&ah([aj])=="[1]"&&ah([K])=="[null]"&&ah(null)=="null"&&ah([K,t,null])=="[null,null,null]"&&ah({a:[aj,true,false,null,"\x00\b\n\f\r\t"]})==ag&&ah(null,aj)==="1"&&ah([1,2],null,1)=="[\n 1,\n 2\n]"&&ah(new R(-8640000000000000))=='"-271821-04-20T00:00:00.000Z"'&&ah(new R(8640000000000000))=='"+275760-09-13T00:00:00.000Z"'&&ah(new R(-62198755200000))=='"-000001-01-01T00:00:00.000Z"'&&ah(new R(-1))=='"1969-12-31T23:59:59.999Z"';}catch(ad){ai=false;}}ac=ai;}if(ab=="json-parse"){var af=U.parse;if(typeof af=="function"){try{if(af("0")===0&&!af(false)){aj=af(ag);var ae=aj.a.length==5&&aj.a[0]===1;if(ae){try{ae=!af('"\t"');}catch(ad){}if(ae){try{ae=af("01")!==1;}catch(ad){}}if(ae){try{ae=af("1.")!==1;}catch(ad){}}}}}catch(ad){ae=false;}}ac=ae;}}}return n[ab]=!!ac;}if(!n("json")){var T="[object Function]",P="[object Date]",M="[object Number]",N="[object String]",D="[object Array]",z="[object Boolean]";var E=n("bug-string-char-index");if(!A){var r=I.floor;var Y=[0,31,59,90,120,151,181,212,243,273,304,334];var C=function(ab,ac){return Y[ac]+365*(ab-1970)+r((ab-1969+(ac=+(ac>1)))/4)-r((ab-1901+ac)/100)+r((ab-1601+ac)/400);};}if(!(q=m.hasOwnProperty)){q=function(ad){var ab={},ac;if((ab.__proto__=null,ab.__proto__={toString:1},ab).toString!=t){q=function(ag){var af=this.__proto__,ae=ag in (this.__proto__=null,this);this.__proto__=af;return ae;};}else{ac=ab.constructor;q=function(af){var ae=(this.constructor||ac).prototype;return af in this&&!(af in ae&&this[af]===ae[af]);};}ab=null;return q.call(this,ad);};}l=function(ad,ag){var ae=0,ab,ac,af;(ab=function(){this.valueOf=0;}).prototype.valueOf=0;ac=new ab();for(af in ac){if(q.call(ac,af)){ae++;}}ab=ac=null;if(!ae){ac=["valueOf","toString","toLocaleString","propertyIsEnumerable","isPrototypeOf","hasOwnProperty","constructor"];l=function(ai,am){var al=t.call(ai)==T,ak,aj;var ah=!al&&typeof ai.constructor!="function"&&d[typeof ai.hasOwnProperty]&&ai.hasOwnProperty||q;for(ak in ai){if(!(al&&ak=="prototype")&&ah.call(ai,ak)){am(ak);}}for(aj=ac.length;ak=ac[--aj];ah.call(ai,ak)&&am(ak)){}};}else{if(ae==2){l=function(ai,al){var ah={},ak=t.call(ai)==T,aj;for(aj in ai){if(!(ak&&aj=="prototype")&&!q.call(ah,aj)&&(ah[aj]=1)&&q.call(ai,aj)){al(aj);}}};}else{l=function(ai,al){var ak=t.call(ai)==T,aj,ah;for(aj in ai){if(!(ak&&aj=="prototype")&&q.call(ai,aj)&&!(ah=aj==="constructor")){al(aj);}}if(ah||q.call(ai,(aj="constructor"))){al(aj);}};}}return l(ad,ag);};if(!n("json-stringify")){var p={92:"\\\\",34:'\\"',8:"\\b",12:"\\f",10:"\\n",13:"\\r",9:"\\t"};var H="000000";var s=function(ab,ac){return(H+(ac||0)).slice(-ab);};var y="\\u00";var B=function(ah){var ac='"',af=0,ag=ah.length,ab=!E||ag>10;var ae=ab&&(E?ah.split(""):ah);for(;af<ag;af++){var ad=ah.charCodeAt(af);switch(ad){case 8:case 9:case 10:case 12:case 13:case 34:case 92:ac+=p[ad];break;default:if(ad<32){ac+=y+s(2,ad.toString(16));break;}ac+=ab?ae[af]:ah.charAt(af);}}return ac+'"';};var o=function(ah,az,af,ak,aw,ab,ai){var ar,ad,ao,ay,ax,aj,av,at,ap,am,aq,ac,ag,ae,au,an;try{ar=az[ah];}catch(al){}if(typeof ar=="object"&&ar){ad=t.call(ar);if(ad==P&&!q.call(ar,"toJSON")){if(ar>-1/0&&ar<1/0){if(C){ax=r(ar/86400000);for(ao=r(ax/365.2425)+1970-1;C(ao+1,0)<=ax;ao++){}for(ay=r((ax-C(ao,0))/30.42);C(ao,ay+1)<=ax;ay++){}ax=1+ax-C(ao,ay);aj=(ar%86400000+86400000)%86400000;av=r(aj/3600000)%24;at=r(aj/60000)%60;ap=r(aj/1000)%60;am=aj%1000;}else{ao=ar.getUTCFullYear();ay=ar.getUTCMonth();ax=ar.getUTCDate();av=ar.getUTCHours();at=ar.getUTCMinutes();ap=ar.getUTCSeconds();am=ar.getUTCMilliseconds();}ar=(ao<=0||ao>=10000?(ao<0?"-":"+")+s(6,ao<0?-ao:ao):s(4,ao))+"-"+s(2,ay+1)+"-"+s(2,ax)+"T"+s(2,av)+":"+s(2,at)+":"+s(2,ap)+"."+s(3,am)+"Z";}else{ar=null;}}else{if(typeof ar.toJSON=="function"&&((ad!=M&&ad!=N&&ad!=D)||q.call(ar,"toJSON"))){ar=ar.toJSON(ah);}}}if(af){ar=af.call(az,ah,ar);}if(ar===null){return"null";}ad=t.call(ar);if(ad==z){return""+ar;}else{if(ad==M){return ar>-1/0&&ar<1/0?""+ar:"null";}else{if(ad==N){return B(""+ar);}}}if(typeof ar=="object"){for(ae=ai.length;ae--;){if(ai[ae]===ar){throw Z();}}ai.push(ar);aq=[];au=ab;ab+=aw;if(ad==D){for(ag=0,ae=ar.length;ag<ae;ag++){ac=o(ag,ar,af,ak,aw,ab,ai);aq.push(ac===K?"null":ac);}an=aq.length?(aw?"[\n"+ab+aq.join(",\n"+ab)+"\n"+au+"]":("["+aq.join(",")+"]")):"[]";}else{l(ak||ar,function(aB){var aA=o(aB,ar,af,ak,aw,ab,ai);if(aA!==K){aq.push(B(aB)+":"+(aw?" ":"")+aA);}});an=aq.length?(aw?"{\n"+ab+aq.join(",\n"+ab)+"\n"+au+"}":("{"+aq.join(",")+"}")):"{}";}ai.pop();return an;}};U.stringify=function(ab,ad,ae){var ac,ak,ai,ah;if(d[typeof ad]&&ad){if((ah=t.call(ad))==T){ak=ad;}else{if(ah==D){ai={};for(var ag=0,af=ad.length,aj;ag<af;aj=ad[ag++],((ah=t.call(aj)),ah==N||ah==M)&&(ai[aj]=1)){}}}}if(ae){if((ah=t.call(ae))==M){if((ae-=ae%1)>0){for(ac="",ae>10&&(ae=10);ac.length<ae;ac+=" "){}}}else{if(ah==N){ac=ae.length<=10?ae:ae.slice(0,10);}}}return o("",(aj={},aj[""]=ab,aj),ak,ai,ac,"",[]);};}if(!n("json-parse")){var L=Q.fromCharCode;var k={92:"\\",34:'"',47:"/",98:"\b",116:"\t",110:"\n",102:"\f",114:"\r"};var F,W;var G=function(){F=W=null;throw S();};var x=function(){var ag=W,ae=ag.length,af,ad,ab,ah,ac;while(F<ae){ac=ag.charCodeAt(F);switch(ac){case 9:case 10:case 13:case 32:F++;break;case 123:case 125:case 91:case 93:case 58:case 44:af=E?ag.charAt(F):ag[F];F++;return af;case 34:for(af="@",F++;F<ae;){ac=ag.charCodeAt(F);if(ac<32){G();}else{if(ac==92){ac=ag.charCodeAt(++F);switch(ac){case 92:case 34:case 47:case 98:case 116:case 110:case 102:case 114:af+=k[ac];F++;break;case 117:ad=++F;for(ab=F+4;F<ab;F++){ac=ag.charCodeAt(F);if(!(ac>=48&&ac<=57||ac>=97&&ac<=102||ac>=65&&ac<=70)){G();}}af+=L("0x"+ag.slice(ad,F));break;default:G();}}else{if(ac==34){break;}ac=ag.charCodeAt(F);ad=F;while(ac>=32&&ac!=92&&ac!=34){ac=ag.charCodeAt(++F);}af+=ag.slice(ad,F);}}}if(ag.charCodeAt(F)==34){F++;return af;}G();default:ad=F;if(ac==45){ah=true;ac=ag.charCodeAt(++F);}if(ac>=48&&ac<=57){if(ac==48&&((ac=ag.charCodeAt(F+1)),ac>=48&&ac<=57)){G();}ah=false;for(;F<ae&&((ac=ag.charCodeAt(F)),ac>=48&&ac<=57);F++){}if(ag.charCodeAt(F)==46){ab=++F;for(;ab<ae&&((ac=ag.charCodeAt(ab)),ac>=48&&ac<=57);ab++){}if(ab==F){G();}F=ab;}ac=ag.charCodeAt(F);if(ac==101||ac==69){ac=ag.charCodeAt(++F);if(ac==43||ac==45){F++;}for(ab=F;ab<ae&&((ac=ag.charCodeAt(ab)),ac>=48&&ac<=57);ab++){}if(ab==F){G();}F=ab;}return +ag.slice(ad,F);}if(ah){G();}if(ag.slice(F,F+4)=="true"){F+=4;return true;}else{if(ag.slice(F,F+5)=="false"){F+=5;return false;}else{if(ag.slice(F,F+4)=="null"){F+=4;return null;}}}G();}}return"$";};var V=function(ac){var ab,ad;if(ac=="$"){G();}if(typeof ac=="string"){if((E?ac.charAt(0):ac[0])=="@"){return ac.slice(1);}if(ac=="["){ab=[];for(;;ad||(ad=true)){ac=x();if(ac=="]"){break;}if(ad){if(ac==","){ac=x();if(ac=="]"){G();}}else{G();}}if(ac==","){G();}ab.push(V(ac));}return ab;}else{if(ac=="{"){ab={};for(;;ad||(ad=true)){ac=x();if(ac=="}"){break;}if(ad){if(ac==","){ac=x();if(ac=="}"){G();}}else{G();}}if(ac==","||typeof ac!="string"||(E?ac.charAt(0):ac[0])!="@"||x()!=":"){G();}ab[ac.slice(1)]=V(x());}return ab;}}G();}return ac;};var O=function(ad,ac,ae){var ab=v(ad,ac,ae);if(ab===K){delete ad[ac];}else{ad[ac]=ab;}};var v=function(ae,ad,af){var ac=ae[ad],ab;if(typeof ac=="object"&&ac){if(t.call(ac)==D){for(ab=ac.length;ab--;){O(ac,ab,af);}}else{l(ac,function(ag){O(ac,ag,af);});}}return af.call(ae,ad,ac);};U.parse=function(ad,ae){var ab,ac;F=0;W=""+ad;ab=V(x());if(x()!="$"){G();}F=W=null;return ae&&t.call(ae)==T?v((ac={},ac[""]=ab,ac),"",ae):ab;};}}U.runInContext=i;return U;}if(g&&!b){i(h,g);}else{var e=h.JSON,j=h.JSON3,c=false;var f=i(h,(h.JSON3={noConflict:function(){if(!c){c=true;h.JSON=e;h.JSON3=j;e=j=null;}return f;}}));h.JSON={parse:f.parse,stringify:f.stringify};}if(b){define(function(){return f;});}}).call(this);

/* jQuery Mouse Wheel 3.1.11
 * Copyright (c) 2013 Brandon Aaron (http://brandon.aaron.sh)
 * Licensed under the MIT License (LICENSE.txt).
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.11",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b)["offsetParent"in a.fn?"offsetParent":"parent"]();return c.length||(c=a("body")),parseInt(c.css("fontSize"),10)},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});


/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 * Released under the MIT license - http://www.opensource.org/licenses/mit-license.php
 */
(function(a){if(typeof define==="function"&&define.amd){define(["jquery"],a)}else{a(jQuery)}}(function(f){var a=/\+/g;function d(i){return b.raw?i:encodeURIComponent(i)}function g(i){return b.raw?i:decodeURIComponent(i)}function h(i){return d(b.json?JSON.stringify(i):String(i))}function c(i){if(i.indexOf('"')===0){i=i.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\")}try{i=decodeURIComponent(i.replace(a," "));return b.json?JSON.parse(i):i}catch(j){}}function e(j,i){var k=b.raw?j:c(j);return f.isFunction(i)?i(k):k}var b=f.cookie=function(q,p,v){if(p!==undefined&&!f.isFunction(p)){v=f.extend({},b.defaults,v);if(typeof v.expires==="number"){var r=v.expires,u=v.expires=new Date();u.setTime(+u+r*86400000)}return(document.cookie=[d(q),"=",h(p),v.expires?"; expires="+v.expires.toUTCString():"",v.path?"; path="+v.path:"",v.domain?"; domain="+v.domain:"",v.secure?"; secure":""].join(""))}var w=q?undefined:{};var s=document.cookie?document.cookie.split("; "):[];for(var o=0,m=s.length;o<m;o++){var n=s[o].split("=");var j=g(n.shift());var k=n.join("=");if(q&&q===j){w=e(k,p);break}if(!q&&(k=e(k))!==undefined){w[j]=k}}return w};b.defaults={};f.removeCookie=function(j,i){if(f.cookie(j)===undefined){return false}f.cookie(j,"",f.extend({},i,{expires:-1}));return !f.cookie(j)}}));

/*!
 * jQuery Form Plugin
 * version: 3.51.0-2014.06.20
 * Copyright (c) 2014 M. Alsup
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):e("undefined"!=typeof jQuery?jQuery:window.Zepto)}(function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(t.target).ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=this;if(i.clk=r,"image"==r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n={};n.fileapi=void 0!==e("<input type='file'/>").get(0).files,n.formdata=void 0!==window.FormData;var i=!!e.fn.prop;e.fn.attr2=function(){if(!i)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t){function r(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;o>a;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function o(a){for(var n=new FormData,i=0;i<a.length;i++)n.append(a[i].name,a[i].value);if(t.extraData){var o=r(t.extraData);for(i=0;i<o.length;i++)o[i]&&n.append(o[i][0],o[i][1])}t.data=null;var s=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:u||"POST"});t.uploadProgress&&(s.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(a/n*100)),t.uploadProgress(e,a,n,r)},!1),r}),s.data=null;var c=s.beforeSend;return s.beforeSend=function(e,r){r.data=t.formData?t.formData:n,c&&c.call(this,e,r)},e.ajax(s)}function s(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(r){a("cannot get iframe.contentWindow document: "+r)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function o(){function t(){try{var e=n(g).readyState;a("state = "+e),e&&"uninitialized"==e.toLowerCase()&&setTimeout(t,50)}catch(r){a("Server abort: ",r," (",r.name,")"),s(k),j&&clearTimeout(j),j=void 0}}var r=f.attr2("target"),i=f.attr2("action"),o="multipart/form-data",c=f.attr("enctype")||f.attr("encoding")||o;w.setAttribute("target",p),(!u||/post/i.test(u))&&w.setAttribute("method","POST"),i!=m.url&&w.setAttribute("action",m.url),m.skipEncodingOverride||u&&!/post/i.test(u)||f.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),m.timeout&&(j=setTimeout(function(){T=!0,s(D)},m.timeout));var l=[];try{if(m.extraData)for(var d in m.extraData)m.extraData.hasOwnProperty(d)&&l.push(e.isPlainObject(m.extraData[d])&&m.extraData[d].hasOwnProperty("name")&&m.extraData[d].hasOwnProperty("value")?e('<input type="hidden" name="'+m.extraData[d].name+'">').val(m.extraData[d].value).appendTo(w)[0]:e('<input type="hidden" name="'+d+'">').val(m.extraData[d]).appendTo(w)[0]);m.iframeTarget||v.appendTo("body"),g.attachEvent?g.attachEvent("onload",s):g.addEventListener("load",s,!1),setTimeout(t,15);try{w.submit()}catch(h){var x=document.createElement("form").submit;x.apply(w)}}finally{w.setAttribute("action",i),w.setAttribute("enctype",c),r?w.setAttribute("target",r):f.removeAttr("target"),e(l).remove()}}function s(t){if(!x.aborted&&!F){if(M=n(g),M||(a("cannot access response document"),t=k),t===D&&x)return x.abort("timeout"),void S.reject(x,"timeout");if(t==k&&x)return x.abort("server abort"),void S.reject(x,"error","server abort");if(M&&M.location.href!=m.iframeSrc||T){g.detachEvent?g.detachEvent("onload",s):g.removeEventListener("load",s,!1);var r,i="success";try{if(T)throw"timeout";var o="xml"==m.dataType||M.XMLDocument||e.isXMLDoc(M);if(a("isXml="+o),!o&&window.opera&&(null===M.body||!M.body.innerHTML)&&--O)return a("requeing onLoad callback, DOM not available"),void setTimeout(s,250);var u=M.body?M.body:M.documentElement;x.responseText=u?u.innerHTML:null,x.responseXML=M.XMLDocument?M.XMLDocument:M,o&&(m.dataType="xml"),x.getResponseHeader=function(e){var t={"content-type":m.dataType};return t[e.toLowerCase()]},u&&(x.status=Number(u.getAttribute("status"))||x.status,x.statusText=u.getAttribute("statusText")||x.statusText);var c=(m.dataType||"").toLowerCase(),l=/(json|script|text)/.test(c);if(l||m.textarea){var f=M.getElementsByTagName("textarea")[0];if(f)x.responseText=f.value,x.status=Number(f.getAttribute("status"))||x.status,x.statusText=f.getAttribute("statusText")||x.statusText;else if(l){var p=M.getElementsByTagName("pre")[0],h=M.getElementsByTagName("body")[0];p?x.responseText=p.textContent?p.textContent:p.innerText:h&&(x.responseText=h.textContent?h.textContent:h.innerText)}}else"xml"==c&&!x.responseXML&&x.responseText&&(x.responseXML=X(x.responseText));try{E=_(x,c,m)}catch(y){i="parsererror",x.error=r=y||i}}catch(y){a("error caught: ",y),i="error",x.error=r=y||i}x.aborted&&(a("upload aborted"),i=null),x.status&&(i=x.status>=200&&x.status<300||304===x.status?"success":"error"),"success"===i?(m.success&&m.success.call(m.context,E,"success",x),S.resolve(x.responseText,"success",x),d&&e.event.trigger("ajaxSuccess",[x,m])):i&&(void 0===r&&(r=x.statusText),m.error&&m.error.call(m.context,x,i,r),S.reject(x,"error",r),d&&e.event.trigger("ajaxError",[x,m,r])),d&&e.event.trigger("ajaxComplete",[x,m]),d&&!--e.active&&e.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,x,i),F=!0,m.timeout&&clearTimeout(j),setTimeout(function(){m.iframeTarget?v.attr("src",m.iframeSrc):v.remove(),x.responseXML=null},100)}}}var c,l,m,d,p,v,g,x,y,b,T,j,w=f[0],S=e.Deferred();if(S.abort=function(e){x.abort(e)},r)for(l=0;l<h.length;l++)c=e(h[l]),i?c.prop("disabled",!1):c.removeAttr("disabled");if(m=e.extend(!0,{},e.ajaxSettings,t),m.context=m.context||m,p="jqFormIO"+(new Date).getTime(),m.iframeTarget?(v=e(m.iframeTarget),b=v.attr2("name"),b?p=b:v.attr2("name",p)):(v=e('<iframe name="'+p+'" src="'+m.iframeSrc+'" />'),v.css({position:"absolute",top:"-1000px",left:"-1000px"})),g=v[0],x={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{g.contentWindow.document.execCommand&&g.contentWindow.document.execCommand("Stop")}catch(n){}v.attr("src",m.iframeSrc),x.error=r,m.error&&m.error.call(m.context,x,r,t),d&&e.event.trigger("ajaxError",[x,m,r]),m.complete&&m.complete.call(m.context,x,r)}},d=m.global,d&&0===e.active++&&e.event.trigger("ajaxStart"),d&&e.event.trigger("ajaxSend",[x,m]),m.beforeSend&&m.beforeSend.call(m.context,x,m)===!1)return m.global&&e.active--,S.reject(),S;if(x.aborted)return S.reject(),S;y=w.clk,y&&(b=y.name,b&&!y.disabled&&(m.extraData=m.extraData||{},m.extraData[b]=y.value,"image"==y.type&&(m.extraData[b+".x"]=w.clk_x,m.extraData[b+".y"]=w.clk_y)));var D=1,k=2,A=e("meta[name=csrf-token]").attr("content"),L=e("meta[name=csrf-param]").attr("content");L&&A&&(m.extraData=m.extraData||{},m.extraData[L]=A),m.forceSync?o():setTimeout(o,10);var E,M,F,O=50,X=e.parseXML||function(e,t){return window.ActiveXObject?(t=new ActiveXObject("Microsoft.XMLDOM"),t.async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!=t.documentElement.nodeName?t:null},C=e.parseJSON||function(e){return window.eval("("+e+")")},_=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i="xml"===r||!r&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&("json"===r||!r&&n.indexOf("json")>=0?o=C(o):("script"===r||!r&&n.indexOf("javascript")>=0)&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var u,c,l,f=this;"function"==typeof t?t={success:t}:void 0===t&&(t={}),u=t.type||this.attr2("method"),c=t.url||this.attr2("action"),l="string"==typeof c?e.trim(c):"",l=l||window.location.href||"",l&&(l=(l.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:l,success:e.ajaxSettings.success,type:u||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var m={};if(this.trigger("form-pre-serialize",[this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&t.beforeSerialize(this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var d=t.traditional;void 0===d&&(d=e.ajaxSettings.traditional);var p,h=[],v=this.formToArray(t.semantic,h);if(t.data&&(t.extraData=t.data,p=e.param(t.data,d)),t.beforeSubmit&&t.beforeSubmit(v,this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[v,this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var g=e.param(v,d);p&&(g=g?g+"&"+p:p),"GET"==t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+g,t.data=null):t.data=g;var x=[];if(t.resetForm&&x.push(function(){f.resetForm()}),t.clearForm&&x.push(function(){f.clearForm(t.includeHidden)}),!t.dataType&&t.target){var y=t.success||function(){};x.push(function(r){var a=t.replaceTarget?"replaceWith":"html";e(t.target)[a](r).each(y,arguments)})}else t.success&&x.push(t.success);if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=x.length;o>i;i++)x[i].apply(n,[e,r,a||f,f])},t.error){var b=t.error;t.error=function(e,r,a){var n=t.context||this;b.apply(n,[e,r,a,f])}}if(t.complete){var T=t.complete;t.complete=function(e,r){var a=t.context||this;T.apply(a,[e,r,f])}}var j=e("input[type=file]:enabled",this).filter(function(){return""!==e(this).val()}),w=j.length>0,S="multipart/form-data",D=f.attr("enctype")==S||f.attr("encoding")==S,k=n.fileapi&&n.formdata;a("fileAPI :"+k);var A,L=(w||D)&&!k;t.iframe!==!1&&(t.iframe||L)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){A=s(v)}):A=s(v):A=(w||D)&&k?o(v):e.ajax(t),f.removeData("jqxhr").data("jqxhr",A);for(var E=0;E<h.length;E++)h[E]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n){if(n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var i={s:this.selector,c:this.context};return!e.isReady&&i.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(i.s,i.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().bind("submit.form-plugin",n,t).bind("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r){var a=[];if(0===this.length)return a;var i,o=this[0],s=this.attr("id"),u=t?o.getElementsByTagName("*"):o.elements;if(u&&!/MSIE [678]/.test(navigator.userAgent)&&(u=e(u).get()),s&&(i=e(':input[form="'+s+'"]').get(),i.length&&(u=(u||[]).concat(i))),!u||!u.length)return a;var c,l,f,m,d,p,h;for(c=0,p=u.length;p>c;c++)if(d=u[c],f=d.name,f&&!d.disabled)if(t&&o.clk&&"image"==d.type)o.clk==d&&(a.push({name:f,value:e(d).val(),type:d.type}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}));else if(m=e.fieldValue(d,!0),m&&m.constructor==Array)for(r&&r.push(d),l=0,h=m.length;h>l;l++)a.push({name:f,value:m[l]});else if(n.fileapi&&"file"==d.type){r&&r.push(d);var v=d.files;if(v.length)for(l=0;l<v.length;l++)a.push({name:f,value:v[l],type:d.type});else a.push({name:f,value:"",type:d.type})}else null!==m&&"undefined"!=typeof m&&(r&&r.push(d),a.push({name:f,value:m,type:d.type,required:d.required}));if(!t&&o.clk){var g=e(o.clk),x=g[0];f=x.name,f&&!x.disabled&&"image"==x.type&&(a.push({name:f,value:g.val()}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}))}return a},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor==Array)for(var i=0,o=n.length;o>i;i++)r.push({name:a,value:n[i]});else null!==n&&"undefined"!=typeof n&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;n>a;a++){var i=this[a],o=e.fieldValue(i,t);null===o||"undefined"==typeof o||o.constructor==Array&&!o.length||(o.constructor==Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,n=t.type,i=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"==n||"button"==n||("checkbox"==n||"radio"==n)&&!t.checked||("submit"==n||"image"==n)&&t.form&&t.form.clk!=t||"select"==i&&-1==t.selectedIndex))return null;if("select"==i){var o=t.selectedIndex;if(0>o)return null;for(var s=[],u=t.options,c="select-one"==n,l=c?o+1:u.length,f=c?o:0;l>f;f++){var m=u[f];if(m.selected){var d=m.value;if(d||(d=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),c)return d;s.push(d)}}return s}return e(t).val()},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"==n?this.value="":"checkbox"==a||"radio"==a?this.checked=!1:"select"==n?this.selectedIndex=-1:"file"==a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(t===!0&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"==r||"radio"==r)this.checked=t;else if("option"==this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"==a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1});

/*
 * jQuery Numeric 1.4.0 - https://github.com/SamWM/jQuery-Plugins/tree/master/numeric/
 * Copyright (c) 2006-2011 Sam Collett (http://www.texotela.co.uk)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 */
(function(a){a.fn.numeric=function(d,e){if(typeof d==="boolean"){d={decimal:d};}d=d||{};if(typeof d.negative=="undefined"){d.negative=true;}var b=(d.decimal===false)?"":d.decimal||".";var c=(d.negative===true)?true:false;e=(typeof(e)=="function"?e:function(){});return this.data("numeric.decimal",b).data("numeric.negative",c).data("numeric.callback",e).keypress(a.fn.numeric.keypress).keyup(a.fn.numeric.keyup).blur(a.fn.numeric.blur);};a.fn.numeric.keypress=function(h){var b=a.data(this,"numeric.decimal");var c=a.data(this,"numeric.negative");var d=h.charCode?h.charCode:h.keyCode?h.keyCode:0;if(d==13&&this.nodeName.toLowerCase()=="input"){return true;}else{if(d==13){return false;}}var f=false;if((h.ctrlKey&&d==97)||(h.ctrlKey&&d==65)){return true;}if((h.ctrlKey&&d==120)||(h.ctrlKey&&d==88)){return true;}if((h.ctrlKey&&d==99)||(h.ctrlKey&&d==67)){return true;}if((h.ctrlKey&&d==122)||(h.ctrlKey&&d==90)){return true;}if((h.ctrlKey&&d==118)||(h.ctrlKey&&d==86)||(h.shiftKey&&d==45)){return true;}if(d<48||d>57){var g=a(this).val();if(a.inArray("-",g.split(""))!==0&&c&&d==45&&(g.length===0||parseInt(a.fn.getSelectionStart(this),10)===0)){return true;}if(b&&d==b.charCodeAt(0)&&a.inArray(b,g.split(""))!=-1){f=false;}if(d!=8&&d!=9&&d!=13&&d!=35&&d!=36&&d!=37&&d!=39&&d!=46){f=false;}else{if(typeof h.charCode!="undefined"){if(h.keyCode==h.which&&h.which!==0){f=true;if(h.which==46){f=false;}}else{if(h.keyCode!==0&&h.charCode===0&&h.which===0){f=true;}}}}if(b&&d==b.charCodeAt(0)){if(a.inArray(b,g.split(""))==-1){f=true;}else{f=false;}}}else{f=true;}return f;};a.fn.numeric.keyup=function(r){var l=a(this).val();if(l&&l.length>0){var f=a.fn.getSelectionStart(this);var u=a.fn.getSelectionEnd(this);var q=a.data(this,"numeric.decimal");var n=a.data(this,"numeric.negative");if(q!==""&&q!==null){var d=a.inArray(q,l.split(""));if(d===0){this.value="0"+l;f++;u++;}if(d==1&&l.charAt(0)=="-"){this.value="-0"+l.substring(1);f++;u++;}l=this.value;}var c=[0,1,2,3,4,5,6,7,8,9,"-",q];var h=l.length;for(var p=h-1;p>=0;p--){var b=l.charAt(p);if(p!==0&&b=="-"){l=l.substring(0,p)+l.substring(p+1);}else{if(p===0&&!n&&b=="-"){l=l.substring(1);}}var g=false;for(var o=0;o<c.length;o++){if(b==c[o]){g=true;break;}}if(!g||b==" "){l=l.substring(0,p)+l.substring(p+1);}}var s=a.inArray(q,l.split(""));if(s>0){for(var m=h-1;m>s;m--){var t=l.charAt(m);if(t==q){l=l.substring(0,m)+l.substring(m+1);}}}this.value=l;a.fn.setSelection(this,[f,u]);}};a.fn.numeric.blur=function(){var b=a.data(this,"numeric.decimal");var e=a.data(this,"numeric.callback");var d=this.value;if(d!==""){var c=new RegExp("^\\d+$|^\\d*"+b+"\\d+$");if(!c.exec(d)){e.apply(this);}}};a.fn.removeNumeric=function(){return this.data("numeric.decimal",null).data("numeric.negative",null).data("numeric.callback",null).unbind("keypress",a.fn.numeric.keypress).unbind("blur",a.fn.numeric.blur);};a.fn.getSelectionStart=function(d){if(d.type==="number"){return undefined;}else{if(d.createTextRange){var b;if(typeof document.selection=="undefined"){b=document.getSelection();}else{b=document.selection.createRange().duplicate();b.moveEnd("character",d.value.length);}if(b.text==""){return d.value.length;}return d.value.lastIndexOf(b.text);}else{try{return d.selectionStart;}catch(c){return 0;}}}};a.fn.getSelectionEnd=function(c){if(c.type==="number"){return undefined;}else{if(c.createTextRange){var b=document.selection.createRange().duplicate();b.moveStart("character",-c.value.length);return b.text.length;}else{return c.selectionEnd;}}};a.fn.setSelection=function(f,d){if(typeof d=="number"){d=[d,d];}if(d&&d.constructor==Array&&d.length==2){if(f.type==="number"){f.focus();}else{if(f.createTextRange){var b=f.createTextRange();b.collapse(true);b.moveStart("character",d[0]);b.moveEnd("character",d[1]);b.select();}else{f.focus();try{if(f.setSelectionRange){f.setSelectionRange(d[0],d[1]);}}catch(c){}}}}};})(jQuery);

/*!
 * jStorage v0.4.11 - cross-browser key-value store database to store data locally in the browser
 * https://github.com/andris9/jStorage
 *
 * Released under Unlicense - http://unlicense.org/
 */
(function(){var t="0.4.11",j=window.jQuery||window.$||(window.$={}),f={parse:window.JSON&&(window.JSON.parse||window.JSON.decode)||String.prototype.evalJSON&&function(F){return String(F).evalJSON();}||j.parseJSON||j.evalJSON,stringify:Object.toJSON||window.JSON&&(window.JSON.stringify||window.JSON.encode)||j.toJSON};if(typeof f.parse!=="function"||typeof f.stringify!=="function"){throw new Error("No JSON support found, include //cdnjs.cloudflare.com/ajax/libs/json2/20110223/json2.js to page");}var m={__jstorage_meta:{CRC32:{}}},c={jStorage:"{}"},y=null,o=0,i=false,k={},C=false,z=0,s={},x=+new Date(),A,B={isXML:function(G){var F=(G?G.ownerDocument||G:0).documentElement;return F?F.nodeName!=="HTML":false;},encode:function(G){if(!this.isXML(G)){return false;}try{return new XMLSerializer().serializeToString(G);}catch(F){try{return G.xml;}catch(H){}}return false;},decode:function(G){var F=("DOMParser" in window&&(new DOMParser()).parseFromString)||(window.ActiveXObject&&function(I){var J=new ActiveXObject("Microsoft.XMLDOM");J.async="false";J.loadXML(I);return J;}),H;if(!F){return false;}H=F.call("DOMParser" in window&&(new DOMParser())||window,G,"text/xml");return this.isXML(H)?H:false;}};function q(){var F=false;if("localStorage" in window){try{window.localStorage.setItem("_tmptest","tmpval");F=true;window.localStorage.removeItem("_tmptest");}catch(G){}}if(F){try{if(window.localStorage){c=window.localStorage;i="localStorage";z=c.jStorage_update;}}catch(M){}}else{if("globalStorage" in window){try{if(window.globalStorage){if(window.location.hostname=="localhost"){c=window.globalStorage["localhost.localdomain"];}else{c=window.globalStorage[window.location.hostname];}i="globalStorage";z=c.jStorage_update;}}catch(L){}}else{y=document.createElement("link");if(y.addBehavior){y.style.behavior="url(#default#userData)";document.getElementsByTagName("head")[0].appendChild(y);try{y.load("jStorage");}catch(K){y.setAttribute("jStorage","{}");y.save("jStorage");y.load("jStorage");}var J="{}";try{J=y.getAttribute("jStorage");}catch(I){}try{z=y.getAttribute("jStorage_update");}catch(H){}c.jStorage=J;i="userDataBehavior";}else{y=null;return;}}}l();b();w();u();if("addEventListener" in window){window.addEventListener("pageshow",function(N){if(N.persisted){p();}},false);}}function e(){var H="{}";if(i=="userDataBehavior"){y.load("jStorage");try{H=y.getAttribute("jStorage");}catch(G){}try{z=y.getAttribute("jStorage_update");}catch(F){}c.jStorage=H;}l();b();u();}function w(){if(i=="localStorage"||i=="globalStorage"){if("addEventListener" in window){window.addEventListener("storage",p,false);}else{document.attachEvent("onstorage",p);}}else{if(i=="userDataBehavior"){setInterval(p,1000);}}}function p(){var F;clearTimeout(C);C=setTimeout(function(){if(i=="localStorage"||i=="globalStorage"){F=c.jStorage_update;}else{if(i=="userDataBehavior"){y.load("jStorage");try{F=y.getAttribute("jStorage_update");}catch(G){}}}if(F&&F!=z){z=F;h();}},25);}function h(){var F=f.parse(f.stringify(m.__jstorage_meta.CRC32)),J;e();J=f.parse(f.stringify(m.__jstorage_meta.CRC32));var H,G=[],I=[];for(H in F){if(F.hasOwnProperty(H)){if(!J[H]){I.push(H);continue;}if(F[H]!=J[H]&&String(F[H]).substr(0,2)=="2."){G.push(H);}}}for(H in J){if(J.hasOwnProperty(H)){if(!F[H]){G.push(H);}}}E(G,"updated");E(I,"deleted");}function E(K,L){K=[].concat(K||[]);var J,H,F,G;if(L=="flushed"){K=[];for(var I in k){if(k.hasOwnProperty(I)){K.push(I);}}L="deleted";}for(J=0,F=K.length;J<F;J++){if(k[K[J]]){for(H=0,G=k[K[J]].length;H<G;H++){k[K[J]][H](K[J],L);}}if(k["*"]){for(H=0,G=k["*"].length;H<G;H++){k["*"][H](K[J],L);}}}}function n(){var G=(+new Date()).toString();if(i=="localStorage"||i=="globalStorage"){try{c.jStorage_update=G;}catch(F){i=false;}}else{if(i=="userDataBehavior"){y.setAttribute("jStorage_update",G);y.save("jStorage");}}p();}function l(){if(c.jStorage){try{m=f.parse(String(c.jStorage));}catch(F){c.jStorage="{}";}}else{c.jStorage="{}";}o=c.jStorage?String(c.jStorage).length:0;if(!m.__jstorage_meta){m.__jstorage_meta={};}if(!m.__jstorage_meta.CRC32){m.__jstorage_meta.CRC32={};}}function r(){a();try{c.jStorage=f.stringify(m);if(y){y.setAttribute("jStorage",c.jStorage);y.save("jStorage");}o=c.jStorage?String(c.jStorage).length:0;}catch(F){}}function v(F){if(typeof F!="string"&&typeof F!="number"){throw new TypeError("Key name must be string or numeric");}if(F=="__jstorage_meta"){throw new TypeError("Reserved key name");}return true;}function b(){var L,G,J,H,I=Infinity,K=false,F=[];clearTimeout(A);if(!m.__jstorage_meta||typeof m.__jstorage_meta.TTL!="object"){return;}L=+new Date();J=m.__jstorage_meta.TTL;H=m.__jstorage_meta.CRC32;for(G in J){if(J.hasOwnProperty(G)){if(J[G]<=L){delete J[G];delete H[G];delete m[G];K=true;F.push(G);}else{if(J[G]<I){I=J[G];}}}}if(I!=Infinity){A=setTimeout(b,Math.min(I-L,2147483647));}if(K){r();n();E(F,"deleted");}}function u(){var I,G;if(!m.__jstorage_meta.PubSub){return;}var F,H=x;for(I=G=m.__jstorage_meta.PubSub.length-1;I>=0;I--){F=m.__jstorage_meta.PubSub[I];if(F[0]>x){H=F[0];d(F[1],F[2]);}}x=H;}function d(H,J){if(s[H]){for(var G=0,F=s[H].length;G<F;G++){try{s[H][G](H,f.parse(f.stringify(J)));}catch(I){}}}}function a(){if(!m.__jstorage_meta.PubSub){return;}var H=+new Date()-2000;for(var G=0,F=m.__jstorage_meta.PubSub.length;G<F;G++){if(m.__jstorage_meta.PubSub[G][0]<=H){m.__jstorage_meta.PubSub.splice(G,m.__jstorage_meta.PubSub.length-G);break;}}if(!m.__jstorage_meta.PubSub.length){delete m.__jstorage_meta.PubSub;}}function g(F,G){if(!m.__jstorage_meta){m.__jstorage_meta={};}if(!m.__jstorage_meta.PubSub){m.__jstorage_meta.PubSub=[];}m.__jstorage_meta.PubSub.unshift([+new Date(),F,G]);r();n();}function D(K,G){var F=K.length,J=G^F,I=0,H;while(F>=4){H=((K.charCodeAt(I)&255))|((K.charCodeAt(++I)&255)<<8)|((K.charCodeAt(++I)&255)<<16)|((K.charCodeAt(++I)&255)<<24);H=(((H&65535)*1540483477)+((((H>>>16)*1540483477)&65535)<<16));H^=H>>>24;H=(((H&65535)*1540483477)+((((H>>>16)*1540483477)&65535)<<16));J=(((J&65535)*1540483477)+((((J>>>16)*1540483477)&65535)<<16))^H;F-=4;++I;}switch(F){case 3:J^=(K.charCodeAt(I+2)&255)<<16;case 2:J^=(K.charCodeAt(I+1)&255)<<8;case 1:J^=(K.charCodeAt(I)&255);J=(((J&65535)*1540483477)+((((J>>>16)*1540483477)&65535)<<16));}J^=J>>>13;J=(((J&65535)*1540483477)+((((J>>>16)*1540483477)&65535)<<16));J^=J>>>15;return J>>>0;}j.jStorage={version:t,set:function(G,H,F){v(G);F=F||{};if(typeof H=="undefined"){this.deleteKey(G);return H;}if(B.isXML(H)){H={_is_xml:true,xml:B.encode(H)};}else{if(typeof H=="function"){return undefined;}else{if(H&&typeof H=="object"){H=f.parse(f.stringify(H));}}}m[G]=H;m.__jstorage_meta.CRC32[G]="2."+D(f.stringify(H),2538058380);this.setTTL(G,F.TTL||0);E(G,"updated");return H;},get:function(F,G){v(F);if(F in m){if(m[F]&&typeof m[F]=="object"&&m[F]._is_xml){return B.decode(m[F].xml);}else{return m[F];}}return typeof(G)=="undefined"?null:G;},deleteKey:function(F){v(F);if(F in m){delete m[F];if(typeof m.__jstorage_meta.TTL=="object"&&F in m.__jstorage_meta.TTL){delete m.__jstorage_meta.TTL[F];}delete m.__jstorage_meta.CRC32[F];r();n();E(F,"deleted");return true;}return false;},setTTL:function(G,F){var H=+new Date();v(G);F=Number(F)||0;if(G in m){if(!m.__jstorage_meta.TTL){m.__jstorage_meta.TTL={};}if(F>0){m.__jstorage_meta.TTL[G]=H+F;}else{delete m.__jstorage_meta.TTL[G];}r();b();n();return true;}return false;},getTTL:function(G){var H=+new Date(),F;v(G);if(G in m&&m.__jstorage_meta.TTL&&m.__jstorage_meta.TTL[G]){F=m.__jstorage_meta.TTL[G]-H;return F||0;}return 0;},flush:function(){m={__jstorage_meta:{CRC32:{}}};r();n();E(null,"flushed");return true;},storageObj:function(){function G(){}G.prototype=m;return new G();},index:function(){var F=[],G;for(G in m){if(m.hasOwnProperty(G)&&G!="__jstorage_meta"){F.push(G);}}return F;},storageSize:function(){return o;},currentBackend:function(){return i;},storageAvailable:function(){return !!i;},listenKeyChange:function(F,G){v(F);if(!k[F]){k[F]=[];}k[F].push(G);},stopListening:function(G,H){v(G);if(!k[G]){return;}if(!H){delete k[G];return;}for(var F=k[G].length-1;F>=0;F--){if(k[G][F]==H){k[G].splice(F,1);}}},subscribe:function(F,G){F=(F||"").toString();if(!F){throw new TypeError("Channel not defined");}if(!s[F]){s[F]=[];}s[F].push(G);},publish:function(F,G){F=(F||"").toString();if(!F){throw new TypeError("Channel not defined");}g(F,G);},reInit:function(){e();},noConflict:function(F){delete window.$.jStorage;if(F){window.jStorage=this;}return this;}};q();})();

/*!
 * mustache.js 0.8.2 - Logic-less {{mustache}} templates with JavaScript
 * http://github.com/janl/mustache.js
 * Released under the MIT license - http://www.opensource.org/licenses/mit-license.php
 */
(function(b,a){if(typeof exports==="object"&&exports){a(exports);}else{if(typeof define==="function"&&define.amd){define(["exports"],a);}else{a(b.Mustache={});}}}(this,function(a){var u=Object.prototype.toString;var k=Array.isArray||function(x){return u.call(x)==="[object Array]";};function b(x){return typeof x==="function";}function e(x){return x.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&");}var g=RegExp.prototype.test;function q(y,x){return g.call(y,x);}var j=/\S/;function h(x){return !q(j,x);}var d={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;"};function m(x){return String(x).replace(/[&<>"'\/]/g,function(y){return d[y];});}var f=/\s*/;var l=/\s+/;var t=/\s*=/;var n=/\s*\}/;var r=/#|\^|\/|>|\{|&|=|!/;function w(Q,F){if(!Q){return[];}var H=[];var G=[];var C=[];var R=false;var O=false;function N(){if(R&&!O){while(C.length){delete G[C.pop()];}}else{C=[];}R=false;O=false;}var J,E,P;function D(S){if(typeof S==="string"){S=S.split(l,2);}if(!k(S)||S.length!==2){throw new Error("Invalid tags: "+S);}J=new RegExp(e(S[0])+"\\s*");E=new RegExp("\\s*"+e(S[1]));P=new RegExp("\\s*"+e("}"+S[1]));}D(F||a.tags);var z=new s(Q);var A,y,I,L,B,x;while(!z.eos()){A=z.pos;I=z.scanUntil(J);if(I){for(var M=0,K=I.length;M<K;++M){L=I.charAt(M);if(h(L)){C.push(G.length);}else{O=true;}G.push(["text",L,A,A+1]);A+=1;if(L==="\n"){N();}}}if(!z.scan(J)){break;}R=true;y=z.scan(r)||"name";z.scan(f);if(y==="="){I=z.scanUntil(t);z.scan(t);z.scanUntil(E);}else{if(y==="{"){I=z.scanUntil(P);z.scan(n);z.scanUntil(E);y="&";}else{I=z.scanUntil(E);}}if(!z.scan(E)){throw new Error("Unclosed tag at "+z.pos);}B=[y,I,A,z.pos];G.push(B);if(y==="#"||y==="^"){H.push(B);}else{if(y==="/"){x=H.pop();if(!x){throw new Error('Unopened section "'+I+'" at '+A);}if(x[1]!==I){throw new Error('Unclosed section "'+x[1]+'" at '+A);}}else{if(y==="name"||y==="{"||y==="&"){O=true;}else{if(y==="="){D(I);}}}}}x=H.pop();if(x){throw new Error('Unclosed section "'+x[1]+'" at '+z.pos);}return v(c(G));}function c(C){var y=[];var A,x;for(var z=0,B=C.length;z<B;++z){A=C[z];if(A){if(A[0]==="text"&&x&&x[0]==="text"){x[1]+=A[1];x[3]=A[3];}else{y.push(A);x=A;}}}return y;}function v(C){var E=[];var B=E;var D=[];var y,A;for(var x=0,z=C.length;x<z;++x){y=C[x];switch(y[0]){case"#":case"^":B.push(y);D.push(y);B=y[4]=[];break;case"/":A=D.pop();A[5]=y[2];B=D.length>0?D[D.length-1][4]:E;break;default:B.push(y);}}return E;}function s(x){this.string=x;this.tail=x;this.pos=0;}s.prototype.eos=function(){return this.tail==="";};s.prototype.scan=function(z){var y=this.tail.match(z);if(!y||y.index!==0){return"";}var x=y[0];this.tail=this.tail.substring(x.length);this.pos+=x.length;return x;};s.prototype.scanUntil=function(z){var y=this.tail.search(z),x;switch(y){case -1:x=this.tail;this.tail="";break;case 0:x="";break;default:x=this.tail.substring(0,y);this.tail=this.tail.substring(y);}this.pos+=x.length;return x;};function p(y,x){this.view=y==null?{}:y;this.cache={".":this.view};this.parent=x;}p.prototype.push=function(x){return new p(x,this);};p.prototype.lookup=function(z){var x=this.cache;var B;if(z in x){B=x[z];}else{var A=this,C,y;while(A){if(z.indexOf(".")>0){B=A.view;C=z.split(".");y=0;while(B!=null&&y<C.length){B=B[C[y++]];}}else{B=A.view[z];}if(B!=null){break;}A=A.parent;}x[z]=B;}if(b(B)){B=B.call(this.view);}return B;};function o(){this.cache={};}o.prototype.clearCache=function(){this.cache={};};o.prototype.parse=function(z,y){var x=this.cache;var A=x[z];if(A==null){A=x[z]=w(z,y);}return A;};o.prototype.render=function(A,x,z){var B=this.parse(A);var y=(x instanceof p)?x:new p(x);return this.renderTokens(B,y,z,A);};o.prototype.renderTokens=function(F,x,D,I){var B="";var J=this;function y(K){return J.render(K,x,D);}var z,G;for(var C=0,E=F.length;C<E;++C){z=F[C];switch(z[0]){case"#":G=x.lookup(z[1]);if(!G){continue;}if(k(G)){for(var A=0,H=G.length;A<H;++A){B+=this.renderTokens(z[4],x.push(G[A]),D,I);}}else{if(typeof G==="object"||typeof G==="string"){B+=this.renderTokens(z[4],x.push(G),D,I);}else{if(b(G)){if(typeof I!=="string"){throw new Error("Cannot use higher-order sections without the original template");}G=G.call(x.view,I.slice(z[3],z[5]),y);if(G!=null){B+=G;}}else{B+=this.renderTokens(z[4],x,D,I);}}}break;case"^":G=x.lookup(z[1]);if(!G||(k(G)&&G.length===0)){B+=this.renderTokens(z[4],x,D,I);}break;case">":if(!D){continue;}G=b(D)?D(z[1]):D[z[1]];if(G!=null){B+=this.renderTokens(this.parse(G),x,D,G);}break;case"&":G=x.lookup(z[1]);if(G!=null){B+=G;}break;case"name":G=x.lookup(z[1]);if(G!=null){B+=a.escape(G);}break;case"text":B+=z[1];break;}}return B;};a.name="mustache.js";a.version="0.8.1";a.tags=["{{","}}"];var i=new o();a.clearCache=function(){return i.clearCache();};a.parse=function(y,x){return i.parse(y,x);};a.render=function(z,x,y){return i.render(z,x,y);};a.to_html=function(A,y,z,B){var x=a.render(A,y,z);if(b(B)){B(x);}else{return x;}};a.escape=m;a.Scanner=s;a.Context=p;a.Writer=o;}));

/**
 * fastLiveFilter jQuery plugin 1.0.3
 *
 * Copyright (c) 2011, Anthony Bush
 * License: <http://www.opensource.org/licenses/bsd-license.php>
 * Project Website: http://anthonybush.com/projects/jquery_fast_live_filter/
 **/
jQuery.fn.fastLiveFilter=function(list,options){options=options||{};list=jQuery(list);var input=this;var timeout=options.timeout||0;var callback=options.callback||function(){};var keyTimeout;var lis=list.children();var len=lis.length;var oldDisplay=len>0?lis[0].style.display:"block";callback(len);input.change(function(){var filter=input.val().toLowerCase();var li;var numShown=0;for(var i=0;i<len;i++){li=lis[i];if((li.textContent||li.innerText||"").toLowerCase().indexOf(filter)>=0){if(li.style.display=="none"){li.style.display=oldDisplay}numShown++}else{if(li.style.display!="none"){li.style.display="none"}}}callback(numShown);return false}).keydown(function(){clearTimeout(keyTimeout);keyTimeout=setTimeout(function(){input.change()},timeout)});return this};

/**
 * @project Ratio.js
 * @purpose Provides a Ratio(Fraction) object for Javascript. Similar to Fraction.py for Python.
 * @author Larry Battle
 * @link Project page: https://github.com/LarryBattle/Ratio.js/
 * @license MIT and GPL 3.0
 * @version 0.4.0
 **/
var Ratio=function(){"use strict";var e=function(t,n,r){if(this instanceof e){this.divSign="/",this.alwaysReduce=!!r;var i=e.getStandardRatioArray(t,n,this.alwaysReduce);return this._n=i[0],this._d=i[1],this}return new e(t,n,r)};return e.MAX_PRECISION=(1/3).toString().length-2,e.MAX_VALUE=Math.pow(2,53),e.MIN_VALUE=-Math.pow(2,53),e.regex={divSignCheck:/(\d|Infinity)\s*\//,divSignSplit:/\//,cleanFormat:/^\d+\.\d+$/,mixedNumbers:/(\S+)\s+(\S[\w\W]*)/,repeatingDecimals:/[^\.]+\.\d*(\d{2,})+(?:\1)$/,repeatingNumbers:/^(\d+)(?:\1)$/},e.VERSION="0.4.0",e.isNumeric=function(e){return!isNaN(parseFloat(e))&&isFinite(e)},e.getValueIfDefined=function(e,t){return typeof t!="undefined"&&t!==null?t:e},e.gcd=function(e,t){var n;t=+t&&+e?+t:0,e=t?e:1;while(t)n=e%t,e=t,t=n;return Math.abs(e)},e.getNumeratorWithSign=function(e,t){var n=+e*(+t||1)<0?-1:1;return Math.abs(+e)*n},e.guessType=function(t){var n="NaN";return t instanceof e?n="Ratio":isNaN(t)?e.regex.divSignCheck.test(t)&&(/\d\s+[+\-]?\d/.test(t)?n="mixed":n="fraction"):(n="number",-1<(+t).toString().indexOf("e")?n="e":t%1&&(n="decimal")),n},e.parseToArray=function(t){var n=[],r,i,s=[],o;switch(e.guessType(t)){case"mixed":n=t.match(e.regex.mixedNumbers),s=e.parseToArray(n[2]),r=+n[1]<0||+s[0]<0?-1:1,s[0]=r*(Math.abs(s[0])+Math.abs(n[1]*s[1]));break;case"fraction":n=t.split(e.regex.divSignSplit),s[0]=e.getNumeratorWithSign(n[0],n[1]),s[1]=Math.abs(+n[1]);break;case"decimal":n=(+t).toString().split("."),s[1]=Math.pow(10,n[1].length),s[0]=Math.abs(n[0])*s[1]+ +n[1],s[0]=-1<n[0].indexOf("-")?-s[0]:s[0];break;case"number":s=[+t,1];break;case"e":n=(+t).toString().split(/e/i),o=e.parseToArray(n[0]),i=Math.abs(+t)<1?[0,1]:[1,0],s[i[0]]=o[i[0]],s[i[1]]=Number(o[i[1]]+"e"+Math.abs(+n[1]));break;case"Ratio":s=[t._n,t._d];break;default:s=[NaN,1]}return s},e.parse=function(t,n){var r=e.parseToArray(t),i;return r.length&&n!==undefined&&n!==null&&(i=e.parseToArray(n),r[0]*=i[1],r[1]*=i[0]),new e(r[0],r[1])},e.simplify=function(t,n){t=e.parse(t,n);var r=t._n,i=r||!t._d?t._d:1,s=e.getRepeatProps(r/i),o;return s.length&&(r=Number(s.join(""))-Number(s[0]+String(s[1])),i=Math.pow(10,s[1].length)*(Math.pow(10,s[2].length)-1)),o=e.gcd(r,i),[r/o,i/o]},e.getRepeatProps=function(t){t=String(t||"");var n=[],r=e.regex.repeatingDecimals.exec(t),i,s=e.regex.repeatingNumbers;return r||(t=t.replace(/\d$/,""),r=e.regex.repeatingDecimals.exec(t)),r&&1<r.length&&/\.\d{10}/.test(r[0])&&(r[1]=s.test(r[1])?s.exec(r[1])[1]:r[1],i=new RegExp("("+r[1]+")+$"),n=t.split(/\./).concat(r[1]),n[1]=n[1].replace(i,"")),n},e.getPrimeFactors=function(e){e=Math.floor(e);var t,n=[],r,i=Math.sqrt,s=1<e&&isFinite(e);while(s){t=i(e),r=2;if(e%r){r=3;while(e%r&&(r+=2)<t);}r=t<r?e:r,n.push(r),s=r!==e,e/=r}return n},e.getCleanENotation=function(e){e=(+e||0).toString();if(/\.\d+(0|9){8,}\d?e/.test(e)){var t=e.match(/(?:\d+\.)(\d+)(?:e[\w\W]*)/)[1].replace(/(0|9)+\d$/,"").length+1;e=(+e).toPrecision(t).toString()}return e},e.simplifyENotation=function(e,t){var n=e/t,r=/[eE]/;if(!isNaN(n)&&r.test(e)&&r.test(t)){var i=e.toString().split("e"),s=t.toString().split("e");Number(s[1])<Number(i[1])?(i[1]=Number(i[1])+ -1*s[1],s[1]=0):(s[1]=Number(s[1])+ -1*i[1],i[1]=0),e=Number(i.join("e")),t=Number(s.join("e"))}return[e,t]},e.getCombinedRatio=function(t,n){if(!(t instanceof e)||n!==undefined)t=e.parse(t,n);return t},e.random=function(){var t=Math.random().toFixed(Math.floor(Math.random()*16));return e.parse(t).simplify()},e.getStandardRatioArray=function(t,n,r){typeof n=="undefined"&&(n=1,typeof t=="undefined"&&(t=0));var i=+Math.abs(n),s=e.getNumeratorWithSign(t,n||1),o=[s,i];return o[1]&&r&&(o=e.simplify(o[0],o[1])),o},e.prototype={constructor:e,numerator:function(t){return typeof t!="undefined"&&(this._n=e.parse(t).valueOf()),this._n},denominator:function(t){return typeof t!="undefined"&&(this._d=e.parse(t).valueOf(),this.correctRatio()),this._d},correctRatio:function(){var t=e.getStandardRatioArray(this._n,this._d,this.alwaysReduce);return this._n=t[0],this._d=t[1],this},toArray:function(){return[this._n,this._d]},valueOf:function(){var t=e.simplifyENotation(this._n,this._d);return t[0]/t[1]},toLocaleString:function(){var e=this.valueOf(),t,n;return isNaN(e)?n="NaN":e%1===0||this._d===1||!isFinite(e%1)?n=String(e):1<Math.abs(e)?(t=parseInt(e,10),n=t+" "+Math.abs(this._n%this._d)+String(this.divSign)+this._d):n=this._n+String(this.divSign)+this._d,n},toString:function(){return String(this._n+this.divSign+this._d)},clone:function(t,n,r){var i=e.getValueIfDefined;return t=i(this._n,t),n=i(this._d,n),r=i(this.alwaysReduce,r),new e(t,n,r)},isNaN:function(){return!e.isNumeric(this.valueOf())},simplify:function(){var t=e.simplify(this._n,this._d);return this.clone(t[0],t[1])},add:function(t,n){t=e.getCombinedRatio(t,n);var r,i,s;return this._d===t._d?(i=this._n+t._n,s=this._d):(r=e.gcd(this._d,t._d),i=(this._n*t._d+this._d*t._n)/r,s=this._d*t._d/r),this.clone(i,s)},divide:function(t,n){return t=e.getCombinedRatio(t,n),this.clone(this._n*t._d,this._d*t._n)},equals:function(t){var n=e.isNumeric(t)||t instanceof e?t.valueOf():e.parse(t).valueOf();return this._n/this._d===+n},deepEquals:function(t){return t instanceof e&&this._n===t._n&&this._d===t._d&&this.divSign===t.divSign&&this.alwaysReduce===t.alwaysReduce},multiply:function(t,n){return t=e.getCombinedRatio(t,n),this.clone(this._n*t._n,this._d*t._d)},subtract:function(t,n){t=e.getCombinedRatio(t,n);var r,i,s;return this._d===t._d?(i=this._n-t._n,s=this._d):(r=e.gcd(this._d,t._d),i=(this._n*t._d-this._d*t._n)/r,s=this._d*t._d/r),this.clone(i,s)},descale:function(t,n){var r=e.getCombinedRatio(t,n);return this.clone(this._n/r,this._d/r)},pow:function(t,n){var r=e.getCombinedRatio(t,n);return this.clone(Math.pow(this._n,+r),Math.pow(this._d,+r))},scale:function(t,n){var r=e.getCombinedRatio(t,n);return this.clone(this._n*+r,this._d*+r)},cleanFormat:function(){var t=e.regex.cleanFormat,n;return t.test(this._n)||t.test(this._d)?e.parse(this._n,this._d):(n=this.clone(),n._n=e.getCleanENotation(n._n),n._d=e.getCleanENotation(n._d),n)},abs:function(){return this.clone(Math.abs(this._n))},mod:function(){return this.clone(this._n%this._d,1)},negate:function(){return this.clone(-this._n)},isProper:function(){return Math.abs(this._n)<this._d},findX:function(t){var n=String(t).split("/");return n.length!==2||!isNaN(n[0])&&!isNaN(n[1])?null:isNaN(n[0])?(new e(n[1])).multiply(this):(new e(n[0])).divide(this)},reciprocal:function(){return this.clone(this._d,this._n)},toQuantityOf:function(){var e=this.valueOf(),t,n,r,i=Infinity,s=arguments.length;for(r=0;r<s;r+=1)n=Math.abs(Math.round(e*arguments[r])/arguments[r]-e),n<i&&(t=arguments[r],i=n);return this.clone(Math.round(e*t),t)},floor:function(){return this.clone(Math.floor(this.valueOf()),1)},ceil:function(){return this.clone(Math.ceil(this.valueOf()),1)},makeProper:function(){return this.clone(this._n%this._d,this._d)}},e}();typeof exports!="undefined"?(typeof module!="undefined"&&module.exports&&(exports=module.exports=Ratio),exports.Ratio=Ratio):this.Ratio=Ratio;

/*!
 * jQuery blockUI plugin
 * Version 2.70.0-2014.11.23
 * Requires jQuery v1.7 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2013 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
!function(){"use strict";function e(e){function t(t,n){var s,h,k=t==window,y=n&&void 0!==n.message?n.message:void 0;if(n=e.extend({},e.blockUI.defaults,n||{}),!n.ignoreIfBlocked||!e(t).data("blockUI.isBlocked")){if(n.overlayCSS=e.extend({},e.blockUI.defaults.overlayCSS,n.overlayCSS||{}),s=e.extend({},e.blockUI.defaults.css,n.css||{}),n.onOverlayClick&&(n.overlayCSS.cursor="pointer"),h=e.extend({},e.blockUI.defaults.themedCSS,n.themedCSS||{}),y=void 0===y?n.message:y,k&&p&&o(window,{fadeOut:0}),y&&"string"!=typeof y&&(y.parentNode||y.jquery)){var m=y.jquery?y[0]:y,v={};e(t).data("blockUI.history",v),v.el=m,v.parent=m.parentNode,v.display=m.style.display,v.position=m.style.position,v.parent&&v.parent.removeChild(m)}e(t).data("blockUI.onUnblock",n.onUnblock);var g,I,w,U,x=n.baseZ;g=e(r||n.forceIframe?'<iframe class="blockUI" style="z-index:'+x++ +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+n.iframeSrc+'"></iframe>':'<div class="blockUI" style="display:none"></div>'),I=e(n.theme?'<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+x++ +';display:none"></div>':'<div class="blockUI blockOverlay" style="z-index:'+x++ +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>'),n.theme&&k?(U='<div class="blockUI '+n.blockMsgClass+' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(x+10)+';display:none;position:fixed">',n.title&&(U+='<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(n.title||"&nbsp;")+"</div>"),U+='<div class="ui-widget-content ui-dialog-content"></div>',U+="</div>"):n.theme?(U='<div class="blockUI '+n.blockMsgClass+' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(x+10)+';display:none;position:absolute">',n.title&&(U+='<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(n.title||"&nbsp;")+"</div>"),U+='<div class="ui-widget-content ui-dialog-content"></div>',U+="</div>"):U=k?'<div class="blockUI '+n.blockMsgClass+' blockPage" style="z-index:'+(x+10)+';display:none;position:fixed"></div>':'<div class="blockUI '+n.blockMsgClass+' blockElement" style="z-index:'+(x+10)+';display:none;position:absolute"></div>',w=e(U),y&&(n.theme?(w.css(h),w.addClass("ui-widget-content")):w.css(s)),n.theme||I.css(n.overlayCSS),I.css("position",k?"fixed":"absolute"),(r||n.forceIframe)&&g.css("opacity",0);var C=[g,I,w],S=e(k?"body":t);e.each(C,function(){this.appendTo(S)}),n.theme&&n.draggable&&e.fn.draggable&&w.draggable({handle:".ui-dialog-titlebar",cancel:"li"});var O=f&&(!e.support.boxModel||e("object,embed",k?null:t).length>0);if(u||O){if(k&&n.allowBodyStretch&&e.support.boxModel&&e("html,body").css("height","100%"),(u||!e.support.boxModel)&&!k)var E=d(t,"borderTopWidth"),T=d(t,"borderLeftWidth"),M=E?"(0 - "+E+")":0,B=T?"(0 - "+T+")":0;e.each(C,function(e,t){var o=t[0].style;if(o.position="absolute",2>e)k?o.setExpression("height","Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.support.boxModel?0:"+n.quirksmodeOffsetHack+') + "px"'):o.setExpression("height",'this.parentNode.offsetHeight + "px"'),k?o.setExpression("width",'jQuery.support.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"'):o.setExpression("width",'this.parentNode.offsetWidth + "px"'),B&&o.setExpression("left",B),M&&o.setExpression("top",M);else if(n.centerY)k&&o.setExpression("top",'(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"'),o.marginTop=0;else if(!n.centerY&&k){var i=n.css&&n.css.top?parseInt(n.css.top,10):0,s="((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "+i+') + "px"';o.setExpression("top",s)}})}if(y&&(n.theme?w.find(".ui-widget-content").append(y):w.append(y),(y.jquery||y.nodeType)&&e(y).show()),(r||n.forceIframe)&&n.showOverlay&&g.show(),n.fadeIn){var j=n.onBlock?n.onBlock:c,H=n.showOverlay&&!y?j:c,z=y?j:c;n.showOverlay&&I._fadeIn(n.fadeIn,H),y&&w._fadeIn(n.fadeIn,z)}else n.showOverlay&&I.show(),y&&w.show(),n.onBlock&&n.onBlock.bind(w)();if(i(1,t,n),k?(p=w[0],b=e(n.focusableElements,p),n.focusInput&&setTimeout(l,20)):a(w[0],n.centerX,n.centerY),n.timeout){var W=setTimeout(function(){k?e.unblockUI(n):e(t).unblock(n)},n.timeout);e(t).data("blockUI.timeout",W)}}}function o(t,o){var s,l=t==window,a=e(t),d=a.data("blockUI.history"),c=a.data("blockUI.timeout");c&&(clearTimeout(c),a.removeData("blockUI.timeout")),o=e.extend({},e.blockUI.defaults,o||{}),i(0,t,o),null===o.onUnblock&&(o.onUnblock=a.data("blockUI.onUnblock"),a.removeData("blockUI.onUnblock"));var r;r=l?e("body").children().filter(".blockUI").add("body > .blockUI"):a.find(">.blockUI"),o.cursorReset&&(r.length>1&&(r[1].style.cursor=o.cursorReset),r.length>2&&(r[2].style.cursor=o.cursorReset)),l&&(p=b=null),o.fadeOut?(s=r.length,r.stop().fadeOut(o.fadeOut,function(){0===--s&&n(r,d,o,t)})):n(r,d,o,t)}function n(t,o,n,i){var s=e(i);if(!s.data("blockUI.isBlocked")){t.each(function(){this.parentNode&&this.parentNode.removeChild(this)}),o&&o.el&&(o.el.style.display=o.display,o.el.style.position=o.position,o.el.style.cursor="default",o.parent&&o.parent.appendChild(o.el),s.removeData("blockUI.history")),s.data("blockUI.static")&&s.css("position","static"),"function"==typeof n.onUnblock&&n.onUnblock(i,n);var l=e(document.body),a=l.width(),d=l[0].style.width;l.width(a-1).width(a),l[0].style.width=d}}function i(t,o,n){var i=o==window,l=e(o);if((t||(!i||p)&&(i||l.data("blockUI.isBlocked")))&&(l.data("blockUI.isBlocked",t),i&&n.bindEvents&&(!t||n.showOverlay))){var a="mousedown mouseup keydown keypress keyup touchstart touchend touchmove";t?e(document).bind(a,n,s):e(document).unbind(a,s)}}function s(t){if("keydown"===t.type&&t.keyCode&&9==t.keyCode&&p&&t.data.constrainTabKey){var o=b,n=!t.shiftKey&&t.target===o[o.length-1],i=t.shiftKey&&t.target===o[0];if(n||i)return setTimeout(function(){l(i)},10),!1}var s=t.data,a=e(t.target);return a.hasClass("blockOverlay")&&s.onOverlayClick&&s.onOverlayClick(t),a.parents("div."+s.blockMsgClass).length>0?!0:0===a.parents().children().filter("div.blockUI").length}function l(e){if(b){var t=b[e===!0?b.length-1:0];t&&t.focus()}}function a(e,t,o){var n=e.parentNode,i=e.style,s=(n.offsetWidth-e.offsetWidth)/2-d(n,"borderLeftWidth"),l=(n.offsetHeight-e.offsetHeight)/2-d(n,"borderTopWidth");t&&(i.left=s>0?s+"px":"0"),o&&(i.top=l>0?l+"px":"0")}function d(t,o){return parseInt(e.css(t,o),10)||0}e.fn._fadeIn=e.fn.fadeIn;var c=e.noop||function(){},r=/MSIE/.test(navigator.userAgent),u=/MSIE 6.0/.test(navigator.userAgent)&&!/MSIE 8.0/.test(navigator.userAgent),f=(document.documentMode||0,e.isFunction(document.createElement("div").style.setExpression));e.blockUI=function(e){t(window,e)},e.unblockUI=function(e){o(window,e)},e.growlUI=function(t,o,n,i){var s=e('<div class="growlUI"></div>');t&&s.append("<h1>"+t+"</h1>"),o&&s.append("<h2>"+o+"</h2>"),void 0===n&&(n=3e3);var l=function(t){t=t||{},e.blockUI({message:s,fadeIn:"undefined"!=typeof t.fadeIn?t.fadeIn:700,fadeOut:"undefined"!=typeof t.fadeOut?t.fadeOut:1e3,timeout:"undefined"!=typeof t.timeout?t.timeout:n,centerY:!1,showOverlay:!1,onUnblock:i,css:e.blockUI.defaults.growlCSS})};l();s.css("opacity");s.mouseover(function(){l({fadeIn:0,timeout:3e4});var t=e(".blockMsg");t.stop(),t.fadeTo(300,1)}).mouseout(function(){e(".blockMsg").fadeOut(1e3)})},e.fn.block=function(o){if(this[0]===window)return e.blockUI(o),this;var n=e.extend({},e.blockUI.defaults,o||{});return this.each(function(){var t=e(this);n.ignoreIfBlocked&&t.data("blockUI.isBlocked")||t.unblock({fadeOut:0})}),this.each(function(){"static"==e.css(this,"position")&&(this.style.position="relative",e(this).data("blockUI.static",!0)),this.style.zoom=1,t(this,o)})},e.fn.unblock=function(t){return this[0]===window?(e.unblockUI(t),this):this.each(function(){o(this,t)})},e.blockUI.version=2.7,e.blockUI.defaults={message:"<h1>Please wait...</h1>",title:null,draggable:!0,theme:!1,css:{padding:0,margin:0,width:"30%",top:"40%",left:"35%",textAlign:"center",color:"#000",border:"3px solid #aaa",backgroundColor:"#fff",cursor:"wait"},themedCSS:{width:"30%",top:"40%",left:"35%"},overlayCSS:{backgroundColor:"#000",opacity:.6,cursor:"wait"},cursorReset:"default",growlCSS:{width:"350px",top:"10px",left:"",right:"10px",border:"none",padding:"5px",opacity:.6,cursor:"default",color:"#fff",backgroundColor:"#000","-webkit-border-radius":"10px","-moz-border-radius":"10px","border-radius":"10px"},iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank",forceIframe:!1,baseZ:1e3,centerX:!0,centerY:!0,allowBodyStretch:!0,bindEvents:!0,constrainTabKey:!0,fadeIn:200,fadeOut:400,timeout:0,showOverlay:!0,focusInput:!0,focusableElements:":input:enabled:visible",onBlock:null,onUnblock:null,onOverlayClick:null,quirksmodeOffsetHack:4,blockMsgClass:"blockMsg",ignoreIfBlocked:!1};var p=null,b=[]}"function"==typeof define&&define.amd&&define.amd.jQuery?define(["jquery"],e):e(jQuery)}();

/*!
 SerializeJSON jQuery plugin.
 https://github.com/marioizquierdo/jquery.serializeJSON
 version 2.3.0 (Sep, 2014)
 Copyright (c) 2014 Mario Izquierdo
 Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 */
(function(a){a.fn.serializeJSON=function(c){var d,b,g,i,h,e;h=a.serializeJSON;e=h.optsWithDefaults(c);b=this.serializeArray();h.readCheckboxUncheckedValues(b,this,e);d={};a.each(b,function(j,f){g=h.splitInputNameIntoKeysArray(f.name);i=h.parseValue(f.value,e);if(e.parseWithFunction){i=e.parseWithFunction(i)}h.deepSet(d,g,i,e)});return d};a.serializeJSON={defaultOptions:{parseNumbers:false,parseBooleans:false,parseNulls:false,parseAll:false,parseWithFunction:null,checkboxUncheckedValue:undefined,useIntKeysAsArrayIndex:false},optsWithDefaults:function(c){var d,b;if(c==null){c={}}d=a.serializeJSON;b=d.optWithDefaults("parseAll",c);return{parseNumbers:b||d.optWithDefaults("parseNumbers",c),parseBooleans:b||d.optWithDefaults("parseBooleans",c),parseNulls:b||d.optWithDefaults("parseNulls",c),parseWithFunction:d.optWithDefaults("parseWithFunction",c),checkboxUncheckedValue:d.optWithDefaults("checkboxUncheckedValue",c),useIntKeysAsArrayIndex:d.optWithDefaults("useIntKeysAsArrayIndex",c)}},optWithDefaults:function(c,b){return(b[c]!==false)&&(b[c]!=="")&&(b[c]||a.serializeJSON.defaultOptions[c])},parseValue:function(e,b){var d,c;c=a.serializeJSON;if(b.parseNumbers&&c.isNumeric(e)){return Number(e)}if(b.parseBooleans&&(e==="true"||e==="false")){return e==="true"}if(b.parseNulls&&e=="null"){return null}return e},isObject:function(b){return b===Object(b)},isUndefined:function(b){return b===void 0},isValidArrayIndex:function(b){return/^[0-9]+$/.test(String(b))},isNumeric:function(b){return b-parseFloat(b)>=0},splitInputNameIntoKeysArray:function(b){var d,c,e;e=a.serializeJSON;if(e.isUndefined(b)){throw new Error("ArgumentError: param 'name' expected to be a string, found undefined")}d=a.map(b.split("["),function(f){c=f[f.length-1];return c==="]"?f.substring(0,f.length-1):f});if(d[0]===""){d.shift()}return d},deepSet:function(c,l,j,b){var k,h,g,i,d,e;if(b==null){b={}}e=a.serializeJSON;if(e.isUndefined(c)){throw new Error("ArgumentError: param 'o' expected to be an object or array, found undefined")}if(!l||l.length===0){throw new Error("ArgumentError: param 'keys' expected to be an array with least one element")}k=l[0];if(l.length===1){if(k===""){c.push(j)}else{c[k]=j}}else{h=l[1];if(k===""){i=c.length-1;d=c[i];if(e.isObject(d)&&(e.isUndefined(d[h])||l.length>2)){k=i}else{k=i+1}}if(e.isUndefined(c[k])){if(h===""){c[k]=[]}else{if(b.useIntKeysAsArrayIndex&&e.isValidArrayIndex(h)){c[k]=[]}else{c[k]={}}}}g=l.slice(1);e.deepSet(c[k],g,j,b)}},readCheckboxUncheckedValues:function(e,d,i){var b,h,g,c,j;if(i==null){i={}}j=a.serializeJSON;b="input[type=checkbox]:not(:checked)";h=d.find(b).add(d.filter(b));h.each(function(f,k){g=a(k);c=g.attr("data-unchecked-value");if(c){e.push({name:k.name,value:c})}else{if(!j.isUndefined(i.checkboxUncheckedValue)){e.push({name:k.name,value:i.checkboxUncheckedValue})}}})}}}(window.jQuery||window.Zepto||window.$));

/**
 * Form2Js, Js2Form
 * Copyright (c) 2010 Maxim Vasiliev https://github.com/maxatwork/form2js
 * @author Maxim Vasiliev
 * @license MIT
 * Date: 19.09.11
 */
(function(root,factory){if(typeof define==="function"&&define.amd){define(factory)}else{root.js2form=factory()}}(this,function(){var _subArrayRegexp=/^\[\d+?\]/,_subObjectRegexp=/^[a-zA-Z_][a-zA-Z_0-9]+/,_arrayItemRegexp=/\[[0-9]+?\]$/,_lastIndexedArrayRegexp=/(.*)(\[)([0-9]*)(\])$/,_arrayOfArraysRegexp=/\[([0-9]+)\]\[([0-9]+)\]/g,_inputOrTextareaRegexp=/INPUT|TEXTAREA/i;function js2form(rootNode,data,delimiter,nodeCallback,useIdIfEmptyName){if(arguments.length<3){delimiter="."}if(arguments.length<4){nodeCallback=null}if(arguments.length<5){useIdIfEmptyName=false}var fieldValues,formFieldsByName;fieldValues=object2array(data);formFieldsByName=getFields(rootNode,useIdIfEmptyName,delimiter,{},true);for(var i=0;i<fieldValues.length;i++){var fieldName=fieldValues[i].name,fieldValue=fieldValues[i].value;if(typeof formFieldsByName[fieldName]!="undefined"){setValue(formFieldsByName[fieldName],fieldValue)}else{if(typeof formFieldsByName[fieldName.replace(_arrayItemRegexp,"[]")]!="undefined"){setValue(formFieldsByName[fieldName.replace(_arrayItemRegexp,"[]")],fieldValue)}}}}function setValue(field,value){var children,i,l;if(field instanceof Array){for(i=0;i<field.length;i++){if(field[i].value==value||value===true){field[i].checked=true}}}else{if(_inputOrTextareaRegexp.test(field.nodeName)){field.value=value}else{if(/SELECT/i.test(field.nodeName)){children=field.getElementsByTagName("option");for(i=0,l=children.length;i<l;i++){if(children[i].value==value){children[i].selected=true;if(field.multiple){break}}else{if(!field.multiple){children[i].selected=false}}}}}}}function getFields(rootNode,useIdIfEmptyName,delimiter,arrayIndexes,shouldClean){if(arguments.length<4){arrayIndexes={}}var result={},currNode=rootNode.firstChild,name,nameNormalized,subFieldName,i,j,l,options;while(currNode){name="";if(currNode.name&&currNode.name!=""){name=currNode.name}else{if(useIdIfEmptyName&&currNode.id&&currNode.id!=""){name=currNode.id}}if(name==""){var subFields=getFields(currNode,useIdIfEmptyName,delimiter,arrayIndexes,shouldClean);for(subFieldName in subFields){if(typeof result[subFieldName]=="undefined"){result[subFieldName]=subFields[subFieldName]}else{for(i=0;i<subFields[subFieldName].length;i++){result[subFieldName].push(subFields[subFieldName][i])}}}}else{if(/SELECT/i.test(currNode.nodeName)){for(j=0,options=currNode.getElementsByTagName("option"),l=options.length;j<l;j++){if(shouldClean){options[j].selected=false}nameNormalized=normalizeName(name,delimiter,arrayIndexes);result[nameNormalized]=currNode}}else{if(/INPUT/i.test(currNode.nodeName)&&/CHECKBOX|RADIO/i.test(currNode.type)){if(shouldClean){currNode.checked=false}nameNormalized=normalizeName(name,delimiter,arrayIndexes);nameNormalized=nameNormalized.replace(_arrayItemRegexp,"[]");if(!result[nameNormalized]){result[nameNormalized]=[]}result[nameNormalized].push(currNode)}else{if(shouldClean){currNode.value=""}nameNormalized=normalizeName(name,delimiter,arrayIndexes);result[nameNormalized]=currNode}}}currNode=currNode.nextSibling}return result}function normalizeName(name,delimiter,arrayIndexes){var nameChunksNormalized=[],nameChunks=name.split(delimiter),currChunk,nameMatches,nameNormalized,currIndex,newIndex,i;name=name.replace(_arrayOfArraysRegexp,"[$1].[$2]");for(i=0;i<nameChunks.length;i++){currChunk=nameChunks[i];nameChunksNormalized.push(currChunk);nameMatches=currChunk.match(_lastIndexedArrayRegexp);if(nameMatches!=null){nameNormalized=nameChunksNormalized.join(delimiter);currIndex=nameNormalized.replace(_lastIndexedArrayRegexp,"$3");nameNormalized=nameNormalized.replace(_lastIndexedArrayRegexp,"$1");if(typeof(arrayIndexes[nameNormalized])=="undefined"){arrayIndexes[nameNormalized]={lastIndex:-1,indexes:{}}}if(currIndex==""||typeof arrayIndexes[nameNormalized].indexes[currIndex]=="undefined"){arrayIndexes[nameNormalized].lastIndex++;arrayIndexes[nameNormalized].indexes[currIndex]=arrayIndexes[nameNormalized].lastIndex}newIndex=arrayIndexes[nameNormalized].indexes[currIndex];nameChunksNormalized[nameChunksNormalized.length-1]=currChunk.replace(_lastIndexedArrayRegexp,"$1$2"+newIndex+"$4")}}nameNormalized=nameChunksNormalized.join(delimiter);nameNormalized=nameNormalized.replace("].[","][");return nameNormalized}function object2array(obj,lvl){var result=[],i,name;if(arguments.length==1){lvl=0}if(obj==null){result=[{name:"",value:null}]}else{if(typeof obj=="string"||typeof obj=="number"||typeof obj=="date"||typeof obj=="boolean"){result=[{name:"",value:obj}]}else{if(obj instanceof Array){for(i=0;i<obj.length;i++){name="["+i+"]";result=result.concat(getSubValues(obj[i],name,lvl+1))}}else{for(i in obj){name=i;result=result.concat(getSubValues(obj[i],name,lvl+1))}}}}return result}function getSubValues(subObj,name,lvl){var itemName;var result=[],tempResult=object2array(subObj,lvl+1),i,tempItem;for(i=0;i<tempResult.length;i++){itemName=name;if(_subArrayRegexp.test(tempResult[i].name)){itemName+=tempResult[i].name}else{if(_subObjectRegexp.test(tempResult[i].name)){itemName+="."+tempResult[i].name}}tempItem={name:itemName,value:tempResult[i].value};result.push(tempItem)}return result}return js2form}));
(function(root,factory){if(typeof define==="function"&&define.amd){define(factory)}else{root.form2js=factory()}}(this,function(){function form2js(rootNode,delimiter,skipEmpty,nodeCallback,useIdIfEmptyName){if(typeof skipEmpty=="undefined"||skipEmpty==null){skipEmpty=true}if(typeof delimiter=="undefined"||delimiter==null){delimiter="."}if(arguments.length<5){useIdIfEmptyName=false}rootNode=typeof rootNode=="string"?document.getElementById(rootNode):rootNode;var formValues=[],currNode,i=0;if(rootNode.constructor==Array||(typeof NodeList!="undefined"&&rootNode.constructor==NodeList)){while(currNode=rootNode[i++]){formValues=formValues.concat(getFormValues(currNode,nodeCallback,useIdIfEmptyName))}}else{formValues=getFormValues(rootNode,nodeCallback,useIdIfEmptyName)}return processNameValues(formValues,skipEmpty,delimiter)}function processNameValues(nameValues,skipEmpty,delimiter){var result={},arrays={},i,j,k,l,value,nameParts,currResult,arrNameFull,arrName,arrIdx,namePart,name,_nameParts;for(i=0;i<nameValues.length;i++){value=nameValues[i].value;if(skipEmpty&&(value===""||value===null)){continue}name=nameValues[i].name;_nameParts=name.split(delimiter);nameParts=[];currResult=result;arrNameFull="";for(j=0;j<_nameParts.length;j++){namePart=_nameParts[j].split("][");if(namePart.length>1){for(k=0;k<namePart.length;k++){if(k==0){namePart[k]=namePart[k]+"]"}else{if(k==namePart.length-1){namePart[k]="["+namePart[k]}else{namePart[k]="["+namePart[k]+"]"}}arrIdx=namePart[k].match(/([a-z_]+)?\[([a-z_][a-z0-9_]+?)\]/i);if(arrIdx){for(l=1;l<arrIdx.length;l++){if(arrIdx[l]){nameParts.push(arrIdx[l])}}}else{nameParts.push(namePart[k])}}}else{nameParts=nameParts.concat(namePart)}}for(j=0;j<nameParts.length;j++){namePart=nameParts[j];if(namePart.indexOf("[]")>-1&&j==nameParts.length-1){arrName=namePart.substr(0,namePart.indexOf("["));arrNameFull+=arrName;if(!currResult[arrName]){currResult[arrName]=[]}currResult[arrName].push(value)}else{if(namePart.indexOf("[")>-1){arrName=namePart.substr(0,namePart.indexOf("["));arrIdx=namePart.replace(/(^([a-z_]+)?\[)|(\]$)/gi,"");arrNameFull+="_"+arrName+"_"+arrIdx;if(!arrays[arrNameFull]){arrays[arrNameFull]={}}if(arrName!=""&&!currResult[arrName]){currResult[arrName]=[]}if(j==nameParts.length-1){if(arrName==""){currResult.push(value);arrays[arrNameFull][arrIdx]=currResult[currResult.length-1]}else{currResult[arrName].push(value);arrays[arrNameFull][arrIdx]=currResult[arrName][currResult[arrName].length-1]}}else{if(!arrays[arrNameFull][arrIdx]){if((/^[a-z_]+\[?/i).test(nameParts[j+1])){currResult[arrName].push({})}else{currResult[arrName].push([])}arrays[arrNameFull][arrIdx]=currResult[arrName][currResult[arrName].length-1]}}currResult=arrays[arrNameFull][arrIdx]}else{arrNameFull+=namePart;if(j<nameParts.length-1){if(!currResult[namePart]){currResult[namePart]={}}currResult=currResult[namePart]}else{currResult[namePart]=value}}}}}return result}function getFormValues(rootNode,nodeCallback,useIdIfEmptyName){var result=extractNodeValues(rootNode,nodeCallback,useIdIfEmptyName);return result.length>0?result:getSubFormValues(rootNode,nodeCallback,useIdIfEmptyName)}function getSubFormValues(rootNode,nodeCallback,useIdIfEmptyName){var result=[],currentNode=rootNode.firstChild;while(currentNode){result=result.concat(extractNodeValues(currentNode,nodeCallback,useIdIfEmptyName));currentNode=currentNode.nextSibling}return result}function extractNodeValues(node,nodeCallback,useIdIfEmptyName){var callbackResult,fieldValue,result,fieldName=getFieldName(node,useIdIfEmptyName);callbackResult=nodeCallback&&nodeCallback(node);if(callbackResult&&callbackResult.name){result=[callbackResult]}else{if(fieldName!=""&&node.nodeName.match(/INPUT|TEXTAREA/i)){fieldValue=getFieldValue(node);result=[{name:fieldName,value:fieldValue}]}else{if(fieldName!=""&&node.nodeName.match(/SELECT/i)){fieldValue=getFieldValue(node);result=[{name:fieldName.replace(/\[\]$/,""),value:fieldValue}]}else{result=getSubFormValues(node,nodeCallback,useIdIfEmptyName)}}}return result}function getFieldName(node,useIdIfEmptyName){if(node.name&&node.name!=""){return node.name}else{if(useIdIfEmptyName&&node.id&&node.id!=""){return node.id}else{return""}}}function getFieldValue(fieldNode){if(fieldNode.disabled){return null}switch(fieldNode.nodeName){case"INPUT":case"TEXTAREA":switch(fieldNode.type.toLowerCase()){case"radio":if(fieldNode.checked&&fieldNode.value==="false"){return false}case"checkbox":if(fieldNode.checked&&fieldNode.value==="true"){return true}if(!fieldNode.checked&&fieldNode.value==="true"){return false}if(fieldNode.checked){return fieldNode.value}break;case"button":case"reset":case"submit":case"image":return"";break;default:return fieldNode.value;break}break;case"SELECT":return getSelectedOptionValue(fieldNode);break;default:break}return null}function getSelectedOptionValue(selectNode){var multiple=selectNode.multiple,result=[],options,i,l;if(!multiple){return selectNode.value}for(options=selectNode.getElementsByTagName("option"),i=0,l=options.length;i<l;i++){if(options[i].selected){result.push(options[i].value)}}return result}return form2js}));

/*
 * jQuery MultiSelect UI Widget 1.14pre
 * Copyright (c) 2012 Eric Hynds
 * @link http://www.erichynds.com/jquery/jquery-ui-multiselect-widget/
 * @license MIT and GPL 3.0
 */
(function($,undefined){var multiselectID=0;var $doc=$(document);$.widget("ech.multiselect",{options:{header:true,height:175,minWidth:225,classes:"",checkAllText:"Check all",uncheckAllText:"Uncheck all",noneSelectedText:"Select options",selectedText:"# selected",selectedList:0,show:null,hide:null,autoOpen:false,multiple:true,position:{},appendTo:"body"},_create:function(){var el=this.element.hide();var o=this.options;this.speed=$.fx.speeds._default;this._isOpen=false;this._namespaceID=this.eventNamespace||("multiselect"+multiselectID);var button=(this.button=$('<span><span class="ui-icon ui-icon-triangle-1-s"></span></span>')).addClass("ui-multiselect ui-widget ui-state-default ui-corner-all").addClass(o.classes).attr({title:el.attr("title"),"aria-haspopup":true,tabIndex:el.attr("tabIndex")}).insertAfter(el),buttonlabel=(this.buttonlabel=$("<span />")).html(o.noneSelectedText).appendTo(button),menu=(this.menu=$("<div />")).addClass("ui-multiselect-menu ui-widget ui-widget-content ui-corner-all").addClass(o.classes).appendTo($(o.appendTo)),header=(this.header=$("<div />")).addClass("ui-widget-header ui-corner-all ui-multiselect-header ui-helper-clearfix").appendTo(menu),headerLinkContainer=(this.headerLinkContainer=$("<ul />")).addClass("ui-helper-reset").html(function(){if(o.header===true){return'<li><a class="ui-multiselect-all" href="#"><span class="ui-icon ui-icon-check"></span><span>'+o.checkAllText+'</span></a></li><li><a class="ui-multiselect-none" href="#"><span class="ui-icon ui-icon-closethick"></span><span>'+o.uncheckAllText+"</span></a></li>"}else{if(typeof o.header==="string"){return"<li>"+o.header+"</li>"}else{return""}}}).append('<li class="ui-multiselect-close"><a href="#" class="ui-multiselect-close"><span class="ui-icon ui-icon-circle-close"></span></a></li>').appendTo(header),checkboxContainer=(this.checkboxContainer=$("<ul />")).addClass("ui-multiselect-checkboxes ui-helper-reset").appendTo(menu);this._bindEvents();this.refresh(true);if(!o.multiple){menu.addClass("ui-multiselect-single")}multiselectID++},_init:function(){if(this.options.header===false){this.header.hide()}if(!this.options.multiple){this.headerLinkContainer.find(".ui-multiselect-all, .ui-multiselect-none").hide()}if(this.options.autoOpen){this.open()}if(this.element.is(":disabled")){this.disable()}},refresh:function(init){var el=this.element;var o=this.options;var menu=this.menu;var checkboxContainer=this.checkboxContainer;var optgroups=[];var html="";var id=el.attr("id")||multiselectID++;el.find("option").each(function(i){var $this=$(this);var parent=this.parentNode;var description=this.innerHTML;var title=this.title;var value=this.value;var inputID="ui-multiselect-"+(this.id||id+"-option-"+i);var isDisabled=this.disabled;var isSelected=this.selected;var labelClasses=["ui-corner-all"];var liClasses=(isDisabled?"ui-multiselect-disabled ":" ")+this.className;var optLabel;if(parent.tagName==="OPTGROUP"){optLabel=parent.getAttribute("label");if($.inArray(optLabel,optgroups)===-1){html+='<li class="ui-multiselect-optgroup-label '+parent.className+'"><a href="#">'+optLabel+"</a></li>";optgroups.push(optLabel)}}if(isDisabled){labelClasses.push("ui-state-disabled")}if(isSelected&&!o.multiple){labelClasses.push("ui-state-active")}html+='<li class="'+liClasses+'">';html+='<label for="'+inputID+'" title="'+title+'" class="'+labelClasses.join(" ")+'">';html+='<input id="'+inputID+'" name="multiselect_'+id+'" type="'+(o.multiple?"checkbox":"radio")+'" value="'+value+'" title="'+title+'"';if(isSelected){html+=' checked="checked"';html+=' aria-selected="true"'}if(isDisabled){html+=' disabled="disabled"';html+=' aria-disabled="true"'}html+=" /><span>"+description+"</span></label></li>"});checkboxContainer.html(html);this.labels=menu.find("label");this.inputs=this.labels.children("input");this._setButtonWidth();this._setMenuWidth();this.button[0].defaultValue=this.update();if(!init){this._trigger("refresh")}},update:function(){var o=this.options;var $inputs=this.inputs;var $checked=$inputs.filter(":checked");var numChecked=$checked.length;var value;if(numChecked===0){value=o.noneSelectedText}else{if($.isFunction(o.selectedText)){value=o.selectedText.call(this,numChecked,$inputs.length,$checked.get())}else{if(/\d/.test(o.selectedList)&&o.selectedList>0&&numChecked<=o.selectedList){value=$checked.map(function(){return $(this).next().html()}).get().join(", ")}else{value=o.selectedText.replace("#",numChecked).replace("#",$inputs.length)}}}this._setButtonValue(value);return value},_setButtonValue:function(value){this.buttonlabel.text(value)},_bindEvents:function(){var self=this;var button=this.button;function clickHandler(){self[self._isOpen?"close":"open"]();return false}button.find("span").bind("click.multiselect",clickHandler);button.bind({click:clickHandler,keypress:function(e){switch(e.which){case 27:case 38:case 37:self.close();break;case 39:case 40:self.open();break}},mouseenter:function(){if(!button.hasClass("ui-state-disabled")){$(this).addClass("ui-state-hover")}},mouseleave:function(){$(this).removeClass("ui-state-hover")},focus:function(){if(!button.hasClass("ui-state-disabled")){$(this).addClass("ui-state-focus")}},blur:function(){$(this).removeClass("ui-state-focus")}});this.header.delegate("a","click.multiselect",function(e){if($(this).hasClass("ui-multiselect-close")){self.close()}else{self[$(this).hasClass("ui-multiselect-all")?"checkAll":"uncheckAll"]()}e.preventDefault()});this.menu.delegate("li.ui-multiselect-optgroup-label a","click.multiselect",function(e){e.preventDefault();var $this=$(this);var $inputs=$this.parent().nextUntil("li.ui-multiselect-optgroup-label").find("input:visible:not(:disabled)");var nodes=$inputs.get();var label=$this.parent().text();if(self._trigger("beforeoptgrouptoggle",e,{inputs:nodes,label:label})===false){return}self._toggleChecked($inputs.filter(":checked").length!==$inputs.length,$inputs);self._trigger("optgrouptoggle",e,{inputs:nodes,label:label,checked:nodes[0].checked})}).delegate("label","mouseenter.multiselect",function(){if(!$(this).hasClass("ui-state-disabled")){self.labels.removeClass("ui-state-hover");$(this).addClass("ui-state-hover").find("input").focus()}}).delegate("label","keydown.multiselect",function(e){e.preventDefault();switch(e.which){case 9:case 27:self.close();break;case 38:case 40:case 37:case 39:self._traverse(e.which,this);break;case 13:$(this).find("input")[0].click();break}}).delegate('input[type="checkbox"], input[type="radio"]',"click.multiselect",function(e){var $this=$(this);var val=this.value;var checked=this.checked;var tags=self.element.find("option");if(this.disabled||self._trigger("click",e,{value:val,text:this.title,checked:checked})===false){e.preventDefault();return}$this.focus();$this.attr("aria-selected",checked);tags.each(function(){if(this.value===val){this.selected=checked}else{if(!self.options.multiple){this.selected=false}}});if(!self.options.multiple){self.labels.removeClass("ui-state-active");$this.closest("label").toggleClass("ui-state-active",checked);self.close()}self.element.trigger("change");setTimeout($.proxy(self.update,self),10)});$doc.bind("mousedown."+this._namespaceID,function(event){var target=event.target;if(self._isOpen&&target!==self.button[0]&&target!==self.menu[0]&&!$.contains(self.menu[0],target)&&!$.contains(self.button[0],target)){self.close()}});$(this.element[0].form).bind("reset.multiselect",function(){setTimeout($.proxy(self.refresh,self),10)})},_setButtonWidth:function(){var width=this.element.outerWidth();var o=this.options;if(/\d/.test(o.minWidth)&&width<o.minWidth){width=o.minWidth}this.button.outerWidth(width)},_setMenuWidth:function(){var m=this.menu;m.outerWidth(this.button.outerWidth())},_traverse:function(which,start){var $start=$(start);var moveToLast=which===38||which===37;var $next=$start.parent()[moveToLast?"prevAll":"nextAll"]("li:not(.ui-multiselect-disabled, .ui-multiselect-optgroup-label)").first();if(!$next.length){var $container=this.menu.find("ul").last();this.menu.find("label")[moveToLast?"last":"first"]().trigger("mouseover");$container.scrollTop(moveToLast?$container.height():0)}else{$next.find("label").trigger("mouseover")}},_toggleState:function(prop,flag){return function(){if(!this.disabled){this[prop]=flag}if(flag){this.setAttribute("aria-selected",true)}else{this.removeAttribute("aria-selected")}}},_toggleChecked:function(flag,group){var $inputs=(group&&group.length)?group:this.inputs;var self=this;$inputs.each(this._toggleState("checked",flag));$inputs.eq(0).focus();this.update();var values=$inputs.map(function(){return this.value}).get();this.element.find("option").each(function(){if(!this.disabled&&$.inArray(this.value,values)>-1){self._toggleState("selected",flag).call(this)}});if($inputs.length){this.element.trigger("change")}},_toggleDisabled:function(flag){this.button.attr({disabled:flag,"aria-disabled":flag})[flag?"addClass":"removeClass"]("ui-state-disabled");var inputs=this.menu.find("input");var key="ech-multiselect-disabled";if(flag){inputs=inputs.filter(":enabled").data(key,true)}else{inputs=inputs.filter(function(){return $.data(this,key)===true}).removeData(key)}inputs.attr({disabled:flag,"arial-disabled":flag}).parent()[flag?"addClass":"removeClass"]("ui-state-disabled");this.element.attr({disabled:flag,"aria-disabled":flag})},open:function(e){var self=this;var button=this.button;var menu=this.menu;var speed=this.speed;var o=this.options;var args=[];if(this._trigger("beforeopen")===false||button.hasClass("ui-state-disabled")||this._isOpen){return}var $container=menu.find("ul").last();var effect=o.show;if($.isArray(o.show)){effect=o.show[0];speed=o.show[1]||self.speed}if(effect){args=[effect,speed]}$container.scrollTop(0).height(o.height);this.position();$.fn.show.apply(menu,args);this.labels.filter(":not(.ui-state-disabled)").eq(0).trigger("mouseover").trigger("mouseenter").find("input").trigger("focus");button.addClass("ui-state-active");this._isOpen=true;this._trigger("open")},close:function(){if(this._trigger("beforeclose")===false){return}var o=this.options;var effect=o.hide;var speed=this.speed;var args=[];if($.isArray(o.hide)){effect=o.hide[0];speed=o.hide[1]||this.speed}if(effect){args=[effect,speed]}$.fn.hide.apply(this.menu,args);this.button.removeClass("ui-state-active").trigger("blur").trigger("mouseleave");this._isOpen=false;this._trigger("close")},enable:function(){this._toggleDisabled(false)},disable:function(){this._toggleDisabled(true)},checkAll:function(e){this._toggleChecked(true);this._trigger("checkAll")},uncheckAll:function(){this._toggleChecked(false);this._trigger("uncheckAll")},getChecked:function(){return this.menu.find("input").filter(":checked")},destroy:function(){$.Widget.prototype.destroy.call(this);$doc.unbind(this._namespaceID);this.button.remove();this.menu.remove();this.element.show();return this},isOpen:function(){return this._isOpen},widget:function(){return this.menu},getButton:function(){return this.button},position:function(){var o=this.options;if($.ui.position&&!$.isEmptyObject(o.position)){o.position.of=o.position.of||this.button;this.menu.show().position(o.position).hide()}else{var pos=this.button.offset();this.menu.css({top:pos.top+this.button.outerHeight(),left:pos.left})}},_setOption:function(key,value){var menu=this.menu;switch(key){case"header":menu.find("div.ui-multiselect-header")[value?"show":"hide"]();break;case"checkAllText":menu.find("a.ui-multiselect-all span").eq(-1).text(value);break;case"uncheckAllText":menu.find("a.ui-multiselect-none span").eq(-1).text(value);break;case"height":menu.find("ul").last().height(parseInt(value,10));break;case"minWidth":this.options[key]=parseInt(value,10);this._setButtonWidth();this._setMenuWidth();break;case"selectedText":case"selectedList":case"noneSelectedText":this.options[key]=value;this.update();break;case"classes":menu.add(this.button).removeClass(this.options.classes).addClass(value);break;case"multiple":menu.toggleClass("ui-multiselect-single",!value);this.options.multiple=value;this.element[0].multiple=value;this.refresh();break;case"position":this.position()}$.Widget.prototype._setOption.apply(this,arguments)}})})(jQuery);

/*!
 * iButton jQuery Plug-in
 * Copyright 2011 Giva, Inc. (http://www.givainc.com/labs/)
 *
 * Apache License, Version 2.0 http://www.apache.org/licenses/LICENSE-2.0
 *
 * Date: 2011-07-26
 * Rev:  1.0.03
 */
(function($){$.iButton={version:"1.0.03",setDefaults:function(options){$.extend(defaults,options)}};$.fn.iButton=function(options){var method=typeof arguments[0]=="string"&&arguments[0];var args=method&&Array.prototype.slice.call(arguments,1)||arguments;var self=(this.length==0)?null:$.data(this[0],"iButton");if(self&&method&&this.length){if(method.toLowerCase()=="object"){return self}else{if(self[method]){var result;this.each(function(i){var r=$.data(this,"iButton")[method].apply(self,args);if(i==0&&r){if(!!r.jquery){result=$([]).add(r)}else{result=r;return false}}else{if(!!r&&!!r.jquery){result=result.add(r)}}});return result||this}else{return this}}}else{return this.each(function(){new iButton(this,options)})}};var counter=0;$.browser.iphone=(navigator.userAgent.toLowerCase().indexOf("iphone")>-1);var iButton=function(input,options){var self=this,$input=$(input),id=++counter,disabled=false,width={},mouse={dragging:false,clicked:null},dragStart={position:null,offset:null,time:null},options=$.extend({},defaults,options,(!!$.metadata?$input.metadata():{})),bDefaultLabelsUsed=(options.labelOn==ON&&options.labelOff==OFF),allow=":checkbox, :radio";if(!$input.is(allow)){return $input.find(allow).iButton(options)}else{if($.data($input[0],"iButton")){return}}$.data($input[0],"iButton",self);if(options.resizeHandle=="auto"){options.resizeHandle=!bDefaultLabelsUsed}if(options.resizeContainer=="auto"){options.resizeContainer=!bDefaultLabelsUsed}this.toggle=function(t){var toggle=(arguments.length>0)?t:!$input[0].checked;$input.attr("checked",toggle).trigger("change")};this.disable=function(t){var toggle=(arguments.length>0)?t:!disabled;disabled=toggle;$input.attr("disabled",toggle);$container[toggle?"addClass":"removeClass"](options.classDisabled);if($.isFunction(options.disable)){options.disable.apply(self,[disabled,$input,options])}};this.repaint=function(){positionHandle()};this.destroy=function(){$([$input[0],$container[0]]).unbind(".iButton");$(document).unbind(".iButton_"+id);$container.after($input).remove();$.data($input[0],"iButton",null);if($.isFunction(options.destroy)){options.destroy.apply(self,[$input,options])}};$input.wrap('<div class="'+$.trim(options.classContainer+" "+options.className)+'" />').after('<div class="'+options.classHandle+'"><div class="'+options.classHandleRight+'"><div class="'+options.classHandleMiddle+'" /></div></div><div class="'+options.classLabelsContainer+'">  <div class="'+options.classLabelOff+'"><span><label>'+options.labelOff+'</label></span></div>  <div class="'+options.classLabelOn+'"><span><label>'+options.labelOn+"</label></span></div></div>");var $container=$input.parent(),$handle=$input.siblings("."+options.classHandle),$labels=$input.siblings("."+options.classLabelsContainer),$offlabel=$labels.children("."+options.classLabelOff),$offspan=$offlabel.children("span"),$onlabel=$labels.children("."+options.classLabelOn),$onspan=$onlabel.children("span");if(options.resizeHandle||options.resizeContainer){width.onspan=$onspan.outerWidth();width.offspan=$offspan.outerWidth()}if(options.resizeHandle){width.handle=Math.min(width.onspan,width.offspan);$handle.css("width",width.handle)}else{width.handle=$handle.width()}if(options.resizeContainer){width.container=$container.width();width.labels=Math.max(width.onspan,width.offspan);$container.css("width",$container.outerWidth());$labels.css("width",width.container)}else{width.container=$container.outerWidth();width.labels=$labels.outerWidth()}$onspan.css("padding-left",width.handle/2);$offspan.css("padding-right",width.handle/2);var handleRight=width.container-width.handle;var positionHandle=function(animate){var checked=$input[0].checked,x=(checked)?handleRight:0,animate=(arguments.length>0)?arguments[0]:true;if(animate&&options.enableFx){$handle.stop().animate({left:x},options.duration,options.easing);$onlabel.stop().animate({right:width.labels-width.handle/2-x+2},options.duration,options.easing);$offlabel.stop().animate({left:x+width.handle/2-2},options.duration,options.easing)}else{$handle.css("left",x);$onlabel.css("right",width.labels-width.handle/2-x+2);$offlabel.css("left",x+width.handle/2-2)}};positionHandle(false);var getDragPos=function(e){return e.pageX||((e.originalEvent.changedTouches)?e.originalEvent.changedTouches[0].pageX:0)};$container.bind("mousedown.iButton touchstart.iButton",function(e){if($(e.target).is(allow)||disabled||(!options.allowRadioUncheck&&$input.is(":radio:checked"))){return}e.preventDefault();mouse.clicked=$handle;dragStart.position=getDragPos(e);dragStart.offset=dragStart.position-(parseInt($handle.css("left"),10)||0);dragStart.time=(new Date()).getTime();return false});if(options.enableDrag){$(document).bind("mousemove.iButton_"+id+" touchmove.iButton_"+id,function(e){if(mouse.clicked!=$handle){return}e.preventDefault();var x=getDragPos(e);if(x!=dragStart.offset){mouse.dragging=true;$container.addClass(options.classHandleActive)}var pct=Math.min(1,Math.max(0,(x-dragStart.offset)/handleRight));$handle.css("left",pct*handleRight);$onlabel.css("right",width.labels-pct*handleRight-width.handle/2+2);$offlabel.css("left",pct*handleRight+width.handle/2-2);return false})}$(document).bind("mouseup.iButton_"+id+" touchend.iButton_"+id,function(e){if(mouse.clicked!=$handle){return false}e.preventDefault();var changed=true;if(!mouse.dragging||(((new Date()).getTime()-dragStart.time)<options.clickOffset)){var checked=$input[0].checked;$input.attr("checked",!checked);if($.isFunction(options.click)){options.click.apply(self,[!checked,$input,options])}}else{var x=getDragPos(e);var pct=(x-dragStart.offset)/handleRight;var checked=(pct>=0.5);if($input[0].checked==checked){changed=false}$input.attr("checked",checked)}$container.removeClass(options.classHandleActive);mouse.clicked=null;mouse.dragging=null;if(changed){$input.trigger("change")}else{positionHandle()}return false});$input.bind("change.iButton",function(){positionHandle();if($input.is(":radio")){var el=$input[0];var $radio=$(el.form?el.form[el.name]:":radio[name="+el.name+"]");$radio.filter(":not(:checked)").iButton("repaint")}if($.isFunction(options.change)){options.change.apply(self,[$input,options])}}).bind("focus.iButton",function(){$container.addClass(options.classFocus)}).bind("blur.iButton",function(){$container.removeClass(options.classFocus)});if($.isFunction(options.click)){$input.bind("click.iButton",function(){options.click.apply(self,[$input[0].checked,$input,options])})}if($input.is(":disabled")){this.disable(true)}if($.browser.msie){$container.find("*").andSelf().attr("unselectable","on");$input.bind("click.iButton",function(){$input.triggerHandler("change.iButton")})}if($.isFunction(options.init)){options.init.apply(self,[$input,options])}};var defaults={duration:200,easing:"swing",labelOn:"On",labelOff:"Off",resizeHandle:"auto",resizeContainer:"auto",enableDrag:true,enableFx:true,allowRadioUncheck:false,clickOffset:120,className:"",classContainer:"ibutton-container",classDisabled:"ibutton-disabled",classFocus:"ibutton-focus",classLabelsContainer:"ibutton-labels-container",classLabelOn:"ibutton-label-on",classLabelOff:"ibutton-label-off",classHandle:"ibutton-handle",classHandleMiddle:"ibutton-handle-middle",classHandleRight:"ibutton-handle-right",classHandleActive:"ibutton-active-handle",init:null,change:null,click:null,disable:null,destroy:null},ON=defaults.labelOn,OFF=defaults.labelOff})(jQuery);

/*
 * jQuery URL parser v.1.0.4
 * Copyright (C) 2012, Thomas James Bonner (tom.bonner@gmail.com) https://github.com/tombonner/jurlp
 *
 * jQuery plugin for parsing, manipulating, filtering and monitoring URLs in href and src attributes within arbitrary elements (including document.location.href), as well as creating anchor elements from URLs found in HTML/text.
 * Authors: Thomas James Bonner (tom.bonner@gmail.com). Yonas Sandbæk (seltar@gmail.com)
 *
 * Licensed under the MIT License - http://www.opensource.org/licenses/mit-license.php
 */
(function(w){var q=/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/;var I={};var g="";var h=function(){var Q=L.apply(this);if(Q==""){if(this.get(0)==w(document).get(0)){Q=document.location.href}else{if(this.is("[href]")){Q=this.attr("href")}else{if(this.is("[src]")){Q=this.attr("src")}}}if(Q!=""){Q=D(Q);this.data("href",Q)}}};var n=function(){if(this.get(0)!=w(document).get(0)&&this.attr("href")==null&&this.attr("src")==null){if(this.html()!=null&&this.hasClass("jurlp-span")==false){var W=[];var V=false;var S="";var R=/((((mailto|spotify|skype)\:([a-zA-Z0-9\.\-\:@\?\=\%]*))|((ftp|git|irc|ircs|irc6|pop|rss|ssh|svn|udp|feed|imap|ldap|nntp|rtmp|sftp|snmp|xmpp|http|https|telnet|ventrilo|webcal|view\-source)\:[\/]{2})?(([a-zA-Z0-9\.\-]+)\:([a-zA-Z0-9\.&;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|xn--0zwm56d|xn--11b5bs3a9aj6g|xn--80akhbyknj4f|xn--9t4b11yi5a|xn--deba0ad|xn--g6w251d|xn--hgbk6aj7f53bba|xn--hlcj6aya9esc7a|xn--jxalpdlp|xn--kgbechtv|xn--zckzah|[a-zA-Z]{2}))(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\?\'\\\+&;%\$\=~_\-]+)?(#\w*)?)*))/i;var U=this.html();while(S=R.exec(U)){U=U.replace(S[0],"$"+W.length);W.push(S[0]);V=true}for(var T=0;T<W.length;T++){var X=U.indexOf("$"+T);var Q=U.substring(X-6,X-1);if(Q=="href="||Q==" src="||U.substring(X-1,X)==">"){U=U.replace("$"+T,W[T])}else{U=U.replace("$"+T,'<a href="[url]" class="jurlp-no-watch">[url]</a>'.replace(/\[url\]/g,W[T]))}}if(V!=false){this.addClass("jurlp-span");this.html(U);return this.find("a[href]").each(function(){var Y=L.apply(w(this));O.apply(w(this),[Y])})}}}return null};var J=function(Q,R){if(this.is("["+Q+"]")!=false){if(this.data("original-"+Q)==null){this.data("original-"+Q,this.attr(Q))}this.attr(Q,R)}};var P=function(Q){if(this.data("original-"+Q)!=null){this.attr(Q,this.data("original-"+Q));this.removeData("original-"+Q)}};var j=function(){this.removeData("href");P.apply(this,["href"]);P.apply(this,["src"]);this.removeData("jurlp-no-watch");this.removeData("is-watched");y.unwatch.apply(this)};var L=function(){return this.href||this.data("href")||this.attr("href")||this.attr("src")||""};var v=function(Q,R){O.apply(this,[p(L.apply(this),Q,R)])};var N=function(Q){v.apply(this,[Q[0],Q[1]])};var O=function(Q){if(typeof Q=="object"){Q=o(Q)}Q=D(Q);if(this.href!=null){this.href=Q;return}if(this.get(0)==w(document).get(0)){this.data("href",Q)}else{J.apply(this,["href",Q]);J.apply(this,["src",Q])}};var H=function(Q){if(Q==null){return{scheme:"",user:"",password:"",host:"",port:"",path:"",query:"",fragment:""}}var V={user:"",password:""};if(Q.substring(0,2)=="//"){Q="http:"+Q}if(Q!=""&&Q.indexOf("://")==-1){Q="http://"+Q}if(Q.indexOf("@")!=-1){var T=Q.match(q);if(T[4]){V.user=T[4]}if(T[5]){V.password=T[5]}}var X=document.createElement("a");X.href=Q;try{var Z=X.protocol}catch(U){if(U.number==-2146697202){var W="";if(V.user!=""){W+=V.user;if(V.password!=""){W+=":"+V.password}W+="@";X.href=Q.replace(W,"")}}}var Y=X.protocol;if(X.protocol.indexOf("//")==-1){Y+="//"}var R=X.pathname;if(R[0]!="/"){R="/"+R}var S=X.port+"";if((S=="21"&&Q.indexOf(":21")==-1)||(S=="80"&&Q.indexOf(":80")==-1)||(S=="443"&&Q.indexOf(":443")==-1)||S=="0"){S=""}return{scheme:Y,user:V.user,password:V.password,host:X.hostname,port:S,path:R,query:X.search,fragment:X.hash}};var o=function(Q){var R=Q.scheme;if(Q.user!=null&&Q.user!=""){R+=Q.user;if(Q.password!=null&&Q.password!=""){R+=":"+Q.password}R+="@"}return R+Q.host+(Q.port!=""?":"+Q.port:"")+Q.path+Q.query+Q.fragment};var D=function(Q){return e.parse(Q).toString()};var K=function(){return o(this)};var p=function(Q,R,T){var S=e.parse(Q);S[R]=T;return o(S)};var l=function(Q){if(typeof Q=="string"){return e.parse(Q)}return Q};var C=function(Q){return l(Q).fragment};var c=function(Q){var R=l(Q).query;if(R[0]=="?"){return R.slice(1)}return R};var z=function(R){var S={};var Q=u(L.apply(this));if(typeof R=="string"){if(R[0]=="?"){Q={};R=R.substring(1)}S=r(R)}else{S=R}if(w.isEmptyObject(S)==false){S=w.extend(Q,S)}else{Q={}}v.apply(this,["query",E.apply(S)])};var r=function(T){var Q={};if(T!=""){var S=T.split("&");for(var R=0;R<S.length;R++){var U=S[R].split("=");Q[U[0]]=U[1]}}return Q};var u=function(Q){return r(c(Q))};var E=function(){var Q="";for(var R in this){if(R!="toString"&&this[R]!=null){Q+="&"+R+"="+this[R]}}if(Q[0]=="&"){Q="?"+Q.slice(1)}return Q};var k=function(Q){var R=l(Q).path;if(R[0]=="/"){return R.slice(1)}return R};var F=function(R){var Q=k(R);if(Q!=""){return Q.split("/")}return[]};var M=function(V){var S="";var U=l(L.apply(this)).path.split("/");var R=V.split("/");var T=0;if(R[0]==""){U=[];T++}U.splice(0,1);for(var Q=R.length;T<Q;T++){if(R[T]==".."){if(U.length>0){U.splice(U.length-1,1)}}else{if(R[T]=="."){}else{U.push(R[T])}}}v.apply(this,["path",A.apply(U)])};var A=function(){if(this.length>0){return"/"+this.join("/")}return"/"};var d=function(Q){return l(Q).port};var s=function(Q){return l(Q).host};var x=function(Q){return l(Q).password};var G=function(Q){return l(Q).user};var a=function(Q){return l(Q).scheme};var t=function(Q,S,R){if(Q.data("is-watched")==true){return}if(I[g]){I[g].push([S,R])}};var f=function(R,Q){if(this.href!=null){R.apply(this,[Q]);return this}t(this,R,[Q]);return this.each(function(){R.apply(w(this),[Q])})};var i=function(S,R){if(this.href!=null){return S.apply(this,[R])}var Q=[];this.each(function(){Q.push(S.apply(w(this),[R]))});return Q};var b=function(R,S,Q){if(Q.length==0){return R.apply(this)}return S.apply(this,Q)};var m=function(Q){if(y[Q]!=null){return y[Q].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof Q=="object"||Q==null){return y.initialise.apply(this,Array.prototype.slice.call(arguments,1))}}return this};var e={toString:{http:function(){return o(this)},mailto:function(){this.password="";this.path="";this.port="";return o(this)},javascript:function(){return"javascript:"+this.javascript},generic:function(){return this.scheme+this.url}},parsers:{http:function(Q){return w.extend(H(Q),{toString:e.toString.http})},mailto:function(Q){return w.extend(H(Q.substring(7)),{scheme:"mailto:",toString:e.toString.mailto})},javascript:function(Q){return w.extend(H(document.location.href),{javascript:Q.substring(11),toString:e.toString.javascript})},generic:function(Q,R){if(R.substring(0,2)=="//"){return w.extend(H(R.substring(2)),{scheme:Q+"://",toString:e.toString.http})}return{scheme:Q+":",url:R,toString:e.toString.generic}}},parse:function(R){if(typeof R!="string"){return R}var S=R.indexOf(":");if(S!=-1){var Q=R.substring(0,S).toLowerCase();if(this.parsers[Q]!=null){return this.parsers[Q](R)}return this.parsers.generic(Q,R.substring(S+1))}return this.parsers.http(R)}};var B={getUrl:function(){return i.apply(this,[L,null])},setUrl:function(Q){O.apply(this,[Q])},parseUrl:function(){return i.apply(this,[function(){return e.parse(L.apply(this))},null])},getFragment:function(){return i.apply(this,[function(){return C(L.apply(this))},null])},setFragment:function(Q){if(Q[0]!="#"){Q="#"+Q}return f.apply(this,[N,["fragment",Q]])},getQuery:function(){return i.apply(this,[function(){return w.extend(u(L.apply(this)),{toString:E})},null])},setQuery:function(Q){return f.apply(this,[z,Q])},getPath:function(){return i.apply(this,[function(){return w.extend(F(L.apply(this)),{toString:A})},null])},setPath:function(Q){return f.apply(this,[M,Q])},getPort:function(){return i.apply(this,[function(){return d(L.apply(this))},null])},setPort:function(Q){return f.apply(this,[N,["port",Q]])},getHost:function(){return i.apply(this,[function(){return s(L.apply(this))},null])},setHost:function(Q){return f.apply(this,[N,["host",Q]])},getPassword:function(){return i.apply(this,[function(){return x(L.apply(this))},null])},setPassword:function(Q){return f.apply(this,[N,["password",Q]])},getUser:function(){return i.apply(this,[function(){return G(L.apply(this))},null])},setUser:function(Q){return f.apply(this,[N,["user",Q]])},getScheme:function(){return i.apply(this,[function(){return a(L.apply(this))},null])},setScheme:function(Q){return f.apply(this,[N,["scheme",Q]])},filters:{"=":function(R,Q){if(R==Q){return true}return false},"!=":function(R,Q){if(R!=Q){return true}return false},"<":function(R,Q){if(R<Q){return true}return false},">":function(R,Q){if(R>Q){return true}return false},"<=":function(R,Q){if(R<=Q){return true}return false},">=":function(R,Q){if(R>=Q){return true}return false},"*=":function(R,Q){if(R.indexOf(Q)!=-1){return true}return false},"^=":function(R,Q){if(R.length>=Q.length){if(R.substring(0,Q.length)==Q){return true}}return false},"$=":function(R,Q){if(R.length>=Q.length){if(R.substring(R.length-Q.length)==Q){return true}}return false},regex:function(R,Q){return R.match(Q)}}};var y={url:function(Q){return b.apply(this,[B.parseUrl,B.setUrl,arguments])},fragment:function(Q){return b.apply(this,[B.getFragment,B.setFragment,arguments])},query:function(Q){return b.apply(this,[B.getQuery,B.setQuery,arguments])},path:function(Q){return b.apply(this,[B.getPath,B.setPath,arguments])},port:function(Q){return b.apply(this,[B.getPort,B.setPort,arguments])},host:function(Q){return b.apply(this,[B.getHost,B.setHost,arguments])},password:function(Q){return b.apply(this,[B.getPassword,B.setPassword,arguments])},user:function(Q){return b.apply(this,[B.getUser,B.setUser,arguments])},scheme:function(Q){return b.apply(this,[B.getScheme,B.setScheme,arguments])},initialise:function(){var Q=this;var R=[];R=n.apply(w(this));if(R!=null){return f.apply(this.filter(function(){return w(this).get(0)!=w(Q).get(0)}).add(R),[h])}return f.apply(Q,[h])},restore:function(){return f.apply(this,[j])},"goto":function(){document.location.href=L.apply(this)},proxy:function(Q,T){var S=L.apply(this);var R={};O.apply(this,[o(l(Q))]);R[T]=S;B.setQuery.apply(this,[R])},watch:function(S){var Q=this.selector;if(I[g]==null){I[g]=[];w(document).bind("DOMNodeInserted",function R(X){if(I[Q]==null){w(document).unbind("DOMNodeInserted",R);return}var Y=w(X.target).filter(Q);if(Y.get(0)==null){Y=w(X.target).find(Q)}if(Y.length>0&&Y.is(".jurlp-no-watch")==false){var V=false;Y.data("is-watched",true);for(var W=0,T=I[Q].length;W<T;W++){var U=I[Q][W][0].apply(Y,I[Q][W][1]);if(U!=null&&U.length==0){V=true;break}}if(V==false&&typeof S=="function"){Y.each(function(){S(w(this),Q)})}}})}return this},unwatch:function(){I[this.selector]=null},filter:function(S,Q,T){var R=e.parse(L.apply(this));if(Q=="=="){Q="="}if((S=="url"||R[S]!=null)&&B.filters[Q]!=null){t(this,y.filter,[S,Q,T]);return this.filter(function(){var U=L.apply(w(this));var V="";if(S!="url"){V=e.parse(U)[S]}else{V=U}if(S=="port"){V=parseInt(V,10);T=parseInt(T,10)}return B.filters[Q].apply(w(this),[V,T])})}return this},"interface":function(){return y}};w.fn.jurlp=function(Q){if(this.selector.indexOf(".filter")==-1){g=this.selector}f.apply(this,[h]);return m.apply(this,arguments)};w.jurlp=function(Q){return{href:Q||document.location.href,url:y.url,scheme:y.scheme,user:y.user,password:y.password,host:y.host,port:y.port,path:y.path,query:y.query,fragment:y.fragment,proxy:y.proxy,"goto":y["goto"],watch:function(S){var R=this.host();return w('[href*="'+R+'"],[src*="'+R+'"]').jurlp("watch",S)},unwatch:function(){var R=this.host();return w('[href*="'+R+'"],[src*="'+R+'"]').jurlp("unwatch")}}}})(jQuery);

/*
 * jQuery UI Nested Sortable
 * v 1.3.5 / 21 jun 2012
 * https://github.com/mjsarfatti/nestedSortable
 *
 * Depends on:
 *   jquery.ui.sortable.js 1.8+
 *
 * Copyright (c) 2010-2012 Manuele J Sarfatti
 * Licensed under the MIT Licens - http://www.opensource.org/licenses/mit-license.php
 */

(function($){$.widget("mjs.nestedSortable",$.extend({},$.ui.sortable.prototype,{options:{tabSize:20,disableNesting:"mjs-nestedSortable-no-nesting",errorClass:"mjs-nestedSortable-error",doNotClear:false,listType:"ol",maxLevels:0,protectRoot:false,rootID:null,rtl:false,isAllowed:function(item,parent){return true}},_create:function(){this.element.data("sortable",this.element.data("nestedSortable"));if(!this.element.is(this.options.listType)){throw new Error("nestedSortable: Please check the listType option is set to your actual list type")}return $.ui.sortable.prototype._create.apply(this,arguments)},destroy:function(){this.element.removeData("nestedSortable").unbind(".nestedSortable");return $.ui.sortable.prototype.destroy.apply(this,arguments)},_mouseDrag:function(event){this.position=this._generatePosition(event);this.positionAbs=this._convertPositionTo("absolute");if(!this.lastPositionAbs){this.lastPositionAbs=this.positionAbs}var o=this.options;if(this.options.scroll){var scrolled=false;if(this.scrollParent[0]!=document&&this.scrollParent[0].tagName!="HTML"){if((this.overflowOffset.top+this.scrollParent[0].offsetHeight)-event.pageY<o.scrollSensitivity){this.scrollParent[0].scrollTop=scrolled=this.scrollParent[0].scrollTop+o.scrollSpeed}else{if(event.pageY-this.overflowOffset.top<o.scrollSensitivity){this.scrollParent[0].scrollTop=scrolled=this.scrollParent[0].scrollTop-o.scrollSpeed}}if((this.overflowOffset.left+this.scrollParent[0].offsetWidth)-event.pageX<o.scrollSensitivity){this.scrollParent[0].scrollLeft=scrolled=this.scrollParent[0].scrollLeft+o.scrollSpeed}else{if(event.pageX-this.overflowOffset.left<o.scrollSensitivity){this.scrollParent[0].scrollLeft=scrolled=this.scrollParent[0].scrollLeft-o.scrollSpeed}}}else{if(event.pageY-$(document).scrollTop()<o.scrollSensitivity){scrolled=$(document).scrollTop($(document).scrollTop()-o.scrollSpeed)}else{if($(window).height()-(event.pageY-$(document).scrollTop())<o.scrollSensitivity){scrolled=$(document).scrollTop($(document).scrollTop()+o.scrollSpeed)}}if(event.pageX-$(document).scrollLeft()<o.scrollSensitivity){scrolled=$(document).scrollLeft($(document).scrollLeft()-o.scrollSpeed)}else{if($(window).width()-(event.pageX-$(document).scrollLeft())<o.scrollSensitivity){scrolled=$(document).scrollLeft($(document).scrollLeft()+o.scrollSpeed)}}}if(scrolled!==false&&$.ui.ddmanager&&!o.dropBehaviour){$.ui.ddmanager.prepareOffsets(this,event)}}this.positionAbs=this._convertPositionTo("absolute");var previousTopOffset=this.placeholder.offset().top;if(!this.options.axis||this.options.axis!="y"){this.helper[0].style.left=this.position.left+"px"}if(!this.options.axis||this.options.axis!="x"){this.helper[0].style.top=this.position.top+"px"}for(var i=this.items.length-1;i>=0;i--){var item=this.items[i],itemElement=item.item[0],intersection=this._intersectsWithPointer(item);if(!intersection){continue}if(itemElement!=this.currentItem[0]&&this.placeholder[intersection==1?"next":"prev"]()[0]!=itemElement&&!$.contains(this.placeholder[0],itemElement)&&(this.options.type=="semi-dynamic"?!$.contains(this.element[0],itemElement):true)){$(itemElement).mouseenter();this.direction=intersection==1?"down":"up";if(this.options.tolerance=="pointer"||this._intersectsWithSides(item)){$(itemElement).mouseleave();this._rearrange(event,item)}else{break}this._clearEmpty(itemElement);this._trigger("change",event,this._uiHash());break}}var parentItem=(this.placeholder[0].parentNode.parentNode&&$(this.placeholder[0].parentNode.parentNode).closest(".ui-sortable").length)?$(this.placeholder[0].parentNode.parentNode):null,level=this._getLevel(this.placeholder),childLevels=this._getChildLevels(this.helper);var previousItem=this.placeholder[0].previousSibling?$(this.placeholder[0].previousSibling):null;if(previousItem!=null){while(previousItem[0].nodeName.toLowerCase()!="li"||previousItem[0]==this.currentItem[0]||previousItem[0]==this.helper[0]){if(previousItem[0].previousSibling){previousItem=$(previousItem[0].previousSibling)}else{previousItem=null;break}}}var nextItem=this.placeholder[0].nextSibling?$(this.placeholder[0].nextSibling):null;if(nextItem!=null){while(nextItem[0].nodeName.toLowerCase()!="li"||nextItem[0]==this.currentItem[0]||nextItem[0]==this.helper[0]){if(nextItem[0].nextSibling){nextItem=$(nextItem[0].nextSibling)}else{nextItem=null;break}}}var newList=document.createElement(o.listType);this.beyondMaxLevels=0;if(parentItem!=null&&nextItem==null&&(o.rtl&&(this.positionAbs.left+this.helper.outerWidth()>parentItem.offset().left+parentItem.outerWidth())||!o.rtl&&(this.positionAbs.left<parentItem.offset().left))){parentItem.after(this.placeholder[0]);this._clearEmpty(parentItem[0]);this._trigger("change",event,this._uiHash())}else{if(previousItem!=null&&(o.rtl&&(this.positionAbs.left+this.helper.outerWidth()<previousItem.offset().left+previousItem.outerWidth()-o.tabSize)||!o.rtl&&(this.positionAbs.left>previousItem.offset().left+o.tabSize))){this._isAllowed(previousItem,level,level+childLevels+1);if(!previousItem.children(o.listType).length){previousItem[0].appendChild(newList)}if(previousTopOffset&&(previousTopOffset<=previousItem.offset().top)){previousItem.children(o.listType).prepend(this.placeholder)}else{previousItem.children(o.listType)[0].appendChild(this.placeholder[0])}this._trigger("change",event,this._uiHash())}else{this._isAllowed(parentItem,level,level+childLevels)}}this._contactContainers(event);if($.ui.ddmanager){$.ui.ddmanager.drag(this,event)}this._trigger("sort",event,this._uiHash());this.lastPositionAbs=this.positionAbs;return false},_mouseStop:function(event,noPropagation){if(this.beyondMaxLevels){this.placeholder.removeClass(this.options.errorClass);if(this.domPosition.prev){$(this.domPosition.prev).after(this.placeholder)}else{$(this.domPosition.parent).prepend(this.placeholder)}this._trigger("revert",event,this._uiHash())}for(var i=this.items.length-1;i>=0;i--){var item=this.items[i].item[0];this._clearEmpty(item)}$.ui.sortable.prototype._mouseStop.apply(this,arguments)},serialize:function(options){var o=$.extend({},this.options,options),items=this._getItemsAsjQuery(o&&o.connected),str=[];$(items).each(function(){var res=($(o.item||this).attr(o.attribute||"id")||"").match(o.expression||(/(.+)[-=_](.+)/)),pid=($(o.item||this).parent(o.listType).parent(o.items).attr(o.attribute||"id")||"").match(o.expression||(/(.+)[-=_](.+)/));if(res){str.push(((o.key||res[1])+"["+(o.key&&o.expression?res[1]:res[2])+"]")+"="+(pid?(o.key&&o.expression?pid[1]:pid[2]):o.rootID))}});if(!str.length&&o.key){str.push(o.key+"=")}return str.join("&")},toHierarchy:function(options){var o=$.extend({},this.options,options),sDepth=o.startDepthCount||0,ret=[];$(this.element).children(o.items).each(function(){var level=_recursiveItems(this);ret.push(level)});return ret;function _recursiveItems(item){var id=($(item).attr(o.attribute||"id")||"").match(o.expression||(/(.+)[-=_](.+)/));if(id){var currentItem={id:id[2]};if($(item).children(o.listType).children(o.items).length>0){currentItem.children=[];$(item).children(o.listType).children(o.items).each(function(){var level=_recursiveItems(this);currentItem.children.push(level)})}return currentItem}}},toArray:function(options){var o=$.extend({},this.options,options),sDepth=o.startDepthCount||0,ret=[],left=2;ret.push({item_id:o.rootID,parent_id:"none",depth:sDepth,left:"1",right:($(o.items,this.element).length+1)*2});$(this.element).children(o.items).each(function(){left=_recursiveArray(this,sDepth+1,left)});ret=ret.sort(function(a,b){return(a.left-b.left)});return ret;function _recursiveArray(item,depth,left){var right=left+1,id,pid;if($(item).children(o.listType).children(o.items).length>0){depth++;$(item).children(o.listType).children(o.items).each(function(){right=_recursiveArray($(this),depth,right)});depth--}id=($(item).attr(o.attribute||"id")).match(o.expression||(/(.+)[-=_](.+)/));if(depth===sDepth+1){pid=o.rootID}else{var parentItem=($(item).parent(o.listType).parent(o.items).attr(o.attribute||"id")).match(o.expression||(/(.+)[-=_](.+)/));pid=parentItem[2]}if(id){ret.push({item_id:id[2],parent_id:pid,depth:depth,left:left,right:right})}left=right+1;return left}},_clearEmpty:function(item){var emptyList=$(item).children(this.options.listType);if(emptyList.length&&!emptyList.children().length&&!this.options.doNotClear){emptyList.remove()}},_getLevel:function(item){var level=1;if(this.options.listType){var list=item.closest(this.options.listType);while(list&&list.length>0&&!list.is(".ui-sortable")){level++;list=list.parent().closest(this.options.listType)}}return level},_getChildLevels:function(parent,depth){var self=this,o=this.options,result=0;depth=depth||0;$(parent).children(o.listType).children(o.items).each(function(index,child){result=Math.max(self._getChildLevels(child,depth+1),result)});return depth?result+1:result},_isAllowed:function(parentItem,level,levels){var o=this.options,isRoot=$(this.domPosition.parent).hasClass("ui-sortable")?true:false,maxLevels=this.placeholder.closest(".ui-sortable").nestedSortable("option","maxLevels");if(!o.isAllowed(this.currentItem,parentItem)||parentItem&&parentItem.hasClass(o.disableNesting)||o.protectRoot&&(parentItem==null&&!isRoot||isRoot&&level>1)){this.placeholder.addClass(o.errorClass);if(maxLevels<levels&&maxLevels!=0){this.beyondMaxLevels=levels-maxLevels}else{this.beyondMaxLevels=1}}else{if(maxLevels<levels&&maxLevels!=0){this.placeholder.addClass(o.errorClass);this.beyondMaxLevels=levels-maxLevels}else{this.placeholder.removeClass(o.errorClass);this.beyondMaxLevels=0}}}}));$.mjs.nestedSortable.prototype.options=$.extend({},$.ui.sortable.prototype.options,$.mjs.nestedSortable.prototype.options)})(jQuery);

/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 *
 * Dual licensed under the MIT and GPL licenses
 *
 */
(function ($) {
    var ColorPicker = function () {
        var
            ids = {},
            inAction,
            charMin = 65,
            visible,
            tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color" title="Reset Color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_transparent"></div><div class="colorpicker_submit" title="Apply Color"></div></div>',
            defaults = {
                eventName: 'click',
                onInit: function () {},
                onShow: function () {},
                onBeforeShow: function(){},
                onHide: function () {},
                onChange: function () {},
                onSubmit: function () {},
                color: 'ff0000',
                livePreview: true,
                flat: false
            },
            fillRGBFields = function  (hsb, cal) {
                var rgb = HSBToRGB(hsb);
                $(cal).data('colorpicker').fields
                    .eq(1).val(rgb.r).end()
                    .eq(2).val(rgb.g).end()
                    .eq(3).val(rgb.b).end();
            },
            fillHSBFields = function  (hsb, cal) {
                $(cal).data('colorpicker').fields
                    .eq(4).val(hsb.h).end()
                    .eq(5).val(hsb.s).end()
                    .eq(6).val(hsb.b).end();
            },
            fillHexFields = function (hsb, cal) {
                $(cal).data('colorpicker').fields
                    .eq(0).val(HSBToHex(hsb)).end();
            },
            setSelector = function (hsb, cal) {
                $(cal).data('colorpicker').selector.css('backgroundColor', '#' + HSBToHex({h: hsb.h, s: 100, b: 100}));
                $(cal).data('colorpicker').selectorIndic.css({
                    left: parseInt(150 * hsb.s/100, 10),
                    top: parseInt(150 * (100-hsb.b)/100, 10)
                });
            },
            setHue = function (hsb, cal) {
                $(cal).data('colorpicker').hue.css('top', parseInt(150 - 150 * hsb.h/360, 10));
            },
            setCurrentColor = function (hsb, cal) {
                $(cal).data('colorpicker').currentColor.css('backgroundColor', '#' + HSBToHex(hsb));
            },
            setNewColor = function (hsb, cal) {
                $(cal).data('colorpicker').newColor.css('backgroundColor', '#' + HSBToHex(hsb));
            },
            keyDown = function (ev) {
                var pressedKey = ev.charCode || ev.keyCode || -1;
                if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
                    return false;
                }
                var cal = $(this).parent().parent();
                if (cal.data('colorpicker').livePreview === true) {
                    change.apply(this);
                }
            },
            change = function (ev) {
                var cal = $(this).parent().parent(), col;
                if (this.parentNode.className.indexOf('_hex') > 0) {
                    cal.data('colorpicker').color = col = HexToHSB(fixHex(this.value));
                } else if (this.parentNode.className.indexOf('_hsb') > 0) {
                    cal.data('colorpicker').color = col = fixHSB({
                        h: parseInt(cal.data('colorpicker').fields.eq(4).val(), 10),
                        s: parseInt(cal.data('colorpicker').fields.eq(5).val(), 10),
                        b: parseInt(cal.data('colorpicker').fields.eq(6).val(), 10)
                    });
                } else {
                    cal.data('colorpicker').color = col = RGBToHSB(fixRGB({
                        r: parseInt(cal.data('colorpicker').fields.eq(1).val(), 10),
                        g: parseInt(cal.data('colorpicker').fields.eq(2).val(), 10),
                        b: parseInt(cal.data('colorpicker').fields.eq(3).val(), 10)
                    }));
                }
                if (ev) {
                    fillRGBFields(col, cal.get(0));
                    fillHexFields(col, cal.get(0));
                    fillHSBFields(col, cal.get(0));
                }
                setSelector(col, cal.get(0));
                setHue(col, cal.get(0));
                setNewColor(col, cal.get(0));
                cal.data('colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col)]);
            },
            blur = function (ev) {
                var cal = $(this).parent().parent();
                cal.data('colorpicker').fields.parent().removeClass('colorpicker_focus');
            },
            focus = function () {
                charMin = this.parentNode.className.indexOf('_hex') > 0 ? 70 : 65;
                $(this).parent().parent().data('colorpicker').fields.parent().removeClass('colorpicker_focus');
                $(this).parent().addClass('colorpicker_focus');
            },
            downIncrement = function (ev) {
                var field = $(this).parent().find('input').focus();
                var current = {
                    el: $(this).parent().addClass('colorpicker_slider'),
                    max: this.parentNode.className.indexOf('_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf('_hsb') > 0 ? 100 : 255),
                    y: ev.pageY,
                    field: field,
                    val: parseInt(field.val(), 10),
                    preview: $(this).parent().parent().data('colorpicker').livePreview
                };
                $(document).bind('mouseup', current, upIncrement);
                $(document).bind('mousemove', current, moveIncrement);
            },
            moveIncrement = function (ev) {
                ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
                if (ev.data.preview) {
                    change.apply(ev.data.field.get(0), [true]);
                }
                return false;
            },
            upIncrement = function (ev) {
                change.apply(ev.data.field.get(0), [true]);
                ev.data.el.removeClass('colorpicker_slider').find('input').focus();
                $(document).unbind('mouseup', upIncrement);
                $(document).unbind('mousemove', moveIncrement);
                return false;
            },
            downHue = function (ev) {
                var current = {
                    cal: $(this).parent(),
                    y: $(this).offset().top
                };
                current.preview = current.cal.data('colorpicker').livePreview;
                $(document).bind('mouseup', current, upHue);
                $(document).bind('mousemove', current, moveHue);
            },
            moveHue = function (ev) {
                change.apply(
                    ev.data.cal.data('colorpicker')
                        .fields
                        .eq(4)
                        .val(parseInt(360*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.y))))/150, 10))
                        .get(0),
                    [ev.data.preview]
                );
                return false;
            },
            upHue = function (ev) {
                fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                $(document).unbind('mouseup', upHue);
                $(document).unbind('mousemove', moveHue);
                return false;
            },
            downSelector = function (ev) {
                var current = {
                    cal: $(this).parent(),
                    pos: $(this).offset()
                };
                current.preview = current.cal.data('colorpicker').livePreview;
                $(document).bind('mouseup', current, upSelector);
                $(document).bind('mousemove', current, moveSelector);
            },
            moveSelector = function (ev) {
                change.apply(
                    ev.data.cal.data('colorpicker')
                        .fields
                        .eq(6)
                        .val(parseInt(100*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.pos.top))))/150, 10))
                        .end()
                        .eq(5)
                        .val(parseInt(100*(Math.max(0,Math.min(150,(ev.pageX - ev.data.pos.left))))/150, 10))
                        .get(0),
                    [ev.data.preview]
                );
                return false;
            },
            upSelector = function (ev) {
                moveSelector(ev);
                fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
                $(document).unbind('mouseup', upSelector);
                $(document).unbind('mousemove', moveSelector);
                return false;
            },
            enterSubmit = function (ev) {
                $(this).addClass('colorpicker_focus');
            },
            leaveSubmit = function (ev) {
                $(this).removeClass('colorpicker_focus');
            },
            clickSubmit = function (ev) {
                var cal = $(this).parent();
                var col = cal.data('colorpicker').color;
                cal.data('colorpicker').origColor = col;
                setCurrentColor(col, cal.get(0));
                cal.data('colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data('colorpicker').el);
            },
            show = function (ev) {
                var cal = $('#' + $(this).data('colorpickerId'));
                cal.data('colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
                var pos = $(this).offset();
                var viewPort = getViewport();
                var top = pos.top + this.offsetHeight;
                var left = pos.left;
                if (top + 176 > viewPort.t + viewPort.h) {
                    top -= this.offsetHeight + 176;
                }
                if (left + 356 > viewPort.l + viewPort.w) {
                    left -= 356;
                }
                cal.css({left: left + 'px', top: top + 'px'});
                if (cal.data('colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
                    cal.show();
                }
                $(document).bind('mousedown', {cal: cal}, hide);
                return false;
            },
            hide = function (ev) {
                if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
                    if (ev.data.cal.data('colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
                        ev.data.cal.hide();
                    }
                    $(document).unbind('mousedown', hide);
                }
            },
            isChildOf = function(parentEl, el, container) {
                if (parentEl == el) {
                    return true;
                }
                if (parentEl.contains) {
                    return parentEl.contains(el);
                }
                if ( parentEl.compareDocumentPosition ) {
                    return !!(parentEl.compareDocumentPosition(el) & 16);
                }
                var prEl = el.parentNode;
                while(prEl && prEl != container) {
                    if (prEl == parentEl)
                        return true;
                    prEl = prEl.parentNode;
                }
                return false;
            },
            getViewport = function () {
                var m = document.compatMode == 'CSS1Compat';
                return {
                    l : window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
                    t : window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
                    w : window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
                    h : window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
                };
            },
            fixHSB = function (hsb) {
                return {
                    h: Math.min(360, Math.max(0, hsb.h)),
                    s: Math.min(100, Math.max(0, hsb.s)),
                    b: Math.min(100, Math.max(0, hsb.b))
                };
            },
            fixRGB = function (rgb) {
                return {
                    r: Math.min(255, Math.max(0, rgb.r)),
                    g: Math.min(255, Math.max(0, rgb.g)),
                    b: Math.min(255, Math.max(0, rgb.b))
                };
            },
            fixHex = function (hex) {
                var len = 6 - hex.length;
                if (len > 0) {
                    var o = [];
                    for (var i=0; i<len; i++) {
                        o.push('0');
                    }
                    o.push(hex);
                    hex = o.join('');
                }
                return hex;
            },
            HexToRGB = function (hex) {
                var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
                return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
            },
            HexToHSB = function (hex) {
                return RGBToHSB(HexToRGB(hex));
            },
            RGBToHSB = function (rgb) {
                var hsb = {
                    h: 0,
                    s: 0,
                    b: 0
                };
                var min = Math.min(rgb.r, rgb.g, rgb.b);
                var max = Math.max(rgb.r, rgb.g, rgb.b);
                var delta = max - min;
                hsb.b = max;
                if (max != 0) {

                }
                hsb.s = max != 0 ? 255 * delta / max : 0;
                if (hsb.s != 0) {
                    if (rgb.r == max) {
                        hsb.h = (rgb.g - rgb.b) / delta;
                    } else if (rgb.g == max) {
                        hsb.h = 2 + (rgb.b - rgb.r) / delta;
                    } else {
                        hsb.h = 4 + (rgb.r - rgb.g) / delta;
                    }
                } else {
                    hsb.h = -1;
                }
                hsb.h *= 60;
                if (hsb.h < 0) {
                    hsb.h += 360;
                }
                hsb.s *= 100/255;
                hsb.b *= 100/255;
                return hsb;
            },
            HSBToRGB = function (hsb) {
                var rgb = {};
                var h = Math.round(hsb.h);
                var s = Math.round(hsb.s*255/100);
                var v = Math.round(hsb.b*255/100);
                if(s == 0) {
                    rgb.r = rgb.g = rgb.b = v;
                } else {
                    var t1 = v;
                    var t2 = (255-s)*v/255;
                    var t3 = (t1-t2)*(h%60)/60;
                    if(h==360) h = 0;
                    if(h<60) {rgb.r=t1; rgb.b=t2; rgb.g=t2+t3}
                    else if(h<120) {rgb.g=t1; rgb.b=t2; rgb.r=t1-t3}
                    else if(h<180) {rgb.g=t1; rgb.r=t2; rgb.b=t2+t3}
                    else if(h<240) {rgb.b=t1; rgb.r=t2; rgb.g=t1-t3}
                    else if(h<300) {rgb.b=t1; rgb.g=t2; rgb.r=t2+t3}
                    else if(h<360) {rgb.r=t1; rgb.g=t2; rgb.b=t1-t3}
                    else {rgb.r=0; rgb.g=0; rgb.b=0}
                }
                return {r:Math.round(rgb.r), g:Math.round(rgb.g), b:Math.round(rgb.b)};
            },
            RGBToHex = function (rgb) {
                var hex = [
                    rgb.r.toString(16),
                    rgb.g.toString(16),
                    rgb.b.toString(16)
                ];
                $.each(hex, function (nr, val) {
                    if (val.length == 1) {
                        hex[nr] = '0' + val;
                    }
                });
                return hex.join('');
            },
            HSBToHex = function (hsb) {
                return RGBToHex(HSBToRGB(hsb));
            },
            restoreOriginal = function () {
                var cal = $(this).parent();
                var col = cal.data('colorpicker').origColor;
                cal.data('colorpicker').color = col;
                fillRGBFields(col, cal.get(0));
                fillHexFields(col, cal.get(0));
                fillHSBFields(col, cal.get(0));
                setSelector(col, cal.get(0));
                setHue(col, cal.get(0));
                setNewColor(col, cal.get(0));
            };
        return {
            init: function (opt) {
                opt = $.extend({}, defaults, opt||{});
                if (typeof opt.color == 'string') {
                    opt.color = HexToHSB(opt.color);
                } else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
                    opt.color = RGBToHSB(opt.color);
                } else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
                    opt.color = fixHSB(opt.color);
                } else {
                    return this;
                }
                return this.each(function () {
                    if (!$(this).data('colorpickerId')) {
                        var options = $.extend({}, opt);
                        options.origColor = opt.color;
                        var id = 'collorpicker_' + parseInt(Math.random() * 1000000);
                        $(this).data('colorpickerId', id);
                        var cal = $(tpl).attr('id', id);
                        if (options.flat) {
                            cal.appendTo(this).show();
                        } else {
                            cal.appendTo(document.body);
                        }
                        options.fields = cal
                            .find('input')
                            .bind('keyup', keyDown)
                            .bind('change', change)
                            .bind('blur', blur)
                            .bind('focus', focus);
                        cal
                            .find('span').bind('mousedown', downIncrement).end()
                            .find('>div.colorpicker_current_color').bind('click', restoreOriginal);
                        options.selector = cal.find('div.colorpicker_color').bind('mousedown', downSelector);
                        options.selectorIndic = options.selector.find('div div');
                        options.el = this;
                        options.hue = cal.find('div.colorpicker_hue div');
                        cal.find('div.colorpicker_hue').bind('mousedown', downHue);
                        options.newColor = cal.find('div.colorpicker_new_color');
                        options.currentColor = cal.find('div.colorpicker_current_color');
                        cal.data('colorpicker', options);
                        cal.find('div.colorpicker_submit')
                            .bind('mouseenter', enterSubmit)
                            .bind('mouseleave', leaveSubmit)
                            .bind('click', clickSubmit);
                        fillRGBFields(options.color, cal.get(0));
                        fillHSBFields(options.color, cal.get(0));
                        fillHexFields(options.color, cal.get(0));
                        setHue(options.color, cal.get(0));
                        setSelector(options.color, cal.get(0));
                        setCurrentColor(options.color, cal.get(0));
                        setNewColor(options.color, cal.get(0));
                        if (options.flat) {
                            cal.css({
                                position: 'relative',
                                display: 'block'
                            });
                        } else {
                            $(this).bind(options.eventName, show);
                        }
                        cal.data('colorpicker').onInit.apply(this, [cal.get(0)])
                    }
                });
            },
            showPicker: function() {
                return this.each( function () {
                    if ($(this).data('colorpickerId')) {
                        show.apply(this);
                    }
                });
            },
            hidePicker: function() {
                return this.each( function () {
                    if ($(this).data('colorpickerId')) {
                        $('#' + $(this).data('colorpickerId')).hide();
                    }
                });
            },
            setColor: function(col) {
                if (typeof col == 'string') {
                    col = HexToHSB(col);
                } else if (col.r != undefined && col.g != undefined && col.b != undefined) {
                    col = RGBToHSB(col);
                } else if (col.h != undefined && col.s != undefined && col.b != undefined) {
                    col = fixHSB(col);
                } else {
                    return this;
                }
                return this.each(function(){
                    if ($(this).data('colorpickerId')) {
                        var cal = $('#' + $(this).data('colorpickerId'));
                        cal.data('colorpicker').color = col;
                        cal.data('colorpicker').origColor = col;
                        fillRGBFields(col, cal.get(0));
                        fillHSBFields(col, cal.get(0));
                        fillHexFields(col, cal.get(0));
                        setHue(col, cal.get(0));
                        setSelector(col, cal.get(0));
                        setCurrentColor(col, cal.get(0));
                        setNewColor(col, cal.get(0));
                    }
                });
            }
        };
    }();
    $.fn.extend({
        ColorPicker: ColorPicker.init,
        ColorPickerHide: ColorPicker.hidePicker,
        ColorPickerShow: ColorPicker.showPicker,
        ColorPickerSetColor: ColorPicker.setColor
    });
})(jQuery);

(function( $, undefined ) {

    function findOriginalEventTargetClick(event) {
        if (typeof event.type != "undefined" && event.type == "click") {
            return event.target;
        } else
        if (typeof event.originalEvent != "undefined") {
            return findOriginalEventTargetClick(event.originalEvent);
        } else {
            return false;
        }
    }

    function hasClass( target, className ) {
        return new RegExp('(\\s|^)' + className + '(\\s|$)').test(target.className);
    }

    $.widget( "ui.combobox", {

        version: "1.0.9",

        widgetEventPrefix: "combobox",

        uiCombo: null,
        uiInput: null,
        _wasOpen: false,
        currentItem: {},

        _create: function() {

            var self = this,
                select = this.element.hide(),
                input, wrapper;

            input = this.uiInput =
                $( "<input />" )
                    .insertAfter(select)
                    .addClass("ui-widget ui-widget-content ui-corner-left ui-combobox-input")
                    .val( select.children(':selected').text() );

            wrapper = this.uiCombo =
                input.wrap( '<span>' )
                    .parent()
                    .addClass( 'ui-combobox' )
                    .insertAfter( select );

            input
                .autocomplete({

                    delay: 0,
                    minLength: 0,

                    appendTo: wrapper,
                    source: $.proxy( this, "_linkSelectList" )

                });

            $( "<button>" )
                .attr( "tabIndex", -1 )
                .attr( "type", "button" )
                .insertAfter( input )
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false
                })
                .removeClass( "ui-corner-all" )
                .addClass( "ui-corner-right ui-button-icon ui-combobox-button" );

            input.data( "ui-autocomplete" )._renderItem = function( ul, item ) {

                var inner, item_class = typeof item.element != "undefined" && $(item.element).hasAttr("class") ? $(item.element).attr("class") : "";

                if (item.optionValue === null || item.element !== null && typeof item.element != "undefined" && $(item.element).hasClass("ui-combobox-disabled")) {
                    inner = item.label;
                } else {
                    inner = '<a>' + item.label + '</a>' + (item.remove ? '<span class="ui-combobox-remove">remove</span>' : '');
                }

                return $( "<li" + (item_class != "" ? (' class="' + item_class + '"') : "") + ">" ).append(inner).appendTo( ul );
            };

            input.data( "ui-autocomplete" ).menu._setOption("items", "> :not(.tb_label)");

            this._on( this._events );

            var $option = select.children(':selected').first();

            if ($option.length) {
                this.currentItem = {
                    label:       $option.text(),
                    value:       $option.text(),
                    key:         $option.hasAttr("key") ? $option.attr("key") : null,
                    optionValue: $option.val(),
                    element:     $option[0]
                }
            }

        },


        _linkSelectList: function( request, response ) {

            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), 'i' );
            var result = this.element.children('option').map(function() {

                var text = $(this).text();

                if (this.value && (!request.term || (matcher.test(text) && !$(this).hasClass("ui-combobox-nocomplete")))) {

                    return {
                        label:       text,
                        value:       text,
                        key:         $(this).hasAttr("key") ? $(this).attr("key") : null,
                        optionValue: $(this).val(),
                        remove:      $(this).hasClass("ui-combobox-remove"),
                        element:     this
                    };
                }
            }).get();

            var sendResponse = function(result) {
                if (request.term) {
                    $.each(result, function(index, obj) {
                        obj.label = obj.label.replace(
                            new RegExp(
                                "(?![^&;]+;)(?!<[^<>]*)(" +
                                $.ui.autocomplete.escapeRegex(request.term) +
                                ")(?![^<>]*>)(?![^&;]+;)", "gi"),
                            "<strong>$1</strong>"
                        );
                    });
                }

                response(result);
            };

            if ($.isFunction(this.options.search_data)) {

                var additional = this.options.search_data.call(this, request.term, matcher, function(data) {
                    if (data.length) {
                        result = result.concat(data);
                    }
                    sendResponse(result);
                });
            } else {
                sendResponse(result);
            }
        },

        _events: {

            "autocompletechange input" : function(event, ui) {

                var $el = $(event.currentTarget);

                if ( !ui.item ) {

                    var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $el.val() ) + "$", "i" ),
                        valid = false;

                    this.element.children( "option" ).each(function() {
                        if ( $( this ).text().match( matcher ) ) {
                            this.selected = valid = true;

                            return false;
                        }
                    });

                    if ( !valid ) {
                        if (!this.element.data( "customValue" )) {
                            $el.val( "" );
                            this.element.prop('selectedIndex', -1);
                            //return false;
                        }
                    }
                }

                this._trigger( "change", event, {
                    item: ui.item ? ui.item : null
                });

            },

            "autocompleteselect input": function( event, ui ) {

                var originalTarget = findOriginalEventTargetClick(event);

                if (false !== originalTarget && hasClass(originalTarget, "ui-combobox-remove")) {

                    var $option = $(ui.item.element);

                    this._trigger( "remove", event, {
                        label:       $option.text(),
                        value:       $option.text(),
                        key:         $option.hasAttr("key") ? $option.attr("key") : null,
                        optionValue: $option.val(),
                        element:     $option[0],
                        context:     this
                    });
                    this.uiInput.trigger("blur");
                    // prevent the 'menuselect' autocomplete event from setting the input's value to ui.item.value
                    return false;
                } else {
                    if (ui.item.element && this.optionExists($(ui.item.element)) && !hasClass(ui.item.element, "ui-combobox-noselect")) {
                        this.element.removeData( "customValue" );
                        ui.item.element.selected = true;
                    }

                    if (!ui.item.element || !this.optionExists($(ui.item.element))) {
                        // if we do not set the custom value and reset the index, the setSelected() method, after input blur, will return the previous value
                        this.element.prop('selectedIndex', -1);
                        this.element.data( "customValue", ui.item.value );
                        this.element.data( "optionValue", ui.item.optionValue );
                    }

                    if (!ui.item.element || !hasClass(ui.item.element, "ui-combobox-noselect")) {
                        this.currentItem = ui.item;
                    }

                    this._trigger( "select", event, {
                        item: ui.item,
                        context: this
                    });
                }
            },

            "autocompleteopen input": function ( event, ui ) {
                this.uiCombo.children('.ui-autocomplete')
                    .outerWidth(this.uiCombo.outerWidth(true));
            },

            "autocompleteclose input": function ( event, ui ) {
                this._trigger( "close", event, ui);
            },

            "mousedown .ui-combobox-button" : function ( event ) {
                this._wasOpen = this.uiInput.autocomplete("widget").is(":visible");
                this._trigger( "buttonmousedown", event, this);
            },

            "click .ui-combobox-button" : function( event ) {

                // close if already visible
                if (this._wasOpen)
                    return;

                this.uiInput.focus();

                // pass empty string as value to search for, displaying all results
                this.uiInput.autocomplete("search", "");

                this._trigger( "open", event, this);
            },

            "focus input" : function(event) {
                this.uiInput.val("");
            },

            "blur input" : function(event) {
                this.setSelected();
                this._trigger( "blur", event, this);
            }

        },


        label: function() {
            return this.uiInput.val();
        },

        value: function ( newVal, custom, optionValue ) {

            var select = this.element,
                valid = false,
                selected;

            if (typeof custom == "undefined") {
                custom = false;
            }

            if (typeof optionValue == "undefined") {
                optionValue = newVal;
            }

            if ( !arguments.length ) {
                if (!select.data( "customValue" )) {
                    selected = select.children( ":selected" );

                    return selected.length > 0 ? selected.val() : null;
                } else {
                    return select.data( "optionValue" ) ? select.data( "optionValue" ) : null;
                }
            }

            select.prop('selectedIndex', -1);
            select.children('option').each(function() {
                if ( this.value == newVal ) {
                    this.selected = valid = true;

                    return false;
                }
            });

            if ( valid ) {
                this.uiInput.val(select.children(':selected').text());
                select.removeData( "customValue" );
                select.removeData( "optionValue" );
            } else {
                select.prop('selectedIndex', -1);
                if (!custom) {
                    this.uiInput.val( "" );
                    select.removeData( "customValue" );
                    select.removeData( "optionValue" );
                } else {
                    this.uiInput.val( newVal );
                    select.data( "customValue", newVal );
                    select.data( "optionValue", optionValue );
                }
            }

            return this;
        },

        customValue: function ( newVal, optionValue ) {
            return this.value( newVal, true, optionValue );
        },

        exportValue: function() {
            var result, select = this.element;

            if (!select.data( "customValue" )) {
                selected = select.children( ":selected" );

                result = {
                    value: selected.length > 0 ? selected.val() : null,
                    type: "option"
                }
            } else {
                result = {
                    value: select.data( "optionValue" ) ? select.data( "optionValue" ) : null,
                    custom: select.data( "customValue" ),
                    type: "custom"
                }
            }

            return result;
        },

        importValue: function(valueObj) {
            if (valueObj.type == "custom") {
                this.customValue(valueObj.custom, valueObj.value);
            } else {
                this.value(valueObj.value);
            }
        },

        setSelected: function() {
            if (!this.element.data( "customValue" )) {
                this.uiInput.val(this.element.children(':selected').text());
            } else {
                this.uiInput.val(this.element.data( "customValue" ));
            }
        },

        optionExists: function($element) {
            return $element.is("option") && this.element.children('option[value="' + $element.val() + '"]').length
        },

        addOption: function(text, value, prepend, can_remove) {
            if (typeof value == "undefined") {
                value = text;
            }

            if (typeof prepend ==  "undefined") {
                prepend = false;
            }

            if (typeof can_remove ==  "undefined") {
                can_remove = false;
            }

            var $option = $("<option></option>").attr("value", value).text(text);

            if (can_remove) {
                $option.addClass("ui-combobox-remove");
            }

            if (prepend) {
                this.element.prepend($option);
            } else {
                this.element.append($option);
            }

            this.value(value);
        },

        removeOption: function(value) {
            this.element.children('option[value="' + value + '"]').remove();
        },

        _destroy: function () {
            this.element.show();
            this.uiCombo.replaceWith( this.element );
        },

        widget: function () {
            return this.uiCombo;
        },

        _getCreateEventData: function() {

            return {
                select: this.element,
                combo: this.uiCombo,
                input: this.uiInput
            };
        }

    });


}(jQuery));

var $sReg = new function() {

    function unlink(object) {

        var unlinked;

        switch (typeof object){
            case 'object':
                unlinked = {};
                for (var p in object) unlinked[p] = unlink(object[p]);
                break;
            case 'array':
                unlinked = [];
                for (var i = 0, l = object.length; i < l; i++) unlinked[i] = unlink(object[i]);
                break;
            default: return object;
        }

        return unlinked;
    }

    function mixin(mix) {

        for (var i = 1, l = arguments.length; i < l; i++){
            var object = arguments[i];
            if (typeof object != 'object') continue;
            for (var key in object){
                var op = object[key], mp = mix[key];
                mix[key] = (mp && typeof op == 'object' && typeof mp == 'object') ? mixin(mp, op) : unlink(op);
            }
        }

        return mix;
    }

    function merge() {

        var args = Array.prototype.slice.call(arguments);

        args.unshift({});

        return mixin.apply(null, args);
    }

    return {

        conf : {},

        set : function(path,value){

            var fragments = path.split('/');

            if( fragments.shift() !== '') {
                return false;
            }

            if(fragments.length > 0 && fragments[fragments.length - 1] == '') {
                fragments.pop();
            }

            var obj = {};
            var ref = obj;
            var len = fragments.length;

            if( len > 0){
                for(var i = 0; i < len-1; i++){
                    ref[fragments[i]] = {};
                    ref = ref[fragments[i]];
                }
                ref[fragments[len-1]] = value;
                this.conf = merge(this.conf,obj);
            } else {
                this.conf = value;
            }
        },

        get : function(path){

            var fragments = path.split('/');

            if( fragments.shift() !== '') {
                return null;
            }

            if(fragments.length > 0 && fragments[fragments.length -1] == '') fragments.pop();

            var ref = this.conf;
            var path_exists = true;
            var i = 0;

            if (ref[fragments[i]] === undefined) {
                return null;
            }

            var len = fragments.length;

            while(path_exists && i < len){
                path_exists = path_exists && (ref[fragments[i]] !== undefined);
                ref = ref[fragments[i]]; i++;
            }

            return ref;
        }
    };
};
