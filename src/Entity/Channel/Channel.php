<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Channel as BaseChannel;
use Sylius\PriceHistoryPlugin\Domain\Model\ChannelInterface;
use Sylius\PriceHistoryPlugin\Domain\Model\LowestPriceForDiscountedProductsAwareTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements ChannelInterface
{
    use LowestPriceForDiscountedProductsAwareTrait;
}
