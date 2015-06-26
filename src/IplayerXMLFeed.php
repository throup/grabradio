<?php
namespace Throup\GrabRadio;

use SimpleXMLElement;

class IplayerXMLFeed extends IplayerFeed {
    protected function _extractData() {
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

    private static function remove_entities($text) {
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
}
