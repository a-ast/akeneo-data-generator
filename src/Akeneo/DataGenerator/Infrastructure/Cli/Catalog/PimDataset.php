<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\Exception\MinimalCatalogExpectedException;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;

class PimDataset
{
    private $channelRepository;
    private $attributeRepository;

    public function __construct(ChannelRepository $channelRepository, AttributeRepository $attributeRepository)
    {
        $this->channelRepository = $channelRepository;
        $this->attributeRepository = $attributeRepository;
    }

    public function isMinimal(): bool
    {
        if ($this->channelRepository->count() !== 1 || $this->attributeRepository->count() !== 1) {
            throw new MinimalCatalogExpectedException(
                sprintf('Expect to have only one channel and on attribute')
            );
        }
        try {
            $code = 'ecommerce';
            $this->channelRepository->get($code);
        } catch (EntityDoesNotExistsException $e) {
            throw new MinimalCatalogExpectedException(
                sprintf('Expect to have a channel with code %s', $code),
                0,
                $e
            );
        }
        try {
            $code = 'sku';
            $this->attributeRepository->get('sku');
        } catch (EntityDoesNotExistsException $e) {
            throw new MinimalCatalogExpectedException(
                sprintf('Expect to have an attribute with code %s', $code),
                0,
                $e
            );
        }

        return true;
    }
}
