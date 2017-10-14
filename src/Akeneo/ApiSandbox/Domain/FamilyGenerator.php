<?php

namespace Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\Family\AttributeRequirement;
use Akeneo\ApiSandbox\Domain\Model\Family\AttributeRequirements;
use Akeneo\ApiSandbox\Domain\Model\Family\Attributes;
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
        $requirements = $this->generateRandomAttributeRequirements($attributes);

        return new Family($code, $attributes, $requirements);
    }

    /**
     * @return Attributes
     */
    private function generateRandomAttributes(): Attributes
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

        return new Attributes($randomAttributes);
    }

    /**
     * @return AttributeRequirements
     */
    private function generateRandomAttributeRequirements(Attributes $attributes): AttributeRequirements
    {
        $channels = $this->channelRepository->all();
        $randomRequirements = [];
        foreach ($channels as $channel) {
            foreach ($attributes->all() as $attribute) {
                if (rand(0, 1) == 1) {
                    $randomRequirements[] = new AttributeRequirement($attribute, $channel);
                }
            }
        }

        return new AttributeRequirements($randomRequirements);
    }
}
