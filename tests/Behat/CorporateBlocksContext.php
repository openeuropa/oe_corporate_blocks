<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\ConfigContext;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines step which used for behat test of Corporate blocks.
 */
class CorporateBlocksContext extends RawDrupalContext {

  /**
   * Configuration context from Drupal Behat Extension.
   *
   * @var \Drupal\DrupalExtension\Context\ConfigContext
   */
  protected $configContext;

  /**
   * Gather external contexts.
   *
   * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
   *   The scenario scope.
   *
   * @BeforeScenario
   */
  public function gatherContexts(BeforeScenarioScope $scope) {
    $environment = $scope->getEnvironment();
    $this->configContext = $environment->getContext(ConfigContext::class);
  }

  /**
   * Backup site configuration.
   *
   * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
   *   The scenario scope.
   *
   * @beforeScenario @preserve-site-switcher-configuration
   */
  public function backupSiteSwitcherConfiguration(BeforeScenarioScope $scope): void {
    $value = \Drupal::config('oe_corporate_blocks.data.site_switcher')->get('active');
    $this->configContext->setConfig('oe_corporate_blocks.data.site_switcher', 'active', $value);
  }

  /**
   * Assertion of links in region.
   *
   * @Then Links in the :region region contains the links:
   *
   * @throws \Exception
   */
  public function assertCorporateBlockLinksUrls(string $region, TableNode $links): void {
    $regionObj = $this->getSession()->getPage()->find('region', $region);
    foreach ($links->getRows() as $row) {
      $linkObj = $regionObj->findLink($row[0]);

      if (empty($linkObj)) {
        throw new \Exception(sprintf('The link "%s" was not found in the region "%s" on the page %s', $row[0], $region, $this->getSession()->getCurrentUrl()));
      }

      if ($linkObj->getAttribute('href') !== $row[1]) {
        throw new \Exception(sprintf('The link "%s" in the region "%s" have not correct url %s. Should be %s', $row[0], $region, $linkObj->getAttribute('href'), $row[1]));
      }

    }
  }

  /**
   * Assert that there are no active site switcher links.
   *
   * @Then no site switcher link should be set as active
   */
  public function assertNotActiveSiteSwitcherLink(): void {
    $this->assertSession()->elementNotExists('css', '.site-switcher a.active');
  }

  /**
   * Assert that the given site switcher link is active.
   *
   * @param string $label
   *   Link label.
   *
   * @Then the :label site switcher link should be set as active
   */
  public function assertActiveSiteSwitcherLink(string $label): void {
    $link = $this->getSession()->getPage()->find('css', '.site-switcher a.active');
    if (!$link) {
      throw new \Exception("Site switcher link '{$label}' not found.");
    }

    if ($link->getText() !== $label) {
      throw new \Exception("Site switcher link '{$label}' is not active.");
    }
  }

}
