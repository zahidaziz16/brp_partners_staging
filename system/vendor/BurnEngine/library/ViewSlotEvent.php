<?php

require_once TB_THEME_ROOT . '/library/vendor/phpQuery.php';

class TB_ViewSlotEvent extends sfEvent
{
    private $content = '';
    private $content_before = array();
    private $content_after = array();

    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return phpQueryObject
     */
    public function getContentPhpQuery()
    {
        return phpQuery::newDocument($this->content);
    }

    /**
     * @return string
     */
    public function getAllContent()
    {
        ksort($this->content_before);
        ksort($this->content_after);

        return implode('', $this->content_before) .
               $this->content .
               implode('', $this->content_after);
    }

    public function insertContentBefore($content, $order = 50)
    {
        $this->content_before[$order] = $content;
    }

    public function insertContentAfter($content, $order = 50)
    {
        $this->content_after[$order] = $content;
    }
}