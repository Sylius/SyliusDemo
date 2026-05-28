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

use App\EventListener\AdminListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class AdminListenerTest extends TestCase
{
    private RequestStack&MockObject $requestStack;

    private AdminListener $listener;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->listener = new AdminListener($this->requestStack);
    }

    public function testItIsTriggeredPreDelete(): void
    {
        $event = $this->createMock(GenericEvent::class);
        $adminUser = $this->createMock(AdminUserInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $session = $this->createMock(Session::class);

        $event->method('getSubject')->willReturn($adminUser);
        $adminUser->method('getEmailCanonical')->willReturn('sylius@example.com');
        $session->method('getFlashBag')->willReturn($flashBag);
        $this->requestStack->method('getSession')->willReturn($session);

        $flashBag
            ->expects(self::once())
            ->method('add')
            ->with('error', 'sylius_demo.admin_account.prevent_delete');

        $event->expects(self::once())->method('stopPropagation');

        $this->listener->preDelete($event);
    }

    public function testItThrowsExceptionIfEventSubjectIsNotAdminUserDuringPreDelete(): void
    {
        $event = $this->createMock(GenericEvent::class);
        $event->method('getSubject')->willReturn('badObject');

        $this->expectException(UnexpectedTypeException::class);

        $this->listener->preDelete($event);
    }

    public function testItIsTriggeredPreUpdate(): void
    {
        $event = $this->createMock(GenericEvent::class);
        $adminUser = $this->createMock(AdminUserInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $session = $this->createMock(Session::class);

        $event->method('getSubject')->willReturn($adminUser);
        $adminUser->method('getEmailCanonical')->willReturn('sylius@example.com');
        $session->method('getFlashBag')->willReturn($flashBag);
        $this->requestStack->method('getSession')->willReturn($session);

        $flashBag
            ->expects(self::once())
            ->method('add')
            ->with('error', 'sylius_demo.admin_account.prevent_edit');

        $event->expects(self::once())->method('stopPropagation');

        $this->listener->preUpdate($event);
    }

    public function testItThrowsExceptionIfEventSubjectIsNotAdminUserDuringPreUpdate(): void
    {
        $event = $this->createMock(GenericEvent::class);
        $event->method('getSubject')->willReturn('badObject');

        $this->expectException(UnexpectedTypeException::class);

        $this->listener->preUpdate($event);
    }
}
