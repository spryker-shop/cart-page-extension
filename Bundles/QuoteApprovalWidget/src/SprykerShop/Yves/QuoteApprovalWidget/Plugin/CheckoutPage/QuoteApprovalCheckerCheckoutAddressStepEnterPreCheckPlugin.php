<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApprovalCheckerCheckoutAddressStepEnterPreCheckPlugin extends AbstractPlugin implements CheckoutAddressStepEnterPreCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Makes a call to quote approval client to determine if step is accessible.
     * - Returns true if quote is locked and has at least one quote approval not in status declined, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteApprovalClient()
            ->checkAddressStepAccessibility($quoteTransfer);
    }
}
