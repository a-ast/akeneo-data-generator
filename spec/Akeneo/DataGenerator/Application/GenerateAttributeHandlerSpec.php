<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateAttribute;
use Akeneo\DataGenerator\Domain\AttributeGenerator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use PhpSpec\ObjectBehavior;

class GenerateAttributeHandlerSpec extends ObjectBehavior
{
    function let(AttributeGenerator $generator, AttributeRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_an_attribute_usable_in_grid(
        $generator,
        $repository,
        GenerateAttribute $command,
        Attribute $attribute
    ) {
        $command->isUseableInGrid()->willReturn(true);
        $generator->generate(true)->willReturn($attribute);
        $repository->add($attribute)->shouldBeCalled();

        $this->handle($command);
    }
}
