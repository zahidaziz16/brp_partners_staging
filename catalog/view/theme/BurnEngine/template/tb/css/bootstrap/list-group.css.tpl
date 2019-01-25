.list-group {
  list-style: none !important;
}
.list-group > .list-group-item {
  position: relative;
  display: table;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.list-group li.list-group-item:before,
.list-group a.list-group-item:before
{
  content: '\f105';
  display: table-cell;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  width: <?php echo $base / 2 + 4; ?>px;
  vertical-align: top;
  font-size: 12px;
  font-family: FontAwesome;
}
