<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateFamily;
use Akeneo\DataGenerator\Application\GenerateFamilyHandler;
use Akeneo\DataGenerator\Domain\FamilyGenerator;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
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
        $handler = new GenerateFamilyHandler($this->getFamilyGenerator(), $this->getFamilyRepository());
        $batchInfo = 100;
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateFamily($attributes);
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }

            if ($index !== 0 && $index % $batchInfo === 0) {
                $output->writeln(sprintf('<info>%s families have been generated and imported</info>', $index));
            }
        }
        $output->writeln(sprintf('<info>%s families have been generated and imported</info>', $number));
    }

    private function getFamilyGenerator(): FamilyGenerator
    {
        $readRepositories = new ReadRepositories($this->getClient());

        return new FamilyGenerator($readRepositories->attributeRepository(), $readRepositories->channelRepository());
    }

    private function getFamilyRepository(): FamilyRepository
    {
        $writeRepositories = new WriteRepositories($this->getClient());

        return $writeRepositories->familyRepository();
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
