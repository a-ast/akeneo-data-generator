<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateChannelWithDefinedCodeAndLocalesAndCurrencies;
use Akeneo\DataGenerator\Domain\ChannelGenerator;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use PhpSpec\ObjectBehavior;

class GenerateChannelWithDefinedCodeAndLocalesAndCurrenciesHandlerSpec extends ObjectBehavior
{
    function let(ChannelGenerator $generator, ChannelRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_a_channel(
        $generator,
        $repository,
        GenerateChannelWithDefinedCodeAndLocalesAndCurrencies $command,
        Channel $channel
    ) {
        $command->code()->willReturn('ecommerce');
        $command->locales()->willReturn(['en_US']);
        $command->currencies()->willReturn(['EUR']);
        $generator->generateWithCode('ecommerce', ['en_US'], ['EUR'])->willReturn($channel);
        $repository->upsert($channel)->shouldBeCalled();
        $this->handle($command);
    }
}
