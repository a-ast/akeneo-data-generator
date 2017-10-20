<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Symfony\Component\Yaml\Yaml;

class ConfigProvider
{
    private $config;

    public function __construct($path)
    {
        $this->config = Yaml::parse(file_get_contents($path))['config'];
    }

    public function getParameter(string $param): string
    {
        return $this->config[$param];
    }
}
