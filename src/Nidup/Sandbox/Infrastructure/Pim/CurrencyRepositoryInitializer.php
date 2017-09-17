<?php

namespace Nidup\Sandbox\Infrastructure\Pim;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Currency;
use Nidup\Sandbox\Domain\CurrencyRepository;

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
