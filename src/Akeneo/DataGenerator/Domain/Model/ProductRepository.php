<?php

namespace Akeneo\DataGenerator\Domain\Model;

interface ProductRepository
{
    public function add(Product $product);
    public function bulkAdd(array $products);
}
