<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\AttributeGroupGenerator;
use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;

class GenerateAttributeGroupHandler
{
    private $generator;
    private $repository;

    public function __construct(AttributeGroupGenerator $generator, AttributeGroupRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateAttributeGroup $command)
    {
        $attribute = $this->generator->generate();
        $this->repository->add($attribute);
    }
}
