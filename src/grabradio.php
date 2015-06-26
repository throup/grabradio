<?php
namespace Throup\GrabRadio;

require_once(__DIR__ . '/_config.php');

$downloaded = '';// file_get_contents("/storage/shared/Radio/downloaded");
$downloaded = explode("\n", $downloaded);
//$handle     = fopen("/storage/shared/Radio/downloaded", "a");

$barepids = [];
foreach (Config::getGenres() as $genre) {
    echo "Getting feed for $genre... ";
    $list     = Factory::getGenreList($genre);
    $barepids = array_merge($barepids, $list->getPids());
    echo "done.\n";
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
  //          $programme->obtainMedia();
            echo "done.\n";
            echo "Moving $pid to library... ";
 //           $library->organiseProgramme($programme);
            echo "done.\n\n";
            $name          = "{$programme->getBrand()}:{$programme->getProgramme()}:{$programme->getTitle()}";
            echo $name, "\n";
            $success[$pid] = $name;
//            fwrite($handle, $pid . "\n");
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
