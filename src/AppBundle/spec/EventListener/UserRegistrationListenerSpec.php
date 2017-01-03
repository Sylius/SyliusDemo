<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\EventListener;

use AppBundle\EventListener\UserRegistrationListener;
use AppBundle\Generator\FlashMessageGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class UserRegistrationListenerSpec extends ObjectBehavior
{
    function let(
        Session $session,
        GeneratorInterface $verificationTokenGenerator,
        FlashMessageGeneratorInterface $flashMessageGenerator
    ) {
        $this->beConstructedWith(
            $session,
            $verificationTokenGenerator,
            $flashMessageGenerator
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRegistrationListener::class);
    }

    function it_adds_flash_message_with_verification_link(
        FlashBagInterface $flashBag,
        FlashMessageGeneratorInterface $flashMessageGenerator,
        GeneratorInterface $verificationTokenGenerator,
        GenericEvent $event,
        Session $session,
        UserInterface $user
    ) {
        $event->getSubject()->willReturn($user);

        $session->getFlashBag()->willReturn($flashBag);

        $verificationTokenGenerator->generate()->willReturn('token');
        $user->setEmailVerificationToken('token')->shouldBeCalled();

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
