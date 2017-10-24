<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\CatalogPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CatalogPage\Dependency\Plugin\CmsBlockWidget\CatalogCmsBlockWidgetPluginInterface;

class CatalogCmsBlockWidgetPlugin extends AbstractWidgetPlugin implements CatalogCmsBlockWidgetPluginInterface
{

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function initialize(int $idCategory): void
    {
        $this->addParameter('idCategory', $idCategory);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CmsBlockWidget/_catalog-page/catalog-cms-block.twig';
    }

}