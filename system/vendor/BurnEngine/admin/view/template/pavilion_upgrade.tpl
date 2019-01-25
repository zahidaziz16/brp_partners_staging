<?php echo $header; ?><?php echo $column_left; ?>

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
    .tb_loading {
      position: relative;
    }
    .tb_loading:before {
      content: '';
      z-index: 10;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      display: block;
      opacity: 0.8;
      background: #fff;
    }
    .tb_loading > * {
      pointer-events: none;
    }
    .tb_loading_bar,
    .tb_loading_bar_percent
    {
      position: relative;
      display: inline-block;
      padding: 25px 30px 25px 70px;
      font-weight: bold;
      color: #000;
      transition-delay: 0s !important;
      vertical-align: top;
      background: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    .tb_loading_bar:before,
    .tb_loading_bar:after
    {
      content: '';
      position: absolute;
      top: 50%;
      left: 30px;
      bottom: 0;
      display: block;
      width: 24px;
      height: 24px;
      margin-top: -12px;
      border-radius: 50%;
    }
    .tb_loading_bar:after {
      border: 2px solid;
      opacity: 0.15;
    }
    .tb_loading_bar:before {
      border-top: 2px solid;
      border-right: 2px solid;
      border-bottom: 2px solid transparent;
      border-left: 2px solid  transparent;
      border-collapse: collapse;
      opacity: 0.7;
      -webkit-animation-name: tb_rotate;
      -webkit-animation-duration: 0.5s;
      -webkit-animation-timing-function: linear;
      -webkit-animation-iteration-count: infinite;
      animation-name: tb_rotate;
      animation-duration: 0.5s;
      animation-timing-function: linear;
      animation-iteration-count: infinite;
    }
    .tb_loading_bar_percent {
      padding: 25px 30px;
    }
    .tb_loading_bar_percent .progress {
      margin-top: 15px;
      margin-bottom: 0;
    }
    .tb_loading > .tb_loading_bar_holder {
      z-index: 100;
      position: absolute;
      left: 0;
      right: 0;
      top: 150px;
      text-align: center;
    }
    #upgradeDialog,
    #upgradeDialog .tb_ovelay
    {
      position: fixed;
      z-index: 10000;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }
    #upgradeDialog .tb_ovelay {
      z-index: 10001;
      background: #fff;
      opacity: 0.7;
    }
    #upgradeDialog .tb_window {
      position: fixed;
      z-index: 10002;
      top: 50%;
      left: 50%;
      width: 440px;
      margin: -200px 0 0 -220px;
      padding: 30px;
      text-align: center;
      background: #fff;
      border-radius: 3px;
      box-shadow:
        0 1px 0 0 rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(0, 0, 0, 0.05),
        0 2px 20px rgba(0, 0, 0, 0.3);
    }
    #upgradeDialog .tb_window h3 {
      margin-bottom: 30px;
      font-size: 18px;
    }
    #upgradeDialog .tb_window select,
    #upgradeDialog .tb_window .btn
    {
      display: inline-block;
      line-height: 30px;
      height: 30px;
      vertical-align: top;
      font-size: 14px;
    }
    #upgradeDialog .tb_window select {
      width: 160px;
      margin-right: 10px;
      padding: 4px 10px;
    }
    #upgradeDialog .tb_window .btn {
      padding: 0 10px;
      line-height: 28px;
      font-weight: 600;
      border-radius: 3px;
    }
  </style>

  <div id="install_error">
    <h2><strong class="text-capitalize">BurnEngine</strong> is not ready yet</h2>
    <br />
    <div class="alert alert-danger">
      <span class="fa fa-exclamation-circle" style="margin: 11px 20px 9px 9px; font-size: 24px;"></span>
      <div>
        <p>BurnEngine and old versions of Pavilion (1.2.9 and backwards) cannot co-exist. You have to either <strong>upgrade</strong> or <strong>unistall</strong> the existing Pavilion module.</p>
        <p>It's <strong>strongly recommended to make a database backup</strong> before processing further. After switching to BurnEngine you won't be able to downgrade to Pavilion unless you restore a backup.</p>
      </div>
    </div>
    <ol>
      <li>If you don't want to transfer data (theme settings) from Pavilion to BurnEngine, click on the 'Uninstall' button bellow. <strong class="text-danger">All Pavilion settings will be lost </strong> and you have to set up your theme anew.</li>
      <li>If you wish to perform an upgrade and transfer the current Pavilion settings to <strong class="text-capitalize">BurnEngine</strong>, please click on the 'Upgrade' button. After the upgrade, Pavilion will be uninstalled automatically.</li>
      <li>If you are not sure which action to take, please check the <a href="http://docs.themeburn.com/burnengine/">official documentation</a> or visit the <a href="http://support.themeburn.com">support forums</a>.</li>
    </ol>
    <br />
    <div class="text-center tbActions">
      <a class="btn btn-primary btn-lg tbUninstall" href="<?php echo $uninstall_url; ?>">Uninstall Pavilion</a>
      &nbsp;&nbsp;&nbsp; <label class="control-label control-label-lg">OR</label> &nbsp;&nbsp;&nbsp;
      <a class="btn btn-primary btn-lg tbUpgrade" href="javascript:;">Upgrade Pavilion</a>
    </div>
  </div>

  <div id="upgradeDialog" style="display: none;">
    <div class="tb_window">
      <h3>Which Pavilion skin are you using as a base?</h3>
      <select id="theme_id">
        <option value="pavilion">Default</option>
        <option value="pavilion_stylish">Stylish</option>
        <option value="pavilion_shoppica">Shoppica</option>
        <option value="pavilion_minimal">Minimal</option>
        <option value="pavilion_dark">Dark</option>
      </select>
      <a class="btn btn-lg btn-primary tbDoUpgrade" href="<?php echo $upgrade_url; ?>">Do upgrade</a>
    </div>
    <div class="tb_ovelay"></div>
  </div>

</div>
<script>

  function uninstallPavilion() {
    var uninstall_url = $("#install_error")
      .addClass('tb_loading')
      .find("div.tb_loading_bar_holder").remove().end()
      .append('<div class="tb_loading_bar_holder"><span class="tb_loading_bar">Uninstalling Pavilion</span></div>')
      .find(".tbActions .tbUninstall").attr("href");

    var promise = $.get(uninstall_url);

    setTimeout(function() {
      promise.done(function() {
        $.get("<?php echo $refresh_mods_url; ?>", function() {
          location.href = "<?php echo $burnengine_url; ?>";
        });
      });
    }, 800);
  }

  function upgradePavilion() {
    $("#upgradeDialog").hide();

    $("#install_error")
            .addClass('tb_loading')
            .append('<div class="tb_loading_bar_holder"><span class="tb_loading_bar_percent">Upgrading Pavilion. Please, don\'t refresh the browser!<div class="progress"><div class="progress-bar" style="width: 0%;">0%</div></div></span></div>');

    var current_progress = 0;

    function upgradeProgress(url) {
      var jqXHR = $.getJSON(url, function(data) {
        if (data.success) {
          if (current_progress == Number(data.progress)) {
            alert('Something went wrong. Please, contact the support');
          }

          current_progress = Number(data.progress);

          if (current_progress < 100) {
            $(".progress-bar").css('width', current_progress + '%').text(current_progress + '%');
            upgradeProgress(url);
          } else {
            uninstallPavilion();
          }
        } else {
          alert('Something went wrong. Please, contact the support');
        }
      });

      jqXHR.fail(function(jqXHR) {
        alert(jqXHR.responseText);
      });
    }

    upgradeProgress($(this).attr("href") + '&theme_id=' + $("#theme_id").val());

    return false;
  }

  $("#install_error").find(".tbActions")
    .on("click", ".tbUninstall", function() {
      if (confirm("You are going to uninstall your existing Pavilion module and erase all of its settings.")) {
        uninstallPavilion();
      }

      return false;
    })
    .on("click", ".tbUpgrade", function() {
      $("#upgradeDialog").show().on("click", ".tbDoUpgrade", upgradePavilion);

      return false;
    });
</script>
<?php echo $footer; ?>