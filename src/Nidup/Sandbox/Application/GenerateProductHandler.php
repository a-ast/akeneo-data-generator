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
        if ($command->withImages()) {
            $product = $this->generator->generateWithImages();
        } else {
            $product = $this->generator->generateWithoutImages();
        }
        $this->repository->add($product);
    }
}
