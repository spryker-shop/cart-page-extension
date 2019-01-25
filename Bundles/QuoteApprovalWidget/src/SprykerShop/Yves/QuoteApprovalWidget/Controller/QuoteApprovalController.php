<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Controller;

use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory
 */
class QuoteApprovalController extends AbstractController
{
    protected const PARAM_REFERER = 'referer';
    protected const MESSAGE_QUOTE_APPROVAL_APPROVE_ERROR = 'quote_approval_widget.cart.error_approve_message';
    protected const MESSAGE_QUOTE_APPROVAL_DECLINE_ERROR = 'quote_approval_widget.cart.error_decline_message';
    protected const MESSAGE_QUOTE_APPROVAL_CANCEL_ERROR = 'quote_approval_widget.cart.error_cancel_message';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createQuoteApprovalAction(Request $request): RedirectResponse
    {
        $quoteApproveRequestForm = $this->getFactory()
            ->createQuoteApproveRequestForm(
                $this->getFactory()->getQuoteClient()->getQuote(),
                $this->getLocale()
            );

        $quoteApproveRequestForm->handleRequest($request);

        if ($quoteApproveRequestForm->isSubmitted() && $quoteApproveRequestForm->isValid()) {
            $quoteApprovalResponseTransfer = $this->getFactory()->getQuoteApprovalClient()->createQuoteApproval($quoteApproveRequestForm->getData());

            $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);
        }

        return $this->redirectBack($request);
    }

    /**
     * @param int $idQuoteApproval
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeQuoteApprovalAction(int $idQuoteApproval, Request $request): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer) {
            $customerTransfer->requireCompanyUserTransfer();

            $quoteApprovalRemoveRequestTransfer = new QuoteApprovalRemoveRequestTransfer();

            $quoteApprovalRemoveRequestTransfer->setIdQuoteApproval($idQuoteApproval)
                ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

            $quoteApprovalResponseTransfer = $this->getFactory()
                ->getQuoteApprovalClient()
                ->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);

            $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);
        }

        return $this->redirectBack($request);
    }

    /**
     * @param string $key
     * @param array $params
     *
     * @return string
     */
    protected function getTranslatedMessage(string $key, array $params = []): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate($key, $this->getLocale(), $params);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer
     *
     * @return void
     */
    protected function addMessagesFromQuoteApprovalResponse(QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer)
    {
        foreach ($quoteApprovalResponseTransfer->getMessages() as $messageTransfer) {
            $translatedMessage = $this->getTranslatedMessage($messageTransfer->getValue(), $messageTransfer->getParameters());

            if ($quoteApprovalResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage($translatedMessage);

                continue;
            }

            $this->addErrorMessage($translatedMessage);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approveAction(Request $request, int $idQuoteApproval): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectBack($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setFkCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->approveQuoteApproval($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_QUOTE_APPROVAL_APPROVE_ERROR);

            return $this->redirectBack($request);
        }

        $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);

        return $this->redirectBack($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idQuoteApproval
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function declineAction(Request $request, int $idQuoteApproval): RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectBack($request);
        }

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setIdQuoteApproval($idQuoteApproval)
            ->setFkCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $quoteApprovalResponseTransfer = $this->getFactory()
            ->getQuoteApprovalClient()
            ->declineQuoteApproval($quoteApprovalRequestTransfer);

        if (!$quoteApprovalResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_QUOTE_APPROVAL_DECLINE_ERROR);

            return $this->redirectBack($request);
        }

        $this->addMessagesFromQuoteApprovalResponse($quoteApprovalResponseTransfer);

        return $this->redirectBack($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectBack(Request $request): RedirectResponse
    {
        $referer = $request->headers->get(static::PARAM_REFERER);

        return $this->redirectResponseExternal($referer);
    }
}
