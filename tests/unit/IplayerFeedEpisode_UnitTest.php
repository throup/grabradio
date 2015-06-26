<?php
namespace Throup\GrabRadio;

class IplayerFeedEpisode_UnitTest extends \PHPUnit_Framework_TestCase {
    public function testAcceptsValidPid() {
        return new IplayerFeedEpisode($this->_pid);
    }

    public function testRejectsEmptyPid() {
        try {
            $feed = new IplayerFeedEpisode('');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsMissingPid() {
        try {
            $feed = new IplayerFeedEpisode();
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsNonsensePid() {
        try {
            $feed = new IplayerFeedEpisode('nonsense_pid');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    /**
     * @depends testAcceptsValidPid
     */
    public function testSetsValidUrl($feed) {
        $this->assertEquals("http://www.bbc.co.uk/iplayer/playlist/{$this->_pid}", $feed->getUrl());
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
