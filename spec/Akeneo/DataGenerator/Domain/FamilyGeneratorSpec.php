<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Exception\NotEnoughAttributesException;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\Family\AttributeRequirements;
use Akeneo\DataGenerator\Domain\Model\Family\Attributes;
use PhpSpec\ObjectBehavior;

class FamilyGeneratorSpec extends ObjectBehavior
{
    function let(AttributeRepository $attributeRepository, ChannelRepository $channelRepository)
    {
        $this->beConstructedWith($attributeRepository, $channelRepository);
    }

    function it_generates_a_family (
        $attributeRepository,
        $channelRepository,
        Attribute $attribute,
        Channel $channel
    ) {
        $attributeRepository->count()->willReturn(2);
        $attributeRepository->all()->willReturn([$attribute]);
        $attribute->getCode()->willReturn('my-code');

        $channelRepository->all()->willReturn([$channel]);

        $generatedFamily = $this->generate(1);
        $generatedFamily->shouldBeAnInstanceOf(Family::class);
        $generatedFamily->getAttributes()->shouldBeAnInstanceOf(Attributes::class);
        $generatedFamily->getRequirements()->shouldBeAnInstanceOf(AttributeRequirements::class);
    }

    function it_throws_an_exception_when_there_is_not_enough_existing_attributes($attributeRepository)
    {
        $attributeRepository->count()->willReturn(10);
        $this->shouldThrow(
            new NotEnoughAttributesException("Only 10 existing attributes, can't add 20 attributes in family")
        )->during(
            'generate',
            [20]
        );
    }
}
