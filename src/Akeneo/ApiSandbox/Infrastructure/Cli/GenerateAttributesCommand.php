<?php

namespace Akeneo\ApiSandbox\Infrastructure\Cli;

use Akeneo\ApiSandbox\Application\GenerateAttribute;
use Akeneo\ApiSandbox\Application\GenerateAttributeHandler;
use Akeneo\ApiSandbox\Domain\AttributeGenerator;
use Akeneo\ApiSandbox\Infrastructure\Database\InMemoryAttributeGroupRepository;
use Akeneo\ApiSandbox\Infrastructure\WebApi\AttributeGroupRepositoryInitializer;
use Akeneo\ApiSandbox\Infrastructure\WebApi\WebApiAttributeRepository;
use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAttributesCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:sandbox:generate-attributes')
            ->setDescription('Import generated attributes through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of attributes to generate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $handler = new GenerateAttributeHandler($this->getGenerator(), $this->getAttributeRepository());
        $batchInfo = 100;
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateAttribute();
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }

            if ($index !== 0 && $index % $batchInfo === 0) {
                $output->writeln(sprintf('<info>%s attributes have been generated and imported</info>', $index));
            }
        }
        $output->writeln(sprintf('<info>%s attributes have been generated and imported</info>', $number));
    }

    private function getGenerator(): AttributeGenerator
    {
        $initializer = new AttributeGroupRepositoryInitializer($this->getClient());
        $groupRepository = new InMemoryAttributeGroupRepository();
        $initializer->initialize($groupRepository);

        return new AttributeGenerator($groupRepository);
    }

    private function getAttributeRepository(): AttributeRepository
    {
        $client = $this->getClient();

        return new WebApiAttributeRepository($client);
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
