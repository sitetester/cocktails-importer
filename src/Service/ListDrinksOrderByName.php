<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\DrinksRepository;

class ListDrinksOrderByName
{
    private DrinksRepository $drinksRepository;

    public function __construct(DrinksRepository $drinksRepository)
    {
        $this->drinksRepository = $drinksRepository;
    }

    public function getList(): array
    {
        $list = [];

        $list['headers'] = ['ID', 'NAME', 'ALCOHOLIC', 'THUMBNAIL', 'CATEGORY'];
        $list['rows'] = $this->drinksRepository->getSortedByName();

        return $list;
    }
}