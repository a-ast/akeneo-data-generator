<?php

namespace Nidup\Sandbox;

use Akeneo\Pim\Client\AkeneoPimClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApiCommand extends Command
{
    protected function configure()
    {
        $this->setName('nidup:sandbox')
            ->setDescription('Test the Akeneo PIM API cli')
            ->addArgument('host', InputArgument::REQUIRED, 'PIM host')
            ->addArgument('port', InputArgument::REQUIRED, 'PIM port')
            ->addArgument('clientId', InputArgument::REQUIRED, 'PIM API client id credential')
            ->addArgument('secret', InputArgument::REQUIRED, 'PIM API secret credential')
            ->addArgument('username', InputArgument::OPTIONAL, 'PIM user name', 'admin')
            ->addArgument('password', InputArgument::OPTIONAL, 'PIM user password', 'admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baseUri = sprintf('%s:%s', $input->getArgument('host'), $input->getArgument('port'));
        $clientId = $input->getArgument('clientId');
        $secret = $input->getArgument('secret');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $clientBuilder = new AkeneoPimClientBuilder(
            $baseUri,
            $clientId,
            $secret,
            $username,
            $password
        );
        $client = $clientBuilder->build();

        $categories = $client->getCategoryApi()->all();

        var_dump($categories);
    }
}