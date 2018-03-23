<?php

declare(strict_types=1);

namespace Tests\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final class RegistrationContext implements Context
{
    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var RepositoryInterface */
    private $shopUserRepository;

    public function __construct(
        NotificationCheckerInterface $notificationChecker,
        UrlGeneratorInterface $urlGenerator,
        RepositoryInterface $shopUserRepository
    ) {
        $this->notificationChecker = $notificationChecker;
        $this->urlGenerator = $urlGenerator;
        $this->shopUserRepository = $shopUserRepository;
    }

    /**
     * @Then I should have account verification link for :email displayed in flash message
     */
    public function iShouldHaveAccountVerificationLinkDisplayedInFlashMessage(string $email): void
    {
        /** @var ShopUserInterface $shopUser */
        $shopUser = $this->shopUserRepository->findOneBy(['username' => $email]);
        Assert::notNull($shopUser);

        $verificationLink = $this->urlGenerator->generate(
            'sylius_shop_user_verification',
            ['_locale' => 'en_US', 'token' => $shopUser->getEmailVerificationToken()]
        );

        $this->notificationChecker->checkNotification(
            sprintf('For demo purposes you can visit http://localhost:8080%s to verify the account.', $verificationLink),
            NotificationType::success()
        );
    }
}
