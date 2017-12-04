<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateAttributes;
use PhpSpec\ObjectBehavior;

class GenerateAttributesSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(10, 50, 20, 20, 10);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GenerateAttributes::class);
    }

    function it_has_a_count()
    {
        $this->count()->shouldReturn(10);
    }

    function it_has_a_percentage_of_useable_in_grid()
    {
        $this->percentageOfUseableInGrid()->shouldReturn(50);
    }

    function it_has_a_percentage_of_localizable()
    {
        $this->percentageOfLocalizable()->shouldReturn(20);
    }

    function it_has_a_percentage_of_scopable()
    {
        $this->percentageOfScopable()->shouldReturn(20);
    }

    function it_has_a_percentage_of_localizable_and_scopable()
    {
        $this->percentageOfLocalizableAndScopable()->shouldReturn(10);
    }
}
