<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

use Symfony\Component\Yaml\Yaml;

class CatalogConfiguration
{
    private $config;

    public function __construct(string $filename)
    {
        $path = __DIR__.'/../../../../../../app/catalog/'.$filename;
        $this->config = Yaml::parse(file_get_contents($path))['catalog'];
    }

    public function categoryTrees(): CategoryTrees
    {
        $configuration = $this->config['entities']['category-trees'];
        $trees = [];
        foreach ($configuration as $tree) {
            $trees[] = new CategoryTree($tree['code'], $tree['children'], $tree['levels']);
        }

        return new CategoryTrees($trees);
    }

    public function attributes(): Attributes
    {
        $configuration = $this->config['entities']['attributes'];

        return new Attributes($configuration['count']);
    }

    public function families(): Families
    {
        $configuration = $this->config['entities']['families'];

        return new Families($configuration['count'], $configuration['attributes_count']);
    }
}
