<?php
namespace Throup\GrabRadio;

use DateTimeInterface;

class Factory {
    public static function getFile($filename) {
        return new File($filename);
    }

    public static function getLibrary() {
        $library = new Library();
        $library->setDirectory(Config::getLibraryPath());
        return $library;
    }

    public static function getMediaGetter() {
        $getter = new GetIplayer();
        $getter->setMode('best');
        $getter->setType('radio');
        $getter->setOutputFilename(self::getTempName());
        return $getter;
    }

    public static function getTempName() {
        return tempnam(sys_get_temp_dir(), '');
    }

    public static function getMetadataSetter(File $media) {
        $setter = new Mp4tags();
        $setter->setMedia($media);
        return $setter;
    }

    public static function getProgramme($pid) {
        $programme = new Programme($pid);
        $metadata  = new IplayerFeedProgramme($pid);
        $programme->setMetadata($metadata);
        return $programme;
    }

    public static function getGenreList($genre, DateTimeInterface $date = null) {
        return new IplayerFeedGenre($genre, $date);
    }
}
