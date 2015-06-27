<?php
declare(strict_types = 1);

namespace Throup\GrabRadio\Domain;

use Throup\GrabRadio\Domain;

class Episode_UnitTest extends Programme_UnitTest {
    /**
     * @return Episode
     */
    protected function newObjectUnderTest(): Domain {
        return new Episode();
    }
}

