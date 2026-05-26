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

namespace Tests\Generator;

use App\Generator\FlashMessageGeneratorInterface;
use App\Generator\VerificationFlashMessageGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class VerificationFlashMessageGeneratorTest extends TestCase
{
    private UrlGeneratorInterface&MockObject $urlGenerator;

    private TranslatorInterface&MockObject $translator;

    private VerificationFlashMessageGenerator $generator;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->generator = new VerificationFlashMessageGenerator($this->urlGenerator, $this->translator);
    }

    public function testItImplementsFlashMessageGeneratorInterface(): void
    {
        self::assertInstanceOf(FlashMessageGeneratorInterface::class, $this->generator);
    }

    public function testItGeneratesFlashMessageWithVerificationLink(): void
    {
        $this->urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('sylius_shop_user_verification', ['token' => 'token'], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('http://demo.sylius.org/verification/?token=token');

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('sylius_demo.verification_link_flash', [
                '%url%' => 'http://demo.sylius.org/verification/?token=token',
            ])
            ->willReturn('For demo purposes you can visit http://demo.sylius.org/verification/?token=token to verify the account.');

        self::assertSame(
            'For demo purposes you can visit http://demo.sylius.org/verification/?token=token to verify the account.',
            $this->generator->generate('token'),
        );
    }
}
