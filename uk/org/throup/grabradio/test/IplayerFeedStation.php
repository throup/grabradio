<?php
namespace uk\org\throup\grabradio\test {
	require_once(__DIR__ . '/../_config.inc');
	use uk\org\throup                as throup;
	use uk\org\throup\grabradio      as grabradio;
	use uk\org\throup\grabradio\test as test;
	class IplayerFeedStation extends \PHPUnit_Framework_TestCase {
		protected $_station;
		protected function setUp() {
			$this->_setValidTestStation();
		}
		public function testRejectsMissingStation() {
			try {
				$feed = new grabradio\IplayerFeedStation();
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testRejectsEmptyStation() {
			try {
				$feed = new grabradio\IplayerFeedStation('');
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testRejectsNonsenseStation() {
			try {
				$feed = new grabradio\IplayerFeedStation('nonsense_station');
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testAcceptsValidStation() {
			try {
				$feed = new grabradio\IplayerFeedStation($this->_station);
			} catch (\Exception $e) {
				$this->fail();
			}
			return $feed;
		}
		/**
		 * @depends testAcceptsValidStation
		 */
		public function testSetsValidUrl($feed) {
			$this->assertEquals("http://feeds.bbc.co.uk/iplayer/{$this->_station}/list", $feed->getUrl());
			return;
		}
		protected function _setValidTestStation() {
			$this->_station = 'bbc_radio_four';
		}
	}
}
