<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;
use PhpSpec\ObjectBehavior;

class InMemoryCategoryRepositorySpec extends ObjectBehavior
{
    function it_adds_a_category(Category $entity)
    {
        $entity->code()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->add($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_upserts_a_category(Category $entity)
    {
        $entity->code()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->upsert($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_upserts_many_categories(Category $sales, Category $master)
    {
        $master->code()->willReturn('master');
        $sales->code()->willReturn('sales');
        $this->count()->shouldReturn(0);
        $this->upsertMany([$master, $sales]);
        $this->count()->shouldReturn(2);
        $this->get('master')->shouldReturn($master);
        $this->get('sales')->shouldReturn($sales);
        $this->all()->shouldReturn([$master, $sales]);
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
