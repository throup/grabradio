<?php
declare(strict_types = 1);

namespace Throup\GrabRadio;

abstract class Domain_UnitTest extends UnitTestCase {
    /**
     * @test
     */
    public function setAndGetPid_validPid() {
        $domain = $this->newObjectUnderTest();
        $pid    = 'p009y95q';

        $domain->setPid($pid);

        $this->assertEquals($pid, $domain->getPid());
    }

    /**
     * @test
     */
    public function setAndGetPid_validUppercasePid() {
        $domain = $this->newObjectUnderTest();
        $pid    = 'P009Y95Q';

        $domain->setPid($pid);
        $expected = strtolower($pid);

        $this->assertEquals($expected, $domain->getPid());
    }

    /**
     * @test
     */
    public function setAndGetPid_validWorldServicePid() {
        $domain = $this->newObjectUnderTest();
        $pid    = 'wcr5dr3dnl3';

        $domain->setPid($pid);

        $this->assertEquals($pid, $domain->getPid());
    }

    /**
     * @test
     * @expectedException \Throup\GrabRadio\Exception
     */
    public function setAndGetPid_invalidPid_hasVowels() {
        $domain = $this->newObjectUnderTest();
        $pid    = 'pabcdefg';

        $domain->setPid($pid);
    }

    /**
     * @test
     * @expectedException \Throup\GrabRadio\Exception
     */
    public function setAndGetPid_invalidPid_lessThanEightCharacters() {
        $domain = $this->newObjectUnderTest();
        $pid    = 'pbcdfgh';

        $domain->setPid($pid);
    }

    /**
     * @test
     * @expectedException \Throup\GrabRadio\Exception
     */
    public function setAndGetPid_invalidPid_moreThanElevenCharacters() {
        $domain = $this->newObjectUnderTest();
        $pid    = 'pbcdfghjklmn';

        $domain->setPid($pid);
    }

    /**
     * @test
     * @expectedException \Throup\GrabRadio\Exception
     */
    public function setAndGetPid_invalidPid_beginsWithNumber() {
        $domain = $this->newObjectUnderTest();
        $pid    = '1bcdfghj';

        $domain->setPid($pid);
    }

    /**
     * @return Domain
     */
    abstract protected function newObjectUnderTest(): Domain;
}

