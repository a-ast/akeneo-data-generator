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
use Faker\Generator;

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
     * @param int $localesNumber
     * @param int $currenciesNumber
     *
     * @return Channel
     */
    public function generate(int $localesNumber, int $currenciesNumber): Channel
    {
        $code = $this->generator->unique()->ean13;
        $locales = $this->randomLocales($localesNumber);
        $currencies = $this->randomCurrencies($currenciesNumber);
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
     * @param int $localesNumber
     *
     * @return array
     */
    private function randomLocales(int $localesNumber): array
    {
        $locales = $this->localeRepository->all();
        $randomLocales = [];
        for ($ind = 0; $ind < $localesNumber; $ind++) {
            /** @var Locale $locale */
            $locale = $locales[rand(0, count($locales) - 1)];
            if (!in_array($locale->code(), $randomLocales)) {
                $randomLocales[$locale->code()] = $locale;
            }
        }

        return $randomLocales;
    }

    /**
     * @param int $currenciesNumber
     *
     * @return array
     */
    private function randomCurrencies(int $currenciesNumber): array
    {
        $currencies = $this->currencyRepository->all();
        $randomCurrencies = [];
        for ($ind = 0; $ind < $currenciesNumber; $ind++) {
            /** @var Currency $currency */
            $currency = $currencies[rand(0, count($currencies) - 1)];
            if (!in_array($currency->code(), $randomCurrencies)) {
                $randomCurrencies[$currency->code()] = $currency;
            }
        }

        return $randomCurrencies;
    }
}
