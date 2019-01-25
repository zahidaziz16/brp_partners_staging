<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('information/sitemap.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('information/sitemap.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('information/sitemap.page_content'); ?>
<div class="tb_sitemap">
  <h2><?php echo $tbData->text_categories; ?></h2>
  <ul class="tb_list_1">
    <?php foreach ($categories as $category_1) { ?>
    <li><a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
      <?php if ($category_1['children']) { ?>
      <ul class="tb_list_1">
        <?php foreach ($category_1['children'] as $category_2) { ?>
        <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
          <?php if ($category_2['children']) { ?>
          <ul class="tb_list_1">
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <li><a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a></li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>

  <h2><?php echo $tbData->text_account_account; ?></h2>
  <ul class="tb_list_1">
    <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
      <ul class="tb_list_1">
        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
        <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
        <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      </ul>
    </li>
    <li><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a></li>
    <li><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
    <li><a href="<?php echo $search; ?>"><?php echo $text_search; ?></a></li>
  </ul>

  <h2><?php echo $text_information; ?></h2>
  <ul class="tb_list_1">
    <?php foreach ($informations as $information) { ?>
    <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
    <?php } ?>
    <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
  </ul>
</div>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>
