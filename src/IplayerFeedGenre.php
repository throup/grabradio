<?php
namespace Throup\GrabRadio;

use DateTimeImmutable;
use DateTimeInterface;

class IplayerFeedGenre extends IplayerJsonFeed {
    public function __construct($genre, DateTimeInterface $date = null) {
        if (!$date) {
            $date = new DateTimeImmutable();
        }
        self::_validateGenre($genre);
        $url = sprintf(
            "http://www.bbc.co.uk/radio/programmes/genres/%s/schedules/%04d/%02d/%02d.json",
            $genre,
            $date->format('Y'),
            $date->format('m'),
            $date->format('d')
        );
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

        foreach ($feed->broadcasts as $broadcast) {
            $entry = $broadcast->programme;
            if ($entry->type == 'episode') {
                $pid           = $entry->pid;
                $this->_pids[] = $pid;
            } else if ($entry->type == 'series') {
                $pid = $entry->pid;
                $series = new IplayerFeedBrand($pid);
                $this->_pids[] = array_merge($this->_pids, $series->getPids());
            } else if ($entry->type == 'brand') {
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

