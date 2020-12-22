<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class DrinksByCategoryProvider
{
    private const API = 'https://www.thecocktaildb.com/api/json/v1/1/filter.php';

    private Client $client;
    private LoggerInterface $logger;
    private CategoryRepository $categoryRepository;

    public function __construct(Client $client, LoggerInterface $logger, CategoryRepository $categoryRepository)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->categoryRepository = $categoryRepository;
    }

    public function provide(): array
    {
        $responses = $this->bulkDownloadByCategories($this->categoryRepository->findAll());

        $drinks = [];
        foreach ($responses as $categoryId => $response) {
            if ($response->getBody() !== null) {
                $json = $response->getBody()->getContents();
                try {
                    $drinks[$categoryId] = json_decode($json, true, 512, JSON_THROW_ON_ERROR)['drinks'];
                } catch (JsonException $e) {
                    $this->logger->debug("Couldn't parse JSON", ['exception' => $e->getMessage()]);
                }
            }
        }

        return $drinks;
    }

    /**
     * Order is maintained
     *
     * @param Category[] $categories
     * @return ResponseInterface[]
     */
    private function bulkDownloadByCategories(array $categories): array
    {
        $promises = [];
        foreach ($categories as $category) {
            $promises [$category->getId()] = $this->client->getAsync(self::API . '?c=' . $category->getName());
        }

        // Wait for the requests to complete; throws a ConnectException, if any of the requests fail
        $responses = '';
        try {
            $responses = Promise\Utils::unwrap($promises);
        } catch (Throwable $e) {
            $this->logger->debug("Couldn't load all promises", ['exception' => $e->getMessage()]);
        }

        return $responses;
    }
}