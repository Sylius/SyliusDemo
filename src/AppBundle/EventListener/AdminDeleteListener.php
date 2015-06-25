<?php

namespace AppBundle\EventListener;

use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Mateusz Zalewski <mateusz.p.zalewski@gmail.com>
 */
class AdminDeleteListener
{
    /**
     * @var Session
     */
    private $session;

    /**
     * Constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param GenericEvent $event
     */
    public function preDelete(GenericEvent $event)
    {
        if (!($subject = $event->getSubject()) instanceof CustomerInterface) {
            throw new UnexpectedTypeException($subject, 'Sylius\Component\Core\Model\CustomerInterface');
        }

        if ('sylius@example.com' === $subject->getEmail() && $subject->getUser()->hasRole('ROLE_SYLIUS_ADMIN')) {
            $this->session->getFlashBag()->add("error", "sylius_demo.account.prevent_delete");

            $event->stopPropagation();
        }
    }
}
