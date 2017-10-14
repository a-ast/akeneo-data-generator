<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Faker\Factory;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\Product\Value;

class ProductValueImageGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $data = $this->generator->image('/tmp/', 640, 480);

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
