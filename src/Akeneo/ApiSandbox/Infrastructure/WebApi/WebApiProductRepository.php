<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\ApiSandbox\Domain\Model\AttributeTypes;
use Akeneo\ApiSandbox\Domain\Model\Category;
use Akeneo\ApiSandbox\Domain\Model\Product;
use Akeneo\ApiSandbox\Domain\Model\ProductCategories;
use Akeneo\ApiSandbox\Domain\Model\ProductRepository;
use Akeneo\ApiSandbox\Domain\Model\ProductValue;
use Akeneo\ApiSandbox\Domain\Model\ProductValues;

class WebApiProductRepository implements ProductRepository
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function add(Product $product)
    {
        $productDataWithoutImages = [
            'identifier' => $product->getIdentifier(),
            'family' => $product->getFamily()->getCode(),
            'values' => $this->normalizeNonImageValues($product->getValues()),
            'categories' => $this->normalizeCategories($product->getCategories())
        ];
        $this->client->getProductApi()->upsert($product->getIdentifier(), $productDataWithoutImages);

        $productAttributeImages = $this->normalizeImageValues($product->getValues());
        foreach ($productAttributeImages as $attributeCode => $productImages) {
            foreach ($productImages as $productImage) {
                $this->client->getProductMediaFileApi()->create(
                    $productImage['data'],
                    [
                        'identifier' => $product->getIdentifier(),
                        'attribute' => $attributeCode,
                        'locale' => $productImage['locale'],
                        'scope' => $productImage['scope'],
                    ]
                );
            }
        }
    }

    private function normalizeNonImageValues(ProductValues $values)
    {
        $data = [];
        /** @var ProductValue $value */
        foreach ($values->all() as $value) {
            if ($value->getAttribute()->getType() !== AttributeTypes::IMAGE) {
                if (!isset($data[$value->getAttribute()->getCode()])) {
                    $data[$value->getAttribute()->getCode()] = [];
                }
                $data[$value->getAttribute()->getCode()][] = [
                    'data' => $value->getData(),
                    'locale' => $value->getLocale(),
                    'scope' => $value->getChannel(),
                ];
            }
        }

        return $data;
    }

    private function normalizeImageValues(ProductValues $values)
    {
        $data = [];
        /** @var ProductValue $value */
        foreach ($values->all() as $value) {
            if ($value->getAttribute()->getType() === AttributeTypes::IMAGE) {
                if (!isset($data[$value->getAttribute()->getCode()])) {
                    $data[$value->getAttribute()->getCode()] = [];
                }
                $data[$value->getAttribute()->getCode()][] = [
                    'data' => $value->getData(),
                    'locale' => $value->getLocale(),
                    'scope' => $value->getChannel(),
                ];
            }
        }

        return $data;
    }

    private function normalizeCategories(ProductCategories $categories)
    {
        $data = [];
        /** @var Category $category */
        foreach ($categories->all() as $category) {
            $data[] = $category->getCode();
        }

        return $data;
    }
}
