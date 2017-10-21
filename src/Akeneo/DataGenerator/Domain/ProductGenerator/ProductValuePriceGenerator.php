<?php

namespace Akeneo\DataGenerator\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\Product\Value;

class ProductValuePriceGenerator implements ProductValueGenerator
{
    /** @var CurrencyRepository */
    private $currencyRepository;
    /** @var Generator */
    private $generator;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $currencies = $this->currencyRepository->all();
        $data = [];
        foreach ($currencies as $currency) {
            $data[]= [
                'amount' => $this->generator->numberBetween(10, 1000),
                'currency' => $currency->code()
            ];
        }

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
