<?php

namespace Nidup\Sandbox\Application;

use Nidup\Sandbox\Domain\Model\ProductRepository;
use Nidup\Sandbox\Domain\ProductGenerator;

class GenerateProductHandler
{
    private $generator;
    private $repository;

    public function __construct(ProductGenerator $generator, ProductRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateProduct $command)
    {
        $product = $this->generator->generate();
        $this->repository->add($product);
    }
}
