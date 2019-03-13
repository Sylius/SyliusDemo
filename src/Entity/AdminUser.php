<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;
use Sylius\RbacPlugin\Entity\AdministrationRoleAwareInterface;
use Sylius\RbacPlugin\Entity\AdministrationRoleTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="sylius_admin_user")
 */
final class AdminUser extends BaseAdminUser implements AdministrationRoleAwareInterface
{
    use AdministrationRoleTrait;
}
