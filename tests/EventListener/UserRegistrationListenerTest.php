<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\EventListener;

use App\EventListener\UserRegistrationListener;
use App\Generator\FlashMessageGeneratorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class UserRegistrationListenerTest extends TestCase
{
    private RequestStack&MockObject $requestStack;

    private FlashMessageGeneratorInterface&MockObject $flashMessageGenerator;

    private UserRegistrationListener $listener;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->flashMessageGenerator = $this->createMock(FlashMessageGeneratorInterface::class);
        $this->listener = new UserRegistrationListener($this->requestStack, $this->flashMessageGenerator);
    }

    public function testItAddsFlashMessageWithVerificationLink(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getEmailVerificationToken')->willReturn('token');

        $event = $this->createMock(GenericEvent::class);
        $event->method('getSubject')->willReturn($user);

        $flashBag = $this->createMock(FlashBagInterface::class);
        $session = $this->createMock(Session::class);
        $session->method('getFlashBag')->willReturn($flashBag);

        $this->requestStack->method('getSession')->willReturn($session);

        $this->flashMessageGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('token')
            ->willReturn('To verify your email address - please visit http://localhost/?token=token');

        $flashBag
            ->expects(self::once())
            ->method('add')
            ->with('success', 'To verify your email address - please visit http://localhost/?token=token');

        $this->listener->addVerificationLink($event);
    }

    public function testItThrowsExceptionIfEventSubjectIsNotUser(): void
    {
        $event = $this->createMock(GenericEvent::class);
        $event->method('getSubject')->willReturn('badObject');

        $this->expectException(\InvalidArgumentException::class);

        $this->listener->addVerificationLink($event);
    }
}
