<?php
namespace Throup\GrabRadio;

class IplayerFeedProgramme extends IplayerFeed implements I_MetadataProvider {
    public function __construct($pid) {
        self::_validatePid($pid);
        $this->_pid = $pid;
        $url        = "http://www.bbc.co.uk/programmes/$pid";
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
        $feed = $this->_getXml();
        $feed->registerXPathNamespace('xhtml', 'http://www.w3.org/1999/xhtml');

        $parts       = [];
        $series      = 0;
        $episode     = 0;
        $total       = 0;
        $date        = '';
        $description = '';

        if ($feed->xpath('//xhtml:a[@typeof="po:Brand"]')) {
            if ($grab = $feed->xpath('//xhtml:a[@typeof="po:Brand"]/xhtml:span[@property="dc:title"]')) {
                $parts[] = (string)$grab[0];
            }
        } elseif ($feed->xpath('//xhtml:a[@typeof="po:Series"]')) {
            if ($grab = $feed->xpath('//xhtml:a[@typeof="po:Series"]/xhtml:span[@property="dc:title"]')) {
                $parts[] = (string)$grab[0];
            }
        } elseif ($feed->xpath('//xhtml:a[@typeof="po:Episode"]')) {
            if ($grab = $feed->xpath('//xhtml:a[@typeof="po:Episode"]/xhtml:span[@property="dc:title"]')) {
                $parts[] = (string)$grab[0];
            }
        }

        if ($feed->xpath('//xhtml:div[@class="episode-details"]')) {
            if ($feed->xpath('//xhtml:div[@class="episode-details"]/xhtml:div[@id="context"]')) {
                if ($feed->xpath('//xhtml:div[@class="episode-details"]/xhtml:div[@id="context"]/xhtml:p/xhtml:span[@class="parents"]')) {
                    foreach ($feed->xpath('//xhtml:div[@class="episode-details"]/xhtml:div[@id="context"]/xhtml:p/xhtml:span[@class="parents"]/xhtml:span[@class="programme"]/xhtml:a')
                             as $parent) {
                        $parts[] = (string)$parent;
                    }
                }
            }
            if ($grab = $feed->xpath('//xhtml:div[@class="episode-details"]/xhtml:*[@class="episode-title"]')) {
                $parts[] = (string)$grab[0];
            }
            if ($grab
                = $feed->xpath('//xhtml:div[@class="episode-details"]/xhtml:div[@id="synopsis"]/xhtml:div[@property="dc:description"]')
            ) {
                $description = $grab[0]->asXML();
                $description = preg_replace('/<p[^>]*>(.*?)<\/p>/s', "\\1\n", $description);
                $description = preg_replace('/<br[^>]*>/', "\n", $description);
                $description = preg_replace('/<[^>]*>/', '', $description);
            }

            if ($grab
                = $feed->xpath('//xhtml:div[@class="episode-details"]/xhtml:div[@id="episode-summary"]/xhtml:dl[@class="episode-summary--list-item"]')
            ) {
                foreach ($grab as $dl) {
                    $dl->registerXPathNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
                    if (trim($dl->dt) == 'First broadcast:') {
                        $date = trim($dl->dd);
                    }
                }
            }
        }

        if ($grab = $feed->xpath('//xhtml:*[@class="position"]')) {
            $position = (string)$grab[0];
            $matches  = [];
            if (preg_match('/^Episode (\d+) of (\d+)$/', $position, $matches)) {
                $episode = $matches[1];
                $total   = $matches[2];
            } elseif (preg_match('/^Episode (\d+)$/', $position, $matches)) {
                $episode = $matches[1];
            }
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

    protected function _spoutXml() {
        $feed = $this->_getXml();
        header("Content-type: application/xml");
        echo preg_replace('#http://www.w3.org/1999/xhtml#', 'http://www.w3.org/1999/xhtml/nodisplay', $feed->asXML());
        exit();
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
