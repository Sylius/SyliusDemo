@managing_channels
Feature: Preventing a new channel addition
    In order to prevent breaking Sylius Demo application
    As an Administrator
    I want to not be able to add a new channel

    Background:
        Given I am logged in as a root administrator

    @ui
    Scenario: Being prevented from adding a new channel
        When I try to create a new channel
        Then I should be notified that I cannot create a new channel on Sylius Demo
