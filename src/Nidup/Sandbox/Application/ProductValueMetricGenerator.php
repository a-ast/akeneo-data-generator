<?php

namespace Nidup\Sandbox\Application;

use Faker\Factory;
use Faker\Generator;
use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\ProductValue;

class ProductValueMetricGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $data = [
            'amount' => $this->generator->numberBetween(1,100),
            'unit' => $attribute->getProperties()->getProperty('default_metric_unit')
        ];

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
