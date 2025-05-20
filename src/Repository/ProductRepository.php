<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
//     /**
//      * Find all producs having a value above the specified one.
//      *
//      * @return Product[] Returns an array of Product objects
//      */
//     public function findByMinimumValue(int $value): array
//     {
//         /** @var Product[] $res */
//         $res = $this->createQueryBuilder('p')
//             ->andWhere('p.value >= :value')
//             ->setParameter('value', $value)
//             ->orderBy('p.value', 'ASC')
//             ->getQuery()
//             ->getResult()
//         ;

//         return $res;
//     }

//     /**
//      * Same as above, but using SQL:
//      * Find all producs having a value above the specified one.
//      *
//      * @return list<array<string, mixed>> Returns an array of arrays (i.e. a raw data set)
//      */
//     public function findByMinimumValue2(int $value): array
//     {
//         $conn = $this->getEntityManager()->getConnection();

//         $sql = '
//             SELECT * FROM product AS p
//             WHERE p.value >= :value
//             ORDER BY p.value ASC
//         ';

//         $resultSet = $conn->executeQuery($sql, ['value' => $value]);

//         return $resultSet->fetchAllAssociative();
//     }
}
