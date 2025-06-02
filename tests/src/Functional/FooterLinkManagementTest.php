<?php

declare(strict_types=1);

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

  /**
   * Tests the social media footer links settings form.
   */
  public function testSocialMediaFooterLinksSettingsForm(): void {
    // Login with a user that can create social media footer links.
    $user = $this->createUser([
      'access administration pages',
      'administer site specific footer links',
    ]);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/footer_link_social');

    // Assert the settings form content and the default values.
    $this->assertSession()->checkboxNotChecked('Display labels');
    $this->assertSession()->pageTextContains("Check this box if you'd like to display the social media links labels.");
    $this->assertSession()->selectExists('Alignment');
    $this->assertSession()->pageTextContains('The alignment of the social media links.');
    $this->assertSession()->fieldValueEquals('Alignment', 'horizontal');

    // Change the values and assert the config is correctly updated.
    $this->getSession()->getPage()->checkField('Display labels');
    $this->getSession()->getPage()->selectFieldOption('Alignment', 'vertical');
    $this->getSession()->getPage()->pressButton('Save configuration');

    $this->assertSession()->pageTextContains('The configuration options have been saved.');
    $this->assertSession()->checkboxChecked('Display labels');
    $this->assertSession()->fieldValueEquals('Alignment', 'vertical');
  }

}
