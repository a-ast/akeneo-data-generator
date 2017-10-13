<?php

namespace spec\Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\Category;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;
use PhpSpec\ObjectBehavior;

class InMemoryCategoryRepositorySpec extends ObjectBehavior
{
    function it_stores_family (Category $entity)
    {
        $entity->getCode()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->add($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_throws_an_exception_when_category_does_not_exists ()
    {
        $this->shouldThrow(
            new EntityDoesNotExistsException("Category NotExisting does not exists")
        )->during(
            'get',
            ['NotExisting']
        );
    }
}
