<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateChannel;
use Akeneo\DataGenerator\Application\GenerateChannelHandler;
use Akeneo\DataGenerator\Domain\ChannelGenerator;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateChannelsCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:api:generate-channels')
            ->setDescription('Import generated channels through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of channels to generate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $handler = $this->channelHandler();
        $batchInfo = 100;
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateChannel();
            $handler->handle($command);

            if ($index !== 0 && $index % $batchInfo === 0) {
                $output->writeln(sprintf('<info>%s channels have been generated and imported</info>', $index));
            }
        }
        $output->writeln(sprintf('<info>%s channels have been generated and imported</info>', $number));
    }

    private function channelHandler(): GenerateChannelHandler
    {
        $readRepositories = new ReadRepositories($this->getClient());
        $generator = new ChannelGenerator(
            $readRepositories->localeRepository(),
            $readRepositories->currencyRepository(),
            $readRepositories->categoryRepository()
        );

        $writeRepositories = new WriteRepositories($this->getClient());
        $channelRepository = $writeRepositories->channelRepository();

        return new GenerateChannelHandler($generator, $channelRepository);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
