<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/_config.inc');

$downloaded = file_get_contents("/storage/shared/Radio/downloaded");
$downloaded = explode("\n", $downloaded);
$handle = fopen("/storage/shared/Radio/downloaded", "a");

	$barepids = array();
	foreach (Config::getStations() as $station) {
		echo "Getting feed for $station... ";
		$list = Factory::getStationList($station);
		$barepids = array_merge($barepids, $list->getPids());
		echo "done.\n";
	}
	echo "\n";
	
	$pids = array();
	foreach ($barepids as $pid) {
		if (!in_array($pid, $downloaded)) {
			$pids[] = $pid;
		}
	}

	$success = array();
	$failure = array();

	$library = Factory::getLibrary();
	foreach ($pids as $pid) {
		$programme = null;
		try {
			$programme = Factory::getProgramme($pid);
			if (!Config::toIgnore($programme->getBrand())) {
				echo "Getting $pid... ";
				$programme->obtainMedia();
				echo "done.\n";
				echo "Moving $pid to library... ";
				$library->organiseProgramme($programme);
				echo "done.\n\n";
				$name = "{$programme->getBrand()}:{$programme->getProgramme()}:{$programme->getTitle()}";
				$success[$pid] = $name;
fwrite($handle, $pid . "\n");
			}
		} catch (\Exception $e) {
			if ($programme) {
				$name = "{$programme->getBrand()}:{$programme->getProgramme()}:{$programme->getTitle()}";
			} else {
				$name = $pid;
			}
			$failure[$pid] = $name;
			echo "\n";
		}
	}
fclose($handle);
	
	echo "\n";
	echo "Successfully downloaded:\n";
	foreach ($success as $pid=>$name) {
		echo " * $pid: $name\n";
	}
	echo "\n";
	echo "Failed to download:\n";
	foreach ($failure as $pid) {
		echo " * $pid: $name\n";
	}
	
	exit();
}