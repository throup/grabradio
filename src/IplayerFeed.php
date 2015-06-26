<?php
namespace Throup\GrabRadio;

use SimpleXMLElement;

class IplayerFeed {
    public function __construct($url) {
        $this->_setUrl($url);
        $this->_fetchContent();
        $this->_extractXml();
    }

    protected function _fetchContent() {
        if (!($this->_content = file_get_contents($this->getUrl()))) {
            throw new \Exception("Failed to get feed content.");
        }
    }

    public function getUrl() {
        return $this->_url;
    }

    protected function _setUrl($url) {
        self::_validateUrl($url);
        $this->_url = $url;
    }

    private function _extractXml() {
        $content = $this->_getContent();
        if ($this->_runTidy) {
            $tidy    = new \tidy();
            $content = (string)$tidy->repairString($content,
                                                   [
                                                       'input-xml'    => true,
                                                       'output-xml'   => true,
                                                       'wrap'         => 80,
                                                       'force-output' => true,
                                                   ],
                                                   'utf8');
            $content = self::remove_entities($content);
        }
        if (get_class($this) == 'uk\org\throup\grabradio\IplayerFeedProgramme') {
            # file_put_contents('/tmp/ref.xml', $content); exit();
        }
        $this->_xml = new SimpleXMLElement($content);
    }

    protected function _getContent() {
        return $this->_content;
    }

    protected static function remove_entities($text) {
        $all  = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES | ENT_HTML401);
        $keep = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES | ENT_XML1);
        $lose = [];
        foreach ($all as $char => $entity) {
            if (!array_key_exists($char, $keep)) {
                $lose[$entity] = $char;
            }
        }
        return strtr($text, $lose);
    }

    protected static function _validateUrl($url) {
        if (!is_string($url)) {
            throw new \Exception("\$url must be a string");
        }
    }

    /**
     * @return SimpleXMLElement
     */
    protected function _getXml() {
        return $this->_xml;
    }

    protected $_runTidy = false;

    /**
     * @var SimpleXMLElement
     */
    protected $_xml = null;

    private   $_content = '';

    private   $_url     = '';
}

