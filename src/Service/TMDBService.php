<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBService
{
    private HttpClientInterface $client;
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params, HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
        $this->params = $params;
    }

    public function getTrendingMovies()
    {
        try {
            $response = $this->client->request('GET', 'https://api.themoviedb.org/3/trending/movie/week', [
                'query' => [
                    'api_key' => $this->params->get('app.tmdb.id'),
                    'language' => 'fr',
                ],
            ]);
            $content = $response->toArray();

            return $content['results'];
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getSearchedMovies(string $title)
    {
        try {
            $response = $this->client->request('GET', 'https://api.themoviedb.org/3/search/movie', [
                'query' => [
                    'api_key' => $this->params->get('app.tmdb.id'),
                    'language' => 'fr',
                    'query' => $title,
                ],
            ]);

            $content = $response->toArray();

            return $content['results'];
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getMovieById(int $tmdbId)
    {
        try {
            $response = $this->client->request('GET', 'https://api.themoviedb.org/3/movie/'.$tmdbId, [
                'query' => [
                    'api_key' => $this->params->get('app.tmdb.id'),
                    'language' => 'fr',
                ],
            ]);

            $content = $response->toArray();

            return $content;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updateGenreList(): array
    {
        try {
            $response = $this->client->request('GET', 'https://api.themoviedb.org/3/genre/movie/list', [
                'query' => [
                    'api_key' => $this->params->get('app.tmdb.id'),
                    'language' => 'fr',
                ],
            ]);

            $content = $response->toArray();

            return $content;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}