<?php
declare(strict_types = 1);

namespace Throup\GrabRadio\Domain;

use DateTime;
use DateTimeImmutable;
use Throup\GrabRadio\Domain;

/**
 * @method Programme newObjectUnderTest()
 */
abstract class Programme_UnitTest extends \Throup\GrabRadio\Domain_UnitTest {
    /**
     * @test
     */
    public function setAndGetTitle() {
        $programme = $this->newObjectUnderTest();
        $title     = self::SAMPLE_STRING;

        $programme->setTitle($title);

        $this->assertEquals($title, $programme->getTitle());
    }

    /**
     * @test
     */
    public function setAndGetDescription() {
        $programme   = $this->newObjectUnderTest();
        $description = self::SAMPLE_STRING;

        $programme->setDescription($description);

        $this->assertEquals($description, $programme->getDescription());
    }

    /**
     * @test
     */
    public function setAndGetPosition() {
        $programme = $this->newObjectUnderTest();
        $position  = self::SAMPLE_INTEGER;

        $programme->setPosition($position);

        $this->assertEquals($position, $programme->getPosition());
    }

    /**
     * @test
     */
    public function setAndGetBroadcast_defaultDateTimeImmutable() {
        $programme = $this->newObjectUnderTest();
        $broadcast = new DateTimeImmutable();

        $programme->setBroadcast($broadcast);

        $this->assertDateTimeInterfaceMatches($broadcast, $programme->getBroadcast());
    }

    /**
     * @test
     */
    public function setAndGetBroadcast_specificDateTimeImmutable() {
        $programme = $this->newObjectUnderTest();
        $broadcast = new DateTimeImmutable('2015-04-23T11:23:56+23:00');

        $programme->setBroadcast($broadcast);

        $this->assertDateTimeInterfaceMatches($broadcast, $programme->getBroadcast());
    }

    /**
     * @test
     */
    public function setAndGetBroadcast_defaultDateTime() {
        $programme = $this->newObjectUnderTest();
        $broadcast = new DateTime();

        $programme->setBroadcast($broadcast);

        $this->assertDateTimeInterfaceMatches($broadcast, $programme->getBroadcast());
    }

    /**
     * @test
     */
    public function setAndGetBroadcast_specificDateTime() {
        $programme = $this->newObjectUnderTest();
        $broadcast = new DateTime('2015-04-23T11:23:56+23:00');

        $programme->setBroadcast($broadcast);

        $this->assertDateTimeInterfaceMatches($broadcast, $programme->getBroadcast());
    }

    /**
     * @test
     */
    public function setAndGetBroadcast_withDateTime_notModifiableOutsideObject() {
        $programme = $this->newObjectUnderTest();
        $broadcast = new DateTime('2015-04-23T11:23:56+23:00');
        $original  = clone $broadcast;

        $programme->setBroadcast($broadcast);
        $broadcast->setTime(01, 02, 03);

        $this->assertDateTimeInterfaceMatches($original, $programme->getBroadcast());
    }
}
