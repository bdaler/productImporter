<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Source\ProductSourceInterface;
use App\Domain\Service\ProductImportService;
use App\Domain\ValueObject\ImportResult;

final readonly class ImportProductHandler
{
    public function __construct(private ProductImportService $importService)
    {
    }

    public function handle(ProductSourceInterface $source): ImportResult
    {
        return $this->importService->import(source: $source);
    }
}
