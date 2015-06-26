<?php
namespace Throup\GrabRadio;

class File {
    public function __construct($fullpath) {
        self::_validatePath($fullpath);
        $this->_constructpath = $fullpath;
        $this->_fullpath      = realpath($fullpath);
    }

    protected static function _validatePath($path) {
        if (!realpath($path)) {
            throw new \Exception("$path is not a valid path");
        }
    }

    public function getDir() {
        return dirname($this->_fullpath);
    }

    public function getFullPath() {
        return $this->_fullpath;
    }

    public function getMimeType() {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $this->_fullpath);
        finfo_close($finfo);
        return $mime;
    }

    public function getName() {
        return basename($this->_fullpath);
    }

    public function moveTo($destination) {
        //			self::_validatePath($destination);
        if (rename($this->_fullpath, $destination)) {
            $realdestination      = realpath($destination);
            $this->_constructpath = $destination;
            $this->_fullpath      = $realdestination;
        } else {
            throw new \Exception("Unable to move {$this->_fullpath} to $destination");
        }
    }
}
