<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateProducts;
use Akeneo\DataGenerator\Application\GenerateProductsHandler;
use Akeneo\DataGenerator\Domain\ProductMediaNormalizer;
use Akeneo\DataGenerator\Domain\ProductNormalizer;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\DataGenerator\Infrastructure\Database\JsonProductRepository;
use Akeneo\DataGenerator\Domain\ProductGenerator;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
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
            ->addOption('with-images', null, InputOption::VALUE_NONE, 'Generate image files')
            ->addOption(
                'products-to-json-file',
                null,
                InputOption::VALUE_REQUIRED,
                'Create products and output in a json file'
            )->addOption(
                'media-to-json-file',
                null,
                InputOption::VALUE_REQUIRED,
                'Create products and output in a json file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $withImages = $input->getOption('with-images');
        $jsonOptions = [];
        if (null !== $input->getOption('products-to-json-file')) {
            $jsonOptions['productDestFile'] = $input->getOption('products-to-json-file');
        }
        if (null !== $input->getOption('media-to-json-file') && $withImages) {
            $jsonOptions['mediaDestFile'] = $input->getOption('media-to-json-file');
        }
        if (isset($jsonOptions['productDestFile']) && !isset($jsonOptions['mediaDestFile']) && $withImages) {
            $output->writeln(
                '<error>Option "--media-to-json-file" is required ' .
                ' if "--product-to-json-file" is used with "--with-images".</error>'
            );
            throw new \InvalidArgumentException(
                'Option "--media-to-json-file" is required if "--product-to-json-file" is used with "--with-images".'
            );
        }

        $handler = $this->productsHandler($jsonOptions);
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
        $handler->handle($command);
    }

    private function productsHandler(array $jsonOptions): GenerateProductsHandler
    {
        $readRepositories = new ReadRepositories($this->getClient());
        $generator = new ProductGenerator(
            $readRepositories->channelRepository(),
            $readRepositories->localeRepository(),
            $readRepositories->currencyRepository(),
            $readRepositories->familyRepository(),
            $readRepositories->categoryRepository()
        );

        if (empty($jsonOptions)) {
            $writeRepositories = new WriteRepositories($this->getClient());
            $writeRepository = $writeRepositories->productRepository();
        } else {
            $writeRepository = new JsonProductRepository(
                new ProductNormalizer(),
                new ProductMediaNormalizer(),
                $jsonOptions['productDestFile'],
                $jsonOptions['mediaDestFile'] ?? null
            );
        }

        return new GenerateProductsHandler($generator, $writeRepository);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();

        return $factory->create();
    }
}
