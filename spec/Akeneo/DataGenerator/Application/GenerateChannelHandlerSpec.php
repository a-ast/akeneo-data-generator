<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateChannel;
use Akeneo\DataGenerator\Domain\ChannelGenerator;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use PhpSpec\ObjectBehavior;

class GenerateChannelHandlerSpec extends ObjectBehavior
{
    function let(ChannelGenerator $generator, ChannelRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_a_channel(
        $generator,
        $repository,
        GenerateChannel $command,
        Channel $channel
    ) {
        $command->getLocalesNumber()->willReturn(3);
        $command->getCurrenciesNumber()->willReturn(2);
        $generator->generate(3, 2)->willReturn($channel);
        $repository->add($channel)->shouldBeCalled();
        $this->handle($command);
    }
}
