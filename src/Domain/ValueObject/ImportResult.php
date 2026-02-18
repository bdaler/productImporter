<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Entity\Product;

final class ImportResult
{
    public int $count = 0 {
        get {
            return $this->count;
        }
    }
    public Money $totalSum {
        get {
            return $this->totalSum;
        }
    }
    private array $errors = [];

    public function __construct()
    {
        $this->totalSum = Money::zero();
    }

    public function addProduct(Product $product): void
    {
        $this->count++;
        $this->totalSum = $this->totalSum->add($product->total());
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
