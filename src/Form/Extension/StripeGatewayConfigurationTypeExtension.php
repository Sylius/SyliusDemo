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

namespace App\Form\Extension;

use FluxSE\SyliusStripePlugin\Form\Type\StripeGatewayConfigurationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class StripeGatewayConfigurationTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('publishable_key')
            ->remove('secret_key')
            ->remove('webhook_secret_keys')
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [StripeGatewayConfigurationType::class];
    }
}
