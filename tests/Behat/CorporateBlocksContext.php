<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\Behat;

use Behat\Gherkin\Node\TableNode;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines step which used for behat test of Corporate blocks.
 */
class CorporateBlocksContext extends RawDrupalContext {

  /**
   * Assertion of links in region.
   *
   * @Then Links in the :region region should be correct:
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

}
