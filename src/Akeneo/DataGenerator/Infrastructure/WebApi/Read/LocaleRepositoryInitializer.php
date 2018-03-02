<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Read;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\Locale;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;

class LocaleRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(LocaleRepository $repository)
    {
        $cursor = $this->client->getLocaleApi()->all(100);
        foreach ($cursor as $itemData) {
            $repository->add(new Locale($itemData['code'], $itemData['enabled']));
        }
    }
}
