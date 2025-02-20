<?php

namespace App\Repository;

use App\Entity\Category;
use App\Enum\GenderEnum;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Returns all categories linked to a group identified by its slug.
     *
     * @param string $groupSlug The slug of the group.
     *
     * @return array
     */
    public function findByGroup(string $groupSlug): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.groups', 'g') // Add the 'groups' association
            ->addSelect('g') // Select the 'groups' association
            ->leftJoin('c.subCategories', 's') // Add the 'subCategories' association
            ->addSelect('s') // Select the 'subCategories' association
            ->where('g.slug = :slug')
            ->setParameter('slug', $groupSlug) 
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Return all products linked to a category (or group or sub-category) and a gender.
     * If the gender is "kids", we include all sub-genders.
     * If the gender is "baby", "boy" or "girl", we include also "kids".
     * 
     * @param string $categorySlug
     * @param GenderEnum $gender
     * @return array 
     */
    public function findProductsByCategoryGroupAndGender(string $categorySlug, GenderEnum $gender): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.products', 'p') // Add the 'products' association
            ->leftJoin('c.groups', 'g') // Add the 'groups' association
            ->leftJoin('c.subCategories', 'sub') // Add the 'subCategories' association
            ->where('c.slug = :categorySlug OR g.slug = :categorySlug OR sub.slug = :categorySlug')
            ->setParameter('categorySlug', $categorySlug)
        ;
        
        // dump($qb->getQuery()->getSQL());

        // Ensure that we retrieve the products
        $qb->andWhere('p IS NOT NULL');

        // Definition of the genders to retrieve depending on the selected category
        $allowedGenders = [$gender->value];
        dump($allowedGenders);

        // If the gender is "kids", we include all sub-genders
        if ($gender === GenderEnum::KIDS) {
            $allowedGenders = [
                GenderEnum::KIDS->value,
                GenderEnum::BABY->value,
                GenderEnum::BOY->value,
                GenderEnum::GIRL->value
            ];
        }
        // If the gender is "baby", "boy" or "girl", we include also "kids"
        elseif ($gender === GenderEnum::BABY || $gender === GenderEnum::BOY || $gender === GenderEnum::GIRL) {
            $allowedGenders[] = GenderEnum::KIDS->value;
        }

        // Apply the filter for genders with an IN ()
        $qb->andWhere('p.gender IN (:genders)')
            ->setParameter('genders', $allowedGenders);

        // dump([
        //     'categorySlug' => $categorySlug,
        //     'URL_gender' => $gender->value,
        //     'allowedGenders' => $allowedGenders
        // ]);

        // dump($qb->getQuery()->getSQL());

        return $qb->getQuery()->getResult();
    }
}
