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
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;

class ReadRepositories
{
    private $client;
    private $localeRepository;
    private $attributeRepository;
    private $attributeGroupRepository;
    private $categoryRepository;
    private $familyRepository;
    private $channelRepository;
    private $currencyRepository;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function localeRepository(): LocaleRepository
    {
        if (!$this->localeRepository) {
            $initializer = new LocaleRepositoryInitializer($this->client);
            $this->localeRepository = new InMemoryLocaleRepository();
            $initializer->initialize($this->localeRepository);
        }

        return $this->localeRepository;
    }

    public function attributeRepository(): AttributeRepository
    {
        if (!$this->attributeRepository) {
            $groupRepository = $this->attributeGroupRepository();
            $initializer = new AttributeRepositoryInitializer($this->client, $groupRepository);
            $this->attributeRepository = new InMemoryAttributeRepository();
            $initializer->initialize($this->attributeRepository);
        }

        return $this->attributeRepository;
    }

    public function attributeGroupRepository(): AttributeGroupRepository
    {
        if (!$this->attributeGroupRepository) {
            $initializer = new AttributeGroupRepositoryInitializer($this->client);
            $this->attributeGroupRepository = new InMemoryAttributeGroupRepository();
            $initializer->initialize($this->attributeGroupRepository);
        }

        return $this->attributeGroupRepository;
    }

    public function categoryRepository(): CategoryRepository
    {
        if (!$this->categoryRepository) {
            $this->categoryRepository = new InMemoryCategoryRepository();
            $initializer = new CategoryRepositoryInitializer($this->client);
            $initializer->initialize($this->categoryRepository);
        }

        return $this->categoryRepository;
    }

    public function familyRepository(): FamilyRepository
    {
        if (!$this->familyRepository) {
            $attributeRepository = $this->attributeRepository();
            $channelRepository = $this->channelRepository();

            $this->familyRepository = new InMemoryFamilyRepository();
            $initializer = new FamilyRepositoryInitializer($this->client, $attributeRepository, $channelRepository);
            $initializer->initialize($this->familyRepository);
        }

        return $this->familyRepository;
    }

    public function channelRepository(): ChannelRepository
    {
        if (!$this->channelRepository) {
            $localeRepository = $this->localeRepository();
            $currencyRepository = $this->currencyRepository();
            $categoryRepository = $this->categoryRepository();

            $initializer = new ChannelRepositoryInitializer(
                $this->client,
                $localeRepository,
                $currencyRepository,
                $categoryRepository
            );
            $this->channelRepository = new InMemoryChannelRepository();
            $initializer->initialize($this->channelRepository);
        }

        return $this->channelRepository;
    }

    public function currencyRepository(): CurrencyRepository
    {
        if (!$this->currencyRepository) {
            $initializer = new CurrencyRepositoryInitializer($this->client);
            $this->currencyRepository = new InMemoryCurrencyRepository();
            $initializer->initialize($this->currencyRepository);
        }

        return $this->currencyRepository;
    }
}
