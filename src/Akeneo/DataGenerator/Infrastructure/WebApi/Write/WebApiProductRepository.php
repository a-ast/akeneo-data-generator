<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\Product\Categories;
use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Domain\Model\Product\Value;
use Akeneo\DataGenerator\Domain\Model\Product\Values;

class WebApiProductRepository implements ProductRepository
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function add(Product $product)
    {
        $productDataWithoutImages = $this->productData($product);
        $this->client->getProductApi()->upsert($product->identifier(), $productDataWithoutImages);
        $this->createMediaFiles($product);
    }

    public function bulkAdd(array $products)
    {
        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = $this->productData($product);
        }
        $this->client->getProductApi()->upsertList($productsData);

        foreach ($products as $product) {
            $this->createMediaFiles($product);
        }
    }

    private function createMediaFiles(Product $product)
    {
        $productAttributeImages = $this->normalizeImageValues($product->values());
        foreach ($productAttributeImages as $attributeCode => $productImages) {
            foreach ($productImages as $productImage) {
                $this->client->getProductMediaFileApi()->create(
                    $productImage['data'],
                    [
                        'identifier' => $product->identifier(),
                        'attribute' => $attributeCode,
                        'locale' => $productImage['locale'],
                        'scope' => $productImage['scope'],
                    ]
                );
            }
        }
    }

    private function productData(Product $product): array
    {
        return [
            'identifier' => $product->identifier(),
            'family' => $product->family()->code(),
            'values' => $this->normalizeNonImageValues($product->values()),
            'categories' => $this->normalizeCategories($product->categories())
        ];
    }

    private function normalizeNonImageValues(Values $values)
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

    private function normalizeImageValues(Values $values)
    {
        $data = [];
        /** @var Value $value */
        foreach ($values->all() as $value) {
            if ($value->getAttribute()->type() === AttributeTypes::IMAGE) {
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

    private function normalizeCategories(Categories $categories)
    {
        $data = [];
        /** @var Category $category */
        foreach ($categories->all() as $category) {
            $data[] = $category->code();
        }

        return $data;
    }
}
