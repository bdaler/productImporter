<?php

namespace App\Tests\Component\Domain\ValueObject;

use App\Domain\Entity\Product;
use App\Domain\ValueObject\ImportResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(className: ImportResult::class)]
final class ImportResultTest extends TestCase
{
    public function testWithProductIncreasesCountAndSum(): void
    {
        $result = new ImportResult();

        $product = Product::create(
            code: 'A1',
            name: 'Paper',
            priceFromCsv: '10.00',
            quantity: 2
        );

        $result->addProduct(product: $product);

        self::assertSame(expected: 1, actual: $result->count);
        self::assertSame(expected: 2000, actual: $result->totalSum->getAmount());
    }
}
