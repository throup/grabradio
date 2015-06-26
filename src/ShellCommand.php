<?php
namespace Throup\GrabRadio;

abstract class ShellCommand {
    protected function _execute($command) {
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ];
        $process        = proc_open($command, $descriptorspec, $pipes);
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

    protected $_return;

    protected $_stderr;

    protected $_stdout;
}

