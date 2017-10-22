<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Domain\ProductGenerator;

class GenerateProductsHandler
{
    private $generator;
    private $repository;

    public function __construct(ProductGenerator $generator, ProductRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateProducts $command)
    {
        $products = [];
        for ($ind = 0; $ind < $command->count(); $ind++) {
            if ($command->withImages()) {
                $products[] = $this->generator->generateWithImages();
            } else {
                $products[] = $this->generator->generateWithoutImages();
            }
        }
        $this->repository->bulkAdd($products);
    }
}
