<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\FileWidget\Dependency\Client\FileWidgetToFileBridge;

class FileWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const FILE_CLIENT = 'FILE CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::FILE_CLIENT] = function (Container $container) {
            return new FileWidgetToFileBridge($container->getLocator()->fileManagerStorage()->client());
        };

        return $container;
    }
}
