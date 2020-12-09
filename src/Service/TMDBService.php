<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBService
{
    private $client;
    private $params;

    public function __construct(ParameterBagInterface $params, HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
        $this->params = $params;
    }

    public function getTrendingMovies()
    {
        // TODO: implement try catch

        $response = $this->client->request('GET', 'https://api.themoviedb.org/3/trending/movie/week', [
            'query' => [
                'api_key' => $this->params->get('app.tmdb.id'),
            ],
        ]);

        $content = $response->toArray();

        return $content['results'];
    }
}