<h2>Subscribers</h2>

<div class="s_actions">
  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbDeleteSubscribers" href="<?php echo $tbUrl->generate('default/deleteSubscribers'); ?>">Delete</a>
</div>

<div class="tb_data_holder">
  <table class="s_table_1 tb_seo_editor_table tbProductsListingTable" cellpadding="0" cellspacing="0" border="0">
    <thead>
      <tr>
        <th width="10"><input type="checkbox" class="subscribersToggle" /></th>
        <?php if ($settings['show_name']): ?>
        <th>Name</th>
        <?php endif; ?>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($subscribers as $subscriber): ?>
      <tr class="s_open">
        <td><input type="checkbox" name="selected_subscribers[]" value="<?php echo $subscriber['id']; ?>"/></td>
        <?php if ($settings['show_name']): ?>
        <td><?php echo $subscriber['name']; ?></td>
        <?php endif; ?>
        <td><?php echo $subscriber['email']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pagination"><?php echo $pagination; ?></div>
</div>

<div class="s_submit clearfix">
  <div class="left">
    <a class="s_button s_white s_h_40 tbButtonBackToExtensions">Back to extensions</a>
  </div>
  <div class="right">
    <a class="s_button s_red s_h_40 tbExportSubscribers" href="<?php echo $tbUrl->generate('default/exportSubscribers'); ?>">Export</a>
  </div>
</div>