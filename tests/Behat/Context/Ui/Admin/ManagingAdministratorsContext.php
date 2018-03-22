<?php

declare(strict_types=1);

namespace Tests\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;

final class ManagingAdministratorsContext implements Context
{
    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    public function __construct(NotificationCheckerInterface $notificationChecker)
    {
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @Then I should be notified that I cannot delete Administrator of Sylius Demo
     */
    public function iShouldBeNotifierThatICannotDeleteAdministratorOfSyliusDemo(): void
    {
        $this->notificationChecker->checkNotification(
            'You cannot delete administrator of Sylius Demo!',
            NotificationType::failure()
        );
    }
}
