<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<script src="view/javascript/common.js" type="text/javascript"></script>
</head>
<body>

<div style="width: 680px;">
	<br /><br />
    <a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>">
    	<img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="width:40%; margin-bottom:20px; border:none;" />
    </a>
        <label style='float:right;text-align: right;font-weight: normal;'>
            <b>GENUINE INSIDE (M) SDN BHD (</b><i>725262-T</i><b>)</b><br>
            NO.2 JALAN BS 9/10, <br>
            TAMAN BUKIT SERDANG SEK9, <br>
            43300 SERI KEMBANGAN , SELANGOR.
        </label>
    <h1 style="vertical-align:middle; padding-top:10px">
        <?php echo $heading_title; ?> #<?php echo $barcode; ?>&nbsp;&nbsp;&nbsp;
        <span>
            <img src="view/barcode_gen/barcode.php?code=<?php echo $barcode; ?>" onerror="this.src='view/image/dot.png';" />
        </span>
    </h1>
 	
    <?php /*
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
      <thead>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $text_payment_address; ?></b></td>
          <?php if ($shipping_method) { ?>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $text_shipping_address; ?></b></td>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $payment_address; ?></td>
          <?php if ($shipping_method) { ?>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $shipping_address; ?></td>
          <?php } ?>
        </tr>
      </tbody>
    </table>
    */ ?>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
      <thead>
      </thead>
      <tbody>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_transaction_no; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $transaction_no; ?></td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_remarks; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $remarks; ?></td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_received_datetime; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $received_datetime; ?></td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_num_of_bin; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $num_of_bin; ?></td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_actual_num_of_bin; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $actual_num_of_bin; ?></td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_ecowarehouse_sync_status; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $ecowarehouse_sync_status; ?></td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><b><?php echo $entry_warehouseeco_sync_status; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 50%;"><?php echo $warehouseeco_sync_status; ?></td>
        </tr>
      </tbody>
    </table>
    
    <h4><?php echo $text_detail_list; ?></h4>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
      <thead>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 8%;"><b><?php echo $column_row_no; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 17%;"><b><?php echo $column_product_name; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 15%;"><b><?php echo $column_product_model; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 15%;"><b><?php echo $column_product_type; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 5%;"><b><?php echo $column_quantity; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 15%;"><b><?php echo $column_remarks; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php $trans_row = 0; ?>
        <?php if ($trans) { ?>
            <?php foreach ($trans as $tran) { ?>
                <tr id="tran-row">
                    <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 8%;">
                        <?php echo $tran['row_no']; ?>
                    </td>
                    <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 17%;">
                        <?php echo $tran['product_name']; ?>
                    </td>
                    <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 15%;">
                        <?php echo $tran['product_model']; ?>
                    </td>
                    <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 15%;">
                        <?php echo $tran['product_type']; ?>
                    </td>
                    <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 5%;">
                        <?php echo $tran['quantity']; ?>
                    </td>
                    <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 15%;">
                        <?php echo $tran['remarks']; ?>
                    </td>
                </tr>
                <?php $trans_row++; ?>
            <?php } ?>
        <?php } else { ?>
            <tr>
              <td colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
</div>
</body>
</html>