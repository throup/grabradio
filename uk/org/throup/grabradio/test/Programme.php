<?php
namespace uk\org\throup\grabradio\test {
	require_once(__DIR__ . '/../_config.inc');
	use uk\org\throup                as throup;
	use uk\org\throup\grabradio      as grabradio;
	use uk\org\throup\grabradio\test as test;
	class Programme extends \PHPUnit_Framework_TestCase {
		protected $_i;
		protected function setUp() {
			$this->_i = new grabradio\Programme();
		}
		public function testIdIsInitiallyEmpty() {
			$this->assertEquals('', $this->_i->getId());
		}
		public function testRejectsInteger() {
			try {
				$this->_i->setId(1);
			} catch (\Exception $e) {
				$this->assertEquals('', $this->_i->getId());
				return;
			}
			$this->fail();
		}
		public function testRejectsFloat() {
			try {
				$this->_i->setId(1.1);
			} catch (\Exception $e) {
				$this->assertEquals('', $this->_i->getId());
				return;
			}
			$this->fail();
		}
		public function testRejectsArray() {
			try {
				$this->_i->setId(array());
			} catch (\Exception $e) {
				$this->assertEquals('', $this->_i->getId());
				return;
			}
			$this->fail();
		}
		public function testRejectsObject() {
			try {
				$this->_i->setId(new \stdClass());
			} catch (\Exception $e) {
				$this->assertEquals('', $this->_i->getId());
				return;
			}
			$this->fail();
		}
	}
}
