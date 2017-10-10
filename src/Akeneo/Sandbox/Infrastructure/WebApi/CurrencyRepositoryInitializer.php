<?php

namespace Akeneo\Sandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Sandbox\Domain\Model\Currency;
use Akeneo\Sandbox\Domain\Model\CurrencyRepository;

class CurrencyRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(CurrencyRepository $repository)
    {
        $cursor = $this->client->getCurrencyApi()->all();
        foreach ($cursor as $itemData) {
            if ($itemData['enabled']) {
                $repository->add(new Currency($itemData['code']));
            }
        }
    }
}
