<?php

namespace spec\Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Exception\NotEnoughAttributesException;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\Channel;
use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\Family\AttributeRequirements;
use Akeneo\ApiSandbox\Domain\Model\Family\Attributes;
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
