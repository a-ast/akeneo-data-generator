<?php

namespace spec\Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Exception\NoAttributeGroupDefinedException;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroup;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroupRepository;
use PhpSpec\ObjectBehavior;

class AttributeGeneratorSpec extends ObjectBehavior
{
    function let(AttributeGroupRepository $groupRepository)
    {
        $this->beConstructedWith($groupRepository);
    }

    function it_generates_a_attribute_usable_in_grid (
        $groupRepository,
        AttributeGroup $group
    ) {
        $groupRepository->count()->willReturn(1);
        $groupRepository->all()->willReturn([$group]);

        $this->generate(true)->shouldBeAnInstanceOf(Attribute::class);
    }


    function it_throws_an_exception_when_no_attribute_group_exists ($groupRepository)
    {
        $groupRepository->count()->willReturn(0);
        $this->shouldThrow(
            new NoAttributeGroupDefinedException("At least one attribute group should exist")
        )->during(
            'generate',
            [true]
        );
    }
}
