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

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_order_item')]
class OrderItem extends BaseOrderItem implements OrderItemInterface
{
}
