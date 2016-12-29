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

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class UserRegistrationListener
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var GeneratorInterface
     */
    private $verificationTokenGenerator;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param Session $session
     * @param GeneratorInterface $verificationTokenGenerator
     * @param UrlGeneratorInterface $urlGenerator
     * @param ChannelContextInterface $channelContext
     * @param TranslatorInterface $translator
     */
    public function __construct(
        Session $session,
        GeneratorInterface $verificationTokenGenerator,
        UrlGeneratorInterface $urlGenerator,
        ChannelContextInterface $channelContext,
        TranslatorInterface $translator
    ) {
        $this->session = $session;
        $this->verificationTokenGenerator = $verificationTokenGenerator;
        $this->urlGenerator = $urlGenerator;
        $this->channelContext = $channelContext;
        $this->translator = $translator;
    }

    /**
     * @param GenericEvent $event
     */
    public function addVerificationLink(GenericEvent $event)
    {
        /** @var CustomerInterface $subject */
        $subject = $event->getSubject();
        Assert::isInstanceOf($subject, CustomerInterface::class);

        $user = $subject->getUser();
        $token = $this->verificationTokenGenerator->generate();
        $user->setEmailVerificationToken($token);

        $url = $this->urlGenerator->generate('sylius_shop_user_verification', ['token' => $token]);
        $message = $this->translator->trans('sylius_demo.verification_link_flash', [
            '%url%' => 'http://'.$this->channelContext->getChannel()->getHostname().$url,
        ]);

        $this->session->getFlashBag()->add('success', $message);
    }
}
