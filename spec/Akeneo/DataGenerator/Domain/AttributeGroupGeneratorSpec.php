<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use PhpSpec\ObjectBehavior;

class AttributeGroupGeneratorSpec extends ObjectBehavior
{
    function it_generates_an_attribute_group ()
    {
        $group = $this->generate();
        $group->shouldBeAnInstanceOf(AttributeGroup::class);
    }
}
