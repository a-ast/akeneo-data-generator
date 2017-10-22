<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateProducts;
use Akeneo\DataGenerator\Application\GenerateProductsHandler;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Akeneo\DataGenerator\Domain\ProductGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateProductsCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:api:generate-products')
            ->setDescription('Import generated products through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of products to generate')
            ->addOption('with-images', null, InputOption::VALUE_NONE, 'Generate image files');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $withImages = $input->getOption('with-images');
        $handler = $this->productsHandler();
        $bulkSize = 100;
        $bulks = floor($number / $bulkSize);
        for ($index = 0; $index < $bulks; $index++) {
            $this->generateProducts($handler, $bulkSize, $withImages);
            $output->writeln(sprintf('<info>%s products have been generated and imported</info>', $bulkSize));
        }
        $lastBulk = $number % $bulkSize;
        $this->generateProducts($handler, $lastBulk, $withImages);
        if ($lastBulk > 0) {
            $output->writeln(sprintf('<info>%s products have been generated and imported</info>', $lastBulk));
        }
    }

    private function generateProducts(GenerateProductsHandler $handler, int $count, bool $withImages)
    {
        $command = new GenerateProducts($count, $withImages);
        try {
            $handler->handle($command);
        } catch (HttpException $e) {
            echo $e->getMessage();
        }
    }

    private function productsHandler(): GenerateProductsHandler
    {
        $readRepositories = new ReadRepositories($this->getClient());
        $generator = new ProductGenerator(
            $readRepositories->channelRepository(),
            $readRepositories->localeRepository(),
            $readRepositories->currencyRepository(),
            $readRepositories->familyRepository(),
            $readRepositories->categoryRepository()
        );

        $writeRepositories = new WriteRepositories($this->getClient());
        $writeRepository = $writeRepositories->productRepository();

        return new GenerateProductsHandler($generator, $writeRepository);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
