<?php

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\Domain\Rule;
use Akeneo\CouplingDetector\Domain\RuleInterface;

$finder = new DefaultFinder();
$finder->in('src');

$rules = [
    new Rule(
        'Akeneo\Sandbox\Domain',
        ['Akeneo\Sandbox\Domain', 'Faker'],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\Sandbox\Application',
        ['Akeneo\Sandbox\Domain', 'Akeneo\Sandbox\Application'],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\Sandbox\Infrastructure\Database',
        [
            'Akeneo\Sandbox\Domain',
            'Akeneo\Sandbox\Application',
            'Akeneo\Sandbox\Infrastructure\Database',
        ],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\Sandbox\Infrastructure\WebApi',
        [
            'Akeneo\Sandbox\Domain',
            'Akeneo\Sandbox\Application',
            'Akeneo\Sandbox\Infrastructure\WebApi',
            'Akeneo\Pim\AkeneoPimClientInterface',
        ],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\Sandbox\Infrastructure\Cli',
        [
            'Akeneo\Sandbox\Domain',
            'Akeneo\Sandbox\Application',
            'Akeneo\Sandbox\Infrastructure\Cli',
            'Akeneo\Pim',
            'Symfony\Component\Console',
            'Symfony\Component\Yaml',
            'Akeneo\Sandbox\Infrastructure\Database', // TODO: should be decoupled
            'Akeneo\Sandbox\Infrastructure\WebApi', // TODO: should be decoupled
        ],
        RuleInterface::TYPE_ONLY
    ),
];

$config = new Configuration($rules, $finder);

return $config;
