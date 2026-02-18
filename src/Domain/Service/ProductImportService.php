<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Application\Source\ProductSourceInterface;
use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\ImportResult;

final readonly class ProductImportService
{
    public function __construct(private ProductRepositoryInterface $repository)
    {
    }

    public function import(ProductSourceInterface $source): ImportResult
    {
        $batch = [];
        $batchSize = 100;
        $result = new ImportResult();

        foreach ($source->getData() as $row) {
            try {
                $product = Product::create(
                    code: trim((string)$row->code),
                    name: trim($row->name),
                    priceFromCsv: $row->price,
                    quantity: $row->quantity,
                );

                $batch[] = $product;
                $result->addProduct(product: $product);

                if (count($batch) >= $batchSize) {
                    $this->repository->saveBatch(products: $batch);
                    $batch = [];
                }
            } catch (\Throwable $e) {
                $result->addError(error: sprintf('Error: %s', $e->getMessage()));
            }
        }

        if (count($batch) > 0) {
            $this->repository->saveBatch(products: $batch);
        }

        return $result;
    }
}
