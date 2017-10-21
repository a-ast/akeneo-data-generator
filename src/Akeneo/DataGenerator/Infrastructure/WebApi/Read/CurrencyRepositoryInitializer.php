<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Read;

use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;

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
