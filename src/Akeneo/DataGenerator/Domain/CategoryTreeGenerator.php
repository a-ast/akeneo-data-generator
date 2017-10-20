<?php

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\Category;
use Faker\Factory;
use Faker\Generator;

class CategoryTreeGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    /**
     * @param int $children
     * @param int $levels
     *
     * @return Category
     */
    public function generate(int $children, int $levels): Category
    {
        $code = $this->generator->unique()->ean13;
        $tree = new Category($code);
        $countByLevel = $this->calculateNodeCountPerLevel($levels, $children);
        $this->generateChildrenCategories($tree, 1, $countByLevel, $levels);

        return $tree;
    }

    /**
     * Generate categories in a tree structure
     *
     * @param Category $parent
     * @param int $level
     * @param int $count
     * @param int $levelMax
     *
     * @return Category
     */
    private function generateChildrenCategories(Category $parent, $level, $count, $levelMax)
    {
        for ($i = 0; $i < $count; $i++) {
            $categoryCode = $this->generator->unique()->ean13;
            $category = new Category($categoryCode, $parent);
            $parent->addChild($category);
            if ($level < $levelMax) {
                $this->generateChildrenCategories($category, $level + 1, $count, $levelMax);
            }
        }
        return $parent;
    }

    /**
     * Calculate on approximation for the average number of nodes per level needed from the
     * provided node count and level count
     *
     * @param int $levelCount
     * @param int $nodeCount
     *
     * @return int
     */
    private function calculateNodeCountPerLevel($levelCount, $nodeCount)
    {
        $lowerLimit = 1;
        $upperLimit = round(pow($nodeCount, 1/$levelCount));
        $approximationFound = false;
        $avgNodeCount = $lowerLimit;
        $prevDistance = PHP_INT_MAX;
        $prevAvgNodeCount = null;
        while (!$approximationFound && $avgNodeCount < $upperLimit) {
            $distance = abs($nodeCount - $this->calculateTotalNodesNumber($levelCount, $avgNodeCount));
            if ($distance > $prevDistance) {
                $approximationFound = true;
            } else {
                $prevAvgNodeCount = $avgNodeCount;
                $avgNodeCount++;
            }
        }
        return $prevAvgNodeCount;
    }

    /**
     * Get the total number of nodes based on levels count and average node count
     * per level
     *
     * @param int $levelCount
     * @param int $avgNodeCount
     *
     * @return int
     */
    private function calculateTotalNodesNumber($levelCount, $avgNodeCount)
    {
        $totalNodeCount = 0;
        for ($level = 1; $level <= $levelCount; $level++) {
            $totalNodeCount += pow($avgNodeCount, $level);
        }
        return $totalNodeCount;
    }
}
