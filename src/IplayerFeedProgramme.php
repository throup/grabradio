<?php
namespace Throup\GrabRadio;

class IplayerFeedProgramme extends IplayerJsonFeed implements I_MetadataProvider {
    public function __construct($pid) {
        self::_validatePid($pid);
        $this->_pid = $pid;
        $url        = "http://www.bbc.co.uk/programmes/$pid.json";
        $this->_setUrl($url);
        $this->_runTidy = true;
        parent::__construct($url);
        //	$this->_spoutXml();
        $this->_extractMetadata();
    }

    protected static function _validatePid($pid) {
        if (!preg_match('/^[a-z0-9]{8}$/', $pid)) {
            throw new \Exception("\$pid must be in the form of 8 alphanumeric characters");
        }
    }

    protected function _extractMetadata() {
        $feed = $this->_getExtractedContent();

        $parts       = [];
        $series      = 0;
        $episode     = 0;
        $total       = 0;
        $date        = '';
        $description = '';

        $entity  = $feed->programme;
        while (true) {
            array_unshift($parts, $entity->title);
            if (isset($entity->parent) && isset($entity->parent->programme)) {
                $entity = $entity->parent->programme;
            } else {
                break;
            }
        }

        if ($feed->programme->long_synopsis) {
            $description = $feed->programme->long_synopsis;
        } else if ($feed->programme->medium_synopsis) {
            $description = $feed->programme->medium_synopsis;
        } else if ($feed->programme->short_synopsis) {
            $description = $feed->programme->short_synopsis;
        }

        if ($feed->programme->first_broadcast_date) {
            $date = $feed->programme->first_broadcast_date;
        }

        if ($feed->programme->position) {
            $episode = $feed->programme->position;
        }

        if (isset($feed->programme->parent) && isset($feed->programme->parent->programme->expected_child_count)) {
            $total = $feed->programme->parent->programme->expected_child_count;
        }

        $last_title = '';
        $bits       = [];
        foreach ($parts as $title) {
            $title = trim($title);
            if ($title != $last_title) {
                $last_title = $title;
                $bits       = array_merge($bits, self::split_to_array($title));
            }
        }

        $titles = [];
        foreach ($bits as $title) {
            if (preg_match('/^Series (\d+)$/', $title, $matches)) {
                $series = $matches[1];
            } else {
                $titles[] = $title;
            }
        }

        if ($titles) {
            $this->_title = array_pop($titles);
        }

        if ($titles) {
            $this->_programme = array_pop($titles);
        } else {
            $this->_programme = $this->_title;
        }

        if ($titles) {
            $this->_brand = array_pop($titles);
        } else {
            $this->_brand = $this->_programme;
        }

        $this->_series      = trim($series);
        $this->_episode     = trim($episode);
        $this->_total       = trim($total);
        $this->_description = trim($description);
        $this->_date        = strtotime($date);
    }

    protected static function split_to_array($value) {
        $array   = [];
        $matches = [];
        if (preg_match('/^(.+) - (.+)$/', $value, $matches)) {
            $array = array_merge($array, self::split_to_array($matches[1]));
            $array = array_merge($array, self::split_to_array($matches[2]));
        } else if (preg_match('/^(.+): (.+)$/', $value, $matches)) {
            $array = array_merge($array, self::split_to_array($matches[1]));
            $array = array_merge($array, self::split_to_array($matches[2]));
        } else {
            $array[] = trim($value);
        }
        return $array;
    }

    public function getBrand() {
        return $this->_brand;
    }

    public function getProgramme() {
        return $this->_programme;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function getSeries() {
        return $this->_series;
    }

    public function getEpisode() {
        return $this->_episode;
    }

    public function getTotal() {
        return $this->_total;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function getDate() {
        return $this->_date;
    }

    public function getImage() {
        return "http://ichef.bbci.co.uk/images/ic/1248x702/legacy/episode/" . $this->getPid() . ".jpg";
    }

    public function getPid() {
        return $this->_pid;
    }

    private $_brand       = '';

    private $_date        = 0;

    private $_description = '';

    private $_episode     = 0;

    private $_image       = '';

    private $_pid;

    private $_programme   = '';

    private $_series      = 0;

    private $_title       = '';

    private $_total       = 0;
}
