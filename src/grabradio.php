<?php
namespace Throup\GrabRadio;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeZone;

require_once(__DIR__ . '/_config.php');

$downloaded = @file_get_contents("/storage/shared/Radio/downloaded");
$downloaded = explode("\n", $downloaded);
$handle     = @fopen("/storage/shared/Radio/downloaded", "a");

$barepids = [];
$today = new DateTimeImmutable('00:00:00Z', new DateTimeZone('UTC'));
$dates = new DatePeriod(
    $today->sub(new DateInterval('P7D')),
    new DateInterval('P1D'),
    $today
);
foreach (Config::getGenres() as $genre) {
    /** @var DateTimeImmutable $date */
    foreach ($dates as $date) {
        echo "Getting feed for $genre on {$date->format('Y-m-d')}... ";
        $list     = Factory::getGenreList($genre, $date);
        $barepids = array_merge($barepids, $list->getPids());
        echo "done.\n";
    }
}
echo "\n";

$pids = [];
foreach ($barepids as $pid) {
    if (!in_array($pid, $downloaded)) {
        $pids[] = $pid;
    }
}

$success = [];
$failure = [];

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
            $name          = "{$programme->getBrand()}:{$programme->getProgramme()}:{$programme->getTitle()}";
            echo $name, "\n";
            $success[$pid] = $name;
            fwrite($handle, $pid . "\n");
            exit();
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
foreach ($success as $pid => $name) {
    echo " * $pid: $name\n";
}
echo "\n";
echo "Failed to download:\n";
foreach ($failure as $pid) {
    echo " * $pid: $name\n";
}

exit();
