<?php

declare(strict_types=1);

namespace App\Infrastructure\Parser;

use App\Application\Source\ParsedProductDto;
use App\Application\Source\ProductSourceInterface;

final readonly class XmlProductSource implements ProductSourceInterface
{
    public function __construct(private string $filePath)
    {
    }

    public function getData(): iterable
    {
        $xml = simplexml_load_string(file_get_contents($this->filePath));

        foreach ($xml->product as $product) {
            yield new ParsedProductDto(
                code: $product->code,
                name: (string)$product->name,
                price: (string)$product->price,
                quantity: (float)$product->quantity,
            );
        }
    }
}
