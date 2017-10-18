<?php

namespace Akeneo\ApiSandbox\Application;

use Akeneo\ApiSandbox\Domain\AttributeGenerator;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;

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
