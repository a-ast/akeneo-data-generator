<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi;

use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Write\WebApiAttributeGroupRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Write\WebApiAttributeRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Write\WebApiCategoryRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Write\WebApiChannelRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Write\WebApiFamilyRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Write\WebApiProductRepository;
use Akeneo\Pim\AkeneoPimClientInterface;

class WriteRepositories
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function channelRepository(): ChannelRepository
    {
        return new WebApiChannelRepository($this->client);
    }

    public function attributeRepository(): AttributeRepository
    {
        return new WebApiAttributeRepository($this->client);
    }

    public function familyRepository(): FamilyRepository
    {
        return new WebApiFamilyRepository($this->client);
    }

    public function categoryRepository(): CategoryRepository
    {
        return new WebApiCategoryRepository($this->client);
    }

    public function productRepository(): ProductRepository
    {
        return new WebApiProductRepository($this->client);
    }

    public function attributeGroupRepository(): AttributeGroupRepository
    {
        return new WebApiAttributeGroupRepository($this->client);
    }
}
