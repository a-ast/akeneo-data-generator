<?php

namespace Akeneo\DataGenerator\Domain\Model\Product;

use Akeneo\DataGenerator\Domain\Model\Attribute;

class Value
{
    private $attribute;
    private $data;
    private $locale;
    private $channel;

    public function __construct(Attribute $attribute, $data, string $locale = null, string $channel = null)
    {
        $this->attribute = $attribute;
        $this->data = $data;
        $this->locale = $locale;
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
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
