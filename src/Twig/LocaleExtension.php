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

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\TwigFilter;

/** necessary until plugins will not compatible with IntlTwig instead SonataIntl (Sylius 1.7)  */
final class LocaleExtension extends AbstractExtension
{
    /** @var IntlExtension */
    private $intlExtension;

    public function __construct(IntlExtension $intlExtension)
    {
        $this->intlExtension = $intlExtension;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('locale', [$this->intlExtension, 'getLocaleName']),
        ];
    }
}
