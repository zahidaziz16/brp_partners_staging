<script type="text/template" id="style_section_background_gradient_template">
  <input type="hidden" name="{{input_property}}[background][rows][{{bg_row_key}}][background_type]" value="gradient" />
  <div class="s_row_1 tb_live_row_2">
    <label>Type</label>
    <div class="s_select">
      <select name="{{input_property}}[background][rows][{{bg_row_key}}][type]" class="tbGradientType">
        <option value="linear" selected="selected">Linear</option>
        <option value="radial">Radial</option>
      </select>
    </div>
  </div>
  <div class="tb_angle s_row_1 tb_live_row_2 tb_gradient_color_row tbLinearGroup">
    <label>Angle</label>
    <div class="tb_knob">
      <div class="tb_knob_dial">
        <div class="tb_knob_pointer"></div>
      </div>
      <input type="text" class="s_spinner" min="-179" max="180" name="{{input_property}}[background][rows][{{bg_row_key}}][angle]" value="0" />
      <span class="s_metric">&deg;</span>
    </div>
  </div>
  {{#bg_container}}
  <div class="s_row_1 tb_live_row_2">
    <label>Bg container</label>
    <div class="s_select">
      <select name="{{input_property}}[background][rows][{{bg_row_key}}][container]">
        <option value="row" selected="selected">Row</option>
        <option value="content">Content area</option>
      </select>
    </div>
  </div>
  {{/bg_container}}
  <div class="s_row_1 tb_live_row_2">
    <label><?php echo $text_position; ?></label>
    <div class="s_select">
      <select name="{{input_property}}[background][rows][{{bg_row_key}}][position]">
        <option value="top left"><?php echo $text_opt_position_1; ?></option>
        <option value="top center"><?php echo $text_opt_position_2; ?></option>
        <option value="top right"><?php echo $text_opt_position_3; ?></option>
        <option value="right"><?php echo $text_opt_position_4; ?></option>
        <option value="bottom right"><?php echo $text_opt_position_5; ?></option>
        <option value="bottom center"><?php echo $text_opt_position_6; ?></option>
        <option value="bottom left"><?php echo $text_opt_position_7; ?></option>
        <option value="left"><?php echo $text_opt_position_8; ?></option>
        <option value="center" selected="selected"><?php echo $text_opt_position_9; ?></option>
        <option value="custom">Custom</option>
      </select>
    </div>
  </div>
  <div class="s_row_1 tb_live_row_2" style="display: none;">
    <label>X</label>
    <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][position_x]" size="7" />
    <span class="s_metric">
      <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][position_x_metric]">
        <option value="px">px</option>
        <option value="%">%</option>
      </select>
    </span>
  </div>
  <div class="s_row_1 tb_live_row_2" style="display: none;">
    <label>Y</label>
    <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][position_y]" size="7" />
    <span class="s_metric">
      <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][position_y_metric]">
        <option value="px">px</option>
        <option value="%">%</option>
      </select>
    </span>
  </div>
  <div class="s_row_1 tb_live_row_2">
    <label><?php echo $text_repeat; ?></label>
    <div class="s_select">
      <select name="{{input_property}}[background][rows][{{bg_row_key}}][repeat]">
        <option value="no-repeat"><?php echo $text_opt_repeat_1; ?></option>
        <option value="repeat-x"><?php echo $text_opt_repeat_3; ?></option>
        <option value="repeat-y"><?php echo $text_opt_repeat_4; ?></option>
        <option value="repeat"><?php echo $text_opt_repeat_2; ?></option>
      </select>
    </div>
  </div>
  <div class="s_row_1 tb_live_row_2">
    <label>Size</label>
    <div class="s_select">
      <select name="{{input_property}}[background][rows][{{bg_row_key}}][size]">
        <option value="auto">Auto</option>
        <option value="custom">Custom</option>
      </select>
    </div>
  </div>
  <div class="s_row_1 tb_live_row_2" style="display: none;">
    <label>Width</label>
    <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][size_x]" value="1" size="7" min="0" />
    <span class="s_metric">
      <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][size_x_metric]">
        <option value="px">px</option>
        <option value="%">%</option>
      </select>
    </span>
  </div>
  <div class="s_row_1 tb_live_row_2" style="display: none;">
    <label>Height</label>
    <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][size_y]" value="1" size="7" min="0" />
    <span class="s_metric">
      <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][size_y_metric]">
        <option value="px">px</option>
        <option value="%">%</option>
      </select>
    </span>
  </div>
  <div class="s_row_1 tb_live_row_2">
    <label><?php echo $text_attachment; ?></label>
    <div class="s_select">
      <select name="{{input_property}}[background][rows][{{bg_row_key}}][attachment]">
        <option value="scroll"><?php echo $text_opt_attachment_1; ?></option>
        <option value="fixed"><?php echo $text_opt_attachment_2; ?></option>
      </select>
    </div>
  </div>
  <div class="s_sortable_holder tb_style_2"></div>
  <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbAddColorRow">Add Color</a>
  <a href="javascript:;" class="s_button s_h_30 s_white s_icon_10 s_delete_10 tbRemoveBackgroundRow">Remove Gradient</a>
</script>

<script type="text/template" id="style_section_background_gradient_color_template">
  <div class="s_sortable_row tb_gradient_color_row tbBgColorRow">
    <div class="s_actions">
      <a href="javascript:;" class="tbRemoveColorRow s_button s_white s_h_20 s_icon_10 s_delete_10">Remove</a>
    </div>
    <h3 class="s_drag_area"><span>Color <span class="row_order">{{row_order}}</span></span></h3>
    <div class="tb_color s_row_1 tb_live_row_1">
      <label>Color</label>
      <div class="colorSelector">
        <div style="background-color: {{color}}"></div>
      </div>
      <input type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][colors][{{row_key}}][color]" value="{{color}}" />
    </div>
    <div class="tb_opacity s_row_1 tb_live_row_1">
      <label>Opacity</label>
      <div class="tb_slider"><div></div></div>
      <input type="text" class="s_spinner" min="0" max="100" name="{{input_property}}[background][rows][{{bg_row_key}}][colors][{{row_key}}][opacity]" value="100" />
      <span class="s_metric">%</span>
    </div>
    <div class="s_row_1 tb_wrap tbBgColorRowOffset">
      <div class="tb_distance s_row_1 tb_live_row_2 tb_col tb_disabled">
        <label>Offset</label>
        <div class="tb_slider"><div></div></div>
        <input type="text" class="s_spinner" min="0" max="100" name="{{input_property}}[background][rows][{{bg_row_key}}][colors][{{row_key}}][offset]" value="100" />
        <span class="s_metric">%</span>
      </div>
      <div class="s_row_1 tb_live_row_2 tb_col">
        <input type="hidden" name="{{input_property}}[background][rows][{{bg_row_key}}][colors][{{row_key}}][offset_auto]" value="0" />
        <label class="s_checkbox">
          <input type="checkbox" name="{{input_property}}[background][rows][{{bg_row_key}}][colors][{{row_key}}][offset_auto]" value="1" checked="checked" />
          <span>Auto</span>
        </label>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="style_section_background_image_template">
  <div class="tb_image_row tbBgImageRow">
    <input type="hidden" name="{{input_property}}[background][rows][{{bg_row_key}}][background_type]" value="image" />
    <input type="hidden" name="{{input_property}}[background][rows][{{bg_row_key}}][image]" value="" id="bg_image_style_{{section_name}}_{{bg_row_key}}" />
    <div class="tb_wrap">
      <div class="tb_col">
      <span class="tb_thumb">
        <img src="<?php echo $no_image; ?>" id="bg_preview_style_{{section_name}}_{{bg_row_key}}" class="image" onclick="image_upload('bg_image_style_{{section_name}}_{{bg_row_key}}', 'bg_preview_style_{{section_name}}_{{bg_row_key}}');" />
      </span>
      </div>
      <div class="tb_col tb_auto">
        <div class="s_row_1">
          <label><strong>Filename</strong></label><em class="tb_filename tbFilename">None</em>
        </div>
        {{#bg_container}}
        <div class="s_row_1">
          <label>Bg container</label>
          <div class="s_select">
            <select name="{{input_property}}[background][rows][{{bg_row_key}}][container]">
              <option value="row">Row</option>
              <option value="content">Content area</option>
            </select>
          </div>
        </div>
        {{/bg_container}}
        <div class="s_row_1">
          <label><?php echo $text_position; ?></label>
          <div class="s_select">
            <select name="{{input_property}}[background][rows][{{bg_row_key}}][position]">
              <option value="top left"><?php echo $text_opt_position_1; ?></option>
              <option value="top"><?php echo $text_opt_position_2; ?></option>
              <option value="top right"><?php echo $text_opt_position_3; ?></option>
              <option value="right"><?php echo $text_opt_position_4; ?></option>
              <option value="bottom right"><?php echo $text_opt_position_5; ?></option>
              <option value="bottom"><?php echo $text_opt_position_6; ?></option>
              <option value="bottom left"><?php echo $text_opt_position_7; ?></option>
              <option value="left"><?php echo $text_opt_position_8; ?></option>
              <option value="center"><?php echo $text_opt_position_9; ?></option>
              <option value="custom">Custom</option>
            </select>
          </div>
        </div>
        <div class="s_row_1" style="display: none;">
          <label>X</label>
          <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][position_x]" size="6" />
          <span class="s_metric">
            <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][position_x_metric]">
              <option value="px">px</option>
              <option value="%">%</option>
            </select>
          </span>
        </div>
        <div class="s_row_1" style="display: none;">
          <label>Y</label>
          <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][position_y]" size="6" />
          <span class="s_metric">
            <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][position_y_metric]">
              <option value="px">px</option>
              <option value="%">%</option>
            </select>
          </span>
        </div>
        <div class="s_row_1">
          <label><?php echo $text_repeat; ?></label>
          <div class="s_select">
            <select name="{{input_property}}[background][rows][{{bg_row_key}}][repeat]">
              <option value="no-repeat"><?php echo $text_opt_repeat_1; ?></option>
              <option value="repeat-x"><?php echo $text_opt_repeat_3; ?></option>
              <option value="repeat-y"><?php echo $text_opt_repeat_4; ?></option>
              <option value="repeat"><?php echo $text_opt_repeat_2; ?></option>
            </select>
          </div>
        </div>
        <div class="s_row_1">
          <label>Size</label>
          <div class="s_select">
            <select name="{{input_property}}[background][rows][{{bg_row_key}}][size]">
              <option value="auto">Auto</option>
              <option value="contain">Contain</option>
              <option value="cover">Cover</option>
              <option value="custom">Custom</option>
            </select>
          </div>
        </div>
        <div class="s_row_1" style="display: none;">
          <label>Width</label>
          <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][size_x]" value="1" size="6" min="0" />
          <span class="s_metric">
            <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][size_x_metric]">
              <option value="px">px</option>
              <option value="%">%</option>
            </select>
          </span>
        </div>
        <div class="s_row_1" style="display: none;">
          <label>Height</label>
          <input class="s_spinner" type="text" name="{{input_property}}[background][rows][{{bg_row_key}}][size_y]" value="1" size="6" min="0" />
          <span class="s_metric">
            <select class="s_metric" name="{{input_property}}[background][rows][{{bg_row_key}}][size_y_metric]">
              <option value="px">px</option>
              <option value="%">%</option>
            </select>
          </span>
        </div>
        <div class="s_row_1">
          <label><?php echo $text_attachment; ?></label>
          <div class="s_select">
            <select name="{{input_property}}[background][rows][{{bg_row_key}}][attachment]">
              <option value="scroll"><?php echo $text_opt_attachment_1; ?></option>
              <option value="fixed"><?php echo $text_opt_attachment_2; ?></option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <span class="clear s_mb_20"></span>
    <a href="javascript:;" class="s_button s_h_30 s_white s_icon_10 s_delete_10 tbRemoveBackgroundRow">Remove Image</a>
  </div>
</script>

<script type="text/template" id="site_background_image_template">
  <div class="tb_image_row clearfix tbSiteBackgroundPreviewRow" item_id="{{item_id}}">
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][type]" value="{{type}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][image]" value="{{{image}}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][position]" value="{{position}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][position_x]" value="{{position_x}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][position_y]" value="{{position_y}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][position_x_metric]" value="{{position_x_metric}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][position_y_metric]" value="{{position_y_metric}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][repeat]" value="{{repeat}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][attachment]" value="{{attachment}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][size]" value="{{size}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][size_x]" value="{{size_x}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][size_y]" value="{{size_y}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][size_x_metric]" value="{{size_x_metric}}" />
    <input type="hidden" name="background[{{property_name}}][{{item_id}}][size_y_metric]" value="{{size_y_metric}}" />
  <span class="tb_thumb{{#no_background}} tb_no_background{{/no_background}}">
    <img src="{{{preview}}}" />
  </span>
    <h3>{{item_name}}</h3>
    <ul class="tb_info">
      <li class="s_icon_10 tb_name_10" title="Category">{{item_name_full}}</li>
      <li class="s_icon_10 tb_position_10" title="Position">{{position}}</li>
      <li class="s_icon_10 tb_repeat_10" title="Repeat">{{repeat}}</li>
      <li class="s_icon_10 tb_attachment_10" title="Attachment">{{attachment}}</li>
      <li class="s_icon_10 tb_bg_size_10" title="Size">{{size}}</li>
    </ul>
    <div class="s_buttons_group s_h_20">
      <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditSiteBackground"></a>
      <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveSiteBackground"></a>
    </div>
  </div>
</script>

<script type="text/template" id="style_section_shadow_row_template">
  <div class="s_sortable_row tb_box_shadow_row">
    <div class="s_actions">
      <a href="javascript:;" class="tbRemoveRow s_button s_white s_h_20 s_icon_10 s_delete_10">Remove</a>
    </div>
    <h3 class="s_drag_area"><span>Shadow <span class="row_order">{{row_order}}</span></span></h3>
    <input type="hidden" name="{{input_property}}[box_shadow][rows][{{row_num}}][size_x]"  value="0" />
    <input type="hidden" name="{{input_property}}[box_shadow][rows][{{row_num}}][size_y]" value="0" />
    <div class="tb_color s_row_1 tb_live_row_1">
      <label>Color</label>
      <div class="colorSelector"><div style="background-color: #000000"></div></div>
      <input type="text" name="{{input_property}}[box_shadow][rows][{{row_num}}][color]" value="#000000" />
    </div>
    <div class="tb_opacity s_row_1 tb_live_row_1">
      <label>Opacity</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="{{input_property}}[box_shadow][rows][{{row_num}}][opacity]" value="100" />
      <span class="s_metric">%</span>
    </div>
    <div class="tb_angle s_row_1 tb_live_row_1">
      <label>Position</label>
      <input type="hidden" name="{{input_property}}[box_shadow][rows][{{row_num}}][inner]" value="0" />
      <label class="s_checkbox">
        <input type="checkbox" min="-179" max="180" name="{{input_property}}[box_shadow][rows][{{row_num}}][inner]" value="1">
        <span>Inset</span>
      </label>
      <div class="tb_knob">
        <div class="tb_knob_dial">
          <div class="tb_knob_pointer"></div>
        </div>
        <input class="s_spinner" type="text" name="{{input_property}}[box_shadow][rows][{{row_num}}][angle]"  value="90" />
        <span class="s_metric">&deg;</span>
      </div>
    </div>
    <div class="tb_distance s_row_1 tb_live_row_1">
      <label>Distance</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="{{input_property}}[box_shadow][rows][{{row_num}}][distance]"  value="0" />
      <span class="s_metric">px</span>
    </div>
    <div class="tb_blur s_row_1 tb_live_row_1">
      <label>Blur</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="{{input_property}}[box_shadow][rows][{{row_num}}][blur]" value="0" />
      <span class="s_metric">px</span>
    </div>
    <div class="tb_spread s_row_1 tb_live_row_1">
      <label>Spread</label>
      <div class="tb_slider"><div></div></div>
      <input class="s_spinner" type="text" min="0" max="100" name="{{input_property}}[box_shadow][rows][{{row_num}}][spread]" value="0" />
      <span class="s_metric">px</span>
    </div>
  </div>
</script>

<?php // Menu Composer  ?>

<script type="text/template" id="store_menu_template">
  <li id="menu_item_{{id}}"{{#no_nesting}} class="mjs-nestedSortable-no-nesting"{{/no_nesting}}>
    <div class="s_widget tbData">
      <h3>{{{title}}}</h3>
      <div class="s_widget_actions">
        <div class="s_buttons_group">
          <a class="s_button s_white s_h_20 s_icon_10 s_edit_10 tbEditMenuItem" href="">&nbsp;</a>
          <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveMenuItem" href="javascript:;">&nbsp;</a>
        </div>
      </div>
    </div>
  </li>
</script>

<script type="text/template" id="common_modal_dialog_template">
  <div>
    <div class="sm_overlayBG"></div>
    <div class="sm_window" style="width: {{width}}px; margin-left: {{margin_left}}px;">
      <a class="sm_closeWindowButton" href="#">close</a>
      <div class="sm_window_wrap">
        <div class="sm_content"></div>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_url_template">
  <div id="menu_url_form" class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Link Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit Link Menu</h2>
          <div class="s_row_1">
            <label>Label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Url</label>
            <input type="text" name="url" />
            <span class="s_metric">
              Target:
              <select name="target">
                <option value="_self">_self</option>
                <option value="_blank">_blank</option>
              </select>
            </span>
          </div>
          <div class="s_row_1">
            <label>Url title</label>
            <input type="text" name="url_title" />
          </div>
          <div class="s_row_1">
            <label>Tabbed submenus</label>
            <label class="tb_toggle"><input type="checkbox" name="tabbed_submenus" value="1" /><span></span><span></span></label>
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_home_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Add/Edit Menu</span></h1>
    <form>
      <div class="tb_subpanel">
        <h2>Home page link</h2>
        <div class="s_row_1">
          <label>Label</label>
          <input type="text" name="label" />
        </div>
        <div class="s_row_1">
          <label>Additional class</label>
          <input type="text" name="class" />
        </div>
        <div class="s_row_1">
          <label>Display</label>
          <div class="s_select">
            <select name="display">
              <option value="label_icon">Label and icon</option>
              <option value="label">Label only</option>
              <option value="icon">Icon only</option>
            </select>
          </div>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_manufacturers_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Brands Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit Brands Menu</h2>
          <div class="s_row_1">
            <label>Label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
          <div class="s_row_1">
            <label>Megamenu</label>
            <label class="tb_toggle"><input type="checkbox" name="is_megamenu" value="1" /><span></span><span></span></label>
          </div>
          <div class="s_row_1 tbSimpleMenu">
            <div class="s_row_1">
              <label>Menu width</label>
              <input class="s_spinner" type="text" name="width" value="200" min="160" size="6" />
              <span class="s_metric">px</span>
            </div>
            <div class="s_row_1 tb_wrap">
              <div class="tb_col s_row_1">
                <label>Display</label>
                <div class="s_select">
                  <select name="manufacturers_display">
                    <option value="label">Label</option>
                    <option value="image">Image</option>
                  </select>
                </div>
              </div>
              <div class="tb_col s_row_1 tbImageSize">
                <input class="inline" type="text" name="image_size_x" value="100" size="3" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="image_size_y" value="100" size="3" /><span class="s_metric">px</span>
              </div>
            </div>
            <div class="s_row_1">
              <label>Manufacturers</label>
              <div class="s_select">
                <select name="manufacturers_type">
                  <option value="all">All</option>
                  <option value="custom">Custom</option>
                </select>
              </div>
            </div>
            <div class="s_row_1 tbManufacturersListing">
              <label>&nbsp;</label>
              <div class="s_full tb_wrap">
                {{#manufacturers}}
                <label class="s_checkbox tb_col tb_1_4"><input type="checkbox" name="manufacturers[]" value="{{id}}" /> <span>{{name}}</span></label>
                {{/manufacturers}}
              </div>
            </div>
          </div>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_html_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>HTML Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit HTML Menu</h2>
          <div class="s_row_1">
            <label>Label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Url</label>
            <input type="text" name="url" />
            <span class="s_metric">
              Target:
              <select name="target">
                <option value="_self">_self</option>
                <option value="_blank">_blank</option>
              </select>
            </span>
          </div>
          <div class="s_row_1">
            <label>Url title</label>
            <input type="text" name="url_title" />
          </div>
          <div class="s_row_1">
            <label>HTML Dropdown</label>
            <div class="s_full">
              <textarea name="html_text" class="ckeditor"></textarea>
            </div>
          </div>
          <div class="s_row_1">
            <label>Dropdown width</label>
            <input type="text" name="dropdown_width" value="160" />
            <span class="s_metric">
              <select name="dropdown_width_metric">
                <option value="px">px</option>
                <option value="%">%</option>
              </select>
            </span>
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_categories_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>All categories Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li class="tbMegamenuOption"><a href="#menu_settings_banner">Banner</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit All categories Menu</h2>
          <div class="s_row_1">
            <label>Custom label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
          <div class="s_row_1">
            <label>Megamenu</label>
            <label class="tb_toggle"><input type="checkbox" name="is_megamenu" value="1" /><span></span><span></span></label>
          </div>
          <fieldset class="s_mb_0">
            <legend>Categories</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Max depth</label>
                <input class="s_spinner" type="text" name="depth" value="2" min="1" size="6" />
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbMegamenuOption">
                <label>Category thumbnail</label>
                <label class="tb_toggle"><input type="checkbox" name="category_thumb" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbRow tbMegamenuOption">
                <label>Subcategory hover thumbnail</label>
                <label class="tb_toggle"><input type="checkbox" name="subcategory_hover_thumb" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbCategoryThumbSize tbMegamenuOption">
                <label>Thumb size</label>
                <input class="inline" type="text" name="cat_image_size_x" value="100" size="2" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="cat_image_size_y" value="100" size="2" /><span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbCategoryThumbSize tbMegamenuOption">
                <label>Thumb position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="category_thumb_position">
                      <option value="left">Right</option>
                      <option value="top">Top</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbMegamenuOption">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Layout Direction</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="subcategory_direction">
                      <option value="row">Row</option>
                      <option value="column">Column</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Minimum column width</label>
                <input class="s_spinner" type="text" name="subcategory_column_width" min="100" size="6" step="5" data-default="200" />
                <span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>
          <fieldset class="s_mb_0 tbMegamenuOption">
            <legend>Brands</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Show Brands</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="manufacturers_type">
                      <option value="none">Do not show</option>
                      <option value="all">All</option>
                      <option value="custom">Custom</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Show title</label>
                <label class="tb_toggle"><input type="checkbox" name="manufacturers_title" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Brands Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="manufacturers_position">
                      <option value="below">Below categories</option>
                      <option value="column">Next to categories</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Brands Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="manufacturers_display">
                      <option value="label">Label</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Thumb size</label>
                <input class="inline" type="text" name="image_size_x" value="100" size="2" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="image_size_y" value="100" size="2" /><span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>
          <fieldset class="s_pt_30 tbManufacturersListing">
            <legend>Filter brands</legend>
            <div class="s_row_2">
              <div class="s_full tb_wrap">
                {{#manufacturers}}
                <label class="s_checkbox tb_col tb_1_5"><input type="checkbox" name="manufacturers[]" value="{{id}}" /> <span>{{name}}</span></label>
                {{/manufacturers}}
              </div>
            </div>
          </fieldset>
        </div>
        <div id="menu_settings_banner" class="tb_subpanel">
          <h2>Menu Banner</h2>
          <fieldset class="tbMegamenuOption">
            <legend>Banner image</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="tb_col tb_1_5 s_row_2">
                <label>Select image</label>
                <input type="hidden" name="menu_banner" value="" id="menu_banner_input" />
                <span class="tb_thumb">
                  <img src="<?php echo $no_image; ?>" id="menu_banner_preview" class="image" onclick="image_upload('menu_banner_input', 'menu_banner_preview');" style="height: 72px;" />
                </span>
                <a class="s_button s_white s_h_30 s_mt_10 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_banner_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_banner_input').val('');">Remove</a>
              </div>
              <div class="tb_col tb_4_5">
                <div class="tb_wrap">
                  <div class="s_row_2 tb_col tb_5_6">
                    <label>Link</label>
                    <div class="s_full clearfix">
                      <input type="text" name="menu_banner_url" />
                    </div>
                  </div>
                  <div class="s_row_2 tb_col tb_1_6">
                    <label>Target</label>
                    <div class="s_full clearfix">
                      <div class="s_select">
                        <select name="menu_banner_url_target">
                          <option value="_self">_self</option>
                          <option value="_blank">_blank</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="s_row_2">
                  <label>Inline styles</label>
                  <div class="s_full clearfix">
                    <input type="text" name="menu_banner_inline_styles" />
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Banner image styles</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Horizontal align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="menu_banner_align">
                      <option value="start">Start</option>
                      <option value="center">Center</option>
                      <option value="end">End</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Vertical align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="menu_banner_valign">
                      <option value="top">Top</option>
                      <option value="middle">Middle</option>
                      <option value="bottom">Bottom</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="menu_banner_position">
                      <option value="0">Before subcategories</option>
                      <option value="1">After subcategories</option>
                      <option value="2">Below subcategories</option>
                      <option value="3">Bottom of menu</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Banner column styles</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Width</label>
                <input class="s_spinner" type="text" name="menu_banner_size" min="1" max="100" size="6" data-default="20" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Padding</label>
                <input class="s_spinner" type="text" name="menu_banner_padding" min="0" max="100" size="6" data-default="20" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbColorOption">
                <label>Background color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_banner_bg" value="" />
              </div>
            </div>
          </fieldset>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_page_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Information Page Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit Page Menu</h2>
          <div class="s_row_1">
            <label>Custom label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_category_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Category Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li class="tbMegamenuOption"><a href="#menu_settings_info">Info</a></li>
            <li class="tbMegamenuOption"><a href="#menu_settings_banner">Banner</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit Category Menu</h2>
          <div class="s_row_1">
            <label>Custom label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
          <fieldset class="s_mb_0">
            <legend>Dropdown Menu</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Megamenu</label>
                <label class="tb_toggle"><input type="checkbox" name="is_megamenu" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbMegamenuOption">
                <label>Custom width</label>
                <input class="s_spinner" type="text" name="dropdown_width" min="10" size="7" />
                <span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>
          <fieldset class="s_mb_0">
            <legend>Subcategories</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Max depth</label>
                <input class="s_spinner" type="text" name="depth" value="2" min="1" size="6" />
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbMegamenuOption">
                <label>Subcategory thumbnail</label>
                <label class="tb_toggle"><input type="checkbox" name="category_thumb" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbRow tbMegamenuOption">
                <label>Subcategory hover thumbnail</label>
                <label class="tb_toggle"><input type="checkbox" name="subcategory_hover_thumb" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbCategoryThumbSize tbMegamenuOption">
                <label>Thumb size</label>
                <input class="inline" type="text" name="cat_image_size_x" value="100" size="2" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="cat_image_size_y" value="100" size="2" /><span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Layout Direction</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="subcategory_direction">
                      <option value="row">Row</option>
                      <option value="column">Column</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 tb_mt_15">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Minimum column width</label>
                <input class="s_spinner" type="text" name="subcategory_column_width" min="100" size="6" step="5" data-default="140" />
                <span class="s_metric">px</span>
              </div>
            </div>
          </fieldset>
          <fieldset class="s_mb_0 tbMegamenuOption">
            <legend>Brands</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Category brands</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="manufacturers_type">
                      <option value="none">Do not show</option>
                      <option value="all">All</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Show title</label>
                <label class="tb_toggle"><input type="checkbox" name="manufacturers_title" value="1" /><span></span><span></span></label>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Brands Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="manufacturers_position">
                      <option value="below">Below subcategories</option>
                      <option value="column">Next to subcategories</option>
                      <option value="bottom">Bottom</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Brands Style</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="manufacturers_display">
                      <option value="label">Label</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay">
                <label>Thumb size</label>
                <input class="inline" type="text" name="image_size_x" value="100" size="2" />
                <span class="s_input_separator">&nbsp;x&nbsp;</span>
                <input class="inline" type="text" name="image_size_y" value="100" size="2" /><span class="s_metric">px</span>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 tb_mt_15">
              <div class="s_row_2 tb_col tb_1_5 tbManufacturersDisplay tbManufacturersColumnSize">
                <label>Column size</label>
                <input class="s_spinner" type="text" name="manufacturers_column_size" min="1" max="100" size="6" data-default="20" />
                <span class="s_metric">%</span>
              </div>
            </div>
          </fieldset>
        </div>
        <div id="menu_settings_info" class="tb_subpanel">
          <h2>Category Information</h2>
          <fieldset class="tbMegamenuOption">
            <legend>Information column</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <div class="s_row_2">
                  <label>Category title</label>
                  <label class="tb_toggle"><input type="checkbox" name="show_title" value="1" /><span></span><span></span></label>
                </div>
                <div class="s_row_2">
                  <label>Category thumbnail</label>
                  <label class="tb_toggle"><input type="checkbox" name="show_main_thumbnail" value="1" /><span></span><span></span></label>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_3_5">
                <label>Description</label>
                <div class="s_full">
                  <textarea name="description" cols="60" rows="8" style="height: 110px;"></textarea>
                </div>
              </div>
              <div class="tb_col tb_1_5 s_row_2">
                <label>Custom background</label>
                <input type="hidden" name="category_custom_bg" value="" id="menu_category_custom_bg_input" />
                <span class="tb_thumb">
                  <img src="<?php echo $no_image; ?>" id="menu_category_custom_bg_preview" class="image" onclick="image_upload('menu_category_custom_bg_input', 'menu_category_custom_bg_preview');" style="height: 54px;" />
                </span>
                <a class="s_button s_white s_h_30 s_mt_10 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_category_custom_bg_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_category_custom_bg_input').val('');">Remove</a>
              </div>
            </div>
          </fieldset>
          <fieldset class="tbMegamenuOption">
            <legend>Information column styles</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5 tbColorOption">
                <label>Text color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="category_information_color" value="#333333" data-default="#333333" />
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Vertical align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="information_valign">
                      <option value="top">Top</option>
                      <option value="middle">Middle</option>
                      <option value="bottom">Bottom</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbCategoryInfoColumnSize">
                <label>Column size</label>
                <input class="s_spinner" type="text" name="info_column_size" min="1" max="100" size="6" data-default="20" />
                <span class="s_metric">%</span>
              </div>
            </div>
          </fieldset>
        </div>
        <div id="menu_settings_banner" class="tb_subpanel">
          <h2>Menu Banner</h2>
          <fieldset class="tbMegamenuOption">
            <legend>Banner image</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="tb_col tb_1_5 s_row_2">
                <label>Select image</label>
                <input type="hidden" name="menu_banner" value="" id="menu_banner_input" />
                <span class="tb_thumb">
                  <img src="<?php echo $no_image; ?>" id="menu_banner_preview" class="image" onclick="image_upload('menu_banner_input', 'menu_banner_preview');" style="height: 72px;" />
                </span>
                <a class="s_button s_white s_h_30 s_mt_10 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_banner_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_banner_input').val('');">Remove</a>
              </div>
              <div class="tb_col tb_4_5">
                <div class="tb_wrap">
                  <div class="s_row_2 tb_col tb_5_6">
                    <label>Link</label>
                    <div class="s_full clearfix">
                      <input type="text" name="menu_banner_url" />
                    </div>
                  </div>
                  <div class="s_row_2 tb_col tb_1_6">
                    <label>Target</label>
                    <div class="s_full clearfix">
                      <div class="s_select">
                        <select name="menu_banner_url_target">
                          <option value="_self">_self</option>
                          <option value="_blank">_blank</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="s_row_2">
                  <label>Inline styles</label>
                  <div class="s_full clearfix">
                    <input type="text" name="menu_banner_inline_styles" />
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Banner image styles</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Horizontal align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="menu_banner_align">
                      <option value="start">Start</option>
                      <option value="center">Center</option>
                      <option value="end">End</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Vertical align</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="menu_banner_valign">
                      <option value="top">Top</option>
                      <option value="middle">Middle</option>
                      <option value="bottom">Bottom</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Position</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select name="menu_banner_position">
                      <option value="0">Before subcategories</option>
                      <option value="1">After subcategories</option>
                      <option value="2">Below subcategories</option>
                      <option value="3">Bottom of menu</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Banner column styles</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_5">
                <label>Width</label>
                <input class="s_spinner" type="text" name="menu_banner_size" min="1" max="100" size="6" data-default="20" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Padding</label>
                <input class="s_spinner" type="text" name="menu_banner_padding" min="0" max="100" size="6" data-default="20" />
                <span class="s_metric">px</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5 tbColorOption">
                <label>Background color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_banner_bg" value="" />
              </div>
            </div>
          </fieldset>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_system_template">
  <div id="menu_system_form" class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>System Page Menu</span></h1>
    <form>
      <div class="tb_tabs tb_subpanel_tabs">
        <div class="tb_tabs_nav clearfix">
          <ul>
            <li><a href="#menu_settings_common">Edit</a></li>
            <li><a href="#menu_settings_advanced">Advanced</a></li>
          </ul>
        </div>
        <div id="menu_settings_common" class="tb_subpanel">
          <h2>Edit System Page Menu</h2>
          <div class="s_row_1">
            <label>Custom label</label>
            <input type="text" name="label" />
          </div>
          <div class="s_row_1">
            <label>Menu color</label>
            <div class="s_full clearfix">
              <div class="inline-block valign_top tbColorOption">
                <div class="colorSelector"><div></div></div>
                <input type="text" name="menu_color" />
              </div>
              <div class="inline-block valign_top s_ml_10">
                <label class="s_checkbox">
                  <input class="tbMenuColorInherit" type="checkbox" name="menu_color_inherit" value="1" data-default="checked" />
                  <span>Use default</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div id="menu_settings_advanced" class="tb_subpanel">
          <h2>Advanced</h2>
          {{#has_menu_icon}}
          <fieldset>
            <legend>Menu Icon</legend>
            <div class="tb_wrap tb_gut_30 s_mb_0 tbMenuIconWrap">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Type</label>
                <div class="s_full clearfix">
                  <div class="s_select">
                    <select class="tbMenuIconType" name="menu_icon_type">
                      <option value="none">None</option>
                      <option value="symbol">Symbol</option>
                      <option value="image">Image</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7 tbIconSpacing">
                <label>Icon spacing</label>
                <input class="s_spinner" type="text" min="0" step="0.1" name="menu_icon_spacing" data-default="0.3" size="6" />
                <span class="s_metric">em</span>
              </div>
              <div class="s_row_2 tb_col tbImageIconRow">
                <input type="hidden" name="menu_icon_image" value="" id="menu_icon_image_input" />
                <span class="tb_thumb" style="padding: 3px;">
                  <img src="<?php echo $no_image; ?>" id="menu_icon_image_preview" class="image" onclick="image_upload('menu_icon_image_input', 'menu_icon_image_preview');" style="height: 47px;" />
                </span>
                <a class="s_button s_white s_h_30 s_ml_15 s_mt_15 s_icon_10 s_delete_10" href="javascript:;" onclick="$('#menu_icon_image_preview').attr('src', '<?php echo $no_image; ?>');$('#menu_icon_image_input').val('');">Remove</a>
              </div>
            </div>
            <div class="tb_wrap tb_gut_30 s_mt_15 tbIconRow">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Icon</label>
                <div class="s_full clearfix">
                  <div class="tbIcon s_h_30{{^menu_icon}} s_icon_holder{{/menu_icon}}" style="width: 35px;">
                    {{#menu_icon}}
                    <span class="glyph_symbol {{menu_icon}}"></span>
                    {{/menu_icon}}
                  </div>
                  {{^menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_plus_10 tbChooseIcon" href="javascript:;">Choose</a>
                  {{/menu_icon}}
                  {{#menu_icon}}
                  <a class="s_button s_white s_h_30 s_icon_10 s_delete_10 tbChooseIcon tbRemoveIcon" href="javascript:;">Remove</a>
                  {{/menu_icon}}
                  <input type="hidden" name="menu_icon" value="{{menu_icon}}" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_7">
                <label>Size</label>
                <input class="s_spinner" type="text" min="10" step="5" name="menu_icon_size" data-default="100" size="6" />
                <span class="s_metric">%</span>
              </div>
              <div class="s_row_2 tb_col tb_1_5">
                <label>Color</label>
                <div class="tbColorOption">
                  <div class="colorSelector"><div></div></div>
                  <input type="text" name="menu_icon_color" />
                </div>
              </div>
              <div class="s_row_2 tb_col tb_1_3">
                <label>Color Inheritance</label>
                <div class="inline-block valign_top">
                  <label class="s_checkbox">
                    <input class="tbMenuIconColorInherit" type="checkbox" name="menu_icon_color_inherit" value="1" data-default="checked" />
                    <span>Inherit</span>
                  </label>
                </div>
                <div class="inline-block valign_top s_ml_10">
                  <label class="s_checkbox s_ml_15">
                    <input class="tbMenuIconColorAsHover" type="checkbox" name="menu_icon_color_as_hover" value="1" />
                    <span>Use for hover</span>
                  </label>
                </div>
              </div>
            </div>
          </fieldset>
          {{/has_menu_icon}}
          <fieldset>
            <legend>Accent label</legend>
            <div class="tb_wrap tb_gut_30">
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent label</label>
                <input type="text" name="accent_text" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent custom margin</label>
                <input type="text" name="accent_margin" value="" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent color</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_color" value="#ffffff" />
              </div>
              <div class="s_row_2 tb_col tb_1_4">
                <label>Accent background</label>
                <div class="colorSelector"><div></div></div>
                <input type="text" name="accent_bg" value="#b92616" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Additional class</legend>
            <div class="s_row_2">
              <input class="tb_1_1" type="text" name="class" />
            </div>
          </fieldset>
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_title_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Title Menu</span></h1>
    <form>
      <div class="tb_subpanel">
        <h2>Edit Title Menu</h2>
        <div class="s_row_1">
          <label>Label</label>
          <input type="text" name="label" />
        </div>
        <div class="s_row_1">
          <label>Additional class</label>
          <input type="text" name="class" />
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_menu_settings_separator_template">
  <div class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Separator Menu</span></h1>
    <form>
      <div class="tb_subpanel">
        <h2>Edit Separator Menu</h2>
        <div class="s_row_1">
          <label>Label</label>
          <input type="text" name="label" />
        </div>
        <div class="s_row_1">
          <label>Additional class</label>
          <input type="text" name="class" />
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="stories_tags_template">
  <div id="menu_system_form" class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>Add/Edit Tag</span></h1>
    <form>
      <div class="tb_subpanel">
        <h2>Page Menu</h2>
        <div class="s_row_1">
          <label>Custom label</label>
          <input type="text" name="label" />
        </div>
        <div class="s_row_1">
          <label>Accent label</label>
          <input type="text" name="accent_text" />
        </div>
        <div class="s_row_1">
          <label>Accent color</label>
          <div class="colorSelector"><div></div></div>
          <input type="text" name="accent_color" value="#ffffff" />
        </div>
        <div class="s_row_1">
          <label>Accent background</label>
          <div class="colorSelector"><div></div></div>
          <input type="text" name="accent_bg" value="#b92616" />
        </div>
        <div class="s_row_1">
          <label>Accent custom margin</label>
          <input type="text" name="accent_margin" value="" />
        </div>
        <div class="s_row_1">
          <label>Additional class</label>
          <input type="text" name="class" />
        </div>
      </div>
    </form>
    <div class="s_submit clearfix">
      <a class="s_button s_red s_h_40 tb_cp_form_submit tbUpdateSettings" href="javascript:;">Update Settings</a>
    </div>
  </div>
</script>

<script type="text/template" id="store_items_restriction_template">
  <tr class="s_open s_nosep tbItemsRestrictionRow">
    <td>
      <input class="s_spinner" type="text" name="{{input_property}}[restrictions][{{row_num}}][max_width]" value="100" min="100" step="10" size="7" />
      <span class="s_metric">px</span>
    </td>
    <td class="align_left">
      <input class="s_spinner" type="text" name="{{input_property}}[restrictions][{{row_num}}][items_per_row]" value="1" min="1" max="12" size="5" />
    </td>
    <td class="align_left">
      <input class="s_spinner" type="text" name="{{input_property}}[restrictions][{{row_num}}][items_spacing]" value="30" step="10" min="0" max="50" size="5" />
      <span class="s_metric">px</span>
    </td>
    <td class="align_right">
      <a class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveItemsRestrictionRow" href="javascript:;"></a>
    </td>
  </tr>
</script>

<script type="text/template" id="system_page_template">
  <div class="s_sortable_row s_nodrag tbSystemPage">
    <div class="s_actions">
      <div class="s_buttons_group">
        <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveRow"></a>
      </div>
    </div>
    <div class="s_sortable_contents">
      <div class="s_row_1">
        <label>Label</label>
        <input type="text" name="system[pages][{{hash}}][label]" />
      </div>
      <div class="s_row_1">
        <label>Route</label>
        <input type="text" name="system[pages][{{hash}}][route]" />
      </div>
      <div class="s_row_1">
        <label>System blocks</label>
        <div class="s_full clearfix">
          <input type="hidden" name="system[pages][{{hash}}][widgets][breadcrumbs]" value="0" />
          <label class="s_checkbox">
            <input type="checkbox" name="system[pages][{{hash}}][widgets][breadcrumbs]" value="1" checked="checked" />
            <span>Breadcrumbs</span>
          </label>
          <input type="hidden" name="system[pages][{{hash}}][widgets][page_title]" value="0" />
          <label class="s_checkbox">
            <input type="checkbox" name="system[pages][{{hash}}][widgets][page_title]" value="1" checked="checked" />
            <span>Page title</span>
          </label>
          <input type="hidden" name="system[pages][{{hash}}][widgets][page_content]" value="0" />
          <label class="s_checkbox">
            <input type="checkbox" name="system[pages][{{hash}}][widgets][page_content]" value="1" checked="checked" />
            <span>Page content</span>
          </label>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="widget_duplicate_submenu_template">
  <div class="s_submenu tbWidgetDuplicateOptions">
    <div class="s_row_2">
      <label><strong>Target:</strong></label>
      <div class="s_buttons_group">
        <a class="s_button s_white s_h_30 tbDuplicate" href="javascript:;">Row</a>
        <a class="s_button s_white s_h_30 tbFavourites" href="javascript:;">Favorites</a>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="preset_name_info_template">
  <div class="s_server_msg s_msg_blue tbPresetNameInfo">
    <p class="s_icon_16 s_info_16">The preset <strong class="tbPresetName">{{preset_name}}</strong> has been applied to the styles.</p>
  </div>
</script>

<script type="text/template" id="area_settings_template">
  <div id="builder_area_templates" class="s_widget_options_holder tb_cp">
    <h1 class="sm_title"><span>{{area_name}} settings</span></h1>
    <div class="tb_tabs tb_subpanel_tabs tbAreaSettingsTabs">
      <div class="tb_tabs_nav">
        <ul class="clearfix">
          <li><a href="#area_settings_export">Export</a></li>
          {{#has_templates}}
          <li><a href="#area_settings_import">Import</a></li>
          {{/has_templates}}
          <li><a href="#area_settings_serialize">Area</a></li>
          <!-- area_settings_tabs_nav -->
        </ul>
      </div>

      <div id="area_settings_export" class="tb_subpanel static">
        <h2>Export template</h2>
        <div class="s_row_1">
          <label>Name</label>
          <input type="text" name="export_name" val="{{name}}" />
        </div>
        <!-- area_export_template_options_end -->
        <div class="s_submit">
          <a class="s_button s_red s_h_40 tbSaveExport" href="javacript:;">Export</a>
        </div>
      </div>

      {{#has_templates}}
      <div id="area_settings_import" class="tb_subpanel static">
        <div class="tb_tabs tb_fly_tabs tbTemplatesTabs">
          <ul class="tb_tabs_nav clearfix">
            <li><a href="#area_settings_import_custom_templates_holder">Custom</a></li>
            <li><a href="#area_settings_import_detault_templates_holder">Default</a></li>
          </ul>

          <div id="area_settings_import_detault_templates_holder" class="tb_subpanel">
            <h2>Default Templates</h2>
            <div class="tb_templates">
              {{#default_templates}}
              <label for="template_{{id}}" class="tbTemplateRow tb_system">
                <input id="template_{{id}}" type="radio" name="area_template" value="{{id}}" data-is_theme="1" />
                {{#image}}<img src="{{{image}}}" />{{/image}}
                <span class="tb_name">
                {{name}}
                <!-- tbRemoveTemplate -->
              </span>
                <span class="tb_overlay"></span>
              </label>
              {{/default_templates}}
              {{^default_templates}}<p class="tb_no_results">There are no default templates available for the current area.</p>{{/default_templates}}
            </div>
          </div>

          <div id="area_settings_import_custom_templates_holder" class="tb_subpanel">
            <h2>Custom Templates</h2>
            <div class="tb_templates">
              {{#custom_templates}}
              <label for="template_{{id}}" class="tbTemplateRow">
                <input id="template_{{id}}" type="radio" name="area_template" value="{{id}}" data-is_theme="0" />
                {{#image}}<img src="{{{image}}}" />{{/image}}
                <span class="tb_name">
                {{name}}
                  <a href="javascript:;" class="s_button s_white s_h_20 s_icon_10 s_delete_10 tbRemoveTemplate"></a>
              </span>
              </label>
              {{/custom_templates}}
              {{^custom_templates}}<p class="tb_no_results">There are no custom templates available for the current area.</p>{{/custom_templates}}
            </div>
          </div>

        </div>

        <div class="s_submit">
          <a class="s_button s_red s_h_40 tbLoadTemplate" href="javacript:;">Load</a>
        </div>
      </div>
      {{/has_templates}}

      <div id="area_settings_serialize" class="tb_subpanel static">
        <div class="s_row_2">
          <div class="s_full clearfix">
            <textarea rows="10"></textarea>
          </div>
        </div>
        <div class="s_submit">
          <a class="s_button s_red s_h_40 tbAreaSerializeExport" href="javacript:;">Export</a>
          <a class="s_button s_red s_h_40 tbAreaLoadExport" href="javacript:;">Load</a>
        </div>
      </div>

      <!-- area_settings_tabs -->
    </div>
  </div>
</script>

<script type="text/template" id="seo_generate_keywords_template">
  <div class="s_widget_options_holder tb_cp">
    <div class="s_row_1">
      <label>Products</label>
      <label class="tb_toggle">
        <input type="checkbox" name="generate[product]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <div class="s_row_1">
      <label>Categories</label>
      <label class="tb_toggle">
        <input type="checkbox" name="generate[category]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <div class="s_row_1">
      <label>Manufacturers</label>
      <label class="tb_toggle">
        <input type="checkbox" name="generate[manufacturer]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <div class="s_row_1">
      <label>Information pages</label>
      <label class="tb_toggle">
        <input type="checkbox" name="generate[information]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>

    <div class="s_row_1">
      <label>Skip items with existing keywords</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[skip_existing]" value="0">
        <input type="checkbox" name="generate[skip_existing]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>

    <div class="s_row_1">
      <label>Transliterate non-latin characters</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[transliterate]" value="0">
        <input type="checkbox" name="generate[transliterate]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>

    <div class="s_submit">
      <a class="s_button s_red s_h_40 tbGenerate" href="javacript:;">Generate</a>
    </div>
  </div>
</script>

<script type="text/template" id="seo_generate_products_meta_template">
  <div class="s_widget_options_holder tb_cp">
    <div class="s_row_1">
      <label>Meta Description</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[description]" value="0">
        <input type="checkbox" name="generate[description]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <input type="text" name="generate[description_pattern]" value="{%name%} / {%model%} / {%manufacturer%} / {%category%}" />

    <div class="s_row_1">
      <label>Meta Keywords</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[keywords]" value="0">
        <input type="checkbox" name="generate[keywords]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <input type="text" name="generate[keywords_pattern]" value="{%description:words_all(20)%}" />

    Legend: {%name%} {%model%} {%sku%} {%upc%} {%manufacturer%} {%category%} {%description%} {%description:chars(80)%} {%description:words(20)%} {%description:words_all(20)%}

    <div class="s_row_1">
      <label>Skip items with existing keywords</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[skip_existing]" value="0">
        <input type="checkbox" name="generate[skip_existing]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>

    <div class="tbPreviewList"></div>

    <div class="s_submit">
      <a class="s_button s_red s_h_40 tbGenerate" href="javacript:;">Generate</a>
      <a class="s_button s_red s_h_40 tbPreview" href="javacript:;">Preview</a>
    </div>
  </div>
</script>

<script type="text/template" id="seo_generate_categories_meta_template">
  <div class="s_widget_options_holder tb_cp">
    <div class="s_row_1">
      <label>Meta Description</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[description]" value="0">
        <input type="checkbox" name="generate[description]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <input type="text" name="generate[description_pattern]" value="{%name%} / {%parent_name%} / {%description:words(5)%}" />

    <div class="s_row_1">
      <label>Meta Keywords</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[keywords]" value="0">
        <input type="checkbox" name="generate[keywords]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>
    <input type="text" name="generate[keywords_pattern]" value="{%description:words_all(10)%}" />

    Legend: {%name%} {%parent_name%} {%description%} {%description:chars(80)%} {%description:words(20)%} {%description:words_all(20)%}

    <div class="s_row_1">
      <label>Skip items with existing meta records</label>
      <label class="tb_toggle">
        <input type="hidden" name="generate[skip_existing]" value="0">
        <input type="checkbox" name="generate[skip_existing]" value="1" checked="checked">
        <span></span><span></span>
      </label>
    </div>

    <div class="tbPreviewList"></div>

    <div class="s_submit">
      <a class="s_button s_red s_h_40 tbGenerate" href="javacript:;">Generate</a>
      <a class="s_button s_red s_h_40 tbPreview" href="javacript:;">Preview</a>
    </div>
  </div>
</script>


<script type="text/template" id="colors_inherit_menu_template">
  <div class="s_submenu">
    <label>Inherit from:</label>
    <div class="tb_colors clearfix">
      {{#inherit_menu}}
      <div class="tb_color{{#selected}} tb_selected{{/selected}} tbChooseParent" style="background-color: {{color}}" data-inherit_key="{{inherit_key}}"></div>
      {{/inherit_menu}}
      <div class="tb_color_label tbCurrentRule">{{{current_label}}}</div>
    </div>
  </div>
</script>