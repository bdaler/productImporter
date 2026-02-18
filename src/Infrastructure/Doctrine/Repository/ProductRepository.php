<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Infrastructure\Doctrine\Entity\Product;
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

    public function persistAndFlush(Product $product, bool $flush = false): void
    {
        $this->getEntityManager()->persist($product);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array<Product> $products
     */
    public function saveBatch(array $products, int $batchSize = 100): void
    {
        $entityManager = $this->getEntityManager();

        $i = 0;
        foreach ($products as $product) {
            $entityManager->persist(object: $product);
            $i++;

            if ($i % $batchSize === 0) {
                $entityManager->flush();
                $entityManager->clear(); // очищаем UnitOfWork
            }
        }

        $entityManager->flush();
        $entityManager->clear();
    }
}
