<?php

namespace App\Component\Application\Source;

interface ProductSourceInterface
{
    /**
     * @return iterable<array<string, mixed>>
     */
    public function getData(): iterable;
}
