<?php

namespace Nidup\Sandbox\Domain\ProductGenerator;

use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\ProductValue;

interface ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue;
}
