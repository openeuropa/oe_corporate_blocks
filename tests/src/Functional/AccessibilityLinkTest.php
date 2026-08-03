<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Drupal\Tests\BrowserTestBase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test footer block rendering.
 */
class AccessibilityLinkTest extends BrowserTestBase {

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
   * Tests EC footer block rendering.
   */
  public function testAccessibilityLinkRendering(): void {
    $entity_type_manager = $this->container
      ->get('entity_type.manager')
      ->getStorage('block');
    $builder = \Drupal::entityTypeManager()->getViewBuilder('block');

    foreach ($this->accessibilityLinkRenderingDataProvider() as $index => $data) {
      try {
        $entity = $entity_type_manager->create([
          'id' => 'footerblock-' . $index,
          'theme' => 'stark',
          'plugin' => $data['plugin'],
          'settings' => [
            'id' => $data['plugin'],
            'label' => 'Footer block',
            'provider' => 'oe_corporate_blocks',
            'label_display' => '0',
          ],
        ]);
        $entity->save();

        // Empty the value instead of deleting the config: a deleted config
        // saves as "new", and Drupal 11.4 skips cache tag invalidation for new
        // config, leaving the render cache stale.
        \Drupal::configFactory()
          ->getEditable('oe_corporate_site_info.settings')
          ->set('accessibility', '')
          ->save();
        $builder->resetCache();

        $build = $builder->view($entity, 'block');
        $crawler = new Crawler((string) $this->container->get('renderer')->renderRoot($build));

        $accessibility_link = $crawler->filter($data['selector']);
        $this->assertCount(0, $accessibility_link);

        \Drupal::configFactory()
          ->getEditable('oe_corporate_site_info.settings')
          ->set('accessibility', 'https://example.com/accessibility')
          ->save();
        $builder->resetCache();

        $build = $builder->view($entity, 'block');
        $crawler = new Crawler((string) $this->container->get('renderer')->renderRoot($build));

        $accessibility_link = $crawler->filter($data['selector']);
        $this->assertCount(1, $accessibility_link);
        $this->assertEquals('Accessibility', $accessibility_link->text());
      }
      catch (\Exception $e) {
        throw new \Exception(sprintf('Failed asserting data for index %s.', $index), 0, $e);
      }
    }
  }

  /**
   * Provides data for testAccessibilityLinkRendering().
   *
   * @return \Generator
   *   The test data.
   */
  protected function accessibilityLinkRenderingDataProvider() {
    yield [
      'plugin' => 'oe_corporate_blocks_ec_footer',
      'selector' => 'a[href="https://example.com/accessibility"]',
    ];
    yield [
      'plugin' => 'oe_corporate_blocks_eu_footer',
      'selector' => 'a[href="https://example.com/accessibility"]',
    ];
  }

}
