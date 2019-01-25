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
        <h2><strong class="text-capitalize">BurnEngine</strong> is not enabled for store <strong><?php echo $store['name']; ?></strong></h2>
        <br />
        <?php if ($theme_error): ?>
        <div class="alert alert-danger">
            <span class="fa fa-exclamation-circle" style="font-size: 24px;"></span>
            <div>
                <p>It looks that you can't enable BurnEngine because of errors when trying to retrieve the default theme:<br /> <strong><?php echo $theme_error; ?></strong></p>
                <p>Please, correct the error and try again.</p>
            </div>
        </div>
        <p>If you are not sure what to do, please check the <a href="">official documentation</a> or visit the <a href="http://support.themeburn.com">support forums</a>.</p>
        <?php else: ?>
        <ol>
            <li>Click on the 'Enable BurnEngine' in order to select BurnEngine for the current store.</li>
            <li>If you don't want to use BurnEngine for <strong><?php echo $store['name']; ?></strong> <a href="<?php echo $default_store_url; ?>">click here</a> to return to the default store.</li>
        </ol>
        <br />
        <div class="text-center tbActions">
            <a class="btn btn-primary btn-lg tbEnable" href="<?php echo $enable_store_url; ?>">Enable BurnEngine</a>
        </div>
        <?php endif; ?>
    </div>

</div>
<script>
    $("#install_error .tbEnable").bind("click", function() {

        $("#install_error").block();

        $.getJSON($(this).attr("href"), function(data) {
            if (data.success) {
                location.reload();
            } else {
                $("#install_error").unblock();
                alert(data.message + ' Something went wrong. Please, contact the support');
            }
        });

        return false;
    });
</script>
<?php echo $footer; ?>