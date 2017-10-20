<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Infrastructure\Database\InMemoryAttributeGroupRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\AttributeGroupRepositoryInitializer;
use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Akeneo\DataGenerator\Application\GenerateProduct;
use Akeneo\DataGenerator\Application\GenerateProductHandler;
use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Domain\ProductGenerator;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryAttributeRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryCategoryRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryChannelRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryCurrencyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryFamilyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryLocaleRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\AttributeRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\CategoryRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\ChannelRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\CurrencyRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\FamilyRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\LocaleRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\WebApiProductRepository;
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
        $handler = new GenerateProductHandler($this->getGenerator(), $this->getProductRepository());
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

    private function getGenerator(): ProductGenerator
    {
        $localeRepository = $this->buildLocaleRepository();
        $currencyRepository = $this->buildCurrencyRepository();
        $categoryRepository = $this->buildCategoryRepository();
        $channelRepository = $this->buildChannelRepository($localeRepository, $currencyRepository);
        $attributeRepository = $this->buildAttributeRepository();
        $familyRepository = $this->buildFamilyRepository($attributeRepository, $channelRepository);

        return new ProductGenerator(
            $channelRepository,
            $localeRepository,
            $currencyRepository,
            $familyRepository,
            $categoryRepository
        );
    }

    private function getProductRepository(): ProductRepository
    {
        $client = $this->getClient();

        return new WebApiProductRepository($client);
    }

    private function buildCategoryRepository(): CategoryRepository
    {
        $client = $this->getClient();
        $repository = new InMemoryCategoryRepository();
        $initializer = new CategoryRepositoryInitializer($client);
        $initializer->initialize($repository);

        return $repository;
    }

    private function buildFamilyRepository(
        AttributeRepository $attributeRepository,
        ChannelRepository $channelRepository
    ): FamilyRepository {
        $client = $this->getClient();
        $repository = new InMemoryFamilyRepository();
        $initializer = new FamilyRepositoryInitializer($client, $attributeRepository, $channelRepository);
        $initializer->initialize($repository);

        return $repository;
    }

    private function buildAttributeRepository(): AttributeRepository
    {
        $client = $this->getClient();
        $initializer = new AttributeGroupRepositoryInitializer($client);
        $groupRepository = new InMemoryAttributeGroupRepository();
        $initializer->initialize($groupRepository);

        $initializer = new AttributeRepositoryInitializer($client, $groupRepository);
        $repository = new InMemoryAttributeRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    private function buildLocaleRepository(): LocaleRepository
    {
        $client = $this->getClient();
        $initializer = new LocaleRepositoryInitializer($client);
        $repository = new InMemoryLocaleRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    private function buildCurrencyRepository(): CurrencyRepository
    {
        $client = $this->getClient();
        $initializer = new CurrencyRepositoryInitializer($client);
        $repository = new InMemoryCurrencyRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    private function buildChannelRepository(
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository
    ): ChannelRepository {
        $client = $this->getClient();
        $initializer = new ChannelRepositoryInitializer($client, $localeRepository, $currencyRepository);
        $repository = new InMemoryChannelRepository();
        $initializer->initialize($repository);

        return $repository;
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
