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
    Then I should see "European Commission" in the "ec_footer" region
    And I should see "Follow the European Commission" in the "ec_footer" region
    And I should see "European Union" in the "ec_footer" region
    And Links in the "ec_footer" region contains the links:
      | European Commission website             | https://ec.europa.eu/info/index_en                                          |
      | Facebook                                | https://www.facebook.com/EuropeanCommission                                 |
      | Twitter                                 | https://twitter.com/EU_commission                                           |
      | Other social media                      | https://europa.eu/european-union/contact/social-networks_en#n:+i:4+e:1+t:+s |
      | EU institutions                         | https://europa.eu/european-union/about-eu/institutions-bodies_en            |
      | European Union                          | https://europa.eu/european-union/index_en                                   |
      | About the Commission's new web presence | https://ec.europa.eu/info/about-commissions-new-web-presence_en             |
      | Language policy                         | https://ec.europa.eu/info/language-policy_en                                |
      | Resources for partners                  | https://ec.europa.eu/info/resources-partners_en                             |
      | Cookies                                 | https://ec.europa.eu/info/cookies_en                                        |
      | Privacy policy                          | https://ec.europa.eu/info/privacy-policy_en                                 |
      | Legal notice                            | https://ec.europa.eu/info/legal-notice_en                                   |
      | Contact                                 | https://ec.europa.eu/info/contact_en                                        |

    When I click "français" in the "header"
    Then I should see "Commission européenne" in the "ec_footer" region
    And I should see "Suivre la Commission européenne" in the "ec_footer" region
    And I should see "Union européenne" in the "ec_footer" region
    And Links in the "ec_footer" region contains the links:
      | Site web de la Commission européenne                         | https://ec.europa.eu/info/index_fr                                          |
      | Facebook                                                     | https://www.facebook.com/EuropeanCommission                                 |
      | Twitter                                                      | https://twitter.com/EU_commission                                           |
      | Autres réseaux sociaux                                       | https://europa.eu/european-union/contact/social-networks_fr#n:+i:4+e:1+t:+s |
      | Institutions de l’UE                                         | https://europa.eu/european-union/about-eu/institutions-bodies_fr            |
      | Union européenne                                             | https://europa.eu/european-union/index_fr                                   |
      | À propos de la nouvelle présence de la Commission sur le web | https://ec.europa.eu/info/about-commissions-new-web-presence_fr             |
      | Politique linguistique                                       | https://ec.europa.eu/info/language-policy_fr                                |
      | Ressources pour les partenaires                              | http://ec.europa.eu/info/resources-partners_fr                              |
      | Cookies                                                      | https://ec.europa.eu/info/cookies_fr                                        |
      | Protection de la vie privée                                  | https://ec.europa.eu/info/privacy-policy_fr                                 |
      | Avis juridique                                               | https://ec.europa.eu/info/legal-notice_fr                                   |
      | Contact                                                      | https://ec.europa.eu/info/contact_fr                                        |

    Examples:
      | path  |
      | home  |
      | login |

  Scenario Outline: On any pages we have correct urls in EU corporate footer block for many languages
    Given the following languages are available:
      | languages |
      | en        |
      | fr        |
    When I am on "the <path> page"
    And Links in the "eu_footer" region contains the links:
      | 00 800 6 7 8 9 10 11                          | tel:0080067891011                                            |
      | telephone options                             | https://europa.eu/european-union/contact/call-us_en          |
      | contact form                                  | https://europa.eu/european-union/contact/write-to-us_en      |
      | local EU office                               | https://europa.eu/european-union/contact/meet-us_en          |

      | Search for EU social media channels           | https://europa.eu/european-union/contact/social-networks_en  |

      | European Parliament                           | http://www.europarl.europa.eu/portal/                        |
      | European Council                              | http://www.consilium.europa.eu/en/european-council/          |
      | Council of the European Union                 | http://www.consilium.europa.eu/en/home/                      |
      | Presidency of the Council of the EU           | https://www.romania2019.eu/home/                             |
      | European Commission                           | https://ec.europa.eu/commission/index_en                     |
      | Court of Justice of the European Union (CJEU) | http://curia.europa.eu/jcms/jcms/j_6/en/                     |
      | European Central Bank (ECB)                   | https://www.ecb.europa.eu/home/html/index.en.html            |
      | European Court of Auditors (ECA)              | http://www.eca.europa.eu/en                                  |
      | European External Action Service (EEAS)       | https://eeas.europa.eu/headquarters/headquarters-homepage_en |
      | European Economic and Social Committee (EESC) | http://www.eesc.europa.eu/?i=portal.en.home                  |
      | European Committee of the Regions (CoR)       | http://cor.europa.eu/en/                                     |
      | European Investment Bank (EIB)                | https://www.eib.org/en/index.htm                             |
      | European Data Protection Supervisor (EDPS)    | https://secure.edps.europa.eu/EDPSWEB/edps/EDPS?lang=en      |

      | Sitemap                                       | /european-union/abouteuropa/sitemap_en                       |
      | About this site                               | /european-union/abouteuropa_en                               |
      | Language policy                               | /european-union/abouteuropa/language-policy_en               |
      | Privacy policy                                | /european-union/abouteuropa/privacy-policy_en                |
      | Legal notice                                  | /european-union/abouteuropa/legal_notices_en                 |
      | Cookies                                       | /european-union/abouteuropa/cookies_en                       |

    When I click "français" in the "header"
    And Links in the "eu_footer" region contains the links:
      | 00 800 6 7 8 9 10 11                                   | tel:0080067891011                                                |
      | options téléphoniques                                  | https://europa.eu/european-union/contact/call-us_fr              |
      | formulaire de contact                                  | https://europa.eu/european-union/contact/write-to-us_fr          |
      | bureau local de l’UE                                   | https://europa.eu/european-union/contact/meet-us_fr              |

      | Rechercher les comptes de l’UE sur les réseaux sociaux | https://europa.eu/european-union/contact/social-networks_fr      |

      | European Parliament                                    | http://www.europarl.europa.eu/portal/fr                          |
      | Conseil européen                                       | http://www.consilium.europa.eu/fr/european-council/              |
      | Conseil de l'Union européenne                          | http://www.consilium.europa.eu/fr/home/                          |
      | Présidence du Conseil de l'UE                          | https://www.romania2019.eu/page-daccueil/                        |
      | Commission européenne                                  | https://ec.europa.eu/commission/index_fr                         |
      | Cour de justice de l'Union européenne (CJUE)           | http://curia.europa.eu/jcms/jcms/j_6/fr/                         |
      | Banque centrale européenne (BCE)                       | https://www.ecb.europa.eu/home/languagepolicy/html/index.fr.html |
      | Cour des comptes européenne                            | http://www.eca.europa.eu/fr                                      |
      | Service européen pour l’action extérieure (SEAE)       | http://eeas.europa.eu/index_fr.htm                               |
      | Comité économique et social européen (CESE)            | http://www.eesc.europa.eu/?i=portal.fr.home                      |
      | Comité européen des régions (CdR)                      | https://cor.europa.eu/fr                                         |
      | Banque européenne d'investissement (BEI)               | https://www.eib.org/fr/index.htm                                 |
      | Contrôleur européen de la protection des données       | https://secure.edps.europa.eu/EDPSWEB/edps/EDPS?lang=fr          |

      | Plan du site                                           | /european-union/abouteuropa/sitemap_fr                           |
      | À propos de ce site                                    | /european-union/abouteuropa_fr                                   |
      | Politique linguistique                                 | /european-union/abouteuropa/language-policy_fr                   |
      | Protection de la vie privée                            | /european-union/abouteuropa/privacy-policy_fr                    |
      | Avis juridique                                         | /european-union/abouteuropa/legal_notices_fr                     |
      | Cookies                                                | /european-union/abouteuropa/cookies_fr                           |

    Examples:
      | path  |
      | home  |
      | login |

