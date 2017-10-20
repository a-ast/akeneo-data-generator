<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi;

use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\Family\AttributeRequirement;
use Akeneo\DataGenerator\Domain\Model\Family\AttributeRequirements;
use Akeneo\DataGenerator\Domain\Model\Family\Attributes;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;

class FamilyRepositoryInitializer
{
    private $client;
    private $attributeRepository;
    private $channelRepository;

    public function __construct(
        AkeneoPimClientInterface $client,
        AttributeRepository $attributeRepository,
        ChannelRepository $channelRepository
    ) {
        $this->client = $client;
        $this->attributeRepository = $attributeRepository;
        $this->channelRepository = $channelRepository;
    }

    public function initialize(FamilyRepository $repository)
    {
        $cursor = $this->client->getFamilyApi()->all();
        foreach ($cursor as $familyData) {
            $attributeCodes = $familyData['attributes'];
            $attributeRequirements = $familyData['attribute_requirements'];
            $attributes = [];
            $requirements = [];
            foreach ($attributeCodes as $attributeCode) {
                $attributes[] = $this->attributeRepository->get($attributeCode);
            }
            foreach ($attributeRequirements as $channelCode => $attributeRequirementCodes) {
                foreach ($attributeRequirementCodes as $attributeRequirementCode) {
                    $requirements[]= new AttributeRequirement(
                        $this->attributeRepository->get($attributeRequirementCode),
                        $this->channelRepository->get($channelCode)
                    );
                }
            }
            $repository->add(
                new Family(
                    $familyData['code'],
                    new Attributes($attributes),
                    new AttributeRequirements($requirements)
                )
            );
        }
    }
}
