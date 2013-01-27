<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');

	class Library {
		public function organiseProgramme(Programme $programme) {
			$metadata  = $programme->getMetadata();
			
			$brand     = $metadata->getBrand();
			$prog      = $metadata->getProgramme();
			$title     = $metadata->getTitle();
			$year      = date('Y', $metadata->getDate());
			$episode   = $metadata->getEpisode();
			$series    = $metadata->getSeries();
			$initial   = substr($brand, 0 , 1);

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
			if ($series||$episode) {
				if ($series) {
					$filename .= $series;
				}
				if ($episode) {
					$filename .= sprintf('%02d', $episode);
				}
				$filename .= ' ';
			}
			$filename .= "$title.m4a";
			
			$programme->moveTo($filename);
		}
		
		private $_dir = '/storage/shared/Radio test';
	}
}