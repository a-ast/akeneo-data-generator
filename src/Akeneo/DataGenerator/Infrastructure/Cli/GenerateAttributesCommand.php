<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateAttributes;
use Akeneo\DataGenerator\Application\GenerateAttributesHandler;
use Akeneo\DataGenerator\Domain\AttributeGenerator;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAttributesCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('akeneo:api:generate-attributes')
            ->setDescription('Import generated attributes through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of attributes to generate')
            ->addOption('useable-in-grid', null, InputOption::VALUE_OPTIONAL, 'Percentage of useable in product grid', 0)
            ->addOption('localizable', null, InputOption::VALUE_OPTIONAL, 'Percentage of localizable attributes wanted', 0)
            ->addOption('scopable', null, InputOption::VALUE_OPTIONAL, 'Percentage of scopable attributes wanted', 0)
            ->addOption(
                'localizable-scopable',
                null,
                InputOption::VALUE_OPTIONAL,
                'Percentage of localizable and scopable attributes wanted',
                0
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $handler = $this->getNewAttributeHandler();
        $generateAttributes = new GenerateAttributes(
            (int) $number,
            (int) $input->getOption('useable-in-grid'),
            (int) $input->getOption('localizable'),
            (int) $input->getOption('scopable'),
            (int) $input->getOption('localizable-scopable')
        );

        try {
            $handler->handle($generateAttributes);
        } catch (HttpException $e) {
            echo $e->getMessage();
        }

        $output->writeln(
            sprintf('<info>%s attributes have been generated and imported</info>', $input->getArgument('number'))
        );
    }

    /**
     * Returns new attribute handler.
     *
     * @return GenerateAttributesHandler
     */
    private function getNewAttributeHandler(): GenerateAttributesHandler
    {
        $client = $this->getClient();
        $readRepositories = new ReadRepositories($client);
        $generator = new AttributeGenerator($readRepositories->attributeGroupRepository());

        $writeRepositories = new WriteRepositories($client);
        $attributeRepository = $writeRepositories->attributeRepository();

        return new GenerateAttributesHandler($generator, $attributeRepository);
    }

    /**
     * Returns a new API client.
     *
     * @return AkeneoPimClientInterface
     */
    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();

        return $factory->create();
    }
}
