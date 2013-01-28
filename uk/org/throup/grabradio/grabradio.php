<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/_config.inc');
	
	$pids = array();
	foreach (Config::getStations() as $station) {
		echo "Getting feed for $station... ";
		$list = Factory::getStationList($station);
		$pids = array_merge($pids, $list->getPids());
		echo "done.\n";
	}
	echo "\n";

	$success = array();
	$failure = array();

	$library = Factory::getLibrary();
	foreach ($pids as $pid) {
		try {
			$programme = Factory::getProgramme($pid);
			echo "Getting $pid... ";
			$programme->obtainMedia();
			echo "done.\n";
			echo "Moving $pid to library... ";
			$library->organiseProgramme($programme);
			echo "done.\n\n";
			$success[] = $pid;
		} catch (\Exception $e) {
			$failure[] = $pid;
			echo "\n";
		}
	}
	
	echo "\n";
	echo "Successfully downloaded:\n";
	foreach ($success as $pid) {
		echo " * $pid\n";
	}
	echo "\n";
	echo "Failed to download:\n";
	foreach ($failure as $pid) {
		echo " * $pid\n";
	}
	
	exit();
}