<li id="tb_menu_burnengine">
    <a class="parent">
        <img src="../system/vendor/BurnEngine/admin/view/image/i_oc_menu.png" style="margin: 3px 0 0 0; vertical-align: top;" />
        <span>BurnEngine</span>
    </a>
    <ul>
        <?php foreach ($menus as $menu): ?>
        <?php if (!is_numeric($menu['url'])): ?>
        <li><a href="<?php echo $menu['url']; ?>"><?php echo $menu['name']; ?></a></li>
        <?php else: ?>
        <li><a href="#" onclick="javascript: $('#tb_cp_content_wrap').tabs('option', 'active', <?php echo $menu['url']; ?>); return false;"><?php echo $menu['name']; ?></a></li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</li>