<?php

use Akeneo\CouplingDetector\Configuration\Configuration;
use Akeneo\CouplingDetector\Configuration\DefaultFinder;
use Akeneo\CouplingDetector\Domain\Rule;
use Akeneo\CouplingDetector\Domain\RuleInterface;

$finder = new DefaultFinder();
$finder->in('src');

$rules = [
    new Rule(
        'Akeneo\ApiSandbox\Domain',
        ['Akeneo\ApiSandbox\Domain', 'Faker'],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\ApiSandbox\Application',
        ['Akeneo\ApiSandbox\Domain', 'Akeneo\ApiSandbox\Application'],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\ApiSandbox\Infrastructure\Database',
        [
            'Akeneo\ApiSandbox\Domain',
            'Akeneo\ApiSandbox\Application',
            'Akeneo\ApiSandbox\Infrastructure\Database',
        ],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\ApiSandbox\Infrastructure\WebApi',
        [
            'Akeneo\ApiSandbox\Domain',
            'Akeneo\ApiSandbox\Application',
            'Akeneo\ApiSandbox\Infrastructure\WebApi',
            'Akeneo\Pim\AkeneoPimClientInterface',
        ],
        RuleInterface::TYPE_ONLY
    ),
    new Rule(
        'Akeneo\ApiSandbox\Infrastructure\Cli',
        [
            'Akeneo\ApiSandbox\Domain',
            'Akeneo\ApiSandbox\Application',
            'Akeneo\ApiSandbox\Infrastructure\Cli',
            'Akeneo\Pim',
            'Symfony\Component\Console',
            'Symfony\Component\Yaml',
            'Akeneo\ApiSandbox\Infrastructure\Database', // TODO: should be decoupled
            'Akeneo\ApiSandbox\Infrastructure\WebApi', // TODO: should be decoupled
        ],
        RuleInterface::TYPE_ONLY
    ),
];

$config = new Configuration($rules, $finder);

return $config;
