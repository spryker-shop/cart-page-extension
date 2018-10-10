<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface;

class CompanyWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCompanyUnitAddressClientInterface
     */
    public function getCompanyUnitAddressClient(): CompanyWidgetToCompanyUnitAddressClientInterface
    {
        return $this->getProvidedDependency(CompanyWidgetDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT_ADDRESS);
    }
}
