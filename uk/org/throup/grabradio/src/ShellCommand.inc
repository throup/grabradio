<?php
namespace uk\org\throup\grabradio {
	require_once(__DIR__ . '/../_config.inc');
	abstract class ShellCommand {
		protected $_stdout;
		protected $_stderr;
		protected $_return;
		protected function _execute($command) {
			$descriptorspec = array(
				0 => array("pipe", "r"),
				1 => array("pipe", "w"),
				2 => array("pipe", "w")
			);
			$process = proc_open($command, $descriptorspec, $pipes);
			if (is_resource($process)) {
			    fclose($pipes[0]);
			    $this->_stdout = stream_get_contents($pipes[1]);
			    fclose($pipes[1]);
			    $this->_stderr = stream_get_contents($pipes[2]);
			    fclose($pipes[2]);
			    $this->_return = proc_close($process);
			} else {
				throw new \Exception('Cannot run: $command');
			}
			return !($this->_return);
		}
	}
}
