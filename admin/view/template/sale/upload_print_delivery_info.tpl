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

<div class="container">
    <div style="page-break-after: always;">
        <a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>">
        	<img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="width:25%; margin-bottom:20px; border:none;" />
        </a>
        <h1 style="vertical-align:middle; padding-top:20px">
            <?php echo $heading_title; ?> #<?php echo $unique_order_id; ?>&nbsp;&nbsp;&nbsp;
            <span>
                <img src="view/barcode_gen/barcode.php?code=<?php echo $unique_order_id; ?>" onerror="this.src='view/image/dot.png';" />
            </span>
        </h1>

    	<table class="table table-bordered">
          <thead>
            <tr>
              <td style="width: 50%;" class="text-left"><?php echo $text_payment_address; ?></td>
              <?php if ($shipping_method) { ?>
              <td style="width: 50%;" class="text-left"><?php echo $text_shipping_address; ?></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-left"><?php echo $payment_address; ?></td>
              <?php if ($shipping_method) { ?>
              <td class="text-left"><?php echo $shipping_address; ?></td>
              <?php } ?>
            </tr>
          </tbody>
        </table>
        Delivery Detail List
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_model; ?></td>
              <td class="text-right"><?php echo $column_quantity; ?></td>
              <td class="text-right">Configure Delivery</td>
              <td class="text-right"><?php echo $column_price; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr <?php if($product["configure_delivery"]=="Gohoffice" || $product["configure_delivery"]=="Own Arrangement") { ?> style="background-color:#CCC" <?php } ?>>
              <td class="text-left"><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } else { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?>
                <?php } ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-right">
                  <?php 
                  	if($product["configure_delivery"]=="BRP Warehouse Gohoffice") {
                         echo $product['wms_quantity']; 
                  	} else {
                        echo $product['quantity']; 
                    }
                  ?>
              </td>
              <td class="text-left">
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
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="text-left"><?php echo $voucher['description']; ?></td>
              <td class="text-left"></td>
              <td class="text-right">1</td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td colspan="5" class="text-right"><?php echo $total['title']; ?></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php if ($comment) { ?>
        <table class="table table-bordered">
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
</div>
<script type="text/javascript">
	setTimeout(function(){window.print();},1000);
</script>
</body>
</html>