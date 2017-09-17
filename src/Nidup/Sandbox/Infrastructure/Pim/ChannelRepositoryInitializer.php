<?php

namespace Nidup\Sandbox\Infrastructure\Pim;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Channel;
use Nidup\Sandbox\Domain\ChannelRepository;
use Nidup\Sandbox\Domain\Currency;
use Nidup\Sandbox\Domain\LocaleRepository;

class ChannelRepositoryInitializer
{
    private $client;
    private $localeRepository;

    public function __construct(AkeneoPimClientInterface $client, LocaleRepository $localeRepository)
    {
        $this->client = $client;
        $this->localeRepository = $localeRepository;
    }

    public function initialize(ChannelRepository $repository)
    {
        $cursor = $this->client->getChannelApi()->all();
        foreach ($cursor as $itemData) {

            $locales = [];
            foreach ($itemData['locales'] as $localeCode) {
                $locales[] = $this->localeRepository->get($localeCode);
            }
            $currencies = [];
            foreach ($itemData['currencies'] as $currencyCode) {
                $currencies[] = new Currency($currencyCode);
            }

            $repository->add(new Channel($itemData['code'], $locales, $currencies));
        }
    }
}
