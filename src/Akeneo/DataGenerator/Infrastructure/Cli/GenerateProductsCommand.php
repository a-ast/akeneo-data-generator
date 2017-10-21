<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\ReadRepositories;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Akeneo\DataGenerator\Application\GenerateProduct;
use Akeneo\DataGenerator\Application\GenerateProductHandler;
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
        $handler = $this->productHandler();
        $batchInfo = 100;
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateProduct($withImages);
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }

            if ($index !== 0 && $index % $batchInfo === 0) {
                $output->writeln(sprintf('<info>%s products have been generated and imported</info>', $index));
            }
        }
        $output->writeln(sprintf('<info>%s products have been generated and imported</info>', $number));
    }

    private function productHandler(): GenerateProductHandler
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

        return new GenerateProductHandler($generator, $writeRepository);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
