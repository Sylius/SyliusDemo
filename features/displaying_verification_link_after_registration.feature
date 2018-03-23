@customer_registration
Feature: Displaying verification link after registration
    In order to be able to use registered account
    As a Visitor
    I want to have account verification link displayed rather than sent by email

    Background:
        Given the store operates on a single channel in "United States"

    @ui
    Scenario: Seeing account verification link in flash message after registration
        When I want to register a new account
        And I specify the first name as "Gandalf"
        And I specify the last name as "The White"
        And I specify the email as "gandalf@middle-earth.com"
        And I specify the password as "go!go!gondor!"
        And I confirm this password
        And I register this account
        Then I should have account verification link for "gandalf@middle-earth.com" displayed in flash message
        But I should not be logged in
