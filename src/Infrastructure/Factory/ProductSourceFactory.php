<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Application\Source\ProductSourceInterface;
use App\Infrastructure\Parser\CsvProductSource;
use App\Infrastructure\Parser\XmlProductSource;

final readonly class ProductSourceFactory
{
    public function create(string $path): ProductSourceInterface
    {
        return match (pathinfo(path: $path, flags: PATHINFO_EXTENSION)) {
            'xml' => new XmlProductSource(filePath: $path),
            'csv' => new CsvProductSource(filePath: $path),
            default => throw new \InvalidArgumentException(message: 'Unsupported file type'),
        };
    }
}
