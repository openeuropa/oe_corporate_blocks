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
   * A list of css selectors needed by this context, keyed by name.
   *
   * @var array
   */
  protected $selectors = [];

  /**
   * CorporateBlocksContext constructor.
   *
   * @param array $selectors
   *   An array of css selectors, keyed by name.
   */
  public function __construct(array $selectors) {
    $this->selectors = $selectors;
  }

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
    $this->assertSession()->elementNotExists('css', $this->getSelector('site switcher active link'));
  }

  /**
   * Assert that the given site switcher link is active.
   *
   * @param string $label
   *   Link label.
   *
   * @throws \Exception
   *   Thrown when the site switcher link is not found or the wrong one is
   *   active.
   *
   * @Then the :label site switcher link should be set as active
   */
  public function assertActiveSiteSwitcherLink(string $label): void {
    $link = $this->getSession()->getPage()->find('css', $this->getSelector('site switcher active link'));
    if (!$link) {
      throw new \Exception("Site switcher link '{$label}' not found.");
    }

    if ($link->getText() !== $label) {
      throw new \Exception("Site switcher link '{$label}' is not active.");
    }
  }

  /**
   * Returns a css selector from the context configuration.
   *
   * @param string $name
   *   The selector name.
   *
   * @return string
   *   The css selector.
   *
   * @throws \Exception
   *   Thrown when the selector is not found.
   */
  protected function getSelector(string $name): string {
    if (!array_key_exists($name, $this->selectors)) {
      throw new \Exception(sprintf('Missing selector "%s".', $name));
    }

    return $this->selectors[$name];
  }

}
