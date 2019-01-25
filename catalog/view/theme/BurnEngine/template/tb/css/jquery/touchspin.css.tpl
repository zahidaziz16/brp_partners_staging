.bootstrap-touchspin {
  width: auto;
}
.bootstrap-touchspin > input {
  padding: 0 !important;
}
.bootstrap-touchspin .btn-group-vertical {
  display: -ms-inline-flexbox;
  display: -webkit-inline-flex;
  display: inline-flex;
      -ms-flex: 0 0 auto;
  -webkit-flex: 0 0 auto;
          flex: 0 0 auto;
      -ms-flex-direction: column;
  -webkit-flex-direction: column;
          flex-direction: column;
}
.bootstrap-touchspin .btn {
  -ms-flex: 1 1 0;
  -webkit-flex: 1 1 0;
  flex: 1 1 0;
  width: 1.5em;
  min-width: <?php echo $base; ?>px;
  padding: 0;
}
.bootstrap-touchspin .btn i,
.bootstrap-touchspin .btn i:before
{
  margin: 0;
  font-size: 12px;
}
.bootstrap-touchspin .bootstrap-touchspin-up {
  <?php if ($lang_dir == 'ltr'): ?>
  border-radius: 0 2px 0 0 !important;
  <?php else: ?>
  border-radius: 2px 0 0 0 !important;
  <?php endif; ?>
}
.bootstrap-touchspin .bootstrap-touchspin-down {
  <?php if ($lang_dir == 'ltr'): ?>
  border-radius: 0 0 2px 0 !important;
  <?php else: ?>
  border-radius: 0 0 0 2px !important;
  <?php endif; ?>
}
