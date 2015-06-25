<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminDeleteListenerSpec extends ObjectBehavior
{
    function let(Session $session)
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\AdminDeleteListener');
    }

    function it_is_triggered_pre_delete(
        GenericEvent $event,
        CustomerInterface $customer,
        UserInterface $user,
        FlashBagInterface $flashBag,
        $session
    ) {
        $event->getSubject()->willReturn($customer);

        $customer->getUser()->willReturn($user);
        $user->hasRole('ROLE_ADMIN')->willReturn(true);

        $customer->getEmail()->willReturn('sylius@example.com');

        $session->getFlashBag()->willReturn($flashBag);
        $flashBag->add("error", "You cannot delete administrator of Sylius Demo!")->shouldBeCalled();

        $event->stopPropagation()->shouldBeCalled();

        $this->preDelete($event);
    }

    function it_throws_exception_if_event_subject_is_not_customer(GenericEvent $event)
    {
        $event->getSubject()->willReturn('badObject');

        $this->shouldThrow(new UnexpectedTypeException('badObject', 'Sylius\Component\Core\Model\CustomerInterface'))->during('preDelete', array($event));
    }
}
