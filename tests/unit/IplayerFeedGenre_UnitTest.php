<?php
namespace Throup\GrabRadio;

class IplayerFeedGenre_UnitTest extends \PHPUnit_Framework_TestCase {
    public function testAcceptsValidGenre() {
        return new IplayerFeedGenre($this->_genre);
    }

    public function testRejectsEmptyGenre() {
        try {
            $feed = new IplayerFeedGenre('');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsMissingGenre() {
        try {
            $feed = new IplayerFeedGenre();
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    public function testRejectsNonsenseGenre() {
        try {
            $feed = new IplayerFeedGenre('nonsense_genre');
        } catch (\Exception $e) {
            return;
        }
        $this->fail();
    }

    /**
     * @depends testAcceptsValidGenre
     */
    public function testSetsValidUrl($feed) {
        $this->assertEquals("http://feeds.bbc.co.uk/iplayer/{$this->_genre}/list", $feed->getUrl());
        return;
    }

    protected function setUp() {
        $this->_setValidTestGenre();
    }

    protected function _setValidTestGenre() {
        $this->_genre = 'bbc_radio_four';
    }

    protected $_genre;
}

