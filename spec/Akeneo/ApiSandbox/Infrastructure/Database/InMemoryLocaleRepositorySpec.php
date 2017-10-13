<?php

namespace spec\Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\Locale;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;
use PhpSpec\ObjectBehavior;

class InMemoryLocaleRepositorySpec extends ObjectBehavior
{
    function it_stores_locale (Locale $entity)
    {
        $entity->getCode()->willReturn('MyCode');
        $this->count()->shouldReturn(0);
        $this->add($entity);
        $this->count()->shouldReturn(1);
        $this->get('MyCode')->shouldReturn($entity);
        $this->all()->shouldReturn([$entity]);
    }

    function it_throws_an_exception_when_locale_does_not_exists ()
    {
        $this->shouldThrow(
            new EntityDoesNotExistsException("Locale NotExisting does not exists")
        )->during(
            'get',
            ['NotExisting']
        );
    }
}
