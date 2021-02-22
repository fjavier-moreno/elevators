<?php

namespace App\Domain\Elevator;

final class Elevator
{
    protected string $id;

    public int $floor;

    public int $floorsTraveled;

    private function __construct(int $floor, int $floorsTraveled)
    {
        $this->id = uniqid('Elevator_');
        $this->floor = $floor;
        $this->floorsTraveled = $floorsTraveled;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public static function create() : Elevator
    {
        return new self(0, 0);
    }

    public function getFloor(): int
    {
        return $this->floor;
    }

    public function setFloor(int $floor) : Elevator
    {
        $this->floor = $floor;

        return $this;
    }

    public function getFloorsTraveled(): int
    {
        return $this->floorsTraveled;
    }

    public function addFloorsTraveled(int $floors): void
    {
        $this->floorsTraveled += $floors;
    }
}