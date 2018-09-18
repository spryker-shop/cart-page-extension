<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartPermissionGroupWidgetPluginInterface;
use SprykerShop\Yves\SharedCartWidget\Widget\SharedCartPermissionGroupWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\SharedCartPermissionGroupWidget instead.
 *
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class SharedCartPermissionGroupWidgetPlugin extends AbstractWidgetPlugin implements SharedCartPermissionGroupWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new SharedCartPermissionGroupWidget($quoteTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getAccessType(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->getFactory()
            ->getSharedCartClient()
            ->getQuoteAccessLevel($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return SharedCartPermissionGroupWidget::getTemplate();
    }
}
