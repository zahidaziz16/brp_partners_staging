<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

<meta charset="UTF-8" />
<title>Image manager</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>

<link href="view/javascript/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet" />

<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />

<style type="text/css">
.modal-dialog,
.modal-content
{
  width: auto;
  margin: 0;
  border: 0 none;
  border-radius: 0;
  box-shadow: none;
}
.modal-header {
  color: #fff;
  background-color: #3289b8;
  border-bottom: 0 none;
}
.modal-body hr + .row {
  display: inline;
}
.modal-body .col-sm-3 {
  width: 20%;
}
</style>

</head>
<body>

<div id="modal-image">
  <?php echo $filemanager; ?>
</div>

<script type="text/javascript">

  function getURLVar(key) {
    var value = [];

    var query = String(document.location).split('?');

    if (query[1]) {
      var part = query[1].split('&');

      for (i = 0; i < part.length; i++) {
        var data = part[i].split('=');

        if (data[0] && data[1]) {
          value[data[0]] = data[1];
        }
      }

      if (value[key]) {
        return value[key];
      } else {
        return '';
      }
    }
  }

  if (window.getSelection) {
    if (window.getSelection().empty) {  // Chrome
      window.getSelection().empty();
    } else if (window.getSelection().removeAllRanges) {  // Firefox
      window.getSelection().removeAllRanges();
    }
  } else if (document.selection) {  // IE?
    document.selection.empty();
  }

  $("#modal-image").on("click", "img", function() {
    window.opener.CKEDITOR.tools.callFunction(getURLVar('CKEditorFuncNum'), $(this).parent().attr("href"));
    self.close();

    return false;
  });

  $("button.close").remove();
</script>
</body>