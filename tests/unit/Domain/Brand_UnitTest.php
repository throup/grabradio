<?php
declare(strict_types = 1);

namespace Throup\GrabRadio\Domain;

use Throup\GrabRadio\Domain;

class Brand_UnitTest extends Programme_UnitTest {
    /**
     * @return Brand
     */
    protected function newObjectUnderTest(): Domain {
        return new Brand();
    }
}

