.table,
.table table,
.table-responsive
{
  border-collapse: separate;
}
.table table,
.table:last-child
{
  margin-bottom: 0;
}
.table > thead > tr > th,
.table > thead > tr > td,
.table > tbody > tr > th,
.table > tbody > tr > td,
.table > tfoot > tr > th,
.table > tfoot > tr > td,
.table table > thead > tr > th,
.table table > thead > tr > td,
.table table > tbody > tr > th,
.table table > tbody > tr > td,
.table table > tfoot > tr > th,
.table table > tfoot > tr > td
{
  padding: <?php echo $base * 0.5; ?>px <?php echo $base; ?>px <?php echo $base * 0.5 - 1; ?>px <?php echo $base; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  text-align: left;
  <?php else: ?>
  text-align: right;
  <?php endif; ?>
  border-bottom-width: 1px;
  border-bottom-style: solid;
}
.table > thead > tr > th,
.table > thead > tr > td,
.table table > thead > tr > th,
.table table > thead > tr > td
{
  font-weight: bold;
}
.table > thead > tr > th:not(:first-child),
.table > thead > tr > td:not(:first-child),
.table table > thead > tr > th:not(:first-child),
.table table > thead > tr > td:not(:first-child)
{
  <?php if ($lang_dir == 'ltr'): ?>
  border-left-width: 1px;
  border-left-style: solid;
  <?php else: ?>
  border-right-width: 1px;
  border-right-style: solid;
  <?php endif; ?>
}
.table > tbody:last-child > tr:last-child > th,
.table > tbody:last-child > tr:last-child > td,
.table > table > tbody:last-child > tr:last-child > th,
.table > table > tbody:last-child > tr:last-child > td,
.table > tfoot > tr > th,
.table > tfoot > tr > td,
.table > table > tfoot > tr > th,
.table > table > tfoot > tr > td
{
  padding-bottom: <?php echo $base * 0.5; ?>px;
  border-width: 0;
  border-style: none;
}
.table > thead,
.table > thead > tr,
.table > tbody,
.table > tbody > tr,
.table > tfoot,
.table > tfoot > tr
.table > table > thead,
.table > table > thead > tr,
.table > table > tbody,
.table > table > tbody > tr,
.table > table > tfoot,
.table > table > tfoot > tr
{
  border-radius: inherit;
}
.table > thead > tr:first-child,
.table > thead > tr:first-child > th:first-child,
.table > table > thead > tr:first-child,
.table > table > thead > tr:first-child > th:first-child
{
  border-top-left-radius: inherit;
}
.table > thead > tr:first-child,
.table > thead > tr:first-child > th:last-child,
.table > table > thead > tr:first-child,
.table > table > thead > tr:first-child > th:last-child
{
  border-top-right-radius: inherit;
}
.table > tbody:last-child > tr:last-child,
.table > tbody:last-child > tr:last-child > th:first-child,
.table > table > tbody:last-child > tr:last-child,
.table > table > tbody:last-child > tr:last-child > th:first-child
{
  border-bottom-left-radius: inherit;
}
.table > tbody:last-child > tr:last-child,
.table > tbody:last-child > tr:last-child > th:last-child,
.table > table > tbody:last-child > tr:last-child,
.table > table > tbody:last-child > tr:last-child > th:last-child
{
  border-bottom-right-radius: inherit;
}

/*  -----------------------------------------------------------------------------------------
    C O N D E N S E D   T A B L E
-----------------------------------------------------------------------------------------  */

.table-condensed > thead > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > th,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > th,
.table-condensed > tfoot > tr > td,
.table-condensed > table > thead > tr > th,
.table-condensed > table > thead > tr > td,
.table-condensed > table > tbody > tr > th,
.table-condensed > table > tbody > tr > td,
.table-condensed > table > tfoot > tr > th,
.table-condensed > table > tfoot > tr > td
{
  padding: <?php echo $base * 0.25; ?>px <?php echo $base * 0.5; ?>px <?php echo $base * 0.25 - 1; ?>px <?php echo $base * 0.5; ?>px;
}
.table-condensed > tbody:last-child > tr:last-child > th,
.table-condensed > tbody:last-child > tr:last-child > td,
.table-condensed > table > tbody:last-child > tr:last-child > th,
.table-condensed > table > tbody:last-child > tr:last-child > td,
.table-condensed > tfoot > tr > th,
.table-condensed > tfoot > tr > td,
.table-condensed > table > tfoot > tr > th,
.table-condensed > table > tfoot > tr > td
{
  padding-bottom: <?php echo $base * 0.25; ?>px;
}

/*  -----------------------------------------------------------------------------------------
    M I N I M A L   T A B L E
-----------------------------------------------------------------------------------------  */

.table-minimal > thead > tr > th,
.table-minimal > thead > tr > td,
.table-minimal > table > thead > tr > th,
.table-minimal > table > thead > tr > td
{
  background-color: transparent !important;
  border-right: 0px none !important;
  border-left:  0px none !important;
  border-bottom-width: 2px;
}
.table-minimal > thead > tr:first-child > *,
.table-minimal > table > thead > tr:first-child > *,
.table-minimal > tbody:first-child > tr:first-child > *,
.table-minimal > table > tbody:first-child > tr:first-child > *
{
  padding-top: 0;
}
.table-minimal > tfoot > tr:last-child > *,
.table-minimal > table > tfoot > tr:last-child > *,
.table-minimal > tbody:last-child > tr:last-child > *,
.table-minimal > table > tbody:last-child > tr:last-child > *
{
  padding-bottom: 0 !important;
}
.table-minimal > thead > tr > th:first-child,
.table-minimal > thead > tr > td:first-child,
.table-minimal > tbody > tr > th:first-child,
.table-minimal > tbody > tr > td:first-child,
.table-minimal > tfoot > tr > th:first-child,
.table-minimal > tfoot > tr > td:first-child,
.table-minimal > table > thead > tr > th:first-child,
.table-minimal > table > thead > tr > td:first-child,
.table-minimal > table > tbody > tr > th:first-child,
.table-minimal > table > tbody > tr > td:first-child,
.table-minimal > table > tfoot > tr > th:first-child,
.table-minimal > table > tfoot > tr > td:first-child
{
  padding-left: 0 !important;
}
.table-minimal > thead > tr > th:last-child,
.table-minimal > thead > tr > td:last-child,
.table-minimal > tbody > tr > th:last-child,
.table-minimal > tbody > tr > td:last-child,
.table-minimal > tfoot > tr > th:last-child,
.table-minimal > tfoot > tr > td:last-child,
.table-minimal > table > thead > tr > th:last-child,
.table-minimal > table > thead > tr > td:last-child,
.table-minimal > table > tbody > tr > th:last-child,
.table-minimal > table > tbody > tr > td:last-child,
.table-minimal > table > tfoot > tr > th:last-child,
.table-minimal > table > tfoot > tr > td:last-child
{
  padding-right: 0 !important;
}

/*  -----------------------------------------------------------------------------------------
    B O R D E R E D   T A B L E
-----------------------------------------------------------------------------------------  */

.table-bordered {
  border-width: 1px;
  border-style: solid;
}
.table-bordered > thead > tr:first-child > th,
.table-bordered > thead > tr:first-child > td,
.table-bordered > table > thead > tr:first-child > th,
.table-bordered > table > thead > tr:first-child > td
{
  border-top: 1px solid rgba(255, 255, 255, 0.5) !important;
  padding-top: <?php echo $base * 0.5 - 1; ?>px;
}
.table-bordered.table-condensed > thead > tr:first-child > th,
.table-bordered.table-condensed > thead > tr:first-child > td,
.table-bordered.table-condensed > table > thead > tr:first-child > th,
.table-bordered.table-condensed > table > thead > tr:first-child > td
{
  padding-top: <?php echo $base * 0.25 - 1; ?>px;
}

/*  -----------------------------------------------------------------------------------------
    S I Z I N G
-----------------------------------------------------------------------------------------  */

table col[class*="col-"] {
  position: static;
  float: none;
  display: table-column;
}
table td[class*="col-"],
table th[class*="col-"] {
  position: static;
  float: none;
  display: table-cell;
}

/*** Spacing ***/

@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .table thead th,
  .table > tbody td
  {
    padding-left: <?php echo $base / 2; ?>px;
    padding-right: <?php echo $base / 2; ?>px;
  }
}

/*  -----------------------------------------------------------------------------------------
    R E S P O N S I V E   T A B L E S
-----------------------------------------------------------------------------------------  */

.table-responsive {
  overflow-x: auto;
  overflow-y: hidden;
  min-height: 0.01%;
}
.table-responsive > .table {
  margin-bottom: 0;
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  .table-responsive {
    width: 100%;
    overflow-y: hidden;
    -ms-overflow-style: -ms-autohiding-scrollbar;
  }
  .table-responsive > .table > thead > tr > th,
  .table-responsive > .table > tbody > tr > th,
  .table-responsive > .table > tfoot > tr > th,
  .table-responsive > .table > thead > tr > td,
  .table-responsive > .table > tbody > tr > td,
  .table-responsive > .table > tfoot > tr > td {
    white-space: nowrap;
  }
  .table-responsive > .table-bordered {
    border: 0;
  }
  .table-responsive > .table-bordered > thead > tr > th:first-child,
  .table-responsive > .table-bordered > tbody > tr > th:first-child,
  .table-responsive > .table-bordered > tfoot > tr > th:first-child,
  .table-responsive > .table-bordered > thead > tr > td:first-child,
  .table-responsive > .table-bordered > tbody > tr > td:first-child,
  .table-responsive > .table-bordered > tfoot > tr > td:first-child {
    border-left: 0;
  }
  .table-responsive > .table-bordered > thead > tr > th:last-child,
  .table-responsive > .table-bordered > tbody > tr > th:last-child,
  .table-responsive > .table-bordered > tfoot > tr > th:last-child,
  .table-responsive > .table-bordered > thead > tr > td:last-child,
  .table-responsive > .table-bordered > tbody > tr > td:last-child,
  .table-responsive > .table-bordered > tfoot > tr > td:last-child {
    border-right: 0;
  }
  .table-responsive > .table-bordered > tbody > tr:last-child > th,
  .table-responsive > .table-bordered > tfoot > tr:last-child > th,
  .table-responsive > .table-bordered > tbody > tr:last-child > td,
  .table-responsive > .table-bordered > tfoot > tr:last-child > td {
    border-bottom: 0;
  }
}
