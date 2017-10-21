<?php

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\Domain\Rule;
use Akeneo\CouplingDetector\Domain\RuleInterface;

$finder = new DefaultFinder();
$finder->in('src');

$rules = [
    new Rule(
        'Akeneo\DataGenerator\Domain',
        ['Akeneo\DataGenerator\Domain', 'Faker'],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\DataGenerator\Application',
        ['Akeneo\DataGenerator\Domain', 'Akeneo\DataGenerator\Application'],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\DataGenerator\Infrastructure\Database',
        [
            'Akeneo\DataGenerator\Domain',
            'Akeneo\DataGenerator\Application',
            'Akeneo\DataGenerator\Infrastructure\Database',
        ],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\DataGenerator\Infrastructure\WebApi',
        [
            'Akeneo\DataGenerator\Domain',
            'Akeneo\DataGenerator\Application',
            'Akeneo\DataGenerator\Infrastructure\WebApi',
            'Akeneo\DataGenerator\Infrastructure\Database', // TODO: should be decoupled
            'Akeneo\Pim\AkeneoPimClientInterface',
        ],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\DataGenerator\Infrastructure\Cli',
        [
            'Akeneo\DataGenerator\Domain',
            'Akeneo\DataGenerator\Application',
            'Akeneo\DataGenerator\Infrastructure\Cli',
            'Akeneo\Pim',
            'Symfony\Component\Console',
            'Symfony\Component\Yaml',
            'Akeneo\DataGenerator\Infrastructure\Database', // TODO: should be decoupled
            'Akeneo\DataGenerator\Infrastructure\WebApi', // TODO: should be decoupled
        ],
        RuleInterface::TYPE_ONLY
    ),
];

$config = new Configuration($rules, $finder);

return $config;
