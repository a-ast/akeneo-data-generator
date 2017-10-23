<?php

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Faker\Factory;
use Faker\Generator;

class AttributeGroupGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    /**
     * @return AttributeGroup
     */
    public function generate(): AttributeGroup
    {
        $code = $this->generator->unique()->ean13;

        return new AttributeGroup($code);
    }
}
