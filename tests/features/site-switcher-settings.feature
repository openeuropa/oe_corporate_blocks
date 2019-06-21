@api
Feature: Site switcher settings.
  In order to be able to set which site switcher link should be active
  As a privileged user
  I want to be able to access the site switcher settings page and update its values

  @preserve-site-switcher-configuration
  Scenario Outline: Privileged users can set which site switcher block link is active.
    When I am on the homepage
    Then no site switcher link should be set as active

    Given I am logged in as a user with the "access administration pages, configure site switcher block" permission
    And I am on "the site switcher block settings page"

    Then I should see the heading "Site switcher settings"
    And I select "<active link>" from "Link"
    And I press "Save configuration"

    When I log out
    And I am on the homepage
    Then the "<active label english>" site switcher link should be set as active

    When I click "français" in the "header"
    Then the "<active label french>" site switcher link should be set as active

    Examples:
      | active link    | active label english               | active label french                  |
      | Info site      | Policies, information and services | Politiques, informations et services |
      | Political site | Commission and its priorities      | La Commission et ses priorités       |
