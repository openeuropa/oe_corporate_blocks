<?php

declare(strict_types = 1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\oe_corporate_blocks\Traits\AssertFooterLinksTrait;

/**
 * Test CRUD operations on links and section links via the UI.
 */
class FooterLinkManagementTest extends BrowserTestBase {

  use AssertFooterLinksTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'config',
    'system',
    'oe_corporate_blocks',
  ];

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

}
