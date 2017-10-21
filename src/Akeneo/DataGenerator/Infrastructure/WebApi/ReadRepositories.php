<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi;

use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryAttributeGroupRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryAttributeRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryCategoryRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryChannelRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryCurrencyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryFamilyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\InMemoryLocaleRepository;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\LocaleRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\AttributeGroupRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\AttributeRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\CategoryRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\ChannelRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\CurrencyRepositoryInitializer;
use Akeneo\DataGenerator\Infrastructure\WebApi\Read\FamilyRepositoryInitializer;
use Akeneo\Pim\AkeneoPimClientInterface;

class ReadRepositories
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function localeRepository(): LocaleRepository
    {
        $initializer = new LocaleRepositoryInitializer($this->client);
        $repository = new InMemoryLocaleRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    public function attributeRepository(): AttributeRepository
    {
        $groupRepository = $this->attributeGroupRepository();
        $initializer = new AttributeRepositoryInitializer($this->client, $groupRepository);
        $repository = new InMemoryAttributeRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    public function attributeGroupRepository(): AttributeGroupRepository
    {
        $initializer = new AttributeGroupRepositoryInitializer($this->client);
        $repository = new InMemoryAttributeGroupRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    public function categoryRepository(): CategoryRepository
    {
        $repository = new InMemoryCategoryRepository();
        $initializer = new CategoryRepositoryInitializer($this->client);
        $initializer->initialize($repository);

        return $repository;
    }

    public function familyRepository(): FamilyRepository
    {
        $attributeRepository = $this->attributeRepository();
        $channelRepository = $this->channelRepository();

        $repository = new InMemoryFamilyRepository();
        $initializer = new FamilyRepositoryInitializer($this->client, $attributeRepository, $channelRepository);
        $initializer->initialize($repository);

        return $repository;
    }

    public function channelRepository(): ChannelRepository
    {
        $localeRepository = $this->localeRepository();
        $currencyRepository = $this->currencyRepository();
        $categoryRepository = $this->categoryRepository();

        $initializer = new ChannelRepositoryInitializer(
            $this->client,
            $localeRepository,
            $currencyRepository,
            $categoryRepository
        );
        $repository = new InMemoryChannelRepository();
        $initializer->initialize($repository);

        return $repository;
    }

    public function currencyRepository(): CurrencyRepository
    {
        $initializer = new CurrencyRepositoryInitializer($this->client);
        $repository = new InMemoryCurrencyRepository();
        $initializer->initialize($repository);

        return $repository;
    }
}
