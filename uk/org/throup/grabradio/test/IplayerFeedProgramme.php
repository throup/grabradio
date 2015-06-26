<?php
namespace uk\org\throup\grabradio\test {
	require_once(__DIR__ . '/../_config.inc');
	use uk\org\throup                as throup;
	use uk\org\throup\grabradio      as grabradio;
	use uk\org\throup\grabradio\test as test;
	class IplayerFeedProgramme extends \PHPUnit_Framework_TestCase {
		protected $_pid;
		protected function setUp() {
			$this->_setValidTestPid();
		}
		public function testRejectsMissingPid() {
			try {
				$feed = new grabradio\IplayerFeedProgramme();
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testRejectsEmptyPid() {
			try {
				$feed = new grabradio\IplayerFeedProgramme('');
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testRejectsNonsensePid() {
			try {
				$feed = new grabradio\IplayerFeedProgramme('nonsense_pid');
			} catch (\Exception $e) {
				return;
			}
			$this->fail();
		}
		public function testAcceptsValidPid() {
			try {
				$feed = new grabradio\IplayerFeedProgramme($this->_pid);
			} catch (\Exception $e) {
				print_r($e);
				$this->fail();
			}
			return $feed;
		}
		public function testGetMetadataForOutsourced() {
			$feed = new grabradio\IplayerFeedProgramme('b01pzqpv');
			$this->assertEquals("Outsourced",        $feed->getBrand());
			$this->assertEquals("Outsourced",        $feed->getProgramme());
			$this->assertEquals("Outsourced",        $feed->getTitle());
			$this->assertEquals(0,                   $feed->getSeries());
			$this->assertEquals(0,                   $feed->getEpisode());
			$this->assertEquals(0,                   $feed->getTotal());
		}
		public function testGetMetadataForFarmingToday() {
			$feed = new grabradio\IplayerFeedProgramme('b01q02sg');
			$this->assertEquals("Farming Today",     $feed->getBrand());
			$this->assertEquals("Farming Today",     $feed->getProgramme());
			$this->assertEquals("25/01/2013",        $feed->getTitle());
			$this->assertEquals(0,                   $feed->getSeries());
			$this->assertEquals(0,                   $feed->getEpisode());
			$this->assertEquals(0,                   $feed->getTotal());
		}
		public function testGetMetadataForProjectArchangel() {
			$feed = new grabradio\IplayerFeedProgramme('b00wmznd');
			$this->assertEquals("Jenny Stephens",    $feed->getBrand());
			$this->assertEquals("Project Archangel", $feed->getProgramme());
			$this->assertEquals("Episode 4",         $feed->getTitle());
			$this->assertEquals(0,                   $feed->getSeries());
			$this->assertEquals(4,                   $feed->getEpisode());
			$this->assertEquals(4,                   $feed->getTotal());
		}
		public function testGetMetadataForCraven() {
			$feed = new grabradio\IplayerFeedProgramme('b01dq51g');
			$this->assertEquals("15 Minute Drama",   $feed->getBrand());
			$this->assertEquals("Craven",            $feed->getProgramme());
			$this->assertEquals("Episode 4",         $feed->getTitle());
			$this->assertEquals(2,                   $feed->getSeries());
			$this->assertEquals(4,                   $feed->getEpisode());
			$this->assertEquals(5,                   $feed->getTotal());
		}
		public function testGetMetadataForWomansHour() {
			$feed = new grabradio\IplayerFeedProgramme('b01q030g');
			$this->assertEquals("Woman's Hour",     $feed->getBrand());
			$this->assertEquals("Woman's Hour",     $feed->getProgramme());
			$this->assertEquals("Armistead Maupin, pension proposals, Sarah Vine discusses thinning hair", $feed->getTitle());
			$this->assertEquals(0,                   $feed->getSeries());
			$this->assertEquals(0,                   $feed->getEpisode());
			$this->assertEquals(0,                   $feed->getTotal());
		}
		/**
		 * @depends testAcceptsValidPid
		 */
		public function testSetsValidUrl($feed) {
			$this->assertEquals("http://www.bbc.co.uk/programmes/{$this->_pid}", $feed->getUrl());
			return;
		}
		protected function _setValidTestPid() {
			$this->_pid = 'b01q02sg';
		}
	}
}
