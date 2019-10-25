<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouper;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouperInterface;

/**
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetConfig getConfig()
 */
class SalesConfigurableBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesConfigurableBundleWidget\Grouper\SalesOrderConfiguredBundleGrouperInterface
     */
    public function createSalesOrderConfiguredBundleGrouper(): SalesOrderConfiguredBundleGrouperInterface
    {
        return new SalesOrderConfiguredBundleGrouper();
    }
}
