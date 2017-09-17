<?php

namespace Nidup\Sandbox\Application;

use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\ProductValue;

interface ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue;
}
