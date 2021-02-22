<?php

namespace App\Application\Service;

use App\Domain\Schedule\Schedule;


class Buffer
{
    public array $schedules;

    public function __construct()
    {
        $this->schedules = [];
    }

    public function add(Schedule $schedule): void
    {
        $this->schedules[] = $schedule;
    }

    public function get(): ?Schedule
    {
        return array_pop($this->schedules);
    }

    public function count(): int
    {
        return count($this->schedules);
    }
}