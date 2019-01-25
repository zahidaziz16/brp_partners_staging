<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>"<?php if ($tbData->is_quickview): ?> class="tb_quickview"<?php endif; ?>>
<head>
<meta charset="UTF-8" />
<link href='https://fonts.gstatic.com' rel='preconnect' crossorigin />
<?php if ($tbData->style['responsive']): ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<?php else: ?>
<meta name="viewport" content="width=<?php echo $tbData->style['maximum_width'] + 60; ?>">
<?php endif; ?>
<title><?php echo $title; ?></title>
<?php $base = $tbData->base_httpsIf; ?>
<base href="<?php echo $base; ?>" target="<?php echo $tbData['base_target']; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="generator" content="<?php echo $tbData['meta_generator']; ?>" />
<?php if (!$tbData->OcVersionGte('2.1.0.0')): ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php endif; ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php require tb_modification($tbData['root_dir'] . '/catalog/view/theme/' . $tbData['basename'] . '/template/tb/header.tpl'); ?>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<?php $tbData->slotStart('oc_header/analytics_code'); ?>
<?php if ($tbData->OcVersionGte('2.1.0.0')): ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<?php else: ?>
<?php echo $google_analytics; ?>
<?php endif; ?>
<?php $tbData->slotStopEcho(); ?>
<!--Start of Zendesk Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="https://v2.zopim.com/?WrZJIO6g3AsrOfwMezYXI8FOVp8d753F";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zendesk Chat Script-->
</head>

<?php $class .= ' ' . $tbData->body_class; ?>
<body class="<?php echo $class; ?>">

<?php if($tbData->facebook['sdk_enabled']): ?>
<div id="fb-root"></div><script type="text/javascript" data-capture="0">window.fbAsyncInit=function(){FB.init({<?php if($tbData->facebook['app_id']): ?>appId:'<?php echo $tbData->facebook['app_id']; ?>',<?php endif; ?>status:true,xfbml:<?php echo $tbData->system['js_lazyload'] ? 'false' : 'true'; ?>});if(window.FB_XFBML_parsed!==undefined){window.FB_XFBML_parsed();}else{window.FB_XFBML_parsed=true;}};(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return;}js=d.createElement(s);js.id=id;js.async=true;js.src="//connect.facebook.net/<?php if($tbData->facebook['locale']) { echo $tbData->facebook['locale']; } else { echo 'en_US'; } ?>/all.js";fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'));</script>
<?php endif; ?>

<div id="wrapper" class="<?php echo $tbData->wrapper_css_classes; ?>">

  <?php // Logo Slot ----------------------------------------------------- ?>

  <?php $tbData->slotStartSystem('common/header.logo'); ?>
  <?php if($tbData['system.logo']['text_logo']): ?>
  <a id="site_logo" class="tb_text" href="<?php echo $home; ?>">
    <?php echo $name; ?>
  </a>
  <?php elseif ($logo): ?>
  <a id="site_logo" href="<?php echo $home; ?>">
    <img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>" />
  </a>
  <?php endif; ?>
  <script>
  $('.tb_wt_header_logo_system').parent().addClass('tbLogoCol');
  </script>
  <?php $tbData->slotStop(); ?>

  <?php // Search Slot -------------------------------------------------- ?>

  <?php $tbData->slotStartSystem('common/header.search'); ?>
  <?php echo $search; ?>
  <?php $tbData->slotStop(); ?>

  <?php // Cart Menu Slot ----------------------------------------------- ?>

  <?php $tbData->slotStartSystem('common/header.cart_menu'); ?>
  <?php if ($tbData->common['checkout_enabled']): ?>
  <?php echo $cart; ?>
  <?php endif; ?>
  <?php $tbData->slotStop(); ?>

  <?php // Welcome Slot ------------------------------------------------- ?>

  <?php $tbData->slotStartSystem('common/header.welcome'); ?>
  <div id="welcome_message" class="tb_text_wrap">
    <p><?php if (!$logged) echo $tbData->text_welcome; else echo $tbData->text_logged; ?></p>
  </div>
  <?php $tbData->slotStop(); ?>

  <?php // Language Slot ------------------------------------------------ ?>

  <?php $tbData->slotStartSystem('common/header.language'); ?>
  <?php echo $language; ?>
  <?php $tbData->slotStop(); ?>

  <?php // Currency Slot ------------------------------------------------ ?>

  <?php $tbData->slotStartSystem('common/header.currency'); ?>
  <?php echo $currency; ?>
  <?php $tbData->slotStop(); ?>


  <script type="text/javascript" data-capture="0">
  window.tb_wishlist_label = '<?php echo $text_wishlist; ?>';
  </script>

  <!-- END_COMMON_HEADER -->