<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\Generator;

use AppBundle\Generator\FlashMessageGeneratorInterface;
use AppBundle\Generator\VerificationFlashMessageGenerator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class VerificationFlashMessageGeneratorSpec extends ObjectBehavior
{
    function let(
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator
    ) {
        $this->beConstructedWith($urlGenerator, $translator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(VerificationFlashMessageGenerator::class);
    }

    function it_implements_flash_message_generator_interface()
    {
        $this->shouldImplement(FlashMessageGeneratorInterface::class);
    }

    function it_generates_flash_message_with_verification_link(
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $urlGenerator
            ->generate('sylius_shop_user_verification', ['token' => 'token'], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('http://demo.sylius.org/verification/?token=token')
        ;

        $translator->trans('sylius_demo.verification_link_flash', [
            '%url%' => 'http://demo.sylius.org/verification/?token=token',
        ])->willReturn('For demo purposes you can visit http://demo.sylius.org/verification/?token=token to verify the account.');

        $this->generate('token')->shouldReturn('For demo purposes you can visit http://demo.sylius.org/verification/?token=token to verify the account.');
    }
}
