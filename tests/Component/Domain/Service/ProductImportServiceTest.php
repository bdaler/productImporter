<?php

namespace App\Tests\Component\Domain\Service;

use App\Application\Source\ParsedProductDto;
use App\Application\Source\ProductSourceInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\ProductImportService;
use App\Domain\ValueObject\ImportResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(className: ProductImportService::class)]
final class ProductImportServiceTest extends TestCase
{
    public function testImportSuccessfulBatch(): void
    {
        $repository = $this->createMock(originalClassName: ProductRepositoryInterface::class);

        $repository
            ->expects($this->once())
            ->method(constraint: 'saveBatch')
            ->with($this->callback(fn (array $products) => count($products) === 2));

        $source = $this->createMock(originalClassName: ProductSourceInterface::class);
        $source
            ->method('getData')
            ->willReturn([
                new ParsedProductDto(
                    code: 'A1',
                    name: 'Paper',
                    price: '100.00',
                    quantity: '2',
                ),
                new ParsedProductDto(
                    code: 'A2',
                    name: 'Pen',
                    price: '50.00',
                    quantity: '3',
                ),
            ]);

        $service = new ProductImportService(repository: $repository);

        $result = $service->import(source: $source);

        self::assertInstanceOf(expected: ImportResult::class, actual: $result);
        self::assertSame(expected: 2, actual: $result->count);
        self::assertSame(expected: 35000, actual: $result->totalSum->getAmount());
        self::assertCount(expectedCount: 0, haystack: $result->getErrors());
    }

    public function testImportWithInvalidRow(): void
    {
        $repository = $this->createMock(originalClassName: ProductRepositoryInterface::class);

        $repository
            ->expects($this->never())
            ->method(constraint: 'saveBatch');

        $source = $this->createMock(originalClassName: ProductSourceInterface::class);
        $source
            ->method('getData')
            ->willReturn([
                new ParsedProductDto(
                    code: '',
                    name: 'Invalid product',
                    price: '100.00',
                    quantity: '1',
                ),
            ]);

        $service = new ProductImportService(repository: $repository);

        $result = $service->import(source: $source);

        self::assertSame(expected: 0, actual: $result->count);
        self::assertCount(expectedCount: 1, haystack: $result->getErrors());
    }
}
