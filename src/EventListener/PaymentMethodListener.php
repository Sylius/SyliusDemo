<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventListener;

use Sylius\MolliePlugin\Payum\Factory\MollieGatewayFactory;
use Sylius\MolliePlugin\Payum\Factory\MollieSubscriptionGatewayFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class PaymentMethodListener
{
    public function __construct(private RequestStack $requestStack, private Router $router)
    {
    }

    public function preCreate(RequestEvent $event): void
    {
        if ('sylius_admin_payment_method_create' !== $event->getRequest()->get('_route')) {
            return;
        }

        if (
            $event->getRequest()->get('factory') !== MollieSubscriptionGatewayFactory::FACTORY_NAME &&
            $event->getRequest()->get('factory') !== MollieGatewayFactory::FACTORY_NAME
        ) {
            return;
        }

        /** @var Session $session */
        $session = $this->requestStack->getSession();
        $session->getFlashBag()->add('error', 'sylius_demo.payment_method.mollie.prevent_create');

        $url = $this->router->generate('sylius_admin_payment_method_index');
        $response = new RedirectResponse($url);

        $event->setResponse($response);
    }
}
