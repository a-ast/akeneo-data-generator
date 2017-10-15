<?php

namespace Akeneo\ApiSandbox\Application;

use Akeneo\ApiSandbox\Domain\FamilyGenerator;
use Akeneo\ApiSandbox\Domain\Model\FamilyRepository;

class GenerateFamilyHandler
{
    private $generator;
    private $repository;

    public function __construct(FamilyGenerator $generator, FamilyRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateFamily $command)
    {
        $family = $this->generator->generate($command->getAttributes());
        $this->repository->add($family);
    }
}
