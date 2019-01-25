<?php

class TB_OutputHandler {
    /**
     * @var TB_Engine
     */
    protected $engine;

    /**
     * @var TB_ViewDataBag
     */
    protected $themeData;

    /**
     * @var TB_StyleBuilder
     */
    protected $styleBuilder;

    /**
     * @var sfEventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var Theme_Catalog_Extension
     */
    protected $themeExtension;

    /**
     * @var TB_Context
     */
    protected $context;

    protected $forceHTTPS = false;
    protected $page_content = '';
    protected $filter_header = true;
    protected $filter_footer = true;
    protected $js_contents = '';
    protected $js_contents_hashes = array();
    protected $styles_cache_key = '';
    protected $main_css_cache_key;
    protected $file_hash;
    protected $document_styles_before_render = array();
    protected $document_scripts_before_render = array();

    public function __construct(TB_Engine $engine, Theme_Catalog_Extension $themeExtension, TB_StyleBuilder $styleBuilder)
    {
        $this->engine          = $engine;
        $this->themeExtension  = $themeExtension;
        $this->styleBuilder    = $styleBuilder;
        $this->themeData       = $engine->getThemeData();
        $this->eventDispatcher = $engine->getEventDispatcher();
        $this->context         = $engine->getContext();
    }

    public function getStylesCacheKey()
    {
        return $this->styles_cache_key;
    }

    public function getStylesFileHash()
    {
        return $this->file_hash;
    }

    public function setHeaderFilter($do_filter)
    {
        $this->themeData['filter_header'] = $this->filter_header = (bool) $do_filter;
    }

    public function setFooterFilter($do_filter)
    {
        $this->themeData['filter_footer'] = $this->filter_footer = (bool) $do_filter;
    }

    public function echoHeader($header)
    {
        if (!$this->filter_header) {
            return;
        }

        $this->themeData->slotStart('oc_header');
        echo $header;
        $this->themeData->slotStop();

        ob_start();
        ob_implicit_flush(0);
        $this->themeData->header_caught = true;
    }

    public function echoFooter($footer)
    {
        if (!$this->filter_footer) {
            return;
        }

        $this->themeData->slotStart('oc_footer');
        echo $footer;
        $this->themeData->slotStop();

        $content = $this->fetchLayout();

        $event = new TB_ViewSlotEvent($this, 'view:output');
        $event->setContent($content);

        $this->eventDispatcher->notify($event);

        ob_end_clean();

        echo trim($event->getAllContent());
    }

    public function ocResponseOutput($output)
    {
        $skip_return_output = false;
        $skip_return_output = TB_Engine::instance()->getEventDispatcher()->filter(new sfEvent($this, 'outputHandler:ocResponseOutput'), $skip_return_output)->getReturnValue();

        if (!$skip_return_output && ($this->context->getArea() != 'catalog' || $this->themeData->header_caught)) {
            return $output;
        }

        $event = new sfEvent($this, 'ocResponse:raw_output.filter', array('raw_output' => $output));
        $this->eventDispatcher->notify($event);

        if ($event->isProcessed()) {
            return $event->getReturnValue();
        }

        if (!TB_RequestHelper::isAjaxRequest() && false !== strpos($output, '<!-- END_COMMON_HEADER -->')) {
            return $this->replaceOutput($output);
        }

        return $output;
    }

    public function customOutput()
    {
        // Do not process and render header/footer

        $this->themeData->set('header_caught', true);

        $content = $this->fetchLayout();

        $event = new TB_ViewSlotEvent($this, 'view:customOutput');
        $event->setContent($content);

        $this->eventDispatcher->notify($event);

        return trim($event->getAllContent());
    }

    protected function fetchLayout()
    {
        $event = new sfEvent($this, 'outputHandler:fetchLayout');
        $this->eventDispatcher->notify($event);

        if ($event->isProcessed()) {
            return $event->getReturnValue();
        }

        ob_start();
        ob_implicit_flush(0);

        $tbData = TB_Engine::instance()->getThemeData();
        require tb_modification(TB_Engine::instance()->getContext()->getCatalogTemplateDir() . '/tb/layout.tpl');

        return ob_get_clean();
    }

    public function areaContentOutput(TB_ViewSlotEvent $event)
    {
        $event->insertContentBefore($this->page_content);
    }

    public function replaceOutput($output)
    {
        ob_start();
        ob_implicit_flush(0);

        $header       = TB_Utils::strGetBefore($output, '<!-- END_COMMON_HEADER -->');
        $page_content = trim(TB_Utils::strGetBetween($output, '<!-- END_COMMON_HEADER -->', '<!-- BEGIN_COMMON_FOOTER -->'));
        $footer       = TB_Utils::strGetAfter($output, '<!-- BEGIN_COMMON_FOOTER -->');

        // Some pages may have no slots (like third party extensions), or may contain non-sloted strings (for
        // example ocmod/vqmod hooks). All of these should be captured in $page_content

        if (in_array('page_content', $this->themeData->areas_system_slots) && false === array_search($this->themeData->route . '.page_content', $this->themeData->viewSlot->getKeys())) {
            // The current route has .page_content slot, but none was met during rendering.
            // It has to be third-party page without slots, which defaults to content__default area
            $this->themeData->slotStart($this->themeData->route . '.page_content');
            echo str_replace('id="content"', '', $page_content);
            $this->themeData->slotStop();
        } else
        if (!empty($page_content)) {
            // The current route is BurnEngine page with or without .page_content slot, so the non-sloted strings are prepended to the content area
            $this->page_content = $page_content;
            TB_Engine::instance()->getEventDispatcher()->connect('area_content:output', array($this, 'areaContentOutput'));
        }


        TB_Engine::instance()->getThemeExtension()->echoHeader($header);
        TB_Engine::instance()->getThemeExtension()->echoFooter($footer);

        return ob_get_clean();
    }

    public function addJsContents($str, $hash = null)
    {
        if (null !== $hash && isset($this->js_contents_hashes[$hash])) {
            return;
        }

        $this->js_contents .= $str;

        if (null !== $hash) {
            $this->js_contents_hashes[$hash] = 1;
        }
    }

    public function viewSlotOcFooter()
    {
        $this->document_styles_before_render = $this->engine->getOcDocument()->getStyles();
        $this->document_scripts_before_render = $this->engine->getOcDocument()->getScripts();
    }

    public function viewOutput(&$html)
    {
        $themeData = $this->themeData;
        $this->eventDispatcher->notify(new sfEvent($this, 'core:generateJs'));
        $items_to_replace = array();

        if ($this->engine->gteOc2()) {
            // 3rd party compatibility, some vqmods search for catalog/view/javascript/bootstrap/js/bootstrap.min.js
            $html = str_replace('<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>', '', $html);
        }

        $js = trim($this->js_contents . $themeData->viewSlot->getJsContents());

        if (is_file($this->context->getThemeDir() . '/scripts.js.tpl')) {
            $theme_js = $this->themeExtension->fetchTemplate($this->context->getThemeDir() . '/scripts.js.tpl', array(), true);
            $js .= str_replace(array('<script>', '</script>'), '', $theme_js);
        }

        $styles = $this->engine->getOcDocument()->getStyles();

        foreach (array_diff(array_keys($styles), array_keys($this->document_styles_before_render)) as $style_hash) {
            if (!empty($themeData->system['critical_css'])) {
                $themeData->oc_styles = array_merge($themeData->oc_styles, array($styles[$style_hash]['href']));
                continue;
            }
            $style_tag = '<link href="'. $styles[$style_hash]['href'] . '" type="text/css" rel="' . $styles[$style_hash]['rel']. '" media="'. $styles[$style_hash]['media']. '" />';
            if (false === strpos($html, $style_tag)) {
                $html = str_replace('</head>', $style_tag . "\n</head>", $html);
            }
        }

        $themeData->appendJavascriptResource($this->getMinifiedJavascript('common.js'));

        if (!$themeData->optimize_js_load) {
            $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] = '';
            if (!$themeData->system['combine_js']) {
                $javascript_vars = $themeData->createJavascriptVars('all');
                if ($themeData->system['minify_js']) {
                    $javascript_vars = TB_Utils::minifyJs($javascript_vars);
                }
                $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] .= '<script>' . $javascript_vars . "</script>\n";
                foreach ($themeData->getJavascriptResources() as $resource) {
                    if (!$this->themeData->system['cache_js'])  {
                        $resource['url'] .= '?' . mt_rand();
                    }
                    if (0 === strpos(basename($resource['url']), 'lazysizes.min.js')) {
                        $script_element = '<script defer src="' . $resource['url'] . '"></script>';
                    } else {
                        $script_element = '<script src="' . $resource['url'] . '"></script>';
                    }
                    $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] .= $script_element . "\n";
                }
            } else {
                $javascript_vars = $themeData->createJavascriptVars(false);
                if ($themeData->system['minify_js']) {
                    $javascript_vars = TB_Utils::minifyJs($javascript_vars);
                }
                $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] .= '<script>' . $javascript_vars . "</script>\n";
                $javascript_resources = $themeData->getJavascriptResources();
                $script_element = '';
                foreach ($javascript_resources as $key => $resource) {
                    if (0 === strpos(basename($resource['url']), 'lazysizes.min.js')) {
                        $script_element = '<script defer src="' . $resource['url'] . '"></script>' . "\n";
                        unset($javascript_resources[$key]);
                    }
                }
                $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] .= $script_element . '<script data-cfasync="false" src="' . $this->mergeJavascript($javascript_resources) . '"></script>' . "\n";
            }

            foreach (array_diff($this->engine->getOcDocument()->getScripts(), $this->document_scripts_before_render) as $script) {
                if (false === strpos($html, '<script type="text/javascript" src="' . $script . '"></script>')) {
                    $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] .= '<script type="text/javascript" src="' . $script . '"></script>' . "\n";
                }
            }

            $js = str_replace(array('<script type="text/javascript"><!--', '<script type="text/javascript">', '<script>', '</script>', '--></script>'), '', $js);
            $javascript_inline_tpl = $this->context->getEngineAreaTemplateDir() . '/javascript_inline.tpl';
            if ($themeData->system['defer_js_load'] || ($this->themeData->optimize_js_load && $this->themeData->system['combine_js'])) {
                $js = $this->themeExtension->fetchTemplate($javascript_inline_tpl, array('js' => $js), true);
            } else {
                $js = $js . $this->themeExtension->fetchTemplate($javascript_inline_tpl, array('js' => ''), true);
            }
            $js = '<script>' . $js . '</script>';

            if ($themeData->system['minify_js']) {
                $external_js = '';
                if (false !== preg_match_all('/(<script type="text\/javascript" src=".*?">.*?<\/script>)/s', $js, $matches)) {
                    foreach ($matches[1] as $match) {
                        $external_js .= $match . "\n";
                    }
                    $js = preg_replace('/(<script type="text\/javascript" src=".*?">.*?<\/script>)/s', '', $js);
                }
                $js = str_replace(array('<script type="text/javascript"><!--', '<script type="text/javascript">', '<script>', '</script>', '--></script>'), '', $js);
                $js = TB_Utils::minifyJs($js);
                $js = $external_js . '<script type="text/javascript">' . $js . '</script>';
            }
        } else {
            $modules_scripts = array();

            foreach (array_diff($this->engine->getOcDocument()->getScripts(), $this->document_scripts_before_render) as $script) {
                if (false !== strpos($html, '<script type="text/javascript" src="' . $script . '"></script>')) {
                    continue;
                }

                if (false === strpos($script, 'http') && false === strpos($script, '//') && is_file($this->context->getRootDir() . '/' . ltrim($script, '/'))) {
                    $themeData->appendJavascriptResource(array(
                        'dir' => $this->context->getRootDir() . '/' . ltrim($script, '/'),
                        'url' => $script
                    ));
                } else {
                    $modules_scripts[] = $script;
                }
            }

            $this->buildOptimizedJavascript($js, $items_to_replace, $html);

            foreach ($modules_scripts as $script) {
                $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] .= '<script type="text/javascript" src="' . $script . '"></script>' . "\n";
            }
        }

        if (!empty($themeData->system['critical_css'])) {
            $styles = array_merge(array($themeData->theme_main_css_src, $themeData->theme_page_css_src), $themeData->oc_styles);
            $styles_placeholder = '';
            foreach ($styles as $style) {
                $styles_placeholder .= 'loadCss("' . $style . '");';
            }
            $items_to_replace['<!--{{stylesheets_placeholder}}-->'] = $styles_placeholder;
        }

        if (!empty($js)) {
            $items_to_replace['</body>'] = $js . "\n</body>";
        }

        $this->assignStyles($items_to_replace);

        if ($this->forceHTTPS) {
            $items_to_replace['http://'] = 'https://';
        }

        if (!empty($items_to_replace)) {
            $html = str_replace(array_keys($items_to_replace), array_values($items_to_replace), $html);
        }

        if ($themeData->system['minify_html']) {
            $search = array(
                '/\>[^\S ]+/s',
                '/[^\S ]+\</s',
                '/(\s)+/s'
            );
            $replace = array(
                '>',
                '<',
                '\\1'
            );
            $html = preg_replace($search, $replace, $html);
        }
    }

    protected function buildOptimizedJavascript(&$js, &$items_to_replace, $html)
    {
        $prepend_js = '';
        $inline_js = '';
        $scripts = $this->themeData->getJavascriptResources();

        if (false !== preg_match_all('/((<script (?:type=".*?"|src=".*?"|data-\w+=".*?").*?>|<script>).*?<\/script>)/is', $html, $matches)) {
            foreach ($matches[1] as $match_key => $match) {
                if (strpos($match, 'data-capture="0"') || (strpos($matches[2][$match_key], 'type="') && false === strpos($matches[2][$match_key], 'javascript'))) {
                    continue;
                }

                if (false === stripos($matches[2][$match_key], ' src=')) {
                    $items_to_replace[$match] = '';
                    if (strpos($match, 'data-critical="1"')) {
                        $inline_js .= $match;
                    } else
                    if (strpos($match, 'data-prepend="1"')) {
                        $prepend_js .= $match;
                    } else {
                        $js .= $match;
                    }
                } else {
                    preg_match('#src=(?:"|\')(.*)(?:"|\')#Usmi', $matches[2][$match_key], $src_match);
                    if (false !== strpos($src_match[1], $this->context->getHost()) || (false === strpos($src_match[1], 'http') && false === strpos($src_match[1], 'https') && false === strpos($src_match[1], '//'))) {
                        $scripts[md5($src_match[1])] = array('url' => $src_match[1]);
                        $items_to_replace[$match] = '';
                    }
                }
            }
            $js = $prepend_js . $js;
            $inline_js = str_replace(array('<script type="text/javascript" data-critical="1">', '</script>'), '', $inline_js);
        }

        $critical_js = file_get_contents($this->context->getCatalogResourceDir() . '/javascript/critical.js');
        $inline_js = '// Critical Javascript' . "\n" . str_replace('/*-critical-inline-scripts*/', $this->themeData->createJavascriptVars(false) . $inline_js, $critical_js);

        $js = str_replace(array('<script type="text/javascript"><!--', '<script><!--', '--></script>'), array('<script type="text/javascript">', '<script>', '</script>'), $js);
        $js = preg_replace('/<script[^>]*>(.*)<\/script>/Uis', '$1', $js);

        $javascript_inline_tpl = $this->context->getEngineAreaTemplateDir() . '/javascript_inline.tpl';
        if ($this->themeData->system['defer_js_load'] || ($this->themeData->optimize_js_load && $this->themeData->system['combine_js'])) {
            $js = $this->themeExtension->fetchTemplate($javascript_inline_tpl, array('js' => $js), true);
        } else {
            $js = $js . $this->themeExtension->fetchTemplate($javascript_inline_tpl, array('js' => ''), true);
        }

        $javascript_resources = array();

        $load_method = $this->themeData->system['defer_js_load'] ? ' defer' : '';

        if ($this->themeData->system['combine_js'])  {
            $js = $inline_js . $js;
            $inline_js = '';

            $js_file = 'inline_' . md5($js) . '.js';
            $js_path = $this->context->getImageDir() . '/cache/tb/' . $js_file;
            if (!is_file($js_path)) {
                if ($this->themeData->system['minify_js']) {
                    $js = TB_Utils::minifyJs($js);
                }
                file_put_contents($js_path, $js);
            }

            $js_url = $this->context->getImageUrl() . 'cache/tb/' . $js_file;
            if (!$this->themeData->system['cache_js'])  {
                $js_url .= '?' . mt_rand();
            }
            $javascript_resources = array(
                1 => array('method' => $load_method, 'url' => $js_url),
                2 => array('method' => $load_method, 'url' => $this->mergeJavascript($scripts))
            );
        } else {
            foreach($scripts as $resource) {
                if (!$this->themeData->system['cache_js'])  {
                    $resource['url'] .= '?' . mt_rand();
                }
                $javascript_resources[] = array('method'=> $load_method, 'url' => $resource['url']);
            }
            $inline_js = $this->themeData->createJavascriptVars() . "\n" . $inline_js . $js;
        }

        $js = '';
        $items_to_replace['<!--{{javascript_resources_placeholder_header}}-->'] = '';
        $items_to_replace['<!--{{javascript_resources_placeholder_footer}}-->'] = '';
        $replace_in = 'header';

        if (!$this->themeData->system['defer_js_load'] && $this->themeData->optimize_js_load) {
            // Move the javascript resource scripts to bottom
            $replace_in = 'footer';
        }

        foreach ($javascript_resources as $resource) {
            $items_to_replace['<!--{{javascript_resources_placeholder_' . $replace_in . '}}-->'] .= '<script' . $resource['method'] . ' data-cfasync="false" src="' . $resource['url'] . '"></script>' . "\n";
        }

        if ($inline_js) {
            if ($this->themeData->system['minify_js'])  {
                $inline_js = TB_Utils::minifyJs($inline_js);
            }

            $items_to_replace['<!--{{javascript_resources_placeholder_footer}}-->'] .= '<script>' . $inline_js . '</script>';
        }

    }

    protected function mergeJavascript($scripts)
    {
        $hashes = array_keys($scripts);
        sort($hashes);

        $scripts_path_hash = md5(implode('|', $hashes));

        if ($this->themeData->system['cache_js'] && $cache_meta = $this->engine->getCacheVar('javascript_cache_meta_' . $scripts_path_hash))  {
            $cache_file = 'cache/tb/main_' . $cache_meta['hash'] . '.script.js';
            if (is_file($this->context->getImageDir() . '/' . $cache_file)) {
                return $this->context->getImageUrl() . $cache_file;
            } else {
                $this->engine->wipeVarsCache('javascript_cache_meta_*');
            }
        }

        $js = '';
        foreach($scripts as $resource) {
            $basename = isset($resource['url']) ? basename($resource['url']) : basename($resource['dir']);

            if (!isset($resource['dir']) && false === strpos($resource['url'], 'http') && false === strpos($resource['url'], 'https') && false === strpos($resource['url'], '//')) {
                $javascript_file = $this->context->getRootDir() . '/' . strtok($resource['url'], '?');
                if (is_file($javascript_file)) {
                    $resource['dir'] = $javascript_file;
                }
            }

            if (isset($resource['dir']) && !is_file($resource['dir']) && substr($basename, -6) == 'min.js') {
                // Prevent race condition. If the cached min.js files are deleted during request, they are not present at this point
                $script_source = $this->getMinifiedJavascript(basename($basename, '.min.js') . '.js', true);
            } else {
                $script_source = isset($resource['dir']) ? file_get_contents($resource['dir']) : TB_Utils::getUrlContents($resource['url']);
                //  Removes multi-line comments
                $script_source = trim(preg_replace('!^[ \t]*/\*\!.*?\*/[ \t]*[\r\n]!s', '', $script_source));
            }

            if (substr($script_source, -1) == ')') {
                $script_source .= ';';
            }

            if ($this->themeData->system['minify_js'] && substr($basename, -6) != 'min.js') {
                $script_source = TB_Utils::minifyJs($script_source);
            }

            $js .= $script_source. "\n";

            if ($basename == 'app.js' || $basename == 'app.min.js') {
                $js_vars = $this->themeData->createJavascriptVars();
                if ($this->themeData->system['minify_js']) {
                    $js_vars = TB_Utils::minifyJs($js_vars);
                }
                $js .= $js_vars;
            }
        }

        $js_hash = md5($js) . '.' . (time() + $this->engine->getConfig('default_cache'));
        $cache_file = 'cache/tb/main_' . $js_hash . '.script.js';

        if (!is_dir($this->context->getImageDir() . '/cache/tb/')) {
            mkdir($this->context->getImageDir() . '/cache/tb/', 0777);
        }

        if (!is_file($cache_file)) {
            if (rand(0,100) < 99 && $files = glob($this->context->getImageDir() . '/cache/tb/main_*.script.js')) {
                foreach ($files as $file) {
                    if (substr(strrchr(preg_replace('/\.script\.js$/', '', $file), '.'), 1) < time()) {
                        @unlink($file);
                    }
                }
            }

            file_put_contents($this->context->getImageDir() . '/' . $cache_file, $js, LOCK_EX);
        }

        if ($this->themeData->system['cache_js'])  {
            $this->engine->setCacheVar('javascript_cache_meta_' . $scripts_path_hash, array('hash' => $js_hash));
        }

        $cache_url = $this->context->getImageUrl() . $cache_file;
        if (!$this->themeData->system['cache_js'])  {
            $cache_url .= '?' . mt_rand();
        }

        return $cache_url;
    }

    private function getMinifiedJavascript($file, $return_contents = false)
    {
        $js_file = $this->context->getCatalogResourceDir() . '/javascript/' . $file;

        if (!is_file($js_file)) {
            $file = basename($js_file, '.js') . '.min.js';
            $js_file = $this->context->getCatalogResourceDir() . '/javascript/' . $file;

            if ($return_contents) {
                return file_get_contents($js_file);
            }

            return array(
                'url' => $this->context->getThemeCatalogJavascriptUrl() . $file,
                'dir' => $js_file
            );
        }

        if ($this->themeData->system['minify_js']) {
            $js_min_file = $this->context->getImageDir() . '/cache/tb/' . $file;

            if (substr($js_min_file, -6) != 'min.js') {
                $js_min_file = substr($js_min_file, 0, -2) . 'min.js';
            }

            if (!file_exists($js_min_file) || !$this->themeData->system['cache_js']) {
                $minified = TB_Utils::minifyJs(file_get_contents($js_file));


                if (!is_dir(dirname($js_min_file))) {
                    TB_Utils::makePath(dirname($js_min_file));
                }

                file_put_contents($js_min_file, $minified);

                if ($return_contents) {
                    return $minified;
                }

            }

            return array(
                'url' => $this->context->getImageUrl() . 'cache/tb/' . basename($js_min_file),
                'dir' => $js_min_file
            );
        }

        if ($return_contents) {
            return file_get_contents($js_file);
        }

        return array(
            'url' => $this->context->getThemeCatalogJavascriptUrl() . $file,
            'dir' => $js_file
        );
    }

    protected function assignStyles(array &$items_to_replace)
    {
        static $assigned = false;

        if ($assigned) {
            return;
        }

        if (!is_dir($this->context->getImageDir() . '/cache/tb/')) {
            mkdir($this->context->getImageDir() . '/cache/tb/', 0777);
        }

        $meta = $this->generateExternalCss();
        $page_css_src = $this->context->getImageUrl() . 'cache/tb/dynamic.' . $meta['css_hash'] . '.css';
        if (!$this->themeData['system']['cache_styles']) {
            $page_css_src .= '?id=' . mt_rand();
        }
        $this->themeData->theme_page_css_src = $page_css_src;
        $this->themeData->webfonts = $meta['webfonts'];

        $meta = $this->generateMainCss();
        $main_css_src = $this->context->getImageUrl() . 'cache/tb/main.'  . $meta['css_hash'] . '.css';
        if (!$this->themeData['system']['cache_styles']) {
            $main_css_src .= '?id=' . mt_rand();
        }
        $this->themeData->theme_main_css_src = $main_css_src;

        $items_to_replace['<!--[if[style_resources_placeholder]]-->'] = '';

        if (!TB_RequestHelper::lteIe9()) {
            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $main_css_src . '" media="all" />' . "\n";
        } else {
            $ie9_main_css_path = $this->context->getImageDir() . '/cache/tb/ie9_main.' . $this->main_css_cache_key . '.css';
            $ie9_external_css_path = $this->context->getImageDir() . '/cache/tb/ie9_external.' . $this->main_css_cache_key . '.css';

            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $this->context->getImageUrl() . 'cache/tb/ie9_external.'  . $this->main_css_cache_key . '.css?id=' . (is_file($ie9_external_css_path) ? md5(file_get_contents($ie9_external_css_path)) : mt_rand()) . '" media="all" />' . "\n";
            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $this->context->getImageUrl() . 'cache/tb/ie9_main.'  . $this->main_css_cache_key . '.css?id=' . (is_file($ie9_external_css_path)  ?md5(file_get_contents($ie9_main_css_path)) : mt_rand()) . '" media="all" />' . "\n";
            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $this->context->getImageUrl() . 'cache/tb/ie9_main_1.'  . $this->main_css_cache_key . '.css?id=' . (is_file($ie9_external_css_path)  ?md5(file_get_contents($ie9_main_css_path)) : mt_rand()) . '" media="all" />' . "\n";
            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $this->context->getImageUrl() . 'cache/tb/ie9_main_2.'  . $this->main_css_cache_key . '.css?id=' . (is_file($ie9_external_css_path)  ?md5(file_get_contents($ie9_main_css_path)) : mt_rand()) . '" media="all" />' . "\n";
            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $this->context->getImageUrl() . 'cache/tb/ie9_main_3.'  . $this->main_css_cache_key . '.css?id=' . (is_file($ie9_external_css_path)  ?md5(file_get_contents($ie9_main_css_path)) : mt_rand()) . '" media="all" />' . "\n";
            $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $this->context->getImageUrl() . 'cache/tb/ie9_main_4.'  . $this->main_css_cache_key . '.css?id=' . (is_file($ie9_external_css_path)  ?md5(file_get_contents($ie9_main_css_path)) : mt_rand()) . '" media="all" />' . "\n";
        }
        $items_to_replace['<!--[if[style_resources_placeholder]]-->'] .= '<link rel="stylesheet" type="text/css" href="' . $page_css_src . '" media="all" />' . "\n";

        $font_links = '';
        foreach ($this->themeData->webfonts as $font) {
            if (!$this->engine->getConfig('catalog_google_fonts_js') || TB_RequestHelper::lteIe9()) {
                $font_links .= '<link href="//fonts.googleapis.com/css?family=' . $font['family'] . '&subset=' . $font['subset'] . '" rel="stylesheet" type="text/css">' . "\n";
            } else {
                $font_links .= 'includeFontResource("//fonts.googleapis.com/css?family=' . $font['family'] . '&subset=' . $font['subset'] . '");';
            }
        }
        $items_to_replace['<!--{{google_fonts_link}}-->'] = $font_links;

        $assigned = true;
    }

    protected function generateMainCss()
    {
        $file_path = $this->context->getImageDir() . '/cache/tb';
        $main_css_path = $file_path . '/main.' . $this->main_css_cache_key . '.css';
        $ie9_main_css_path = $file_path . '/ie9_main.' . $this->main_css_cache_key . '.css';
        $ie9_external_css_path = $file_path . '/ie9_external.' . $this->main_css_cache_key . '.css';

        $meta = false;
        if (file_exists($main_css_path . '.meta.cache')) {
            $meta = unserialize(file_get_contents($main_css_path . '.meta.cache'));
        }

        $generate_ie9 = TB_RequestHelper::lteIe9() && (!file_exists($ie9_main_css_path) || !file_exists($ie9_external_css_path));
        $generate_main = !$this->themeData['system']['cache_styles'] || !$meta || !file_exists($file_path . '/main.' . $meta['css_hash'] . '.css');

        if (!$generate_main && !$generate_ie9) {
            return $meta;
        }

        $css = $this->themeExtension->fetchTemplate('main.css', array(
            'media_queries'           => true,
            'external_css'            => $this->styleBuilder->getExternalCss(),
            'presets_css'             => $this->themeData->presets_css,
            'global_colors_css'       => $this->styleBuilder->getGlobalColorsString(),
            'theme_catalog_image_url' => $this->context->getThemeCatalogImageUrl()

        ));

        if ($this->themeData->system['minify_css']) {
            $css = TB_Utils::minifyCss($css);
        }

        if ($generate_ie9) {
            $ie9_external_css = $this->styleBuilder->getExternalCss();
            if ($this->themeData->system['minify_css']) {
                $ie9_external_css = TB_Utils::minifyCss($ie9_external_css);
            }
            file_put_contents($ie9_external_css_path, $ie9_external_css, LOCK_EX);

            $ie9_main_css = $this->themeExtension->fetchTemplate('main.css', array(
                'media_queries'           => false,
                'external_css'            => '',
                'presets_css'             => $this->themeData->presets_css,
                'global_colors_css'       => $this->styleBuilder->getGlobalColorsString(),
                'theme_catalog_image_url' => $this->context->getThemeCatalogImageUrl()

            ));
            if ($this->themeData->system['minify_css']) {
                $ie9_main_css = TB_Utils::minifyCss($ie9_main_css);
            }
            $ie9_main_css = explode('.ie9_divide_hook{}', $ie9_main_css);
            file_put_contents($ie9_main_css_path, $ie9_main_css[0], LOCK_EX);
            file_put_contents(str_replace('ie9_main.', 'ie9_main_1.', $ie9_main_css_path), $ie9_main_css[1], LOCK_EX);
            file_put_contents(str_replace('ie9_main.', 'ie9_main_2.', $ie9_main_css_path), $ie9_main_css[2], LOCK_EX);
            file_put_contents(str_replace('ie9_main.', 'ie9_main_3.', $ie9_main_css_path), $ie9_main_css[3], LOCK_EX);
            file_put_contents(str_replace('ie9_main.', 'ie9_main_4.', $ie9_main_css_path), $ie9_main_css[4], LOCK_EX);
        }

        if ($generate_main) {
            $meta = array(
                'css_hash' => md5($css)
            );
            file_put_contents($main_css_path . '.meta.cache', serialize($meta), LOCK_EX);

            $css = str_replace('.ie9_divide_hook{}', '', $css);
            file_put_contents($file_path . '/main.' . $meta['css_hash'] . '.css', $css, LOCK_EX);
        }

        return $meta;
    }

    public function determineStylesCacheKey()
    {
        if ($this->themeData->skip_layout) {
            return;
        }

        $this->file_hash = $this->buildCssCacheHash();
        $this->main_css_cache_key =  $this->themeData->language_direction . '.' . $this->context->getStoreId() . (TB_RequestHelper::isRequestHTTPS() ? '.ssl': '') . '.' . substr(md5($this->context->getHost()), 0, 5);

        $file_path = $this->context->getImageDir() . '/cache/tb';
        $meta = false;
        if (file_exists($file_path . '/' . $this->file_hash . '.meta.cache')) {
            $meta = unserialize(file_get_contents($file_path . '/' . $this->file_hash . '.meta.cache'));
        }

        $ie9_main_css_path = $file_path . '/ie9_main.' . $this->main_css_cache_key . '.css';;
        $ie9_external_css_path = $file_path . '/ie9_external.' . $this->main_css_cache_key . '.css';
        $generate_ie9 = TB_RequestHelper::lteIe9() && (!file_exists($ie9_main_css_path) || !file_exists($ie9_external_css_path));

        if (!$meta || !file_exists($file_path . '/dynamic.' . $meta['css_hash'] . '.css') || $generate_ie9) {
            $this->styles_cache_key = false;
        } else {
            $this->styles_cache_key = $this->file_hash;
        }
    }

    public function assignJavascriptAfterDispatch()
    {
        $this->themeData->appendJavascriptResource($this->getMinifiedJavascript('app.js'));
        if ($this->engine->gteOc2()) {
            $this->themeData->prependJavascriptResource('javascript/bootstrap.min.js');
            $this->eventDispatcher->notify(new sfEvent($this->themeData, 'core:prependTwitterBootstrap'));
        } else {
            $this->themeData->prependJavascriptResource('javascript/jquery-ui.min.js');
        }
        $this->themeData->prependJavascriptResource('javascript/jquery-migrate.min.js');
        $this->themeData->prependJavascriptResource('javascript/jquery.min.js');

        if (!$this->themeData->optimize_js_load) {
            $this->themeData->prependJavascriptResource($this->getMinifiedJavascript('critical.js'));
        }

        if ($this->themeData->system['image_lazyload'] || $this->themeData->system['bg_lazyload'] || $this->themeData->system['js_lazyload'])  {
            $this->themeData->prependJavascriptResource('javascript/lazysizes.min.js');
        }

        $this->themeData->appendJavascriptResource('javascript/libs.min.js');

        $event = new sfEvent($this->themeData, 'core:assignAssets');
        $this->engine->getEventDispatcher()->notify($event);
    }

    protected function buildCssCacheHash()
    {
        static $hash;

        if (null !== $hash) {
            return $hash;
        }

        $hash = array();

        foreach ($this->themeData->request_areas_settings as $area => $key) {
            $hash[] = $this->shortenAreaName($area) . '_' . $this->shortenKeyName(str_replace('/', '_', $key));
        }

        foreach ($this->themeData->request_areas as $area => $key) {
            if (!in_array($area . '_' . str_replace('/', '_', $key), $hash)) {
                $hash[] = 'ar_' . $this->shortenAreaName($area) . '_' . $this->shortenKeyName(str_replace('/', '_', $key));
            }
        }

        $hash = !empty($hash) ? implode('.', $hash) : 'no_area';
        $hash .= '.' . $this->context->getCurrentLanguage('code');
        if (TB_RequestHelper::isRequestHTTPS()) {
            $hash .= '.ssl';
        }

        $hash .= '.' . substr(md5($this->context->getHost()), 0, 5);

        if ($this->themeData->body_background && $this->themeData->body_background['image']) {
            $hash .= '.' . substr(md5($this->themeData->body_background['image']), -5);
        }

        return $hash;
    }

    protected function shortenAreaName($area)
    {
        switch ($area) {
            case 'header':
                return 'head';
            case 'footer':
                return  'foot';
            case 'content':
                return  'cont';
            case 'column_left':
                return  'col_l';
            case 'column_right':
                return  'col_r';
        }

        return $area;
    }

    protected function shortenKeyName($key)
    {
        return str_replace(
            array('system', 'product', 'default', 'information', 'global'),
            array('sys', 'prod', 'def', 'info', 'glob'),
            $key
        );
    }

    protected function generateExternalCss()
    {
        $file_path = $this->context->getImageDir() . '/cache/tb';
        $resource_hash = $this->buildCssCacheHash();

        $meta = false;
        if (file_exists($file_path . '/' . $resource_hash . '.meta.cache')) {
            $meta = unserialize(file_get_contents($file_path . '/' . $resource_hash . '.meta.cache'));
        }

        $generate_external = !$this->themeData['system']['cache_styles'] || !$meta || !file_exists($file_path . '/dynamic.' . $meta['css_hash'] . '.css');

        if (!$generate_external) {
            return $meta;
        }

        $this->generateStyles();

        if (!is_dir($file_path)) {
            mkdir(dirname($file_path), 0777);
        }

        $this->eventDispatcher->notify(new sfEvent($this, 'core:generateExternalCss', array('styleBuilder' => $this->styleBuilder)));

        $css = $this->themeExtension->fetchTemplate('page.css', array(
            'scoped_colors_css'       => $this->styleBuilder->getScopedColorsString(),
            'plugins_css'             => $this->styleBuilder->getCssString(),
            'fonts_css'               => $this->styleBuilder->getFontsString(),
            'theme_catalog_image_url' => $this->context->getThemeCatalogImageUrl()
        ));

        if ($this->themeData->system['minify_css']) {
            $css = TB_Utils::minifyCss($css);
        }

        $css_hash = md5($css);

        $css = '/* ' . $this->buildCssCacheHash() . ' */' . $css;
        file_put_contents($file_path . '/dynamic.' . $css_hash . '.css', $css, LOCK_EX);

        $meta = array(
            'webfonts' => $this->styleBuilder->getWebFonts(),
            'css_hash' => $css_hash
        );
        file_put_contents($file_path . '/' . $resource_hash . '.meta.cache', serialize($meta), LOCK_EX);

        return $meta;
    }

    protected function generateStyles()
    {
        static $generated = false;

        if ($generated) {
            return;
        }

        $this->styleBuilder->setBaseLineHeight($this->themeData->fonts['body']['line-height']);

        foreach ($this->themeExtension->getPlugins() as $plugin) {
            if (method_exists($plugin, 'buildStyles')) {
                $plugin->buildStyles($this->styleBuilder, $this->themeData);
            }
        }

        $generated = true;
    }
}