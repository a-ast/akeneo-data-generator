<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\AttributeGenerator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;

class GenerateAttributesHandler
{
    /** @var AttributeGenerator */
    private $generator;

    /** @var AttributeRepository */
    private $repository;

    /** @var float */
    private $numberOfUseableInGrid;

    /**
     * @param AttributeGenerator $generator
     * @param AttributeRepository $repository
     */
    public function __construct(AttributeGenerator $generator, AttributeRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    /**
     * @param GenerateAttributes $command
     */
    public function handle(GenerateAttributes $command): void
    {
        $attributes = $this->compute($command);
        $this->repository->addAll($attributes);
    }

    /**
     * @param GenerateAttributes $generateAttributes
     *
     * @return array
     */
    private function compute(GenerateAttributes $generateAttributes): array
    {
        $totalPercentage = $generateAttributes->percentageOfLocalizable() +
            $generateAttributes->percentageOfScopable() +
            $generateAttributes->percentageOfLocalizableAndScopable();

        if (100 < $totalPercentage) {
            throw new \InvalidArgumentException(
                'Number of localizable and scopable attributes can not be upper to the total number of attributes.'
            );
        }
        $totalAttributesNumber = $generateAttributes->count();
        $numberOfLocalizable = $this->convertFromPercentageToCount(
            $generateAttributes->percentageOfLocalizable(),
            $totalAttributesNumber
        );
        $numberOfScopable = $this->convertFromPercentageToCount(
            $generateAttributes->percentageOfScopable(),
            $totalAttributesNumber
        );
        $numberOfLocalizableAndScopable = $this->convertFromPercentageToCount(
            $generateAttributes->percentageOfLocalizableAndScopable(),
            $totalAttributesNumber
        );
        $this->numberOfUseableInGrid = $this->convertFromPercentageToCount(
            $generateAttributes->percentageOfUseableInGrid(),
            $totalAttributesNumber
        );

        $rest = $totalAttributesNumber - $numberOfLocalizableAndScopable - $numberOfLocalizable - $numberOfScopable;

        return array_merge(
            [],
            $this->generateAttributes($numberOfLocalizableAndScopable, true, true),
            $this->generateAttributes($numberOfLocalizable, true, false),
            $this->generateAttributes($numberOfScopable, false, true),
            $this->generateAttributes($rest, false, false)
        );
    }

    /**
     * @param float $count
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute[]
     */
    private function generateAttributes(float $count, bool $isLocalizable, bool $isScopable): array
    {
        $attributes = [];
        while (0 < $count) {
            $attributes[] = $this->generator->generate($this->isUseableInGrid(), $isLocalizable, $isScopable);
            $count--;
        }

        return $attributes;
    }

    /**
     * @return bool
     */
    private function isUseableInGrid(): bool
    {
        if (0 < $this->numberOfUseableInGrid) {
            $this->numberOfUseableInGrid--;

            return true;
        }

        return false;
    }

    /**
     * @param int $percentage
     * @param int $count
     *
     * @return float
     */
    private function convertFromPercentageToCount(int $percentage, int $count): float
    {
        return round(($percentage * $count) / 100);
    }
}
