<?php

namespace Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use Faker\Factory;
use Faker\Generator;

class FamilyGenerator
{
    /** @var AttributeRepository */
    private $attributeRepository;

    /** @var ChannelRepository */
    private $channelRepository;

    /** @var Generator */
    private $generator;

    /**
     * @param AttributeRepository $attributeRepository
     * @param ChannelRepository   $channelRepository
     */
    public function __construct(AttributeRepository $attributeRepository, ChannelRepository $channelRepository)
    {
        $this->generator = Factory::create();
        $this->attributeRepository = $attributeRepository;
        $this->channelRepository = $channelRepository;
    }

    /**
     * @return Family
     */
    public function generate(): Family
    {
        $code = $this->generator->unique()->ean13;
        $attributes = $this->generateRandomAttributes();

        return new Family($code, $attributes);
    }

    /**
     * @return Attribute[]
     */
    private function generateRandomAttributes(): array
    {
        $attributes = $this->attributeRepository->all();
        $randomAttributes = [];
        for ($ind = 0; $ind < 20; $ind++) {
            /** @var Attribute $attribute */
            $attribute = $attributes[rand(0, count($attributes) - 1)];
            if (!in_array($attribute->getCode(), $randomAttributes)) {
                $randomAttributes[$attribute->getCode()] = $attribute;
            }
        }

        return $randomAttributes;
    }
}
