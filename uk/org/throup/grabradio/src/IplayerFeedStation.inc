<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');
	class IplayerFeedStation extends IplayerFeed {
		public function __construct($station) {
			self::_validateStation($station);
			$url = "http://feeds.bbc.co.uk/iplayer/$station/list";
			parent::__construct($url);
			$this->_extractEpisodes();
		}
		
		public function getPids() {
			return $this->_pids;
		}
		
		protected $_pids = array();
		
		protected function _extractEpisodes() {
			$feed = $this->_getXml();
			if ($feed->getName() != "feed"
			 || $feed->children('http://www.w3.org/2005/Atom')->count() < 1) {
				throw new \Exception("No Atom feed found.");
			}
			$matches = array();
			foreach ($feed->children('http://www.w3.org/2005/Atom')->entry as $entry) {
				if (preg_match('/tag:feeds.bbc.co.uk,2008:PIPS:([a-z0-9]{8})/', $entry->id, $matches)) {
					$pid = $matches[1];
					$this->_pids[] = $pid;
				}
			}
		}
		protected static function _validateStation($station) {
			if(!preg_match('/^bbc_[a-z_]+$/', $station)) {
				throw new \Exception("\$station must be in the form 'bbc_xxxxx_xxx'");
			}
		}
	}
}
		