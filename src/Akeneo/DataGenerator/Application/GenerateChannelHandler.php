<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\ChannelGenerator;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;

class GenerateChannelHandler
{
    private $generator;
    private $repository;

    public function __construct(ChannelGenerator $generator, ChannelRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateChannel $command)
    {
        $channel = $this->generator->generate($command->getLocalesNumber(), $command->getCurrenciesNumber());
        $this->repository->add($channel);
    }
}
