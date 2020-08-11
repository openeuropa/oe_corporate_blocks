<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides the corporate footer block.
 *
 * @Block(
 *   id = "oe_corporate_blocks_ec_footer",
 *   admin_label = @Translation("EC Footer block"),
 *   category = @Translation("Corporate blocks"),
 * )
 */
class EcFooterBlock extends FooterBlockBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cache = new CacheableMetadata();
    $cache->addCacheContexts(['languages:language_interface']);

    $config = $this->configFactory->get('oe_corporate_blocks.ec_data.footer');
    $cache->addCacheableDependency($config);

    $build['#theme'] = 'oe_corporate_blocks_ec_footer';

    NestedArray::setValue($build, ['#corporate_footer', 'site_name'], $config->get('site_name'));
    NestedArray::setValue($build, ['#corporate_footer', 'content_owner_details'], $config->get('content_owner_details'));

    NestedArray::setValue($build, ['#corporate_footer', 'class_navigation'], $config->get('class_navigation'));

    NestedArray::setValue($build, ['#corporate_footer', 'service_navigation'], $config->get('service_navigation'));

    NestedArray::setValue($build, ['#corporate_footer', 'legal_navigation'], $config->get('legal_navigation'));

    $this->setSiteSpecificFooter($build, $cache);

    $cache->applyTo($build);

    return $build;
  }

}
