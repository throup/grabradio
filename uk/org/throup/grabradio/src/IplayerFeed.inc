<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');
	class IplayerFeed {
		public function __construct($url) {
			$this->_setUrl($url);
			$this->_fetchContent();
			$this->_extractXml();
		}
		public function getUrl() {
			return $this->_url;
		}
		protected function _setUrl($url) {
			self::_validateUrl($url);
			$this->_url = $url;
		}
		protected function _getContent() {
			return $this->_content;
		}
		protected function _getXml() {
			return $this->_xml;
		}
		private $_url     = '';
		private $_content = '';
		protected $_runTidy = false;
		protected $_xml     = null;
		protected function _fetchContent() {
			if (!($this->_content = file_get_contents($this->getUrl()))) {
				throw new \Exception("Failed to get feed content.");
			}
		}
		private function _extractXml() {
			$content = $this->_getContent();
			if ($this->_runTidy) {
				$tidy = new \tidy();
				$content = (string) $tidy->repairString($content, array('input-xml'=>true, 'output-xml'=>true, 'wrap'=>80, 'force-output'=>true), 'utf8');
				$content = self::remove_entities($content);
			}
            if (get_class($this) == 'uk\org\throup\grabradio\IplayerFeedProgramme') {
               # file_put_contents('/tmp/ref.xml', $content); exit();
            }
			$this->_xml = new \SimpleXMLElement($content);
		}
		protected static function _validateUrl($url) {
			if(!is_string($url)) {
				throw new \Exception("\$url must be a string");
			}
		}
		protected static function remove_entities($text) {
			$all  = get_html_translation_table(HTML_ENTITIES,     ENT_QUOTES|ENT_HTML401);
			$keep = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES|ENT_XML1);
			$lose = array();
			foreach ($all as $char=>$entity) {
				if (!array_key_exists($char, $keep)) {
					$lose[$entity] = $char;
				}
			}
			return strtr($text, $lose);
		}
	}
}
