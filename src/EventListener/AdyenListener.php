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

use Sylius\AdyenPlugin\Provider\AdyenClientProviderInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final readonly class AdyenListener
{
    public function __construct(private RequestStack $requestStack, private Router $router)
    {
    }

    public function preAccess(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = (string) $request->get('_route');

        $isAdyenLogPage = str_starts_with($route, 'sylius_adyen_admin_log');
        $isAdyenPaymentMethodCreate = 'sylius_admin_payment_method_create' === $route
            && AdyenClientProviderInterface::FACTORY_NAME === $request->get('factory');

        if (!$isAdyenLogPage && !$isAdyenPaymentMethodCreate) {
            return;
        }

        /** @var Session $session */
        $session = $this->requestStack->getSession();
        $session->getFlashBag()->add('error', 'sylius_demo.payment_method.adyen.prevent_access');

        $url = $this->router->generate('sylius_admin_payment_method_index');
        $event->setResponse(new RedirectResponse($url));
    }
}
