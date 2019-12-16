<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\Behat;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use PHPUnit\Framework\Assert;

/**
 * Defines generic step definitions.
 *
 * This context contains step definitions that are supposed to be used only
 * when testing this module, do not reuse it in your project.
 */
class DrupalContext extends RawDrupalContext {

  /**
   * Checks that the given select field has the options listed in the table.
   *
   * | option 1 |
   * | option 2 |
   * |   ...    |
   *
   * @Then I should have the following options for the :select select:
   */
  public function assertSelectOptions(string $select, TableNode $options): void {
    // Retrieve the specified field.
    if (!$field = $this->getSession()->getPage()->findField($select)) {
      throw new ExpectationException("Field '$select' not found.", $this->getSession());
    }

    // Retrieve the options table from the test scenario and flatten it.
    $expected_options = $options->getRows();
    array_walk($expected_options, function (&$value) {
      $value = reset($value);
    });

    // Retrieve the actual options that are shown in the select field.
    $actual_options = $field->findAll('css', 'option');

    // Convert into a flat list of option text strings.
    array_walk($actual_options, function (&$value) {
      $value = $value->getText();
    });

    Assert::assertEquals($expected_options, $actual_options);
  }

}
