<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\ViewDataTransformer;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Throwable;

class ViewDataTransformer implements ViewDataTransformerInterface
{
    protected const KEY_COLUMNS = 'columns';
    protected const PATH_DELIMITER = '.';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[] $additionalColumnPlugins
     *
     * @return array
     */
    public function transformProductData(array $productConcreteTransfers, array $additionalColumnPlugins): array
    {
        $products = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $sku = $productConcreteTransfer->getSku();
            $products[$sku] = $productConcreteTransfer->toArray(true, true);
            $products[$sku][static::KEY_COLUMNS] = $this->flattenColumns($productConcreteTransfer, $additionalColumnPlugins);
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[] $additionalColumnPlugins
     *
     * @return array
     */
    protected function flattenColumns(ProductConcreteTransfer $productConcreteTransfer, array $additionalColumnPlugins)
    {
        $columns = [];

        foreach ($additionalColumnPlugins as $additionalColumnPlugin) {
            $path = $additionalColumnPlugin->getDataPath();
            $columns[$path] = $this->getDataByPath($productConcreteTransfer, $path);
        }

        return $columns;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string $path
     *
     * @return mixed|null
     */
    protected function getDataByPath(ProductConcreteTransfer $productConcreteTransfer, string $path)
    {
        $path = explode(static::PATH_DELIMITER, $path);

        try {
            $data = array_reduce(
                $path,
                function ($object, $property) {
                    return is_object($property) ? $object->{$property} : $object[$property];
                },
                $productConcreteTransfer
            );
        } catch (Throwable $exception) {
            $data = null;
        }

        return $data;
    }
}
