<?php

class TB_ColorSchemer
{
    protected $widget_default_colors = array();
    protected $column_default_colors = array();
    protected $row_default_colors    = array();
    protected $area_default_colors   = array();
    protected $theme_default_colors  = array();
    protected $filtered              = array();
    protected $current_area = '';

    public static $color_inheritance_error_type = 'exception';

    /**
     * @return TB_ColorSchemer
     */
    public static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    public function setFilteredColors($colors, $context)
    {
        if (!isset($this->{$context . '_default_colors'})) {
            throw new Exception('Invalid color section');
        }

        $this->{$context . '_default_colors'} = $colors;
        array_push($this->filtered, $context);
    }

    public function filterThemeColors(array &$colors, array $default_colors)
    {
        $colors =  $this->filterColors($colors, $default_colors, 'theme');

        return $this;
    }

    public function filterAreaColors(array &$colors, array $default_colors, $area_name)
    {
        $this->current_area = $area_name;
        $colors =  $this->filterColors($colors, $default_colors, 'area');

        return $this;
    }

    public function filterRowColors(array &$colors, array $default_colors)
    {
        $colors = $this->filterColors($colors, $default_colors, 'row');

        return $this;
    }

    public function filterColumnColors(array &$colors, array $default_colors)
    {
        $colors = $this->filterColors($colors, $default_colors, 'column');

        return $this;
    }

    public function filterWidgetColors(array &$colors, array $default_colors, $widget_id)
    {
        $colors =  $this->filterColors($colors, $default_colors, 'widget', $widget_id);

        return $this;
    }

    protected function filterColors(array $colors, array $default_colors, $context, $context_id = '')
    {
        $context_msg = '';
        if ($context_id) {
            $context_msg .= '; Context id: ' . $context_id;
        }

        foreach ($default_colors as $group_key => $group_values) {
            foreach ($group_values as $section_key => $section_values) {

                if (0 === strpos($section_key, '_')) {
                    continue;
                }

                if (isset($colors[$group_key][$section_key])) {
                    unset($colors[$group_key][$section_key]['elements']);
                    unset($colors[$group_key][$section_key]['important']);
                    unset($colors[$group_key][$section_key]['property']);
                    unset($colors[$group_key][$section_key]['can_inherit']);

                    if ($colors[$group_key][$section_key]['inherit'] != 2) {
                        unset($colors[$group_key][$section_key]['inherit_key']);
                        unset($colors[$group_key][$section_key]['force_print']);
                    } else {
                        $colors[$group_key][$section_key]['force_print'] = 1;
                    }

                    if (!empty($section_values['can_inherit']) && $colors[$group_key][$section_key]['inherit'] == 2) {
                        $section_values['original_inherit_key'] = $section_values['inherit_key'];
                        $section_values['original_force_print'] = $section_values['force_print'];
                    }

                    $section_values = TB_FormHelper::initFlatVarsSimple($section_values, $colors[$group_key][$section_key]);

                    if ($section_values['inherit'] == 2 && ($section_values['original_inherit_key'] == $section_values['inherit_key'] || false === $this->resolveParentColor(!empty($this->theme_default_colors) ? $this->theme_default_colors : $colors, $section_values['inherit_key'], true))) {
                        $section_values['inherit_key'] = $section_values['original_inherit_key'];
                        unset($section_values['original_inherit_key']);
                        $section_values['inherit'] = 1;
                    }
                }

                $section_values['context'] = $context;
                $section_values['id'] = $context . '_' . $group_key . '_' . $section_key;

                if (!isset($section_values['can_inherit']) || !$section_values['can_inherit']) {
                    $default_colors[$group_key][$section_key] = $section_values;
                    continue;
                }

                if (empty($section_values['inherit_key'])) {
                    throw new Exception("Empty inherit key for {$group_key}[{$section_key}]" . $context_msg);
                }

                if (false === strpos($section_values['inherit_key'], '.')) {
                    throw new Exception("Wrong inherit key {$section_values['inherit_key']} for {$group_key}[{$section_key}]" . $context_msg);
                }

                $inherit_parent = $context;
                $inherit_key    = $section_values['inherit_key'];

                if (false !== strpos($inherit_key, ':')) {
                    list ($inherit_parent, $inherit_key) = explode(':', $section_values['inherit_key']);
                    if (!in_array($inherit_parent, $this->filtered)) {
                        var_dump($section_values);
                        throw new Exception("The '{$context}' color '{$inherit_key}' inherits from '{$inherit_parent}' which has not been processed yet." . $context_msg);
                    }
                }

                if (false === strpos($inherit_key, '.') || count(explode('.', $inherit_key)) > 2) {
                    throw new Exception('Invalid ' . $context . ' inherit key ' . $inherit_key . $context_msg);
                }

                $parent_default_colors = $inherit_parent != $context ? $this->{$inherit_parent . '_default_colors'} : $default_colors;

                list ($inherit_group, $inherit_section) = explode('.', $inherit_key);

                if (!isset($parent_default_colors[$inherit_group][$inherit_section])) {
                    $msg = 'Color inheritance issue';
                    if (!empty($this->current_area)) {
                        $msg .= "\nCurrent area: " . $this->current_area;
                    }
                    $msg .= "\nCurrent rule: $context -> {$group_key} -> {$section_key} -> {$inherit_key} \nMissing parent: {$inherit_parent}[$inherit_group][$inherit_section]";

                    throw new Exception($msg . $context_msg);
                }

                if (!empty($section_values['original_inherit_key']) && false !== strpos($section_values['original_inherit_key'], ':')) {
                    // Inherit menu
                    list ($original_inherit_parent, $original_inherit_key) = explode(':', $section_values['original_inherit_key']);
                    if ($original_inherit_parent != $context) {
                        if (!in_array($original_inherit_parent, $this->filtered)) {
                            var_dump($section_values);
                            throw new Exception("The '{$context}' color '{$original_inherit_key}' inherits from '{$original_inherit_parent}' which has not been processed yet. The color is from the inherit menu." . $context_msg);
                        }
                        $original_parent_default_colors = $this->{$original_inherit_parent . '_default_colors'};

                        list ($original_inherit_group, $original_inherit_section) = explode('.', $original_inherit_key);

                        $section_values['original_color'] = $original_parent_default_colors[$original_inherit_group][$original_inherit_section]['color'];
                    }
                }

                $section_values['parent_context'] = $inherit_parent;
                $section_values['parent_id']      = '';
                $section_values['parent_color'  ] = '';
                $inherit_parent_title             = '';

                if ($inherit_parent == $context) {
                    if (!isset($default_colors[$inherit_group][$inherit_section]['children'])) {
                        $default_colors[$inherit_group][$inherit_section]['children'] = array();
                    }
                    $default_colors[$inherit_group][$inherit_section]['children'][] = $section_values['id'];
                    $section_values['parent_id'] = $inherit_parent . '_' . $inherit_group . '_' . $inherit_section;
                    if ($section_values['inherit']) {
                        $section_values['color'] = $inherit_key;
                    }
                } else {
                    $section_values['parent_color'] = $parent_default_colors[$inherit_group][$inherit_section]['color'];
                    if ($section_values['inherit']) {
                        $section_values['color'] = $section_values['parent_color'];
                    }

                    switch ($inherit_parent) {
                        case 'theme':
                            $inherit_parent_title = 'Theme Colors';
                            if ($context == 'area') {
                                $section_values['parent_id'] = $inherit_parent . '_' . $inherit_group . '_' . $inherit_section;
                                unset($section_values['parent_color']);
                            }
                            break;
                        case 'area':
                            list(, $area_name) = explode('_', $this->current_area, 2);
                            $inherit_parent_title = ucfirst(str_replace('_', ' ', $area_name)) . ' Area -> Colors';
                            break;
                        case 'row':
                            $inherit_parent_title = 'Row Colors';
                            if ($context == 'column') {
                                $section_values['parent_id'] = $inherit_parent . '_' . $inherit_group . '_' . $inherit_section;
                                unset($section_values['parent_color']);
                            }
                            break;
                        case 'column':
                            $inherit_parent_title = 'Column Colors';
                            break;
                    }
                }

                $section_values['inherit_title'] = 'Unknown inherit';

                if (isset($parent_default_colors[$inherit_group]['_label'])) {
                    if (0 === stripos(strtolower($inherit_parent_title), 'content')) {
                        $inherit_parent_title = 'Content wrap';
                    }
                    $section_values['inherit_title'] = 'Inherits from ' .
                        ($inherit_parent_title != "" ? $inherit_parent_title . ' -> ' : "") .
                        ($parent_default_colors[$inherit_group]['_label'] ? $parent_default_colors[$inherit_group]['_label'] . ' -> ' : '' ) .
                        $parent_default_colors[$inherit_group][$inherit_section]['label'];
                }

                $default_colors[$group_key][$section_key] = $section_values;
                array_push($this->filtered, $inherit_parent);
            }
        }

        foreach ($default_colors as &$group_values) {
            foreach ($group_values as $section_key => &$section_values) {
                if (0 === strpos($section_key, '_')) {
                    continue;
                }
                if (false !== strpos($section_values['color'], '.')) {
                    $section_values['color'] = $this->resolveParentColor($default_colors, $section_values['color'], $context_msg);
                }
            }
        }

        $colors = $this->{$context . '_default_colors'} = $default_colors;

        return $colors;
    }

    public function resolveParentColor($colors, $inherit_key, $suppress_error = false, $context_msg = '')
    {
        if (false !== strpos($inherit_key, ':')) {
            $inherit_key = substr($inherit_key, strpos($inherit_key, ':') + 1);
        }

        list ($inherit_group, $inherit_section) = explode('.', $inherit_key);

        if (!isset($colors[$inherit_group][$inherit_section])) {
            if ($suppress_error || self::$color_inheritance_error_type === false) {
                return false;
            }

            switch (self::$color_inheritance_error_type) {
                case 'info':
                    fb('The parent defined by ' . $inherit_key . ' does not exist');
                    return false;
                case 'exception':
                    throw new Exception('The parent defined by ' . $inherit_key . ' does not exist' . $context_msg);
            }
        }

        $parent = $colors[$inherit_group][$inherit_section];

        if (false !== strpos($parent['color'], '.')) {
            if ($parent['color'] == $inherit_key) {
                throw new Exception('Infinite recursion detected when trying to resolve the color, defined by inherit key ' . $inherit_key . $context_msg);
            }

            return $this->resolveParentColor($colors, $parent['color']);
        }

        return $parent['color'];
    }
}