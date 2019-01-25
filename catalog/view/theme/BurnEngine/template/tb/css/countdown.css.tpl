.tb_counter {
  position: relative;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
       -ms-flex-align: stretch;
  -webkit-align-items: stretch;
          align-items: stretch;
  text-align: center;
}
h4 + .tb_counter.tb_style_1 {
  margin: 20px 0 10px 0 !important;
}
.tb_listing .tb_counter.tb_style_1 {
  margin-top: 20px !important;
}
.tb_counter.tb_style_1 .tb_counter_label,
.tb_counter.tb_style_1 .tb_counter_time
{
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
       -ms-flex-align: center;
  -webkit-align-items: center;
          align-items: center;
            -ms-flex-pack: center !important;
  -webkit-justify-content: center !important;
          justify-content: center !important;
}
.tb_counter.tb_style_1 .tb_counter_label {
      -ms-flex: 19 1 0px;
  -webkit-flex: 19 1 0px;
          flex: 19 1 0px;
  min-width: 80px;
  margin: 0;
  padding: <?php echo $base * 0.25; ?>px;
  text-transform: uppercase;
  font-weight: 600;
  -webkit-transition: all 0.3s;
          transition: all 0.3s;
}
.tb_counter.tb_style_1:hover .tb_counter_label {
  top: -<?php echo $base * 1.5; ?>px;
}
.tb_counter.tb_style_1 .tb_counter_time {
  position: relative;
  z-index: 2;
      -ms-flex: 81 1 200px;
  -webkit-flex: 81 1 200px;
          flex: 81 1 200px;
      -ms-flex-wrap: wrap;
  -webkit-flex-wrap: wrap;
          flex-wrap: wrap;
}
.tb_counter.tb_style_1 .tb_counter_time > span,
.tb_counter.tb_style_1 .tb_counter_time > span:after
{
  display: block;
  text-align: center;
}
.tb_counter.tb_style_1 .tb_counter_time > span {
      -ms-flex: 25 1 0px;
  -webkit-flex: 25 1 0px;
          flex: 25 1 0px;
  min-width: 50px;
  padding: 0.7em 0.1em 0.5em 0.1em !important;
  line-height: <?php echo $tbData->calculateLineHeight(18, $base); ?>px;
  font-size: 18px;
  font-weight: 600;
}
.tb_counter.tb_style_1 .tb_counter_time > span:after {
  line-height: <?php echo $base; ?>px;
  text-transform: uppercase;
  font-size: 10px;
  font-weight: normal;
  font-family: Arial;
  opacity: 0.5;
}
.tb_counter.tb_style_1 .tb_counter_days:after    { content: <?php echo "'" . $tbData->text_days    . "'"; ?>; }
.tb_counter.tb_style_1 .tb_counter_hours:after   { content: <?php echo "'" . $tbData->text_hours   . "'"; ?>; }
.tb_counter.tb_style_1 .tb_counter_minutes:after { content: <?php echo "'" . $tbData->text_minutes . "'"; ?>; }
.tb_counter.tb_style_1 .tb_counter_seconds:after { content: <?php echo "'" . $tbData->text_seconds . "'"; ?>; }