<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;
use PhpSpec\ObjectBehavior;

class InMemoryAttributeGroupRepositorySpec extends ObjectBehavior
{
    function it_stores_attribute_group (AttributeGroup $group)
    {
        $group->getCode()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->add($group);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($group);
        $this->all()->shouldReturn([$group]);
    }

    function it_throws_an_exception_when_attribute_group_does_not_exists ()
    {
        $this->shouldThrow(
            new EntityDoesNotExistsException("Attribute Group NotExisting does not exists")
        )->during(
            'get',
            ['NotExisting']
        );
    }
}
