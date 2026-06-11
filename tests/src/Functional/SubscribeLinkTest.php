<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_corporate_blocks\Functional;

use Drupal\Tests\BrowserTestBase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test footer block rendering of the subscribe for updates link.
 */
class SubscribeLinkTest extends BrowserTestBase {

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
   * Tests the subscribe for updates link rendering in footer blocks.
   */
  public function testSubscribeLinkRendering(): void {
    $entity_type_manager = $this->container->get('entity_type.manager')->getStorage('block');
    $builder = \Drupal::entityTypeManager()->getViewBuilder('block');

    foreach ($this->subscribeLinkRenderingDataProvider() as $index => $data) {
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

        \Drupal::configFactory()
          ->getEditable('oe_corporate_site_info.settings')
          ->delete()
          ->save();
        $builder->resetCache();

        $build = $builder->view($entity, 'block');
        $crawler = new Crawler((string) $this->container->get('renderer')->renderRoot($build));

        $subscribe_link = $crawler->filter($data['selector']);
        $this->assertCount(0, $subscribe_link);

        \Drupal::configFactory()
          ->getEditable('oe_corporate_site_info.settings')
          ->set('subscribe', 'https://example.com/subscribe')
          ->save();
        $builder->resetCache();

        $build = $builder->view($entity, 'block');
        $crawler = new Crawler((string) $this->container->get('renderer')->renderRoot($build));

        $subscribe_link = $crawler->filter($data['selector']);
        $this->assertCount(1, $subscribe_link);
        $this->assertEquals('Subscribe for updates', $subscribe_link->text());
      }
      catch (\Exception $e) {
        throw new \Exception(sprintf('Failed asserting data for index %s.', $index), 0, $e);
      }
    }
  }

  /**
   * Provides data for testSubscribeLinkRendering().
   *
   * @return \Generator
   *   The test data.
   */
  protected function subscribeLinkRenderingDataProvider() {
    yield [
      'plugin' => 'oe_corporate_blocks_ec_footer',
      'selector' => 'a[href="https://example.com/subscribe"]',
    ];
    yield [
      'plugin' => 'oe_corporate_blocks_eu_footer',
      'selector' => 'a[href="https://example.com/subscribe"]',
    ];
  }

}
