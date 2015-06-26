<?php
namespace uk\org\throup\grabradio\test {
	require_once(__DIR__ . '/../_config.inc');
	use uk\org\throup                as throup;
	use uk\org\throup\grabradio      as grabradio;
	use uk\org\throup\grabradio\test as test;
	class IplayerFeedEpisode extends \PHPUnit_Framework_TestCase {
		protected $_pid;
		protected function setUp() {
			$this->_setValidTestPid();
		}
		public function testRejectsMissingPid() {
			try {
				$feed = new grabradio\IplayerFeedEpisode();
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testRejectsEmptyPid() {
			try {
				$feed = new grabradio\IplayerFeedEpisode('');
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testRejectsNonsensePid() {
			try {
				$feed = new grabradio\IplayerFeedEpisode('nonsense_pid');
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testAcceptsValidPid() {
			try {
				$feed = new grabradio\IplayerFeedEpisode($this->_pid);
			} catch (\Exception $e) {
				print_r($e);
				$this->fail();
			}
			return $feed;
		}
		/**
		 * @depends testAcceptsValidPid
		 */
		public function testSetsValidUrl($feed) {
			$this->assertEquals("http://www.bbc.co.uk/iplayer/playlist/{$this->_pid}", $feed->getUrl());
			return;
		}
		protected function _setValidTestPid() {
			$this->_pid = 'b01q02sg';
		}
	}
}
