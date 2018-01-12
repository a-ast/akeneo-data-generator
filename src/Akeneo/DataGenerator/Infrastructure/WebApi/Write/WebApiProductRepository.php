<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\ProductMediaNormalizer;
use Akeneo\DataGenerator\Domain\ProductNormalizer;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;

class WebApiProductRepository implements ProductRepository
{
    /** @var AkeneoPimClientInterface */
    private $client;

    /** @var ProductNormalizer */
    private $productNormalizer;

    /** @var ProductMediaNormalizer */
    private $mediaNormalizer;

    /**
     * @param AkeneoPimClientInterface $client
     * @param ProductNormalizer $productNormalizer
     * @param ProductMediaNormalizer $mediaNormalizer
     */
    public function __construct(
        AkeneoPimClientInterface $client,
        ProductNormalizer $productNormalizer,
        ProductMediaNormalizer $mediaNormalizer
    ) {
        $this->client = $client;
        $this->productNormalizer = $productNormalizer;
        $this->mediaNormalizer = $mediaNormalizer;
    }

    public function add(Product $product): void
    {
        $productDataWithoutImages = $this->productNormalizer->normalize($product);
        $this->client->getProductApi()->upsert($product->identifier(), $productDataWithoutImages);
        $this->createMediaFiles($product);
    }

    public function bulkAdd(array $products): void
    {
        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = $this->productNormalizer->normalize($product);
        }
        $this->client->getProductApi()->upsertList($productsData);

        foreach ($products as $product) {
            $this->createMediaFiles($product);
        }
    }

    private function createMediaFiles(Product $product): void
    {
        $productAttributeImages = $this->mediaNormalizer->normalize($product);
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
}
