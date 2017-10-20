<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateFamily;
use Akeneo\DataGenerator\Application\GenerateFamilyHandler;
use Akeneo\DataGenerator\Domain\FamilyGenerator;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryAttributeGroupRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryAttributeRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryChannelRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryCurrencyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryLocaleRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\AttributeGroupRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\AttributeRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\ChannelRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\CurrencyRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\LocaleRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\WebApiFamilyRepository;
use Akeneo\Pim\AkeneoPimClientBuilder;
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
        $handler = new GenerateFamilyHandler($this->getGenerator(), $this->getFamilyRepository());
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

    private function getGenerator(): FamilyGenerator
    {
        $client = $this->getClient();

        $initializer = new LocaleRepositoryInitializer($client);
        $localeRepository = new InMemoryLocaleRepository();
        $initializer->initialize($localeRepository);

        $initializer = new CurrencyRepositoryInitializer($client);
        $currencyRepository = new InMemoryCurrencyRepository();
        $initializer->initialize($currencyRepository);

        $initializer = new ChannelRepositoryInitializer($client, $localeRepository, $currencyRepository);
        $channelRepository = new InMemoryChannelRepository();
        $initializer->initialize($channelRepository);

        $initializer = new AttributeGroupRepositoryInitializer($client);
        $groupRepository = new InMemoryAttributeGroupRepository();
        $initializer->initialize($groupRepository);

        $initializer = new AttributeRepositoryInitializer($client, $groupRepository);
        $attributeRepository = new InMemoryAttributeRepository();
        $initializer->initialize($attributeRepository);

        return new FamilyGenerator($attributeRepository, $channelRepository);
    }

    private function getFamilyRepository(): FamilyRepository
    {
        $client = $this->getClient();

        return new WebApiFamilyRepository($client);
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
