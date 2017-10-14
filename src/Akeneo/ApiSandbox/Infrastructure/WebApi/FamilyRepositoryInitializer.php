<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Domain\Model\Family\AttributeRequirement;
use Akeneo\ApiSandbox\Domain\Model\Family\AttributeRequirements;
use Akeneo\ApiSandbox\Domain\Model\Family\Attributes;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\FamilyRepository;

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