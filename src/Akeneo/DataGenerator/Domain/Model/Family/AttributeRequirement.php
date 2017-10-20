<?php

namespace Akeneo\DataGenerator\Domain\Model\Family;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Channel;

class AttributeRequirement
{
    /** @var Attribute */
    private $attribute;
    /** @var Channel */
    private $channel;

    public function __construct(Attribute $attribute, Channel $channel)
    {
        $this->attribute = $attribute;
        $this->channel = $channel;
    }

    /**
     * @return Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    /**
     * @return Channel
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }
}
