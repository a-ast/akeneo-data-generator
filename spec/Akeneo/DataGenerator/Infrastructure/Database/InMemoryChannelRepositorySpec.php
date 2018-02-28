<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;
use PhpSpec\ObjectBehavior;

class InMemoryChannelRepositorySpec extends ObjectBehavior
{
    function it_adds_a_channel(Channel $entity)
    {
        $entity->code()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->add($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_upsert_a_channel(Channel $entity)
    {
        $entity->code()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->upsert($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_throws_an_exception_when_channel_does_not_exists ()
    {
        $this->shouldThrow(
            new EntityDoesNotExistsException("Channel NotExisting does not exists")
        )->during(
            'get',
            ['NotExisting']
        );
    }
}
