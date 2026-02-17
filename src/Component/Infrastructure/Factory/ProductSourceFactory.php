<?php

namespace App\Component\Infrastructure\Factory;

use App\Component\Application\Source\ProductSourceInterface;
use App\Component\Infrastructure\Parser\CsvProductSource;
use App\Component\Infrastructure\Parser\XmlProductSource;

final readonly class ProductSourceFactory
{
    public function create(string $path): ProductSourceInterface
    {
        return match (pathinfo(path: $path, flags: PATHINFO_EXTENSION)) {
            'xml' => new XmlProductSource(filePath: $path),
            'csv' => new CsvProductSource(filePath: $path),
            default => throw new \InvalidArgumentException(message: "Unsupported file type"),
        };
    }
}
