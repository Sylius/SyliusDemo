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

namespace App\Fixture\Listener;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\AfterFixtureListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\FixtureEvent;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class ShopUserVerificationListener extends AbstractListener implements AfterFixtureListenerInterface
{
    /** @param UserRepositoryInterface<ShopUserInterface> $shopUserRepository */
    public function __construct(
        private readonly UserRepositoryInterface $shopUserRepository,
        private readonly ObjectManager $shopUserManager,
    ) {
    }

    public function afterFixture(FixtureEvent $fixtureEvent, array $options): void
    {
        if ('shop_user' !== $fixtureEvent->fixture()->getName()) {
            return;
        }

        foreach ($options['emails'] as $email) {
            $shopUser = $this->shopUserRepository->findOneByEmail($email);
            if (null === $shopUser) {
                continue;
            }

            $shopUser->setVerifiedAt(new \DateTime());
        }

        $this->shopUserManager->flush();
    }

    public function getName(): string
    {
        return 'shop_user_verification';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('emails')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }
}
