<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Generator\FlashMessageGeneratorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Webmozart\Assert\Assert;

final class UserRegistrationListener
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var FlashMessageGeneratorInterface
     */
    private $flashMessageGenerator;

    /**
     * @param Session $session
     * @param FlashMessageGeneratorInterface $flashMessageGenerator
     */
    public function __construct(
        Session $session,
        FlashMessageGeneratorInterface $flashMessageGenerator
    ) {
        $this->session = $session;
        $this->flashMessageGenerator = $flashMessageGenerator;
    }

    /**
     * @param GenericEvent $event
     */
    public function addVerificationLink(GenericEvent $event): void
    {
        /** @var UserInterface $subject */
        $subject = $event->getSubject();
        Assert::isInstanceOf($subject, UserInterface::class);

        $token = $subject->getEmailVerificationToken();
        $message = $this->flashMessageGenerator->generate($token);

        $this->session->getFlashBag()->add('success', $message);
    }
}
