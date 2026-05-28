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

use Sylius\PayPalPlugin\Form\Type\PayPalConfigurationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class PayPalConfigurationTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('client_id')
            ->remove('client_secret')
            ->remove('reports_sftp_username')
            ->remove('reports_sftp_password')
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [PayPalConfigurationType::class];
    }
}
