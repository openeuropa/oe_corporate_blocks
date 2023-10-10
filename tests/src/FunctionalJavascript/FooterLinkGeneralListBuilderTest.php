<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\FunctionalJavascript;

use Behat\Mink\Element\NodeElement;
use Drupal\Component\Utility\Html;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\oe_corporate_blocks\Entity\FooterLinkGeneralInterface;
use Drupal\Tests\oe_corporate_blocks\Traits\AssertFooterLinksTrait;

/**
 * Test the general footer link overview page.
 */
class FooterLinkGeneralListBuilderTest extends WebDriverTestBase {

  use AssertFooterLinksTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'config',
    'system',
    'oe_corporate_blocks',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Test general footer link drag-and-drop management UI.
   */
  public function testGeneralLinkUi(): void {

    // Create 5 general links.
    foreach (range(1, 5) as $value) {
      $this->createGeneralLink("Link {$value}", $value);
    }

    $user = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/footer_link_general');

    // Move "Link 1" under "Contact us".
    $link = $this->findRowByLabel('Link 1');
    $this->moveRowWithKeyboard($link, 'up', 4);
    $this->assertGeneralLinkRow($link, 'contact_us', -9);

    // Move "Link 2" under "About us".
    $link = $this->findRowByLabel('Link 2');
    $this->moveRowWithKeyboard($link, 'up', 3);
    $this->assertGeneralLinkRow($link, 'about_us', -7);

    // Move "Link 3" under "Related sites".
    $link = $this->findRowByLabel('Link 3');
    $this->moveRowWithKeyboard($link, 'up', 2);
    $this->assertGeneralLinkRow($link, 'related_sites', -5);

    // Move "Link 4" under "Accessibility".
    $link = $this->findRowByLabel('Link 4');
    $this->moveRowWithKeyboard($link, 'up', 1);
    $this->assertGeneralLinkRow($link, 'accessibility', -3);

    // Save current link disposition.
    $this->getSession()->getPage()->pressButton('Save');

    // Assert that sections and weights have actually being saved.
    $this->assertGeneralLinkEntity('Link 1', 'http://example.com/link-1', 'contact_us', -9);
    $this->assertGeneralLinkEntity('Link 2', 'http://example.com/link-2', 'about_us', -7);
    $this->assertGeneralLinkEntity('Link 3', 'http://example.com/link-3', 'related_sites', -5);
    $this->assertGeneralLinkEntity('Link 4', 'http://example.com/link-4', 'accessibility', -3);
    $this->assertGeneralLinkEntity('Link 5', 'http://example.com/link-5', '', -1);

    // Move "Link 3" under "Disabled".
    $link = $this->findRowByLabel('Link 3');
    $this->moveRowWithKeyboard($link, 'down', 3);
    $this->assertGeneralLinkRow($link, '', -2);

    // Save current link disposition.
    $this->getSession()->getPage()->pressButton('Save');

    // Assert that "Link 3" has been correctly saved.
    $this->assertGeneralLinkEntity('Link 3', 'http://example.com/link-3', '', -2);

    // Delete all sections.
    $section_storage = \Drupal::entityTypeManager()->getStorage('footer_link_section');
    $sections = $section_storage->loadMultiple();
    $section_storage->delete($sections);
    $this->getSession()->reload();

    // Assert that all links have been assigned to the "Disabled" section.
    $this->assertGeneralLinkEntity('Link 1', 'http://example.com/link-1', '', -9);
    $this->assertGeneralLinkEntity('Link 2', 'http://example.com/link-2', '', -7);
    $this->assertGeneralLinkEntity('Link 3', 'http://example.com/link-3', '', -2);
    $this->assertGeneralLinkEntity('Link 4', 'http://example.com/link-4', '', -4);
    $this->assertGeneralLinkEntity('Link 5', 'http://example.com/link-5', '', -1);

    // Assert that all link entities contain the correct values.
    $this->assertGeneralLinkRow($this->findRowByLabel('Link 1'), '', -9);
    $this->assertGeneralLinkRow($this->findRowByLabel('Link 2'), '', -7);
    $this->assertGeneralLinkRow($this->findRowByLabel('Link 3'), '', -2);
    $this->assertGeneralLinkRow($this->findRowByLabel('Link 4'), '', -4);
    $this->assertGeneralLinkRow($this->findRowByLabel('Link 5'), '', -1);
  }

  /**
   * Create a general link given its label and weight.
   *
   * @param string $label
   *   The link label.
   * @param int $weight
   *   The link weight.
   *
   * @return \Drupal\oe_corporate_blocks\Entity\FooterLinkGeneralInterface
   *   The general link object.
   */
  protected function createGeneralLink(string $label, int $weight): FooterLinkGeneralInterface {
    $id = strtolower(str_replace(' ', '_', $label));
    $link = \Drupal::entityTypeManager()->getStorage('footer_link_general')->create([
      'id' => $id,
      'label' => $label,
      'url' => 'http://example.com/' . Html::getId($label),
      'section' => '',
      'weight' => $weight,
    ]);
    $link->save();

    return $link;
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
