<?php
foreach ($jsarr as $key => $value) {
  if (is_array($value) || is_object($value)) {
    $value_str = json_encode($value);
  } else {
    $value_str = "'" . $value . "'";
  }
  echo "\$sReg.set('$key', $value_str);";
}

