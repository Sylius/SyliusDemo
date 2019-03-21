<?php

declare(strict_types=1);

namespace Tests\Behat\Context\Setup;

use App\Entity\AdminUser;
use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SecurityServiceInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Sylius\RbacPlugin\Access\Model\OperationType;
use Sylius\RbacPlugin\Entity\AdministrationRoleInterface;
use Sylius\RbacPlugin\Model\Permission;

final class AdminUserContext implements Context
{
    /** @var SecurityServiceInterface */
    private $securityService;

    /** @var ExampleFactoryInterface */
    private $userFactory;

    /** @var FactoryInterface */
    private $administrationRoleFactory;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var RepositoryInterface */
    private $administrationRoleRepository;

    private $objectManager;

    /** @var array */
    private $rbacConfiguration;

    public function __construct(
        SecurityServiceInterface $securityService,
        ExampleFactoryInterface $userFactory,
        FactoryInterface $administrationRoleFactory,
        UserRepositoryInterface $userRepository,
        RepositoryInterface $administrationRoleRepository,
        array $rbacConfiguration
    ) {
        $this->securityService = $securityService;
        $this->userFactory = $userFactory;
        $this->administrationRoleFactory = $administrationRoleFactory;
        $this->userRepository = $userRepository;
        $this->administrationRoleRepository = $administrationRoleRepository;
        $this->rbacConfiguration = $rbacConfiguration;
    }

    /**
     * @Given I am logged in as a root administrator
     */
    public function iAmLoggedInAsRootAdministrator(): void
    {
        $administrationRole = $this->createRootAdministrationRole();

        /** @var AdminUser $user */
        $user = $this->userFactory->create(['email' => 'sylius@example.com', 'password' => 'sylius']);
        $user->setAdministrationRole($administrationRole);

        $this->userRepository->add($user);

        $this->securityService->logIn($user);
    }

    /**
     * @Given there is a root administrator :email identified by :password
     * @Given /^there is(?:| also) a root administrator "([^"]+)"$/
     */
    public function thereIsAnAdministratorIdentifiedBy(string $email, string $password = 'sylius'): void
    {
        $administrationRole = $this->createRootAdministrationRole();

        /** @var AdminUser $adminUser */
        $adminUser = $this->userFactory->create(['email' => $email, 'password' => $password, 'enabled' => true]);
        $adminUser->setAdministrationRole($administrationRole);

        $this->userRepository->add($adminUser);
    }

    private function createRootAdministrationRole(): AdministrationRoleInterface
    {
        /** @var AdministrationRoleInterface $administrationRole */
        $administrationRole = $this->administrationRoleFactory->createNew();
        $administrationRole->setName('Root');

        foreach ($this->rbacConfiguration as $section => $configuration) {
            if ($section === 'custom') {
                continue;
            }
            $administrationRole->addPermission(Permission::ofType($section, [OperationType::write()]));
        }

        $this->administrationRoleRepository->add($administrationRole);

        return $administrationRole;
    }
}
