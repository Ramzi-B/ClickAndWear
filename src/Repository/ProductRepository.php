<?php

namespace App\Repository;

use App\Entity\Product;
use App\Enum\GenderEnum;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Returns all products that are for women.
     *
     * @return Collection<int, Product>
     */
    public function findAllWomenProduct()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.slug = :femme')
            // ->Where('c.groups', 'g')
            ->where('p.gender = :femme')
            ->setParameter('femme', 'femme')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns all products that are for men.
     *
     * @return Collection<int, Product>
     */
    public function findAllMenProduct()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.slug = :homme')
            ->where('p.gender = :homme')
            ->setParameter('homme', 'homme')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns all products categorized for children,
     * including specific genders such as 'enfant', 'bebe', 'garcon', and 'fille'.
     *
     * @return Collection<int, Product> A collection of products for children.
     */
    public function findAllChildrenProduct()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.slug = :enfant')
            ->where('p.gender = :enfant OR p.gender = :bebe OR p.gender = :garcon OR p.gender = :fille')
            ->setParameter('bebe', 'bebe')
            ->setParameter('garcon', 'garcon')
            ->setParameter('fille', 'fille')
            ->setParameter('enfant', 'enfant')
            ->getQuery()
            ->getResult()
        ;
    }
}
