<?php

namespace App\Component\Infrastructure\Parser;

use App\Component\Application\Source\ProductSourceInterface;

final readonly class XmlProductSource implements ProductSourceInterface
{
    public function __construct(private string $filePath)
    {
    }

    /**
     * @inheritDoc
     */
    public function getData(): iterable
    {
        $xml = simplexml_load_string(file_get_contents($this->filePath));

        foreach ($xml->product as $product) {
            yield [
                'code' => (string) $product->code,
                'name' => (string) $product->name,
                'price' => (string) $product->price,
                'quantity' => (string) $product->quantity,
            ];
        }
    }
}
