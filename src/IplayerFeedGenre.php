<?php
namespace Throup\GrabRadio;

class IplayerFeedGenre extends IplayerJsonFeed {
    public function __construct($genre) {
        self::_validateGenre($genre);
        $url = "http://www.bbc.co.uk/radio/programmes/genres/{$genre}/player.json";
        parent::__construct($url);
        $this->_extractEpisodes();
    }

    protected static function _validateGenre($genre) {
        if (!preg_match('/^[a-z_]+$/', $genre)) {
            throw new \Exception("\$genre must be in the form 'xxxxx_xxx'");
        }
    }

    protected function _extractEpisodes() {
        $feed = $this->_getExtractedContent();

        foreach ($feed->category_slice->programmes as $entry) {
            if ($entry->type == 'episode' && $entry->is_available) {
                $pid           = $entry->pid;
                $this->_pids[] = $pid;
            } else if ($entry->type == 'series' && $entry->is_available) {
                $pid = $entry->pid;
                $series = new IplayerFeedBrand($pid);
                $this->_pids[] = array_merge($this->_pids, $series->getPids());
            } else if ($entry->type == 'brand' && $entry->is_available) {
                $pid = $entry->pid;
                $series = new IplayerFeedBrand($pid);
                $this->_pids[] = array_merge($this->_pids, $series->getPids());
            }
        }
    }

    public function getPids() {
        return $this->_pids;
    }

    protected $_pids = [];
}

class IplayerFeedBrand extends IplayerJsonFeed {
    public function __construct($pid) {
        $url = "http://www.bbc.co.uk/programmes/{$pid}/episodes/player.json";
        parent::__construct($url);
        $this->_extractEpisodes();
    }

    protected function _extractEpisodes() {
        $feed = $this->_getExtractedContent();
        foreach ($feed->episodes as $entry) {
            if ($entry->programme->type == 'episode') {
                $pid           = $entry->programme->pid;
                $this->_pids[] = $pid;
            }
        }
    }

    public function getPids() {
        return $this->_pids;
    }

    protected $_pids = [];
}

