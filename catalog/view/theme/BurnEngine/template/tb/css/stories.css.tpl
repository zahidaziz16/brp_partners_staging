.tb_page_stories_show .breadcrumb li:first-child a:before {
  content: none;
}
.tb_article .thumbnail {
  max-width: 100% !important;
  margin-bottom: <?php echo $base; ?>px;
}
.tb_thumbnail_top .thumbnail {
  float: none;
  margin-left: 0 !important;
  margin-right: 0 !important;
}
.tb_thumbnail_left .thumbnail {
  float: left;
  margin-right: 20px;
  margin-bottom: 0;
}
.tb_thumbnail_right .thumbnail {
  float: right;
  margin-left: 20px;
  margin-bottom: 0;
}
.tb_article .tb_meta {
  margin-left: -<?php echo $base * 0.75; ?>px;
  margin-right: -<?php echo $base * 0.75; ?>px;
  margin-bottom: <?php echo $base * 0.5; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  padding-right: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
}
.tb_article .tb_meta > * {
  position: relative;
  display: inline-block;
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
  margin-bottom: <?php echo $base * 0.5; ?>px;
  vertical-align: top;
}
.tb_article .tb_meta > * + * {
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  padding-right: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
}
.tb_article .tb_meta > * + *:before {
  content: '';
  position: absolute;
  top: 2px;
  bottom: 2px;
  left: 0;
  display: inline-block;
  <?php if ($lang_dir == 'ltr'): ?>
  border-left: 1px solid;
  <?php else: ?>
  border-right: 1px solid;
  <?php endif; ?>
  opacity: 0.2;
}
.tb_article .tb_meta .fa {
  margin-right: 0.2em;
  vertical-align: top;
  font-size: 14px;
}
.tb_article .tb_meta + .tb_text_wrap {
  position: relative;
  margin-top: <?php echo $base; ?>px;
  padding-top: <?php echo $base * 1.5; ?>px;
}
.tb_article .tb_meta + .tb_text_wrap:after {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  display: block;
  width: 100%;
  height: 0;
  border-bottom: 1px solid;
  opacity: 0.15;
}
.tb_article .tb_item_info > .tb_meta:last-child {
  margin-bottom: -10px !important;
}
.tb_article .tb_read_more {
  display: block;
  margin-top: <?php echo $base * 0.5; ?>px;
  font-weight: 600;
}
.tb_article .tb_read_more span {
  vertical-align: top;
  font-size: 1.2em;
}
.tb_article .tb_comments {
  position: relative;
  padding-top: <?php echo $base * 1.5; ?>px;
}
.tb_articles + .pagination {
  margin-bottom: 0;
  padding-top: <?php echo $base * 1.5; ?>px;
}

/*  Listing  ----------------------------------------------------------------------------- */

.tb_listing:not(.tb_compact_view) .tb_article .thumbnail:before {
  content: '\f0c1';
  position: absolute;
  z-index: 2;
  top: 50%;
  left: 50%;
  display: block;
  width: 60px;
  height: 60px;
  margin: -30px 0 0 -30px;
  line-height: 60px;
  text-align: center;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);
  font-family: FontAwesome;
  font-size: 28px;
  color: #fff;
  opacity: 0;
  border-radius: 50%;
  background-color: rgba(0, 0, 0, 0.6);
  -webkit-transition: all 0.3s;
  transition: all 0.3s;
}
.tb_listing:not(.tb_compact_view) .tb_article .thumbnail:hover:before {
  opacity: 1;
}

/*** Grid view ***/

.tb_grid_view .tb_article {
  text-align: initial;
}
.tb_grid_view .tb_article > * {
  width: auto !important;
  min-width: 100%;
}

/*** List view ***/

.tb_list_view .tb_article + .tb_article {
  margin-top: <?php echo $base * 1.5; ?>px;
  padding-top: <?php echo $base * 1.5; ?>px;
}
.tb_list_view .tb_article:last-child {
  margin-bottom: <?php echo $base * 1.5; ?>px;
}
.tb_list_view .tb_article .tb_meta + .tb_description {
  margin-top: 0;
  padding-top: 0;
}
.tb_list_view .tb_article .tb_meta + .tb_description:after {
  content: none;
}
.tb_list_view.tb_thumbnail_left  .tb_description,
.tb_list_view.tb_thumbnail_right .tb_description
{
  clear: none;
}

/*** Compact view ***/

.tb_compact_view .tb_article h3 + .tb_description,
.tb_compact_view .tb_article h3 + .tb_meta
{
  margin-top: <?php echo $base * 0.5; ?>px;
}
.tb_compact_view .tb_article .tb_description {
  margin-bottom: <?php echo $base * 0.5; ?>px;
}
.tb_compact_view .tb_article .tb_meta {
  margin-left: -<?php echo $base * 0.5; ?>px;
  margin-right: -<?php echo $base * 0.5; ?>px;
  <?php if ($lang_dir == 'ltr'): ?>
  padding-left: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  padding-right: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_compact_view .tb_article .tb_meta > * {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.5; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.5; ?>px;
  <?php endif; ?>
}
.tb_compact_view .tb_article .tb_meta > * + * {
  padding-left: 0;
  padding-right: 0;
}
.tb_compact_view .tb_article .tb_meta > * + *:before {
  content: none;
}

.tb_article .tb_meta .tb_social_share > :not(:last-child) {
  <?php if ($lang_dir == 'ltr'): ?>
  margin-right: <?php echo $base * 0.75; ?>px;
  <?php else: ?>
  margin-left: <?php echo $base * 0.75; ?>px;
  <?php endif; ?>
}