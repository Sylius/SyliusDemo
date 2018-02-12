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

use Sylius\Bundle\CoreBundle\Application\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new \Sylius\Bundle\AdminBundle\SyliusAdminBundle(),
            new \Sylius\Bundle\ShopBundle\SyliusShopBundle(),

            new \FOS\OAuthServerBundle\FOSOAuthServerBundle(), // Required by SyliusApiBundle
            new \Sylius\Bundle\AdminApiBundle\SyliusAdminApiBundle(),

            new \Sylius\ShopApiPlugin\ShopApiPlugin(),
            new \League\Tactician\Bundle\TacticianBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),

            new \AppBundle\AppBundle(),
        ];

        if ('prod' === $this->getEnvironment()) {
            $bundles[] = new \Snc\RedisBundle\SncRedisBundle();
        }

        return array_merge(parent::registerBundles(), $bundles);
    }
}
