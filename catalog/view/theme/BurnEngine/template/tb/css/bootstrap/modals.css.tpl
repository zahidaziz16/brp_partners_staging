.modal {
  position: fixed;
  z-index: 1040;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  overflow: hidden;
  display: none;
  -webkit-overflow-scrolling: touch;
  outline: 0;
  background-color: rgba(255, 255, 255, 0.6);
}
[data-toggle="modal"] {
  cursor: pointer !important;
}
.modal.fade .modal-dialog {
  -webkit-transform: translate(0,-30px);
          transform: translate(0,-30px);
  -webkit-transition: -webkit-transform 0.3s ease-out;
          transition: transform 0.3s ease-out;
}
.modal.fade.in .modal-dialog {
  -webkit-transform: translate(0, 0);
          transform: translate(0, 0);
}
.modal-open {
  overflow: hidden;
}
.modal-open .modal {
  overflow-x: hidden;
  overflow-y: auto;
}
.modal-dialog {
  position: relative;
  width: auto;
  margin: <?php echo $base; ?>px;
}
.modal-content {
  position: relative;
  background-color: #ffffff;
  border-radius: 2px;
  box-shadow:
  0 1px 0 0 rgba(0, 0, 0, 0.1),
  0 0 0 1px rgba(0, 0, 0, 0.08),
  0 1px 5px 0 rgba(0, 0, 0, 0.2);
  outline: 0;
}
.modal-backdrop {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  background-color: #fff;
}
.modal-backdrop.fade {
  opacity: 0;
}
.modal-backdrop.fade.in {
  opacity: 0.6;
}

/*** Header ***/

.modal-header {
  position: relative;
  padding: <?php echo $base; ?>px <?php echo $base * 1.5; ?>px;
  border-top-left-radius: inherit;
  border-top-right-radius: inherit;
}
.modal-header .close,
.modal-header .close:before,
.modal-body > .close,
.modal-body > .close:before
{
  position: absolute;
  top: 50%;
  <?php if ($lang_dir == 'ltr'): ?>
  right: <?php echo $base - 2; ?>px;
  <?php else: ?>
  left: <?php echo $base - 2; ?>px;
  <?php endif; ?>
  display: block;
  width: <?php echo $base * 1.5 - 4; ?>px;
  height: <?php echo $base * 1.5 - 4; ?>px;
  margin-top: -<?php echo $base * 0.75 - 2; ?>px;
  padding: 0;
  line-height: <?php echo $base * 1.5 - 4; ?>px;
  text-align: center;
  letter-spacing: 0;
  word-spacing: 0;
  font-size: 20px;
  color: inherit;
  background-color: transparent !important;
  box-shadow: none !important;
}
.modal-header .close,
.modal-body > .close
{
  opacity: 0.6;
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
}
.modal-header .close:before,
.modal-body > .close:before
{
  left: 0;
  right: 0;
  content: '\274c';
  content: '\2716';
  content: '\2715';
  font-family: FontAwesome;
}
.modal-header .close:hover,
.modal-body > .close:hover
{
  opacity: 1;
}
.modal-header .close span,
.modal-header .close svg,
.modal-body > .close span,
.modal-body > .close svg
{
  display: none;
}
.modal-title {
  margin: 0;
  color: inherit;
}

/*** Body ***/

.modal-body {
  position: relative;
  padding: <?php echo $base * 1.5; ?>px;
}
.modal-body > .close {
  top: <?php echo $base * 1.5 - 4; ?>px;
  right: <?php echo $base * 1.5 - 4; ?>px;
  margin: 0;
}

/*** Footer ***/

.modal-footer {
  padding: <?php echo $base; ?>px;
  text-align: right;
  border-top: 1px solid #ddd;
}
.modal-scrollbar-measure {
  position: absolute;
  top: -9999px;
  width: 50px;
  height: 50px;
  overflow: scroll;
}
@media (min-width: <?php echo ($screen_sm + 1) . 'px'; ?>) {
  .modal-dialog {
    width: 600px;
    margin: 30px auto;
  }
  .modal-sm {
    width: 300px;
  }
}
@media (min-width: <?php echo ($screen_md + 1) . 'px'; ?>) {
  .modal-lg {
    width: 900px;
  }
}
