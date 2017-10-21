<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\ChannelGenerator;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;

class GenerateChannelWithDefinedCodeAndLocalesAndCurrenciesHandler
{
    private $generator;
    private $repository;

    public function __construct(ChannelGenerator $generator, ChannelRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateChannelWithDefinedCodeAndLocalesAndCurrencies $command)
    {
        $channel = $this->generator->generateWithCode($command->code(), $command->locales(), $command->currencies());
        $this->repository->add($channel);
    }
}
