<?php

namespace spec\Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\Channel;
use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\FamilyAttributeRequirements;
use Akeneo\ApiSandbox\Domain\Model\FamilyAttributes;
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
        $attributeRepository->count()->willReturn(1);
        $attributeRepository->all()->willReturn([$attribute]);
        $attribute->getCode()->willReturn('my-code');

        $channelRepository->all()->willReturn([$channel]);

        $generatedFamily = $this->generate();
        $generatedFamily->shouldBeAnInstanceOf(Family::class);
        $generatedFamily->getAttributes()->shouldBeAnInstanceOf(FamilyAttributes::class);
        $generatedFamily->getRequirements()->shouldBeAnInstanceOf(FamilyAttributeRequirements::class);
    }
}
