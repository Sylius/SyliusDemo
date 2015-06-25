<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminUpdateListenerSpec extends ObjectBehavior
{
    function let(Session $session)
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\AdminUpdateListener');
    }

    function it_is_triggered_pre_update(
        GenericEvent $event,
        CustomerInterface $customer,
        UserInterface $user,
        FlashBagInterface $flashBag,
        $session
    ) {
        $event->getSubject()->willReturn($customer);

        $customer->getUser()->willReturn($user);
        $user->hasRole('ROLE_SYLIUS_ADMIN')->willReturn(true);
        $user->isEnabled()->willReturn(false);

        $customer->getEmail()->willReturn('sylius@example.com');

        $session->getFlashBag()->willReturn($flashBag);
        $flashBag->add('error', 'sylius_demo.account.prevent_disable')->shouldBeCalled();

        $event->stopPropagation()->shouldBeCalled();

        $this->preUpdate($event);
    }

    function it_throws_exception_if_event_subject_is_not_customer(GenericEvent $event)
    {
        $event->getSubject()->willReturn('badObject');

        $this->shouldThrow(new UnexpectedTypeException('badObject', 'Sylius\Component\Core\Model\CustomerInterface'))->during('preUpdate', array($event));
    }
}
