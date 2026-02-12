<?php

namespace App\Component\Infrastructure\Parser;

use App\Component\Application\Source\ProductSourceInterface;
use League\Csv\Reader;

final readonly class CsvProductSource implements ProductSourceInterface
{
    public function __construct(private string $filePath) {}

    public function getData(): iterable
    {
        $csv = Reader::from(filename: $this->filePath);
        $csv->setHeaderOffset(offset: 0);

        foreach ($csv->getRecords() as $row) {
            yield $row;
        }
    }
}
