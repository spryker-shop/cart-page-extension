<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Shared\Application\Application;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Application as SilexApplication;
use Spryker\Yves\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjector;

abstract class YvesBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var \Spryker\Yves\Application\ApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @var \Spryker\Shared\Application\Application
     */
    protected $sprykerApplication;

    public function __construct()
    {
        $this->serviceContainer
            = $this->application
            = new SilexApplication();

        $this->sprykerApplication = new Application($this->serviceContainer);
        $this->config = new ShopApplicationConfig();
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    public function boot()
    {
        $this->registerServiceProviders();

        $this->setupApplication();

        $this->registerRouters();

        $this->registerControllerProviders();

        $this->application->boot();
        $this->sprykerApplication->boot();

        return $this->sprykerApplication;
    }

    /**
     * @return void
     */
    protected function setupApplication(): void
    {
        foreach ($this->getApplicationPlugins() as $applicationPlugin) {
            $this->sprykerApplication->registerApplicationPlugin($applicationPlugin);
        }
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideExternalDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container): Container
    {
        $container = $dependencyProvider->provideDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function injectExternalDependencies(DependencyInjector $dependencyInjector, Container $container): Container
    {
        $container = $dependencyInjector->injectDependencies($container);

        return $container;
    }

    /**
     * @return void
     */
    abstract protected function registerServiceProviders();

    /**
     * @return void
     */
    abstract protected function registerRouters();

    /**
     * @return void
     */
    abstract protected function registerControllerProviders();
}
