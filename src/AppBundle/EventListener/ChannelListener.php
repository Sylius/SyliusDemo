<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

final class ChannelListener
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param Session $session
     */
    public function __construct(Session $session, Router $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function preCreate(GetResponseEvent $event)
    {
        if ('sylius_admin_channel_create' === $event->getRequest()->get('_route')) {
            $this->session->getFlashBag()->add('error', 'sylius_demo.channel.prevent_create');

            $url = $this->router->generate('sylius_admin_channel_index');
            $response = new RedirectResponse($url);

            $event->setResponse($response);
        }
    }
}
