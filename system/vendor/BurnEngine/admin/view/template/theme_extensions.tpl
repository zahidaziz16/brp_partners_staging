<div class="tb_subpanel" id="extensions_listing">
  <h2><span>Extensions</span></h2>

  <table class="s_table_1" width="100%" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr>
      <th class="align_left"><?php echo $text_extensions; ?></th>
      <th width="1"><?php echo $text_actions; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($not_installed_extensions as $extension): ?>
    <tr class="s_open">
      <td class="align_left"><?php echo $extension->getInitOption('title'); ?></td>
      <td><a class="install_button s_button s_h_20 s_white s_icon_10 s_plus_10 tbInstallAction" href="<?php echo $tbUrl->generate('extensions/installExtension', 'name=' . $extension->getName()); ?>"><?php echo $text_install; ?></a></td>
    </tr>
    <?php endforeach; ?>
    <?php foreach ($installed_extensions as $extension): ?>
    <?php if (!$extension->isCoreExtension()): ?>
    <tr class="s_open">
      <td class="align_left"><?php echo $extension->getInitOption('title'); ?></td>
      <td class="align_right">
        <div class="btn-group">
          <?php if ($extension->getInitOption('actions')): ?>
          <?php foreach ($extension->getInitOption('actions') as $action): ?>
          <a class="s_button s_white s_h_20 <?php echo ($action['type'])?>" href="<?php echo $action['url']; ?>" title="<?php echo $action['title']; ?>"></a> |
          <?php endforeach; ?>
          <?php endif; ?>
          <?php if ($extension->canEdit()): ?>
          <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 edit_button tbEditAction" href="<?php echo $tbUrl->generate('default/index', 'extension=' . $extension->getName()); ?>" title="Edit"></a>
          <?php endif; ?>
          <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 uninstall_button tbUninstallAction" href="<?php echo $tbUrl->generate('extensions/uninstallExtension', 'name=' . $extension->getName()); ?>" title="<?php echo $text_uninstall; ?>"></a>
        </div>
      </td>
    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script type="text/javascript">
$("#extensions_listing").on("click", ".tbUninstallAction, .tbInstallAction", function() {

  if ($(this).is(".tbUninstallAction") && !confirm("Are you sure?")) {
    return false;
  }

  $("#extensions_listing").block({ message: "<h1>Loading...</h1>" });

  jQuery.getJSON($(this).attr("href"), function(data) {
    if (data.reload == 1) {
      location.reload();
    } else {
      $("#extensions_listing").unblock();

      if (data.success == true) {
        displayAlertSuccess(data.message);
      } else
      if (data.success == false) {
        displayAlertWarning(data.message);
      }
    }
  });

  return false;
}).on("click", ".tbEditAction", function() {
  var $container = $("#extensions_listing");

  $container.block({ message: "<h1>Loading...</h1>" });
  $container.parent().load($(this).attr("href"), function() {
    $container.unblock();
  });

  return false;
});
</script>