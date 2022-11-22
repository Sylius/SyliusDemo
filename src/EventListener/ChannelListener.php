<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ChannelListener
{
    public function __construct(private RequestStack $requestStack, private Router $router)
    {
    }

    public function preCreate(RequestEvent $event): void
    {
        if ('sylius_admin_channel_create' === $event->getRequest()->get('_route')) {

            /** @var Session $session */
            $session = $this->requestStack->getSession();
            $session->getFlashBag()->add('error', 'sylius_demo.channel.prevent_create');

            $url = $this->router->generate('sylius_admin_channel_index');
            $response = new RedirectResponse($url);

            $event->setResponse($response);
        }
    }
}
