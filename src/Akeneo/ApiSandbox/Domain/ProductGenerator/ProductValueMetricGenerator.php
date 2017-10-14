<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\Product\Value;

class ProductValueMetricGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $data = [
            'amount' => $this->generator->numberBetween(1, 100),
            'unit' => $attribute->getProperties()->getProperty('default_metric_unit')
        ];

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
