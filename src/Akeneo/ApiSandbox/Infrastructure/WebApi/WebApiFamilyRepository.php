<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\FamilyRepository;
use Akeneo\Pim\AkeneoPimClientInterface;

class WebApiFamilyRepository implements FamilyRepository
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $code): Family
    {
        throw new \LogicException('not implemented yet');
    }

    public function add(Family $family)
    {
        $familyData = [
            'attributes' => $family->getAttributes()->getCodes(),
            'attribute_requirements' => $family->getRequirements()->getAttributeCodesPerChannel()
        ];
        $this->client->getFamilyApi()->create($family->getCode(), $familyData);
    }

    public function count(): int
    {
        throw new \LogicException('not implemented yet');
    }

    public function all(): array
    {
        throw new \LogicException('not implemented yet');
    }
}
