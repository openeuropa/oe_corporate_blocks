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

    NestedArray::setValue($build, ['#corporate_footer', 'about_ec', 'title'], $config->get('about_ec_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'about_ec', 'items'], $config->get('about_ec_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'social_media', 'title'], $config->get('social_media_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'social_media', 'items'], $config->get('social_media_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'about_eu', 'title'], $config->get('about_eu_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'about_eu', 'items'], $config->get('about_eu_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'bottom_links'], $config->get('bottom_links'));

    $cache->applyTo($build);

    return $build;
  }

}
