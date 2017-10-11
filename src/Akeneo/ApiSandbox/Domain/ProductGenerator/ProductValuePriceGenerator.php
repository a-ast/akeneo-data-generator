<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\CurrencyRepository;
use Akeneo\ApiSandbox\Domain\Model\ProductValue;

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

    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $currencies = $this->currencyRepository->all();
        $data = [];
        foreach ($currencies as $currency) {
            $data[]= [
                'amount' => $this->generator->numberBetween(10, 1000),
                'currency' => $currency->getCode()
            ];
        }

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
