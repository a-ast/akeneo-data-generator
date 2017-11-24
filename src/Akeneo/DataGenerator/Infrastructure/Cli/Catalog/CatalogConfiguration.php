<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

use Symfony\Component\Yaml\Yaml;

class CatalogConfiguration
{
    /** @var array */
    private $config;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $path = __DIR__.'/../../../../../../app/catalog/'.$filename;
        $this->config = Yaml::parse(file_get_contents($path))['catalog'];
    }

    /**
     * @return CategoryTrees
     */
    public function categoryTrees(): CategoryTrees
    {
        $configuration = $this->config['entities']['category-trees'];
        $trees = [];
        foreach ($configuration as $tree) {
            $trees[] = new CategoryTree($tree['code'], $tree['children'], $tree['levels']);
        }

        return new CategoryTrees($trees);
    }

    /**
     * @return Attributes
     */
    public function attributes(): Attributes
    {
        $configuration = $this->config['entities']['attributes'];
        $localizable = $configuration['percentage_of_localizable'] ?? 0;
        $scopable = $configuration['percentage_of_scopable'] ?? 0;
        $localizableAndScopable = $configuration['percentage_of_localizable_and_scopable'] ?? 0;
        $useableInGrid = $configuration['percentage_of_useable_in_grid'] ?? 0;

        return new Attributes(
            $configuration['count'],
            $localizable,
            $scopable,
            $localizableAndScopable,
            $useableInGrid
        );
    }

    /**
     * @return AttributeGroups
     */
    public function attributeGroups(): AttributeGroups
    {
        $configuration = $this->config['entities']['attribute-groups'];

        return new AttributeGroups($configuration['count']);
    }

    /**
     * @return Families
     */
    public function families(): Families
    {
        $configuration = $this->config['entities']['families'];

        return new Families($configuration['count'], $configuration['attributes_count']);
    }

    /**
     * @return Products
     */
    public function products(): Products
    {
        $configuration = $this->config['entities']['products'];

        return new Products($configuration['count'], $configuration['with_images']);
    }

    /**
     * @return Channels
     */
    public function channels(): Channels
    {
        $configuration = $this->config['entities']['channels'];
        $channels = [];
        foreach ($configuration as $channel) {
            $channels[] = new Channel($channel['code'], $channel['locales'], $channel['currencies']);
        }

        return new Channels($channels);
    }
}
