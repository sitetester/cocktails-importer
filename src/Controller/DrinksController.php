<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DrinksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/drinks", name="drinks")
 */
class DrinksController extends AbstractController
{
    private DrinksRepository $drinksRepository;

    public function __construct(DrinksRepository $drinksRepository)
    {
        $this->drinksRepository = $drinksRepository;
    }

    /**
     * Name of this route is `drinks_index` (php bin/console debug:router)
     * @Route("/", name="_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'drinks/index.html.twig',
            [
                'drinks' => $this->drinksRepository->getSortedByName()
            ]
        );
    }
}