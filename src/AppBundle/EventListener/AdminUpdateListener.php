<?php

namespace AppBundle\EventListener;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class AdminUpdateListener
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
    public function preUpdate(GenericEvent $event)
    {
        if (!($subject = $event->getSubject()) instanceof CustomerInterface) {
            throw new UnexpectedTypeException($subject, 'Sylius\Component\Core\Model\CustomerInterface');
        }

        if ('sylius@example.com' === $subject->getEmail()) {
            $this->session->getFlashBag()->add('error', 'sylius_demo.account.prevent_edit');

            $event->stopPropagation();
        }
    }
}
