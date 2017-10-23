<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateAttributeGroup;
use Akeneo\DataGenerator\Domain\AttributeGroupGenerator;
use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use PhpSpec\ObjectBehavior;

class GenerateAttributeGroupHandlerSpec extends ObjectBehavior
{
    function let(AttributeGroupGenerator $generator, AttributeGroupRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_an_attribute_group(
        $generator,
        $repository,
        GenerateAttributeGroup $command,
        AttributeGroup $group
    ) {
        $generator->generate()->willReturn($group);
        $repository->add($group)->shouldBeCalled();

        $this->handle($command);
    }
}
