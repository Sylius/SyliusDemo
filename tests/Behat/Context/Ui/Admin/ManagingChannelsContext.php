<?php

declare(strict_types=1);

namespace Tests\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Page\Admin\Crud\CreatePageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;

final class ManagingChannelsContext implements Context
{
    /** @var CreatePageInterface */
    private $channelCreatePage;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    public function __construct(CreatePageInterface $channelCreatePage, NotificationCheckerInterface $notificationChecker)
    {
        $this->channelCreatePage = $channelCreatePage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When I try to create a new channel
     */
    public function iTryToCreateANewChannel(): void
    {
        $this->channelCreatePage->tryToOpen();
    }

    /**
     * @Then I should be notified that I cannot create a new channel on Sylius Demo
     */
    public function shouldBeNotifiedThatICannotCreateANewChannelOnSyliusDemo(): void
    {
        $this->notificationChecker->checkNotification(
            'You cannot create channel on Sylius Demo!',
            NotificationType::failure()
        );
    }
}
