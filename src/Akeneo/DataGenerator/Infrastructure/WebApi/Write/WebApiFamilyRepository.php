<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
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
            'attributes' => $family->attributes()->getCodes(),
            'attribute_requirements' => $family->requirements()->getAttributeCodesPerChannel()
        ];
        $this->client->getFamilyApi()->create($family->code(), $familyData);
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
