<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateAttributes;
use Akeneo\DataGenerator\Application\GenerateAttributesHandler;
use Akeneo\DataGenerator\Domain\AttributeGenerator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use PhpSpec\ObjectBehavior;

class GenerateAttributesHandlerSpec extends ObjectBehavior
{
    function let(AttributeGenerator $generator, AttributeRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GenerateAttributesHandler::class);
    }

    function it_generates_a_configured_attribute_usable_in_grid(
        $generator,
        $repository,
        GenerateAttributes $command,
        Attribute $attribute
    ) {
        $command->count()->willReturn(10);
        $command->percentageOfUseableInGrid()->willReturn(100);
        $command->percentageOfLocalizable()->willReturn(20);
        $command->percentageOfScopable()->willReturn(20);
        $command->percentageOfLocalizableAndScopable()->willReturn(10);
        $generator->generate(true, true, true)->shouldBeCalledTimes(1)->willReturn($attribute);
        $generator->generate(true, false, true)->shouldBeCalledTimes(2)->willReturn($attribute);
        $generator->generate(true, true, false)->shouldBeCalledTimes(2)->willReturn($attribute);
        $generator->generate(true, false, false)->shouldBeCalledTimes(5)->willReturn($attribute);
        $attributes = [
            $attribute,
            $attribute,
            $attribute,
            $attribute,
            $attribute,
            $attribute,
            $attribute,
            $attribute,
            $attribute,
            $attribute
        ];
        $repository->addAll($attributes)->shouldBeCalled();

        $this->handle($command);
    }

    function it_throws_an_exception_if_total_percentage_is_upper_than_100(GenerateAttributes $command)
    {
        $command->percentageOfLocalizable()->willReturn(50);
        $command->percentageOfScopable()->willReturn(50);
        $command->percentageOfLocalizableAndScopable()->willReturn(50);
        $this->shouldThrow(
            new \InvalidArgumentException(
                'Number of localizable and scopable attributes can not be upper to the total number of attributes.'
            )
        )->during('handle', [$command]);
    }
}
