<?php

namespace App\Component\Application;

use App\Component\Application\Source\ProductSourceInterface;
use App\Component\Domain\Service\ProductImportService;
use App\Component\Domain\ValueObject\ImportResult;

final readonly class ImportProductHandler
{
    public function __construct(private ProductImportService $importService) {}

    public function handle(ProductSourceInterface $source): ImportResult
    {
        return $this->importService->import(source: $source);
    }
}
