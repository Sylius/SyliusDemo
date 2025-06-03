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

namespace App\Entity\Product;

use Sylius\ProductBundlePlugin\Entity\ProductBundlesAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\MolliePlugin\Entity\ProductInterface;
use Sylius\MolliePlugin\Entity\ProductTrait;
use Sylius\ProductBundlePlugin\Entity\ProductInterface as ProductBundleAwareInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product')]
class Product extends BaseProduct implements ProductInterface, ProductBundleAwareInterface
{
    use ProductBundlesAwareTrait;
    use ProductTrait;
}
