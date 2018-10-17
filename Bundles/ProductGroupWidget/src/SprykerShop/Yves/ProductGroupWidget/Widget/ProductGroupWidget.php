<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductGroupWidget\ProductGroupWidgetFactory getFactory()
 */
class ProductGroupWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        $this->addParameter('productGroupItems', $this->getProductGroups($idProductAbstract))
            ->addParameter('idProductAbstract', $idProductAbstract);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductGroupWidget';
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductGroupWidget/views/product-group/product-group.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getProductGroups(int $idProductAbstract): array
    {
        $productGroup = $this->getFactory()->getProductGroupStorageClient()->findProductGroupItemsByIdProductAbstract($idProductAbstract);
        $productViewTransfers = [];
        $productStorageClient = $this->getFactory()->getProductStorageClient();

        foreach ($productGroup->getGroupProductAbstractIds() as $idProductAbstract) {
            $productData = $productStorageClient->findProductAbstractStorageData($idProductAbstract, $this->getLocale());
            if (!$productData) {
                continue;
            }

            $productViewTransfers[] = $productStorageClient->mapProductStorageData($productData, $this->getLocale());
        }

        return $productViewTransfers;
    }
}