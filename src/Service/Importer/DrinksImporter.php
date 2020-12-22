<?php

declare(strict_types=1);

namespace App\Service\Importer;

use App\Entity\Category;
use App\Entity\Drinks;
use App\Repository\CategoryRepository;
use App\Service\Provider\DrinksByCategoryProvider;
use App\Service\Provider\DrinksByIdProvider;
use Doctrine\ORM\EntityManagerInterface;

class DrinksImporter
{
    private DrinksCategoriesImporter $categoriesImporter;
    private DrinksByCategoryProvider $drinksByCategoryProvider;
    private DrinksByIdProvider $drinksByIdProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(
        DrinksCategoriesImporter $categoriesImporter,
        DrinksByCategoryProvider $drinksByCategoryProvider,
        DrinksByIdProvider $drinksByIdProvider,
        EntityManagerInterface $entityManager
    ) {
        $this->categoriesImporter = $categoriesImporter;
        $this->drinksByCategoryProvider = $drinksByCategoryProvider;
        $this->drinksByIdProvider = $drinksByIdProvider;
        $this->entityManager = $entityManager;
    }

    public function import(): bool
    {
        $this->categoriesImporter->import();
        $drinksByCategories = $this->drinksByCategoryProvider->provide();

        $ids = [];
        foreach ($drinksByCategories as $categoryId => $drinks) {
            /** @var CategoryRepository $categoryRepo */
            $categoryRepo = $this->entityManager->getRepository(Category::class);
            $category = $categoryRepo->find($categoryId);

            foreach ($drinks as $drink) {
                $ids[] = $drink['idDrink'];
            }

            $this->persistDrinks(
                $this->drinksByIdProvider->provide($ids),
                $category
            );
        }

        return true;
    }

    /**
     * @param array $parsedDrinks
     * @param Category $category
     */
    private function persistDrinks(array $parsedDrinks, Category $category): void
    {
        foreach ($parsedDrinks as $drink) {
            $this->entityManager->persist(
                (new Drinks())
                    ->setDrinkId((int)$drink['idDrink'])
                    ->setName($drink['strDrink'])
                    ->setCategory($category)
                    ->setAlcoholic($drink['strAlcoholic'])
                    ->setGlass($drink['strGlass'])
                    ->setInstructions($drink['strInstructions'])
                    ->setThumbnail($drink['strDrinkThumb'] ?? '')
            );
        }

        $this->entityManager->flush();
    }
}
