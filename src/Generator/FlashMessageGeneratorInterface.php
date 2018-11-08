<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Generator;

interface FlashMessageGeneratorInterface
{
    /**
     * @param string $token
     *
     * @return string
     */
    public function generate(string $token): string;
}
