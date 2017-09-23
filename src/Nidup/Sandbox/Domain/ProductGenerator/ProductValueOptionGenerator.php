<?php

namespace Nidup\Sandbox\Domain\ProductGenerator;

use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\ProductValue;

class ProductValueOptionGenerator implements ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $options = $attribute->getAttributeOptions();
        $codes = $options->getCodes();
        if (count($codes) > 0) {
            $data = $codes[rand(0, count($codes) -1 )];
        } else {
            $data = null;
        }

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}