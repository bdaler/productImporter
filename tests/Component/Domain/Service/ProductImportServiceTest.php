<?php

namespace App\Tests\Component\Domain\Service;

use App\Component\Application\Source\ProductSourceInterface;
use App\Component\Domain\Repository\ProductRepositoryInterface;
use App\Component\Domain\Service\ProductImportService;
use App\Component\Domain\ValueObject\ImportResult;
use PHPUnit\Framework\TestCase;

final class ProductImportServiceTest extends TestCase
{
    public function testImportSuccessfulBatch(): void
    {
        $repository = $this->createMock(originalClassName: ProductRepositoryInterface::class);

        $repository
            ->expects($this->once())
            ->method(constraint: 'saveBatch')
            ->with($this->callback(fn(array $products) => count($products) === 2));

        $source = $this->createMock(originalClassName: ProductSourceInterface::class);
        $source
            ->method('getData')
            ->willReturn([
                [
                    'code' => 'A1',
                    'name' => 'Paper',
                    'price' => '100.00',
                    'quantity' => '2',
                ],
                [
                    'code' => 'A2',
                    'name' => 'Pen',
                    'price' => '50.00',
                    'quantity' => '3',
                ],
            ]);

        $service = new ProductImportService(repository: $repository);

        $result = $service->import(source: $source);

        self::assertInstanceOf(expected: ImportResult::class, actual: $result);
        self::assertSame(expected: 2, actual: $result->getCount());
        self::assertSame(expected: 35000, actual: $result->getTotalSum()->getAmount());
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
                [
                    'code' => '',
                    'name' => 'Invalid product',
                    'price' => '100.00',
                    'quantity' => '1',
                ],
            ]);

        $service = new ProductImportService(repository: $repository);

        $result = $service->import(source: $source);

        self::assertSame(expected: 0, actual: $result->getCount());
        self::assertCount(expectedCount: 1, haystack: $result->getErrors());
    }
}
