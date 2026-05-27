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

namespace Tests\Controller;

use App\Controller\CompleteDemoPayPalPaymentAction;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Sylius\Component\Payment\Model\GatewayConfigInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\PayPalPlugin\Manager\PaymentStateManagerInterface;
use Sylius\PayPalPlugin\Provider\OrderProviderInterface;
use Sylius\PayPalPlugin\Resolver\CapturePaymentResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CompleteDemoPayPalPaymentActionTest extends TestCase
{
    public function testItThrows404WhenDemoModeIsOff(): void
    {
        $action = $this->makeAction(demoMode: false);

        $this->expectException(NotFoundHttpException::class);

        $action(new Request(), orderToken: 'tok', paymentId: '1');
    }

    public function testItThrows403WhenCsrfHeaderMissing(): void
    {
        $csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManager->expects(self::never())->method('isTokenValid');

        $action = $this->makeAction(csrfTokenManager: $csrfTokenManager);

        $this->expectException(AccessDeniedHttpException::class);

        $action(new Request(), orderToken: 'tok', paymentId: '1');
    }

    public function testItThrows403WhenCsrfHeaderInvalid(): void
    {
        $csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManager
            ->expects(self::once())
            ->method('isTokenValid')
            ->with(self::callback(static fn (CsrfToken $token): bool => 'demo_paypal_complete' === $token->getId()))
            ->willReturn(false)
        ;

        $action = $this->makeAction(csrfTokenManager: $csrfTokenManager);

        $this->expectException(AccessDeniedHttpException::class);

        $action(new Request(server: ['HTTP_X_CSRF_TOKEN' => 'wrong']), orderToken: 'tok', paymentId: '1');
    }

    public function testItThrows404WhenPaymentNotFound(): void
    {
        $paymentRepository = $this->createMock(PaymentRepositoryInterface::class);
        $paymentRepository
            ->expects(self::once())
            ->method('findOneByOrderToken')
            ->with('1', 'tok')
            ->willReturn(null)
        ;

        $action = $this->makeAction(paymentRepository: $paymentRepository);

        $this->expectException(NotFoundHttpException::class);

        $action(new Request(server: ['HTTP_X_CSRF_TOKEN' => 'valid']), orderToken: 'tok', paymentId: '1');
    }

    public function testItReturns409WhenPaymentNotInNewState(): void
    {
        $action = $this->makeAction(paymentRepository: $this->makePaymentRepositoryReturning(
            $this->makePaymentMock(state: PaymentInterface::STATE_COMPLETED),
        ));

        $response = $action(new Request(server: ['HTTP_X_CSRF_TOKEN' => 'valid']), orderToken: 'tok', paymentId: '1');

        self::assertSame(409, $response->getStatusCode());
        self::assertSame('{"error":"already_processed"}', $response->getContent());
    }

    public function testItReturns422ForNonPayPalGateway(): void
    {
        $action = $this->makeAction(paymentRepository: $this->makePaymentRepositoryReturning(
            $this->makePaymentMock(gatewayFactory: 'offline'),
        ));

        $response = $action(new Request(server: ['HTTP_X_CSRF_TOKEN' => 'valid']), orderToken: 'tok', paymentId: '1');

        self::assertSame(422, $response->getStatusCode());
        self::assertSame('{"error":"unsupported_gateway"}', $response->getContent());
    }

    public function testItThrows404WhenPaymentOrderTokenDoesNotMatchRequestToken(): void
    {
        $action = $this->makeAction(paymentRepository: $this->makePaymentRepositoryReturning(
            $this->makePaymentMock(orderToken: 'other-token'),
        ));

        $this->expectException(NotFoundHttpException::class);

        $action(new Request(server: ['HTTP_X_CSRF_TOKEN' => 'valid']), orderToken: 'tok', paymentId: '1');
    }

    public function testItCompletesPaymentAndReturnsRedirectUrl(): void
    {
        $order = $this->makeOrderMock('tok');
        $payment = $this->makePaymentMock();

        $orderProvider = $this->createMock(OrderProviderInterface::class);
        $orderProvider
            ->expects(self::once())
            ->method('provideOrderByToken')
            ->with('tok')
            ->willReturn($order)
        ;

        $capturePaymentResolver = $this->createMock(CapturePaymentResolverInterface::class);
        $capturePaymentResolver
            ->expects(self::once())
            ->method('resolve')
            ->with($payment)
        ;

        $paymentStateManager = $this->createMock(PaymentStateManagerInterface::class);
        $paymentStateManager
            ->expects(self::once())
            ->method('process')
            ->with($payment)
        ;

        $stateMachine = $this->createMock(StateMachineInterface::class);
        $stateMachine
            ->expects(self::once())
            ->method('apply')
            ->with($payment, PaymentTransitions::GRAPH, PaymentTransitions::TRANSITION_COMPLETE)
        ;

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('sylius_shop_order_thank_you', ['tokenValue' => 'tok'])
            ->willReturn('/en_US/order/tok/thank-you')
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('flush');

        $action = $this->makeAction(
            paymentRepository: $this->makePaymentRepositoryReturning($payment),
            orderProvider: $orderProvider,
            capturePaymentResolver: $capturePaymentResolver,
            paymentStateManager: $paymentStateManager,
            stateMachine: $stateMachine,
            urlGenerator: $urlGenerator,
            entityManager: $entityManager,
        );

        $response = $action(new Request(server: ['HTTP_X_CSRF_TOKEN' => 'valid']), orderToken: 'tok', paymentId: '1');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"redirect_url":"\/en_US\/order\/tok\/thank-you"}', $response->getContent());
    }

    private function makeAction(
        bool $demoMode = true,
        ?PaymentRepositoryInterface $paymentRepository = null,
        ?CsrfTokenManagerInterface $csrfTokenManager = null,
        ?OrderProviderInterface $orderProvider = null,
        ?CapturePaymentResolverInterface $capturePaymentResolver = null,
        ?PaymentStateManagerInterface $paymentStateManager = null,
        ?StateMachineInterface $stateMachine = null,
        ?UrlGeneratorInterface $urlGenerator = null,
        ?EntityManagerInterface $entityManager = null,
    ): CompleteDemoPayPalPaymentAction {
        return new CompleteDemoPayPalPaymentAction(
            demoMode: $demoMode,
            paymentRepository: $paymentRepository ?? $this->makePaymentRepositoryReturning($this->makePaymentMock()),
            csrfTokenManager: $csrfTokenManager ?? $this->makePassingCsrfTokenManager(),
            orderProvider: $orderProvider ?? $this->createMock(OrderProviderInterface::class),
            capturePaymentResolver: $capturePaymentResolver ?? $this->createMock(CapturePaymentResolverInterface::class),
            paymentStateManager: $paymentStateManager ?? $this->createMock(PaymentStateManagerInterface::class),
            stateMachine: $stateMachine ?? $this->createMock(StateMachineInterface::class),
            urlGenerator: $urlGenerator ?? $this->createMock(UrlGeneratorInterface::class),
            entityManager: $entityManager ?? $this->createMock(EntityManagerInterface::class),
        );
    }

    private function makePassingCsrfTokenManager(): CsrfTokenManagerInterface
    {
        $csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManager->method('isTokenValid')->willReturn(true);

        return $csrfTokenManager;
    }

    private function makePaymentRepositoryReturning(?PaymentInterface $payment): PaymentRepositoryInterface
    {
        $paymentRepository = $this->createMock(PaymentRepositoryInterface::class);
        $paymentRepository->method('findOneByOrderToken')->willReturn($payment);

        return $paymentRepository;
    }

    /** @return PaymentInterface&MockObject */
    private function makePaymentMock(
        ?string $state = PaymentInterface::STATE_NEW,
        string $gatewayFactory = 'sylius_paypal',
        string $orderToken = 'tok',
    ): PaymentInterface {
        $gatewayConfig = $this->createMock(GatewayConfigInterface::class);
        $gatewayConfig->method('getFactoryName')->willReturn($gatewayFactory);

        $method = $this->createMock(PaymentMethodInterface::class);
        $method->method('getGatewayConfig')->willReturn($gatewayConfig);

        $payment = $this->createMock(PaymentInterface::class);
        $payment->method('getState')->willReturn($state);
        $payment->method('getMethod')->willReturn($method);
        $payment->method('getOrder')->willReturn($this->makeOrderMock($orderToken));

        return $payment;
    }

    private function makeOrderMock(string $tokenValue): OrderInterface
    {
        $order = $this->createMock(OrderInterface::class);
        $order->method('getTokenValue')->willReturn($tokenValue);

        return $order;
    }
}
