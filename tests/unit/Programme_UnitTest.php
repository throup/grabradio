<?php
namespace Throup\GrabRadio;

use PHPUnit_Framework_TestCase;

class Programme_UnitTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function instantiate() {
        new Programme('');
    }
}
