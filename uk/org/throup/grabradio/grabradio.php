<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/_config.inc');
	
#	$feed = new IplayerFeed('http://www.bbc.co.uk/iplayer/playlist/b00j1dvv');
#	$feed = new IplayerFeedEpisode('b01q02sg');
/*
$pids = array(
              'b01dq51g', // 15 Minute Drama: Craven: Series 2: Episode 4 of 5
              'b00wmznd', // Project Archangel: Episode 4 of 4
              'b01q02sg', // Farming Today: 25/01/2013
              'b01pzqpv', // Outsourced
              'b01q030g', // Woman's Hour
              'b01pzv5w', // Front Row
              'b01q03qq', // The Archers
             );
*/
/*
	$pids = array();
	foreach (Config::getStations() as $station) {
		echo "Getting feed for $station... ";
		$list = Factory::getStationList($station);
		$pids = array_merge($pids, $list->getPids());
		echo "done.\n";
	}
	echo "\n";
*/
$pids = array('b01q19v0');
	$library = Factory::getLibrary();
	foreach ($pids as $pid) {
		$programme = Factory::getProgramme($pid);
		echo "Getting $pid... ";
		$programme->obtainMedia();
		echo "done.\n";
		echo "Moving $pid to library... ";
		$library->organiseProgramme($programme);
		echo "done.\n\n";
	}
	
	exit();
	
	function output($string) {
		$string = preg_replace('/"/', '""', $string);
		echo "\"$string\"";
	}
}