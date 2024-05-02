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
    And I set the site owner to "Directorate-General for Digital Services"
    When I am on "the <path> page"
    Then I should see "This site is managed by: Directorate-General for Digital Services" in the "ec_footer" region
    And the region "ec_footer" contains the links:
      | European Commission website                      | https://commission.europa.eu/index_en                                                                                   |
      | Strategy                                         | https://commission.europa.eu/strategy_en                                                                                |
      | About the European Commission                    | https://commission.europa.eu/about-european-commission_en                                                               |
      | Business, Economy, Euro                          | https://commission.europa.eu/business-economy-euro_en                                                                   |
      | Live, work, travel in the EU                     | https://commission.europa.eu/live-work-travel-eu_en                                                                     |
      | Law                                              | https://commission.europa.eu/law_en                                                                                     |
      | Funding, Tenders                                 | https://commission.europa.eu/funding-tenders_en                                                                         |
      | Research and innovation                          | https://commission.europa.eu/research-and-innovation_en                                                                 |
      | Energy, Climate change, Environment              | https://commission.europa.eu/energy-climate-change-environment_en                                                       |
      | Education                                        | https://commission.europa.eu/education_en                                                                               |
      | Aid, Development cooperation, Fundamental rights | https://commission.europa.eu/aid-development-cooperation-fundamental-rights_en                                          |
      | Food, Farming, Fisheries                         | https://commission.europa.eu/food-farming-fisheries_en                                                                  |
      | EU regional and urban development                | https://commission.europa.eu/eu-regional-and-urban-development_en                                                       |
      | Jobs at the European Commission                  | https://commission.europa.eu/jobs-european-commission_en                                                                |
      | Statistics                                       | https://commission.europa.eu/statistics_en                                                                              |
      | Press Corner                                     | https://ec.europa.eu/commission/presscorner/home/en                                                                     |
      | Events                                           | https://commission.europa.eu/events_en                                                                                  |
      | Publications                                     | https://commission.europa.eu/publications_en                                                                            |
      | Contact the European Commission                  | https://commission.europa.eu/about-european-commission/contact_en                                                       |
      | Accessibility                                    | https://commission.europa.eu/accessibility-statement_en                                                                 |
      | Follow the European Commission on social media   | https://european-union.europa.eu/contact-eu/social-media-channels_en#/search?page=0&institutions=european_commission    |
      | Resources for partners                           | https://commission.europa.eu/resources-partners_en                                                                      |
      | Report an IT vulnerability                       | https://commission.europa.eu/legal-notice/vulnerability-disclosure-policy_en                                            |
      | Languages on our websites                        | https://commission.europa.eu/languages-our-websites_en                                                                  |
      | Cookies                                          | https://commission.europa.eu/cookies_en                                                                                 |
      | Privacy policy                                   | https://commission.europa.eu/privacy-policy_en                                                                          |
      | Legal notice                                     | https://commission.europa.eu/legal-notice_en                                                                            |

    When I click "français" in the "sidebar"
    Then the region "ec_footer" contains the links:
      | Site web de la Commission européenne                      | https://commission.europa.eu/index_fr                                                                                   |
      | Stratégie                                                 | https://commission.europa.eu/strategy_fr                                                                                |
      | À propos de la Commission européenne                      | https://commission.europa.eu/about-european-commission_fr                                                               |
      | Entreprises, économie et euro                             | https://commission.europa.eu/business-economy-euro_fr                                                                   |
      | Vivre, travailler et voyager dans l’UE                    | https://commission.europa.eu/live-work-travel-eu_fr                                                                     |
      | Législation                                               | https://commission.europa.eu/law_fr                                                                                     |
      | Financement, appels d’offres                              | https://commission.europa.eu/funding-tenders_fr                                                                         |
      | Recherche et innovation                                   | https://commission.europa.eu/research-and-innovation_fr                                                                 |
      | Énergie, changement climatique, environnement             | https://commission.europa.eu/energy-climate-change-environment_fr                                                       |
      | Éducation                                                 | https://commission.europa.eu/education_fr                                                                               |
      | Aide, coopération au développement et droits fondamentaux | https://commission.europa.eu/aid-development-cooperation-fundamental-rights_fr                                          |
      | Alimentation, agriculture et pêche                        | https://commission.europa.eu/food-farming-fisheries_fr                                                                  |
      | Développement régional et urbain de l’UE                  | https://commission.europa.eu/eu-regional-and-urban-development_fr                                                       |
      | Travailler à la Commission européenne                     | https://commission.europa.eu/jobs-european-commission_fr                                                                |
      | Statistiques                                              | https://commission.europa.eu/statistics_fr                                                                              |
      | Coin presse                                               | https://ec.europa.eu/commission/presscorner/home/fr                                                                     |
      | Événements                                                | https://commission.europa.eu/events_fr                                                                                  |
      | Publications                                              | https://commission.europa.eu/publications_fr                                                                            |
      | Contacter la Commission européenne                        | https://commission.europa.eu/about-european-commission/contact_fr                                                       |
      | Accessibilité                                             | https://commission.europa.eu/accessibility-statement_fr                                                                 |
      | Suivre la Commission européenne sur les médias sociaux    | https://european-union.europa.eu/contact-eu/social-media-channels_fr#/search?page=0&institutions=european_commission    |
      | Ressources pour les partenaires                           | https://commission.europa.eu/resources-partners_fr                                                                      |
      | Signaler une vulnérabilité informatique                   | https://commission.europa.eu/legal-notice/vulnerability-disclosure-policy_fr                                            |
      | Les langues sur nos sites web                             | https://commission.europa.eu/languages-our-websites_fr                                                                  |
      | Cookies                                                   | https://commission.europa.eu/cookies_fr                                                                                 |
      | Protection de la vie privée                               | https://commission.europa.eu/privacy-policy_fr                                                                          |
      | Avis juridique                                            | https://commission.europa.eu/legal-notice_fr                                                                            |
    Examples:
      | path  |
      | home  |
      | login |

  Scenario Outline: Urls are correctly shown in the EU corporate footer block when page and language are changed.
    Given the following languages are available:
      | languages |
      | en        |
      | fr        |
    And I set the site owner to "Directorate-General for Digital Services"
    When I am on "the <path> page"
    Then I should see "This site is managed by: Directorate-General for Digital Services" in the "eu_footer" region
    And I should see "Social media" in the "eu_footer" region
    And I should see "EU institutions and bodies" in the "eu_footer" region
    And I should see "Legal" in the "eu_footer_bottom_title" region
    And the region "eu_footer" contains the links:
      | Call us 00 800 6 7 8 9 10 11                  | tel:0080067891011                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
      | Use other telephone options                   | https://european-union.europa.eu/contact-eu/call-us_en                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
      | Write to us via our contact form              | https://european-union.europa.eu/contact-eu/write-us_en                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
      | Meet us at one of the EU centres              | https://european-union.europa.eu/contact-eu/meet-us_en                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

      | Search for EU social media channels           | https://european-union.europa.eu/contact-eu/social-media-channels_en                                                                                                                                                                                                                                                                                                                                                                                                                                          |

      | Search all EU institutions and bodies         | https://european-union.europa.eu/institutions-law-budget/institutions-and-bodies/search-all-eu-institutions-and-bodies_en                                                                                                                                                                                                                                                                                                                                                                                     |

      | Languages on our websites                     | https://european-union.europa.eu/languages-our-websites_en                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
      | Privacy policy                                | https://european-union.europa.eu/privacy-policy_en                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
      | Legal notice                                  | https://european-union.europa.eu/legal-notice_en                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
      | Cookies                                       | https://european-union.europa.eu/cookies_en                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
      | Accessibility                                 | https://european-union.europa.eu/accessibility-statement_en                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
    And the region "eu_footer" does not contain the links:
      | Presidency of the Council of the EU | https://www.romania2019.eu/home/                |
      | About this site                     | https://europa.eu/european-union/abouteuropa_en |

    When I click "français" in the "sidebar"
    Then I should see "Institutions et organes de l'UE" in the "eu_footer" region
    And the region "eu_footer" contains the links:
      | Appelez-nous au 00 800 6 7 8 9 10 11                   | tel:0080067891011                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
      | Utilisez d'autres options téléphoniques                | https://european-union.europa.eu/contact-eu/call-us_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
      | Écrivez-nous au moyen de notre formulaire de contact   | https://european-union.europa.eu/contact-eu/write-us_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
      | Rencontrez-nous dans un des centres de l’UE            | https://european-union.europa.eu/contact-eu/meet-us_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

      | Rechercher les comptes de l’UE sur les réseaux sociaux | https://european-union.europa.eu/contact-eu/social-media-channels_fr                                                                                                                                                                                                                                                                                                                                                                                                                                          |

      | Rechercher tous les organes et institutions de l’UE    | https://european-union.europa.eu/institutions-law-budget/institutions-and-bodies/search-all-eu-institutions-and-bodies_fr                                                                                                                                                                                                                                                                                                                                                                                     |

      | Les langues sur nos sites web                          | https://european-union.europa.eu/languages-our-websites_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
      | Protection de la vie privée                            | https://european-union.europa.eu/privacy-policy_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
      | Avis juridique                                         | https://european-union.europa.eu/legal-notice_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
      | Cookies                                                | https://european-union.europa.eu/cookies_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
      | Accessibilité                                          | https://european-union.europa.eu/accessibility-statement_fr                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
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
