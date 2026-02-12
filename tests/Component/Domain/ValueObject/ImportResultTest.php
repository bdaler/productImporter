<?php

namespace App\Tests\Component\Domain\ValueObject;

use App\Component\Domain\ValueObject\ImportResult;
use App\Component\Domain\ValueObject\ProductVO;
use PHPUnit\Framework\TestCase;

final class ImportResultTest extends TestCase
{
    public function testWithProductIncreasesCountAndSum(): void
    {
        $result = ImportResult::create();

        $product = ProductVO::create(
            code: 'A1',
            name: 'Paper',
            priceFromCsv: '10.00',
            quantity: 2
        );

        $result = $result->withProduct($product);

        self::assertSame(expected: 1, actual: $result->getCount());
        self::assertSame(expected: 2000, actual: $result->getTotalSum()->getAmount());
    }
}
