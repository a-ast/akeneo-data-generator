<?php

namespace Nidup\Sandbox\Domain;

class ProductValue
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

    public function toArray(): array
    {
        return [
            $this->attribute->getCode() =>
                [[
                    'data' => $this->data,
                    'locale' => $this->locale,
                    'scope' => $this->channel,
                ]]
        ];
    }
}
