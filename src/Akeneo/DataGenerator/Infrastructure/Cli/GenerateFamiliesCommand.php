<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateFamily;
use Akeneo\DataGenerator\Application\GenerateFamilyHandler;
use Akeneo\DataGenerator\Domain\FamilyGenerator;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateFamiliesCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:api:generate-families')
            ->setDescription('Import generated families through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of families to generate')
            ->addArgument('attributes', InputArgument::REQUIRED, 'Number of attributes per family');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $attributes = $input->getArgument('attributes');
        $handler = $this->familyHandler();
        $batchInfo = 100;
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateFamily($attributes);
            $handler->handle($command);

            if ($index !== 0 && $index % $batchInfo === 0) {
                $output->writeln(sprintf('<info>%s families have been generated and imported</info>', $index));
            }
        }
        $output->writeln(sprintf('<info>%s families have been generated and imported</info>', $number));
    }

    private function familyHandler(): GenerateFamilyHandler
    {
        $readRepositories = new ReadRepositories($this->getClient());
        $generator = new FamilyGenerator($readRepositories->attributeRepository(), $readRepositories->channelRepository());

        $writeRepositories = new WriteRepositories($this->getClient());
        $familyRepository = $writeRepositories->familyRepository();

        return new GenerateFamilyHandler($generator, $familyRepository);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
