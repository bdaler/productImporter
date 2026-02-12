<?php

namespace App\Component\Domain\ValueObject;

use App\Component\Domain\Exception\InvalidProductPriceException;
use App\Component\Domain\Exception\InvalidProductQuantityException;
use InvalidArgumentException;

final readonly class ProductVO
{
    private function __construct(
        private string $code,
        private string $name,
        private Money $price,
        private float $quantity,
    ) { }

    /**
     * @throws InvalidProductPriceException
     * @throws InvalidProductQuantityException
     */
    public static function create(string $code, string $name, string $priceFromCsv, float $quantity): self
    {
        if ($code === '') {
            throw new InvalidArgumentException(message: 'Code cannot be empty');
        }

        if ($name === '' || mb_strlen($name) > 2000) {
            throw new InvalidArgumentException(message: 'Invalid name');
        }

        if ($priceFromCsv < 0) {
            throw new InvalidProductPriceException(message: 'Price cannot be negative');
        }

        if ($quantity < 0) {
            throw new InvalidProductQuantityException(message: 'Quantity cannot be negative');
        }

        return new self(code: $code, name: $name, price: Money::fromRubles(rubles: $priceFromCsv), quantity: $quantity);
    }

    public function total(): Money
    {
        return $this->price->multiply(quantity: $this->quantity);
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }
}
