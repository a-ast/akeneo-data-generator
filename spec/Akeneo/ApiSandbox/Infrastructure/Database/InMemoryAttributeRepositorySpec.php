<?php

namespace spec\Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;
use PhpSpec\ObjectBehavior;

class InMemoryAttributeRepositorySpec extends ObjectBehavior
{
    function it_stores_attribute (Attribute $entity)
    {
        $entity->getCode()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->add($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_throws_an_exception_when_attribute_does_not_exists ()
    {
        $this->shouldThrow(
            new EntityDoesNotExistsException("Attribute NotExisting does not exists")
        )->during(
            'get',
            ['NotExisting']
        );
    }
}
