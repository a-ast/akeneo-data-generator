<?php

namespace Nidup\Sandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Model\Locale;
use Nidup\Sandbox\Domain\Model\LocaleRepository;

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
