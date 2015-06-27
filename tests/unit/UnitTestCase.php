<?php
declare(strict_types = 1);

namespace Throup\GrabRadio;

use DateTime;
use DateTimeInterface;
use Exception;
use PHPUnit_Framework_TestCase;

abstract class UnitTestCase extends PHPUnit_Framework_TestCase {
    const SAMPLE_STRING  = 'A sample string';
    const SAMPLE_INTEGER = 123;

    /**
     * @param DateTimeInterface $expected
     * @param DateTimeInterface $actual
     */
    protected function assertDateTimeInterfaceMatches(DateTimeInterface $expected, DateTimeInterface $actual) {
        try {
            $this->assertEquals($expected->getTimestamp(), $actual->getTimestamp());
            $this->assertEquals($expected->format(DateTime::RFC3339), $actual->format(DateTime::RFC3339));
        } catch (Exception $e) {
            $message = 'Failed asserting that two instances of DateTimeInterface are equal.';
            $this->assertEquals($expected, $actual, $message);
        }
    }
}
