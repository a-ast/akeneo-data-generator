<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\FamilyGenerator;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;

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
