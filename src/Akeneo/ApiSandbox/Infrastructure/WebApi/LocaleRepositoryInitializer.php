<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\ApiSandbox\Domain\Model\Locale;
use Akeneo\ApiSandbox\Domain\Model\LocaleRepository;

class LocaleRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(LocaleRepository $repository)
    {
        $cursor = $this->client->getLocaleApi()->all();
        foreach ($cursor as $itemData) {
            if ($itemData['enabled']) {
                $repository->add(new Locale($itemData['code']));
            }
        }
    }
}
