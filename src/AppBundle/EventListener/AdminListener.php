<?php

namespace AppBundle\EventListener;

use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Mateusz Zalewski <mateusz.p.zalewski@gmail.com>
 */
final class AdminListener
{
    /**
     * @var Session
     */
    private $session;

    /**
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
        $subject = $subject = $event->getSubject();

        if (!$subject instanceof AdminUserInterface) {
            throw new UnexpectedTypeException($subject, AdminUserInterface::class);
        }

        if ('sylius@example.com' === $subject->getEmailCanonical()) {
            $this->session->getFlashBag()->add('error', 'sylius_demo.admin_account.prevent_delete');
            $event->stopPropagation();
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function preUpdate(GenericEvent $event)
    {
        $subject = $subject = $event->getSubject();

        if (!$subject instanceof AdminUserInterface) {
            throw new UnexpectedTypeException($subject, AdminUserInterface::class);
        }

        if ('sylius@example.com' === $subject->getEmailCanonical()) {
            $this->session->getFlashBag()->add('error', 'sylius_demo.admin_account.prevent_edit');
            $event->stopPropagation();
        }
    }
}
