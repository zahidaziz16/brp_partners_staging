.thumbnail {
  position: relative;
  <?php if ($lang_dir == 'ltr'): ?>
  float: left;
  <?php else: ?>
  float: right;
  <?php endif; ?>
}
.thumbnail,
.thumbnail img,
.thumbnail a img
{
  display: block;
}
img.thumbnail,
.thumbnail img
{
  margin-left: auto;
  margin-right: auto;
}

.image-holder,
.image-holder span
{
  display: block;
  font-size: 0;
  margin-left:  auto;
  margin-right: auto;
}
.image-holder span {
  width: 100%;
  height: 0;
}
.image-holder img[src*="BurnEngine/image/pixel.gif"] {
  max-height: 100%;
}