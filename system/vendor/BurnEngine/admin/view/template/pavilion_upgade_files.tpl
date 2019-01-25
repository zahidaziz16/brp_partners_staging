<div class="s_server_msg s_msg_yellow tbWarningMessage" style="padding: 10px 20px;">
    <div class="s_icon_32 s_error_32" style="display: table-cell;">
        <p>Some leftover Pavilion files/folders need to be removed:</p>
        <code style="display: block; margin: 0 15px; font-family: Monospace; font-weight: bold; font-size: 15px; color: inherit; background: transparent;">
            <?php foreach ($pavilion_files as $file): ?>
            <?php echo $file; ?><br />
            <?php endforeach; ?>
        </code>
        <?php if ($remove_pavilion_url): ?>
        <p>Please, click on the button to remove them automatically, or delete them manually from your server and refresh the BurnEngine CP.</p>
        <?php else: ?>
        <p>Please, delete them manually from your server.</p>
        <?php endif; ?>
    </div>
    <?php if ($remove_pavilion_url): ?>
    <div style="display: table-cell; width: 1%; vertical-align: middle;">
        <a id="tb_remove_pavilion_files" class="s_button s_red s_h_40" href="<?php echo $remove_pavilion_url; ?>" style="position: relative; z-index: 1; white-space: nowrap;">Remove Pavilion files</a>
    </div>
    <script>
        $("#tb_remove_pavilion_files").bind("click", function() {

            if (!confirm("Pavilion files are going to be wiped out. Please, make sure you don't have any important stuff in the Pavilion folders.")) {
                return false;
            }

            var $warning = $(this).closest(".tbWarningMessage");

            $warning.block();

            $.getJSON($(this).attr("href"), function(data) {
                if (data.success) {
                    location.reload();
                } else {
                    $warning.unblock();
                    alert("Some files were not removed ");
                }
            });

            return false;
        });
    </script>
    <?php endif; ?>
</div>