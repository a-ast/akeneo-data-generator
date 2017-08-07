<?php

namespace Nidup\Sandbox\Infrastructure\Cli;


use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Application\ConfigProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    protected function configure()
    {
        $this->setName('nidup:sandbox:import')
            ->setDescription('Import through the Akeneo PIM Web API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getClient();
        $products = $client->getProductApi()->all();
        foreach ($products as $product) {
            var_dump($product);
            die();
        }
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $config = new ConfigProvider( __DIR__.'/../../../../../app/parameters.yml');
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