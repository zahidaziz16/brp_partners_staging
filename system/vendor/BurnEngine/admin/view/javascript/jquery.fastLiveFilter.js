/**
 * fastLiveFilter jQuery plugin 1.0.3
 *
 * Copyright (c) 2011, Anthony Bush
 * License: <http://www.opensource.org/licenses/bsd-license.php>
 * Project Website: http://anthonybush.com/projects/jquery_fast_live_filter/
 **/
jQuery.fn.fastLiveFilter=function(list,options){options=options||{};list=jQuery(list);var input=this;var timeout=options.timeout||0;var callback=options.callback||function(){};var keyTimeout;var lis=list.children();var len=lis.length;var oldDisplay=len>0?lis[0].style.display:"block";callback(len);input.change(function(){var filter=input.val().toLowerCase();var li;var numShown=0;for(var i=0;i<len;i++){li=lis[i];if((li.textContent||li.innerText||"").toLowerCase().indexOf(filter)>=0){if(li.style.display=="none"){li.style.display=oldDisplay}numShown++}else{if(li.style.display!="none"){li.style.display="none"}}}callback(numShown);return false}).keydown(function(){clearTimeout(keyTimeout);keyTimeout=setTimeout(function(){input.change()},timeout)});return this};
