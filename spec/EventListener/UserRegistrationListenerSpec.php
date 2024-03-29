<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\App\EventListener;

use App\EventListener\UserRegistrationListener;
use App\Generator\FlashMessageGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class UserRegistrationListenerSpec extends ObjectBehavior
{
    function let(
        RequestStack $requestStack,
        FlashMessageGeneratorInterface $flashMessageGenerator
    ) {
        $this->beConstructedWith($requestStack, $flashMessageGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRegistrationListener::class);
    }

    function it_adds_flash_message_with_verification_link(
        FlashBagInterface $flashBag,
        FlashMessageGeneratorInterface $flashMessageGenerator,
        GenericEvent $event,
        RequestStack $requestStack,
        Session $session,
        UserInterface $user
    ) {
        $event->getSubject()->willReturn($user);

        $requestStack->getSession()->willReturn($session);

        $session->getFlashBag()->willReturn($flashBag);

        $user->getEmailVerificationToken()->willReturn('token');

        $flashMessageGenerator
            ->generate('token')
            ->willReturn('To verify your email address - please visit http://localhost/?token=token')
        ;

        $flashBag
            ->add('success', 'To verify your email address - please visit http://localhost/?token=token')
            ->shouldBeCalled()
        ;

        $this->addVerificationLink($event);
    }

    function it_throws_exception_if_event_subject_is_not_user(GenericEvent $event)
    {
        $event->getSubject()->willReturn('badObject');

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('addVerificationLink', [$event])
        ;
    }
}
