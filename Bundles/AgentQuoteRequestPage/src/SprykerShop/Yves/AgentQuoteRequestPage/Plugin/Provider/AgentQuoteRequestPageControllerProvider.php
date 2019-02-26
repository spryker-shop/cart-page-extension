<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class AgentQuoteRequestPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_AGENT_QUOTE_REQUEST = 'agent/quote-request';
    public const ROUTE_AGENT_QUOTE_REQUEST_CANCEL = 'agent/quote-request/cancel';
    public const ROUTE_AGENT_QUOTE_REQUEST_DETAILS = 'agent/quote-request/details';
    public const ROUTE_AGENT_QUOTE_REQUEST_START_EDIT = 'agent/quote-request/start-edit';
    public const ROUTE_AGENT_QUOTE_REQUEST_EDIT = 'agent/quote-request/edit';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addAgentQuoteRequestRoute()
            ->addAgentQuoteRequestCancelRoute()
            ->addQuoteRequestDetailsRoute()
            ->addQuoteRequestStartEditRoute()
            ->addQuoteRequestEditRoute();
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestViewController::indexAction()
     *
     * @return $this
     */
    protected function addAgentQuoteRequestRoute()
    {
        $this->createController('/{agent}/quote-request', static::ROUTE_AGENT_QUOTE_REQUEST, 'AgentQuoteRequestPage', 'AgentQuoteRequestView')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestDeleteController::cancelAction()
     *
     * @return $this
     */
    protected function addAgentQuoteRequestCancelRoute()
    {
        $this->createController('/{agent}/quote-request/cancel/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_CANCEL, 'AgentQuoteRequestPage', 'AgentQuoteRequestDelete', 'cancel')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestViewController::detailsAction()
     *
     * @return $this
     */
    protected function addQuoteRequestDetailsRoute()
    {
        $this->createController('/{agent}/quote-request/details/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_DETAILS, 'AgentQuoteRequestPage', 'AgentQuoteRequestView', 'details')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditController::startEditAction()
     *
     * @return $this
     */
    protected function addQuoteRequestStartEditRoute()
    {
        $this->createController('/{agent}/quote-request/start-edit/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_START_EDIT, 'AgentQuoteRequestPage', 'AgentQuoteRequestEdit', 'startEdit')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\AgentQuoteRequestPage\Controller\AgentQuoteRequestEditController::editAction()
     *
     * @return $this
     */
    protected function addQuoteRequestEditRoute()
    {
        $this->createController('/{agent}/quote-request/edit/{quoteRequestReference}', static::ROUTE_AGENT_QUOTE_REQUEST_EDIT, 'AgentQuoteRequestPage', 'AgentQuoteRequestEdit', 'edit')
            ->assert('agent', $this->getAllowedLocalesPattern() . 'agent|agent')
            ->value('agent', 'agent')
            ->assert('quoteRequestReference', static::QUOTE_REQUEST_REFERENCE_REGEX);

        return $this;
    }
}
