/*  -----------------------------------------------------------------------------------------
    Floats
-----------------------------------------------------------------------------------------  */

.z-index-1            { z-index: 1; }
.z-index-2            { z-index: 2; }
.z-index-3            { z-index: 3; }
.z-index-4            { z-index: 4; }
.z-index-5            { z-index: 5; }

/*  -----------------------------------------------------------------------------------------
    Floats
-----------------------------------------------------------------------------------------  */

.clear                { clear: both; }
.pull-right           { float: right !important; }
.pull-left            { float: left !important;  }
.nofloat              { float: none !important;  }
.center-block         { display: block; float: none; margin-left: auto; margin-right: auto; }

/*  -----------------------------------------------------------------------------------------
    Content toggle
-----------------------------------------------------------------------------------------  */

.hide,
.ui-tabs-hide         { display: none !important; }
.show,
.block,
.display-block        { display: block !important;        }
.display-inline       { display: inline !important;       }
.display-inline-block { display: inline-block !important; }
.display-table        { display: table !important;        }
.display-table-row    { display: table-row !important;    }
.display-table-cell   { display: table-cell !important;   }
.invisible            { visibility: hidden; }
.flex-fill            {     -ms-flex: 1 1 auto;
                        -webkit-flex: 1 1 auto;
                                flex: 1 1 auto;
                      }
.flex-basis-0         {
                            -ms-flex-basis: 0px;
                        -webkit-flex-basis: 0px;
                                flex-basis: 0px;
                      }
.flex-nowrap.flex-nowrap
                      {
                            -ms-flex-wrap: nowrap;
                        -webkit-flex-wrap: nowrap;
                                flex-wrap: nowrap;
                      }

.row-wrap.force_fullwidth:not(.tb_width_fixed) {
  width: 100vw;
  margin-left: calc(50% - 50vw);
}
.row-wrap.force_fullwidth.tb_content_fixed > .row {
  margin-right: auto;
  margin-left:  auto;
}
.text-hide {
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}
.hidden {
  display: none !important;
  visibility: hidden !important;
}

/*  -----------------------------------------------------------------------------------------
    Affix plugin
-----------------------------------------------------------------------------------------  */

.affix    { position: fixed; }

/*  -----------------------------------------------------------------------------------------
    Clearfix
-----------------------------------------------------------------------------------------  */

.clearfix:before,
.clearfix:after,
.tb_wt:before,
.tb_wt:after
{
  content: "";
  display: table;
}
.clearfix:after,
.tb_wt:after
{
  clear: both;
}
