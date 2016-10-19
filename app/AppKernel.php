<?php

use Sylius\Bundle\CoreBundle\Application\Kernel;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new Sylius\Bundle\AdminBundle\SyliusAdminBundle(),
            new Sylius\Bundle\ApiBundle\SyliusApiBundle(),
            new Sylius\Bundle\ShopBundle\SyliusShopBundle(),
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new AppBundle\AppBundle(),
        ];

        if ('prod' === $this->getEnvironment()) {
            $bundles[] = new Snc\RedisBundle\SncRedisBundle();
        }

        return array_merge(parent::registerBundles(), $bundles);
    }
}
