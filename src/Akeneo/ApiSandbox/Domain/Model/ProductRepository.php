<?php

namespace Akeneo\ApiSandbox\Domain\Model;

interface ProductRepository
{
    public function add(Product $product);
}
