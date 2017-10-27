<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\ProductSalePage;

use SprykerShop\Client\ProductSalePage\Plugin\Elasticsearch\Query\SaleSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\RawCatalogSearchResultFormatterPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductLabel\ProductLabelClient;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\LocalizedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\PaginatedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\StoreQueryExpanderPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\SortedResultFormatterPlugin;
use Spryker\Client\Search\SearchClient;
use Spryker\Shared\Kernel\Store;

class ProductSalePageDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_SEARCH = 'CLIENT_SEARCH';
    const CLIENT_PRODUCT_LABEL = 'CLIENT_PRODUCT_LABEL';
    const SALE_SEARCH_QUERY_PLUGIN = 'SALE_SEARCH_QUERY_PLUGIN';
    const SALE_SEARCH_QUERY_EXPANDER_PLUGINS = 'SALE_SEARCH_QUERY_EXPANDER_PLUGINS';
    const SALE_SEARCH_RESULT_FORMATTER_PLUGINS = 'SALE_SEARCH_RESULT_FORMATTER_PLUGINS';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addSearchClient($container);
        $container = $this->addProductLabelClient($container);
        $container = $this->addSaleSearchQueryPlugin($container);
        $container = $this->addSaleSearchQueryExpanderPlugins($container);
        $container = $this->addSaleSearchResultFormatterPlugins($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container[self::CLIENT_SEARCH] = function () {
            return new SearchClient();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductLabelClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_LABEL] = function () {
            return new ProductLabelClient();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSaleSearchQueryPlugin(Container $container)
    {
        $container[self::SALE_SEARCH_QUERY_PLUGIN] = function () {
            return new SaleSearchQueryPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSaleSearchQueryExpanderPlugins(Container $container)
    {
        $container[self::SALE_SEARCH_QUERY_EXPANDER_PLUGINS] = function () {
            return [
                new StoreQueryExpanderPlugin(),
                new LocalizedQueryExpanderPlugin(),
                new FacetQueryExpanderPlugin(),
                new SortedQueryExpanderPlugin(),
                new PaginatedQueryExpanderPlugin(),
            ];
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSaleSearchResultFormatterPlugins(Container$container)
    {
        $container[self::SALE_SEARCH_RESULT_FORMATTER_PLUGINS] = function () {
            return [
                new FacetResultFormatterPlugin(),
                new SortedResultFormatterPlugin(),
                new PaginatedResultFormatterPlugin(),
                new RawCatalogSearchResultFormatterPlugin(),
            ];
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container$container)
    {
        $container[self::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }
}