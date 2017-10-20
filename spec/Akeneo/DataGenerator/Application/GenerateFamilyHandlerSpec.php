<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateFamily;
use Akeneo\DataGenerator\Domain\FamilyGenerator;
use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use PhpSpec\ObjectBehavior;

class GenerateFamilyHandlerSpec extends ObjectBehavior
{
    function let(FamilyGenerator $generator, FamilyRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_a_family(
        $generator,
        $repository,
        GenerateFamily $command,
        Family $family
    ) {
        $command->getAttributes()->willReturn(20);
        $generator->generate(20)->willReturn($family);
        $repository->add($family)->shouldBeCalled();

        $this->handle($command);
    }
}
