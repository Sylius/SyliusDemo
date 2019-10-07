@managing_administrators
Feature: Preventing default administrator deletion
    In order to prevent breaking Sylius Demo application
    As an Administrator
    I want to be prevented from deleting administrator with email "sylius@example.com"

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Being prevented from deleting default administrator
        When I browse administrators
        And I delete administrator with email "sylius@example.com"
        Then I should be notified that I cannot delete Administrator of Sylius Demo
        And I should see the administrator "sylius@example.com" in the list
