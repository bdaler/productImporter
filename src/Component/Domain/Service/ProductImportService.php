<?php

namespace App\Component\Domain\Service;

use App\Component\Application\Source\ProductSourceInterface;
use App\Component\Domain\Repository\ProductRepositoryInterface;
use App\Component\Domain\ValueObject\ImportResult;
use App\Component\Domain\ValueObject\ProductVO;
use Throwable;

final readonly class ProductImportService
{
    public function __construct(private ProductRepositoryInterface $repository)
    {
    }

    public function import(ProductSourceInterface $source): ImportResult
    {
        $batch = [];
        $batchSize = 100;
        $result = ImportResult::create();

        foreach ($source->getData() as $row) {
            try {
                $product = ProductVO::create(
                    code: trim($row['code']),
                    name: trim($row['name']),
                    priceFromCsv: (string)$row['price'],
                    quantity: (float)$row['quantity'],
                );

                $batch[] = $product;
                $result = $result->withProduct(product: $product);

                if (count($batch) >= $batchSize) {
                    $this->repository->saveBatch(products: $batch);
                    $batch = [];
                }
            } catch (Throwable $e) {
                $result = $result->withError(error: sprintf('Error: %s', $e->getMessage()));
            }
        }

        if (count($batch) > 0) {
            $this->repository->saveBatch(products: $batch);
        }

        return $result;
    }
}
