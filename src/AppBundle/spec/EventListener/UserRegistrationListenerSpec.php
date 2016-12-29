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
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class UserRegistrationListenerSpec extends ObjectBehavior
{
    function let(
        Session $session,
        GeneratorInterface $verificationTokenGenerator,
        UrlGeneratorInterface $urlGenerator,
        ChannelContextInterface $channelContext,
        TranslatorInterface $translator
    ) {
        $this->beConstructedWith(
            $session,
            $verificationTokenGenerator,
            $urlGenerator,
            $channelContext,
            $translator
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRegistrationListener::class);
    }

    function it_adds_flash_message_with_verification_link(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CustomerInterface $customer,
        FlashBagInterface $flashBag,
        GeneratorInterface $verificationTokenGenerator,
        GenericEvent $event,
        Session $session,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        UserInterface $user
    ) {
        $event->getSubject()->willReturn($customer);
        $customer->getUser()->willReturn($user);

        $session->getFlashBag()->willReturn($flashBag);
        $channelContext->getChannel()->willReturn($channel);

        $channel->getHostname()->willReturn('localhost');

        $verificationTokenGenerator->generate()->willReturn('token');
        $user->setEmailVerificationToken('token')->shouldBeCalled();

        $urlGenerator
            ->generate('sylius_shop_user_verification', ['token' => 'token'])
            ->willReturn('/verification/?token=token')
        ;

        $translator->trans('sylius_demo.verification_link_flash', [
            '%url%' => 'http://localhost/verification/?token=token',
        ])->willReturn('To verify your email address - please visit http://localhost/?token=token');

        $flashBag
            ->add('success', 'To verify your email address - please visit http://localhost/?token=token')
            ->shouldBeCalled()
        ;

        $this->addVerificationLink($event);
    }

    function it_throws_exception_if_event_subject_is_not_customer(GenericEvent $event)
    {
        $event->getSubject()->willReturn('badObject');

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('addVerificationLink', [$event])
        ;
    }
}
