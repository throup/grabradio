<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');
	class Factory {
		public static function getStationList($station) {
			return new IplayerFeedStation($station);
		}
		
		public static function getLibrary() {
			$library = new Library();
			$library->setDirectory(Config::getLibraryPath());
			return $library;
		}
		
		public static function getProgramme($pid) {
			$programme = new Programme($pid);
			$metadata = new IplayerFeedProgramme($pid);
			$programme->setMetadata($metadata);
			return $programme;
		}
		
		public static function getMediaGetter() {
			$getter = new GetIplayer();
			$getter->setMode('best');
			$getter->setType('radio');
			$getter->setOutputFilename(self::getTempName());
			return $getter;
		}
		
		public static function getMetadataSetter(File $media) {
			$setter = new Mp4tags();
			$setter->setMedia($media);
			return $setter;
		}
		
		public static function getFile($filename) {
			return new File($filename);
		}
		
		public static function getTempName() {
			return tempnam(sys_get_temp_dir(), '');
		}
	}
}