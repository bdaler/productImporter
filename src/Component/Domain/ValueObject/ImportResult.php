<?php

namespace App\Component\Domain\ValueObject;

final readonly class ImportResult
{
    private function __construct(
        private int $count,
        private Money $totalSum,
        private array $errors
    ) {
    }

    public static function create(): self
    {
        return new self(count: 0, totalSum: Money::zero(), errors: []);
    }

    public function withProduct(ProductVO $product): self
    {
        return new self(
            count: $this->count + 1,
            totalSum: $this->totalSum->add(other: $product->total()),
            errors: $this->errors
        );
    }

    public function withError(string $error): self
    {
        return new self(
            count: $this->count,
            totalSum: $this->totalSum,
            errors: [...$this->errors, $error]
        );
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getTotalSum(): Money
    {
        return $this->totalSum;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
