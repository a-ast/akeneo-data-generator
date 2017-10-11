<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\FamilyRepository;

class FamilyRepositoryInitializer
{
    private $client;
    private $attributeRepository;

    public function __construct(AkeneoPimClientInterface $client, AttributeRepository $attributeRepository)
    {
        $this->client = $client;
        $this->attributeRepository = $attributeRepository;
    }

    public function initialize(FamilyRepository $repository)
    {
        $cursor = $this->client->getFamilyApi()->all();
        foreach ($cursor as $familyData) {
            $attributeCodes = $familyData['attributes'];
            $attributes = [];
            foreach ($attributeCodes as $attributeCode) {
                $attributes[] = $this->attributeRepository->get($attributeCode);
            }
            $repository->add(new Family($familyData['code'], $attributes));
        }
    }
}
