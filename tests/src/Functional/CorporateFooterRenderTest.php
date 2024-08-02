<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Drupal\Tests\BrowserTestBase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test footer block rendering.
 */
class CorporateFooterRenderTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'oe_corporate_blocks',
    'oe_corporate_site_info',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    \Drupal::configFactory()
      ->getEditable('oe_corporate_site_info.settings')
      ->set('accessibility', 'https://example.com/accessibility')
      ->save();
  }

  /**
   * Tests EC footer block rendering.
   */
  public function testEcFooterBlockRendering(): void {
    $entity_type_manager = $this->container
      ->get('entity_type.manager')
      ->getStorage('block');
    $entity = $entity_type_manager->create([
      'id' => 'ecfooterblock',
      'theme' => 'stark',
      'plugin' => 'oe_corporate_blocks_ec_footer',
      'settings' => [
        'id' => 'oe_corporate_blocks_ec_footer',
        'label' => 'EC Footer block',
        'provider' => 'oe_corporate_blocks',
        'label_display' => '0',
      ],
    ]);
    $entity->save();
    $builder = \Drupal::entityTypeManager()->getViewBuilder('block');
    $build = $builder->view($entity, 'block');
    $render = $this->container->get('renderer')->renderRoot($build);
    $crawler = new Crawler($render->__toString());

    $accessibilityLink = $crawler->filter('a[href="https://example.com/accessibility"]');
    $this->assertCount(1, $accessibilityLink);
  }

  /**
   * Tests EU footer block rendering.
   */
  public function testEuFooterBlockRendering(): void {
    $entity_type_manager = $this->container
      ->get('entity_type.manager')
      ->getStorage('block');
    $entity = $entity_type_manager->create([
      'id' => 'eufooterblock',
      'theme' => 'stark',
      'plugin' => 'oe_corporate_blocks_eu_footer',
      'settings' => [
        'id' => 'oe_corporate_blocks_eu_footer',
        'label' => 'EU Footer block',
        'provider' => 'oe_corporate_blocks',
        'label_display' => '0',
      ],
    ]);
    $entity->save();
    $builder = \Drupal::entityTypeManager()->getViewBuilder('block');
    $build = $builder->view($entity, 'block');
    $render = $this->container->get('renderer')->renderRoot($build);
    $crawler = new Crawler($render->__toString());

    $accessibilityLink = $crawler->filter('a[href="https://example.com/accessibility"]');
    $this->assertCount(1, $accessibilityLink);
  }

}
