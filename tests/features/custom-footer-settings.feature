@api
Feature: Custom footer settings.
  In order to be able to expose custom footer content
  As a privileged user
  I want to be able to access the custom footer management page and update its values

  Scenario: Privileged users can control General links in Custom footer through Footer links manager page.
    Given I am logged in as a user with the "access administration pages, administer footer general link" permission

    # Add new link for custom footer
    When I am on "the footer links manager page"
    Then I should see the heading "Footer general link entities"
    When I click "Add footer general link"
    Then I should see the heading "Add footer general link"
    When I fill in "Label" with "00000 European Commission, official website"
    And I fill in "Machine-readable name" with "00000_eurpoean_commission_official_website"
    And I fill in "URL" with "https://ec.europa.eu/info/index_en"
    And I press "Save"
    Then I should see the heading "Footer general link entities"

    When I click "Add footer general link"
    Then I should see the heading "Add footer general link"
    When I fill in "Label" with "00001 About the European Commission"
    And I fill in "Machine-readable name" with "00001_about_the_european_commission"
    And I fill in "URL" with "https://ec.europa.eu/info/strategy_en"
    And I press "Save"
    Then I should see the heading "Footer general link entities"

    # Make sure that caching work correctly for anonymous user.
    When I log out
    And I am on the homepage
    Then Links in the "footer" region contains the links:
      | 00000 European Commission, official website | https://ec.europa.eu/info/index_en    |
      | 00001 About the European Commission         | https://ec.europa.eu/info/strategy_en |
    # We should see the links which is not translated yet.
    And I click "français" in the "header"
    And Links in the "footer" region contains the links:
      | 00000 European Commission, official website | https://ec.europa.eu/info/index_en    |
      | 00001 About the European Commission         | https://ec.europa.eu/info/strategy_en |

    # Translate links for French language.
    When I am logged in as a user with the "access administration pages, administer footer general link, translate configuration" permission
    And I am on "the footer links manager page"
    And I click "Translate" in the "00000 European Commission, official website" row
    Then I should see "Translations for 00000 European Commission, official website footer general link"
    When I click "Add" in the "French" row
    And I fill in "Label" with "00000 European Commission, official website FR"
    And I fill in "URL" with "https://ec.europa.eu/info/index_fr"
    And I press "Save translation"

    And I am on "the footer links manager page"
    And I click "Translate" in the "00001 About the European Commission" row
    And I click "Add" in the "French" row
    And I fill in "Label" with "00001 About the European Commission FR"
    And I fill in "URL" with "https://ec.europa.eu/info/strategy_fr"
    And I press "Save translation"

    # Change original labels of links.
    And I am on "the footer links manager page"
    And I click "Edit" in the "00000 European Commission, official website" row
    And I fill in "Label" with "00000 European Commission, official website EN"
    And I press "Save"

    And I am on "the footer links manager page"
    And I click "Edit" in the "00001 About the European Commission" row
    And I fill in "Label" with "00001 About the European Commission EN"
    And I press "Save"
    # Make sure that caching work correctly for anonymous user.
    And I log out
    And I am on the homepage
    Then Links in the "footer" region contains the links:
      | 00000 European Commission, official website EN | https://ec.europa.eu/info/index_en    |
      | 00001 About the European Commission EN         | https://ec.europa.eu/info/strategy_en |
    # We shouldn't see the translated links.
    And I click "français" in the "header"
    And Links in the "footer" region contains the links:
      | 00000 European Commission, official website FR | https://ec.europa.eu/info/index_fr    |
      | 00001 About the European Commission FR         | https://ec.europa.eu/info/strategy_fr |

    # Delete the general links.
    When I am logged in as a user with the "access administration pages, administer footer general link, translate configuration" permission
    And I am on "the footer links manager page"
    And I click "Delete" in the "00000 European Commission, official website EN" row
    And I press "Delete"
    And I am on "the footer links manager page"
    And I click "Delete" in the "00001 About the European Commission EN" row
    And I press "Delete"
    # Make sure that caching work correctly for anonymous user.
    And I log out
    And I am on the homepage
    Then Links in the "footer" region do not contains the links:
      | 00000 European Commission, official website EN | https://ec.europa.eu/info/index_en    |
      | 00001 About the European Commission EN         | https://ec.europa.eu/info/strategy_en |
    # We shouldn't see the translated links.
    And I click "français" in the "header"
    And Links in the "footer" region do not contains the links:
      | 00000 European Commission, official website FR | https://ec.europa.eu/info/index_fr    |
      | 00001 About the European Commission FR         | https://ec.europa.eu/info/strategy_fr |

  Scenario: Privileged users can control Social links in Custom footer through Footer links manager page.
    Given I am logged in as a user with the "access administration pages, administer footer social link" permission

    # Add new link for custom footer
    When I am on "the footer social links manager page"
    Then I should see the heading "Footer social link entities"
    When I click "Add footer social link"
    Then I should see the heading "Add footer social link"
    When I fill in "Label" with "00000 Instagram"
    And I fill in "Machine-readable name" with "00000_instagram"
    And I fill in "URL" with "https://www.instagram.com/europeancommission"
    And I select "Instagram" from "Social network"
    And I press "Save"
    Then I should see the heading "Footer social link entities"

    # Make sure that caching work correctly for anonymous user.
    When I log out
    And I am on the homepage
    Then Links in the "footer" region contains the links:
      | 00000 Instagram | https://www.instagram.com/europeancommission |
    # We should see the links which is not translated yet.
    And I click "français" in the "header"
    And Links in the "footer" region contains the links:
      | 00000 Instagram | https://www.instagram.com/europeancommission |

    # Translate links for French language.
    When I am logged in as a user with the "access administration pages, administer footer social link, translate configuration" permission
    And I am on "the footer social links manager page"
    And I click "Translate" in the "00000 Instagram" row
    Then I should see "Translations for 00000 Instagram footer social link"
    When I click "Add" in the "French" row
    And I fill in "Label" with "00000 Instagram FR"
    And I fill in "URL" with "https://www.instagram.com/europeancommission?hl=fr"
    And I press "Save translation"

    # Change original labels of links.
    And I am on "the footer social links manager page"
    And I click "Edit" in the "00000 Instagram" row
    And I fill in "Label" with "00000 Instagram EN"
    And I press "Save"

    # Make sure that caching work correctly for anonymous user.
    And I log out
    And I am on the homepage
    Then Links in the "footer" region contains the links:
      | 00000 Instagram EN | https://www.instagram.com/europeancommission |
    # We shouldn't see the translated links.
    And I click "français" in the "header"
    And Links in the "footer" region contains the links:
      | 00000 Instagram FR | https://www.instagram.com/europeancommission?hl=fr |

    # Delete the social links.
    When I am logged in as a user with the "access administration pages, administer footer social link, translate configuration" permission
    And I am on "the footer social links manager page"
    And I click "Delete" in the "00000 Instagram EN" row
    And I press "Delete"
    # Make sure that caching work correctly for anonymous user.
    And I log out
    And I am on the homepage
    # Links shouldn't be available.
    Then Links in the "footer" region do not contains the links:
      | 00000 Instagram EN | https://www.instagram.com/europeancommission |
    # We shouldn't see the translated links.
    And I click "français" in the "header"
    And Links in the "footer" region do not contains the links:
      | 00000 Instagram FR | https://www.instagram.com/europeancommission?hl=fr |
