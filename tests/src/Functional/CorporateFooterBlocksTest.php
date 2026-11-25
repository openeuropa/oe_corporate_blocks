<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Drupal\Core\Config\FileStorage;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\oe_corporate_blocks\Traits\FooterBlocksTestTrait;
use OpenEuropa\TestingUtilities\Traits\CachedDatabaseInstallTrait;

/**
 * Tests the rendering of the EC and EU corporate footer blocks.
 */
class CorporateFooterBlocksTest extends BrowserTestBase {

  use CachedDatabaseInstallTrait;
  use FooterBlocksTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'config',
    'config_translation',
    'dynamic_page_cache',
    'language',
    'locale',
    'oe_corporate_blocks',
    'oe_corporate_site_info',
    'page_cache',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->cacheDbInstall = TRUE;
    parent::setUp();

    $this->addFrenchLanguage(TRUE);
    $this->placeFooterBlocks();

    $this->setSiteOwners([
      'Directorate-General for Digital Services',
      'Directorate-General for Agriculture and Rural Development',
    ]);
  }

  /**
   * Tests the EC footer block links in multiple languages and pages.
   */
  public function testEcFooterBlock(): void {
    foreach ($this->getPagePaths() as $path) {
      $this->visitPage($path);
      $this->assertTextInRegion('This site is managed by: Directorate-General for Digital Services, Directorate-General for Agriculture and Rural Development', 'ec_footer');
      $this->assertRegionContainsLinks('ec_footer', [
        'European Commission website' => 'https://commission.europa.eu/index_en',
        'About us' => 'https://commission.europa.eu/about_en',
        'Contact us' => 'https://commission.europa.eu/about/contact_en',
        'Priorities' => 'https://commission.europa.eu/priorities-2024-2029_en',
        'Topics' => 'https://commission.europa.eu/topics_en',
        'Funding and tenders' => 'https://commission.europa.eu/funding-tenders_en',
        'Jobs' => 'https://commission.europa.eu/get-involved/jobs-european-commission_en',
        'Press corner' => 'https://ec.europa.eu/commission/presscorner/home/en',
        'Events' => 'https://commission.europa.eu/get-involved/events_en',
        'Facebook' => 'https://www.facebook.com/EuropeanCommission',
        'Bluesky' => 'https://bsky.app/profile/ec.europa.eu',
        'Mastodon' => 'https://ec.social-network.europa.eu/@EUCommission',
        'Youtube' => 'https://www.youtube.com/user/eutube',
        'LinkedIn' => 'http://www.linkedin.com/company/european-commission',
        'Other' => 'https://european-union.europa.eu/contact-eu/social-media-channels_en',
        'Report an IT vulnerability' => 'https://commission.europa.eu/legal-notice/vulnerability-disclosure-policy_en',
        'Languages on our websites' => 'https://commission.europa.eu/languages-our-websites_en',
        'Cookies' => 'https://commission.europa.eu/cookies-policy_en',
        'Privacy policy' => 'https://commission.europa.eu/privacy-policy-websites-managed-european-commission_en',
        'Legal notice' => 'https://commission.europa.eu/legal-notice_en',
      ]);

      // Switch the page to French.
      $this->visitPage($path, 'fr');
      $this->assertRegionContainsLinks('ec_footer', [
        'Site web de la Commission européenne' => 'https://commission.europa.eu/index_fr',
        'Qui nous sommes' => 'https://commission.europa.eu/about_fr',
        'Nous contacter' => 'https://commission.europa.eu/about/contact_fr',
        'Priorités' => 'https://commission.europa.eu/priorities-2024-2029_fr',
        'Thèmes' => 'https://commission.europa.eu/topics_fr',
        "Financement et appels d'offres" => 'https://commission.europa.eu/funding-tenders_fr',
        'Emplois' => 'https://commission.europa.eu/get-involved/jobs-european-commission_fr',
        'Coin presse' => 'https://ec.europa.eu/commission/presscorner/home/fr',
        'Événements' => 'https://commission.europa.eu/get-involved/events_fr',
        'Facebook' => 'https://www.facebook.com/EuropeanCommission',
        'Bluesky' => 'https://bsky.app/profile/ec.europa.eu',
        'Mastodon' => 'https://ec.social-network.europa.eu/@EUCommission',
        'Youtube' => 'https://www.youtube.com/user/eutube',
        'LinkedIn' => 'http://www.linkedin.com/company/european-commission',
        'Other' => 'https://european-union.europa.eu/contact-eu/social-media-channels_fr',
        'Signaler une vulnérabilité informatique' => 'https://commission.europa.eu/legal-notice/vulnerability-disclosure-policy_fr',
        'Les langues sur nos sites web' => 'https://commission.europa.eu/languages-our-websites_fr',
        'Cookies' => 'https://commission.europa.eu/cookies-policy_fr',
        'Protection de la vie privée' => 'https://commission.europa.eu/privacy-policy-websites-managed-european-commission_fr',
        'Avis juridique' => 'https://commission.europa.eu/legal-notice_fr',
      ]);
    }
  }

  /**
   * Tests the EU footer block links in multiple languages and pages.
   */
  public function testEuFooterBlock(): void {
    foreach ($this->getPagePaths() as $path) {
      $this->visitPage($path);
      $this->assertTextInRegion('This site is managed by: Directorate-General for Digital Services, Directorate-General for Agriculture and Rural Development', 'eu_footer');
      $this->assertTextInRegion('Social media', 'eu_footer');
      $this->assertTextInRegion('EU institutions and bodies', 'eu_footer');
      $this->assertTextInRegion('Legal', 'eu_footer_bottom_title');
      $this->assertRegionContainsLinks('eu_footer', [
        'Call us 00 800 6 7 8 9 10 11' => 'tel:0080067891011',
        'Use other telephone options' => 'https://european-union.europa.eu/contact-eu/call-us_en',
        'Write to us via our contact form' => 'https://european-union.europa.eu/contact-eu/write-us_en',
        'Meet us at one of the EU centres' => 'https://european-union.europa.eu/contact-eu/meet-us_en',
        'Search for EU social media channels' => 'https://european-union.europa.eu/contact-eu/social-media-channels_en',
        'Search all EU institutions and bodies' => 'https://european-union.europa.eu/institutions-law-budget/institutions-and-bodies/search-all-eu-institutions-and-bodies_en',
        'Languages on our websites' => 'https://european-union.europa.eu/languages-our-websites_en',
        'Privacy policy' => 'https://european-union.europa.eu/privacy-policy_en',
        'Legal notice' => 'https://european-union.europa.eu/legal-notice_en',
        'Cookies' => 'https://european-union.europa.eu/cookies_en',
        'Accessibility' => 'https://european-union.europa.eu/accessibility-statement_en',
      ]);
      $this->assertRegionNotContainsLinks('eu_footer', [
        'Presidency of the Council of the EU' => 'https://www.romania2019.eu/home/',
        'About this site' => 'https://europa.eu/european-union/abouteuropa_en',
      ]);

      // Switch the page to French.
      $this->visitPage($path, 'fr');
      $this->assertTextInRegion("Institutions et organes de l'UE", 'eu_footer');
      $this->assertRegionContainsLinks('eu_footer', [
        'Appelez-nous au 00 800 6 7 8 9 10 11' => 'tel:0080067891011',
        "Utilisez d'autres options téléphoniques" => 'https://european-union.europa.eu/contact-eu/call-us_fr',
        'Écrivez-nous au moyen de notre formulaire de contact' => 'https://european-union.europa.eu/contact-eu/write-us_fr',
        'Rencontrez-nous dans un des centres de l’UE' => 'https://european-union.europa.eu/contact-eu/meet-us_fr',
        'Rechercher les comptes de l’UE sur les réseaux sociaux' => 'https://european-union.europa.eu/contact-eu/social-media-channels_fr',
        'Rechercher tous les organes et institutions de l’UE' => 'https://european-union.europa.eu/institutions-law-budget/institutions-and-bodies/search-all-eu-institutions-and-bodies_fr',
        'Les langues sur nos sites web' => 'https://european-union.europa.eu/languages-our-websites_fr',
        'Protection de la vie privée' => 'https://european-union.europa.eu/privacy-policy_fr',
        'Avis juridique' => 'https://european-union.europa.eu/legal-notice_fr',
        'Cookies' => 'https://european-union.europa.eu/cookies_fr',
        'Accessibilité' => 'https://european-union.europa.eu/accessibility-statement_fr',
      ]);
      $this->assertRegionNotContainsLinks('eu_footer', [
        "Présidence du Conseil de l'UE" => 'https://www.romania2019.eu/page-daccueil/',
        'À propos de ce site' => 'https://europa.eu/european-union/abouteuropa_fr',
      ]);
    }
  }

  /**
   * Tests that custom site specific footer links are shown in the EC footer.
   */
  public function testCustomFooterLinks(): void {
    $source = new FileStorage(\Drupal::service('extension.list.module')->getPath('oe_corporate_blocks_demo') . '/config/install');
    $entity_type_manager = \Drupal::entityTypeManager();
    $links = [
      'footer_link_general' => [
        'oe_corporate_blocks.footer_link.general.contact',
        'oe_corporate_blocks.footer_link.general.legal_notice',
      ],
      'footer_link_social' => [
        'oe_corporate_blocks.footer_link.social.facebook',
        'oe_corporate_blocks.footer_link.social.twitter',
        'oe_corporate_blocks.footer_link.social.other_social_media',
      ],
    ];
    foreach ($links as $entity_type_id => $config_names) {
      foreach ($config_names as $config_name) {
        $entity_type_manager->getStorage($entity_type_id)->create($source->read($config_name))->save();
      }
    }

    foreach ($this->getPagePaths() as $path) {
      $this->visitPage($path);
      $this->assertRegionContainsLinks('ec_footer', [
        'Custom Contact' => 'https://commission.europa.eu/contact_en',
        'Custom Legal notice' => 'https://commission.europa.eu/legal-notice_en',
        'Custom Facebook' => 'https://www.facebook.com/EuropeanCommission',
        'Custom X' => 'https://twitter.com/EU_commission',
        'Custom Other social media' => 'https://european-union.europa.eu/contact-eu/social-media-channels_en',
      ]);
    }
  }

  /**
   * Returns the paths of the pages on which the footer is tested.
   *
   * @return array
   *   The paths: the home page and the login page.
   */
  protected function getPagePaths(): array {
    return ['/', 'user/login'];
  }

  /**
   * Sets the site owners, given the SKOS concept labels.
   *
   * @param array $labels
   *   The SKOS concept labels.
   */
  protected function setSiteOwners(array $labels): void {
    $storage = \Drupal::entityTypeManager()->getStorage('skos_concept');
    $ids = [];
    foreach ($labels as $label) {
      $entities = $storage->loadByProperties(['pref_label' => $label]);
      $this->assertCount(1, $entities, sprintf('Expected one SKOS concept with label "%s".', $label));
      $ids[] = reset($entities)->id();
    }

    \Drupal::configFactory()
      ->getEditable('oe_corporate_site_info.settings')
      ->set('site_owners', $ids)
      ->save();
  }

}
