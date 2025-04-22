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

namespace App\Generator;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class VerificationFlashMessageGenerator implements FlashMessageGeneratorInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator,
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function generate(string $token): string
    {
        $url = $this
            ->urlGenerator
            ->generate('sylius_shop_user_verification', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
        ;

        $message = $this->translator->trans('sylius_demo.verification_link_flash', [
            '%url%' => $url,
        ]);

        return $message;
    }
}
