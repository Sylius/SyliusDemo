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

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\PayPalPlugin\Manager\PaymentStateManagerInterface;
use Sylius\PayPalPlugin\Provider\OrderProviderInterface;
use Sylius\PayPalPlugin\Resolver\CapturePaymentResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final readonly class CompleteDemoPayPalPaymentAction
{
    public function __construct(
        private bool $demoMode,
        private PaymentRepositoryInterface $paymentRepository,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private OrderProviderInterface $orderProvider,
        private CapturePaymentResolverInterface $capturePaymentResolver,
        private PaymentStateManagerInterface $paymentStateManager,
        private StateMachineInterface $stateMachine,
        private UrlGeneratorInterface $urlGenerator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request, string $orderToken, string $paymentId): JsonResponse
    {
        if (!$this->demoMode) {
            throw new NotFoundHttpException();
        }

        $csrfToken = (string) $request->headers->get('X-CSRF-Token', '');
        if ('' === $csrfToken || !$this->csrfTokenManager->isTokenValid(new CsrfToken('demo_paypal_complete', $csrfToken))) {
            throw new AccessDeniedHttpException();
        }

        $payment = $this->paymentRepository->findOneByOrderToken($paymentId, $orderToken);
        if (null === $payment) {
            throw new NotFoundHttpException();
        }

        if (PaymentInterface::STATE_NEW !== $payment->getState()) {
            return new JsonResponse(['error' => 'already_processed'], 409);
        }

        $gatewayConfig = $payment->getMethod()?->getGatewayConfig();
        if (null === $gatewayConfig || 'sylius_paypal' !== $gatewayConfig->getFactoryName()) {
            return new JsonResponse(['error' => 'unsupported_gateway'], 422);
        }

        $paymentOrder = $payment->getOrder();
        if (!$paymentOrder instanceof OrderInterface || $orderToken !== $paymentOrder->getTokenValue()) {
            throw new NotFoundHttpException();
        }

        $order = $this->orderProvider->provideOrderByToken($orderToken);

        $this->capturePaymentResolver->resolve($payment);
        $this->paymentStateManager->process($payment);
        $this->stateMachine->apply($payment, PaymentTransitions::GRAPH, PaymentTransitions::TRANSITION_COMPLETE);
        $this->entityManager->flush();

        $redirectUrl = $this->urlGenerator->generate('sylius_shop_order_thank_you', [
            'tokenValue' => $order->getTokenValue(),
        ]);

        return new JsonResponse(['redirect_url' => $redirectUrl]);
    }
}
