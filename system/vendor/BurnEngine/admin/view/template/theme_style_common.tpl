<?php $style = $theme_settings['style']; ?>
<h2><span><?php echo $text_title_design_common; ?></span></h2>

<fieldset>
  <legend>Site width</legend>
  <div class="tb_wrap">
    <div class="tb_col tb_1_5 tb_live_1_1">
      <input class="s_spinner" type="text" name="style[maximum_width]" min="1000" size="7" step="10" value="<?php echo $style['maximum_width']; ?>" />
      <span class="s_metric">px</span>
    </div>
    <div class="tb_col tb_4_5">
      <p class="s_help right">Maximum site width in desktop view.</p>
    </div>
  </div>
</fieldset>

<fieldset>
  <legend>Responsive</legend>
  <div class="s_actions">
    <input type="hidden" name="style[responsive]" value="0" />
    <label class="tb_toggle tb_toggle_small"><input id="style_common_responsive" type="checkbox" name="style[responsive]" value="1"<?php if($style['responsive'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
  </div>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Mobile menu padding</label>
      <input type="text" name="style[mobile_menu_padding]" value="<?php echo $style['mobile_menu_padding']; ?>" />
    </div>
  </div>
</fieldset>

<fieldset>
  <legend>Sticky header</legend>
  <div class="s_actions">
    <input type="hidden" name="style[sticky_header]" value="0" />
    <label class="tb_toggle tb_toggle_small"><input id="style_common_sticky_header" type="checkbox" name="style[sticky_header]" value="1"<?php if($style['sticky_header'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
  </div>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Style</label>
      <div class="s_select">
        <select name="style[sticky_header_style]">
          <option value="default"<?php if($style['sticky_header_style'] == 'default') echo ' selected="selected"';?>>Default</option>
          <option value="minimal"<?php if($style['sticky_header_style'] == 'minimal') echo ' selected="selected"';?>>Minimal</option>
        </select>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Layout</label>
      <div class="s_select">
        <select name="style[sticky_header_layout]">
          <option value="full"<?php       if($style['sticky_header_layout'] == 'full')       echo ' selected="selected"';?>>Full</option>
          <option value="full_fixed"<?php if($style['sticky_header_layout'] == 'full_fixed') echo ' selected="selected"';?>>Full (fixed width content)</option>
          <option value="fixed"<?php      if($style['sticky_header_layout'] == 'fixed')      echo ' selected="selected"';?>>Fixed</option>
        </select>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Padding</label>
      <input type="text" name="style[sticky_header_padding]" value="<?php echo $style['sticky_header_padding']; ?>" />
    </div>
  </div>
</fieldset>

<fieldset id="style_common_system_messages">
  <legend>System messages</legend>
  <div class="tb_wrap tb_gut_30">
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label>Position</label>
      <div class="s_select">
        <select name="style[msg_position]">
          <option value="topLeft"<?php if($style['msg_position'] == 'topLeft') echo ' selected="selected"';?>>Top Left</option>
          <option value="topCenter"<?php if($style['msg_position'] == 'topCenter') echo ' selected="selected"';?>>Top Center</option>
          <option value="topRight"<?php if($style['msg_position'] == 'topRight') echo ' selected="selected"';?>>Top Right</option>
          <option value="bottomRight"<?php if($style['msg_position'] == 'bottomRight') echo ' selected="selected"';?>>Bottom Right</option>
          <option value="bottomCenter"<?php if($style['msg_position'] == 'bottomCenter') echo ' selected="selected"';?>>Bottom Center</option>
          <option value="bottomLeft"<?php if($style['msg_position'] == 'bottomLeft') echo ' selected="selected"';?>>Bottom Left</option>
        </select>
      </div>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label for="style_common_stack_messages">Stack messages</label>
      <span class="clear"></span>
      <input type="hidden" name="style[msg_stack]" value="0" />
      <label class="tb_toggle"><input id="style_common_stack_messages" type="checkbox" name="style[msg_stack]" value="1"<?php if($style['msg_stack'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>
    <div class="s_row_2 tb_col tb_1_5 tb_live_row_1 tb_live_1_1">
      <label >Timeout</label>
      <input class="s_spinner" type="text" name="style[msg_timeout]" min="1000" size="7" step="500" value="<?php echo $style['msg_timeout']; ?>" />
      <span class="s_metric">ms</span>
    </div>
  </div>
</fieldset>
