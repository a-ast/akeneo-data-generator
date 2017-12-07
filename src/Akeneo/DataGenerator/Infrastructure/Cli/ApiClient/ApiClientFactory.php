<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\ApiClient;

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;

class ApiClientFactory
{
    public function create(): AkeneoPimClientInterface
    {
        $path = __DIR__.'/../../../../../../app/parameters.yml';
        $config = new ApiClientConfiguration($path);
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
