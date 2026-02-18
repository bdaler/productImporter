<?php

declare(strict_types=1);

namespace App\Application\Source;

interface ProductSourceInterface
{
    /**
     * @return iterable<ParsedProductDto>
     */
    public function getData(): iterable;
}
