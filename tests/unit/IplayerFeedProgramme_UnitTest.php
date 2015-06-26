<?php
namespace Throup\GrabRadio;

class IplayerFeedProgramme_UnitTest extends \PHPUnit_Framework_TestCase {
    public function testAcceptsValidPid() {
        return new IplayerFeedProgramme($this->_pid);
    }

    public function testGetMetadataForCraven() {
        $feed = new IplayerFeedProgramme('b01dq51g');
        $this->assertEquals("15 Minute Drama", $feed->getBrand());
        $this->assertEquals("Craven", $feed->getProgramme());
        $this->assertEquals("Episode 4", $feed->getTitle());
        $this->assertEquals(2, $feed->getSeries());
        $this->assertEquals(4, $feed->getEpisode());
        $this->assertEquals(5, $feed->getTotal());
    }

    public function testGetMetadataForFarmingToday() {
        $feed = new IplayerFeedProgramme('b01q02sg');
        $this->assertEquals("Farming Today", $feed->getBrand());
        $this->assertEquals("Farming Today", $feed->getProgramme());
        $this->assertEquals("25/01/2013", $feed->getTitle());
        $this->assertEquals(0, $feed->getSeries());
        $this->assertEquals(0, $feed->getEpisode());
        $this->assertEquals(0, $feed->getTotal());
    }

    public function testGetMetadataForTheOutsourced() {
        $feed = new IplayerFeedProgramme('b01pzqpv');
        $this->assertEquals("The Outsourced", $feed->getBrand());
        $this->assertEquals("The Outsourced", $feed->getProgramme());
        $this->assertEquals("The Outsourced", $feed->getTitle());
        $this->assertEquals(0, $feed->getSeries());
        $this->assertEquals(0, $feed->getEpisode());
        $this->assertEquals(0, $feed->getTotal());
    }

    public function testGetMetadataForProjectArchangel() {
        $feed = new IplayerFeedProgramme('b00wmznd');
        $this->assertEquals("Jenny Stephens", $feed->getBrand());
        $this->assertEquals("Project Archangel", $feed->getProgramme());
        $this->assertEquals("Episode 4", $feed->getTitle());
        $this->assertEquals(0, $feed->getSeries());
        $this->assertEquals(4, $feed->getEpisode());
        $this->assertEquals(4, $feed->getTotal());
    }

    public function testGetMetadataForWomansHour() {
        $feed = new IplayerFeedProgramme('b01q030g');
        $this->assertEquals("Woman's Hour", $feed->getBrand());
        $this->assertEquals("Woman's Hour", $feed->getProgramme());
        $this->assertEquals("Armistead Maupin, pension proposals, Sarah Vine discusses thinning hair",
                            $feed->getTitle());
        $this->assertEquals(0, $feed->getSeries());
        $this->assertEquals(0, $feed->getEpisode());
        $this->assertEquals(0, $feed->getTotal());
    }

    public function testRejectsEmptyPid() {
        try {
            $feed = new IplayerFeedProgramme('');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsMissingPid() {
        try {
            $feed = new IplayerFeedProgramme();
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsNonsensePid() {
        try {
            $feed = new IplayerFeedProgramme('nonsense_pid');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    /**
     * @depends testAcceptsValidPid
     */
    public function testSetsValidUrl($feed) {
        $this->assertEquals("http://www.bbc.co.uk/programmes/{$this->_pid}.json", $feed->getUrl());
        return;
    }

    protected function setUp() {
        $this->_setValidTestPid();
    }

    protected function _setValidTestPid() {
        $this->_pid = 'b01q02sg';
    }

    protected $_pid;
}

