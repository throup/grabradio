<?php
namespace Throup\GrabRadio;

/*
 * ERROR: Failed to get version pid metadata from iplayer site
 * INFO: Streaming completed successfully
 * 
 */
class GetIplayer extends ShellCommand {
    public function __construct($executable = '/usr/bin/get_iplayer') {
        $this->_executable = (string)$executable;
    }

    public function get($pid) {
        $options = "--pid $pid --force";
        foreach ($this->_options as $option => $value) {
            $options .= " --$option $value";
        }
        return $this->_run($options);
    }

    protected function _run($options = '') {
        $tempfile = Factory::getTempName();
        $command  = "{$this->_executable} $options > $tempfile";

        if (!$this->_execute($command)) {
            throw new \Exception("get_iplayer returned error code: {$this->_return}");
        }

        if (!preg_match('/INFO: Streaming completed successfully$/s', $this->_stderr)) {
            throw new \Exception("get_iplayer did not complete stream successfully");
        }

        $command = 'ffmpeg -i '
                   . escapeshellarg($tempfile)
                   . ' -c copy -map 0 -y -f ipod '
                   . escapeshellarg($this->_filename);
        if (!$this->_execute($command)) {
            echo "\n\n";
            echo "ffmpeg output:\n";
            echo $this->_stdout;
            echo "\n\n";
            echo "ffmpeg errors:\n";
            echo $this->_stderr;
            echo "\n\n";
            echo "ffmpeg return value: ", $this->_return, "\n\n";
            throw new \Exception("ffmpeg failed to remux the media file. ($command)");
        }
        chmod($this->_filename, 0644);
        unlink($tempfile);
        return Factory::getFile($this->_filename);
    }

    public function setFormat($format) {
        if (array_key_exists($format, $this->_formats)) {
            $this->_format = $format;
            $this->setRawOutput();
        } else {
            throw new \Exception("Format $format unknown");
        }
    }

    public function setRawOutput() {
        $this->_setOption('raw');
    }

    protected function _setOption($option, $value = '') {
        $option                  = (string)$option;
        $this->_options[$option] = $value;
    }

    public function setMode($mode) {
        $this->_setOption('modes', $mode);
    }

    public function setOutputFilename($filename) {
        if ($filename = (string)$filename) {
            $this->_setOption('stream');
            $this->_filename = $filename;
        } else {
            throw new \Exception("Invalid filename");
        }
    }

    public function setType($type) {
        $this->_setOption('type', $type);
    }

    private $_executable = '';

    private $_filename   = '';

    private $_formats    = [];

    private $_options    = [];
}

