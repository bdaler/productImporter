<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    /**
     * @param array<Product> $products
     */
    public function saveBatch(array $products, int $batchSize = 100): void;
}
