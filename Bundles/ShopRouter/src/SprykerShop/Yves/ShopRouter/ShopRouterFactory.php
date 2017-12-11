<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopRouter;

use Spryker\Shared\Application\Business\Routing\SilexRouter;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopRouter\Creator\ResourceCreatorHandler;
use SprykerShop\Yves\ShopRouter\Dependency\Client\ShopRouterToUrlStorageClientInterface;
use SprykerShop\Yves\ShopRouter\Generator\UrlGenerator;
use SprykerShop\Yves\ShopRouter\Mapper\UrlMapper;
use SprykerShop\Yves\ShopRouter\Merger\ParameterMerger;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class ShopRouterFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    public function getResourceCreatorPlugins()
    {
        return $this->getProvidedDependency(ShopRouterDependencyProvider::PLUGIN_RESOURCE_CREATORS);
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Mapper\UrlMapperInterface
     */
    public function createUrlMapper()
    {
        return new UrlMapper();
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Merger\ParameterMergerInterface
     */
    public function createParameterMerger()
    {
        return new ParameterMerger();
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Dependency\Client\ShopRouterToUrlStorageClientInterface
     */
    public function getUrlMatcher(): ShopRouterToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(ShopRouterDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return \Silex\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(ShopRouterDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function createSharedSilexRouter()
    {
        return new SilexRouter($this->getApplication());
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return \SprykerShop\Yves\ShopRouter\Generator\UrlGenerator
     */
    public function createUrlGenerator(RouteCollection $routeCollection, RequestContext $requestContext)
    {
        return new UrlGenerator($this->getApplication(), $routeCollection, $requestContext);
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Creator\ResourceCreatorHandlerInterface
     */
    public function createResourceCreatorHandler()
    {
        return new ResourceCreatorHandler($this->getResourceCreatorPlugins(), $this->getApplication());
    }
}
