<?php echo $header; ?><?php echo $column_left; ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
<?php if (!$tbData['gteOc2']): ?>
<link rel="stylesheet" href="../system/vendor/BurnEngine/admin/view/stylesheet/bootstrap.min.css" />
<style type="text/css">
    .alert p {
        margin: 0;
    }
    .alert p + p {
        margin-top: 10px;
    }
    .alert-danger {
        background-color: #fef1f1;
        border: 1px solid #fcd9df;
        color: #f56b6b;
    }
    .btn {
        color: #fff !important;
    }
    .alert .fa {
        display: none;
    }
</style>
<?php endif; ?>

<div id="content">
    <style type="text/css">
        @-webkit-keyframes tb_rotate {
        from {transform: rotate(0deg);}
        to   {transform: rotate(359deg);}
        }
        @keyframes tb_rotate {
        from {transform: rotate(0deg);}
        to   {transform: rotate(359deg);}
        }

        #install_error {
            max-width: 780px;
            min-height: 500px;
            margin: 50px auto;
            line-height: 22px;
            font-size: 14px;
        }
        #install_error p:not(:last-child),
        #install_error ol:not(:last-child),
        #install_error li:not(:last-child)
        {
            margin-bottom: 20px;
        }
        #install_error p:not(:first-child),
        #install_error ol:not(:first-child),
        #install_error li:not(:first-child)
        {
            margin-top: 20px;
        }
        #install_error .alert {
            margin-bottom: 40px;
            padding: 19px;
        }
        #install_error .text-danger,
        #install_error .alert strong
        {
            color: #ea4343;
        }
        #install_error .alert > span {
            float: left;
            margin-right: 15px;
            font-size: 18px;
            line-height: inherit;
        }
        #install_error .alert div,
        #install_error .alert p
        {
            overflow: hidden;
        }
        #install_error .alert div p:last-child {
            margin-bottom: 0;
        }
    </style>

    <div id="install_error">
        <h2><strong class="text-capitalize">BurnEngine</strong> must be upgraded <?php if ($reason == 'engine'): ?>to v.<strong><?php echo $new_version; ?></strong><?php else: ?> due to OpenCart version change<?php endif; ?></h2>
        <br />
        <div class="alert alert-danger">
            <span class="fa fa-exclamation-circle" style="font-size: 24px;"></span>
            <div>
                <p>It's strongly recommended to make a <strong>database backup</strong> before processing further.</p>
            </div>
        </div>
        <ol>
            <?php if ($reason == 'engine'): ?>
            <li>The currently installed version is <strong><?php echo $current_version; ?></strong>, while the uploaded version is <strong><?php echo $new_version; ?></strong></li>
            <?php elseif ($oc_version): ?>
            <li>BurnEngine was installed on OpenCart v.<strong><?php echo $oc_version; ?></strong>, while the current version is <strong><?php echo VERSION; ?></strong></li>
            <?php endif; ?>
            <li>To perform an upgrade, please click on the 'Upgrade BurnEngine' button.</li>
            <li>If you are not sure what to do, please check the <a href="">official documentation</a> or visit the <a href="http://support.themeburn.com">support forums</a>.</li>
        </ol>
        <br />
        <div class="text-center tbActions">
            <a class="btn btn-primary btn-lg tbUpgrade" href="<?php echo $upgrade_url; ?>" data-refresh_mods_url="<?php echo $refresh_mods_url; ?>">Upgrade BurnEngine</a>
        </div>
    </div>

</div>
<script>
    $("#content .tbUpgrade").bind("click", function() {
        if (!confirm("Are you sure?")) {
            return false;
        }

        var $aEl =$(this);

        $("#install_error").block();

        $.getJSON($aEl.attr("href"), function(data) {
            if (data.success) {
                $.get($aEl.data("refresh_mods_url"), function() {
                    window.location.hash = '#tb_cp_panel_theme_settings,color_settings_tab';
                    window.location.reload(true);
                });
            } else {
                alert('Something went wrong. Please, contact the support');
            }
        });

        return false;
    });
</script>
<?php echo $footer; ?>