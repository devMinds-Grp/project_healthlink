<?php
namespace App\Controller;

use App\Service\GeocodingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    private $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    #[Route('/map', name: 'map')]
    public function index(): Response
    {
        // Géolocaliser une adresse
        $coordinates = $this->geocodingService->geocodeAddress('Tour Eiffel, Paris');
        $latitude = $coordinates[0];
        $longitude = $coordinates[1];

        return $this->render('map.html.twig', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
