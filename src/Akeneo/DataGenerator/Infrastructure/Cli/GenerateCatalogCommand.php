<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateCategoryTree;
use Akeneo\DataGenerator\Application\GenerateCategoryTreeHandler;
use Akeneo\DataGenerator\Domain\CategoryTreeGenerator;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\CatalogConfiguration;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Http\Client\Exception\HttpException;
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
        $handler = new GenerateCategoryTreeHandler($this->getCategoryTreeGenerator(), $this->getCategoryRepository());
        foreach ($trees as $tree) {
            $command = new GenerateCategoryTree($tree->getChildren(), $tree->getLevels());
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }
        }
        $output->writeln(sprintf('<info>%s trees have been generated and imported</info>', $trees->count()));

        $output->writeln(sprintf('<info>catalog %s has been generated and imported</info>', $fileName));
    }

    private function getCategoryTreeGenerator(): CategoryTreeGenerator
    {
        return new CategoryTreeGenerator();
    }

    private function getCategoryRepository(): CategoryRepository
    {
        $writeRepositories = new WriteRepositories($this->getClient());

        return $writeRepositories->categoryRepository();
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
