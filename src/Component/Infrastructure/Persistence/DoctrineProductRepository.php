<?php

namespace App\Component\Infrastructure\Persistence;

use App\Component\Domain\Repository\ProductRepositoryInterface;
use App\Component\Domain\ValueObject\ProductVO;
use App\Entity\Product;
use App\Repository\ProductRepository;

final readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function save(ProductVO $product): void
    {
        $entity = $this->makeEntity(product: $product);
        $this->repository->persistAndFlush(product: $entity, flush: true);
    }

    /**
     * @param array<ProductVO> $products
     */
    public function saveBatch(array $products, int $batchSize = 100): void
    {
        $entities = array_map(fn (ProductVO $product) => $this->makeEntity(product: $product), $products);
        $this->repository->saveBatch(products: $entities);
    }

    private function makeEntity(ProductVO $product): Product
    {
        $entity = new Product();
        $entity->setCode(code: $product->getCode());
        $entity->setName(name: $product->getName());
        $entity->setPrice(price: $product->getPrice()->getAmount());
        $entity->setQuantity(quantity: $product->getQuantity());

        return $entity;
    }
}
