<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\Simulator\Simulator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class HomeController extends AbstractController
{
    public function homeAction(Request $request, Simulator $simulator)
    {
        if (Request::METHOD_POST === $request->getMethod()) {
            $periodFrom = new DateTime();
            $periodTo = new DateTime();

            $reports = $simulator->simulate(
                $request->request->getInt('elevatorsAmount', 3),
                $request->request->get('schedules', []),
                $periodFrom->setTime(
                    $request->request->getInt('periodFromHour', 9),
                    $request->request->getInt('periodFromMinutes', 0)
                ),
                $periodTo->setTime(
                    $request->request->getInt('periodToHour', 20),
                    $request->request->getInt('periodToMinutes', 0)
                )
            );

            return $this->render(
                'Simulator/results.html.twig',
                [
                    'reports' => $reports,
                    'colspan' => $request->request->getInt('elevatorsAmount', 3)
                ]
            );
        }

        return $this->render('Simulator/home.html.twig');
    }
}