<?php $tbData->slotFilter('common/header.scripts.filter', $scripts); ?>
<?php $tbData->slotFilter('common/header.styles.filter', $styles); ?>
<?php $tbData->slotFlag('catalog.template.header'); ?>
<?php if (!isset($tbData['seo_settings']['facebook_opengraph']) || !empty($tbData['seo_settings']['facebook_opengraph'])): ?>
<?php echo $tbData->fbMeta; ?>
<?php endif; ?>
<?php if (!isset($tbData['seo_settings']['twitter_card']) || !empty($tbData['seo_settings']['twitter_card'])): ?>
<?php echo $tbData->twitterMeta; ?>
<?php endif; ?>
<?php // CRITICAL CSS ////////////////////////////// ?>
<?php if (!empty($tbData->system['critical_css'])): ?>
<style><?php echo $tbData->system['critical_css']; ?></style>
<?php endif; ?>
<?php // CUSTOM FONTS ////////////////////////////// ?>
<?php if ($tbData->engine_config['catalog_google_fonts']): ?>
<?php if ($tbData->engine_config['catalog_google_fonts_js']): ?>
<script data-capture="0">
try{if(!parent.document)throw new Error('');tbRootWindow=top!==self?window.parent:window}catch(a){tbRootWindow=window};tbWindowWidth=window.innerWidth;function includeFontResource(e){"use strict";function t(e,t,c){e.addEventListener?e.addEventListener(t,c,!1):e.attachEvent&&e.attachEvent("on"+t,c)}function c(e){return window.localStorage&&localStorage.font_css_cache&&localStorage.font_css_cache_file===e}function n(){if(window.localStorage&&window.XMLHttpRequest)if(c(a))o(localStorage.font_css_cache);else{var e=new XMLHttpRequest;e.open("GET",a,!0),e.onreadystatechange=function(){4===e.readyState&&200===e.status&&(o(e.responseText),localStorage.font_css_cache=e.responseText,localStorage.font_css_cache_file=a)},e.send()}else{var t=document.createElement("link");t.href=a,t.rel="stylesheet",t.type="text/css",document.getElementsByTagName("head")[0].appendChild(t),document.cookie="font_css_cache"}}function o(e){var t=document.createElement("style");t.setAttribute("type","text/css"),document.getElementsByTagName("head")[0].appendChild(t),t.styleSheet?t.styleSheet.cssText=e:t.innerHTML=e}var a=e;window.localStorage&&localStorage.font_css_cache||document.cookie.indexOf("font_css_cache")>-1?n():t(window,"load",n)}
<!--{{google_fonts_link}}-->
</script>
<?php else: ?>
<!--{{google_fonts_link}}-->
<?php endif; ?>
<?php endif; ?>
<?php // THEME STYLES ////////////////////////////// ?>
<!--[if[style_resources_placeholder]]-->
<!--[if lt IE 10]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/BurnEngine/stylesheet/ie.css" media="screen" />
<![endif]-->
<?php // THEME SCRIPTS ////////////////////////// ?>
<!--{{javascript_resources_placeholder_header}}-->