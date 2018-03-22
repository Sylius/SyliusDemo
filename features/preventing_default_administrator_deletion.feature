@managing_administrators
Feature: Preventing default administrator deletion
    In order to prevent breaking Sylius Demo application
    As an Administrator
    I want to not be able to delete administrator with email "sylius@example.com"

    Background:
        Given there is an administrator "sylius@example.com"
        And there is also an administrator "admin@example.com"
        And I am logged in as "admin@example.com" administrator

    @ui
    Scenario: Being prevented from deleting default administrator
        When I browse administrators
        And I delete administrator with email "sylius@example.com"
        Then I should be notified that I cannot delete Administrator of Sylius Demo
        And I should see the administrator "sylius@example.com" in the list
