<?php // ------------------------------------------------------------- ?>
<?php // ---   HEADER   ---------------------------------------------- ?>
<?php // ------------------------------------------------------------- ?>
<?php $tbData->slotEcho('oc_header'); ?>

<?php if ($tbData->hasArea('header')): ?>
<header<?php echo $tbData->header_section_attributes; ?>>
  <?php echo $tbData->renderArea('header'); ?>
</header>
<?php endif; ?>
<?php // ---   end HEADER   ------------------------------------------ ?>


<?php // ------------------------------------------------------------- ?>
<?php // ---   INTRO   ----------------------------------------------- ?>
<?php // ------------------------------------------------------------- ?>
<?php if ($tbData->hasArea('intro')): ?>
<section<?php echo $tbData->intro_section_attributes; ?>>
  <?php echo $tbData->renderArea('intro'); ?>
</section>
<?php endif; ?>
<?php // ---   end INTRO   ------------------------------------------- ?>


<?php // ------------------------------------------------------------- ?>
<?php // ---   CONTENT   --------------------------------------------- ?>
<?php // ------------------------------------------------------------- ?>
<?php if ($tbData->hasArea('content') || $tbData->hasArea('column_left') || $tbData->hasArea('column_right')): ?>
<section<?php echo $tbData->content_section_attributes; ?>>
  <?php if (!empty($tbData->system['critical_css'])): ?>
  <span class="tb_loading_bar"></span>
  <?php endif; ?>
  <div class="<?php echo $tbData->content_row_css_classes; ?>">

    <?php // ------------------------------------------------------------- ?>
    <?php // ---   MAIN COLUMN   ----------------------------------------- ?>
    <?php // ------------------------------------------------------------- ?>
    <div class="main col col-xs-12 col-sm-fill col-md-fill">
      <?php echo $tbData->renderArea('content'); ?>
    </div>

    <?php // ------------------------------------------------------------- ?>
    <?php // ---   LEFT COLUMN   ----------------------------------------- ?>
    <?php // ------------------------------------------------------------- ?>
    <?php if ($tbData->hasArea('column_left')): ?>
    <aside id="left_col" class="sidebar col col-xs-12 col-sm-auto col-md-auto">
      <?php echo $tbData->renderArea('column_left'); ?>
    </aside>
    <?php endif; ?>
    <?php // ---   end LEFT COLUMN   ------------------------------------- ?>

    <?php // ------------------------------------------------------------- ?>
    <?php // ---   RIGHT COLUMN   ---------------------------------------- ?>
    <?php // ------------------------------------------------------------- ?>
    <?php if ($tbData->hasArea('column_right')): ?>
    <aside id="right_col" class="sidebar col col-xs-12 col-sm-auto col-md-auto">
      <?php echo $tbData->renderArea('column_right'); ?>
    </aside>
    <?php endif; ?>
    <?php // ---   end RIGHT COLUMN   ------------------------------------ ?>

  </div>
</section>
<?php endif; ?>
<?php // ---   end CONTENT   ----------------------------------------- ?>


<?php // ------------------------------------------------------------- ?>
<?php // ---   FOOTER   ---------------------------------------------- ?>
<?php // ------------------------------------------------------------- ?>
<?php if ($tbData->hasArea('footer')): ?>
<section<?php echo $tbData->footer_section_attributes; ?>>
  <?php echo $tbData->renderArea('footer'); ?>
</section>
<?php endif; ?>
<?php // ---   end FOOTER   ------------------------------------------ ?>


<?php // ------------------------------------------------------------- ?>
<?php // ---   BOTTOM   ---------------------------------------------- ?>
<?php // ------------------------------------------------------------- ?>
<?php $tbData->slotEcho('oc_footer'); ?>
<?php // ---   end BOTTOM   ------------------------------------------ ?>