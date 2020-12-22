<?php

declare(strict_types=1);

namespace App\Service\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class DrinksByIdProvider
{
    private const API = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php';
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function provide(array $ids): array
    {
        $drinks = [];

        foreach ($this->bulkDownloadIds($ids) as $response) {
            if ($response->getBody() !== null) {
                $json = $response->getBody()->getContents();
                try {
                    $drinks[] = json_decode($json, true, 512, JSON_THROW_ON_ERROR)['drinks'][0];
                } catch (JsonException $e) {
                    $this->logger->debug("Couldn't parse JSON", ['exception' => $e->getMessage()]);
                }
            }
        }

        return $drinks;
    }

    /**
     * @param array $ids
     * @return ResponseInterface[]
     */
    private function bulkDownloadIds(array $ids): array
    {
        $promises = [];
        foreach ($ids as $id) {
            $promises [] = $this->client->getAsync(self::API . '?i=' . $id);
        }

        // Wait for the requests to complete; throws a ConnectException, if any of the requests fail
        $responses = [];
        try {
            $responses = Promise\Utils::unwrap($promises);
        } catch (Throwable $e) {
            $this->logger->debug("Couldn't load all promises");
        }

        return $responses;
    }
}