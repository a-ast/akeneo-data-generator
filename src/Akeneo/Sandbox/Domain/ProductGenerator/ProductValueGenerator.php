<?php

namespace Akeneo\Sandbox\Domain\ProductGenerator;

use Akeneo\Sandbox\Domain\Model\Attribute;
use Akeneo\Sandbox\Domain\Model\ProductValue;

interface ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue;
}
