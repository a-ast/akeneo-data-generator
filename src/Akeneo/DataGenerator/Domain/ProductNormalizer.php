<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\Product\Categories;
use Akeneo\DataGenerator\Domain\Model\Product\Value;
use Akeneo\DataGenerator\Domain\Model\Product\Values;

/**
 * Normalize a product without media in order to send it to the Api.
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductNormalizer
{
    /**
     * Normalizes a product without its media.
     *
     * @param Product $product
     *
     * @return array
     */
    public function normalize(Product $product): array
    {
        return [
            'identifier' => (string) $product->identifier(),
            'family' => (string) $product->family()->code(),
            'values' => $this->normalizeNonImageValues($product->values()),
            'categories' => $this->normalizeCategories($product->categories())
        ];
    }

    private function normalizeNonImageValues(Values $values): array
    {
        $data = [];
        /** @var Value $value */
        foreach ($values->all() as $value) {
            if ($value->getAttribute()->type() !== AttributeTypes::IMAGE) {
                if (!isset($data[$value->getAttribute()->code()])) {
                    $data[$value->getAttribute()->code()] = [];
                }
                $data[$value->getAttribute()->code()][] = [
                    'data' => $value->getData(),
                    'locale' => $value->getLocale(),
                    'scope' => $value->getChannel(),
                ];
            }
        }

        return $data;
    }

    private function normalizeCategories(Categories $categories): array
    {
        $data = [];
        /** @var Category $category */
        foreach ($categories->all() as $category) {
            $data[] = $category->code();
        }

        return $data;
    }
}
