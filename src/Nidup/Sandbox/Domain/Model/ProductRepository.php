<?php

namespace Nidup\Sandbox\Domain\Model;

interface ProductRepository
{
    public function add(Product $product);
}
