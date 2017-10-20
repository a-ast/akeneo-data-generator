<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateCategoryTree;
use Akeneo\DataGenerator\Application\GenerateCategoryTreeHandler;
use Akeneo\DataGenerator\Domain\CategoryTreeGenerator;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\WebApiCategoryRepository;
use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCategoryTreesCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:api:generate-category-trees')
            ->setDescription('Import generated category tree through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of trees to generate')
            ->addArgument('children', InputArgument::REQUIRED, 'Number of categories to generate per tree')
            ->addArgument('levels', InputArgument::REQUIRED, 'Number of levels per tree');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $children = $input->getArgument('children');
        $levels = $input->getArgument('levels');
        $handler = new GenerateCategoryTreeHandler($this->getGenerator(), $this->getCategoryRepository());
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateCategoryTree($children, $levels);
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }
            $output->writeln(sprintf('<info>%s trees have been generated and imported</info>', $index+1));
        }
    }

    private function getGenerator(): CategoryTreeGenerator
    {
        return new CategoryTreeGenerator();
    }

    private function getCategoryRepository(): CategoryRepository
    {
        $client = $this->getClient();

        return new WebApiCategoryRepository($client);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $config = new ConfigProvider(__DIR__.'/../../../../../app/parameters.yml');
        $baseUri = $config->getParameter('base_uri');
        $clientId = $config->getParameter('client_id');
        $secret = $config->getParameter('secret');
        $username = $config->getParameter('username');
        $password = $config->getParameter('password');

        $clientBuilder = new AkeneoPimClientBuilder($baseUri);
        return $clientBuilder->buildAuthenticatedByPassword(
            $clientId,
            $secret,
            $username,
            $password
        );
    }
}
