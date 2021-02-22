<?php

namespace App\Infrastructure\Simulator;

use App\Application\Service\TravelerService;
use App\Domain\Elevator\Elevator;
use App\Domain\Schedule\Schedule;
use \DateTime;
use DateInterval;

class Simulator
{
    protected TravelerService $travelerService;

    protected array $report;

    public function __construct(TravelerService $travelerService)
    {
        $this->travelerService = $travelerService;
    }

    /**
     * @param int      $numOfElevators
     * @param array    $schedulesData
     * @param DateTime $periodFrom
     * @param DateTime $periodTo
     *
     * @return array
     */
    public function simulate(int $numOfElevators, array $schedulesData, DateTime $periodFrom, DateTime $periodTo) : array
    {
        $elevators = $this->createElevators($numOfElevators);
        $schedules = $this->createSchedules($schedulesData);

        while ($periodFrom < $periodTo) {
            $elevators = $this->travelerService->makeTravels($elevators, $schedules, $periodFrom);
            $this->addDataToReport($elevators, $periodFrom);
            $periodFrom->add(new DateInterval('PT1M'));
        }

        return $this->report;
    }

    /**
     * Create elevators
     *
     * @param int $amount
     *
     * @return Elevator[]
     */
    private function createElevators(int $amount) : array
    {
        $elevators = [];

        while (0 < $amount) {
            $elevators[] = Elevator::create();
            --$amount;
        }

        return $elevators;
    }

    /**
     * Create schedules
     *
     * @param array $schedulesData
     *
     * @return Schedule[]
     */
    private function createSchedules(array $schedulesData) : array
    {
        $schedules = [];

        /** @var array $schedule */
        foreach ($schedulesData as $schedule) {
            $from = new DateTime();
            $to = new DateTime();
            $schedules[] = Schedule::create(
                $from->setTime((int)$schedule['hourStart'], $schedule['minuteStart']),
                $to->setTime($schedule['hourEnd'], $schedule['minuteEnd']),
                $schedule['frequency'],
                explode(',', $schedule['startFloors']),
                explode(',', $schedule['endFloors'])
            );
        }

        return $schedules;
    }

    /**
     * Add data to final report
     *
     * @param array    $elevators
     * @param DateTime $time
     */
    private function addDataToReport(array $elevators, DateTime $time) : void
    {
        $this->report[$time->format('U')] = array_map(
            static function (Elevator $elevator): array {
                return [
                    'elevator_id' => $elevator->getId(),
                    'current_floor' => $elevator->getFloor(),
                    'total_traveled' => $elevator->getFloorsTraveled()
                ];
            },
            $elevators
        );
    }
}