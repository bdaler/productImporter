<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Entity\Product as ProductEntity;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Doctrine\Entity\Product as ProductOrm;
use App\Infrastructure\Doctrine\Repository\ProductRepository;

final readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function save(ProductEntity $product): void
    {
        $entity = $this->makeEntity(product: $product);
        $this->repository->persistAndFlush(product: $entity, flush: true);
    }

    /**
     * @param array<Product> $products
     */
    public function saveBatch(array $products, int $batchSize = 100): void
    {
        $entities = array_map(fn (ProductEntity $product) => $this->makeEntity(product: $product), $products);
        $this->repository->saveBatch(products: $entities);
    }

    private function makeEntity(ProductEntity $product): ProductOrm
    {
        $entity = new ProductOrm();
        $entity->setCode(code: $product->getCode());
        $entity->setName(name: $product->getName());
        $entity->setPrice(price: $product->getPrice()->getAmount());
        $entity->setQuantity(quantity: $product->getQuantity());

        return $entity;
    }
}
