<?php

namespace Akeneo\ApiSandbox\Infrastructure\Cli;

use Akeneo\ApiSandbox\Application\GenerateFamily;
use Akeneo\ApiSandbox\Application\GenerateFamilyHandler;
use Akeneo\ApiSandbox\Domain\FamilyGenerator;
use Akeneo\ApiSandbox\Domain\Model\FamilyRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\InMemoryAttributeGroupRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\InMemoryAttributeRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\InMemoryChannelRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\InMemoryCurrencyRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\InMemoryLocaleRepository;
use Akeneo\ApiSandbox\Infrastructure\WebApi\AttributeGroupRepositoryInitializer;
use Akeneo\ApiSandbox\Infrastructure\WebApi\AttributeRepositoryInitializer;
use Akeneo\ApiSandbox\Infrastructure\WebApi\ChannelRepositoryInitializer;
use Akeneo\ApiSandbox\Infrastructure\WebApi\CurrencyRepositoryInitializer;
use Akeneo\ApiSandbox\Infrastructure\WebApi\LocaleRepositoryInitializer;
use Akeneo\ApiSandbox\Infrastructure\WebApi\WebApiFamilyRepository;
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
        $this->setName('akeneo:sandbox:generate-families')
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
