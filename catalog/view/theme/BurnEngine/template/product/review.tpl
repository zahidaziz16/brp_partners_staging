<?php if ($reviews) { $i = 0; $j = count($reviews); ?>
<div class="tb_listing tb_list_view">
  <?php foreach ($reviews as $review) { ?>
  <div class="tb_review<?php if($i == $j-1) echo ' last'; ?>">
    <div class="tb_meta">
      <p class="tb_author"><strong><?php echo $review['author']; ?></strong><small>(<?php echo $review['date_added']; ?>)</small></p>
      <div class="rating">
        <div class="tb_bar">
          <span class="tb_percent" style="width: <?php echo $review['rating'] * 20; ?>%;"></span>
          <span class="tb_base"></span>
        </div>
        <span class="tb_average"><?php echo $review['rating']; ?>/5</span>
      </div>
    </div>
    <p><?php echo $review['text']; ?></p>
  </div>
  <?php $i++; } ?>
</div>
<div class="pagination">
  <?php $pagination = str_replace('pagination', 'links', $pagination); ?>
  <?php echo $pagination; ?>
</div>
<?php } else { ?>
<p class="tb_empty tb_bg_str_1 tb_mb_0"><?php echo $text_no_reviews; ?></p>
<?php } ?>
