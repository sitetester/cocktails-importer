<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Drinks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Drinks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drinks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drinks[]    findAll()
 * @method Drinks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrinksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drinks::class);
    }

    public function getSortedByName(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.id', 'd.name', 'd.alcoholic', 'd.thumbnail', 'c.name as categoryName')
            ->innerJoin('d.category', 'c')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
