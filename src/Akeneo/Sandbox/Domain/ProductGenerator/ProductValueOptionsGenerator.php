<?php

namespace Akeneo\Sandbox\Domain\ProductGenerator;

use Akeneo\Sandbox\Domain\Model\Attribute;
use Akeneo\Sandbox\Domain\Model\ProductValue;

class ProductValueOptionsGenerator implements ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $options = $attribute->getAttributeOptions();
        $codes = $options->getCodes();
        $randomCodes = [];
        for ($ind = 0; $ind < 3; $ind++) {
            $randomCodes[] = $codes[rand(0, count($codes) -1)];
        }
        $data = array_unique($randomCodes);

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
