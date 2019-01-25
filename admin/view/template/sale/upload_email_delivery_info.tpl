<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="en">
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
    <a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>">
        <img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="width:40%; margin-bottom:20px; border:none;" />
    </a>
    <h1 style="vertical-align:middle; padding-top:10px">
        <?php echo $heading_title; ?> #<?php echo $unique_order_id; ?>&nbsp;&nbsp;&nbsp;
        <span>
            <img src="view/barcode_gen/barcode.php?code=<?php echo $unique_order_id; ?>" onerror="this.src='view/image/dot.png';" />
        </span>
    </h1>

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
    <h4><?php echo "Delivery Detail List"; ?></h4>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
      <thead>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 20%;"><b><?php echo $column_product; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 20%;"><b><?php echo $column_model; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><b><?php echo $column_quantity; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><b>Configure Delivery</b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><b><?php echo $column_price; ?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr <?php if($product["configure_delivery"]=="Gohoffice" || $product["configure_delivery"]=="Own Arrangement") { ?> style="background-color:#CCC" <?php } ?>>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 20%;">
          <?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            <?php if ($option['type'] != 'file') { ?>
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } else { ?>
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?>
            <?php } ?></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 20%;"><?php echo $product['model']; ?></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;">
              <?php 
                if($product["configure_delivery"]=="BRP Warehouse Gohoffice") {
                     echo $product['wms_quantity']; 
                } else {
                    echo $product['quantity']; 
                }
              ?>
          </td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;">
          	<?php if($product["configure_delivery"]=="Gohoffice") { ?>
                By G.I
            <?php } else if($product["configure_delivery"]=="BRP Warehouse") { ?>
                By BRP Warehouse
            <?php } else if($product["configure_delivery"]=="BRP Warehouse Gohoffice") { ?>
                By BRP Warehouse &amp; Remainder by G.I
            <?php } else if($product["configure_delivery"]=="Own Arrangement") { ?>
                Own Arrangement
            <?php } else { ?>
                -
            <?php } ?>
          </td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><?php echo $product['price']; ?></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 20%;"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; width: 20%;"></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;">1</td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><?php echo $voucher['amount']; ?></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td colspan="5" style="text-align: right;"><?php echo $total['title']; ?></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px; width: 20%;"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if ($comment) { ?>
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
      <thead>
        <tr>
          <td><?php echo $text_comment; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $comment; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
</div>
</body>
</html>