<form action='<?php echo $action ?>' method='post'  class="form-horizontal" id='upay_payment_form' name='upay_payment_form'>
              <?php 
              
              
              ?>
              <?php echo implode('', $upay_args_array) ?>
              <div class="buttons">
              <div class="pull-right">
                <input type='submit' class="btn btn-primary" id='button-confirm' value='<?php echo $button ?>' />
                  
              </div>
                  </div>

</form>