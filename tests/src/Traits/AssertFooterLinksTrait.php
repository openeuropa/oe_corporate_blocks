<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\Traits;

use Behat\Mink\Element\NodeElement;
use Drupal\Component\Utility\Html;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Test\RefreshVariablesTrait;

/**
 * Provides various footer link related assertions.
 */
trait AssertFooterLinksTrait {

  use RefreshVariablesTrait;

  /**
   * Assert a general link row against given section and weight values.
   *
   * @param \Behat\Mink\Element\NodeElement $link
   *   Link table row object.
   * @param string $section
   *   Link section name.
   * @param int $weight
   *   Link weight.
   */
  protected function assertGeneralLinkRow(NodeElement $link, string $section, int $weight): void {
    $this->assertTrue($link->hasAttribute('data-drupal-selector'), 'Attribute "data-drupal-selector" not found.');
    $id = str_replace(['edit-entities-', '-'], ['', '_'], $link->getAttribute('data-drupal-selector'));
    $this->assertSession()->fieldValueEquals("entities[{$id}][section]", $section, $link);
    $this->assertSession()->fieldValueEquals("entities[{$id}][weight]", $weight, $link);
  }

  /**
   * Assert general link entity values, given its label.
   *
   * @param string $label
   *   Link label.
   * @param string $url
   *   Link URL.
   * @param string $section
   *   Link section name.
   * @param int $weight
   *   Link weight.
   */
  protected function assertGeneralLinkEntity(string $label, string $url, string $section, int $weight): void {
    $link = $this->loadLinkByLabel('footer_link_general', $label);
    $this->assertEqual($link->getUrl()->toString(), $url, "'{$label}' URL was expected to be set to '{$url}', but '{$link->getUrl()->toString()}' was found.");
    $this->assertEqual($link->getSection(), $section, "'{$label}' section was expected to be set to '{$section}', but '{$link->getSection()}' was found.");
    $this->assertEqual($link->getWeight(), $weight, "'{$label}' weight was expected to be set to '{$weight}', but '{$link->getWeight()}' was found.");
  }

  /**
   * Assert link section entity values, given its label.
   *
   * @param string $label
   *   Link label.
   * @param int $weight
   *   Link weight.
   */
  protected function assertLinkSectionEntity(string $label, int $weight): void {
    $link = $this->loadLinkByLabel('footer_link_section', $label);
    $this->assertEqual($link->getWeight(), $weight, "'{$label}' weight was expected to be set to '{$weight}', but '{$link->getWeight()}' was found.");
  }

  /**
   * Find a link row in the entities table, given its label.
   *
   * @param string $label
   *   Link label.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The row element, if any.
   */
  protected function findRowByLabel(string $label): NodeElement {
    $row_selector = strtolower(Html::getId($label));
    $xpath = "//table[@id='edit-entities']/tbody/tr[@data-drupal-selector='edit-entities-{$row_selector}']";
    $row = $this->getSession()->getPage()->find('xpath', $xpath);
    $this->assertNotEmpty($row, "Table row with label {$label} not found.");
    return $row;
  }

  /**
   * Load a link, given its entity type ID and its label.
   *
   * @param string $entity_type_id
   *   Entity type ID, for ex. 'footer_link_general'.
   * @param string $label
   *   Link label.
   *
   * @return \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface|\Drupal\oe_corporate_blocks\Entity\FooterLinkSectionInterface|\Drupal\oe_corporate_blocks\Entity\FooterLinkSocialInterface
   *   Link object, if any.
   */
  protected function loadLinkByLabel(string $entity_type_id, string $label): ConfigEntityInterface {
    $this->refreshVariables();
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type_id);
    $id = strtolower(str_replace(' ', '_', $label));
    $link = $storage->load($id);
    if (!$link) {
      throw new \InvalidArgumentException("No link of type {$entity_type_id} with label {$label} has been found.");
    }
    return $link;
  }

}
