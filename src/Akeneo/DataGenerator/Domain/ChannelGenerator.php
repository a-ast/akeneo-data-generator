<?php

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\Locale;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use Faker\Factory;

class ChannelGenerator
{
    /** @var Generator */
    private $generator;
    /** @var LocaleRepository */
    private $localeRepository;
    /** @var CurrencyRepository */
    private $currencyRepository;
    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->localeRepository = $localeRepository;
        $this->currencyRepository = $currencyRepository;
        $this->categoryRepository = $categoryRepository;
        $this->generator = Factory::create();
    }

    /**
     * @return Channel
     */
    public function generate(): Channel
    {
        $code = $this->generator->unique()->ean13;
        $locales = $this->randomLocales();
        $currencies = $this->randomCurrencies();
        $tree = $this->randomTree();

        return new Channel($code, $locales, $currencies, $tree);
    }

    /**
     * @param string $code
     * @param array  $localeCodes
     * @param array  $currencyCodes
     *
     * @return Channel
     */
    public function generateWithCode(string $code, array $localeCodes, array $currencyCodes): Channel
    {
        $locales = [];
        foreach ($localeCodes as $localeCode) {
            $locales[] = $this->localeRepository->get($localeCode);
        }

        $currencies = [];
        foreach ($currencyCodes as $currencyCode) {
            $currencies[] = $this->currencyRepository->get($currencyCode);
        }

        $tree = $this->randomTree();

        return new Channel($code, $locales, $currencies, $tree);
    }

    private function randomTree(): Category
    {
        $trees = $this->categoryRepository->allTrees();

        return $trees[rand(0, count($trees) - 1)];
    }

    /**
     * @return array
     */
    private function randomLocales(): array
    {
        $numberLocales = 2;
        $locales = $this->localeRepository->all();
        $randomLocales = [];
        for ($ind = 0; $ind < $numberLocales; $ind++) {
            /** @var Locale $locale */
            $locale = $locales[rand(0, count($locales) - 1)];
            if (!in_array($locale->getCode(), $randomLocales)) {
                $randomLocales[$locale->getCode()] = $locale;
            }
        }

        return $randomLocales;
    }

    /**
     * @return array
     */
    private function randomCurrencies(): array
    {
        $numberCurrencies = 2;
        $currencies = $this->currencyRepository->all();
        $randomCurrencies = [];
        for ($ind = 0; $ind < $numberCurrencies; $ind++) {
            /** @var Currency $currency */
            $currency = $currencies[rand(0, count($currencies) - 1)];
            if (!in_array($currency->getCode(), $randomCurrencies)) {
                $randomCurrencies[$currency->getCode()] = $currency;
            }
        }

        return $randomCurrencies;
    }
}
