<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');

	class Mp4tags extends ShellCommand {
		public function __construct($executable = '/usr/bin/mp4tags') {
			$this->_executable = (string) $executable;
		}

		public function write() {
			$options = "";
			foreach ($this->_options as $option=>$value) {
				$options .= " -$option " . escapeshellarg($value);
			}
			return $this->_run($options);
		}

		public function setMedia(File $media) {
			$this->_media = $media;
		}
		
		public function setArtist($artist) {
			if ($artist = (string) $artist) {
				$this->_setOption('artist', $artist);
			}
		}

		public function setAlbum($album) {
			if ($album = (string) $album) {
				$this->_setOption('album', $album);
			}
		}

		public function setComment($comment) {
			if ($comment = (string) $comment) {
				$this->_setOption('comment', $comment);
			}
		}

		public function setGenre($genre) {
			if ($genre = (string) $genre) {
				$this->_setOption('genre', $genre);
			}
		}
		
		public function setTitle($title) {
			if ($title = (string) $title) {
				$this->_setOption('song', $title);
			}
		}

		public function setComposer($composer) {
			if ($composer = (string) $composer) {
				$this->_setOption('writer', $composer);
			}
		}

		public function setYear($year) {
			if ($year = (int) $year) {
				$this->_setOption('year', $year);
			}
		}

		public function setTrack($track, $total = 0) {
			if ($track = (int) $track) {
				$this->_setOption('track', $track);
				if ($total = (int) $total) {
					$this->_setOption('tracks', $total);
				}
			}
		}

		public function setDisc($disc, $total = 0) {
			if ($disc = (int) $disc) {
				$this->_setOption('disk', $disc);
				if ($total = (int) $total) {
					$this->_setOption('disks', $total);
				}
			}
		}

		public function setPicture(File $picture) {
			$this->_setOption('picture', $picture->getFullPath());
		}

		protected function _run($options = '') {
			$command = "{$this->_executable} $options " . escapeshellarg($this->_media->getFullPath());

			if (!$this->_execute($command)) {
				echo "\n\nmp4tags command:\n";
				echo $command;
				echo "\n\nmp4tags stdout:\n";
				echo $this->_stdout;
				echo "\n\nmp4tags stderr:\n";
				echo $this->_stderr;
				echo "\n\n";
				throw new \Exception("mp4tags returned error code: {$this->_return}");
			}
		}
		
		protected function _setOption($option, $value = '') {
			$option = (string) $option;
			$this->_options[$option] = $value;
		}
		
		private $_options    = array();

		private $_executable = '';
	}
}
