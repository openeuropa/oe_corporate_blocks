@api
Feature: Corporate blocks feature
  In order to be able to showcase Corporate blocks
  As an anonymous user
  I want to use corporate blocks

  Scenario Outline: On any pages we have correct urls in corporate footer block for many languages
    Given the following languages are available:
      | languages |
      | en        |
      | fr        |
    And I set the site owner to "Directorate-General for Digital Services, Directorate-General for Agriculture and Rural Development"
    When I am on "the <path> page"
    Then I should see "This site is managed by: Directorate-General for Digital Services, Directorate-General for Agriculture and Rural Development" in the "ec_footer" region
    And the region "ec_footer" contains the links:
      | European Commission website | https://commission.europa.eu/index_en                                               |
      | About us                    | https://commission.europa.eu/about_en                                               |
      | Contact us                  | https://commission.europa.eu/about/contact_en                                       |
      | Priorities                  | https://commission.europa.eu/priorities-2024-2029_en                                |
      | Topics                      | https://commission.europa.eu/topics_en                                              |
      | Funding and tenders         | https://commission.europa.eu/funding-tenders_en                                     |
      | Jobs                        | https://commission.europa.eu/get-involved/jobs-european-commission_en               |
      | Press corner                | https://ec.europa.eu/commission/presscorner/home/en                                 |
      | Events                      | https://commission.europa.eu/get-involved/events_en                                 |
      | Facebook                    | https://www.facebook.com/EuropeanCommission                                         |
      | Bluesky                     | https://bsky.app/profile/ec.europa.eu                                               |
      | Mastodon                    | https://ec.social-network.europa.eu/@EUCommission                                   |
      | Youtube                     | https://www.youtube.com/user/eutube                                                 |
      | LinkedIn                    | http://www.linkedin.com/company/european-commission                                 |
      | Other                       | https://european-union.europa.eu/contact-eu/social-media-channels_en                |
      | Contact us                  | https://commission.europa.eu/about/contact_en                                       |
      | Report an IT vulnerability  | https://commission.europa.eu/legal-notice/vulnerability-disclosure-policy_en        |
      | Languages on our websites   | https://commission.europa.eu/languages-our-websites_en                              |
      | Cookies                     | https://commission.europa.eu/cookies-policy_en                                      |
      | Privacy policy              | https://commission.europa.eu/privacy-policy-websites-managed-european-commission_en |
      | Legal notice                | https://commission.europa.eu/legal-notice_en                                        |

    When I click "français" in the "sidebar"
    Then the region "ec_footer" contains the links:
      | Site web de la Commission européenne    | https://commission.europa.eu/index_fr                                               |
      | Qui nous sommes                         | https://commission.europa.eu/about_fr                                               |
      | Nous contacter                          | https://commission.europa.eu/about/contact_fr                                       |
      | Priorités                               | https://commission.europa.eu/priorities-2024-2029_fr                                |
      | Thèmes                                  | https://commission.europa.eu/topics_fr                                              |
      | Financement et appels d'offres          | https://commission.europa.eu/funding-tenders_fr                                     |
      | Emplois                                 | https://commission.europa.eu/get-involved/jobs-european-commission_fr               |
      | Coin presse                             | https://ec.europa.eu/commission/presscorner/home/fr                                 |
      | Événements                              | https://commission.europa.eu/get-involved/events_fr                                 |
      | Facebook                                | https://www.facebook.com/EuropeanCommission                                         |
      | Bluesky                                 | https://bsky.app/profile/ec.europa.eu                                               |
      | Mastodon                                | https://ec.social-network.europa.eu/@EUCommission                                   |
      | Youtube                                 | https://www.youtube.com/user/eutube                                                 |
      | LinkedIn                                | http://www.linkedin.com/company/european-commission                                 |
      | Other                                   | https://european-union.europa.eu/contact-eu/social-media-channels_fr                |
      | Nous contacter                          | https://commission.europa.eu/about/contact_fr                                       |
      | Signaler une vulnérabilité informatique | https://commission.europa.eu/legal-notice/vulnerability-disclosure-policy_fr        |
      | Les langues sur nos sites web           | https://commission.europa.eu/languages-our-websites_fr                              |
      | Cookies                                 | https://commission.europa.eu/cookies-policy_fr                                      |
      | Protection de la vie privée             | https://commission.europa.eu/privacy-policy-websites-managed-european-commission_fr |
      | Avis juridique                          | https://commission.europa.eu/legal-notice_fr                                        |
    Examples:
      | path  |
      | home  |
      | login |

  Scenario Outline: Urls are correctly shown in the EU corporate footer block when page and language are changed.
    Given the following languages are available:
      | languages |
      | en        |
      | fr        |
    And I set the site owner to "Directorate-General for Digital Services, Directorate-General for Agriculture and Rural Development"
    When I am on "the <path> page"
    Then I should see "This site is managed by: Directorate-General for Digital Services, Directorate-General for Agriculture and Rural Development" in the "eu_footer" region
    And I should see "Social media" in the "eu_footer" region
    And I should see "EU institutions and bodies" in the "eu_footer" region
    And I should see "Legal" in the "eu_footer_bottom_title" region
    And the region "eu_footer" contains the links:
      | Call us 00 800 6 7 8 9 10 11          | tel:0080067891011                                                                                                         |
      | Use other telephone options           | https://european-union.europa.eu/contact-eu/call-us_en                                                                    |
      | Write to us via our contact form      | https://european-union.europa.eu/contact-eu/write-us_en                                                                   |
      | Meet us at one of the EU centres      | https://european-union.europa.eu/contact-eu/meet-us_en                                                                    |

      | Search for EU social media channels   | https://european-union.europa.eu/contact-eu/social-media-channels_en                                                      |

      | Search all EU institutions and bodies | https://european-union.europa.eu/institutions-law-budget/institutions-and-bodies/search-all-eu-institutions-and-bodies_en |

      | Languages on our websites             | https://european-union.europa.eu/languages-our-websites_en                                                                |
      | Privacy policy                        | https://european-union.europa.eu/privacy-policy_en                                                                        |
      | Legal notice                          | https://european-union.europa.eu/legal-notice_en                                                                          |
      | Cookies                               | https://european-union.europa.eu/cookies_en                                                                               |
      | Accessibility                         | https://european-union.europa.eu/accessibility-statement_en                                                               |
    And the region "eu_footer" does not contain the links:
      | Presidency of the Council of the EU | https://www.romania2019.eu/home/                |
      | About this site                     | https://europa.eu/european-union/abouteuropa_en |

    When I click "français" in the "sidebar"
    Then I should see "Institutions et organes de l'UE" in the "eu_footer" region
    And the region "eu_footer" contains the links:
      | Appelez-nous au 00 800 6 7 8 9 10 11                   | tel:0080067891011                                                                                                         |
      | Utilisez d'autres options téléphoniques                | https://european-union.europa.eu/contact-eu/call-us_fr                                                                    |
      | Écrivez-nous au moyen de notre formulaire de contact   | https://european-union.europa.eu/contact-eu/write-us_fr                                                                   |
      | Rencontrez-nous dans un des centres de l’UE            | https://european-union.europa.eu/contact-eu/meet-us_fr                                                                    |

      | Rechercher les comptes de l’UE sur les réseaux sociaux | https://european-union.europa.eu/contact-eu/social-media-channels_fr                                                      |

      | Rechercher tous les organes et institutions de l’UE    | https://european-union.europa.eu/institutions-law-budget/institutions-and-bodies/search-all-eu-institutions-and-bodies_fr |

      | Les langues sur nos sites web                          | https://european-union.europa.eu/languages-our-websites_fr                                                                |
      | Protection de la vie privée                            | https://european-union.europa.eu/privacy-policy_fr                                                                        |
      | Avis juridique                                         | https://european-union.europa.eu/legal-notice_fr                                                                          |
      | Cookies                                                | https://european-union.europa.eu/cookies_fr                                                                               |
      | Accessibilité                                          | https://european-union.europa.eu/accessibility-statement_fr                                                               |
    And the region "eu_footer" does not contain the links:
      | Présidence du Conseil de l'UE | https://www.romania2019.eu/page-daccueil/       |
      | À propos de ce site           | https://europa.eu/european-union/abouteuropa_fr |

    Examples:
      | path  |
      | home  |
      | login |

  Scenario Outline: The custom footer block shows the correct links in different languages
    Given I am on "the <path> page"
    And the region "ec_footer" contains the links:
      | Custom Contact            | https://commission.europa.eu/contact_en                              |
      | Custom Legal notice       | https://commission.europa.eu/legal-notice_en                         |
      | Custom Facebook           | https://www.facebook.com/EuropeanCommission                          |
      | Custom X                  | https://twitter.com/EU_commission                                    |
      | Custom Other social media | https://european-union.europa.eu/contact-eu/social-media-channels_en |

    Examples:
      | path  |
      | home  |
      | login |
