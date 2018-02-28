<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Domain\ProductMediaNormalizer;
use Akeneo\DataGenerator\Domain\ProductNormalizer;

/**
 * Write in a json file
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class JsonProductRepository implements ProductRepository
{
    /** @var string */
    private $productsDestFile;

    /** @var string|null */
    private $mediaDestFile;

    /** @var ProductMediaNormalizer */
    private $mediaNormalizer;

    /** @var ProductNormalizer */
    private $productNormalizer;

    /**
     * @param ProductNormalizer      $productNormalizer
     * @param ProductMediaNormalizer $mediaNormalizer
     * @param string                 $productsDestFile
     * @param string|null            $mediaDestFile
     */
    public function __construct(
        ProductNormalizer $productNormalizer,
        ProductMediaNormalizer $mediaNormalizer,
        string $productsDestFile,
        $mediaDestFile = null
    ) {
        $this->productNormalizer = $productNormalizer;
        $this->mediaNormalizer = $mediaNormalizer;
        $this->productsDestFile = $productsDestFile;
        $this->mediaDestFile = $mediaDestFile;
    }

    public function add(Product $product): void
    {
        $normalizedProduct = $this->productNormalizer->normalize($product);
        $this->writeAllProducts([$normalizedProduct]);

        $normalizedMedia = $this->mediaNormalizer->normalize($product);
        if (!empty($normalizedMedia)) {
            $this->writeAllMedia([$normalizedMedia]);
        }
    }

    public function bulkAdd(array $products): void
    {
        $normalizedProducts = [];
        $normalizedMedia = [];
        foreach ($products as $product) {
            $normalizedProduct = $this->productNormalizer->normalize($product);
            $normalizedProducts[] = $normalizedProduct;
            $media = $this->mediaNormalizer->normalize($product);
            if (null !== $media) {
                $normalizedMedia[$normalizedProduct['identifier']] = $media;
            }
        }

        $this->writeAllProducts($normalizedProducts);
        $this->writeAllMedia($normalizedMedia);
    }

    private function writeAllProducts(array $normalizedProducts): void
    {
        $currentContent = [];
        if (file_exists($this->productsDestFile)) {
            $currentContent = json_decode(file_get_contents($this->productsDestFile), true);
        }
        $data = array_merge($currentContent, $normalizedProducts);
        file_put_contents($this->productsDestFile, json_encode($data));
    }

    private function writeAllMedia(array $normalizedMedia): void
    {
        if (empty($normalizedMedia) || null === $this->mediaDestFile) {
            return;
        }

        $currentContent = [];
        if (file_exists($this->mediaDestFile)) {
            $currentContent = json_decode(file_get_contents($this->mediaDestFile), true);
        }
        $data = $currentContent + $normalizedMedia;
        file_put_contents($this->mediaDestFile, json_encode($data));
    }
}
