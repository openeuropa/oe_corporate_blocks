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
   * Asserts that certain links available in a region.
   *
   * @Then the region :region contains the links:
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
   * Asserts that certain links are not available in a region.
   *
   * @Then the region :region does not contain the links:
   *
   * @throws \Exception
   */
  public function assertNoLinksInRegion(string $region, TableNode $links): void {
    $region_object = $this->getSession()->getPage()->find('region', $region);
    foreach ($links->getRows() as $row) {
      $link_object = $region_object->findLink($row[0]);

      if (!empty($link_object) && $link_object->getAttribute('href') === $row[1]) {
        throw new \Exception(sprintf('The link "%s" was found in the region "%s" on the page %s', $row[0], $region, $this->getSession()->getCurrentUrl()));
      }

    }
  }

}
