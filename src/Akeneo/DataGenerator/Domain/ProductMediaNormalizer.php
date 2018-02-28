<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\DataGenerator\Domain\Model\Product;

/**
 * Product media normalizer
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductMediaNormalizer
{
    public function normalize(Product $product): ?array
    {
        $values = $product->values();

        $data = [];
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
}
