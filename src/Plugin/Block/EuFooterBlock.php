<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides the corporate EU footer block.
 *
 * @Block(
 *   id = "oe_corporate_blocks_eu_footer",
 *   admin_label = @Translation("EU Footer block"),
 *   category = @Translation("Corporate blocks"),
 * )
 */
class EuFooterBlock extends FooterBlockBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cache = new CacheableMetadata();
    $cache->addCacheContexts(['languages:language_interface']);

    $config = $this->configFactory->get('oe_corporate_blocks.eu_data.footer');
    $cache->addCacheableDependency($config);

    $build['#theme'] = 'oe_corporate_blocks_eu_footer';

    $data = $config->get();
    if ($data['contact']) {
      foreach ($data['contact'] as &$contact_item) {
        $contact_item = [
          '#markup' => $contact_item,
        ];
      }
    }

    NestedArray::setValue($build, ['#corporate_footer'], $data);
    $this->setSiteSpecificFooter($build, $cache);

    $cache->applyTo($build);

    return $build;
  }

}
