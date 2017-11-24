<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Exception\NoAttributeGroupDefinedException;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use PhpSpec\ObjectBehavior;

class AttributeGeneratorSpec extends ObjectBehavior
{
    function let(AttributeGroupRepository $groupRepository)
    {
        $this->beConstructedWith($groupRepository);
    }

    function it_generates_a_configured_attribute(
        $groupRepository,
        AttributeGroup $group
    ) {
        $groupRepository->count()->willReturn(1);
        $groupRepository->all()->willReturn([$group]);

        $attribute = $this->generate(true, true, true);
        $attribute->shouldBeAnInstanceOf(Attribute::class);
        $attribute->shouldBeUseableInGrid(true);
        $attribute->shouldBeLocalized(true);
        $attribute->shouldBeScopable(true);

        $attribute = $this->generate(false, false, false);
        $attribute->shouldBeAnInstanceOf(Attribute::class);
        $attribute->shouldBeUseableInGrid(false);
        $attribute->shouldBeLocalized(false);
        $attribute->shouldBeScopable(false);
    }

    function it_throws_an_exception_when_no_attribute_group_exists ($groupRepository)
    {
        $groupRepository->count()->willReturn(0);
        $this->shouldThrow(
            new NoAttributeGroupDefinedException("At least one attribute group should exist")
        )->during(
            'generate',
            [true, true, true]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getMatchers(): array
    {
        return [
            'beUseableInGrid' => function (Attribute $attribute, $isUseableInGrid) {
                $properties = $attribute->properties();

                return $isUseableInGrid === $properties->getProperty('useable_as_grid_filter');
            },
            'beLocalized' => function (Attribute $attribute, $isLocalized) {
                return $isLocalized === $attribute->localizable();
            },
            'beScopable' => function (Attribute $attribute, $isScopable) {
                return $isScopable === $attribute->scopable();
            }
        ];
    }
}
