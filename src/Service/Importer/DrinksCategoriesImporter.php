<?php

declare(strict_types=1);

namespace App\Service\Importer;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Log\LoggerInterface;

class DrinksCategoriesImporter
{
    private const API = 'https://www.thecocktaildb.com/api/json/v1/1/list.php?c=list';

    private Client $client;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(Client $client, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function import(): void
    {
        $categories = $this->decode(
            $this->download()
        );

        $this->persist($categories);
    }

    private function decode(string $json): array
    {
        $categories = [];
        try {
            $categories = json_decode($json, true, 512, JSON_THROW_ON_ERROR)['drinks'];
        } catch (JsonException $e) {
            $this->logger->debug("Couldn't parse JSON", ['exception' => $e->getMessage()]);
        }

        return $categories;
    }

    private function download(): string
    {
        $response = '';
        try {
            $response = $this->client->get(self::API);
        } catch (GuzzleException $e) {
            $this->logger->debug("Couldn't load URL", ['exception' => $e->getMessage()]);
        }

        return $response->getBody()->getContents();
    }

    private function persist(array $categories): void
    {
        foreach ($categories as $category) {
            $this->entityManager->persist(
                (new Category())
                    ->setName($category['strCategory'])
            );
        }

        $this->entityManager->flush();
    }
}
