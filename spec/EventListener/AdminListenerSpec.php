<?php

namespace spec\App\EventListener;

use App\EventListener\AdminListener;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class AdminListenerSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AdminListener::class);
    }

    function it_is_triggered_pre_delete(
        GenericEvent $event,
        AdminUserInterface $adminUser,
        FlashBagInterface $flashBag,
        RequestStack $requestStack,
        Session $session
    ) {
        $event->getSubject()->willReturn($adminUser);

        $adminUser->getEmailCanonical()->willReturn('sylius@example.com');

        $requestStack->getSession()->willReturn($session);
        $session->getFlashBag()->willReturn($flashBag);
        $flashBag->add("error", "sylius_demo.admin_account.prevent_delete")->shouldBeCalled();

        $event->stopPropagation()->shouldBeCalled();

        $this->preDelete($event);
    }

    function it_throws_exception_if_event_subject_is_not_customer_during_pre_delete(GenericEvent $event)
    {
        $event->getSubject()->willReturn('badObject');

        $this->shouldThrow(new UnexpectedTypeException('badObject', 'Sylius\Component\Core\Model\AdminUserInterface'))->during('preDelete', array($event));
    }

    function it_is_triggered_pre_update(
        GenericEvent $event,
        AdminUserInterface $adminUser,
        FlashBagInterface $flashBag,
        RequestStack $requestStack,
        Session $session
    ) {
        $event->getSubject()->willReturn($adminUser);

        $adminUser->getEmailCanonical()->willReturn('sylius@example.com');

        $requestStack->getSession()->willReturn($session);
        $session->getFlashBag()->willReturn($flashBag);

        $flashBag->add('error', 'sylius_demo.admin_account.prevent_edit')->shouldBeCalled();
        $event->stopPropagation()->shouldBeCalled();

        $this->preUpdate($event);
    }

    function it_throws_exception_if_event_subject_is_not_customer_during_pre_update(GenericEvent $event)
    {
        $event->getSubject()->willReturn('badObject');
        $this->shouldThrow(new UnexpectedTypeException('badObject', 'Sylius\Component\Core\Model\AdminUserInterface'))->during('preUpdate', array($event));
    }
}
