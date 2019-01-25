.alert {
  position: relative;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
  min-height: <?php echo $base * 3; ?>px;
  margin-bottom: <?php echo $base * 1.5; ?>px;
  padding: <?php echo $base - 1; ?>px;
}
.alert > .fa {
      -ms-flex: 0 1 auto !important;
  -webkit-flex: 0 1 auto !important;
          flex: 0 1 auto !important;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: 0.5em !important;
  <?php else: ?>
  margin-left: 0.5em !important;
  <?php endif; ?>
}
.alert:last-child {
  margin-bottom: 0;
}
.alert h4 {
  color: inherit;
}
.alert .alert-link {
  font-weight: bold;
}
.alert > p,
.alert > ul {
  margin-bottom: 0;
}
.alert > p + p {
  margin-top: 5px;
}
.alert-dismissable,
.alert-dismissible {
  padding-right: 35px;
}
.alert[class] a.close,
.alert[class] button.close
{
  position: absolute;
  top: 50%;
  right: <?php echo $base * 0.5; ?>px;
  width: 30px;
  height: 30px;
  margin-top: -15px;
  padding: 0;
  line-height: 30px !important;
  text-align: center;
  letter-spacing: 0;
  word-spacing: 0;
  font-size: 24px;
  font-family: Open Sans, Arial, sans-serif;
  color: inherit !important;
  opacity: 0.6;
  background-color: transparent !important;
  box-shadow: none;
}
.alert[class] .close:hover {
  opacity: 1;
}
.alert-success {
  background-color: #dff0d8;
  border-color: #d6e9c6;
  color: #3c763d;
}
.alert-success hr {
  border-top-color: #c9e2b3;
}
.alert-success .alert-link {
  color: #2b542c;
}
.alert-info {
  background-color: #d9edf7;
  border-color: #bce8f1;
  color: #31708f;
}
.alert-info hr {
  border-top-color: #a6e1ec;
}
.alert-info .alert-link {
  color: #245269;
}
.alert-warning {
  background-color: #fcf8e3;
  border-color: #faebcc;
  color: #8a6d3b;
}
.alert-warning hr {
  border-top-color: #f7e1b5;
}
.alert-warning .alert-link {
  color: #66512c;
}
.alert-danger {
  background-color: #f2dede;
  border-color: #ebccd1;
  color: #a94442;
}
.alert-danger hr {
  border-top-color: #e4b9c0;
}
.alert-danger .alert-link {
  color: #843534;
}

/*** Colors ***/

.alert-success {
  color: green;
  background: #f4fbe4;
  border: 1px solid #dff1ba;
}
.alert-info {
  color: #39688a;
  background: #e9f6ff;
  border: 1px solid #d5e4ef;
}
.alert-warning {
  color: #726300;
  background: #fffcd9;
  border: 1px solid #f3e59a;
}
.alert-danger {
  color: #d60000;
  background: #ffede5;
  border: 1px solid #ffd8c3;
}

/*** Colors ***/

.alert .mdi {
  vertical-align: top;
}