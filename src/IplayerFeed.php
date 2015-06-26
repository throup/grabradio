<?php

namespace Throup\GrabRadio;

abstract class IplayerFeed {
    public function __construct($url) {
        $this->_setUrl($url);
        $this->_fetchContent();
        $this->_extractData();
    }

    protected function _fetchContent() {
        if (!($this->_content = file_get_contents($this->getUrl()))) {
            throw new \Exception("Failed to get feed content.");
        }
    }

    abstract protected function _extractData();

    public function getUrl() {
        return $this->_url;
    }

    protected function _setUrl($url) {
        self::_validateUrl($url);
        $this->_url = $url;
    }

//
    protected static function _validateUrl($url) {
        if (!is_string($url)) {
            throw new \Exception("\$url must be a string");
        }
    }

    protected function _getContent() {
        return $this->_content;
    }

    protected $_content = '';

    protected $_url     = '';
}
