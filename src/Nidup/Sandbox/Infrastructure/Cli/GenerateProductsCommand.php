<?php

namespace Nidup\Sandbox\Infrastructure\Cli;

use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Application\GenerateProduct;
use Nidup\Sandbox\Application\GenerateProductHandler;
use Nidup\Sandbox\Domain\Model\ProductRepository;
use Nidup\Sandbox\Domain\ProductGenerator;
use Nidup\Sandbox\Domain\Model\AttributeRepository;
use Nidup\Sandbox\Domain\Model\CategoryRepository;
use Nidup\Sandbox\Domain\Model\ChannelRepository;
use Nidup\Sandbox\Domain\Model\CurrencyRepository;
use Nidup\Sandbox\Domain\Model\FamilyRepository;
use Nidup\Sandbox\Domain\Model\LocaleRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryAttributeRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryCategoryRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryChannelRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryCurrencyRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryFamilyRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryLocaleRepository;
use Nidup\Sandbox\Infrastructure\WebApi\AttributeRepositoryInitializer;
use Nidup\Sandbox\Infrastructure\WebApi\CategoryRepositoryInitializer;
use Nidup\Sandbox\Infrastructure\WebApi\ChannelRepositoryInitializer;
use Nidup\Sandbox\Infrastructure\WebApi\CurrencyRepositoryInitializer;
use Nidup\Sandbox\Infrastructure\WebApi\FamilyRepositoryInitializer;
use Nidup\Sandbox\Infrastructure\WebApi\LocaleRepositoryInitializer;
use Nidup\Sandbox\Infrastructure\WebApi\WebApiProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateProductsCommand extends Command
{
    protected function configure()
    {
        $this->setName('nidup:sandbox:generate-products')
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
            } catch (\Exception $e) {
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
        $familyRepository = $this->buildFamilyRepository($attributeRepository);

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

    private function buildFamilyRepository(AttributeRepository $attributeRepository): FamilyRepository
    {
        $client = $this->getClient();
        $repository = new InMemoryFamilyRepository();
        $initializer = new FamilyRepositoryInitializer($client, $attributeRepository);
        $initializer->initialize($repository);

        return $repository;
    }

    private function buildAttributeRepository(): AttributeRepository
    {
        $client = $this->getClient();
        $initializer = new AttributeRepositoryInitializer($client);
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
        $baseUri = sprintf('%s:%s', $config->getParameter('host'), $config->getParameter('port'));
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
