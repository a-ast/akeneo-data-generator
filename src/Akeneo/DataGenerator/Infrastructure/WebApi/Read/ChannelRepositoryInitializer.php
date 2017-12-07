<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Read;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;

class ChannelRepositoryInitializer
{
    private $client;
    private $localeRepository;
    private $currencyRepository;
    private $categoryRepository;

    public function __construct(
        AkeneoPimClientInterface $client,
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->client = $client;
        $this->localeRepository = $localeRepository;
        $this->currencyRepository = $currencyRepository;
        $this->categoryRepository = $categoryRepository;
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
                $currencies[] = $this->currencyRepository->get($currencyCode);
            }
            $tree = $this->categoryRepository->get($itemData['category_tree']);

            $repository->add(new Channel($itemData['code'], $locales, $currencies, $tree));
        }
    }
}
