<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerAuthenticationSuccessHandler extends AbstractPlugin implements AuthenticationSuccessHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->setCustomerSession($token->getUser()->getCustomerTransfer());

        $response = $this->createRedirectResponse($request);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function setCustomerSession(CustomerTransfer $customer)
    {
        $this->getFactory()
            ->getCustomerClient()
            ->setCustomer($customer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request)
    {
        $targetUrl = $this->determineTargetUrl($request);

        $response = $this->getFactory()->createRedirectResponse($targetUrl);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function determineTargetUrl($request)
    {
        if ($request->headers->has('Referer')) {
            return $request->headers->get('Referer');
        }

        return HomePageControllerProvider::ROUTE_HOME;
    }
}