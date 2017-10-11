<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\ProductValue;

class ProductValueBooleanGenerator implements ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $data = rand(0, 1) == 1;

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
