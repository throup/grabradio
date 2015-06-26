<?php
namespace Throup\GrabRadio;

class IplayerFeedEpisode extends IplayerFeed {
    public function __construct($pid) {
        self::_validatePid($pid);
        $this->_pid = $pid;
        $url        = "http://www.bbc.co.uk/iplayer/playlist/$pid";
        parent::__construct($url);
        $this->_extractMetadata();
    }

    protected static function _validatePid($pid) {
        if (!preg_match('/^[a-z0-9]{8}$/', $pid)) {
            throw new \Exception("\$pid must be in the form of 8 alphanumeric characters");
        }
    }

    protected function _extractMetadata() {
        $playlist = $this->_getXml();
        if ($playlist->getName() != "playlist"
            || $playlist->children('http://bbc.co.uk/2008/emp/playlist')->count() < 1
        ) {
            throw new \Exception("No BBC playlist found.");
        }
        foreach ($playlist->children('http://www.w3.org/2005/Atom')->entry as $entry) {
            $idstring = $entry->id[0];
            $matches  = [];
            if (preg_match('/^tag\:feeds\.bbc\.co\.uk\,2008\:PIPS\:([a-z0-9]{8})$/', $idstring, $matches)) {
                $pid               = $matches[1];
                $this->_pids[$pid] = $pid;
            } else {
                throw new \Exception("Couldn't match pid in $idstring");
            }
        }
    }

    private $_pid;

    private $_pids = [];
}
