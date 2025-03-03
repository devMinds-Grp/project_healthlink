<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/api/events', name: 'api_events', methods: ['GET'])]
    public function getEvents(): JsonResponse
    {
        $events = [
            [
                'id' => 1,
                'title' => 'Event 1',
                'start' => '2021-10-01T10:00:00',
                'end' => '2021-10-01T12:00:00',
            ],
            [
                'id' => 2,
                'title' => 'Event 2',
                'start' => '2021-10-02T10:00:00',
                'end' => '2021-10-02T12:00:00',
            ],
        ];

        return $this->json($events);
    }
}