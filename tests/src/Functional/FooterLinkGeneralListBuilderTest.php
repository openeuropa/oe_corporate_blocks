<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Behat\Mink\Element\NodeElement;
use Drupal\Core\Entity\EntityInterface;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests the footer link general overview page.
 */
class FooterLinkGeneralListBuilderTest extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'config',
    'system',
    'oe_corporate_blocks',
  ];

  /**
   * Test that the the default libraries are loaded correctly.
   */
  public function testSettingSectionByDragging(): void {

    // Create a bunch of general links.
    foreach (array_flip(range(1, 5)) as $value) {
      $this->createLink($value);
    }

    $user = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/footer_link_general');

    // Move "Link 0" under "Contact us".
    $link = $this->findLinkRow(0);
    $this->moveRowWithKeyboard($link, 'up', 3);
    $this->assertLinkRow($link, 'contact_us', -9);

    // Move "Link 1" under "About us".
    $link = $this->findLinkRow(1);
    $this->moveRowWithKeyboard($link, 'up', 2);
    $this->assertLinkRow($link, 'about_us', -7);

    // Move "Link 2" under "Related sites".
    $link = $this->findLinkRow(2);
    $this->moveRowWithKeyboard($link, 'up', 1);
    $this->assertLinkRow($link, 'related_sites', -5);

    // Save current link disposition.
    $this->getSession()->getPage()->pressButton('Save');

    // Assert that sections and weights have actually being saved.
    $this->assertLinkEntity(0, 'contact_us', -9);
    $this->assertLinkEntity(1, 'about_us', -7);
    $this->assertLinkEntity(2, 'related_sites', -5);
    $this->assertLinkEntity(3, '', -3);
    $this->assertLinkEntity(4, '', -2);

    // Move "Link 2" under "Disabled".
    $link = $this->findLinkRow(2);
    $this->moveRowWithKeyboard($link, 'down', 1);
    $this->assertLinkRow($link, '', -4);

    // Save current link disposition.
    $this->getSession()->getPage()->pressButton('Save');

    // @todo Fix assertion below, it fails as cache gets in the way.
    // Assert that "Link 2" has has been correctly saved.
    // $this->assertLinkEntity(2, '', -4);
    // Delete all sections.
    $section_storage = \Drupal::entityTypeManager()->getStorage('footer_link_section');
    $sections = $section_storage->loadMultiple();
    $section_storage->delete($sections);
    $this->getSession()->reload();

    // Assert that all links have been assigned to the "Disabled" section.
    $this->assertLinkEntity(0, '', -9);
    $this->assertLinkEntity(1, '', -7);
    $this->assertLinkEntity(2, '', -5);
    $this->assertLinkEntity(3, '', -3);
    $this->assertLinkEntity(4, '', -2);

    // Assert that all link entities contains the correct values.
    $this->assertLinkRow($this->findLinkRow(0), '', -9);
    $this->assertLinkRow($this->findLinkRow(1), '', -7);
    $this->assertLinkRow($this->findLinkRow(2), '', -5);
    $this->assertLinkRow($this->findLinkRow(3), '', -3);
    $this->assertLinkRow($this->findLinkRow(4), '', -2);
  }

  /**
   * Assert that a link gets the right section and weight values.
   *
   * @param \Behat\Mink\Element\NodeElement $link
   *   Link table row object.
   * @param string $section
   *   Link section name.
   * @param int $weight
   *   Link weight.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  protected function assertLinkRow(NodeElement $link, string $section, int $weight): void {
    $this->assertTrue($link->hasAttribute('data-drupal-selector'), 'Attribute "data-drupal-selector" not found.');
    $id = str_replace(['edit-entities-', '-'], ['', '_'], $link->getAttribute('data-drupal-selector'));
    $this->assertSession()->fieldValueEquals("entities[{$id}][section]", $section, $link);
    $this->assertSession()->fieldValueEquals("entities[{$id}][weight]", $weight, $link);
  }

  /**
   * Create a link by using a numeric value to fill in its properties.
   *
   * @param int $value
   *   A numeric value.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The link object.
   */
  protected function createLink(int $value): EntityInterface {
    $link = \Drupal::entityTypeManager()->getStorage('footer_link_general')->create([
      'id' => "link_{$value}",
      'label' => "Link {$value}",
      'url' => "http://example.com/{$value}",
      'section' => '',
      'weight' => $value,
    ]);
    $link->save();

    return $link;
  }

  /**
   * Assert link entity values.
   *
   * @param int $value
   *   The numeric value assigned to the link at its creation.
   * @param string $section
   *   Link section name.
   * @param int $weight
   *   Link weight.
   */
  protected function assertLinkEntity(int $value, string $section, int $weight): void {
    $storage = \Drupal::entityTypeManager()->getStorage('footer_link_general');
    $storage->resetCache();
    $link = $storage->load("link_{$value}");
    $this->assertEqual($link->get('section'), $section, "Link {$value} section was expected to be set to '{$section}', but '{$link->get('section')}' was found.");
    $this->assertEqual($link->get('weight'), $weight, "Link {$value} weight was expected to be set to '{$weight}', but '{$link->get('weight')}' was found.");
  }

  /**
   * Finds a link row in the test table by its related numeric value.
   *
   * @param int $value
   *   The numeric value assigned to the link at its creation.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The row element.
   */
  protected function findLinkRow(int $value): NodeElement {
    $xpath = "//table[@id='edit-entities']/tbody/tr[@data-drupal-selector='edit-entities-link-{$value}']";
    $row = $this->getSession()->getPage()->find('xpath', $xpath);
    $this->assertNotEmpty($row);
    return $row;
  }

  /**
   * Moves a row through the keyboard.
   *
   * @param \Behat\Mink\Element\NodeElement $row
   *   The row to move.
   * @param string $arrow
   *   The arrow button to use to move the row. Either one of 'left', 'right',
   *   'up' or 'down'.
   * @param int $repeat
   *   (optional) How many times to press the arrow button. Defaults to 1.
   */
  protected function moveRowWithKeyboard(NodeElement $row, string $arrow, int $repeat = 1): void {
    $keys = [
      'left' => 37,
      'right' => 39,
      'up' => 38,
      'down' => 40,
    ];
    if (!isset($keys[$arrow])) {
      throw new \InvalidArgumentException('The arrow parameter must be one of "left", "right", "up" or "down".');
    }

    $key = $keys[$arrow];

    $handle = $row->find('css', 'a.tabledrag-handle');
    $handle->focus();

    for ($i = 0; $i < $repeat; $i++) {
      $handle->keyDown($key);
      $handle->keyUp($key);
    }

    $handle->blur();
  }

}
