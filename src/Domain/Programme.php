<?php
declare(strict_types = 1);

namespace Throup\GrabRadio\Domain;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Throup\GrabRadio\Domain;

abstract class Programme extends Domain {
    /**
     * @return DateTimeImmutable
     */
    public function getBroadcast(): DateTimeImmutable {
        return $this->broadcast;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return (string) $this->description;
    }

    /**
     * @return int
     */
    public function getPosition(): int {
        return (int) $this->position;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return (string) $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = (string) $title;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position) {
        $this->position = (int) $position;
    }

    /**
     * @param DateTimeImmutable $broadcast
     */
    public function setBroadcast(DateTimeInterface $broadcast) {
        $this->broadcast = new DateTimeImmutable(
            $broadcast->format(DateTime::RFC3339),
            $broadcast->getTimezone()
        );
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description) {
        $this->description = (string) $description;
    }

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var  DateTimeImmutable
     */
    private $broadcast;

    /**
     * @var string
     */
    private $description = '';
}

