@api
Feature: Corporate blocks feature
  In order to be able to showcase Corporate blocks
  As an anonymous user
  I want to use corporate blocks

  Scenario: On the home page we have correct urls in corporate footer block
    Given I am on homepage
    Then Links in the "footer" region contains the links:
    | Commission and its priorities           | https://ec.europa.eu/commission/index_en                                    |
    | Policies, information and services      | https://ec.europa.eu/info/index_en                                          |
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


  Scenario: On the user login page we have correct urls in corporate footer block
    Given I am on "the login page"
    Then Links in the "footer" region contains the links:
      | Commission and its priorities           | https://ec.europa.eu/commission/index_en                                    |
      | Policies, information and services      | https://ec.europa.eu/info/index_en                                          |
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

