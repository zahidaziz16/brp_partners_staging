.fade {
  opacity: 0;
  -webkit-transition: opacity 0.15s linear;
          transition: opacity 0.15s linear;
}
.fade.in {
  opacity: 1;
}
.collapse {
  display: none;
  visibility: hidden;
}
.collapse.in {
  display: block;
  visibility: visible;
}
tr.collapse.in {
  display: table-row;
}
tbody.collapse.in {
  display: table-row-group;
}
.collapsing {
  position: relative;
  height: 0;
  overflow: hidden;
  -webkit-transition-property: height, visibility;
          transition-property: height, visibility;
  -webkit-transition-duration: 0.35s;
          transition-duration: 0.35s;
  -webkit-transition-timing-function: ease;
          transition-timing-function: ease;
}

/*** Rotate clockwise ***/

@-webkit-keyframes tb_rotate {
  from {transform: rotate(0deg);}
  to   {transform: rotate(359deg);}
}
@keyframes tb_rotate {
  from {transform: rotate(0deg);}
  to   {transform: rotate(359deg);}
}
