<?php

declare(strict_types=1);

namespace App\Infrastructure\Parser;

use App\Application\Source\ParsedProductDto;
use App\Application\Source\ProductSourceInterface;
use League\Csv\Reader;

final readonly class CsvProductSource implements ProductSourceInterface
{
    public function __construct(private string $filePath)
    {
    }

    public function getData(): iterable
    {
        $csv = Reader::from(filename: $this->filePath);
        $csv->setHeaderOffset(offset: 0);

        foreach ($csv->getRecords() as $row) {
            yield new ParsedProductDto(
                code: $row['code'],
                name: $row['name'],
                price: $row['price'],
                quantity: $row['quantity']
            );
        }
    }
}
