<?php

namespace App\Component\Domain\Repository;

use App\Component\Domain\ValueObject\ProductVO;

interface ProductRepositoryInterface
{
    public function save(ProductVO $product): void;

    /**
     * @param array<ProductVO> $products
     */
    public function saveBatch(array $products, int $batchSize = 100): void;
}
