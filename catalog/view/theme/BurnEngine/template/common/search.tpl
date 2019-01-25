<div id="search">
  <div class="tb_search_wrap
              form-group-<?php echo $tbData['system.search']['size']; ?>
              <?php if($tbData['system.search']['style'] == 3 && !empty($tbData['system.search']['search_icon'])): ?>
              <?php echo $tbData['system.search']['search_icon']; ?>
              <?php endif; ?>
              ">
    <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search for products (enter 3 characters or more to perform a live search)." id="filter_keyword" class="input-<?php echo $tbData['system.search']['size']; ?>" />
    <a id="search_button"
       class="tb_search_button btn
              <?php if($tbData['system.search']['style'] ==  1): ?>
              btn-default
              <?php endif; ?>
              <?php if($tbData['system.search']['style'] != 3 && !empty($tbData['system.search']['search_icon'])): ?>
              <?php echo $tbData['system.search']['search_icon']; ?>
              <?php endif; ?>
              btn-<?php echo $tbData['system.search']['size']; ?>"
       href="javascript:;"
       title="<?php echo $text_search; ?>"></a>
  </div>
</div>