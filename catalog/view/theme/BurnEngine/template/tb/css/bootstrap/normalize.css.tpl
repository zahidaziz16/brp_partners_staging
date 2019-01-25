*, body, button, input, textarea, select, table, td, th {
  margin: 0;
  padding: 0;
  line-height: inherit;
  font-family: inherit;
  font-size: 100%;
}
html {
  -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
}
body {
  overflow-x: hidden;
}
@media (min-width: <?php echo ($screen_lg + 1) . 'px'; ?>) {
  html[dir="ltr"] {
    width: 100vw;
  }
}
@media (max-width: <?php echo $screen_sm . 'px'; ?>) {
  html {
    overflow-x: hidden;
  }
}
body:before {
  content: '';
  display: table !important;
}
img,
svg
{
  max-width: 100%;
  height: auto;
  -ms-interpolation-mode: bicubic;
}
svg {
  max-height: 100%;
  fill: currentColor;
}
body > svg {
  display: none;
}
a,
img,
a img,
iframe
{
  border: 0;
  text-decoration: none;
}
a:active          { background: transparent;    }
a:not([href])     { cursor: default;            }
a:before, a:after { outline: 0 none !important; }

ul {
  list-style: none;
}
table {
  width: 100%;
  max-width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
}
button,
input[type="button"],
input[type="reset"],
input[type="submit"]
{
  -webkit-appearance: button;
  cursor: pointer;
}
input[type="file"],
input[type="file"]:hover,
input[type="file"]:focus
{
  background-color: transparent;
}
button[disabled],
input[disabled]
{
  cursor: default;
}
button,
input[type=date],
input[type=time],
input[type=datetime],
input[type=search],
input[type=number],
input[type=submit],
input[type=reset],
input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration,
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button
{
  -webkit-appearance: none;
  -moz-appearance: none;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  height: auto;
}
button::-moz-focus-inner,
input::-moz-focus-inner
{
  border: 0;
  padding: 0;
}
::-webkit-input-placeholder {
  color: inherit !important;
  opacity: 0.5 !important;
}
::-moz-placeholder {
  color: inherit !important;
  opacity: 0.5 !important;
}
:-ms-input-placeholder {
  color: inherit !important;
  opacity: 0.5 !important;
}
input[type="checkbox"],
input[type="radio"] {
  padding: 0;
}
article,
aside,
details,
figcaption,
figure,
footer,
header,
main,
menu,
nav,
section,
summary
{
  display: block;
}
.disabled,
[disabled],
[readonly],
.disabled  *,
[disabled] *,
[readonly] *
{
  cursor: not-allowed;
}

/*** Jquery UI reset ***/

.tb_tabs .ui-icon,
.tb_accordion .ui-icon
{
  position: static;
  display: inline;
  width: auto;
  height: auto;
  text-indent: 0;
}

/*****************************************************/
/******   B O X    M O D E L   ***********************/
/*****************************************************/

* {
  box-sizing: border-box;
}

/*  Video embeds  ------------------------------------------------------------------------ */

iframe[src*="youtube"],
iframe[src*="vimeo"]
{
  max-width: 100%;
}
@media (max-width: <?php echo $screen_xs; ?>px) {
  iframe[src*="youtube"],
  iframe[src*="vimeo"] {
    height: auto;
  }
}