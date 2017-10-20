<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\AttributeGenerator;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;

class GenerateAttributeHandler
{
    private $generator;
    private $repository;

    public function __construct(AttributeGenerator $generator, AttributeRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateAttribute $command)
    {
        $attribute = $this->generator->generate($command->isUseableInGrid());
        $this->repository->add($attribute);
    }
}
