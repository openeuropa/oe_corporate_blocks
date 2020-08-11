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
    When I am on "the <path> page"
    Then I should see "This site is managed by the Directorate-General for Communication" in the "ec_footer" region
    And the region "ec_footer" contains the links:
      | European Commission website                      | https://ec.europa.eu/info/index_en                                          |
      | Strategy                                         | https://ec.europa.eu/info/strategy_en                                       |
      | About the European Commission                    | https://ec.europa.eu/info/about-european-commission_en                      |
      | Business, Economy, Euro                          | https://ec.europa.eu/info/business-economy-euro_en                          |
      | Live, work, travel in the EU                     | https://ec.europa.eu/info/live-work-travel-eu_en                            |
      | Law                                              | https://ec.europa.eu/info/law_en                                            |
      | Funding, Tenders                                 | https://ec.europa.eu/info/funding-tenders_en                                |
      | Research and innovation                          | https://ec.europa.eu/info/research-and-innovation_en                        |
      | Energy, Climate change, Environment              | https://ec.europa.eu/info/energy-climate-change-environment_en              |
      | Education                                        | https://ec.europa.eu/info/education_en                                      |
      | Aid, Development cooperation, Fundamental rights | https://ec.europa.eu/info/aid-development-cooperation-fundamental-rights_en |
      | Food, Farming, Fisheries                         | https://ec.europa.eu/info/food-farming-fisheries_en                         |
      | EU regional and urban development                | https://ec.europa.eu/info/eu-regional-and-urban-development_en              |
      | Jobs at the European Commission                  | https://ec.europa.eu/info/jobs-european-commission_en                       |
      | Statistics                                       | https://ec.europa.eu/info/statistics_en                                     |
      | News                                             | https://ec.europa.eu/commission/presscorner/home/en                         |
      | Events                                           | https://ec.europa.eu/info/events_en                                         |
      | Publications                                     | https://ec.europa.eu/info/publications_en                                   |
      | Contact the European Commission                  | https://ec.europa.eu/info/about-european-commission/contact_en              |
      | Follow the European Commission on social media   | https://europa.eu/european-union/contact/social-networks_en#n:+i:4+e:1+t:+s |
      | Resources for partners                           | https://ec.europa.eu/info/resources-partners_en                             |
      | Language policy                                  | https://ec.europa.eu/info/language-policy_en                                |
      | Cookies                                          | https://ec.europa.eu/info/cookies_en                                        |
      | Privacy policy                                   | https://ec.europa.eu/info/privacy-policy_en                                 |
      | Legal notice                                     | https://ec.europa.eu/info/legal-notice_en                                   |
      | Brexit content disclaimer                        | https://ec.europa.eu/info/brexit-content-disclaimer_en                      |

    When I click "français" in the "header"
    Then I should see "Ce site est géré par la direction générale de la communication" in the "ec_footer" region
    And the region "ec_footer" contains the links:
      | Site web de la Commission européenne                      | https://ec.europa.eu/info/index_fr                                          |
      | Stratégie                                                 | https://ec.europa.eu/info/strategy_fr                                       |
      | À propos de la Commission européenne                      | https://ec.europa.eu/info/about-european-commission_fr                      |
      | Entreprises, économie et euro                             | https://ec.europa.eu/info/business-economy-euro_fr                          |
      | Vivre, travailler et voyager dans l’UE                    | https://ec.europa.eu/info/live-work-travel-eu_fr                            |
      | Législation                                               | https://ec.europa.eu/info/law_fr                                            |
      | Financement, appels d’offres                              | https://ec.europa.eu/info/funding-tenders_fr                                |
      | Recherche et innovation                                   | https://ec.europa.eu/info/research-and-innovation_fr                        |
      | Énergie, changement climatique, environnement             | https://ec.europa.eu/info/energy-climate-change-environment_fr              |
      | Éducation                                                 | https://ec.europa.eu/info/education_fr                                      |
      | Aide, coopération au développement et droits fondamentaux | https://ec.europa.eu/info/aid-development-cooperation-fundamental-rights_fr |
      | Alimentation, agriculture et pêche                        | https://ec.europa.eu/info/food-farming-fisheries_fr                         |
      | Développement régional et urbain de l’UE                  | https://ec.europa.eu/info/eu-regional-and-urban-development_fr              |
      | Travailler à la Commission européenne                     | https://ec.europa.eu/info/jobs-european-commission_fr                       |
      | Statistiques                                              | https://ec.europa.eu/info/statistics_fr                                     |
      | Actualité                                                 | https://ec.europa.eu/commission/presscorner/home/fr                         |
      | Événements                                                | https://ec.europa.eu/info/events_fr                                         |
      | Publications                                              | https://ec.europa.eu/info/publications_fr                                   |
      | Contacter la Commission européenne                        | https://ec.europa.eu/info/about-european-commission/contact_fr              |
      | Suivre la Commission européenne sur les médias sociaux    | https://europa.eu/european-union/contact/social-networks_fr#n:+i:4+e:1+t:+s |
      | Ressources pour les partenaires                           | https://ec.europa.eu/info/resources-partners_fr                             |
      | Politique linguistique                                    | https://ec.europa.eu/info/language-policy_fr                                |
      | Cookies                                                   | https://ec.europa.eu/info/cookies_fr                                        |
      | Protection de la vie privée                               | https://ec.europa.eu/info/privacy-policy_fr                                 |
      | Avis juridique                                            | https://ec.europa.eu/info/legal-notice_fr                                   |
      | Brexit: clause de non-responsabilité                      | https://ec.europa.eu/info/brexit-content-disclaimer_fr                      |
    Examples:
      | path  |
      | home  |
      | login |

  Scenario Outline: Urls are correctly shown in the EU corporate footer block when page and language are changed.
    Given the following languages are available:
      | languages |
      | en        |
      | fr        |
    When I am on "the <path> page"
    Then I should see "This site is managed by the European Commission, Directorate-General for Communication (DG COMM)" in the "eu_footer" region
    And I should see "Social media" in the "eu_footer" region
    And I should see "EU institutions" in the "eu_footer" region
    And I should see "Legal" in the "eu_footer_bottom_title" region
    And the region "eu_footer" contains the links:
      | 00 800 6 7 8 9 10 11                          | tel:0080067891011                                            |
      | telephone options                             | https://europa.eu/european-union/contact/call-us_en          |
      | contact form                                  | https://europa.eu/european-union/contact/write-to-us_en      |
      | local EU office                               | https://europa.eu/european-union/contact/meet-us_en          |

      | Search for EU social media channels           | https://europa.eu/european-union/contact/social-networks_en  |

      | European Parliament                           | http://www.europarl.europa.eu/portal/                        |
      | European Council                              | http://www.consilium.europa.eu/en/european-council/          |
      | Council of the European Union                 | http://www.consilium.europa.eu/en/home/                      |
      | European Commission                           | https://ec.europa.eu/commission/index_en                     |
      | Court of Justice of the European Union (CJEU) | http://curia.europa.eu/jcms/jcms/j_6/en/                     |
      | European Central Bank (ECB)                   | https://www.ecb.europa.eu/home/html/index.en.html            |
      | European Court of Auditors (ECA)              | http://www.eca.europa.eu/en                                  |
      | European External Action Service (EEAS)       | https://eeas.europa.eu/headquarters/headquarters-homepage_en |
      | European Economic and Social Committee (EESC) | http://www.eesc.europa.eu/?i=portal.en.home                  |
      | European Committee of the Regions (CoR)       | http://cor.europa.eu/en/                                     |
      | European Investment Bank (EIB)                | https://www.eib.org/en/index.htm                             |
      | European Data Protection Supervisor (EDPS)    | https://secure.edps.europa.eu/EDPSWEB/edps/EDPS?lang=en      |

      | About this site                               | https://europa.eu/european-union/abouteuropa_en                               |
      | Language policy                               | https://europa.eu/european-union/abouteuropa/language-policy_en               |
      | Privacy policy                                | https://europa.eu/european-union/abouteuropa/privacy-policy_en                |
      | Legal notice                                  | https://europa.eu/european-union/abouteuropa/legal_notices_en                 |
      | Brexit content disclaimer                     | https://europa.eu/european-union/brexit-content-disclaimer_en                 |
      | Cookies                                       | https://europa.eu/european-union/abouteuropa/cookies_en                       |
    And the region "eu_footer" does not contain the links:
      | Presidency of the Council of the EU | https://www.romania2019.eu/home/ |

    When I click "français" in the "header"
    Then I should see "Institutions de l’UE" in the "eu_footer" region
    And the region "eu_footer" contains the links:
      | 00 800 6 7 8 9 10 11                                   | tel:0080067891011                                                |
      | options téléphoniques                                  | https://europa.eu/european-union/contact/call-us_fr              |
      | formulaire de contact                                  | https://europa.eu/european-union/contact/write-to-us_fr          |
      | bureau local de l’UE                                   | https://europa.eu/european-union/contact/meet-us_fr              |

      | Rechercher les comptes de l’UE sur les réseaux sociaux | https://europa.eu/european-union/contact/social-networks_fr      |

      | European Parliament                                    | http://www.europarl.europa.eu/portal/fr                          |
      | Conseil européen                                       | http://www.consilium.europa.eu/fr/european-council/              |
      | Conseil de l'Union européenne                          | http://www.consilium.europa.eu/fr/home/                          |
      | Commission européenne                                  | https://ec.europa.eu/commission/index_fr                         |
      | Cour de justice de l'Union européenne (CJUE)           | http://curia.europa.eu/jcms/jcms/j_6/fr/                         |
      | Banque centrale européenne (BCE)                       | https://www.ecb.europa.eu/home/languagepolicy/html/index.fr.html |
      | Cour des comptes européenne                            | http://www.eca.europa.eu/fr                                      |
      | Service européen pour l’action extérieure (SEAE)       | http://eeas.europa.eu/index_fr.htm                               |
      | Comité économique et social européen (CESE)            | http://www.eesc.europa.eu/?i=portal.fr.home                      |
      | Comité européen des régions (CdR)                      | https://cor.europa.eu/fr                                         |
      | Banque européenne d'investissement (BEI)               | https://www.eib.org/fr/index.htm                                 |
      | Contrôleur européen de la protection des données       | https://secure.edps.europa.eu/EDPSWEB/edps/EDPS?lang=fr          |

      | À propos de ce site                                    | https://europa.eu/european-union/abouteuropa_fr                                   |
      | Politique linguistique                                 | https://europa.eu/european-union/abouteuropa/language-policy_fr                   |
      | Protection de la vie privée                            | https://europa.eu/european-union/abouteuropa/privacy-policy_fr                    |
      | Avis juridique                                         | https://europa.eu/european-union/abouteuropa/legal_notices_fr                     |
      | Brexit: clause de non-responsabilité                   | https://europa.eu/european-union/brexit-content-disclaimer_fr                 |
      | Cookies                                                | https://europa.eu/european-union/abouteuropa/cookies_fr                           |
    And the region "eu_footer" does not contain the links:
      | Présidence du Conseil de l'UE | https://www.romania2019.eu/page-daccueil/ |

    Examples:
      | path  |
      | home  |
      | login |


  Scenario Outline: The custom footer block shows the correct links in different languages
    Given I am on "the <path> page"
    And the region "ec_footer" contains the links:
      | Custom Contact            | https://ec.europa.eu/info/contact_en                                        |
      | Custom Legal notice       | https://ec.europa.eu/info/legal-notice_en                                   |
      | Custom Facebook           | https://www.facebook.com/EuropeanCommission                                 |
      | Custom Twitter            | https://twitter.com/EU_commission                                           |
      | Custom Other social media | https://europa.eu/european-union/contact/social-networks_en#n:+i:4+e:1+t:+s |

    Examples:
      | path  |
      | home  |
      | login |
