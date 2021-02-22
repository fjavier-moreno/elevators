<?php

namespace App\Application\Service;

use App\Domain\Elevator\Elevator;
use App\Domain\Schedule\Schedule;
use DateTime;

class TravelerService
{
    protected Buffer $buffer;

    public function __construct(Buffer $buffer)
    {
        $this->buffer = $buffer;
    }

    public function makeTravels(array $elevators, array $schedules, DateTime $time) : array
    {
        $trips = $this->getTrips(count($elevators), $schedules, $time);
        $elevators = $this->moveElevators($trips, $elevators);

        // Execute buffered trips
        while ($this->buffer->count() > 0) {
            $savedSchedules[] = $this->buffer->get();

            if (isset($savedSchedules)) {
                $trips = $this->getTrips(count($elevators), $savedSchedules, $time);
                $elevators = $this->moveElevators($trips, $elevators);
            }
        }

        return $elevators;
    }

    /**
     * Return closest elevator for given origin position based on elevators current position.
     *
     * @param int   $origin
     * @param array $elevators
     *
     * @return Elevator
     */
    private function getClosestElevator(int $origin, array $elevators) : Elevator
    {
        $closest = null;
        /** @var Elevator $elevator */
        foreach ($elevators as $elevator) {
            if ($closest === null ||
                abs($origin - $closest->getFloor()) > abs($elevator->getFloor() - $origin)
            ) {
                $closest = $elevator;
            }
        }

        return $closest;
    }

    /**
     * Update elevator current floor and amount of travels.
     *
     * @param string $id
     * @param int    $start
     * @param int    $destination
     * @param array  $elevators
     *
     * @return array
     */
    private function updateElevatorStats(string $id, int $start, int $destination, array $elevators) : array
    {
        return array_map(
            function (Elevator $elevator) use($id, $start, $destination) : Elevator {
                if ($id === $elevator->getId()) {
                    if ($elevator->getFloor() === $start) {
                        $travel = abs($elevator->getFloor() - $destination);
                    } else {
                        $travel = abs($elevator->getFloor() - $start);
                        $travel = abs($travel - $destination);
                    }

                    $elevator->setFloor($destination)
                        ->addFloorsTraveled($travel);
                }

                return $elevator;
            },
            $elevators
        );
    }

    /**
     * Return trips for given time. If no time is provided will return trips for current time.
     *
     * @param int           $maxOrigins
     * @param array         $schedules
     * @param DateTime|null $time
     *
     * @return array
     */
    private function getTrips(int $maxOrigins, array $schedules, ?DateTime $time = null): array
    {
        $tripsBySchedule = ['from' => [], 'to' => []];

        if (!$time instanceof DateTime) {
            $time = new DateTime();
        }
        // Get trips for given time
        /** @var Schedule $schedule */
        foreach ($schedules as $schedule) {
            if ($schedule->isInTime($time)) {
                // Check if there are more trips than active elevators
                if (($maxOrigins === count($tripsBySchedule['from']) ||
                    count($schedule->getStartFloors()) + count($tripsBySchedule['from']) > $maxOrigins)
                ) {
                    // Transfer excess trips from current schedule to a new schedule for save in buffer
                    $startFloors = $schedule->getStartFloors();
                    $startFloorsToSave = [];
                    while ((count($tripsBySchedule['from']) + count($startFloors)) > $maxOrigins) {
                        $startFloorsToSave[] = array_pop($startFloors);
                    }

                    // Create new schedule to save posposed trip
                    $scheduleToSave = Schedule::create(
                        $schedule->getTimeStart(),
                        $schedule->getTimeEnd(),
                        $schedule->getFrequencyInMinutes(),
                        $startFloorsToSave,
                        $schedule->getEndFloors()
                    );

                    $this->buffer->add($scheduleToSave);

                    // Add remaining trips to current calculation
                    if (1 < count($startFloors)) {
                        // Elevator is called from more than 1 floor at same time
                        $tripsBySchedule['from'] = $startFloors;
                    } elseif (!empty($startFloors)){
                        $tripsBySchedule['from'][] = $startFloors[0];
                    }
                } else {
                    if (1 < count($schedule->getStartFloors())) {
                        // Elevator is called from more than 1 floor at same time
                        $tripsBySchedule['from'] = $schedule->getStartFloors();
                    } else {
                        $tripsBySchedule['from'][] = $schedule->getStartFloors()[0];
                    }
                }

                if (1 < count($schedule->getEndFloors())) {
                    // Multiple destinations at same time
                    $tripsBySchedule['to'] = $schedule->getEndFloors();
                } else {
                    $tripsBySchedule['to'][] = $schedule->getEndFloors()[0];
                }
            }
        }

        // Group overlapping starts & ends
        $tripsBySchedule['from'] = array_unique(array_values($tripsBySchedule['from']));
        if (1 < count($tripsBySchedule['from'])) {
            asort($tripsBySchedule['from']);
        }
        $tripsBySchedule['to'] = array_unique(array_values($tripsBySchedule['to']));
        if (1 < count($tripsBySchedule['to'])) {
            asort($tripsBySchedule['to']);
        }

        return $tripsBySchedule;
    }

    /**
     * @param array $trips
     * @param array $elevators
     *
     * @return array
     */
    private function moveElevators(array $trips, array $elevators): array
    {
        if (1 < count($trips['from'])){
            foreach ($trips['from'] as $start) {
                $elevator = $this->getClosestElevator($start, $elevators);
                // I have assumed that there is only one destination when there is more than one origin since in the example it appears like this
                $elevators = $this->updateElevatorStats($elevator->getId(), (int)$start, (int)$trips['to'][0], $elevators);
            }
        } else {
            $calledFrom = (int)array_pop($trips['from']);
            $elevator = $this->getClosestElevator($calledFrom, $elevators);
            foreach ($trips['to'] as $destination) {
                $elevators = $this->updateElevatorStats($elevator->getId(), $calledFrom, (int)$destination, $elevators);
            }
        }

        return $elevators;
    }
}