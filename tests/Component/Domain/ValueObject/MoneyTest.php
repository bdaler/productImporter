<?php

namespace App\Tests\Component\Domain\ValueObject;

use App\Domain\Exception\InvalidProductPriceException;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(className: Money::class)]
final class MoneyTest extends TestCase
{
    public function testFromRublesCreatesCorrectAmount(): void
    {
        $money = Money::fromRubles(rubles: '1253.25');

        self::assertSame(expected: 125325, actual: $money->getAmount());
    }

    public function testFromRublesThrowsOnInvalidFormat(): void
    {
        $this->expectException(exception: InvalidProductPriceException::class);

        Money::fromRubles(rubles: '12.345');
    }

    public function testAddReturnsNewInstance(): void
    {
        $a = Money::fromRubles(rubles: '10.00');
        $b = Money::fromRubles(rubles: '5.00');

        $c = $a->add(other: $b);

        self::assertSame(expected: 1500, actual: $c->getAmount());
        self::assertNotSame(expected: $a, actual: $c);
    }
}
