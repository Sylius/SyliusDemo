@managing_administrators
Feature: Preventing default administrator edition
    In order to prevent breaking Sylius Demo application
    As an Administrator
    I want to be prevented from editing administrator with email "sylius@example.com"

    Background:
        Given there is also an administrator "admin@example.com"
        And there is an administrator "sylius@example.com"
        And I am logged in as "admin@example.com" administrator

    @ui
    Scenario: Being prevented from edition default administrator
        When I want to edit administrator "sylius@example.com"
        And I change its name to "Jon Snow"
        And I change its email to "jon@example.com"
        And I save my changes
        Then I should be notified that I cannot edit Administrator of Sylius Demo
        And I should see the administrator "sylius@example.com" in the list
