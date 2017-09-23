<?php

namespace Nidup\Sandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Model\MeasureFamily;
use Nidup\Sandbox\Domain\Model\MeasureFamilyRepository;

class MeasureFamilyRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(MeasureFamilyRepository $repository)
    {
        $cursor = $this->client->getMeasureFamilyApi()->all();
        foreach ($cursor as $itemData) {
            $repository->add(new MeasureFamily($itemData['code'], $itemData['standard'], $itemData['units']));
        }
    }
}
