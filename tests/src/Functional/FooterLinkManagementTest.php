<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\oe_corporate_blocks\Traits\AssertFooterLinksTrait;
use Drupal\Tests\oe_corporate_blocks\Traits\FooterBlocksTestTrait;
use OpenEuropa\TestingUtilities\Traits\CachedDatabaseInstallTrait;

/**
 * Test CRUD operations on links and section links via the UI.
 */
class FooterLinkManagementTest extends BrowserTestBase {

  use AssertFooterLinksTrait;
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
  }

  /**
   * Test general link creation.
   */
  public function testGeneralLinkCreation(): void {
    // Create a new link section.
    $user = $this->createUser([
      'access administration pages',
      'administer site specific footer link sections',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/footer_link_section/add');

    // Get page.
    $page = $this->getSession()->getPage();

    // Create new section.
    $page->fillField('Label', 'Section 1');
    $page->fillField('Machine-readable name', 'section_1');
    $page->pressButton('Save');

    // Assert section is created with default weight.
    $this->assertLinkSectionEntity('Section 1', 0);
    $this->drupalGet('/admin/config/footer_link_section');

    // Assert that section is correctly displayed on the overview page.
    $this->findRowByLabel('Section 1');

    // Assert that general links can be assigned to a section.
    $this->drupalLogout();
    $user = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $this->drupalLogin($user);

    // Assert that "link managers" cannot manage sections.
    $this->drupalGet('/admin/config/footer_link_section');
    $this->assertSession()->pageTextContains('You are not authorized to access this page.');

    // Create new general link.
    $this->drupalGet('/admin/config/footer_link_general/add');
    $page->selectFieldOption('Section', 'Section 1');
    $page->fillField('Label', 'Link 1');
    $page->fillField('URL', 'http://example.com/link-1');
    $page->fillField('Machine-readable name', 'link_1');
    $page->pressButton('Save');

    // Assert default link values.
    $this->assertGeneralLinkEntity('Link 1', 'http://example.com/link-1', 'section_1', 0);

    // Assert link position on overview table.
    $link = $this->findRowByLabel('Link 1');
    $this->assertGeneralLinkRow($link, 'section_1', 0);
  }

  /**
   * Tests the social media footer links settings form.
   */
  public function testSocialMediaFooterLinksSettingsForm(): void {
    // Login with a user that can create social media footer links.
    $user = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/footer_link_social');

    // Assert the settings form content and the default values.
    $this->assertSession()->checkboxNotChecked('Display labels');
    $this->assertSession()->pageTextContains("Check this box if you'd like to display the social media links labels.");
    $this->assertSession()->selectExists('Alignment');
    $this->assertSession()->pageTextContains('The alignment of the social media links.');
    $this->assertSession()->fieldValueEquals('Alignment', 'horizontal');

    // Change the values and assert the config is correctly updated.
    $this->getSession()->getPage()->checkField('Display labels');
    $this->getSession()->getPage()->selectFieldOption('Alignment', 'vertical');
    $this->getSession()->getPage()->pressButton('Save configuration');

    $this->assertSession()->pageTextContains('The configuration options have been saved.');
    $this->assertSession()->checkboxChecked('Display labels');
    $this->assertSession()->fieldValueEquals('Alignment', 'vertical');
  }

  /**
   * Tests the management of the general site specific footer links.
   */
  public function testGeneralLinksSiteSpecificFooter(): void {
    $this->setUpSiteSpecificFooterTest();
    $editor = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $translator = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
      'translate configuration',
    ]);

    // Add new links for the site specific footer.
    $this->drupalLogin($editor);
    $this->drupalGet('/admin/config/footer_link_general');
    $this->assertHeading('Footer Link General entities');
    $page = $this->getSession()->getPage();
    $links = [
      '00000_eurpoean_commission_official_website' => [
        'label' => '00000 European Commission, official website',
        'url' => 'https://commission.europa.eu/index_en',
      ],
      '00001_about_the_european_commission' => [
        'label' => '00001 About the European Commission',
        'url' => 'https://commission.europa.eu/strategy_en',
      ],
    ];
    foreach ($links as $machine_name => ['label' => $label, 'url' => $url]) {
      $this->clickLink('Add footer link');
      $this->assertHeading('Add footer link general');
      $page->fillField('Label', $label);
      $page->fillField('Machine-readable name', $machine_name);
      $page->fillField('URL', $url);
      $page->selectFieldOption('Section', 'Related links');
      $page->pressButton('Save');
      $this->assertHeading('Footer Link General entities');
    }

    // Make sure that caching works correctly for anonymous users. The links
    // are shown also in French, as they are not translated yet.
    $this->drupalLogout();
    $expected_links = [
      '00000 European Commission, official website' => 'https://commission.europa.eu/index_en',
      '00001 About the European Commission' => 'https://commission.europa.eu/strategy_en',
    ];
    foreach (['ec_footer', 'eu_footer'] as $region) {
      $this->visitPage();
      $this->assertRegionContainsLinks($region, $expected_links);
      $this->visitPage('', 'fr');
      $this->assertRegionContainsLinks($region, $expected_links);
    }

    // Translate the links in French.
    $this->drupalLogin($translator);
    $translations = [
      '00000 European Commission, official website' => [
        'label' => '00000 European Commission, official website FR',
        'url' => 'https://commission.europa.eu/index_fr',
      ],
      '00001 About the European Commission' => [
        'label' => '00001 About the European Commission FR',
        'url' => 'https://commission.europa.eu/strategy_fr',
      ],
    ];
    foreach ($translations as $original_label => ['label' => $label, 'url' => $url]) {
      $this->drupalGet('/admin/config/footer_link_general');
      $this->clickLinkInRow('Translate', $original_label);
      $this->assertSession()->pageTextContains("Translations for $original_label footer link general");
      $this->clickLinkInRow('Add', 'French');
      $page->fillField('Label', $label);
      $page->fillField('URL', $url);
      $page->pressButton('Save translation');
    }

    // Change the original labels of the links.
    foreach (array_keys($translations) as $original_label) {
      $this->drupalGet('/admin/config/footer_link_general');
      $this->clickLinkInRow('Edit', $original_label);
      $page->fillField('Label', "$original_label EN");
      $page->pressButton('Save');
    }

    // Make sure that caching works correctly for anonymous users and that
    // the translated links are shown in French.
    $this->drupalLogout();
    $expected_en_links = [
      '00000 European Commission, official website EN' => 'https://commission.europa.eu/index_en',
      '00001 About the European Commission EN' => 'https://commission.europa.eu/strategy_en',
    ];
    $expected_fr_links = [
      '00000 European Commission, official website FR' => 'https://commission.europa.eu/index_fr',
      '00001 About the European Commission FR' => 'https://commission.europa.eu/strategy_fr',
    ];
    foreach (['ec_footer', 'eu_footer'] as $region) {
      $this->visitPage();
      $this->assertRegionContainsLinks($region, $expected_en_links);
      $this->visitPage('', 'fr');
      $this->assertRegionContainsLinks($region, $expected_fr_links);
    }

    // Delete the general links.
    $this->drupalLogin($translator);
    foreach (array_keys($translations) as $original_label) {
      $this->drupalGet('/admin/config/footer_link_general');
      $this->clickLinkInRow('Delete', "$original_label EN");
      $page->pressButton('Delete');
    }

    // Make sure that caching works correctly for anonymous users and that
    // the links are gone in all languages.
    $this->drupalLogout();
    foreach (['ec_footer', 'eu_footer'] as $region) {
      $this->visitPage();
      $this->assertRegionNotContainsLinks($region, $expected_en_links);
      $this->visitPage('', 'fr');
      $this->assertRegionNotContainsLinks($region, $expected_fr_links);
    }
  }

  /**
   * Tests the management of the social site specific footer links.
   */
  public function testSocialLinksSiteSpecificFooter(): void {
    $this->setUpSiteSpecificFooterTest();
    $editor = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $translator = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
      'translate configuration',
    ]);

    // Add a new social link for the site specific footer.
    $this->drupalLogin($editor);
    $this->drupalGet('/admin/config/footer_link_social');
    $this->assertHeading('Footer Link Social entities');
    $this->clickLink('Add social media footer link');
    $this->assertHeading('Add footer link social');
    $options = [];
    foreach ($this->assertSession()->selectExists('Social network')->findAll('css', 'option') as $option) {
      $options[] = $option->getText();
    }
    $this->assertEquals([
      '- Select -',
      'Bluesky',
      'Email',
      'Facebook',
      'Instagram',
      'Linkedin',
      'Mastodon',
      'Pinterest',
      'RSS',
      'Skype',
      'Telegram',
      'Threads',
      'X',
      'YouTube',
      'Other',
    ], $options);
    $page = $this->getSession()->getPage();
    $page->fillField('Label', '00000 Instagram');
    $page->fillField('Machine-readable name', '00000_instagram');
    $page->fillField('URL', 'https://www.instagram.com/europeancommission');
    $page->selectFieldOption('Social network', 'Instagram');
    $page->pressButton('Save');
    $this->assertHeading('Footer Link Social entities');

    // Make sure that caching works correctly for anonymous users. The link
    // is shown also in French, as it is not translated yet.
    $this->drupalLogout();
    $expected_links = [
      '00000 Instagram' => 'https://www.instagram.com/europeancommission',
    ];
    foreach (['ec_footer', 'eu_footer'] as $region) {
      $this->visitPage();
      $this->assertRegionContainsLinks($region, $expected_links);
      $this->visitPage('', 'fr');
      $this->assertRegionContainsLinks($region, $expected_links);
    }

    // Translate the link in French.
    $this->drupalLogin($translator);
    $this->drupalGet('/admin/config/footer_link_social');
    $this->clickLinkInRow('Translate', '00000 Instagram');
    $this->assertSession()->pageTextContains('Translations for 00000 Instagram footer link social');
    $this->clickLinkInRow('Add', 'French');
    $page->fillField('Label', '00000 Instagram FR');
    $page->fillField('URL', 'https://www.instagram.com/europeancommission?hl=fr');
    $page->pressButton('Save translation');

    // Change the original label of the link.
    $this->drupalGet('/admin/config/footer_link_social');
    $this->clickLinkInRow('Edit', '00000 Instagram');
    $page->fillField('Label', '00000 Instagram EN');
    $page->pressButton('Save');

    // Make sure that caching works correctly for anonymous users and that
    // the translated link is shown in French.
    $this->drupalLogout();
    $expected_en_links = [
      '00000 Instagram EN' => 'https://www.instagram.com/europeancommission',
    ];
    $expected_fr_links = [
      '00000 Instagram FR' => 'https://www.instagram.com/europeancommission?hl=fr',
    ];
    foreach (['ec_footer', 'eu_footer'] as $region) {
      $this->visitPage();
      $this->assertRegionContainsLinks($region, $expected_en_links);
      $this->visitPage('', 'fr');
      $this->assertRegionContainsLinks($region, $expected_fr_links);
    }

    // Delete the social link.
    $this->drupalLogin($translator);
    $this->drupalGet('/admin/config/footer_link_social');
    $this->clickLinkInRow('Delete', '00000 Instagram EN');
    $page->pressButton('Delete');

    // Make sure that caching works correctly for anonymous users and that
    // the link is gone in all languages.
    $this->drupalLogout();
    foreach (['ec_footer', 'eu_footer'] as $region) {
      $this->visitPage();
      $this->assertRegionNotContainsLinks($region, $expected_en_links);
      $this->visitPage('', 'fr');
      $this->assertRegionNotContainsLinks($region, $expected_fr_links);
    }
  }

  /**
   * Tests that invalid URLs cannot be saved in the general footer links.
   */
  public function testInvalidGeneralLinkUrls(): void {
    $this->drupalLogin($this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]));

    $cases = [
      'http://example:com' => 'The path http://example:com is invalid.',
      'htp://example.com' => 'The path htp://example.com is invalid.',
      'node/1' => 'The specified target is invalid. Manually entered paths should start with one of the following characters: / ? #',
      '//' => 'The path // is invalid.',
      '/<front>/node/1' => 'The path /<front>/node/1 is invalid.',
      '<front>/node/1' => 'The path <front>/node/1 is invalid.',
      '/<front>' => 'The path /<front> is invalid.',
    ];
    $page = $this->getSession()->getPage();
    foreach ($cases as $url => $message) {
      $this->drupalGet('/admin/config/footer_link_general/add');
      $page->fillField('Label', 'Test');
      $page->fillField('Machine-readable name', 'test');
      $page->fillField('URL', $url);
      $page->pressButton('Save');
      $this->assertSession()->statusMessageContains($message, 'error');
    }
  }

  /**
   * Prepares the site for the site specific footer link tests.
   */
  protected function setUpSiteSpecificFooterTest(): void {
    $this->addFrenchLanguage();
    $this->placeFooterBlocks();
    $this->drupalPlaceBlock('page_title_block');
    $this->drupalPlaceBlock('local_actions_block');
  }

  /**
   * Asserts that a heading with the given text exists on the page.
   *
   * @param string $text
   *   The heading text.
   */
  protected function assertHeading(string $text): void {
    $this->assertSession()->elementExists('xpath', "//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6][normalize-space()='{$text}']");
  }

  /**
   * Clicks a link inside the table row containing the given text.
   *
   * @param string $link
   *   The link locator.
   * @param string $row_text
   *   The text the table row contains.
   */
  protected function clickLinkInRow(string $link, string $row_text): void {
    $row = $this->assertSession()->elementExists('xpath', "//tr[contains(normalize-space(.), '{$row_text}')]");
    $row->clickLink($link);
  }

}
