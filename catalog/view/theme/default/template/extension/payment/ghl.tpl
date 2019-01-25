<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="TransactionType" value="SALE"> 
  <input type="hidden" name="PymtMethod" value="ANY"> 
  <input type="hidden" name="ServiceID" value="<?php echo $merchant_id; ?>"> 
  <input type="hidden" name="PaymentID" value="<?php echo $order_id; ?>">
  <input type="hidden" name="OrderNumber" value="<?php echo $order_id; ?>">
  <input type="hidden" name="PaymentDesc" value="<?php echo $description; ?>"> 
  <input type="hidden" name="MerchantReturnURL" value="<?php echo $return_url; ?>">
  <input type="hidden" name="MerchantCallBackURL" value="<?php echo $server_callback; ?>">
  <input type="hidden" name="Amount" value="<?php echo $amount; ?>"> 
  <input type="hidden" name="CurrencyCode" value="<?php echo strtoupper($currency); ?>"> 
  <input type="hidden" name="CustIP" value="<?php echo $ip_address; ?>"> 
  <input type="hidden" name="CustName" value="<?php echo $name; ?>"> 
  <input type="hidden" name="CustEmail" value="<?php echo $email; ?>"> 
  <input type="hidden" name="CustPhone" value="<?php echo $telephone; ?>"> 
  <input type="hidden" name="PageTimeout" value="<?php echo $page_timeout; ?>"> 
  <input type="hidden" name="HashValue" value="<?php echo $digest; ?>"> 

  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
