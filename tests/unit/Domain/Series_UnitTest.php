<?php
declare(strict_types = 1);

namespace Throup\GrabRadio\Domain;

use Throup\GrabRadio\Domain;

class Series_UnitTest extends Programme_UnitTest {
    /**
     * @return Series
     */
    protected function newObjectUnderTest(): Domain {
        return new Series();
    }
}
