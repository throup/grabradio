<?php
declare(strict_types = 1);

namespace Throup\GrabRadio;

abstract class Domain {
    /**
     * @return string
     */
    public function getPid(): string {
        return (string) $this->pid;
    }

    /**
     * @param string $pid
     */
    public function setPid(string $pid) {
        if (!preg_match('/^[b-df-hj-np-tv-z][b-df-hj-np-tv-z0-9]{7,10}$/i', $pid)) {
            throw new Exception();
        }
        $this->pid = strtolower($pid);
    }

    /**
     * @var string
     */
    private $pid = '';
}
