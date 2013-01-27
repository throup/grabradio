<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');
	class Config {
		public static function load($file = '') {
			$files = array(
			               '/etc/grabradio',
			              );
			if (array_key_exists('HOME', $_SERVER)) {
				$files[] = $_SERVER['HOME'].'/.grabradiorc';
			}
			if ($file) {
				$files[] = $file;
			}
			foreach ($files as $file) {
				try {
					@include_once($file);
				} catch (\Exception $e) {
					echo "Problem with config file: $file.\n";
					die($e->getMessage());
				}
			}
		}
		public static function getStations() {
			return self::$_stations;
		}
		public static function setStations($array) {
			self::$_stations = (array) $array;
		}
		public static function ignoreBrands($array) {
			self::$_ignore = array_merge(self::$_ignore, $array);
		}
		private static $_stations = array();
		private static $_ignore   = array();
	}
}
