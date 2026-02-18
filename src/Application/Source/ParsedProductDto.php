<?php

declare(strict_types=1);

namespace App\Application\Source;

final readonly class ParsedProductDto
{
    public function __construct(
        public string $code,
        public string $name,
        public string $price,
        public float $quantity,
    ) {
    }
}
