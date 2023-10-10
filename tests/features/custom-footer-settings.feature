@api
Feature: Site specific footer links management.
  In order to be able to manage site specific footer links
  As a privileged user
  I want to be able to access the site specific footer management page and update its values

  Scenario Outline: Privileged users can control General links in Site specific footer through Footer links manager page.
    Given I am logged in as a user with the "access administration pages, administer site specific footer links" permissions

    # Add new link for site specific footer
    When I am on "the footer links manager page"
    Then I should see the heading "Footer Link General entities"
    When I click "Add footer link"
    Then I should see the heading "Add footer link general"
    When I fill in "Label" with "00000 European Commission, official website"
    And I fill in "Machine-readable name" with "00000_eurpoean_commission_official_website"
    And I fill in "URL" with "https://commission.europa.eu/index_en"
    And I should have the following options for the "Section" select:
      | - Disabled -  |
      | Contact us    |
      | About us      |
      | Related sites |
      | Accessibility |
    And I select "Related sites" from "Section"
    And I press "Save"
    Then I should see the heading "Footer Link General entities"

    When I click "Add footer link"
    Then I should see the heading "Add footer link general"
    When I fill in "Label" with "00001 About the European Commission"
    And I fill in "Machine-readable name" with "00001_about_the_european_commission"
    And I fill in "URL" with "https://commission.europa.eu/strategy_en"
    And I select "Related sites" from "Section"
    And I press "Save"
    Then I should see the heading "Footer Link General entities"

    # Make sure that caching work correctly for anonymous user.
    When I log out
    And I am on the homepage
    Then the region "<region>" contains the links:
      | 00000 European Commission, official website | https://commission.europa.eu/index_en    |
      | 00001 About the European Commission         | https://commission.europa.eu/strategy_en |
    # We should see the links which is not translated yet.
    And I click "français" in the "sidebar"
    And the region "<region>" contains the links:
      | 00000 European Commission, official website | https://commission.europa.eu/index_en    |
      | 00001 About the European Commission         | https://commission.europa.eu/strategy_en |

    # Translate links for French language.
    When I am logged in as a user with the "access administration pages, administer site specific footer links, translate configuration" permissions
    And I am on "the footer links manager page"
    And I click "Translate" in the "00000 European Commission, official website" row
    Then I should see "Translations for 00000 European Commission, official website footer link general"
    When I click "Add" in the "French" row
    And I fill in "Label" with "00000 European Commission, official website FR"
    And I fill in "URL" with "https://commission.europa.eu/index_fr"
    And I press "Save translation"

    And I am on "the footer links manager page"
    And I click "Translate" in the "00001 About the European Commission" row
    And I click "Add" in the "French" row
    And I fill in "Label" with "00001 About the European Commission FR"
    And I fill in "URL" with "https://commission.europa.eu/strategy_fr"
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
    Then the region "<region>" contains the links:
      | 00000 European Commission, official website EN | https://commission.europa.eu/index_en    |
      | 00001 About the European Commission EN         | https://commission.europa.eu/strategy_en |
    # We shouldn't see the translated links.
    And I click "français" in the "sidebar"
    And the region "<region>" contains the links:
      | 00000 European Commission, official website FR | https://commission.europa.eu/index_fr    |
      | 00001 About the European Commission FR         | https://commission.europa.eu/strategy_fr |

    # Delete the general links.
    When I am logged in as a user with the "access administration pages, administer site specific footer links, translate configuration" permissions
    And I am on "the footer links manager page"
    And I click "Delete" in the "00000 European Commission, official website EN" row
    And I press "Delete"
    And I am on "the footer links manager page"
    And I click "Delete" in the "00001 About the European Commission EN" row
    And I press "Delete"
    # Make sure that caching work correctly for anonymous user.
    And I log out
    And I am on the homepage
    Then the region "<region>" does not contain the links:
      | 00000 European Commission, official website EN | https://commission.europa.eu/index_en    |
      | 00001 About the European Commission EN         | https://commission.europa.eu/strategy_en |
    # We shouldn't see the translated links.
    And I click "français" in the "sidebar"
    And the region "<region>" does not contain the links:
      | 00000 European Commission, official website FR | https://commission.europa.eu/index_fr    |
      | 00001 About the European Commission FR         | https://commission.europa.eu/strategy_fr |

    Examples:
      | region    |
      | ec_footer |
      | eu_footer |

  Scenario Outline: Privileged users can control Social links in Site specific footer through Footer links manager page.
    Given I am logged in as a user with the "access administration pages, administer site specific footer links" permissions

    # Add new link for site specific footer
    When I am on "the footer social links manager page"
    Then I should see the heading "Footer Link Social entities"
    When I click "Add social media footer link"
    Then I should see the heading "Add footer link social"
    And I should have the following options for the "Social network" select:
      | - None -  |
      | Facebook  |
      | Instagram |
      | Linkedin  |
      | Pinterest |
      | RSS       |
      | Skype     |
      | Twitter   |
      | YouTube   |
    When I fill in "Label" with "00000 Instagram"
    And I fill in "Machine-readable name" with "00000_instagram"
    And I fill in "URL" with "https://www.instagram.com/europeancommission"
    And I select "Instagram" from "Social network"
    And I press "Save"
    Then I should see the heading "Footer Link Social entities"

    # Make sure that caching work correctly for anonymous user.
    When I log out
    And I am on the homepage
    Then the region "<region>" contains the links:
      | 00000 Instagram | https://www.instagram.com/europeancommission |
    # We should see the links which is not translated yet.
    And I click "français" in the "sidebar"
    And the region "<region>" contains the links:
      | 00000 Instagram | https://www.instagram.com/europeancommission |

    # Translate links for French language.
    When I am logged in as a user with the "access administration pages, administer site specific footer links, translate configuration" permissions
    And I am on "the footer social links manager page"
    And I click "Translate" in the "00000 Instagram" row
    Then I should see "Translations for 00000 Instagram footer link social"
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
    Then the region "<region>" contains the links:
      | 00000 Instagram EN | https://www.instagram.com/europeancommission |
    # We shouldn't see the translated links.
    And I click "français" in the "sidebar"
    And the region "<region>" contains the links:
      | 00000 Instagram FR | https://www.instagram.com/europeancommission?hl=fr |

    # Delete the social links.
    When I am logged in as a user with the "access administration pages, administer site specific footer links, translate configuration" permissions
    And I am on "the footer social links manager page"
    And I click "Delete" in the "00000 Instagram EN" row
    And I press "Delete"
    # Make sure that caching work correctly for anonymous user.
    And I log out
    And I am on the homepage
    # Links shouldn't be available.
    Then the region "<region>" does not contain the links:
      | 00000 Instagram EN | https://www.instagram.com/europeancommission |
    # We shouldn't see the translated links.
    And I click "français" in the "sidebar"
    And the region "<region>" does not contain the links:
      | 00000 Instagram FR | https://www.instagram.com/europeancommission?hl=fr |

    Examples:
      | region    |
      | ec_footer |
      | eu_footer |

  Scenario Outline: Invalid URLs should not be saved in the footer.
    Given I am logged in as a user with the "access administration pages, administer site specific footer links" permissions
    When I am on "the footer links manager page"
    And I click "Add footer link"
    And I fill in "Label" with "Test"
    And I fill in "Machine-readable name" with "test"
    And I fill in "URL" with "<url>"
    And I press "Save"
    Then I should see the following error messages:
      | error messages |
      | <message>      |

    Examples:
      | url                | message                                                                                                          |
      | http://example:com | The path http://example:com is invalid.                                                                          |
      | htp://example.com  | The path htp://example.com is invalid.                                                                           |
      | node/1             | The specified target is invalid. Manually entered paths should start with one of the following characters: / ? # |
      | //                 | The path // is invalid.                                                                                          |
      | /<front>/node/1    | The path /<front>/node/1 is invalid.                                                                             |
      | <front>/node/1     | The path <front>/node/1 is invalid.                                                                              |
      | /<front>           | The path /<front> is invalid.                                                                                    |
