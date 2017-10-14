<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\Product\Value;

class ProductValueBooleanGenerator implements ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $data = rand(0, 1) == 1;

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
