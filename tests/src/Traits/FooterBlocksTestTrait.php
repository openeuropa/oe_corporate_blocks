<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_corporate_blocks\Traits;

use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\locale\Gettext;

/**
 * Provides helpers for testing the rendering of the corporate footer blocks.
 */
trait FooterBlocksTestTrait {

  /**
   * The CSS selectors of the footer block regions, keyed by region name.
   *
   * @var array
   */
  protected array $footerRegions = [
    'ec_footer' => '#block-ec-footer',
    'eu_footer' => '#block-eu-footer',
    'eu_footer_bottom_title' => '#block-eu-footer .footer__corporate-bottom--eu-title',
  ];

  /**
   * Places the EC and EU corporate footer blocks.
   */
  protected function placeFooterBlocks(): void {
    $this->drupalPlaceBlock('oe_corporate_blocks_ec_footer', [
      'id' => 'ec_footer',
      'region' => 'footer',
      'label_display' => '0',
    ]);
    $this->drupalPlaceBlock('oe_corporate_blocks_eu_footer', [
      'id' => 'eu_footer',
      'region' => 'footer',
      'label_display' => '0',
    ]);
  }

  /**
   * Adds the French language and optionally imports the module translations.
   *
   * @param bool $import_translations
   *   Whether to import the interface and configuration translations shipped
   *   with the module.
   */
  protected function addFrenchLanguage(bool $import_translations = FALSE): void {
    ConfigurableLanguage::createFromLangcode('fr')->save();
    if (!$import_translations) {
      return;
    }

    $file = (object) [
      'uri' => \Drupal::service('extension.list.module')->getPath('oe_corporate_blocks') . '/translations/oe_corporate_blocks-fr.po',
      'langcode' => 'fr',
    ];
    Gettext::fileToDatabase($file, []);

    /** @var \Drupal\locale\LocaleConfigManager $locale_config_manager */
    $locale_config_manager = \Drupal::service('locale.config_manager');
    $locale_config_manager->updateConfigTranslations($locale_config_manager->getComponentNames(), ['fr']);
  }

  /**
   * Visits a page, optionally in a given language.
   *
   * @param string $path
   *   The internal path, without language prefix. Empty for the front page.
   * @param string|null $langcode
   *   (optional) The language code to prefix the path with.
   */
  protected function visitPage(string $path = '', ?string $langcode = NULL): void {
    $path = trim(($langcode ? $langcode . '/' : '') . $path, '/');
    $this->drupalGet($path !== '' ? $path : '<front>');
  }

  /**
   * Asserts that the given links, with the given URLs, exist in a region.
   *
   * @param string $region
   *   The region name, as defined in ::$footerRegions.
   * @param array $links
   *   The expected hrefs, keyed by link text.
   */
  protected function assertRegionContainsLinks(string $region, array $links): void {
    $region_element = $this->assertSession()->elementExists('css', $this->footerRegions[$region]);
    foreach ($links as $text => $href) {
      $link = $region_element->findLink($text);
      $this->assertNotEmpty($link, sprintf('The link "%s" was not found in the region "%s" on the page %s.', $text, $region, $this->getUrl()));
      $this->assertEquals($href, $link->getAttribute('href'), sprintf('The link "%s" in the region "%s" does not have the correct URL.', $text, $region));
    }
  }

  /**
   * Asserts that the given links, with the given URLs, don't exist in a region.
   *
   * @param string $region
   *   The region name, as defined in ::$footerRegions.
   * @param array $links
   *   The hrefs, keyed by link text.
   */
  protected function assertRegionNotContainsLinks(string $region, array $links): void {
    $region_element = $this->assertSession()->elementExists('css', $this->footerRegions[$region]);
    foreach ($links as $text => $href) {
      $link = $region_element->findLink($text);
      $this->assertFalse($link && $link->getAttribute('href') === $href, sprintf('The link "%s" was found in the region "%s" on the page %s.', $text, $region, $this->getUrl()));
    }
  }

  /**
   * Asserts that a text is present in a region.
   *
   * @param string $text
   *   The text.
   * @param string $region
   *   The region name, as defined in ::$footerRegions.
   */
  protected function assertTextInRegion(string $text, string $region): void {
    $this->assertSession()->elementTextContains('css', $this->footerRegions[$region], $text);
  }

}
