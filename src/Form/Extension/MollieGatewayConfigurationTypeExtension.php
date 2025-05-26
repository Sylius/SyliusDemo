<?php

/*
 * This file is part of the Sylius Mollie Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form\Extension;

use Sylius\MolliePlugin\Form\Type\MollieGatewayConfigurationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class MollieGatewayConfigurationTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('profile_id')
            ->remove('api_key_live')
            ->remove('api_key_test')
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [MollieGatewayConfigurationType::class];
    }
}
