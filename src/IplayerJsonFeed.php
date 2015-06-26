<?php

namespace Throup\GrabRadio;

class IplayerJsonFeed extends IplayerFeed {
    protected function _extractData() {
        $this->extract = json_decode($this->_getContent());
    }

    protected function _getExtractedContent() {
        return $this->extract;
    }

    private $extract;
}
