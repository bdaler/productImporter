<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidProductPriceException;

final readonly class Money
{
    private function __construct(private int $amount) // в копейках
    {
    }

    public static function zero(): self
    {
        return new self(amount: 0);
    }

    /**
     * Ожидаемый формат:
     * 123
     * 123.4
     * 123.45
     */
    public static function fromRubles(string $rubles): self
    {
        if (!preg_match(pattern: '/^\d+(\.\d{1,2})?$/', subject: $rubles)) {
            throw new InvalidProductPriceException(
                sprintf('Invalid money format: "%s"', $rubles)
            );
        }

        $parts = explode(separator: '.', string: $rubles);

        $rub = $parts[0];
        $kop = $parts[1] ?? '0';

        $amount = ((int)$rub * 100) + (int)$kop;

        return new self(amount: $amount);
    }

    public function add(self $other): self
    {
        return new self(amount: $this->amount + $other->amount);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function multiply(float $quantity): self
    {
        return new self(amount: (int)round(num: $this->amount * $quantity));
    }

    public function format(): string
    {
        return number_format(num: $this->amount / 100, decimals: 2, thousands_separator: '');
    }
}
