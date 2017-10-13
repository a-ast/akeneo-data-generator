<?php

namespace spec\Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use PhpSpec\ObjectBehavior;

class FamilyGeneratorSpec extends ObjectBehavior
{
    function let(AttributeRepository $attributeRepository, ChannelRepository $channelRepository)
    {
        $this->beConstructedWith($attributeRepository, $channelRepository);
    }

    function it_generates_a_family (
        $attributeRepository,
        Attribute $attribute
    ) {
        $attributeRepository->count()->willReturn(1);
        $attributeRepository->all()->willReturn([$attribute]);
        $attribute->getCode()->willReturn('my-code');

        $this->generate()->shouldBeAnInstanceOf(Family::class);
    }
}
