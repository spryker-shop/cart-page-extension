<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsPage\ResourceCreator\PageResourceCreator;

class CmsPageFactory extends AbstractFactory
{

    /**
     * @return \SprykerShop\Yves\CmsPage\ResourceCreator\PageResourceCreator
     */
    public function createPageResourceCreator()
    {
        return new PageResourceCreator();
    }

    /**
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    public function getCmsTwigRendererPlugin()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CMS_TWIG_CONTENT_RENDERER_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Cms\CmsClientInterface
     */
    public function getCmsClient()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_CMS);
    }

    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::STORE);
    }

}