<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Domain\ProductGenerator;

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
