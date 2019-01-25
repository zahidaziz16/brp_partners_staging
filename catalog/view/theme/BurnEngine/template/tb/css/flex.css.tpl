.flex-it {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
}
.flex-direction {
  -webkit-box-orient: horizontal;   /* horizontal | vertical */
  -webkit-box-direction: normal;    /* normal | reverse */
  -moz-box-orient: horizontal;      /* horizontal | vertical */
  -moz-box-direction: normal;       /* normal | reverse */
  -ms-flex-direction: row;          /* row | column | row-reverse | column-reverse */
  -webkit-flex-direction: row;      /* row | column | row-reverse | column-reverse */
  flex-direction: row;              /* row | column | row-reverse | column-reverse */
}
.flex-wrap {
  -webkit-box-lines: multiple;      /* multiple | single */
  -moz-box-lines: multiple;         /* multiple | single */
  -ms-flex-wrap: nowrap;            /* wrap | nowrap */
  -webkit-flex-wrap: nowrap;        /* wrap | nowrap */
  flex-wrap: nowrap;                /* wrap | nowrap */
}
.flex-size {
  -webkit-box-flex: 0;
  -moz-box-flex: 0;
  -ms-flex: 0 1 auto;
  -webkit-flex: 0 1 auto;
  flex: 0 1 auto;
}
.flex-order {
  -webkit-box-ordinal-group: 1;     /* positive number */
  -moz-box-ordinal-group: 1;        /* positive number */
  -ms-flex-order: 0;
  -webkit-order: 0;
  order: 0;
}
.flex-justify-content {
  -webkit-box-pack: center;         /* center | end | justify | start */
  -moz-box-pack: center;            /* center | end | justify | start */
  -ms-flex-pack: center;            /* center | end | justify | start */
  -webkit-justify-content: center;  /* center | flex-end | flex-start | space-around | space-between */
  justify-content: center;          /* center | flex-end | flex-start | space-around | space-between */
}
.flex-align-content {
  -ms-flex-line-pack: center;       /* center | distribute | end | justify | start | stretch */
  -webkit-align-content: center;    /* center | flex-end | flex-start | space-around | space-between | stretch */
  align-content: center;            /* center | flex-end | flex-start | space-around | space-between | stretch */
}
.flex-align-items {
  -webkit-box-align: stretch;       /* baseline | center | end | start | stretch */
  -moz-box-align: stretch;          /* baseline | center | end | start | stretch */
  -ms-flex-align: stretch;          /* baseline | center | end | start | stretch */
  -webkit-align-items: stretch;     /* baseline | center | flex-end | flex-start | stretch */
  align-items: stretch;             /* baseline | center | flex-end | flex-start | stretch */
}
.flex-align-self {
  -ms-flex-item-align: auto;        /* auto | baseline | center | end | start | stretch */
  -webkit-align-self: auto;         /* auto | baseline | center | flex-end | flex-start | stretch */
  align-self: auto;                 /* auto | baseline | center | flex-end | flex-start | stretch */
}