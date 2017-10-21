<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateAttribute;
use Akeneo\DataGenerator\Application\GenerateAttributeHandler;
use Akeneo\DataGenerator\Application\GenerateCategoryTree;
use Akeneo\DataGenerator\Application\GenerateCategoryTreeHandler;
use Akeneo\DataGenerator\Application\GenerateFamily;
use Akeneo\DataGenerator\Application\GenerateFamilyHandler;
use Akeneo\DataGenerator\Domain\AttributeGenerator;
use Akeneo\DataGenerator\Domain\CategoryTreeGenerator;
use Akeneo\DataGenerator\Domain\FamilyGenerator;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\Attributes;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\CatalogConfiguration;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\CategoryTrees;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\Families;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCatalogCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:api:generate-catalog')
            ->setDescription('Import generated catalog (channels, trees, families, attributes, options) through the Akeneo PIM Web API')
            ->addArgument('file-name', InputArgument::REQUIRED, 'Catalog file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('file-name');
        $configuration = new CatalogConfiguration($fileName);

        $trees = $configuration->categoryTrees();
        $this->generateTrees($trees);
        $output->writeln(sprintf('<info>%s trees have been generated and imported</info>', $trees->count()));

        $attributes = $configuration->attributes();
        $this->generateAttributes($attributes);
        $output->writeln(sprintf('<info>%s attributes have been generated and imported</info>', $attributes->count()));

        $families = $configuration->families();
        $this->generateFamilies($families);
        $output->writeln(sprintf('<info>%s families have been generated and imported</info>', $families->count()));

        $output->writeln(sprintf('<info>catalog %s has been generated and imported</info>', $fileName));
    }

    private function generateTrees(CategoryTrees $trees)
    {
        $handler = $this->categoryTreeHandler();
        foreach ($trees as $tree) {
            $command = new GenerateCategoryTree($tree->getChildren(), $tree->getLevels());
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }
        }
    }

    private function generateAttributes(Attributes $attributes)
    {
        $handler = $this->attributeHandler();
        for ($index = 0; $index < $attributes->count(); $index++) {
            $command = new GenerateAttribute(false);
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }
        }
    }

    private function generateFamilies(Families $families)
    {
        $handler = $this->familyHandler();
        for ($index = 0; $index < $families->count(); $index++) {
            $command = new GenerateFamily($families->attributesCount());
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }
        }
    }

    private function attributeHandler(): GenerateAttributeHandler
    {
        $client = $this->getClient();
        $readRepositories = new ReadRepositories($client);
        $generator = new AttributeGenerator($readRepositories->attributeGroupRepository());

        $writeRepositories = new WriteRepositories($client);
        $attributeRepository = $writeRepositories->attributeRepository();

        return new GenerateAttributeHandler($generator, $attributeRepository);
    }

    private function categoryTreeHandler(): GenerateCategoryTreeHandler
    {
        $generator = new CategoryTreeGenerator();
        $writeRepositories = new WriteRepositories($this->getClient());
        $categoryRepository = $writeRepositories->categoryRepository();

        return new GenerateCategoryTreeHandler($generator, $categoryRepository);
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
