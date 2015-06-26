<?php
namespace Throup\GrabRadio;

class Library {
    public function organiseProgramme(Programme $programme) {
        $metadata  = $programme->getMetadata();
        $extension = 'm4a';

        $brand   = self::tidyName($metadata->getBrand());
        $prog    = self::tidyName($metadata->getProgramme());
        $title   = self::tidyName($metadata->getTitle());
        $year    = date('Y', $metadata->getDate());
        $episode = $metadata->getEpisode();
        $series  = $metadata->getSeries();
        $initial = substr($brand, 0, 1);

        $filename = "{$this->_dir}/$initial/";
        if (!realpath($filename)) {
            mkdir($filename);
        }
        if ($brand != $prog) {
            $filename .= "$brand/";
            if (!realpath($filename)) {
                mkdir($filename);
            }
        }
        if ($year) {
            $filename .= "$year ";
        }
        if ($prog != $title) {
            $filename .= "$prog/";
            if (!realpath($filename)) {
                mkdir($filename);
            }
        }
        if ($series || $episode) {
            if ($series) {
                $filename .= $series;
            }
            if ($episode) {
                $filename .= sprintf('%02d', $episode);
            }
            $filename .= ' ';
        }
        $filename .= "$title";

        $count    = 0;
        $testname = $filename;
        while (file_exists("$testname.$extension")) {
            $count++;
            $testname = "$filename-$count";
        }
        $filename = $testname;
        $programme->moveTo("$filename.$extension");
    }

    public static function tidyName($name) {
        $name = ucwords(preg_replace('/\//', '_', $name));
        if ('The ' == substr($name, 0, 4)) {
            $name = substr($name, 4) . ', The';
        }
        return $name;
    }

    public function setDirectory($dir) {
        $this->_dir = $dir;
    }

    private $_dir = '';
}
