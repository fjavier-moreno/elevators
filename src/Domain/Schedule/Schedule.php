<?php

namespace App\Domain\Schedule;

use DateInterval;
use \DateTime;

final class Schedule
{
    public DateTime $timeStart;

    public DateTime $timeEnd;

    public int $frequencyInMinutes;

    public array $startFloors;

    Public array $endFloors;

    Public array $frequencyCalendar;

    private function __construct(
        DateTime $timeStart,
        DateTime $timeEnd,
        int $frequencyInMinutes,
        array $startFloors,
        array $endFloors
    ) {
        $this->timeStart = $timeStart;
        $this->timeEnd = $timeEnd;
        $this->frequencyInMinutes = $frequencyInMinutes;
        $this->startFloors = $startFloors;
        $this->endFloors = $endFloors;
        $this->createCalendar();
    }

    public static function create(
        DateTime $timeStart,
        DateTime $timeEnd,
        int $frequencyInMinutes,
        array $startFloors,
        array $endFloors
    ) : Schedule{
        return new self($timeStart, $timeEnd, $frequencyInMinutes, $startFloors, $endFloors);
    }

    public function getTimeStart(): DateTime
    {
        return $this->timeStart;
    }

    public function getTimeEnd(): DateTime
    {
        return $this->timeEnd;
    }

    public function getFrequencyInMinutes(): int
    {
        return $this->frequencyInMinutes;
    }

    public function getStartFloors(): array
    {
        return $this->startFloors;
    }

    public function getEndFloors(): array
    {
        return $this->endFloors;
    }

    public function getFrequencyCalendar(): array
    {
        return $this->frequencyCalendar;
    }

    public function isInTime(DateTime $time) : bool
    {
        foreach ($this->frequencyCalendar as $executionTime) {
            if ($executionTime == $time) {
                return true;
            }
        }

        return false;
    }

    private function createCalendar(): void
    {
        $from = clone $this->timeStart;

        while ($from <= $this->timeEnd) {
            $this->frequencyCalendar[] = clone $from;
            $from->add(new DateInterval('PT'.$this->frequencyInMinutes.'M'));
        }
    }
}