<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodingService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = 'af9cea647b9e48e0b0c558570343348d'; 
    }

    public function geocodeAddress(string $address)
    {
        $response = $this->client->request('GET', 'https://api.opencagedata.com/geocode/v1/json', [
            'query' => [
                'q' => $address,
                'key' => $this->apiKey,
                'language' => 'fr',
            ]
        ]);

        $data = $response->toArray();
        if (!empty($data['results'])) {
            $coordinates = $data['results'][0]['geometry'];
            return [$coordinates['lat'], $coordinates['lng']];
        }

        return null;
    }
}

