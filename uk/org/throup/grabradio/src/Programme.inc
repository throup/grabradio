<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');
	class Programme {
		public function __construct($pid) {
			$this->_pid = $pid;
		}
		
		/**
		 * @return string
		 */
		public function getPid() {
			return $this->_pid;
		}
		
		public function setMetadata(I_MetadataProvider $metadata) {
			$this->_metadata = $metadata;
		}
		
		public function getMetadata() {
			return $this->_metadata;
		}
		
		public function moveTo($path) {
			echo "\nMoving to $path... ";
			$this->_media->moveTo($path);
			echo "done.\n";
		}
		
		public function obtainMedia() {
			$getter = Factory::getMediaGetter();
			$this->_media = $getter->get($this->getPid());
			$setter = Factory::getMetadataSetter($this->_media);
			$setter->setArtist($this->_metadata->getBrand());
			$setter->setAlbum($this->_metadata->getProgramme());
			$setter->setTitle($this->_metadata->getTitle());
			$setter->setDisc($this->_metadata->getSeries());
			$setter->setTrack($this->_metadata->getEpisode(), $this->_metadata->getTotal());
			$setter->setComment($this->_metadata->getDescription());
			$setter->setYear(date('Y', $this->_metadata->getDate()));
			$setter->write();
		}
		
		private $_pid;
		private $_metadata;
		private $_media;
		
		protected static function _validatePid($pid) {
			if(!preg_match('/^[a-z0-9]{8}$/', $pid)) {
				throw new \Exception("\$pid must be in the form of 8 alphanumeric characters");
			}
		}
	}
}