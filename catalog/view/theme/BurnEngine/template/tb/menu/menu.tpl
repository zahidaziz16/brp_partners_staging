<?php
$menu_class  = 'nav';
$menu_class .= $widget_settings['orientation'] == 'vertical' ? ' nav-stacked' : ' nav-horizontal';
$menu_class .= $widget_settings['responsive_stack'] ? ' nav-responsive' : '';
$menu_class .= $widget_settings['orientation'] == 'vertical' && $widget_settings['level_1_style'] == 'list' ? ' tb_list_1' : '';
$menu_class .= $widget_settings['orientation'] == 'horizontal' && $widget_settings['justified_navigation'] ? ' nav-justified' : '';
$menu_class .= $widget_settings['orientation'] == 'horizontal' && $widget_settings['justified_dropdown']   ? ' nav-justified-dropdown' : '';
$menu_class .= !$widget_settings['dropdown_indicator'] ? ' tb_nocaret' : '';
$menu_class .= $widget_settings['separator'] != 'none' ? ' tb_separate_menus' : '';
$menu_class  = trim($menu_class) ? ' class="' . trim($menu_class) . '"' : '';
$relative_to = $widget_settings['orientation'] == 'horizontal' ? $widget_settings['relative_to'] : $widget_settings['relative_to_vertical'] ;

$menu_attr   = ' data-relative_to="' . $relative_to . '"';
?>

<nav>
  <ul<?php echo $menu_class; ?><?php echo $menu_attr; ?>>
    <?php echo $menu_items; ?>
  </ul>
</nav>

