<?php
namespace Throup\GrabRadio;

class IplayerFeedStation_UnitTest extends \PHPUnit_Framework_TestCase {
    public function testAcceptsValidStation() {
        try {
            $feed = new IplayerFeedStation($this->_station);
        } catch (\Exception $e) {
            $this->fail();
        }
        return $feed;
    }

    public function testRejectsEmptyStation() {
        try {
            $feed = new IplayerFeedStation('');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsMissingStation() {
        try {
            $feed = new IplayerFeedStation();
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsNonsenseStation() {
        try {
            $feed = new IplayerFeedStation('nonsense_station');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    /**
     * @depends testAcceptsValidStation
     */
    public function testSetsValidUrl($feed) {
        $this->assertEquals("http://feeds.bbc.co.uk/iplayer/{$this->_station}/list", $feed->getUrl());
        return;
    }

    protected function setUp() {
        $this->_setValidTestStation();
    }

    protected function _setValidTestStation() {
        $this->_station = 'bbc_radio_four';
    }

    protected $_station;
}

