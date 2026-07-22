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

use Sylius\AdyenPlugin\Form\Type\ConfigurationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class AdyenConfigurationTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('environment')
            ->remove('merchantAccount')
            ->remove('liveEndpointUrlPrefix')
            ->remove('apiKey')
            ->remove('clientKey')
            ->remove('hmacKey')
            ->remove('authUser')
            ->remove('authPassword')
            ->remove('esdEnabled')
            ->remove('esdType')
            ->remove('merchantCategoryCode')
            ->remove('captureMode')
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [ConfigurationType::class];
    }
}
